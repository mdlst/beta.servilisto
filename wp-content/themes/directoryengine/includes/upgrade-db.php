<?php
/**
 * INIT  to insert "et_featured" meta key yet.
 *
 * @since 1.8.4
 * 
 */
function de_update_et_featured_init(){
    if( is_admin() || current_user_can( 'manage_options' ) ){
        wp_enqueue_script( 'de-update-featured', get_template_directory_uri() .'/js/update_featured.js', array('jquery','backbone', 'appengine', 'backend') , true, false);
    }
}
add_action( 'admin_enqueue_scripts', 'de_update_et_featured_init');  
/**
 * Select all place ID hasn't "et_featured" meta key yet.
 *
 * @since 1.8.4
 * @return json array( "sucess" => true/falase, "data"=> array place ID)
 */
add_action( 'wp_ajax_de_get_list_to_update_et_fetured', 'de_get_list_to_update_et_fetured' );
function de_get_list_to_update_et_fetured(){
    global $wpdb;
    if( is_admin() && current_user_can( 'manage_options' ) ){
        $sql = "SELECT  ID 
                        FROM $wpdb->posts
                        WHERE ID NOT IN (
                            SELECT ID
                                FROM $wpdb->posts as P
                                    JOIN $wpdb->postmeta as A
                                        ON P.ID = A.post_id
                                WHERE A.meta_key = 'et_featured' AND P.post_type = 'place'
                            ) AND post_type = 'place' GROUP BY ID";
            $myrows = $wpdb->get_results($sql);
            wp_send_json(array('sucess' => true, 'data' => $myrows));  
            return;
    }
    else{
        wp_send_json(array('sucess' => false));  
    }
}
/**
 * Insert "et_featured" meta key to all places
 *
 * @since 1.8.4
 * @return json array( "sucess" => true/falase, "msg"=> 'message')
 */
add_action( 'wp_ajax_de_update_et_fetured', 'de_update_et_fetured' );
function de_update_et_fetured(){
    global $wpdb;
    if( is_admin() && current_user_can( 'manage_options' )  ){
        if(isset($_REQUEST['content']) && $_REQUEST['content'] != ''){
            $post_in_arr = (array)$_REQUEST['content'];
            $arg_values = '';
            foreach ($post_in_arr as $key => $value) {
                $id = $value['ID'];
                $arg_values .= " ($id, 'et_featured', '0' ),";
            }
            $arg_values = substr( $arg_values, 0, -1 );
            $sql = "INSERT INTO $wpdb->postmeta
                            (post_id, meta_key, meta_value ) 
                            VALUES $arg_values";
            $result = $wpdb->query( $sql );
            if($result){
                ae_update_option( 'de_is_update_et_featured', '0' );
                wp_send_json(array('sucess' => true, 'msg' => __('You updated "et_featured" meta key successfull.', ET_DOMAIN ) ) ) ;  
                return;
            }
        }
        wp_send_json(array('sucess' => true, 'msg' => __('You have updated "et_featured" meta key to all places.', ET_DOMAIN ) ) ) ; 
    }
    wp_send_json(array('sucess' => false,  'msg' => __('You updated "et_featured" meta key failed.', ET_DOMAIN ) ) );
}

if(!function_exists('de_get_list_to_update_rating_score')):
/**
 * callback for ajax de_get_list_to_update_rating_score to get the list of place dont have rating_score meta key
 * @return json send back to client
 * 
 * @since  1.8.5
 * @author  Dakachi
 */
function de_get_list_to_update_rating_score(){
    global $wpdb;
    if( is_admin() && current_user_can( 'manage_options' ) ){
        $sql = "SELECT  ID 
                        FROM $wpdb->posts
                        WHERE ID NOT IN (
                            SELECT ID
                                FROM $wpdb->posts as P
                                    JOIN $wpdb->postmeta as A
                                        ON P.ID = A.post_id
                                WHERE A.meta_key = 'rating_score' AND P.post_type = 'place'
                            ) AND post_type = 'place' GROUP BY ID";
            $myrows = $wpdb->get_results($sql);
            wp_send_json(array('sucess' => true, 'data' => $myrows));  
            return;
    }
    else{
        wp_send_json(array('sucess' => false));  
    }
}
add_action( 'wp_ajax_de_get_list_to_update_rating_score', 'de_get_list_to_update_rating_score' );
endif;

if(!function_exists('de_update_rating_score')):
/**
 * Insert "rating_score" meta key to all places
 * @return json array( "sucess" => true/falase, "msg"=> 'message')
 * 
 * @since 1.8.5
 * @author  Dakachi
 */
function de_update_rating_score(){
    global $wpdb;
    if( is_admin() && current_user_can( 'manage_options' )  ){
        if(isset($_REQUEST['content']) && $_REQUEST['content'] != ''){
            $post_in_arr = (array)$_REQUEST['content'];
            $arg_values = '';
            foreach ($post_in_arr as $key => $value) {
                $id = $value['ID'];
                $arg_values .= " ($id, 'rating_score', '0' ),";
            }
            $arg_values = substr( $arg_values, 0, -1 );
            $sql = "INSERT INTO $wpdb->postmeta
                            (post_id, meta_key, meta_value ) 
                            VALUES $arg_values";
            $result = $wpdb->query( $sql );
            if($result){
                wp_send_json(array('sucess' => true, 'msg' => __('You updated "rating_score" meta key successfull.', ET_DOMAIN ) ) ) ;  
                return;
            }
        }
        wp_send_json(array('sucess' => true, 'msg' => __('You have updated "rating_score" meta key to all places.', ET_DOMAIN ) ) ) ; 
    }

    wp_send_json(array('sucess' => false,  'msg' => __('You updated "rating_score" meta key failed.', ET_DOMAIN ) ) );
}
add_action( 'wp_ajax_de_update_rating_score', 'de_update_rating_score' );
endif;