<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.3
* File                    : addons/themes/beautify/includes/class-themes-beautify-translation-text.php
* File Version            : 1.0
* Created / Last Modified : 12 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Beautify theme translation text PHP class.
*/

    if (!class_exists('DOPBSPThemesBeautifyTranslationText')){
        class DOPBSPThemesBeautifyTranslationText{
            /*
             * Constructor
             */
            function __construct(){
                /*
                 * Initialize Beautify theme custom posts text.
                 */
                add_filter('dopbsp_filter_translation_text', array(&$this, 'customPosts'));
            }
            
            /*
             * Beautify theme custom posts text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function customPosts($text){
                array_push($text, array('key' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'parent' => '',
                                        'text' => 'Custom posts - Beautify theme'));
                
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_NAME',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Staff members'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_SINGULAR_NAME',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_MENU_NAME',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Staff members'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_NAME_ADMIN_BAR',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Add staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_ALL_ITEMS',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Staff members'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_ADD_NEW',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Add staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_ADD_NEW_ITEM',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Add staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_EDIT_ITEM',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Edit staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_NEW_ITEM',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'New staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_VIEW_ITEM',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'View staff member'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_SEARCH_ITEMS',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Search staff members'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_NOT_FOUND',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'No staff member(s) found.'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_NOT_FOUND_IN_TRASH',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'No staff member(s) found in trash.'));
                array_push($text, array('key' => 'CUSTOM_POSTS_BEAUTIFY_PARENT_ITEM_COLON',
                                        'parent' => 'PARENT_CUSTOM_POSTS_BEAUTIFY',
                                        'text' => 'Supervisor'));
                
                return $text;
            }
        }
    }