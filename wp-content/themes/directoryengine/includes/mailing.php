<?php
/**
 * Class DE_Mailing
 */
class DE_Mailing extends AE_Mailing
{
    public static $instance;
    
    static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new DE_Mailing();
        }
        
        return self::$instance;
    }
    
    function __construct() {
    }

    /**
     * Send a cash notification mail to admin 
     * @param integer $post_id
     * @author Tuandq
     * @since 2.1
     */   
    function send_pending_cash($post_id){
        $place = get_post($post_id);
        $place_name = $place->post_title;        
        $user = get_userdata($place->post_author);
        $author = $user->display_name;
        $link = get_post_permalink($post_id);
        $template_default = '<p>Dear admin,</p>
                            <p>An author has just submitted a new place and choose "Cash" as his package payment method. This payment is now pending for your review, please kindly check it out!</p>
                            <p>Place\'s name: [place_name]<br>
                            Author: [author]<br>
                            Link: [link]</p>
                            <p>Regards,</p>';
        $message = ae_get_option('ae_pending_cash_notification_mail', $template_default);
        $message = str_replace('[place_name]', $place_name, $message);
        $message = str_replace('[author]', $author , $message);
        $message = str_replace('[link]', $link , $message);        
        $to_email = ae_get_option('new_post_alert') ? ae_get_option( 'new_post_alert' ) : get_bloginfo('admin_email'); 
        $subject = __('New Place Submission Announcement',ET_DOMAIN); 
        $this->wp_mail($to_email,$subject,$message,'','');
    }  
    /**
     * Send mail to admin when site have new event 
     * @param integer $post_id
     * @author Tuandq
     * @since 2.1
     */   
    function send_event_place_mail($args){
        $post_id            = $args->post_parent;
        $place              = get_post($post_id);
        $place_name         = $place->post_title;     
        $user               = get_userdata($args->post_author);
        $author             = $user->display_name;
        $link               = $args->permalink;
        $event              = $args->post_title;
        $template_default   = '<p>Dear admin,</p>
                                <p>There is a new pending event that needs your review.</p>
                                <p>Place\'s name: [place_name]<br>
                                Event: [event]<br>
                                Author: [author]<br>
                                Link: [link]</p>
                                <p>Regards,</p>';
        $message = ae_get_option('ae_event_place_mail', $template_default);
        $message = str_replace('[place_name]', $place_name, $message);
        $message = str_replace('[event]', $event , $message);
        $message = str_replace('[author]', $author , $message);
        $message = str_replace('[link]', $link , $message);        
        $to_email = ae_get_option('new_post_alert') ? ae_get_option( 'new_post_alert' ) : get_bloginfo('admin_email'); 
        $subject = __('New pending Event',ET_DOMAIN); 
        $this->wp_mail($to_email,$subject,$message,'','');
    }
}

/**
 * Send a cash notification mail to admin 
 * @param integer $post_data
 * @author Tuandq
 * @since 2.1
 */ 
function send_email_pending_cash($post_data){
    if(isset($post_data)){
        global $wpdb;
        $post_parent = $post_data['ID'];
        $find_args = array( 'post_parent'   => $post_parent,
                            'post_status'   => 'pending',
                            'post_type'     => 'order'
                    );
        $find_payment = get_posts( $find_args );
        if($find_payment){
            $et_mailing   =   DE_Mailing::get_instance(); 
            $et_mailing->send_pending_cash($post_data['ID']);  
        }             
        wp_reset_postdata();
    }
}
add_action('ae_after_process_payment','send_email_pending_cash');