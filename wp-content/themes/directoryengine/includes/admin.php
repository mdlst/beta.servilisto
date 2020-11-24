<?php
/**
 *
 */
define('ADMIN_PATH', TEMPLATEPATH . '/admin');

if(!class_exists('AE_Base')) return;

/**
 * Handle admin features
 * Adding admin menus
 */
class ET_Admin extends AE_Base
{
    /**
     * construct ET_Admin
     */
    function __construct() {

        /**
         * admin setup
        */
        $this->add_action('init', 'admin_setup');

        /**
         * update first options
        */
        $this->add_action('after_switch_theme', 'update_first_time');
        

        //if (!get_option('de_first_time_install')) {
            $this->add_action('tgmpa_register', 'de_required_plugins');
        //}
        //declare ajax classes
        new AE_CategoryAjax(new AE_Category(array(
            'taxonomy' => 'place_category'
        )));
        new AE_CategoryAjax(new AE_Category(array(
            'taxonomy' => 'location'
        )));

        $this->add_ajax('ae-reset-option', 'reset_option');

        /**
         * override author link
        */
        global $wp_rewrite;
        //$wp_rewrite->author_base      = apply_filters('de_author_base', 'user' );
        $wp_rewrite->author_structure = '/' . $wp_rewrite->author_base. '/%author%';


        /**
         * set default options
         */
        $options = AE_Options::get_instance();
        if (!$options->init) $options->reset($this->get_default_options());   

        $this->add_action('admin_head', 'admin_custom_css');
        $this->add_action('ae_upload_image', 'update_site_image', 10, 2);
    }
    
    /**
     * ajax function reset option
     */
    function reset_option() {
        
        $option_name = $_REQUEST['option_name'];
        $default_options = $this->get_default_options();
        
        if (isset($default_options[$option_name])) {
            $options = AE_Options::get_instance();
            $options->$option_name = $default_options[$option_name];
            wp_send_json(array(
                'msg' => $default_options[$option_name]
            ));
        }
    }

    /**
     * action hook ae_upload_image to setup site branding
     * @param $attach_data
     * @param string $data
     */
    function update_site_image( $attach_data , $data ){

        switch ($data) {
            case 'geolocation_icon':
                $options = AE_Options::get_instance();
                // save this setting to theme options
                $options->$data = $attach_data;
                $options->save();

                break;
            default:
                # code...
                break;
        }
    }
    function admin_custom_css () {
    ?>
        <style type="text/css">
        .custom-icon {
            margin: 10px;
        }
        .custom-icon input {
            width: 80%;
        }
        </style>
    <?php
    }
    
    /**
     * retrieve site default options
     */
    function get_default_options() {
        
        return array(
            'blogname' => get_option('blogname') ,
            'blogdescription' => get_option('blogdescription') ,
            'copyright' => '<span class="enginethemes"> <a href="http://www.enginethemes.com/themes/directoryengine/" >Directory Software</a> - Powered by WordPress </span>',
            
            // default forgot passmail
            'forgotpass_mail_template' => '<p>Dear [display_name],</p><p>You have just sent a request to recover the password associated with your account in [blogname]. If you did not make this request, please ignore this email; otherwise, click the link below to create your new password:</p><p>[activate_url]</p><p>Regards,<br />[blogname]</p>',
            
            // default register mail template
            'register_mail_template' => '<p>Dear [display_name],</p><p>You have successfully registered an account with &nbsp;&nbsp;[blogname].&nbsp;Here is your account information:</p><ol><li>Username: [user_login]</li><li>Email: [user_email]</li></ol><p>Thank you and welcome to [blogname].</p>',

            // default confirm mail template
            'password_mail_template' => '<p>Dear [display_name],</p><p>You have successfully registered an account with &nbsp;&nbsp;[blogname].&nbsp;Here is your account information:</p><ol><li>Username: [user_login]</li><li>Email: [user_email]</li><li>Password: [password]</li></ol><p>Thank you and welcome to [blogname].</p>',
            
            //  default reset pass mail template
            'resetpass_mail_template' => "<p>Dear [display_name],</p><p>You have successfully changed your password. Click this link &nbsp;[site_url] to login to your [blogname]'s account.</p><p>Sincerely,<br />[blogname]</p>",

            // default confirm mail template
            'confirm_mail_template' => '<p>Dear [display_name],</p><p>You have successfully registered an account with &nbsp;&nbsp;[blogname].&nbsp;Here is your account information:</p><ol><li>Username: [user_login]</li><li>Email: [user_email]</li></ol><p>Please click the link below to confirm your email address.</p><p>[confirm_link]</p><p>Thank you and welcome to [blogname].</p>',

            // default confirmed mail template
            'confirmed_mail_template' => "<p>Dear [display_name],</p><p>Your email address has been successfully confirmed.</p><p>Thank you and welcome to [blogname].</p>",

            //  default inbox mail template
            'inbox_mail_template' => "<p>Dear [display_name],</p><p>You have just received the following message from user: <a href=\"[sender_link]\">[sender]</a></p>
                                        <p>|--------------------------------------------------------------------------------------------------|</p>
                                        <p>Place link: <a href=\"[place_link]\">[place_link]</a></p>
                                        <p>Message: </p>
                                        [message]
                                        <p>|--------------------------------------------------------------------------------------------------|</p>
                                        <p>You can answer the user by replying this email.</p><p>Sincerely,<br />[blogname]</p>",
             //  default inbox mail template
            'publish_mail_template' => "<p>Dear [display_name],</p>
                                        <p>Your place: [title] in [blogname] is publish.</p>
                                        <p>You can follow this link: [link] to view your listing offer.</p>
                                        <p>Sincerely,<br />[blogname]</p>",

            'archive_mail_template' => "<p>Dear [display_name],</p>
                                        <p>Your place: [title] in [blogname] has been archived due to expiration or manual administrative action.</p>
                                        <p>If you want to continue displaying this listing in our website, please go to your dashboard at [author_link] to renew your listing offer.</p>
                                        <p>Sincerely,<br />[blogname]</p>",

            'reject_mail_template' => "<p>Dear [display_name],</p>
                                        <p>Your place: [title] in [blogname] has been rejected due to expiration or manual administrative action.</p>
                                        <p>Reject message: [reject_message]</p>
                                        <p>Please contact the administrators via [admin_email] for more information, or go to your dashboard at [author_link] to edit your listing offer and post it again.</p>
                                        <p>Sincerely,<br />[blogname]</p>",

            'cash_notification_mail' => "<p>Dear [display_name],</p>
                                        <p>[cash_message]</p>
                                        <p>Sincerely, <br/>[blogname].</p>",
            'ae_receipt_mail'   => '<p>Dear [display_name],</p>
                                    <p>Thank you for your payment.</p>
                                    <p>
                                        Here are the details of your transaction:<br />
                                        Detail:Submit post [link]<br />
                                    </p>
                                    <p>
                                        <strong> Customer info</strong>:<br /> 
                                        [display_name] <br /> 
                                        Email: [user_email]. <br />
                                    </p>
                                    <p> 
                                        <strong> Invoice</strong> <br />
                                        Invoice No: [invoice_id]  <br />
                                        Date: [date]. <br /> 
                                        Payment: [payment] <br /> 
                                        Total: [total] [currency]<br /> 
                                    </p>
                                    <p>Sincerely,<br />[blogname]</p>',  
            'ae_report_mail'    => '<p>Hi Admins,</p><p>The place [place_title]( [place_link] ) has been reported by [user_name].</p><p>|--------------------------------------------------------------------------------------------------|</p>[report_message]<p>|--------------------------------------------------------------------------------------------------|</p><p>You can manage the reports following this link: [reports_link].</p><p>Sincerely,<br>[blogname]</p>',
            'ae_claim_mail'    => '<p>Hi Admins,</p><p>The place "[place_title]" has new claim request&nbsp;by [user_name].</p><p>|--------------------------------------------------------------------------------------------------|</p><p>Full name: [claim_full_name]</p><p>Address: [claim_address]</p><p>Email: [claim_email]</p><p>Phone: [claim_phone]</p><p>Message:[claim_message]</p><p>|--------------------------------------------------------------------------------------------------|</p><p>You can approve this claim following this link: [place_link].</p><p>Sincerely,<br>[blogname]</p>',
            'ae_approve_claim_mail'    => '<p>Hi [display_name],</p><p>Your claim request to the place [place_title] has been approved.</p><p>You can update that place following this link: [place_link] .</p><p>Sincerely,<br>[blogname]</p>',
            'ae_reject_claim_mail'    => '<p>Hi [display_name],</p><p>Your claim request to the place [place_title] has been rejected.</p><p>If you have any questions please contact the Administrators.</p><p>Sincerely,<br>[blogname]</p>',
            'ae_comment_place_mail' => '<p>Hi [display_name],</p>
                                        <p>The place <a href=\"[place_link]\">[place_title]</a> has been commented by [comment_author].</p>
                                        <p>|--------------------------------------------------------------------------------------------------|</p>
                                        <p>
                                            <strong>Author</strong>: [comment_author] <br>
                                            <strong>Email</strong>: [comment_author_email] <br>
                                            <strong>Date</strong>: [comment_date] <br>
                                            <strong>Comment Content</strong>: [comment_message] <br>
                                        </p>
                                        <p>|--------------------------------------------------------------------------------------------------|</p>
                                        <p>You can reply the comment with this link: <a href=\"[comment_link]\">[comment_link]</a>.</p>',
            'ae_event_place_mail'       =>  '<p>Dear admin,</p>
                                            <p>There is a new pending event that needs your review.</p>
                                            <p>Place\'s name: [place_name]<br>
                                            Event: [event]<br>
                                            Author: [author]<br>
                                            Link: [link]</p>
                                            <p>Regards,</p>',
            'ae_pending_cash_notification_mail' => '<p>Dear admin,</p>
                                                    <p>An author has just submitted a new place and choose "Cash" as his package payment method. This payment is now pending for your review, please kindly check it out!</p>
                                                    <p>Place\'s name: [place_name]<br>
                                                    Author: [author]<br>
                                                    Link: [link]</p>
                                                    <p>Regards,</p>',
            'init' => 1
        );
    }    
    function update_first_time() {
        update_option('de_first_time_install', 1);
    }
    function de_required_plugins() {
        $plugins = array(
            array(
                'name'               => 'WPBakery Visual Composer Plugin',
                'slug'               => 'js_composer',
                'source'             => 'http://www.enginethemes.com/files/js_composer.zip',
                'required'           => false,
                'version'            => '4.3.3',
                'force_activation'   => false,
                'force_deactivation' => true,
                'external_url'       => 'http://www.enginethemes.com/files/js_composer.zip',
            ),
            array(
                'name'               => 'Revolution Slider Plugin',
                'slug'               => 'revslider',
                'source'             => 'http://www.enginethemes.com/files/revslider.zip',
                'required'           => false,
                'version'            => '4.6.0',
                'force_activation'   => false,
                'force_deactivation' => true,
                'external_url'       => 'http://www.enginethemes.com/files/revslider.zip',
            )
        );
        
        // Change this to your theme text domain, used for internationalising strings
        $theme_text_domain = ET_DOMAIN;
        
        $config = array(
            'domain' => $theme_text_domain,
            'default_path' => '',
            'parent_menu_slug' => 'themes.php',
            'parent_url_slug' => 'themes.php',
            'menu' => 'install-required-plugins',
            'has_notices' => true,
            'is_automatic' => false,
            'message' => '',
            'strings' => array(
                'page_title' => __('Install Required Plugins', $theme_text_domain) ,
                'menu_title' => __('Install Plugins', $theme_text_domain) ,
                'installing' => __('Installing Plugin: %s', $theme_text_domain) ,
                 // %1$s = plugin name
                'oops' => __('Something went wrong with the plugin API.', $theme_text_domain) ,
                'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.') ,
                 // %1$s = plugin name(s)
                'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.') ,
                 // %1$s = plugin name(s)
                'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.') ,
                 // %1$s = plugin name(s)
                'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.') ,
                 // %1$s = plugin name(s)
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.') ,
                 // %1$s = plugin name(s)
                'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.') ,
                 // %1$s = plugin name(s)
                'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.') ,
                 // %1$s = plugin name(s)
                'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.') ,
                 // %1$s = plugin name(s)
                'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins') ,
                'activate_link' => _n_noop('Activate installed plugin', 'Activate installed plugins') ,
                'return' => __('Return to Required Plugins Installer', $theme_text_domain) ,
                'plugin_activated' => __('Plugin activated successfully.', $theme_text_domain) ,
                'complete' => __('All plugins installed and activated successfully. %s', $theme_text_domain)
                 // %1$s = dashboard link
                
            )
        );
        
        tgmpa($plugins, $config);
    }
    
    /**
     * update admin setup
     */
    function admin_setup() {
        
        $sections = array();
        /**
         * general settings section
         */
        $sections[] = array(
            'args' => array(
                'title' => __("General", ET_DOMAIN) ,
                'id' => 'general-settings',
                'icon' => 'y',
                'class' => ''
            ) ,
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Website Title", ET_DOMAIN) ,
                        'id' => 'site-name',
                        'class' => '',
                        'desc' => __("Enter your website title.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'blogname',
                            'type' => 'text',
                            'title' => __("Website Title", ET_DOMAIN) ,
                            'name' => 'blogname',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                
                array(
                    'args' => array(
                        'title' => __("Website Description", ET_DOMAIN) ,
                        'id' => 'site-description',
                        'class' => '',
                        'desc' => __("Enter your website description", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'blogdescription',
                            'type' => 'text',
                            'title' => __("Website Title", ET_DOMAIN) ,
                            'name' => 'blogdescription',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Copyright", ET_DOMAIN) ,
                        'id' => 'site-copyright',
                        'class' => '',
                        'desc' => __("This copyright information will appear in the footer.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'copyright',
                            'type' => 'text',
                            'title' => __("Copyright", ET_DOMAIN) ,
                            'name' => 'copyright',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Support Phone", ET_DOMAIN) ,
                        'id' => 'ssupport-phone',
                        'class' => '',
                        'desc' => __("Enter your support phone number.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'ssupport-phone',
                            'type' => 'text',
                            'title' => __("Support Phone", ET_DOMAIN) ,
                            'name' => 'support_phone',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,

                array(
                    'args' => array(
                        'title' => __("Support Email", ET_DOMAIN) ,
                        'id' => 'support-email-e',
                        'class' => '',
                        'desc' => __("Enter your support email.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'support-email',
                            'type' => 'text',
                            'title' => __("Support Email", ET_DOMAIN) ,
                            'name' => 'support_email',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Google Analytics Script", ET_DOMAIN) ,
                        'id' => 'site-analytics',
                        'class' => '',
                        'desc' => __("Google analytics is a service offered by Google that generates detailed statistics about the visits to a website.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'opt-ace-editor-js',
                            'type' => 'textarea',
                            'title' => __("Google Analytics Script", ET_DOMAIN) ,
                            'name' => 'google_analytics',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Email Confirmation " , ET_DOMAIN) ,
                        'id' => 'user-confirm',
                        'class' => '',
                        'desc' => __("Enabling this will require users to confirm their email addresses after registration.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'user_confirm',
                            'type' => 'switch',
                            'title' => __("Email Confirmation", ET_DOMAIN) ,
                            'name' => 'user_confirm',
                            'class' => ''
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Twitter URL", ET_DOMAIN) ,
                        'id' => 'site-twitter',
                        'class' => '',
                        // 'desc' => __("Your .", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'site-twitter',
                            'type' => 'text',
                            'title' => __("Copyright", ET_DOMAIN) ,
                            'name' => 'site_twitter',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Facebook URL", ET_DOMAIN) ,
                        'id' => 'site-facebook',
                        'class' => '',
                        // 'desc' => __("This copyright information will appear in the footer.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'site-facebook',
                            'type' => 'text',
                            'title' => __("Copyright", ET_DOMAIN) ,
                            'name' => 'site_facebook',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Google Plus URL", ET_DOMAIN) ,
                        'id' => 'site-google',
                        'class' => '',
                        // 'desc' => __("This copyright information will appear in the footer.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'site-google',
                            'type' => 'text',
                            'title' => __("Google Plus URL", ET_DOMAIN) ,
                            'name' => 'site_google',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Login in admin panel", ET_DOMAIN) ,
                        'id' => 'login_init',
                        'class' => '',
                        'desc' => __("Prevent directly login to admin page.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'login-init',
                            'type' => 'switch',
                            'label' => __("Enable this option will prevent directly login to admin page.", ET_DOMAIN) ,
                            'name' => 'login-init',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Disable automatic page creation.", ET_DOMAIN) ,
                        'id' => 'auto_create_page',
                        'class' => '',
                        'desc' => __("Disable automatic page creation", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'login-init',
                            'type' => 'switch',
                            'label' => __("Enable this option will disable automatic page creation.", ET_DOMAIN) ,
                            'name' => 'auto_create_page',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Administrators email", ET_DOMAIN) ,
                        'id' => 'new_post_alert',
                        'class' => '',
                        'desc' => __("The new post notification will be sent to these emails.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'site-google',
                            'type' => 'text',
                            'title' => __("Ex: abc@gmail.com,bcd@gmail.com", ET_DOMAIN) ,
                            'name' => 'new_post_alert',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,                  
            )
        );
        /**
         * branding section
         */
        $sections[] = array(
            
            'args' => array(
                'title' => __("Branding", ET_DOMAIN) ,
                'id' => 'branding-settings',
                'icon' => 'b',
                'class' => ''
            ) ,
            
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Site logo", ET_DOMAIN) ,
                        'id' => 'site-logo',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Your logo should be in PNG, GIF or JPG format, within 150x50px and less than 1500Kb.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'opt-ace-editor-js',
                            'type' => 'image',
                            'title' => __("Site Logo", ET_DOMAIN) ,
                            'name' => 'site_logo',
                            'class' => '',
                            'size' => array(
                                '150',
                                '50'
                            )
                        )
                    )
                ) ,

                array(
                    'args' => array(
                        'title' => __("Mobile logo", ET_DOMAIN) ,
                        'id' => 'mobile-logo',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Your logo should be in PNG, GIF or JPG format, within 150x50px and less than 1500Kb.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'opt-ace-editor-js',
                            'type' => 'image',
                            'title' => __("Mobile Logo", ET_DOMAIN) ,
                            'name' => 'mobile_logo',
                            'class' => '',
                            'size' => array(
                                '150',
                                '50'
                            )
                        )
                    )
                ) ,
                
                array(
                    'args' => array(
                        'title' => __("Mobile Icon", ET_DOMAIN) ,
                        'id' => 'mobile-icon',
                        'class' => '',
                        'name' => '',
                        'desc' => __("This icon will be used as a launcher icon for iPhone and Android smartphones and also as the website favicon. The image dimensions should be 57x57px.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'opt-ace-editor-js',
                            'type' => 'image',
                            'title' => __("Mobile Icon", ET_DOMAIN) ,
                            'name' => 'mobile_icon',
                            'class' => '',
                            'size' => array(
                                '57',
                                '57'
                            )
                        )
                    )
                ) ,

                array(
                    'args' => array(
                        'title' => __("Use Pre Loading", ET_DOMAIN) ,
                        'id' => 'use-pre-loading',
                        'class' => '',
                        'desc' => __("The page preloading effect seen on the website.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'use_pre_loading-field',
                            'type' => 'switch',
                            'name' => 'use_pre_loading',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                
                array(
                    'args' => array(
                        'title' => __("Pre Loading Icon", ET_DOMAIN) ,
                        'id' => 'pre-loading-icon',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Preloading Image. The image dimensions should be 57x57px.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'opt-ace-editor-js',
                            'type' => 'image',
                            'title' => __("Mobile Icon", ET_DOMAIN) ,
                            'name' => 'pre_loading',
                            'class' => '',
                            'size' => array(
                                '57',
                                '57'
                            )
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Default avatar", ET_DOMAIN) ,
                        'id' => 'et-default-avatar',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Default avatar Image. The image dimensions should be 150x150px.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'default-avatar',
                            'type' => 'image',
                            'title' => __("Default avatar", ET_DOMAIN) ,
                            'name' => 'default_avatar',
                            'class' => '',
                            'size' => array(
                                '150',
                                '150'
                            )
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Default thumbnail", ET_DOMAIN) ,
                        'id' => 'et-default-thumbnail-img',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Default thumbnail Image. The image dimensions should be 270x280px.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'default-thumbnail-img',
                            'type' => 'image',
                            'title' => __("Default thumbnail", ET_DOMAIN) ,
                            'name' => 'default_thumbnail_img',
                            'class' => '',
                            'size' => array(
                                '270',
                                '280'
                            )
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Default Location Image", ET_DOMAIN) ,
                        'id' => 'et-default-location-img',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Default thumbnail Image. The image dimensions should be 200x175px.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'default-location-img',
                            'type' => 'image',
                            'title' => __("Default thumbnail", ET_DOMAIN) ,
                            'name' => 'default_location_img',
                            'class' => '',
                            'size' => array(
                                '255',
                                '160'
                            )
                        )
                    )
                ),
            )
        );
        /**
         * Map section
         */
        $sections[] = array(
            'args' => array(
                'title' => __("Map", ET_DOMAIN) ,
                'id' => 'map-settings',
                'icon' => 'x',
                'class' => ''
            ) ,
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Map API Key", ET_DOMAIN) ,
                        'id' => 'gg_map_key',
                        'class' => '',
                        'desc' => __("Enter your API Key. <a href='https://console.developers.google.com/apis/library' target='_blank' rel='nofollow'>Get key</a>", ET_DOMAIN)
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'gg-map-key',
                            'type' => 'text',
                            'title' => __("Map API Key", ET_DOMAIN) ,
                            'name' => 'gg_map_apikey',
                            'class' => 'option-item bg-grey-input ',
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Map Settings", ET_DOMAIN) ,
                        'id' => 'map-center',
                        'class' => '',
                        'desc' => __("Enter the address you want to set center.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'map-center-field',
                            'type' => 'map',
                            'title' => __("Map Default Center", ET_DOMAIN) ,
                            'name' => 'map_center_default',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __("Default center address", ET_DOMAIN)
                        ),
                        array(
                            'id' => 'map-zoom-field',
                            'type' => 'text',
                            'title' => __("Map Zoom", ET_DOMAIN) ,
                            'label' => __("Map Default Zoom", ET_DOMAIN) ,
                            'name' => 'map_zoom_default',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __("Map Default Zoom", ET_DOMAIN)
                        ),
                        array(
                            'id' => 'mobile-map-zoom-field',
                            'type' => 'text',
                            'title' => __("Mobile Map Zoom", ET_DOMAIN) ,
                            'label' => __("Mobile Map Default Zoom", ET_DOMAIN) ,
                            'name' => 'mobile_map_zoom_default',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __("Mobile Map Default Zoom", ET_DOMAIN)
                        ),
                        
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Displaying Place Labels", ET_DOMAIN) ,
                        'id' => 'map_typestyle',
                        'class' => '',
                        'desc' => __("Enable this option if you want all place labels on google map to be displayed.", ET_DOMAIN)
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'map-typestyle-field',
                            'type' => 'switch',
                            'label' => __("Display Map Type Style", ET_DOMAIN) ,
                            'name' => 'map_typestyle',
                            'class' => 'option-item bg-grey-input ',
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Display map marker in single place page", ET_DOMAIN) ,
                        'id' => 'fitbounds',
                        'class' => '',
                        'desc' => __("If you enable this option, only map marker of the current place will be displayed.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'single-map-marker',
                            'type' => 'switch',
                            'label' => __("Enable will display multiple map marker icons in category of current place.", ET_DOMAIN) ,
                            'name' => 'single_map_marker',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,    
                array(
                    'args' => array(
                        'title' => __("Fitbounds", ET_DOMAIN) ,
                        'id' => 'fitbounds',
                        'class' => '',
                        'desc' => __("Sets the viewport to contain all markers.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'map-fitBounds-field',
                            'type' => 'switch',
                            'label' => __("Sets the viewport to contain all markers", ET_DOMAIN) ,
                            'name' => 'fitbounds',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Geolocation", ET_DOMAIN) ,
                        'id' => 'geolocation',
                        'class' => '',
                        'desc' => __("Enabling this will require users to provide their current location.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'map-geolocation',
                            'type' => 'switch',
                            'title' => __("Geolocation", ET_DOMAIN) ,
                            'name' => 'geolocation',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __("Geolocation", ET_DOMAIN),
                            'lable' => __("geolocation", ET_DOMAIN)
                        )
                    )
                ) ,
                // array(
                //     'args' => array(
                //         'title' => __("Geolocation icon", ET_DOMAIN) ,
                //         'id' => 'geolocation_icon',
                //         'class' => '',
                //         'name' => '',
                //         'desc' => __("Your icon should be in PNG, GIF or JPG format, within 150x50px and less than 1500Kb.", ET_DOMAIN)
                //     ) ,

                //     'fields' => array(
                //         array(
                //             'id' => 'geolocation_icon',
                //             'type' => 'image',
                //             'title' => __("Site Logo", ET_DOMAIN) ,
                //             'name' => 'geolocation_icon',
                //             'class' => '',
                //             'size' => array(
                //                 '40',
                //                 '40'
                //             )
                //         )
                //     )
                // ),
                array(
                    // Units of measurement
                    'args' => array(
                        'title' => __("Units of measurement", ET_DOMAIN) ,
                        'id' => 'unit_measurement',
                        'class' => '',
                        'desc' => __("Select the unit of measurement when your user search nearby.", ET_DOMAIN)
                    ) ,
                    
                    'fields' => array(
                        array(
                            'id' => 'map-unit_measurement',
                            'type' => 'select',
                            'data' => array('mile' => __("Miles", ET_DOMAIN),'km' => __("Kilometers", ET_DOMAIN) ),
                            'title' => __("Units of measurement", ET_DOMAIN) ,
                            'name' => 'unit_measurement',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __("Units of measurement", ET_DOMAIN),
                            'lable' => __("Units of measurement", ET_DOMAIN)
                        )
                    )
                ),
                array(
                    // Nearby distance
                    'args' => array(
                        'title' => __("Nearby distance", ET_DOMAIN) ,
                        'id' => 'nearby_distance',
                        'class' => '',
                        'desc' => __("Enter a distance number for your user search nearby.", ET_DOMAIN)
                    ) ,
                  
                    'fields' => array(
                        array(
                            'id' => 'nearby-distance',
                            'type' => 'text',
                            'title' => __("Mobile Nearby Distance", ET_DOMAIN) ,
                            'name' => 'nearby_distance',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __("Mobile Nearby Distance", ET_DOMAIN)
                        )
                    )
                ),
            )
        );
        /**
         * Content section
         */
        $sections[] = array(
            
            'args' => array(
                'title' => __("Content", ET_DOMAIN) ,
                'id' => 'content-settings',
                'icon' => 'l',
                'class' => ''
            ) ,
            
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Maximum number of pictures in gallery", ET_DOMAIN) ,
                        'id' => 'max-carousel',
                        'class' => 'max-carousel',
                        'desc' => __("Set up how many pictures a place can have.", ET_DOMAIN)
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'max_carousel',
                            'type' => 'text',
                            'title' => __("Max Number Of Place Gallery", ET_DOMAIN) ,
                            'name' => 'max_carousel',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Post Images In A Comment/Review", ET_DOMAIN) ,
                        'id' => 'show-carousel-comment',
                        'class' => 'show-carousel-comment',                        
                        'desc' => __('Allow users attach photos in their comments/reviews.', ET_DOMAIN)
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'post_image_comment',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'post_image_comment',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Maximum number of pictures in a comment/review", ET_DOMAIN) ,
                        'id' => 'max-carousel-comment',
                        'class' => 'max-carousel-comment',
                        'desc' => __("Set up the number of images users can attach in a comment/review.", ET_DOMAIN)
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'max_carousel_comment',
                            'type' => 'text',
                            'title' => __("Max Number Of Comment/Review Gallery", ET_DOMAIN) ,
                            'name' => 'max_carousel_comment',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Pending Post", ET_DOMAIN) ,
                        'id' => 'pending-post',
                        'class' => 'pending-post',
                        'desc' => __("Enabling this will make every new place post pending until you review and approve it manually.", ET_DOMAIN) ,
                        
                        // 'name' => 'currency'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'use_pending',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'use_pending',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("View counter", ET_DOMAIN) ,
                        'id' => 'view-counter',
                        'class' => 'view-counter',
                        'desc' => __("Enable view counter.", ET_DOMAIN) ,

                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'enable_view_counter',
                            'type' => 'switch',
                            'title' => __("View counter", ET_DOMAIN) ,
                            'name' => 'enable_view_counter',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),

                array(
                    'args' => array(
                        'title' => __("Maximum Number of Categories", ET_DOMAIN) ,
                        'id' => 'max-categories',
                        'class' => 'max-categories',
                        'desc' => __("Set a maximum number of categories a place can assign to", ET_DOMAIN)
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'max_cat',
                            'type' => 'text',
                            'title' => __("Max Number Of Place Categories", ET_DOMAIN) ,
                            'name' => 'max_cat',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ),
            
                // array(
                //     'args' => array(
                //         'title' => __("Order categories list by", ET_DOMAIN) ,
                //         'id' => 'categories-order-by',
                //         'class' => 'categories-order-by',
                //         'desc' => __("Order list place categories by", ET_DOMAIN)
                        
                //     ) ,
                //     'fields' => array(
                //         array(
                //             'id' => 'place_category_order',
                //             'type' => 'select',
                //             'data' => array('name' => 'name', 'id' => 'ID', 'slug' => "slug", 'count' => 'count' ),
                //             'title' => __("Order categories list by", ET_DOMAIN) ,
                //             'name' => 'place_category_order',
                //             'class' => 'option-item bg-grey-input '
                //         )
                //     )
                // ),

                array(
                    'type' => 'cat',
                    'args' => array(
                        'title'     => __("Place Category", ET_DOMAIN) ,
                        'taxonomy'  => 'place_category',
                        'id'        => 'place_category',
                        'class'     => '',
                        'name'      => 'place-category',
                        'desc'      => __("Place Category.", ET_DOMAIN) ,
                        'use_icon'  => 1,
                        'use_color' => 1,
                    ) ,
                    'fields' => array()
                ) ,
                array(
                    'type' => 'cat',
                    'args' => array(
                        'title'     => __("Location", ET_DOMAIN) ,
                        'taxonomy'  => 'location',
                        'id'        => 'location',
                        'class'     => '',
                        'name'      => 'location',
                        'desc'      => __("Location.", ET_DOMAIN) ,
                        'use_icon'  => 0,
                        'use_color' => 0,
                    ) ,
                    'fields' => array()
                )
            )
        );
        /**
         * slug settings
        */
        $sections[] = array(
            'args' => array(
                'title' => __("Url slug", ET_DOMAIN) ,
                'id' => 'Url-Slug',
                'icon' => 'i',
                'class' => ''
            ) ,
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Place", ET_DOMAIN) ,
                        'id' => 'place-slug',
                        'class' => 'list-package',
                        'desc' => __("Enter slug for your Single Place page", ET_DOMAIN) ,
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'place_slug',
                            'type' => 'text',
                            'title' => __("Single Place page Slug", ET_DOMAIN) ,
                            'name' => 'place_slug',
                            'placeholder' => __("Single Place page Slug", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Place Archives", ET_DOMAIN) ,
                        'id' => 'place-archive_slug',
                        'class' => 'list-package',
                        'desc' => __("Enter slug for your Archive Places page", ET_DOMAIN) ,
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'place_archive_slug',
                            'type' => 'text',
                            'title' => __("Archive Places page Slug", ET_DOMAIN) ,
                            'name' => 'place_archive_slug',
                            'placeholder' => __("Archive Places page Slug", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Place Category", ET_DOMAIN) ,
                        'id' => 'place-Category',
                        'class' => 'list-package',
                        'desc' => __("Enter slug for your Archive Places page", ET_DOMAIN) ,
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'place_category_slug',
                            'type' => 'text',
                            'title' => __("Places Category page Slug", ET_DOMAIN) ,
                            'name' => 'place_category_slug',
                            'placeholder' => __("Places Category page Slug", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Place Location", ET_DOMAIN) ,
                        'id' => 'place-Location',
                        'class' => 'list-package',
                        'desc' => __("Enter slug for your Archive Places page", ET_DOMAIN) ,
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'place_location_slug',
                            'type' => 'text',
                            'title' => __("Places Location page Slug", ET_DOMAIN) ,
                            'name' => 'place_location_slug',
                            'placeholder' => __("Places Location page Slug", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Place Tags", ET_DOMAIN) ,
                        'id' => 'place-tag',
                        'class' => 'list-package',
                        'desc' => __("Enter slug for your Tag Places page", ET_DOMAIN) ,
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'place_tag_slug',
                            'type' => 'text',
                            'title' => __("Places Tag page Slug", ET_DOMAIN) ,
                            'name' => 'place_tag_slug',
                            'placeholder' => __("Places Tag page Slug", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) 

            )
        );
        /**
         * google captcha settings section
         */
        $sections['gg_captcha'] = array(
            'args' => array(
                'title' => __("Captcha", ET_DOMAIN) ,
                'id'    => 'gg-captcha',
                'icon'  => '3',
                'class' => ''
            ),
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Google reCaptcha", ET_DOMAIN) ,
                        'id'    => 'google-recaptcha',
                        'class' => '',
                        'desc'  => __("Enabling this will prevent spammers to register.<a href='https://www.google.com/recaptcha/admin#list' target='_blank' rel='nofollow'>get key</a>", ET_DOMAIN)
                    ),
                    'fields' => array(
                        array(
                            'id'    => 'gg_captcha',
                            'type'  => 'switch',
                            'title' => __("Google reCaptcha", ET_DOMAIN) ,
                            'name'  => 'gg_captcha',
                            'class' => ''
                        ),
                        array(
                            'id'          => 'gg_site_key',
                            'type'        => 'text',
                            'title'       => __("Site key", ET_DOMAIN) ,
                            'name'        => 'gg_site_key',
                            'placeholder' => __("reCaptcha Site Key", ET_DOMAIN) ,
                            'class'       => ''
                        ),
                        array(
                            'id'          => 'gg_secret_key',
                            'type'        => 'text',
                            'title'       => __("Secret key", ET_DOMAIN) ,
                            'name'        => 'gg_secret_key',
                            'placeholder' => __("reCaptcha Secret Key", ET_DOMAIN) ,
                            'class'       => ''
                        )
                    )
                )
            )
        );
        /**
         * license key settings
         */
        $sections[] = array(
            'args' => array(
                'title' => __("Payment", ET_DOMAIN) ,
                'id' => 'payment-settings',
                'icon' => '%',
                'class' => ''
            ) ,
            
            'groups' => array(
                
                array(
                    'args' => array(
                        'title' => __("Payment Currency", ET_DOMAIN) ,
                        'id' => 'payment-currency',
                        'class' => 'list-package',
                        'desc' => __("Enter currency code and sign ....", ET_DOMAIN) ,
                        'name' => 'currency'
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'currency-code',
                            'type' => 'text',
                            'title' => __("Code", ET_DOMAIN) ,
                            'name' => 'code',
                            'placeholder' => __("Code", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        ) ,
                        array(
                            'id' => 'currency-code',
                            'type' => 'text',
                            'title' => __("Sign", ET_DOMAIN) ,
                            'name' => 'icon',
                            'placeholder' => __("Sign", ET_DOMAIN) ,
                            'class' => 'option-item bg-grey-input '
                        ) ,
                        array(
                            'id' => 'currency-code',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'align',
                            
                            // 'label' => __("Code", ET_DOMAIN),
                            'class' => 'option-item bg-grey-input ',
                            'label_1' => __("Left", ET_DOMAIN) ,
                            'label_2' => __("Right", ET_DOMAIN) ,
                        ) ,
                    )
                ) ,
                
                array(
                    'args' => array(
                        'title' => __("Free to submit place", ET_DOMAIN) ,
                        'id' => 'free-to-submit-place',
                        'class' => 'free-to-submit-place',
                        'desc' => __("Enabling this will allow users to submit place free.", ET_DOMAIN) ,
                        
                        // 'name' => 'currency'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'disable-plan',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'disable_plan',
                            'class' => 'option-item bg-grey-input '
                        ),
                        array(
                            'id' => 'disable-plan',
                            'type' => 'text',
                            'title' => __("Number of event", ET_DOMAIN) ,
                            'name' => 'number_event',
                            'class' => 'option-item bg-grey-input ', 
                            // 'placeholder' => __("number of events", ET_DOMAIN), 
                            'label' => __("Number of event per listing", ET_DOMAIN), 
                        )
                    )
                ) ,

                /* payment test mode settings */
                array(
                    'args' => array(
                        'title' => __("Payment Test Mode", ET_DOMAIN) ,
                        'id' => 'payment-test-mode',
                        'class' => 'payment-test-mode',
                        'desc' => __("Enabling this will allow you to test payment without charging your account.", ET_DOMAIN) ,
                        
                        // 'name' => 'currency'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'test-mode',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'test_mode',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) , // payment test mode

                /* payment gateways settings */
                array(
                    'args' => array(
                        'title' => __("Payment Gateways", ET_DOMAIN) ,
                        'id' => 'payment-gateways',
                        'class' => 'payment-gateways',
                        'desc' => __("Set payment plans your users can choose when posting new places.", ET_DOMAIN) ,
                        
                        // 'name' => 'currency'
                        
                    ) ,
                    'fields' => array()
                ) ,
                
                array(
                    'args' => array(
                        'title' => __("Paypal", ET_DOMAIN) ,
                        'id' => 'Paypal',
                        'class' => 'payment-gateway',
                        'desc' => __("Enabling this will allow your users to pay through PayPal", ET_DOMAIN) ,
                        
                        'name' => 'paypal'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'paypal',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'enable',
                            'class' => 'option-item bg-grey-input '
                        ) ,
                        array(
                            'id' => 'paypal_mode',
                            'type' => 'text',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'api_username',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __('Enter your PayPal email address', ET_DOMAIN)
                        )
                    )
                ) ,
                
                array(
                    'args' => array(
                        'title' => __("2Checkout", ET_DOMAIN) ,
                        'id' => '2Checkout',
                        'class' => 'payment-gateway',
                        'desc' => __("Enabling this will allow your users to pay through 2Checkout", ET_DOMAIN) ,
                        
                        'name' => '2checkout'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => '2Checkout_mode',
                            'type' => 'switch',
                            'title' => __("2Checkout mode", ET_DOMAIN) ,
                            'name' => 'enable',
                            'class' => 'option-item bg-grey-input '
                        ) ,
                        array(
                            'id' => 'sid',
                            'type' => 'text',
                            'title' => __("Sid", ET_DOMAIN) ,
                            'name' => 'sid',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __('Your 2Checkout Seller ID', ET_DOMAIN)
                        ),
                        array(
                            'id' => 'secret_key',
                            'type' => 'text',
                            'title' => __("Secret Key", ET_DOMAIN) ,
                            'name' => 'secret_key',
                            'class' => 'option-item bg-grey-input ',
                            'placeholder' => __('Your 2Checkout Secret Key', ET_DOMAIN)
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Cash", ET_DOMAIN) ,
                        'id' => 'Cash',
                        'class' => 'payment-gateway',
                        'desc' => __("Enabling this will allow your user to send cash to your bank account.", ET_DOMAIN) ,
                        
                        'name' => 'cash'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'cash_message_enable',
                            'type' => 'switch',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'enable',
                            'class' => 'option-item bg-grey-input '
                        ) ,
                        array(
                            'id' => 'cash_message',
                            'type' => 'editor',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'cash_message',
                            'class' => 'option-item bg-grey-input ',
                            // 'placeholder' => __('Enter your PayPal email address', ET_DOMAIN)
                        )
                    )
                ) , // end payment gateways
                    
                /**
                 * package plan list
                */
                array(
                    'type' => 'list',
                    'args' => array(
                        'title' => __("Payment Plans", ET_DOMAIN) ,
                        'id' => 'list-package',
                        'class' => 'list-package',
                        'desc' => '',
                        'name' => 'payment_package',
                    ) ,
                    
                    'fields' => array(
                        'form' => '/admin-template/package-form.php',
                        'form_js' => '/admin-template/package-form-js.php',
                        'js_template' => '/admin-template/package-js-item.php',
                        'template' => '/admin-template/package-item.php'
                    )
                ),
                // limit_free_plan
                array(
                    'args' => array(
                        'title' => __("Limit Free Plan Use", ET_DOMAIN) ,
                        'id' => 'limit_free_plan',
                        'class' => 'limit_free_plan',
                        'desc' => __("Enter the maximum number allowed for employers to use your Free plan (plan with price is 0)", ET_DOMAIN)
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'cash_message_enable',
                            'type' => 'text',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'limit_free_plan',
                            'class' => 'option-item bg-grey-input '
                        )
                    )
                ) ,
            )
        );
        
        /**
         * mail template settings section
         */
        $sections[] = array(
            'args' => array(
                'title' => __("Mailing", ET_DOMAIN) ,
                'id' => 'mail-settings',
                'icon' => 'M',
                'class' => ''
            ) ,
            
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Authentication Mail Template", ET_DOMAIN) ,
                        'id' => 'mail-description-group',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'mail-description',
                            'type' => 'desc',
                            'title' => __("Mail description here", ET_DOMAIN) ,
                            'text' => __("Email templates for authentication process. You can use placeholders to include some specific content.", ET_DOMAIN) . '<a class="icon btn-template-help payment" data-icon="?" href="#" title="View more details"></a>' . '<div class="cont-template-help payment-setting">
                                                    [user_login],[display_name],[user_email] : ' . __("user's details you want to send mail", ET_DOMAIN) . '<br />
                                                    [dashboard] : ' . __("member dashboard url ", ET_DOMAIN) . '<br />
                                                    [title], [link], [excerpt],[desc] : ' . __("question title, link and details", ET_DOMAIN) . ' <br />
                                                    [activate_url] : ' . __("activate link is require for user to renew their pass", ET_DOMAIN) . ' <br />
                                                    [cash_message] : '.__("cash message when user pay success", ET_DOMAIN).' </br/>
                                                    [site_url],[blogname],[admin_email] : ' . __(" site info, admin email", ET_DOMAIN) . '
                                                </div>',
                            
                            'class' => '',
                            'name' => 'mail_description'
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Register Mail Template", ET_DOMAIN) ,
                        'id' => 'register-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'register_mail_template',
                            'type' => 'editor',
                            'title' => __("Register Mail", ET_DOMAIN) ,
                            'name' => 'register_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ) ,

                array(
                    'args' => array(
                        'title' => __("Confirm Mail Template", ET_DOMAIN) ,
                        'id' => 'confirm-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'confirm_mail_template',
                            'type' => 'editor',
                            'title' => __("Confirme Mail", ET_DOMAIN) ,
                            'name' => 'confirm_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ) ,

                array(
                    'args' => array(
                        'title' => __("Confirmed Mail Template", ET_DOMAIN) ,
                        'id' => 'confirmed-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'confirmed_mail_template',
                            'type' => 'editor',
                            'title' => __("Confirmed Mail", ET_DOMAIN) ,
                            'name' => 'confirmed_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ) ,

                array(
                    'args' => array(
                        'title' => __("Forgotpass Mail Template", ET_DOMAIN) ,
                        'id' => 'forgotpass-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'forgotpass_mail_template',
                            'type' => 'editor',
                            'title' => __("Register Mail", ET_DOMAIN) ,
                            'name' => 'forgotpass_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Resetpass Mail Template", ET_DOMAIN) ,
                        'id' => 'resetpass-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'resetpass_mail_template',
                            'type' => 'editor',
                            'title' => __("Resetpassword Mail", ET_DOMAIN) ,
                            'name' => 'resetpass_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Inbox Mail Template", ET_DOMAIN) ,
                        'id' => 'inbox-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'inbox_mail_template',
                            'type' => 'editor',
                            'title' => __("Inbox Mail", ET_DOMAIN) ,
                            'name' => 'inbox_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Place Mail Template", ET_DOMAIN) ,
                        'id' => 'mail-description-group',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'mail-description',
                            'type' => 'desc',
                            'title' => __("Mail description here", ET_DOMAIN) ,
                            'text' => __("Email templates for place process. You can use placeholders to include some specific content.", ET_DOMAIN) . '<a class="icon btn-template-help payment" data-icon="?" href="#" title="View more details"></a>' . '<div class="cont-template-help payment-setting">
                                                    [user_login],[display_name],[user_email] : ' . __("user's details you want to send mail", ET_DOMAIN) . '<br />
                                                    [dashboard] : ' . __("member dashboard url ", ET_DOMAIN) . '<br />
                                                    [title], [link], [excerpt],[desc] : ' . __("question title, link and details", ET_DOMAIN) . ' <br />
                                                    [activate_url] : ' . __("activate link is require for user to renew their pass", ET_DOMAIN) . ' <br />
                                                    [cash_message] : '.__("cash message when user pay success", ET_DOMAIN).' </br/>
                                                    [site_url],[blogname],[admin_email] : ' . __(" site info, admin email", ET_DOMAIN) . '
                                                </div>',
                            
                            'class' => '',
                            'name' => 'mail_description'
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Publish Mail Template", ET_DOMAIN) ,
                        'id' => 'publish-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'publish_mail_template',
                            'type' => 'editor',
                            'title' => __("publish Mail", ET_DOMAIN) ,
                            'name' => 'publish_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Archive Mail Template", ET_DOMAIN) ,
                        'id' => 'archive-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'archive_mail_template',
                            'type' => 'editor',
                            'title' => __("archive Mail", ET_DOMAIN) ,
                            'name' => 'archive_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Reject Mail Template", ET_DOMAIN) ,
                        'id' => 'reject-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'reject_mail_template',
                            'type' => 'editor',
                            'title' => __("reject Mail", ET_DOMAIN) ,
                            'name' => 'reject_mail_template',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Cash Notification Mail Template", ET_DOMAIN) ,
                        'id' => 'cash-mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'cash_notification_mail',
                            'type' => 'editor',
                            'title' => __("Cash Notification Mail", ET_DOMAIN) ,
                            'name' => 'cash_notification_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Receipt Mail Template", ET_DOMAIN) ,
                        'id' => 'ae-receipt_mail',
                        'class' => '',
                        'name' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'ae_receipt_mail',
                            'type' => 'editor',
                            'title' => __("Receipt Mail Template", ET_DOMAIN) ,
                            'name' => 'ae_receipt_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Report Mail Template", ET_DOMAIN) ,
                        'id'    => 'ae-report_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_report_mail',
                            'type'  => 'editor',
                            'title' => __("Report Mail Template", ET_DOMAIN) ,
                            'name'  => 'ae_report_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Claim Mail Template", ET_DOMAIN) ,
                        'id'    => 'ae-claim_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_claim_mail',
                            'type'  => 'editor',
                            'title' => __("Claim Mail Template", ET_DOMAIN) ,
                            'name'  => 'ae_claim_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Approve Claim Mail Template", ET_DOMAIN) ,
                        'id'    => 'ae-approve_claim_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_approve_claim_mail',
                            'type'  => 'editor',
                            'title' => __("Approve Claim Mail Template", ET_DOMAIN) ,
                            'name'  => 'ae_approve_claim_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Reject Claim Mail Template", ET_DOMAIN) ,
                        'id'    => 'ae-reject_claim_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_reject_claim_mail',
                            'type'  => 'editor',
                            'title' => __("Reject Claim Mail Template", ET_DOMAIN) ,
                            'name'  => 'ae_reject_claim_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("New Review Mail Template", ET_DOMAIN) ,
                        'id'    => 'ae-comment_place_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_comment_place_mail',
                            'type'  => 'editor',
                            'title' => __("Comment Place Mail Template", ET_DOMAIN) ,
                            'name'  => 'ae_comment_place_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("New Event Mail Template", ET_DOMAIN) ,
                        'id'    => 'ae-event_place_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_event_place_mail',
                            'type'  => 'editor',
                            'title' => __("Comment Place Mail Template", ET_DOMAIN) ,
                            'name'  => 'ae_event_place_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Cash Payment Review Email Template", ET_DOMAIN) ,
                        'id'    => 'ae-pending_cash_notification_mail',
                        'class' => '',
                        'name'  => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id'    => 'ae_pending_cash_notification_mail',
                            'type'  => 'editor',
                            'title' => __("Cash Payment Review Email Template", ET_DOMAIN) ,
                            'name'  => 'ae_pending_cash_notification_mail',
                            'class' => '',
                            'reset' => 1
                        )
                    )
                ),
            )


        );
        
        /**
         * language settings
         */
        $sections[] = array(
            'args' => array(
                'title' => __("Language", ET_DOMAIN) ,
                'id' => 'language-settings',
                'icon' => 'G',
                'class' => ''
            ) ,
            
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Website Language", ET_DOMAIN) ,
                        'id' => 'website-language',
                        'class' => '',
                        'name' => '',
                        'desc' => __("Select the language you want to use for your website.", ET_DOMAIN)
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'forgotpass_mail_template',
                            'type' => 'language_list',
                            'title' => __("Register Mail", ET_DOMAIN) ,
                            'name' => 'website_language',
                            'class' => ''
                        )
                    )
                ) ,
                array(
                    'args' => array(
                        'title' => __("Translator", ET_DOMAIN) ,
                        'id' => 'translator',
                        'class' => '',
                        'name' => 'translator',
                        'desc' => __("Translate a language", ET_DOMAIN)
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'translator-field',
                            'type' => 'translator',
                            'title' => __("Register Mail", ET_DOMAIN) ,
                            'name' => 'translate',
                            'class' => ''
                        )
                    )
                )
            )
        );
        
        /**
         * license key settings
         */
        $sections[] = array(
            'args' => array(
                'title' => __("License", ET_DOMAIN) ,
                'id' => 'update-settings',
                'icon' => 'K',
                'class' => ''
            ) ,
            
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("License Key", ET_DOMAIN) ,
                        'id' => 'license-key',
                        'class' => '',
                        'desc' => ''
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'et_license_key',
                            'type' => 'text',
                            'title' => __("License Key", ET_DOMAIN) ,
                            'name' => 'et_license_key',
                            'class' => ''
                        )
                    )
                )
            )
        );
        
        $temp = array();
        $options = AE_Options::get_instance();
        
        foreach ($sections as $key => $section) {
            $temp[] = new AE_section($section['args'], $section['groups'], $options);
        }
        
        $pages = array();
        /**
         * overview container
         */
        $container = new AE_Overview(array(
            'place'
        ) , true);
        
        //$statics      =   array();
        // $header      =   new AE_Head( array( 'page_title'    => __('Overview', ET_DOMAIN),
        //                                  'menu_title'    => __('OVERVIEW', ET_DOMAIN),
        //                                  'desc'          => __("Overview", ET_DOMAIN) ) );
        $pages[] = array(
            'args' => array(
                'parent_slug' => 'et-overview',
                'page_title' => __('Overview', ET_DOMAIN) ,
                'menu_title' => __('OVERVIEW', ET_DOMAIN) ,
                'cap' => 'administrator',
                'slug' => 'et-overview',
                'icon' => 'L',
                'desc' => sprintf(__("%s overview", ET_DOMAIN) , $options->blogname)
            ) ,
            'container' => $container,
            
            // 'header' => $header
            
            
        );
        
        /**
         * setting view
         */
        $container = new AE_Container(array(
            'class' => '',
            'id' => 'settings'
        ) , $temp, '');
        $pages[] = array(
            'args' => array(
                'parent_slug' => 'et-overview',
                'page_title' => __('Settings', ET_DOMAIN) ,
                'menu_title' => __('SETTINGS', ET_DOMAIN) ,
                'cap' => 'administrator',
                'slug' => 'et-settings',
                'icon' => 'y',
                'desc' => __("Manage how your DirectoryEngine looks and feels", ET_DOMAIN)
            ) ,
            'container' => $container
        );      
        /**
         * user list view
         */
        
        $container = new AE_UsersContainer(array(
            'filter' => array(
                'moderate'
            )
        ));
        $pages[] = array(
            'args' => array(
                'parent_slug' => 'et-overview',
                'page_title' => __('Members', ET_DOMAIN) ,
                'menu_title' => __('MEMBERS', ET_DOMAIN) ,
                'cap' => 'administrator',
                'slug' => 'et-users',
                'icon' => 'g',
                'desc' => __("Overview of registered members", ET_DOMAIN)
            ) ,
            'container' => $container
        );

        /**
         * order list view
         */        
        $orderlist = new AE_OrderList(array());
        $pages[] = array(
            'args' => array(
                'parent_slug' => 'et-overview',
                'page_title' => __('Payments', ET_DOMAIN) ,
                'menu_title' => __('PAYMENTS', ET_DOMAIN) ,
                'cap' => 'administrator',
                'slug' => 'et-payments',
                'icon' => '%',
                'desc' => __("Overview of all payments", ET_DOMAIN)
            ) ,
            'container' => $orderlist
        );
        /**
         * setup wizard view
         */
        $container = new AE_Wizard();
        $pages[] = array(
            'args' => array(
                'parent_slug' => 'et-overview',
                'page_title'  => __('Setup Wizard', ET_DOMAIN) ,
                'menu_title'  => __('SETUP WIZARD', ET_DOMAIN) ,
                'cap'         => 'administrator',
                'slug'        => 'et-wizard',
                'icon'        => 'S',
                'desc'        => __("Set up and manage every content of your site", ET_DOMAIN)
            ) ,
            'container' => $container
        );

        ///////////////////////////////
        // page for upgrade database //
        ///////////////////////////////
        $db_sections = array(
            'args' => array(
                'title' => __("Upgrade Database", ET_DOMAIN) ,
                'id' => 'upgrade_databse',
                'icon' => '~',
                'class' => ''
            ) ,
            
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __("Add 'et_featured' key", ET_DOMAIN) ,
                        'id' => 'add-et-featured-post',
                        'class' => 'add-et-featured',
                        'desc' => __('Add the meta data "et_featured = 0" to the place that does not have the meta key.', ET_DOMAIN) ,
                        
                        // 'name' => 'currency'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'de_is_update_et_featured',
                            'type' => 'button',
                            'title' => __("Align", ET_DOMAIN) ,
                            'name' => 'de_is_update_et_featured',
                            'class' => '',
                            'value' => 'Update Featured',
                            'action' => 'de_update_et_featured_key'
                            
                        )
                    )
                ),
                array(
                    'args' => array(
                        'title' => __("Add 'Rating Score' key", ET_DOMAIN) ,
                        'id' => 'add-rating_score-post',
                        'class' => 'add-rating_score',
                        'desc' => __('Add the meta data "rating_score = 0" to the place that does not have the meta key.', ET_DOMAIN) ,
                        
                        // 'name' => 'currency'
                        
                    ) ,
                    'fields' => array(
                        array(
                            'id' => 'de_is_update_rating_score',
                            'type' => 'button',
                            'name' => 'de_is_update_rating_score',
                            'class' => '',
                            'value' => 'Update Rating Score',
                            'action' => 'de_update_rating_score'
                            
                        )
                    )
                )
                
            )
        );
        
        $temp = new AE_section($db_sections['args'], $db_sections['groups'], $options);
        
        $update_db_settings = new AE_container(array(
            'class' => 'field-settings',
            'id' => 'settings',
        ) , $temp, $options );
        
        $pages[] = array(
            'args' => array(
                'parent_slug' => 'et-overview',
                'page_title' => __('Upgrade Database', ET_DOMAIN) ,
                'menu_title' => __('UPGRADE DATABASE', ET_DOMAIN) ,
                'cap' => 'administrator',
                'slug' => 'ae-upgrade-db',
                'icon' => '~',
                'desc' => __("You must upgrade the database to make DirectoryEngine work properly.", ET_DOMAIN)
            ) ,
            'container' => $update_db_settings
        );

        /**
         *  filter pages config params so user can hook to here
         */
        $pages = apply_filters('ae_admin_menu_pages', $pages);
        
        /**
         * add menu page
         */
        $this->admin_menu = new AE_Menu($pages);
        
        /**
         * add sub menu page
         */
        foreach ($pages as $key => $page) {
            new AE_Submenu($page, $pages);
        }
    }
}

