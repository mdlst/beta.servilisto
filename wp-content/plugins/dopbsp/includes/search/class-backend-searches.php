<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : includes/searches/class-backend-searches.php
* File Version            : 1.0.4
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end searches PHP class.
*/

    if (!class_exists('DOPBSPBackEndSearches')){
        class DOPBSPBackEndSearches extends DOPBSPBackEnd{
            /*
             * Constructor
             */
            function __construct(){
            }
        
            /*
             * Prints out the searches page.
             * 
             * @return HTML page
             */
            function view(){
                global $DOPBSP;
                
                $DOPBSP->views->backend_searches->template();
            }
                
            /*
             * Display searches list.
             * 
             * @return searches list HTML
             */      
            function display(){
                global $wpdb;
                global $DOPBSP;
                                    
                $html = array();
                
                $searches = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->searches.' WHERE user_id=%d OR user_id=0 ORDER BY id DESC',
                                                              wp_get_current_user()->ID));
                
                /* 
                 * Create searches list HTML.
                 */
                array_push($html, '<ul>');
                
                if ($wpdb->num_rows != 0){
                    if ($searches){
                        foreach ($searches as $search){
                            array_push($html, $this->listItem($search));
                        }
                    }
                }
                else{
                    array_push($html, '<li class="dopbsp-no-data">'.$DOPBSP->text('SEARCHES_NO_SEARCHES').'</li>');
                }
                array_push($html, '</ul>');
                
                echo implode('', $html);
                
            	die();                
            }
            
            /*
             * Returns searches list item HTML.
             * 
             * @param search (object): search data
             * 
             * @return search list item HTML
             */
            function listItem($search){
                global $DOPBSP;
                
                $html = array();
                $user = get_userdata($search->user_id); // Get data about the user who created the searches.
                
                array_push($html, '<li class="dopbsp-item" id="DOPBSP-search-ID-'.$search->id.'" onclick="DOPBSPBackEndSearch.display('.$search->id.')">');
                array_push($html, ' <div class="dopbsp-header">');
                
                /*
                 * Display search ID.
                 */
                array_push($html, '     <span class="dopbsp-id">ID: '.$search->id.'</span>');
                
                /*
                 * Display data about the user who created the search.
                 */
                if ($search->user_id > 0){
                    array_push($html, '     <span class="dopbsp-header-item dopbsp-avatar">'.get_avatar($search->user_id, 17));
                    array_push($html, '         <span class="dopbsp-info">'.$DOPBSP->text('SEARCHES_CREATED_BY').': '.$user->data->display_name.'</span>');
                    array_push($html, '         <br class="dopbsp-clear" />');
                    array_push($html, '     </span>');
                }
                array_push($html, '     <br class="dopbsp-clear" />');
                array_push($html, ' </div>');
                array_push($html, ' <div class="dopbsp-name">'.($search->name == '' ? '&nbsp;':$search->name).'</div>');
                array_push($html, '</li>');
                
                return implode('', $html);
            }
        }
    }