<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.3
* File                    : addons/themes/beautify/dopbsp-themes-beautify.php
* File Version            : 1.0
* Created / Last Modified : 12 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Beautify theme PHP class.
*/

    DOPBSPErrorsHandler::start();
    
    try{
        /*
         * Classses
         */
        include_once 'includes/class-themes-beautify-settings.php';
        include_once 'includes/class-themes-beautify-translation-text.php';
    }
    catch(Exception $ex){
        add_action('admin_notices', 'dopbspMissingFiles');
    }
    
    DOPBSPErrorsHandler::finish();
    
    /*
     * Global classes.
     */
    global $DOPBSPThemesBeautify;

    if (!class_exists('DOPBSPThemesBeautify')){
        class DOPBSPThemesBeautify{
            /*
             * Constructor
             */
            function __construct(){
                /*
                 * Initialize Beautify theme.
                 */
                add_filter('dopbsp_filter_custom_posts', array(&$this, 'init'));
                
                /*
                 * Initialize classes.
                 */
                $this->initClasses();
            }
            
            /*
             * Initialize PHP classes.
             */
            function initClasses(){
                /*
                 * Initialize settings class. This class is the 1st initialized.
                 */
                class_exists('DOPBSPThemesBeautifySettings') ? new DOPBSPThemesBeautifySettings():'Class does not exist!';
    
                /*
                 * Initialize translation class. This class is the 2nd initialized.
                 */
                class_exists('DOPBSPThemesBeautifyTranslationText') ? new DOPBSPThemesBeautifyTranslationText():'Class does not exist!';
            }
            
            /*
             * Initialize Beautify theme custom posts.
             * 
             * @param custom_posts (array): custom posts list
             * 
             * @return update custom posts list
             */
            function init($custom_posts){
                $custom_posts[0] = 'beautify';
                
                return $custom_posts;
            }
        }
        
        $DOPBSPThemesBeautify = new DOPBSPThemesBeautify();
    }