<?php

/**
 * place review class
 */
class DE_Multi_Review extends AE_Comments
{
    static $current_review;
    static $instance;

    /**
     * return class $instance
     */
    public static function get_instance() {
        if (self::$instance == null) {

            self::$instance = new DE_Multi_Review();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->comment_type = 'review';
        $this->meta = array(
            'et_multi_rate'
        );

        $this->post_arr = array();
        $this->author_arr = array();

        $this->duplicate = true;
        $this->limit_time = 120;
    }
}

/**
 * Class DE_Multi_ReviewAction
 */
class DE_Multi_ReviewAction extends AE_Base
{

    public function __construct() {
        //
        $this->add_action('preprocess_comment', 'process_review');

        $this->add_action('wp_insert_comment', 'update_post_rating', 12, 2);

        $this->add_action('wp_insert_comment', 'send_mail', 11, 2);

        $this->add_action('trashed_comment', 'trash_comment', 12, 2);

        $this->add_action('untrashed_comment', 'untrash_comment', 12, 2);

        $this->add_action('spammed_comment','trash_comment',12, 2);

        $this->add_action('unspammed_comment','untrash_comment',12, 2);

        $this->add_action('transition_comment_status', 'my_approve_comment_callback', 12, 3);

        $this->init_ajax();
    }

    function init_ajax() {

        $ce_priv_event = array(
            'ae-review-sync'
        );

        $this->add_ajax('ae-fetch-comments', 'fetch_comments', true, true);

        foreach ($ce_priv_event as $key => $value) {
            $function = str_replace('ae-', '', $value);
            $function = str_replace('-', '_', $function);
            $this->add_ajax($value, $function, true, false);
        }
    }

    /**
     * filter comment before new to check comment post ID and set comment type to review
     * @author Dakachi
     * @param Array $commentdata the array of comment data
     * @return Array comment
     */
    function process_review($commentdata) {
        global $user_ID, $current_user;

        $post = get_post($commentdata['comment_post_ID']);

        // comment on place
        if ($post->post_type == 'place') {
            if (isset($_POST['score']) || isset($_POST['comment_parent'])) {
                // if have post score update comment to review
                $commentdata['comment_type'] = 'review';

                /**
                 * die if user not login and try to submit review
                 */
                if (!$commentdata['comment_parent'] && !$user_ID) {
                    wp_die(__('You have to login to post review.', ET_DOMAIN));
                }
            }
        }
        $rate_score = 0;
        if (isset($_POST['score']) && array_filter($_POST['score']) != null) {
            $rate_score = (int)$_POST['score'];
        }

        if ($rate_score > 0) {
            /*
             $args = array(
                 'author_email' =>$current_user->data->user_email,
                 'post_id'=>$post->ID,
                 'comment_type'=>'review'
             );
             $user_comments = get_comments($args);
             $flag = 0;
             if ($user_comments) {
                 foreach ($user_comments as $key => $value) {
                     $rate = get_comment_meta($value->comment_ID, 'et_multi_rate', true);
                     $rate = (int)$rate;
                     if ($rate) {
                         $flag = 1;
                         break;
                     }
                 }
             }
             if ($flag == 1) {
                 //$flag = 0;
                 wp_die(__("You can only rate a place for one time.", ET_DOMAIN));
             }*/
        }
        $time = 0;
        // review comment not too fast, should after 3 or 5 minute to post next review
        $comments = get_comments(array(
            'comment_type' => '',
            'post_id'=> $post->ID,
            'author_email' => $current_user->user_email,
            'number' => 1
        ));

        if (!empty($comments)) {
            // check latest comment
            $comment = $comments[0];
            $date = $comment->comment_date_gmt;
            $ago = time() - strtotime($date);
            $review = DE_Multi_Review::get_instance();
            if (isset($review->limit_time)) {
                $time =  $review->limit_time;
            }
            //return error if comment to fast
            if ($ago < ((int)$time)) wp_die(__("Please wait 2 minutes after each action submission.", ET_DOMAIN));
        }



        return $commentdata;
    }

    /**
     * catch hook wp_insert_comment to send mail
     * @param int $comment_id
     * @param $comment
     * @author ThanhTu
     */

    function send_mail($comment_id, $comment){
        global $wpdb, $current_user;
        $post_id = $comment->comment_post_ID;
        $post = get_post($post_id);
        $user_data = get_userdata($post->post_author);

        if($post->post_type == 'place' && $comment->comment_type == 'review'){

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= "From: ".get_option('blogname')." < ".get_option('admin_email') ."> \r\n";
            // Subject
            $subject = sprintf(__('Tu anuncio tiene una nueva opinión!', ET_DOMAIN));

            // Content Mail
            $content = ae_get_option('ae_comment_place_mail');
            $content = str_ireplace('[display_name]', $user_data->display_name , $content);
            $content = str_ireplace('[place_link]', get_permalink($post->ID) , $content);
            $content = str_ireplace('[place_title]', $post->post_title , $content);
            $content = str_ireplace('[comment_author]', $comment->comment_author , $content);
            $content = str_ireplace('[comment_author_email]', $comment->comment_author_email , $content);
            $content = str_ireplace('[comment_date]', $comment->comment_date , $content);
            $content = str_ireplace('[comment_message]', $comment->comment_content , $content);
            $content = str_ireplace('[comment_link]', get_comment_link($comment->comment_ID), $content);
            return wp_mail($user_data->user_email, $subject, $content, $headers );
        }
    }

    /**
     * catch hook wp_insert_comment to update rating
     * @param  int $comment_id
     * @param  $comment
     * @since  1.0
     * @author Dakachi
     */
    function update_post_rating($comment_id, $comment) {
        $rating = new Critical_category();
        global $wpdb, $post,$ae_post_factory;
        $post_id = $comment->comment_post_ID;
        $post = get_post($post_id);
        if ($post->post_type == 'place') {
            // get AE place object
            $place_obj = $ae_post_factory->get('place');
            $place = $place_obj->convert($post);
            /*echo "<pre>";
            print_r($place);exit;*/
            $rvc_arr = array();
            $rvc_arr = $place->tax_input['review_criteria'];

            // check comment rating post and update comment meta
            if (isset($_POST['score']) && $_POST['score']) {
                $rate = (array)$_POST['score'];
                $flag1 = 1;
                foreach ($_POST['score'] as $key => $value) {
                    if ($value!= '' && (int)$value != 0) {
                        $flag1 = 0;
                    }
                }

                foreach ($_POST['score'] as $key => $value) {
                    if ($flag1 == 0) {
                        if ($value === '' || (float)$value === 0) {
                            wp_die(__("Please rating for all criteria.", ET_DOMAIN));
                            return;
                        }
                    }
                }
                $rate = array_nice_key($rate);
                $is_update = de_multirating_is_review($rate);
                if ($is_update) {
                    update_comment_meta($comment_id, 'et_multi_rate', $rate);
                }
                // tinh tong diem review cua tung comment de phong truong hop khach hang tat multirating
                update_comment_meta($comment_id, 'et_rate', array_sum($rate)/count($rate));
            }

            //if(!get_option( 'have_sync_multi_rating', false )) {
            //sync old reviews data to de_multirating data
            if (!isset($place->multi_overview_score) || (int)$place->multi_overview_score <= 0) {
                // update post rating score
                $sql1 = "SELECT M.meta_value as rate_point, M.comment_ID as comment_id
                            FROM $wpdb->comments as C
                                join $wpdb->commentmeta as M
                                on C.comment_ID = M.comment_id
                                    and M.meta_key = 'et_rate'
                                    and C.comment_post_ID = $post_id
                                    and C.comment_approved = 1
                            WHERE M.meta_value > 1";
                $results1 = $wpdb->get_results($sql1);
                foreach ($results1 as $key => $value) {
                    $result_arr = array();
                    foreach ($rvc_arr as $rvc_arr_key => $rvc_arr_value) {
                        $result_arr[$rvc_arr_value->name] = $value->rate_point;
                    }

                    $multi = get_comment_meta((int)$value->comment_id, 'et_multi_rate', true);
                    if (!$multi) {
                        update_comment_meta((int)$value->comment_id, 'et_multi_rate', $result_arr);
                    }
                }
                update_option('have_sync_multi_rating', 1);
            }
            //}
            // update post rating score
            $sql = "SELECT M.meta_value  as rate_point
                        FROM $wpdb->comments as C
                            join $wpdb->commentmeta as M
                            ON C.comment_ID = M.comment_id
                        WHERE   M.meta_key = 'et_multi_rate'
                                AND C.comment_post_ID = $post_id
                                AND C.comment_approved = 1";
            $results = $wpdb->get_results($sql);
            $meta_array = array();
            $count_multi_review = 0;
            foreach ($results as $key => $value) {
                if (isSerialized($value->rate_point)) {
                    foreach (unserialize($value->rate_point) as $criteria => $criteria_value) {
                        if (!isset($meta_array[$criteria])) {
                            $meta_array[$criteria] = 0;
                        }
                        $meta_array[$criteria] += (float)$criteria_value;
                    }
                    $count_multi_review++;
                }
            }
            $sum = 0;
            $overview = 0;
            if (!empty($meta_array)) {
                foreach ($meta_array as $key => $value) {
                    $meta_array[$key] = $value/$count_multi_review;
                    $sum+= $meta_array[$key];
                }
                $overview = round($sum/count($meta_array), 1);
            }
            // update post rating score
            update_post_meta($post_id, 'multi_overview_score', $overview);
            // update post rating_score
            update_post_meta($post_id, 'rating_score', $overview);
            update_post_meta($post_id, 'multi_rating_score', $meta_array);

            // post review count
            update_post_meta($post_id, 'multi_reviews_count',  $count_multi_review);
            update_post_meta($post_id, 'reviews_count',  $count_multi_review);

            $enable_critical = ae_get_option('enable_critical');
            //$rating->calc_overating($post, $enable_critical, true);
            $rating->calc_overating($place, $enable_critical, true);


        }
    }

    /**
     * approve comment callback
     * @param $new_status
     * @param $old_status
     * @param $comment
     */
    function my_approve_comment_callback($new_status, $old_status, $comment) {
        if ($old_status != $new_status) {
            if ($new_status == 'approved') {
                $this->update_post_rating($comment->comment_ID, $comment);
            }
            if ($new_status == 'unapproved') {
                $this->trash_comment($comment->comment_ID);
            }
        }
    }

    /**
     * sync review (create)
     */
    function review_sync() {
        //global $user_ID, $current_user;
        $args = $_POST['content'];

        /**
         * validate data
         */
        if (empty($args['comment_content']) || empty($args['comment_post_ID'])) {
            wp_send_json(array(
                    'success' => false,
                    'msg' => __("Please fill in required field.", ET_DOMAIN)
                )
            );
        }

        $review = DE_Multi_Review::get_instance();
        $comment = $review->insert($args);

        if (!is_wp_error($comment)) {
            wp_send_json(array(
                    'success' => true,
                    'msg' => __("Your review has been submitted.", ET_DOMAIN)
                )
            );
        } else {
            wp_send_json(array(
                    'success' => false,
                    'msg' => $comment->get_error_message()
                )
            );
        }
    }

    /**
     * fetch comment
     */
    function fetch_comments() {

        global $ae_post_factory;
        $review_object = $ae_post_factory->get('de_review');
        // get review object

        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 2;
        $query = $_REQUEST['query'];

        /*$map = array(
             'status' => 'approve',
             'meta_key' => 'et_multi_rate',
             'type' => 'review',
             'post_type' => 'place',
             'number' => '4',
             'total' => '10'
         );
         */
        $query['page'] = $page;

        //add_filter( 'comments_clauses' , array($this, 'groupby') );
        $data = $review_object->fetch($query);
        if (!empty($data)) {
            $data['success'] = true;
            wp_send_json($data);
        } else {
            /**
             * return false if empty data
             */
            wp_send_json(array(
                'success' => false,
                'data' => $data
            ));
        }
    }

    function groupby( $args ){
        global $wpdb;
        $args['groupby'] = ' ' .$wpdb->comments.'.comment_post_ID';
        return $args;
    }

    /**
     * @param $comment_id
     */
    function trash_comment($comment_id)
    {
        $enable_critical = ae_get_option('enable_critical');
        $comment = get_comment($comment_id);
        $post_id = $comment->comment_post_ID;
        /*$place = get_post($post_id);
        $meta_values = get_post_meta( $post_id);*/
        global $wpdb, $ae_post_factory;
        $post = get_post($post_id);
        $place_obj = $ae_post_factory->get('place');
        $place = $place_obj->convert($post);
        $term_list = wp_get_object_terms($place->ID, 'review_criteria');

        $mask = array();
        if($enable_critical) {
            if ($place->de_critical_cate) {
                $critical_category = Critical_category::get_critical_options($place->de_critical_cate);
            } else {
                $critical_category = Critical_category::get_critical_options($place->place_category[0]);
            }
            //get name of critical
            if ($critical_category != 1 && $critical_category != null) {
                $term_name = Critical_category::critical_name($critical_category);
                $mask = $term_name;
            }
        }else{
            foreach ($term_list as $key => $value) {
                array_push($mask, $value->name);
            }
        }

        // update post rating score
        $sql = "SELECT M.meta_value  as rate_point
                    FROM $wpdb->comments as C
                        join $wpdb->commentmeta as M
                        ON C.comment_ID = M.comment_id
                    WHERE   M.meta_key = 'et_multi_rate'
                            AND C.comment_post_ID = $post_id
                            AND C.comment_approved = 1";
        $results = $wpdb->get_results($sql);

        $meta_array = array();
        $count_multi_review = 0;
        foreach ($results as $key => $value) {
            $rate_point = unserialize($value->rate_point);
            if (isSerialized($value->rate_point) && count($rate_point) > 0) {
                foreach (unserialize($value->rate_point) as $criteria => $criteria_value) {

                    if (!isset($meta_array[$criteria] )) {
                        $meta_array[$criteria] = 0;
                    }
                    $meta_array[$criteria] += (float)$criteria_value;
                }
                $count_multi_review++;
            }
        }
        $sum = 0;
        $array_multi_rating_score = array();

        if (!empty($meta_array)) {
            foreach ($meta_array as $key => $value) {
                $meta_array[$key] = $value/$count_multi_review;
            }
        }

        foreach ($mask as $key => $value) {
            $sum += isset($meta_array[$value]) ? $meta_array[$value] : 0;
            $array_multi_rating_score[$value] = isset($meta_array[$value]) ? $meta_array[$value] : 0;
        }

        if ($sum != 0) {
            $overview = round($sum/count($mask), 1);
        } else {
            $overview = 0;
        }

        // update post rating score
        update_post_meta($post_id, 'multi_overview_score', $overview);
        // update post rating_score
        update_post_meta($post_id, 'rating_score', $overview);
        update_post_meta($post_id, 'multi_rating_score', $array_multi_rating_score);
        // post review count
        update_post_meta($post_id, 'multi_reviews_count',  $count_multi_review);
        update_post_meta($post_id, 'reviews_count',  $count_multi_review);

        // $enable_critical = ae_get_option('enable_critical');
        // $rating->calc_overating($post, $enable_critical, true);
    }

    function untrash_comment($comment_id)
    {
        $enable_critical = ae_get_option('enable_critical');
        $comment = get_comment($comment_id);
        $post_id = $comment->comment_post_ID;
        /*$place = get_post($post_id);
        $meta_values = get_post_meta( $post_id);*/
        global $wpdb, $ae_post_factory;
        $post = get_post($post_id);
        $place_obj = $ae_post_factory->get('place');
        $place = $place_obj->convert($post);
        $term_list = wp_get_object_terms($place->ID, 'review_criteria');

        $mask = array();
        if($enable_critical) {
            if ($place->de_critical_cate) {
                $critical_category = Critical_category::get_critical_options($place->de_critical_cate);
            } else {
                $critical_category = Critical_category::get_critical_options($place->place_category[0]);
            }
            //get name of critical
            if ($critical_category != 1 && $critical_category != null) {
                $term_name = Critical_category::critical_name($critical_category);
                $mask = $term_name;
            }
        }else{
            foreach ($term_list as $key => $value) {
                array_push($mask, $value->name);
            }
        }

        // update post rating score
        $sql = "SELECT M.meta_value  as rate_point
                    FROM $wpdb->comments as C
                        join $wpdb->commentmeta as M
                        ON C.comment_ID = M.comment_id
                    WHERE   M.meta_key = 'et_multi_rate'
                            AND C.comment_post_ID = $post_id
                            AND C.comment_approved = 1";
        $results = $wpdb->get_results($sql);

        $meta_array = array();
        $count_multi_review = 0;
        foreach ($results as $key => $value) {
            $rate_point = unserialize($value->rate_point);
            if (isSerialized($value->rate_point) && count($rate_point) > 0) {
                foreach (unserialize($value->rate_point) as $criteria => $criteria_value) {

                    if (!isset($meta_array[$criteria] )) {
                        $meta_array[$criteria] = 0;
                    }
                    $meta_array[$criteria] += (float)$criteria_value;
                }
                $count_multi_review++;
            }
        }
        $sum = 0;
        $array_multi_rating_score = array();

        if (!empty($meta_array)) {
            foreach ($meta_array as $key => $value) {
                $meta_array[$key] = $value/$count_multi_review;
            }
        }

        foreach ($mask as $key => $value) {
            $sum += isset($meta_array[$value]) ? $meta_array[$value] : 0;
            $array_multi_rating_score[$value] = isset($meta_array[$value]) ? $meta_array[$value] : 0;
        }

        if ($sum != 0) {
            $overview = round($sum/count($mask), 1);
        } else {
            $overview = 0;
        }

        // update post rating score
        update_post_meta($post_id, 'multi_overview_score', $overview);
        // update post rating_score
        update_post_meta($post_id, 'rating_score', $overview);
        update_post_meta($post_id, 'multi_rating_score', $array_multi_rating_score);
        // post review count
        update_post_meta($post_id, 'multi_reviews_count',  $count_multi_review);
        update_post_meta($post_id, 'reviews_count',  $count_multi_review);

    }
}
new DE_Multi_ReviewAction();

/**
 * class AE_Favorite
 * declare favorite data and config
 * @author Dakachi
 */
class AE_Favorite extends AE_Comments
{
    /**
     * return class $instance
     */
    public static $instance;

    /**
     * @return AE_Favorite
     */
    public static function get_instance() {
        if (self::$instance == null) {

            self::$instance = new AE_Favorite();
        }
        return self::$instance;
    }

    /**
     * construct AE_Favorite
     */
    public function __construct() {
        $this->comment_type = 'favorite';
        $this->meta = array();

        $this->post_arr = array();
        $this->author_arr = array();
        // not allow duplicate, user just can post one favorite (comment) on a post
        $this->duplicate = false;
        // set limit time for each submision post
        $this->limit_time = 120;
    }
}

/**
 * class AE_FavoriteAction init all action work with class AE_Favorite
 * @author Dakachi
 * @version 1.0
 */
class AE_FavoriteAction extends AE_Base
{
    /**
     * construct AE_FavoriteAction
     */
    public function __construct() {
        $this->comment = AE_Favorite::get_instance();

        $this->add_ajax('ae-sync-favorite', 'sync_favorite', true, false);
        $this->add_ajax('ae-fetch-favorite', 'fetch_favorite', true, false);
    }

    public function sync_favorite(){
        $action = $_REQUEST['sync'];
        switch ($action) {
            case 'add':
                $this->add_favorite($_REQUEST);
                break;

            default:
                $this->remove_favorite($_REQUEST);
                break;
        }
    }

    /**
     * ajax callback fetch post
     * @author ThanhTu
     * @since 1.0
     */
    function fetch_favorite(){
        global $ae_post_factory;
        $review_object = $ae_post_factory->get('de_favorite');
        // get review object
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 2;
        $query = $_REQUEST['query'];

        $query['page'] = $page;
        //add_filter( 'comments_clauses' , array($this, 'groupby') );
        $data = $review_object->fetch($query);

        ob_start();
        ae_comments_pagination($query['total'],$page, array(
            'user_id' => $query['user_id'],
            'type'        => 'review',
            'status'      => 'approve',
            'number' => $query['number'],
            'total' => $query['total'],
            'post_type' => 'place',
            'page' => $page,
            'paginate' => $query['paginate']
        ));

        $paginate = ob_get_clean();
        $result = array(
            'max_num_pages' => $query['total'],
            'success' => true,
            'paginate' => $paginate,
            'total' => $query['total'],
            'data' => $data['data']
        );
        if (!empty($data)) {
            $data['success'] = true;
            wp_send_json($result);
        } else {
            wp_send_json(array(
                'success' => false,
                'data' => $result
            ));
        }
    }

    /**
     * ae-add-favorite ajax callback
     * @since 1.0
     * @param $request
     */
    function add_favorite($request) {
        global $user_ID;
        $args = array();

        /**
         * validate data
         */
        if (empty($request['comment_post_ID'])) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("Please fill in required field.", ET_DOMAIN)
            ));
        }

        if (!$user_ID) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("You have to login.", ET_DOMAIN)
            ));
        }

        /**
         * set favorite data
         */
        $args['comment_post_ID'] = $_REQUEST['comment_post_ID'];
        $args['comment_approved'] = 1;
        $args['comment_content'] = "Me gusta este anuncio";
        $args['type'] = 'favorite';

        $comment = $this->comment->insert($args);

        if (!is_wp_error($comment)) {
            wp_send_json(array(
                'success' => true,
                'msg' => "Añadido a favoritos correctamente.",
                'text' => "Eliminar de favoritos.",
                'data' => $comment
            ));
        } else {
            wp_send_json(array(
                'success' => false,
                'msg' => $comment->get_error_message()
            ));
        }
    }

    /**
     * user remove favorite
     * @since 1.0
     * @param  array $request
     */
    function remove_favorite ($request) {
        global $current_user;
        $comment = get_comment( $request['ID'], OBJECT );
        if(!current_user_can( 'edit_others_posts' )) {

            if( $comment->comment_author_email  !== $current_user->user_email ) {
                wp_send_json( array('success' => false) );
            }
        }
        if( $comment->comment_type == $this->comment->comment_type ) {
            wp_delete_comment( $comment->comment_ID , true );
            wp_send_json(array(
                'success' => true,
                'msg' => "Eliminado de favoritos correctamente.",
                'text' => "Añadir a favoritos"
            ));

        }else {
            wp_send_json( array('success' => false , 'text' => "Ha habido un error al eliminarlo de favoritos.") );
        }
    }
}
/**
 * new class
 */
new AE_FavoriteAction();

