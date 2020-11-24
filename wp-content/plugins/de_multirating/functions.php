<?php
/**
 *Check serialized data from  post meta value
 *
 *@since version 1.0
 *@param string $str 
 *@return true if this string is isSerialized from array / false if this isn't
 */
function isSerialized( $str ) {
    return ( $str == serialize( false ) || @unserialize( $str ) !== false );
}
/**
 * Format review array.
 *
 *@param array $rate_arr
 *@return array $new_rate_arr after format 
 *
*/
function array_nice_key( $rate_arr = array() ){
    $new_rate_arr = array();
    foreach ( $rate_arr as $key => $value ){
        $key = foreach_nice_key( $key );     
        $new_rate_arr[$key] = $value;
    }
    return $new_rate_arr;
}

/**
 * print a key without '' from foreach
 * @param $str
 * @return mixed
 */
function foreach_nice_key( $str ){
    // $str = preg_replace('/[^a-zA-Z0-9\s\\\']/', '', $str );
    // $str = substr( $str, 0, -1 );
    // $str = substr( $str, 1 );
    $pattern = array('\'', '\/', '\\');
    $str = str_replace($pattern, '', $str);
    return $str;
}
add_filter( 'ae_place_field', 'de_multirating_ae_place_field' );
/**
 * add more custom field 'multi_overview_score' and 'multi_reviews_count' to a place
 * @param $args
 * @since version 1.0
 * @since version 1.4 add de_critical_cate
 * @return array
 * @author Tambh
 */
function de_multirating_ae_place_field( $args ){
    $more = array( 'multi_overview_score', 'multi_rating_score', 'multi_reviews_count', 'de_critical_cate' );
    $args = wp_parse_args( $args, $more );
    return $args;
}
add_filter( 'ae_post_taxs', 'add_criteria_to_place', 10 );
/**
 *Add 'review_criteria' taxonomy to a place
 *
 * @param $place_tax
 * @return array
 * @since version 1.0
 */
function add_criteria_to_place( $place_tax ){
    if( is_array( $place_tax ) ){
        array_push( $place_tax, 'review_criteria' );
    }
    return $place_tax;
}

add_action( 'ae_insert_place', 'de_multirating_filter_pre_insert', 10, 2 );
add_action( 'ae_update_place', 'de_multirating_filter_pre_insert', 10, 2 );
/**
 * insert/update review criterias for a place
 * @param $result
 * @param $args
 * @return mixed
 * @since version 1.0
 */
function de_multirating_filter_pre_insert($result, $args){  
    if( isset( $args['review_criteria'] ) && is_array( $args['review_criteria']) ){
        $rc_arr = $args['review_criteria'];
        $rc_arr = array_map( 'intval', $rc_arr );
        wp_set_object_terms( $result, $rc_arr, 'review_criteria' );
    }
}
add_filter( 'de_place_validate_data', 'de_multirating_check_data' );
/**
 * Validate data from form submit review
 *
 * @since version 1.0
 * @param $args
 * @return \WP_Error
 */
function de_multirating_check_data( $args ){
    $enable_critical = ae_get_option('enable_critical');
    global $ae_post_factory;
    if( !ae_user_can( 'edit_others_posts' ) && $args['method'] =='update' && $enable_critical == 0 && $args['review_criteria'] != ''){
        $post = get_post( $args['ID'] );
        $place_obj  = $ae_post_factory->get( 'place' );
        $place = $place_obj->convert( $post );
        $new_rc_arr = array_map( 'intval', (array)$args['review_criteria'] );
        //var_dump($new_rc_arr);
        foreach ( $place->tax_input['review_criteria'] as $key => $value ) {
            if( !in_array($value->term_id, $new_rc_arr ) ){
                return new WP_Error( 'de_review_criteria_delete', __("You just can add more criterias.", ET_DOMAIN) );
            }
        }
    }
    return $args;
}

// add_filter( 'ae_convert_place', 'de_multirating_convert_place' );
/**
 * convert place data
 *
 * @param $result
 * @return array
 * @since version 1.0
 */
function de_multirating_convert_place( $result ){
    $result->rating_score = ($result->multi_overview_score) ? $result->multi_overview_score : $result->rating_score;
    return $result;
}
add_filter( 'ae_convert_comment', 'de_multirating_convert_review' );
/**
 *Convert review data
 *
 * @param $review
 * @since version 1.0
 */
function de_multirating_convert_review( $review ){
    $rate = get_comment_meta( $review->comment_ID, 'et_multi_rate' , true );
    $rate = de_multirating_avg( $rate );
    if( isset($review->et_rate) && $rate ){
        //$comment = get_comment( $review->comment_ID );
        $review->et_multi_rate = $rate;
        $review->et_rate = $rate;
    }
    return $review;
}

/**
 *AVG review score
 *
 * @param $arr_ct
 * @return float|int $avgsum
 * @since version 1.0
 */
function de_multirating_avg( $arr_ct ){
    $sum = 0;
    $avgsum = 0;
    if( is_array( $arr_ct ) ){
        foreach ( $arr_ct as $key => $value ) {
            $sum += $value;
        }
        if($sum > 0) {
            $avgsum = $sum/count($arr_ct);    
        }
    }
    return $avgsum;
}
/**
 *Filter review criteria key
 *@param string $key 
 *@return string key after filter
 *
 * @since  1.1
 * @author Tam
 */
function de_multirating_filter_review_criteria( $key ){
    $pattern = array('\'', '\/', '\\');
    $key = str_replace($pattern, '', $key);
    return $key;
    // $key_end = substr($key, -1);
    // $key_end = preg_replace($pattern, '', $key_end);
    // $key_start = substr($key, 0, 1);
    // $key_start = preg_replace($pattern, '', $key_start);
    // $key = substr_replace($key, $key_start, 0, 1 );
    // $key = substr_replace($key, $key_end, (strlen($key)-1), strlen($key));
}
/**
 *Check if that is a review
 *
 *@param array $rates
 *@since version 1.1
 *@return true if this is a review false if this isn't
 */
function de_multirating_is_review( $rates = array() ){
    $flag = false;
    if(is_array($rates)) {
        foreach ($rates as $key => $value) {
            if((int)$value > 0){
                $flag = true;
            }
        }
    }
    return $flag;
}

/**
* Class Critical Category
*/
class Critical_category extends AE_Base
{
    
    function __construct()
    {
        require_once dirname( __FILE__ ).'/field-critical.php';
        $this->add_ajax('sync-critical','sync');
        $this->add_ajax('update_rating', 'update_rating');
        $this->add_action( 'ae_update_place', 'de_multirating_update_place', 10, 2 );
    }
    
    /**
     * Hook update critecal when update place
     * @param $result
     * @param $args
     */
    function de_multirating_update_place($result, $args){
        global $user_ID;
        $args = (object)$args;
        $enable_critical = ae_get_option('enable_critical');
        if(current_user_can('manage_options') || $args->post_author == $user_ID){
            $this->calc_overating($args, $enable_critical, true);
        }
    }

    /**
     * hook into insert post for update critical category
     * @param $post_id
     */
    function insert_post($post_id){
        if (isset($_POST['critical_cate']) && $_POST['critical_cate'] != '') 
        {
            update_post_meta($post_id, 'de_critical_cate', $_POST['critical_cate']);
        }
    }

    /**
     * sync data backend
     */
    function sync(){
        $var = $_POST;
        $this -> set_term_critical($var['tax'], $var['critical']);
        
        wp_send_json(array('success'=>true, 'msg'=> 'sync success'));
    }
    /** 
     * set critical options 
     * @param $tax_slug: category slug
     * @param $critical_value: option value
     * @param $prefix (default option_critical)
     * @return true/false
     */
    function set_term_critical($tax_slug,$critical_value,$prefix ="option_critical"){
        $critical = get_option( $prefix );

        if ( !is_array( $critical ) ) $critical = array();

        $critical[$tax_slug] = $critical_value;
        $uploaded = update_option( $prefix, $critical );
        return $uploaded;
    }

    /** 
     * get term thumbnail by term id
     * @param $tax_slug: array of term category
     * @param $prefix: name of options
     * @return array $return: return criteria of category or multi category
     * @author QuangDat
     */
    public static function get_critical_options($tax_slug,$prefix = "option_critical"){
        $critical_options = get_option( $prefix );
        $c = array();
        $return = array();
        // if place have more than one category
        if (is_array($tax_slug) && count($tax_slug) > 1) {
            foreach ($tax_slug as $slug) {
                //find each cate have parent or not
                $p = get_term_by('id',(int)$slug,'place_category');
                /* 
                 * if does have, get critical of this category
                 * else find parent and get critical
                 */
                if ($p->parent == 0 && isset($critical_options[$slug])) {
                    $c[] =  $critical_options[$slug] ;
                } elseif ($p->parent != 0) {
                    $parent = get_ancestors( $p->term_id, 'place_category' );
                    $end = end($parent);
                    $c[] =  $critical_options[$end] ;
                   
                }
            }
            $arr = call_user_func_array('array_merge', $c);
            $return =  array_unique($arr);
        } 

        if (is_array($tax_slug) && count($tax_slug) == 1) {
            $tax = (int)$tax_slug[0];
            $p = get_term_by('id',$tax,'place_category');
            /* 
             * if does have, get critical of this category
             * else find parent and get critical
             */
            if ($p->parent == 0 && isset($critical_options[$tax])) {
                $return =  $critical_options[$tax] ;
            } elseif ($p->parent != 0) {
                $parent = get_ancestors( $p->term_id, 'place_category' );
                $end = end($parent);
                $return =  $critical_options[$end] ;
            }
        } 

        if (!is_array($tax_slug)) {
            // for backend
            $tax = (int)$tax_slug;

            $p = get_term_by('id',$tax,'place_category');
            /* 
             * if does have, get critical of this category
             * else find parent and get critical
             */
            if ($p->parent == 0 && isset($critical_options[$tax])) {
                $return =  isset($critical_options[$tax]) ? $critical_options[$tax] : 1;
            } elseif ($p->parent != 0) {
                $parent = get_ancestors( $p->term_id, 'place_category' );
                $end = end($parent);
                $return =  isset($critical_options[$end]) ? $critical_options[$end] : 1;
            }
            //$return = isset($thumbnail[$tax]) ? $thumbnail[$tax] : 1;
        }

        return $return;
    }

    /**
     * get all name of criterias
     * @param  array $terms
     * @return array $term_name : return term name of criterials
     * @author QuangDat
     */
    public static function critical_name ($terms = array()){
        $t = array();
        $term_name = array();

        foreach($terms as $term){
            $t[] = get_term_by('id',(int)$term,'review_criteria');
        }
        
        foreach ($t as $names) {
            if ($names) {
                $term_name[] = $names->name;  
            }
        }
        return $term_name;
    }

    /**
     * function will calculator overrating
     * @param  object $place
     * @param  boolen $update_data 
     * @param  int $enable_critical 
     * @return int    $overview
     * @author QuangDat
     */
    function calc_overating($place, $enable_critical, $update_data = true){
        global $wpdb;
        $default = array(
            'orderby' => ae_get_option('de_multirating_orderby', 'name'),
            'order' => ae_get_option('de_multirating_order', 'DESC')
        );
       
        $sum = 0;
        $mask = array();
        
        $term_list = wp_get_object_terms($place->ID, 'review_criteria', $default);
        //if click enable critical by category
        if ($enable_critical) {
            /* get list critical by category of place
             * if don't have critical_cate, it's will get place category
             */
            if($place->de_critical_cate){
                $critical_category = $this->get_critical_options($place->de_critical_cate);
            } else {
                $critical_category = $this->get_critical_options($place->place_category[0]);
            }
            //get name of critical
            if ($critical_category != 1 && $critical_category != null) {
                $term_name = $this->critical_name($critical_category);
                $mask = $term_name;
            }
        } else {
            foreach ($term_list as $key => $value) {
                array_push($mask, $value->name);
            }
        }
        $reviews = get_comments(array(
                'type' => 'review', 
                'post_id' => $place->ID,
                'status' => 'approve',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key'       => 'et_rate',
                        'value'     => '0',
                        'compare'   => '>'
                    ),
                    // array(
                    //     'key'       => 'et_multi_rate',
                    //     'value'     => '',
                    //     'compare'   => '>'
                    // )
                )
            )
        );
        
        // update crirical for review of theme
        foreach ($reviews as $comment) {
            $rate = get_comment_meta($comment->comment_ID, 'et_rate' , true);
            $rate_multi = get_comment_meta($comment->comment_ID, 'et_multi_rate' , true);
            if(empty($rate_multi)){
                $arr_multi_rate = array();
                foreach ($mask as $key => $value) {
                    $arr_multi_rate[$value] = $rate;
                }
                update_comment_meta($comment->comment_ID, 'et_multi_rate', $arr_multi_rate);
            }
        }

        $sql = "SELECT M.meta_value  as rate_point
                    FROM $wpdb->comments as C
                        join $wpdb->commentmeta as M
                        ON C.comment_ID = M.comment_id
                    WHERE   M.meta_key = 'et_multi_rate'
                            AND C.comment_post_ID = $place->ID
                            AND C.comment_approved = 1";
        $results = $wpdb->get_results($sql);
        $meta_array = array();
        $count_multi_review = 0;
        foreach ($results as $key => $value) {
            $rate_point = unserialize($value->rate_point);
            // Check array review
            if (isSerialized($value->rate_point) && count($rate_point) > 0) {
                foreach (unserialize($value->rate_point) as $criteria => $criteria_value) {
                    if (!isset($meta_array[$criteria])) {
                        $meta_array[$criteria] = 0;
                    }
                    $meta_array[$criteria] += (float)$criteria_value;    
                }
                // count multi review
                $count_multi_review++;
            }
        }

        $sum = 0;
        if (!empty($meta_array)) {
            foreach ($meta_array as $key => $value) {
                $meta_array[$key] = $value/$count_multi_review;   
            }
        }
        
        //calc rating
        $sum = 0;
        $array_multi_rating_score = array();
        
        foreach ($mask as $key => $value) { 
            $sum += isset($meta_array[$value]) ? $meta_array[$value] : 0;
            $array_multi_rating_score[$value] = isset($meta_array[$value]) ? $meta_array[$value] : 0;
        }

        if ($sum != 0) {
            $overview = round($sum/count($mask), 1);
        } else {
            $overview = 0;
        }

        //update rating
        if($update_data){
            // update post rating score
            update_post_meta((int)$place->ID, 'multi_overview_score', $overview);
            // update post rating_score
            update_post_meta((int)$place->ID, 'rating_score', $overview);
			/*echo "<pre>";
			print_r($array_multi_rating_score);exit;*/
            update_post_meta((int)$place->ID, 'multi_rating_score', $array_multi_rating_score);
        }
        return $overview;
    }

    function update_rating(){
        global $wp_query;

        $post_arr = new WP_Query(array(
                'post_type'   => 'place',
                'post_status' => array('reject' ,'pending','publish', 'archive', 'draft'),
                'showposts'   => -1
                )
            );
        $enable_critical = $_POST['enable_critical'];
        $arr = array();
        if($post_arr->have_posts()){
            while ($post_arr->have_posts()) {
                $post_arr->the_post();
                global $post, $ae_post_factory;
                /**
                 * convert
                */
                $ae_post    =   $ae_post_factory->get('place');
                $convert    =   $ae_post->convert($post);
                $arr[] =   $convert->post_title;
                $this->calc_overating($convert, $enable_critical, true);
            }
        } 
        
        wp_send_json($arr);
    }
}
new Critical_category();
