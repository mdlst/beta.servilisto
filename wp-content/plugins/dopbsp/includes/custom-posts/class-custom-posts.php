<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.3
* File                    : includes/custom-posts/class-custom-posts.php
* File Version            : 1.0.3
* Created / Last Modified : 14 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Custom posts PHP class.
*/

    if (!class_exists('DOPBSPCustomPosts')){
        class DOPBSPCustomPosts extends DOPBSPFrontEnd{
            /*
             * Public variables.
             */
            public $custom_posts = array();
            
            /*
             * Constructor
             */
            function __construct(){
                add_action('init', array(&$this, 'init'));
            }
            
            /*
             * Initialize custom posts.
             * 
             * @param post (object): post data
             */
            function init($post){
                global $DOPBSP;
                
                if (is_admin()
                        && $DOPBSP->classes->backend_settings_users->permission(wp_get_current_user()->ID, 'use-custom-posts')
                        || !is_admin()){
                    $this->custom_posts[0] = 'default';
                    $this->custom_posts = apply_filters('dopbsp_filter_custom_posts', $this->custom_posts);
                    
                    for ($i=0; $i<count($this->custom_posts); $i++){
                        $custom_post = strtoupper($this->custom_posts[$i]);
                        
                        $postdata = array('exclude_from_search' => false,
                                          'has_archive' => true,
                                          'labels' => array('add_new' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_ADD_NEW'),
                                                            'add_new_item' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_ADD_NEW_ITEM'),
                                                            'all_items' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_ALL_ITEMS'),
                                                            'edit_item' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_EDIT_ITEM'),
                                                            'menu_name' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_MENU_NAME'),
                                                            'name' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_NAME'),
                                                            'name_admin_bar' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_NAME_ADMIN_BAR'),
                                                            'new_item' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_NEW_ITEM'),
                                                            'not_found' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_NOT_FOUND'),
                                                            'not_found_in_trash' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_NOT_FOUND_IN_TRASH'),
                                                            'parent_item_colon' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_PARENT_ITEM_COLON'),
                                                            'search_items' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_SEARH_ITEMS'),
                                                            'singular_name' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_SINGULAR_NAME'),
                                                            'view_item' => $DOPBSP->text('CUSTOM_POSTS_'.$custom_post.'_VIEW_ITEM')),
                                          'menu_icon' => $DOPBSP->paths->url.'assets/gui/images/icon-hover.png',
                                          'public' => true,
                                          'publicly_queryable' => true,
                                          'rewrite' => true,
                                          'taxonomies' => array('category', 
                                                                'post_tag'),
                                          'show_in_nav_menus' => true,
                                          'supports' => array('title', 
                                                              'editor', 
                                                              'author', 
                                                              'thumbnail', 
                                                              'excerpt', 
                                                              'trackbacks', 
                                                              'custom-fields', 
                                                              'comments', 
                                                              'revisions'));
                        register_post_type(DOPBSP_CONFIG_CUSTOM_POSTS_SLUG, $postdata);
                    }
                }
            }
            
            /*
             * Add a calendar if none is attached to the custom post.
             * 
             * @param post_id (integer): posts ID
             */
            function add($post_id){
                global $wpdb;
                global $DOPBSP;
                    
                $control_data = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE post_id=%d',
                                                                  $post_id));

                /*
                 * Create calendar if none is attached to the custom post.
                 */
                if ($wpdb->num_rows == 0){
                    /*
                     * Add calendar.
                     */
                    $DOPBSP->classes->backend_calendar->add($post_id, 
                                                            get_the_title($post_id));
                }
            }
        }
    }