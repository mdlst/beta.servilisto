<?php

/**
 * Registers a new post type event
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string  See optional args description above.
 * @return object|WP_Error the registered post type object, or an error object
 */
function de_register_events() {
    
    $labels = array(
        'name' => __('Events', ET_DOMAIN) ,
        'singular_name' => __('event', ET_DOMAIN) ,
        'add_new' => _x('Add New event', ET_DOMAIN, ET_DOMAIN) ,
        'add_new_item' => __('Add New event', ET_DOMAIN) ,
        'edit_item' => __('Edit event', ET_DOMAIN) ,
        'new_item' => __('New event', ET_DOMAIN) ,
        'view_item' => __('View event', ET_DOMAIN) ,
        'search_items' => __('Search Events', ET_DOMAIN) ,
        'not_found' => __('No Events found', ET_DOMAIN) ,
        'not_found_in_trash' => __('No Events found in Trash', ET_DOMAIN) ,
        'parent_item_colon' => __('Parent event:', ET_DOMAIN) ,
        'menu_name' => __('Events', ET_DOMAIN) ,
    );
    
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => '',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        // 'hierarchical' => true,
        'supports' => array(
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'trackbacks',
            'comments',
            'revisions',
            'page-attributes',
            'post-formats'
        )
    );
    
    register_post_type('event', $args);
    
    global $ae_post_factory;
    
    $ae_post_factory->set('event', new AE_Posts('event', array() , array(
        'ribbon',
        'open_time',
        'close_time'
    )));
}

add_action('init', 'de_register_events');

/**
 * class DE_EventAction control all event action
 * @author Dakachi
 * @package DirectoryEngine
*/
class DE_EventAction extends AE_Base{
    /**
     * construct DE_EventAction
    */
	function __construct(){

		$this->post_type = 'event';
        /**
         * sync event 
         * - create
         * - update
        */
		$this->add_ajax( 'ae-sync-event', 'sync_event' );

        $this->add_ajax( 'ae-fetch-events', 'fetch_posts');
        /**
         * check permission create event
        */
        $this->add_ajax( 'ae-check-event', 'request_create_event' );
        /**
         * add action to insert meta data for place after insert an event
        */
        $this->add_action( 'ae_insert_event', 'after_insert_event', 10, 2 );
        /**
         * filter event link in backend
        */
		$this->add_filter('post_type_link', 'post_link', 10 , 2);
        /**
         * filter place data and add event ribbon
        */
        $this->add_filter( 'ae_convert_place', 'add_ribbon' );

        /**
         * catch event change status event, update place ribbon
         */
        $this->add_action('transition_post_status', 'change_post_status', 10, 3);

        $this->add_filter( 'ae_convert_event', 'convert_event' );
	}
    /**
     * ajax callback fetch post
     * @author ThanhTu
     * @version 1.0
     */
    function fetch_posts(){
        global $ae_post_factory;
        $attachment = $ae_post_factory->get($this->post_type);
        $page = 1;
        if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' ){
            $page = $_REQUEST['page'];
        }
        extract($_REQUEST);        
        
        /** @var Array $query */
        $query_args = array(
            'paged' => $page,
            'showposts' => $query['showposts']
        );

        $query_args = wp_parse_args($query_args, $query);
        /**
         * fetch data
         */
        $data = $attachment->fetch($query_args);

        // get the pagination html string
        ob_start();
        ae_pagination($data['query'], $page, $_REQUEST['paginate']);
        $paginate = ob_get_clean();
        
        /**
         * send data to client
         */
        if (!empty($data)) {
            wp_send_json(array(
                'data' => $data['posts'],
                'paginate' => $paginate,
                'msg' => __("Successs", ET_DOMAIN) ,
                'success' => true,
                'max_num_pages' => $data['max_num_pages'],
                // 'status' => $status,
                'total' => $data['query']->found_posts
            ));
        } else {
            wp_send_json(array(
                'success' => false
            ));
        }
    }

    /**
     * catch filter ae_convert_event to convert event data
     * @param array $result
     * @return mixed
     */
    function convert_event( $result ){
        global $user_ID, $ae_post_factory;
        $open_time = date_i18n( get_option( 'date_format' ), strtotime($result->open_time) );
        $close_time = date_i18n( get_option( 'date_format' ), strtotime($result->close_time) );
        $result->event_time = sprintf(__("%s to %s", ET_DOMAIN), $open_time , $close_time);
        if (ae_user_can('edit_others_posts') || $user_ID == $result->post_author) {
            $result->large_thumbnail = wp_get_attachment_image_src( $result->featured_image, 'large');
            $result->large_thumbnail = $result->large_thumbnail[0];
        }
        $result->post_content_trim = wp_trim_words($result->post_content,'50');
        $post = get_post($result->post_parent);
        $place_obj = $ae_post_factory->get('place');
        $place = $place_obj->convert($post);
        $result->post_data = $place;
        return $result;
    }

    /**
     * filter event link and change it to place link
     * @param $url
     * @param $post
     * @return false|string
     */
	public function post_link($url, $post){
		if($post->post_type == 'event' && $post->post_parent != '') {
			return get_permalink( $post->post_parent );
		}
		return $url;
	}

	/**
	 * 
	*/
	public function sync_event(){
		$request = $_REQUEST;
        global $ae_post_factory, $user_ID;

		if (isset($request['archive'])) {
            $request['post_status'] = 'archive';
        }
        if (isset($request['publish'])) {
            $request['post_status'] = 'publish';
        }
        if (isset($request['delete'])) {
            $request['post_status'] = 'trash';
        }

        /**
         * account pending
        */
		if(!AE_Users::is_activate($user_ID)) {
            wp_send_json( array('success' => false  , 'msg' => __("Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN)) );
        };

        /**
         * an event have to bind with a listing
        */
        if(!isset($request['post_parent'])) {
            wp_send_json( array('success' => false , 'msg' => __("You have to add event for a listing.", ET_DOMAIN)) );
        }

        /**
         * check user authentication
        */
        if( !current_user_can( 'edit_other_posts') && !isset($request['ID']) ) {
            $check = $this->check_create_event($request);
            if(!$check['success']) wp_send_json( $check );
        }

        /**
         * create new event
        */
        if(!isset($request['ID'])){
            $pending = ae_get_option('use_pending', false);
        
            if($pending){
                $request['post_status'] = 'pending';    
            }else {
                $request['post_status'] = 'publish';    
            }    
        }        

        $post_object = $ae_post_factory->get($this->post_type);

        // sync place
        $result = $post_object->sync($request);
        if(!is_wp_error($result)) {
        	/**
             * featured image not null and should be in carousels array data
             */
            if (isset($request['featured_image'])) {
                set_post_thumbnail($result->ID, $request['featured_image']);
            }
            /**
             * update event expired date
            */
            $expired_date = date('Y-m-d h:i:s', strtotime( $request['close_time'] ));
            update_post_meta( $result->ID, 'et_expired_date', $expired_date );
             /**
             * send email to admin
            */
            $et_mailing   =   DE_Mailing::get_instance(); 
            $et_mailing->send_event_place_mail($result);

            wp_send_json(array(
                'success' => true,
                'data' => $result,
                'msg' => (isset($request['ID'])) ? __("Update event successful!", ET_DOMAIN) : __("Create event successfull!", ET_DOMAIN)
            ));

        }else{
        	// update false
            wp_send_json(array(
                'success' => false,
                'data' => $result,
                'msg' => $result->get_error_message()
            ));
        }
	}

    /**
     * catch ajax when user request to create a event
    */
    public function request_create_event(){
        if(!current_user_can('edit_others_posts' )) {
            $check = $this->check_create_event($_REQUEST);
            wp_send_json( $check );
        }        
        wp_send_json_success();
    }

    /**
     * check user can create event and send json to client
     * @param $request
     * @author Dakachi
     * @return array
     */
    function check_create_event($request){
        global $user_ID, $ae_post_factory;
        $post_parent = get_post($request['post_parent']);
        if(!is_user_logged_in()) {
            return ( array('success' =>  false , 'msg' => __("You have to login to create event.", ET_DOMAIN)) );       
        }
        
        if(!current_user_can('edit_others_posts') && $user_ID != $post_parent->post_author){
            return ( array('success' =>  false , 'msg' => __("Permission denied.", ET_DOMAIN)) );       
        }

        /**
         * get publish event and count
        */
        $publish_event = get_posts( array('post_parent' => $post_parent->ID, 'post_type'=> 'event', 'post_status' => 'publish') );
        /**
         * check free to submit post or not
        */
        $disable_plan    = ae_get_option('disable_plan');
        if($disable_plan){ // disable payment plan
            // maximum number of event can post each listing
            $number_event = ae_get_option('number_event', 1);
            /**
             * check
            */
            if($number_event <= count($publish_event)) {
                return ( array( 'success' => false  , 'msg' => __("You have reached the maximum number of event posts.", ET_DOMAIN)) ); 
            }

        }else{
            /**
             * check package with event
            */
            $sku = get_post_meta( $post_parent->ID , 'et_payment_package', true);
            $pack = $ae_post_factory->get('pack');
            $package = $pack->get($sku);
            /**
             * return message if reach the limit
            */
            if(!isset($package->number_event) || $package->number_event <= count($publish_event)) {
                return ( array( 'success' => false  , 'msg' => __("You have reached the maximum number of event posts.", ET_DOMAIN)) ); 
            }
        }

        return array('success' => true);

    }

    /**
     * update place data after add an event
     * @param Object $result Wp_post object
     * @param Array $args
     * @author Dakachi
     * @return Object
     */
    public function after_insert_event($result, $args){
        if( isset($args['post_parent']) && $args['post_parent'] ) {
            // update_post_meta( $args['post_parent'], 'de_event_post', $result );
        }
        return $result;
    }

    /**
     * add ribbon data to place after convert
     * @param $place
     * @return
     */
    public function add_ribbon($place){
        $place->ribbon = '';
        $event_id = get_post_meta( $place->ID, 'de_event_post', true );
        if($event_id) {
            $event = get_post($event_id);
            if($event) {
                $place->ribbon = get_post_meta( $event_id, 'ribbon', true );    
            }
        }        
        return $place;
    }

    /**
     * catch action change event status to archive remove post parent ribbon
     * @param $new_status
     * @param $old_status
     * @param $post
     */
    public function change_post_status ($new_status, $old_status, $post) {
        // not is post type controled
        if($post->post_type != $this->post_type) return;
        if($new_status != 'publish') {
            delete_post_meta( $post->post_parent, 'de_event_post', $post->ID );
        }
        if($new_status == 'publish') {
            update_post_meta( $post->post_parent, 'de_event_post', $post->ID );
        }
    }
}

