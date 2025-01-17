<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : includes/settings/class-backend-settings-general.php
* File Version            : 1.0.3
* Created / Last Modified : 06 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end general settings PHP class.
*/

    if (!class_exists('DOPBSPBackEndSettingsGeneral')){
        class DOPBSPBackEndSettingsGeneral extends DOPBSPBackEndSettings{
            /*
             * Constructor
             */
            function __construct(){
                add_filter('dopbsp_filter_default_settings_general', array(&$this, 'defaults'), 9);
            }
        
            /*
             * Prints out the general settings page.
             * 
             * @post id (integer): calendar ID
             * 
             * @return general settings HTML
             */
            function display(){
                global $DOPBSP;
                
                $DOPBSP->views->backend_settings_general->template(array());
                
                die();
            }
            
            /*
             * Set default general settings.
             * 
             * @param default_general (array): default general options values
             * 
             * @return default general settings array
             */
            function defaults($default_general){
                $default_general = array('dopbsp_licence_email' => '',
                                         'dopbsp_licence_instance' => '',
                                         'dopbsp_licence_key' => '',
                                         'dopbsp_licence_status' => 'deactivated',
                                         'google_map_api_key' => '',
                                         'referral_id' => '',
                                         'referral_display' => 'false');
                
                return $default_general;
            }
        }
    }