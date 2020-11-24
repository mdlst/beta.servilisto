<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : includes/search/class-backend-search.php
* File Version            : 1.0.2
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search PHP class.
*/

    if (!class_exists('DOPBSPBackEndSearch')){
        class DOPBSPBackEndSearch extends DOPBSPBackEndSearches{
            private $views;
            
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Add a search.
             */
            function add(){
                global $wpdb;
                global $DOPBSP;
                
                $wpdb->insert($DOPBSP->tables->searches, array('user_id' => wp_get_current_user()->ID,
                                                               'name' => $DOPBSP->text('SEARCHES_ADD_SEARCH_NAME')));
                
                echo $DOPBSP->classes->backend_searches->display();

            	die();
            }
            
            /*
             * Prints out the search.
             * 
             * @post id (integer): search ID
             * @post language (string): search current editing language
             * 
             * @return search HTML
             */
            function display(){
		global $DOT;
                global $DOPBSP;
                
                $id = $DOT->post('id', 'int');
                $language = $DOT->post('language');
                
                $DOPBSP->views->backend_search->template(array('id' => $id,
                                                               'language' => $language));
                
                die();
            }
            
            /*
             * Edit search fields.
             * 
             * @post id (integer): search ID
             * @post field (string): search field
             * @post value (string): search new value
             */
            function edit(){
		global $DOT;
                global $wpdb;  
                global $DOPBSP;
                
                $id = $DOT->post('id', 'int');
                $field = $DOT->post('field');
                $value = $DOT->post('value');
                
                $wpdb->update($DOPBSP->tables->searches, array($field => $value), 
                                                         array('id' =>$id));
                
            	die();
            }
            
            /*
             * Delete search.
             * 
             * @post id (integer): search ID
             * 
             * @return number of searches left
             */
            function delete(){
		global $DOT;
                global $wpdb;
                global $DOPBSP;
                
                $id = $DOT->post('id', 'int');

                /*
                 * Delete search.
                 */
                $wpdb->delete($DOPBSP->tables->searches, array('id' => $id));
                
                /*
                 * Delete search settings.
                 */
                $wpdb->delete($DOPBSP->tables->settings_search, array('search_id' => $id));
                
                /*
                 * Count the number of remaining searches.
                 */
                $searches = $wpdb->get_results('SELECT * FROM '.$DOPBSP->tables->searches.' ORDER BY id DESC');
                
                echo $wpdb->num_rows;

            	die();
            }
        }
    }