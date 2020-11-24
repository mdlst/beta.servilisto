<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : views/search/views-backend-search-sidebar.php
* File Version            : 1.0.2
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search sidebar views class.
*/

    if (!class_exists('DOPBSPViewsFrontEndSearchSidebar')){
        class DOPBSPViewsFrontEndSearchSidebar extends DOPBSPViewsFrontEndSearch{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search sidebar.
             * 
             * @param args (array): function arguments
             *                      * atts (object): shortcode attributes
             *                      * settings_search (object): search settings
             * 
             * @return search HTML
             */
            function template($args = array()){
		global $DOT;
                global $DOPBSP;
                
                $atts = $args['atts'];
                $settings_search = $args['settings_search'];
                
                $id = $atts['id'];
                $hours = json_decode($settings_search->hours_definitions);
                
                $check_in = $DOT->get('check_in') ? $DOT->get('check_in'):$DOPBSP->text('SEARCH_FRONT_END_CHECK_IN');
                $check_out = $DOT->get('check_out') ? $DOT->get('check_out'):$DOPBSP->text('SEARCH_FRONT_END_CHECK_OUT');
                $check_in_val = $DOT->get('check_in') ? $DOT->get('check_in'):'';
                $check_out_val = $DOT->get('check_out') ? $DOT->get('check_out'):'';
                $no_items = $DOT->get('no_items') ? $DOT->get('check_out'):'';
                $start_hour = $DOT->get('start_hour') ? $DOT->get('start_hour'):'';
                $end_hour = $DOT->get('end_hour') ? $DOT->get('end_hour'):'';
                
                $html = array();
                
                array_push($html, ' <div class="dopbsp-module">');
                array_push($html, '     <h4>'.$DOPBSP->text('SEARCH_FRONT_END_TITLE').'</h4>');
                array_push($html, ' </div>');
                
                array_push($html, ' <hr />');
                
                array_push($html, ' <div class="dopbsp-search-sidebar-form">');
                
                if ($settings_search->search_enabled == 'true'
                        && DOPBSP_DEVELOPMENT_MODE){
                    array_push($html, ' <div class="dopbsp-module">');
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <input type="text" name="DOPBSPSearch-search'.$id.'" id="DOPBSPSearch-search'.$id.'" class="DOPBSPSearch-search" value="" />');
                    array_push($html, '     </div>');
                    array_push($html, ' </div>');
                }
                
                array_push($html, '     <div class="dopbsp-module">');
                array_push($html, '         <div class="dopbsp-input-wrapper">');
                array_push($html, '             <input type="text" name="DOPBSPSearch-check-in-view'.$id.'" id="DOPBSPSearch-check-in-view'.$id.'" class="DOPBSPSearch-check-in-view" value="'.$check_in.'" />');
                array_push($html, '             <input type="hidden" name="DOPBSPSearch-check-in'.$id.'" id="DOPBSPSearch-check-in'.$id.'" value="'.$check_in_val.'" />');
                array_push($html, '         </div>');
                
                if ($settings_search->days_multiple_select == 'true' && $settings_search->hours_enabled == 'false'){
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <input type="text" name="DOPBSPSearch-check-out-view'.$id.'" id="DOPBSPSearch-check-out-view'.$id.'" class="DOPBSPSearch-check-out-view" value="'.$check_out.'" />');
                    array_push($html, '         <input type="hidden" name="DOPBSPSearch-check-out'.$id.'" id="DOPBSPSearch-check-out'.$id.'" value="'.$check_out_val.'" />');
                    array_push($html, '     </div>');
                }
                array_push($html, '     </div>');
                
                if ($settings_search->hours_enabled == 'true'){
                    array_push($html, ' <div class="dopbsp-module">');
                    array_push($html, '     <div class="dopbsp-input-wrapper DOPBSPSearch-left">');
                    array_push($html, '         <label for="DOPBSPSearch-start-hour'.$id.'">'.$DOPBSP->text('SEARCH_FRONT_END_START_HOUR').'</label>');
                    array_push($html, '         <select id="DOPBSPSearch-start-hour'.$id.'" class="dopbsp-small">');
                    array_push($html, '             <option value=""></option>');
                    
                    foreach ($hours as $hour){
                        array_push($html, '         <option value="'.$hour->value.'" '.($start_hour == $hour->value ? 'selected="selected"':'').'>'.($settings_search->hours_ampm == 'true' ? $DOPBSP->classes->prototypes->getAMPM($hour->value):$hour->value).'</option>');
                    }
                    array_push($html, '         </select>');
                    array_push($html, '     </div>');
                
                    if ($settings_search->hours_multiple_select == 'true'){
                        array_push($html, ' <div class="dopbsp-input-wrapper DOPBSPSearch-left"">');
                        array_push($html, '     <label for="DOPBSPSearch-end-hour'.$id.'">'.$DOPBSP->text('SEARCH_FRONT_END_END_HOUR').'</label>');
                        array_push($html, '     <select id="DOPBSPSearch-end-hour'.$id.'" class="dopbsp-small">');
                        array_push($html, '         <option value=""></option>');

                        foreach ($hours as $hour){
                            array_push($html, '     <option value="'.$hour->value.'" '.($end_hour == $hour->value ? 'selected="selected"':'').'>'.($settings_search->hours_ampm == 'true' ? $DOPBSP->classes->prototypes->getAMPM($hour->value):$hour->value).'</option>');
                        }
                        array_push($html, '     </select>');
                        array_push($html, ' </div>');
                    }
                    array_push($html, '     <br class="DOPBSPSearch-clear" />');
                    array_push($html, ' </div>');
                }
                
                if ($settings_search->availability_enabled == 'true'){
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <label for="DOPBSPSearch-no-items'.$id.'">'.$DOPBSP->text('SEARCH_FRONT_END_NO_ITEMS').'</label>');
                    array_push($html, '         <select id="DOPBSPSearch-no-items'.$id.'" class="dopbsp-small">');
                    array_push($html, '             <option value=""></option>');
                    
                    for ($i=(int)$settings_search->availability_min; $i<=(int)$settings_search->availability_max; $i++){
                        array_push($html, '         <option value="'.$i.'">'.$i.'</option>');
                    }
                    array_push($html, '         </select>');
                    array_push($html, '     </div>');
                }
                
                if ($settings_search->price_enabled == 'true'){
                    array_push($html, ' <div class="dopbsp-module dopbsp-price">');
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <label id="DOPBSPSearch-price-min'.$id.'">&nbsp;</label>');
                    array_push($html, '         <label id="DOPBSPSearch-price-max'.$id.'">&nbsp;</label>');
                    array_push($html, '         <input type="hidden" name="DOPBSPSearch-price-min-value'.$id.'" id="DOPBSPSearch-price-min-value'.$id.'" value="" />');
                    array_push($html, '         <input type="hidden" name="DOPBSPSearch-price-max-value'.$id.'" id="DOPBSPSearch-price-max-value'.$id.'" value="" />');
                    array_push($html, '         <div id="DOPBSPSearch-price'.$id.'"></div>');
                    array_push($html, '     </div>');
                    array_push($html, ' </div>');
                }
                array_push($html, ' </div>');
                
                return implode("\n", $html);
            }
            
            /*
             * Returns search sidebar.
             * 
             * @param args (array): function arguments
             *                      * atts (object): shortcode attributes
             *                      * settings_search (object): search settings
             * 
             * @return search HTML
             */
            function templateWidget($args = array()){
                global $DOPBSP;
                global $wpdb;
                
                $atts = $args['atts'];
                $settings_search = $args['settings_search'];
                
                $id = $atts['id'];
                $hours = json_decode($settings_search->hours_definitions);
                
                $days_all = $wpdb->get_results('SELECT * FROM '.$DOPBSP->tables->days);
                $max_availability = 1;
                
                // Get max rooms
                foreach($days_all as $day) {
                    $data = json_decode($day->data);
                    
                    if($data->available > $max_availability) {
                        $max_availability = $data->available;
                    }
                }
                
                $html = array();
                
                array_push($html, ' <div class="dopbsp-search-sidebar-form">');
                
                if ($settings_search->search_enabled == 'true'
                        && DOPBSP_DEVELOPMENT_MODE){
                    array_push($html, ' <div class="dopbsp-module">');
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <input type="text" name="DOPBSPSearchWidget-search'.$id.'" id="DOPBSPSearchWidget-search'.$id.'" class="DOPBSPSearchWidget-search" value="" />');
                    array_push($html, '     </div>');
                    array_push($html, ' </div>');
                }
                
                array_push($html, '     <div class="dopbsp-module">');
                array_push($html, '         <div class="dopbsp-input-wrapper">');
                array_push($html, '             <input type="text" name="DOPBSPSearchWidget-check-in-view'.$id.'" id="DOPBSPSearchWidget-check-in-view'.$id.'" class="DOPBSPSearchWidget-check-in-view" value="'.$DOPBSP->text('SEARCH_FRONT_END_CHECK_IN').'" />');
                array_push($html, '             <input type="hidden" name="DOPBSPSearchWidget-check-in'.$id.'" id="DOPBSPSearchWidget-check-in'.$id.'" value="" />');
                array_push($html, '         </div>');
                
                if ($settings_search->days_multiple_select == 'true'){
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <input type="text" name="DOPBSPSearchWidget-check-out-view'.$id.'" id="DOPBSPSearchWidget-check-out-view'.$id.'" class="DOPBSPSearchWidget-check-out-view" value="'.$DOPBSP->text('SEARCH_FRONT_END_CHECK_OUT').'" />');
                    array_push($html, '         <input type="hidden" name="DOPBSPSearchWidget-check-out'.$id.'" id="DOPBSPSearchWidget-check-out'.$id.'" value="" />');
                    array_push($html, '     </div>');
                }
                
                if ($settings_search->hours_enabled == 'true'){
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <label for="DOPBSPSearchWidget-start-hour'.$id.'">'.$DOPBSP->text('SEARCH_FRONT_END_START_HOUR').'</label>');
                    array_push($html, '         <select id="DOPBSPSearchWidget-start-hour'.$id.'" class="dopbsp-small">');
                    array_push($html, '             <option value=""></option>');
                    
                    foreach ($hours as $hour){
                        array_push($html, '         <option value="'.$hour->value.'">'.$DOPBSP->classes->prototypes->getAMPM($hour->value).'</option>');
                    }
                    array_push($html, '         </select>');
                    array_push($html, '     </div>');
                
                    if ($settings_search->hours_multiple_select == 'true'){
                        array_push($html, ' <div class="dopbsp-input-wrapper">');
                        array_push($html, '     <label for="DOPBSPSearchWidget-end-hour'.$id.'">'.$DOPBSP->text('SEARCH_FRONT_END_END_HOUR').'</label>');
                        array_push($html, '     <select id="DOPBSPSearchWidget-end-hour'.$id.'" class="dopbsp-small">');
                        array_push($html, '         <option value=""></option>');

                        foreach ($hours as $hour){
                            array_push($html, '     <option value="'.$hour->value.'">'.$DOPBSP->classes->prototypes->getAMPM($hour->value).'</option>');
                        }
                        array_push($html, '     </select>');
                        array_push($html, ' </div>');
                    }
                }
                
                if ($settings_search->availability_enabled == 'true'){
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <label for="DOPBSPSearchWidget-no-items'.$id.'">'.$DOPBSP->text('SEARCH_FRONT_END_NO_ITEMS').'</label>');
                    array_push($html, '         <select id="DOPBSPSearchWidget-no-items'.$id.'" class="dopbsp-small">');
                    array_push($html, '             <option value=""></option>');
                    
                    for ($i=(int)$settings_search->availability_min; $i<=(int)$settings_search->availability_max; $i++){
                        array_push($html, '         <option value="'.$i.'">'.$i.'</option>');
                    }
                    array_push($html, '         </select>');
                    array_push($html, '     </div>');
                }
                
                
                array_push($html, '     <div class="dopbsp-input-wrapper">');
                array_push($html, '     <input type="button" name="DOPBSPSearchWidget-check-availability'.$id.'" id="DOPBSPSearchWidget-check-availability'.$id.'" class="dopbsp-button" value="'.$DOPBSP->text('SEARCHES_WIDGET').' "/>');
                array_push($html, '     </div>');
                array_push($html, '     <br class="DOPBSPSearchWidget-clear" />');
                array_push($html, ' </div>');
                
                $settings_search->price_enabled = 'false';
                if ($settings_search->price_enabled == 'true'){
                    array_push($html, ' <div class="dopbsp-module dopbsp-price">');
                    array_push($html, '     <div class="dopbsp-input-wrapper">');
                    array_push($html, '         <label id="DOPBSPSearchWidget-price-min'.$id.'">&nbsp;</label>');
                    array_push($html, '         <label id="DOPBSPSearchWidget-price-max'.$id.'">&nbsp;</label>');
                    array_push($html, '         <input type="hidden" name="DOPBSPSearchWidget-price-min-value'.$id.'" id="DOPBSPSearchWidget-price-min-value'.$id.'" value="" />');
                    array_push($html, '         <input type="hidden" name="DOPBSPSearchWidget-price-max-value'.$id.'" id="DOPBSPSearchWidget-price-max-value'.$id.'" value="" />');
                    array_push($html, '         <div id="DOPBSPSearchWidget-price'.$id.'"></div>');
                    array_push($html, '     </div>');
                    array_push($html, ' </div>');
                }
                array_push($html, ' </div>');
                
                return implode("\n", $html);
            }
        }
    }