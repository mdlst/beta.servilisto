<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : includes/settings/class-backend-settings-search.php
* File Version            : 1.0.3
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search settings PHP class.
*/

    if (!class_exists('DOPBSPBackEndSettingsSearch')){
        class DOPBSPBackEndSettingsSearch extends DOPBSPBackEndSettings{
            /*
             * Constructor
             */
            function __construct(){
                add_filter('dopbsp_filter_default_settings_search', array(&$this, 'defaults'), 9);
            }
            
            /*
             * Display search settings.
             * 
             * @post id (integer): search ID
             * 
             * @return search settings HTML
             */
            function display(){
		global $DOT;
                global $DOPBSP;
                
                $DOPBSP->views->backend_settings_search->template(array('id' => $DOT->post('id', 'int')));
                
                die();
            }
            
            /*
             * Set default search settings.
             * 
             * @param default_search (array): default search options values
             * 
             * @return default search settings array
             */
            function defaults($default_search){
                $default_search = array('date_type' => '1',
                                        'template' => 'default',
                                        'search_enabled' => 'false',
                                        'price_enabled' => 'true',

                                        'view_default' => 'list',
                                        'view_list_enabled' => 'true',
                                        'view_grid_enabled' => 'false',
                                        'view_map_enabled' => 'false',
                                        'view_results_page' => '10',
                                        'view_sidebar_position' => 'left',

                                        'currency' => 'USD',
                                        'currency_position' => 'before',

                                        'days_first' => '1',
                                        'days_multiple_select' => 'true',

                                        'hours_ampm' => 'false',
                                        'hours_definitions' => '[{"value": "00:00"},{"value": "01:00"},{"value": "02:00"},{"value": "03:00"},{"value": "04:00"},{"value": "05:00"},{"value": "06:00"},{"value": "07:00"},{"value": "08:00"},{"value": "09:00"},{"value": "10:00"},{"value": "11:00"},{"value": "12:00"},{"value": "13:00"},{"value": "14:00"},{"value": "15:00"},{"value": "16:00"},{"value": "17:00"},{"value": "18:00"},{"value": "19:00"},{"value": "20:00"},{"value": "21:00"},{"value": "22:00"},{"value": "23:00"}]',
                                        'hours_enabled' => 'false',
                                        'hours_multiple_select' => 'true',

                                        'availability_enabled' => 'false',
                                        'availability_max' => '10',
                                        'availability_min' => '1');
                
                return $default_search;
            }
        }
    }