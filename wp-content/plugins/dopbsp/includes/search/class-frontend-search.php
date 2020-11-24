<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : includes/search/class-frontend-search.php
* File Version            : 1.0.5
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Front end search PHP class.
*/

    if (!class_exists('DOPBSPFrontEndSearch')){
        class DOPBSPFrontEndSearch extends DOPBSPFrontEnd{
            /*
             * Constructor.
             */
            function __construct(){
            }
            
            /*
             * Get search.
             * 
             * @param settings (object): calendar settings
             * 
             * @return data array
             */
            function get(){
                global $DOPBSP;
                    
                return array('data' => array(),
                             'text' => array('checkIn' => $DOPBSP->text('SEARCH_FRONT_END_CHECK_IN'),
                                             'checkOut' => $DOPBSP->text('SEARCH_FRONT_END_CHECK_OUT'),
                                             'hourEnd' => $DOPBSP->text('SEARCH_FRONT_END_END_HOUR'),
                                             'hourStart' => $DOPBSP->text('SEARCH_FRONT_END_START_HOUR'),
                                             'noItems' => $DOPBSP->text('SEARCH_FRONT_END_NO_ITEMS'),
                                             'noServices' => $DOPBSP->text('SEARCH_FRONT_END_NO_SERVICES_AVAILABLE'),
                                             'noServicesSplitGroup' => $DOPBSP->text('SEARCH_FRONT_END_NO_SERVICES_AVAILABLE_SPLIT_GROUP'),
                                             'title' => $DOPBSP->text('SEARCH_TITLE')));
            }

            /*
             * Display front end search.
             * 
             * @param atts (array): shortcode attributes
             */
            function display($atts){
                global $wpdb;
                global $DOPBSP;
                
                /*
                 * Do not display anything if the shortcode is invalid.
                 */
                $search = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $atts['id']));
                
                if ($wpdb->num_rows == 0){
                    return false;
                }
                
// HOOK (dopbsp_action_frontend_search_display) ********************************* Add action before calendar init.
                do_action('dopbsp_action_frontend_search_display');
                
                return $DOPBSP->views->frontend_search->template(array('atts' => $atts,
                                                                       'search' => $search));
            }

            /*
             * Display front end search.
             * 
             * @param atts (array): shortcode attributes
             */
            function displayWidget($atts){
                global $wpdb;
                global $DOPBSP;
                
                /*
                 * Do not display anything if the shortcode is invalid.
                 */
                $search = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $atts['id']));
                
                if ($wpdb->num_rows == 0){
                    return false;
                }
                
// HOOK (dopbsp_action_frontend_search_display) ********************************* Add action before calendar init.
                do_action('dopbsp_action_frontend_search_widget_display');
                
                return $DOPBSP->views->frontend_search->templateWidget(array('atts' => $atts,
                                                                             'search' => $search));
            }
            
            function getJSON($atts,
                             $search,
                             $settings){
                global $DOPBSP;
                
                $data = array();
                
                $id = $atts['id'];
                $redirect_id = isset($atts['redirect_id']) ? $atts['redirect_id']:0;
                $language = $atts['lang'];
                
                /*
                 * JSON
                 */
                $data = array('availability' => array('data' => array('enabled' => $settings->availability_enabled == 'true' ? true:false,
                                                                      'max' => (int)$settings->availability_max,
                                                                      'min' => (int)$settings->availability_min),
                                                      'text' => array('title' => $DOPBSP->text('SEARCH_FRONT_END_NO_ITEMS'))),
                              'currency' => array('data' => array('code' => $settings->currency,
                                                                  'position' => $settings->currency_position,
                                                                  'sign' => $DOPBSP->classes->currencies->get($settings->currency),
                                                  'text' => array())),
                              'days' => array('data' => array('first' => (int)$settings->days_first,
                                                              'multipleSelect' => $settings->days_multiple_select == 'true' ? true:false),
                                              'text' => array('names' => array($DOPBSP->text('DAY_SUNDAY'), 
                                                                               $DOPBSP->text('DAY_MONDAY'), 
                                                                               $DOPBSP->text('DAY_TUESDAY'), 
                                                                               $DOPBSP->text('DAY_WEDNESDAY'), 
                                                                               $DOPBSP->text('DAY_THURSDAY'), 
                                                                               $DOPBSP->text('DAY_FRIDAY'), 
                                                                               $DOPBSP->text('DAY_SATURDAY')),
                                                              'shortNames' => array($DOPBSP->text('SHORT_DAY_SUNDAY'), 
                                                                                    $DOPBSP->text('SHORT_DAY_MONDAY'), 
                                                                                    $DOPBSP->text('SHORT_DAY_TUESDAY'), 
                                                                                    $DOPBSP->text('SHORT_DAY_WEDNESDAY'), 
                                                                                    $DOPBSP->text('SHORT_DAY_THURSDAY'), 
                                                                                    $DOPBSP->text('SHORT_DAY_FRIDAY'), 
                                                                                    $DOPBSP->text('SHORT_DAY_SATURDAY')))),
                              'hours' => array('data' => array('ampm' => $settings->hours_ampm == 'true' ? true:false,
                                                               'definitions' => json_decode($settings->hours_definitions),
                                                               'enabled' => $settings->hours_enabled == 'true' ? true:false,
                                                               'multipleSelect' => $settings->hours_multiple_select == 'true' ? true:false),
                                               'text' => array()),
                              'ID' => $id,
                              'REDIRECT_ID' => $redirect_id,
                              'months' => array('data' => array(),
                                                'text' => array('names' => array($DOPBSP->text('MONTH_JANUARY'), 
                                                                                 $DOPBSP->text('MONTH_FEBRUARY'),  
                                                                                 $DOPBSP->text('MONTH_MARCH'),
                                                                                 $DOPBSP->text('MONTH_APRIL'),  
                                                                                 $DOPBSP->text('MONTH_MAY'),  
                                                                                 $DOPBSP->text('MONTH_JUNE'),  
                                                                                 $DOPBSP->text('MONTH_JULY'),  
                                                                                 $DOPBSP->text('MONTH_AUGUST'),  
                                                                                 $DOPBSP->text('MONTH_SEPTEMBER'),  
                                                                                 $DOPBSP->text('MONTH_OCTOBER'),  
                                                                                 $DOPBSP->text('MONTH_NOVEMBER'),  
                                                                                 $DOPBSP->text('MONTH_DECEMBER')),
                                                                'shortNames' => array($DOPBSP->text('SHORT_MONTH_JANUARY'),  
                                                                                      $DOPBSP->text('SHORT_MONTH_FEBRUARY'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_MARCH'),
                                                                                      $DOPBSP->text('SHORT_MONTH_APRIL'),
                                                                                      $DOPBSP->text('SHORT_MONTH_MAY'),
                                                                                      $DOPBSP->text('SHORT_MONTH_JUNE'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_JULY'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_AUGUST'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_SEPTEMBER'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_OCTOBER'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_NOVEMBER'), 
                                                                                      $DOPBSP->text('SHORT_MONTH_DECEMBER')))),
                              'pluginURL' => $DOPBSP->paths->url,
                              'price' => array('data' => array('enabled' => $settings->price_enabled == 'true' ? true:false,
                                                               'max' => $this->getPriceMax($id),
                                                               'min' => $this->getPriceMin($id)),
                                               'text' => array()),
                              'search' => array('data' => array('dateType' => (int)$settings->date_type,
                                                                'enabled' => $settings->search_enabled == 'true' ? true:false,
                                                                'language' => $language,
                                                                'template' => $settings->template),
                                                'text' => array('checkIn' => $DOPBSP->text('SEARCH_FRONT_END_CHECK_IN'),
                                                                'checkOut' => $DOPBSP->text('SEARCH_FRONT_END_CHECK_OUT'),
                                                                'hourEnd' => $DOPBSP->text('SEARCH_FRONT_END_END_HOUR'),
                                                                'hourStart' => $DOPBSP->text('SEARCH_FRONT_END_START_HOUR'),
                                                                'title' => $DOPBSP->text('SEARCH_TITLE'))),
                              'sort' => array('data' => array(),
                                              'text' => array('name' => $DOPBSP->text('SEARCH_FRONT_END_SORT_NAME'),
                                                              'price' => $DOPBSP->text('SEARCH_FRONT_END_SORT_PRICE'),
                                                              'title' => $DOPBSP->text('SEARCH_FRONT_END_SORT_TITLE'))),
                              'URL' => admin_url('admin-ajax.php'),
                              'view' => array('data' => array('default' => $settings->view_default == 'true' ? true:false,
                                                              'gridEnabled' => $settings->view_grid_enabled == 'true' ? true:false,
                                                              'listEnabled' => $settings->view_list_enabled == 'true' ? true:false,
                                                              'mapEnabled' => $settings->view_map_enabled == 'true' ? true:false,
                                                              'results' => (int)$settings->view_results_page),
                                               'text' => array('grid' => $DOPBSP->text('SEARCH_FRONT_END_VIEW_GRID'),
                                                               'list' => $DOPBSP->text('SEARCH_FRONT_END_VIEW_LIST'),
                                                               'map' => $DOPBSP->text('SEARCH_FRONT_END_VIEW_MAP'))));
                
                return json_encode($data);
            }
            
            function getPriceMin($id){
                global $wpdb;
                global $DOPBSP;
                
                $query = array();
                
                $search = $wpdb->get_row($wpdb->prepare('SELECT calendars_excluded FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $id));
                
                /*
                 * Tables
                 */
                array_push($query, 'SELECT MIN(calendars.price_min) AS price_min');
                array_push($query, ' FROM '.$DOPBSP->tables->calendars.' calendars');
                array_push($query, ', '.$DOPBSP->tables->searches.' search');
                array_push($query, ' WHERE search.id='.$id);
                
                /*
                 * Exclude calendars.
                 */
                array_push($query, ' AND calendars.post_id<>0');
                array_push($query, ' AND IF (search.hours_enabled="true", calendars.hours_enabled="true", calendars.hours_enabled="false")');
                array_push($query, $search->calendars_excluded == '' ? '':' AND calendars.id NOT IN ('.$search->calendars_excluded.')');
                
                $calendars = $wpdb->get_row(implode('', $query));
                
                return isset($calendars->price_min) ? (float)$calendars->price_min:0;
            }
            
            function getPriceMax($id){
                global $wpdb;
                global $DOPBSP;
                
                $query = array();
                
                $search = $wpdb->get_row($wpdb->prepare('SELECT calendars_excluded FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $id));
                
                /*
                 * Tables
                 */
                array_push($query, 'SELECT MAX(calendars.price_max) AS price_max');
                array_push($query, ' FROM '.$DOPBSP->tables->calendars.' calendars');
                array_push($query, ', '.$DOPBSP->tables->searches.' search');
                array_push($query, ' WHERE search.id='.$id);
                
                /*
                 * Exclude calendars.
                 */
                array_push($query, ' AND calendars.post_id<>0');
                array_push($query, ' AND IF (search.hours_enabled="true", calendars.hours_enabled="true", calendars.hours_enabled="false")');
                array_push($query, $search->calendars_excluded == '' ? '':' AND calendars.id NOT IN ('.$search->calendars_excluded.')');
                
                $calendars = $wpdb->get_row(implode('', $query));
                
                return isset($calendars->price_max) ? (float)$calendars->price_max:0;
            }
        }
    }