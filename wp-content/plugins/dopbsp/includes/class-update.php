<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.2.3
* File                    : includes/class-update.php
* File Version            : 1.0.5
* Created / Last Modified : 21 April 2016
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Update PHP class.
*/

    if (!class_exists('DOPBSPUpdate')){
        class DOPBSPUpdate{
            /*
             * Public variables.
             */
            public $platform = '';
            public $plugin_name = '';
            public $product_id = '';
            public $key = '';
            public $email = '';
            public $renew_url = '';
            public $instance = '';
            public $domain = '';
            public $software_version = '';
            public $plugin_or_theme = '';
            public $text_domain = '';
            
            /*
             * Constructor
             */
            function __construct(){
                add_action('init', array(&$this, 'update'));
                
                $this->init();
            }
            
            /*
             * Initialize licence data.
             */
            function init(){
                $this->platform = DOPBSP_CONFIG_SHOP_URL;
                $this->plugin_name = 'dopbsp/dopbsp.php';
                $this->product_id = 'Pinpoint Booking System Wordpress Plugin (PRO version)';
                $this->key = '';
                $this->email = '';
                $this->renew_url = $this->platform.'my-account';
                $this->instance = '';
                $this->domain = str_ireplace(array('http://', 'https://'), '', home_url());
                $this->software_version = '2.9.4';
                $this->plugin_or_theme = 'plugin';
                $this->text_domain = 'dopbsp';
            }
               
            /*
             * Check for updates.
             */
            function update(){
		global $DOT;
                global $DOPBSP;
                
                $settings_general = $DOPBSP->classes->backend_settings->values(0,  
                                                                               'general');
                $this->email = $settings_general->dopbsp_licence_email;
                $this->instance = $settings_general->dopbsp_licence_instance;
                $this->key = $settings_general->dopbsp_licence_key;
                $this->status = $settings_general->dopbsp_licence_status;
		
		/*
		 * Verify activation status on settings page.
		 */
                if ($DOT->get('page') == 'dopbsp-settings'){
		    $DOPBSP->classes->backend_settings_licences->status($this->text_domain);
		}
                
                return DOPBSPUpdateAPI::instance($this->platform,
                                                 $this->plugin_name,
                                                 $this->product_id,
                                                 $this->key,
                                                 $this->email,
                                                 $this->renew_url,
                                                 $this->instance,
                                                 $this->domain,
                                                 $this->software_version,
                                                 $this->plugin_or_theme,
                                                 $this->text_domain);
            }
        }
    }