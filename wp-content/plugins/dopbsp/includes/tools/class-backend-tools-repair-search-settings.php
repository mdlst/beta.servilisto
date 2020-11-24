<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : includes/tools/class-backend-tools-repair-search-settings.php
* File Version            : 1.0.1
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end repair search settings PHP class.
*/

    if (!class_exists('DOPBSPBackEndToolsRepairSearchSettings')){
        class DOPBSPBackEndToolsRepairSearchSettings extends DOPBSPBackEndTools{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Display repair search settings.
             * 
             * @return repair search settings HTML
             */
            function display(){
                global $DOPBSP;
                
                $DOPBSP->views->backend_tools_repair_search_settings->template();
                
                die();
            }
            
            /*
             * Get searches list.
             * 
             * @return a string with all searches IDs
             */
            function get(){
                global $wpdb;
                global $DOPBSP;
                
                $searches_list = array();
                
                /*
                 * Repair searches settings.
                 */
                $searches = $wpdb->get_results('SELECT * FROM '.$DOPBSP->tables->searches.' ORDER BY id');
                array_push($searches_list, 0);
                
                foreach ($searches as $search){
                    array_push($searches_list, $search->id);
                }
                
                echo implode(',', $searches_list);
                
                die();
            }
            
            /*
             * Repair settings for each search.
             * 
             * @post id (integer): search ID
             * @post no (integer): search position
             * 
             * @return status HTML
             */
            function set(){
		global $DOT;
                global $wpdb;
                global $DOPBSP;
                
                $id = $DOT->post('id', 'int') ? $DOT->post('id', 'int'):0;
                $no = $DOT->post('no', 'int') ? $DOT->post('no', 'int'):0;
                
                $html = array();
                
                $columns = $wpdb->get_results('DESCRIBE '.$DOPBSP->tables->settings_search);
                
                array_push($html, '<tr class="dopbsp-'.($no%2 == 0 ? 'odd':'even').'">');
                
                if ($id != 0){
                    $search = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                              $id));
                
                    array_push($html, ' <td>ID: '.$id.' - '.$search->name.'</td>');
                }
                else{
                    array_push($html, ' <td>'.$DOPBSP->text('SETTINGS_GENERAL_TITLE').'</td>');
                }
                
                if (count($columns) > 5){
                    /*
                     * Update search settings.
                     */
                    $settings_search = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->settings_search.' WHERE search_id=%d AND name=""',
                                                                       $id));
                    $default_search = $DOPBSP->classes->backend_settings->default_search;
                    
                    foreach ($default_search as $key => $default){
                        $value_data = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->settings_search.' WHERE search_id=%d AND name="%s"',
                                                                    $id, $key));
                        
                        if ($wpdb->num_rows == 0
                                && isset($settings_search->$key)
                                && $settings_search->$key != $default){
                            $wpdb->insert($DOPBSP->tables->settings_search, array('search_id' => $id,
                                                                                    'name' => $key,
                                                                                    'value' => $settings_search->$key));
                        }
                    }
                    
                    array_push($html, ' <td><span class="dopbsp-icon dopbsp-success"></span>'.$DOPBSP->text('TOOLS_REPAIR_SEARCH_SETTINGS_REPAIRED').'</td>');
                }
                else{
                    array_push($html, ' <td><span class="dopbsp-icon dopbsp-none"></span>'.$DOPBSP->text('TOOLS_REPAIR_SEARCH_SETTINGS_UNCHANGED').'</td>');
                }
                array_push($html, '</tr>');
                
                echo implode('', $html);
                
                die();
            }
            
            /*
             * Clean searches settings tables.
             */
            function clean(){
                global $wpdb;
                global $DOPBSP;
                
                /*
                 * Delete columns.
                 */
                $columns_search = $wpdb->get_results('DESCRIBE '.$DOPBSP->tables->settings_search);
                
                if (count($columns_search) > 5){
                    foreach ($columns_search as $column){
                        if ($column->Field != 'id'
                                && $column->Field != 'search_id'
                                && $column->Field != 'name'
                                && $column->Field != 'value'){
                            $wpdb->query('ALTER TABLE '.$DOPBSP->tables->settings_search.' DROP COLUMN '.$column->Field);
                        }
                    }
                }
                
                /*
                 * Delete old data.
                 */
                $wpdb->delete($DOPBSP->tables->settings_search, array('name' => ''));;
                
                die();
            }
        }
    }