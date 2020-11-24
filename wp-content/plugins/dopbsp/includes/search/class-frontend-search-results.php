<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.3
* File                    : includes/search/class-frontend-search-results.php
* File Version            : 1.0.6
* Created / Last Modified : 14 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Front end search results PHP class.
*/

    if (!class_exists('DOPBSPFrontEndSearchResults')){
        class DOPBSPFrontEndSearchResults extends DOPBSPFrontEndSearch{
            /*
             * Constructor.
             */
            function __construct(){
            }
            
            /*
             * Get search results.
             * 
             * @return results HTML
             */
            function get(){
		global $DOT;
                global $wpdb;
                global $DOPBSP;
                
                $id = $DOT->post('id', 'int');
                $language = $DOT->post('language');
                $check_in = $DOT->post('check_in');
                $check_out = $DOT->post('check_out');
                $start_hour = $DOT->post('start_hour') ? $DOT->post('start_hour'):'';
                $end_hour = $DOT->post('end_hour') ? $DOT->post('end_hour'):'';
                $no_items = $DOT->post('no_items', 'int') != 0 ? $DOT->post('no_items', 'int'):1;           
                $price_min = $DOT->post('price_min', 'float');
                $price_max = $DOT->post('price_max', 'float') != 0 ? $DOT->post('price_max', 'float'):1000000000;
                $sort_by = $DOT->post('sort_by') ? $DOT->post('sort_by'):'price';
                $sort_direction = $DOT->post('sort_direction') ? $DOT->post('sort_direction'):'ASC';
                $view = $DOT->post('view');
                $results = $DOT->post('results');
                $page = $DOT->post('page', 'int');
                
                $query = array();
                
                $search = $wpdb->get_row($wpdb->prepare('SELECT calendars_excluded FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $id));
                $settings_search = $DOPBSP->classes->backend_settings->values($id,  
                                                                              'search');
		
		/*
		 * Get available calendars.
		 */
		$calendars_available = $DOT->models->availability->find($check_in,
									$check_out,
									$start_hour == '' ? '00:00:00':$start_hour.':00',
									$end_hour == '' ? '00:00:00':$end_hour.':00',
									$no_items,
									$price_min != '' ? (float)$price_min:-1,
									$price_max != '' ? (float)$price_max:-1,
									'',
									'',
									$sort_by == 'price' ? 'price':'id',
									$sort_direction,
									1,
									2000);
                
		if (count($calendars_available) > 0){
		    /*
		     * Tables
		     */
		    array_push($query, 'SELECT calendars.id, calendars.post_id, calendars.name, calendars.min_available, calendars.price_min, calendars.address, calendars.address_alt, calendars.coordinates, calendars.hours_interval_enabled, search.currency, search.currency_position');
		    array_push($query, ' FROM '.$DOPBSP->tables->calendars.' calendars');
		    array_push($query, ', '.$DOPBSP->tables->searches.' search');
		    array_push($query, ' WHERE search.id='.$id);
		    array_push($query, $view == 'map' ? ' AND calendars.coordinates<>""':'');

		    /*
		     * Available calendars list.
		     */
		    if ($calendars_available !== false
			    && count($calendars_available) > 0){
			array_push($query, ' AND calendars.id IN ('.implode(',', $calendars_available).')');
		    }

		    /*
		     * Exclude calendars.
		     */
		    array_push($query, ' AND calendars.post_id<>0');
		    array_push($query, ' AND IF (search.hours_enabled="true", calendars.hours_enabled="true", calendars.hours_enabled="false")');
		    array_push($query, $search->calendars_excluded == '' ? '':' AND calendars.id NOT IN ('.$search->calendars_excluded.')');

		    /*
		     * Price
		     */
		    if ($calendars_available === false){
			array_push($query, ' AND (calendars.price_min>='.$price_min.' AND calendars.price_min<='.$price_max.')');
		    }

		    /*
		     * No Items
		     */
		    if ($calendars_available === false){
			array_push($query, ' AND (calendars.min_available>='.$no_items.')');
		    }

		    /*
		     * Sort
		     */
		    array_push($query, ' ORDER BY '.($sort_by == 'price' ? 'calendars.price_min':'calendars.name').' '.$sort_direction);

		    /*
		     * Limit
		     */
		    array_push($query, ' LIMIT 0, 2000');

		    $calendars = $wpdb->get_results(implode('', $query));
		}
		else{
		    $calendars = array();
		}
                
//                if(count($calendars) > 0) {
//                    $settings_calendar = $DOPBSP->classes->backend_settings->values($calendars[0]->id,  
//                                                                                    'calendar');
//                    
//                    if($settings_calendar->days_morning_check_out == true 
//                       && $check_out != ''
//                       && $check_out != $check_in) {
//                        $check_out = date( 'Y-m-d', strtotime( $check_out . ' -1 day' ) );
//                    }
//                } 
                
                $DOPBSP->classes->translation->set($language,
                                                   false,
                                                   array('frontend',
                                                         'calendar'));
                
//                $calendars = $this->available($calendars,
//                                              $check_in,
//                                              $check_out,
//                                              $start_hour,
//                                              $end_hour,
//                                              $no_items,
//                                              $settings_search);
                
                switch ($view){
                    case 'grid':
                        $DOPBSP->views->frontend_search_results_grid->template(array('calendars' => $calendars,
                                                                                     'page' => $page,
                                                                                     'results' => $results));
                        break;
                    case 'map':
                        $this->locations($calendars,
                                         $page,
                                         $results);
                        break;
                    default:
                        $DOPBSP->views->frontend_search_results_list->template(array('calendars' => $calendars,
                                                                                     'page' => $page,
                                                                                     'results' => $results));
                }
                
                die();
            }
            
            /*
             * Get search results.
             * 
             * @return results HTML
             */
            function getWidget(){
		global $DOT;
                global $wpdb;
                global $DOPBSP;
                
                $id = $DOT->post('id', 'int');
                $language = $DOT->post('language');
                $check_in = $DOT->post('check_in');
                $check_out = $DOT->post('check_out');
                $no_items = $DOT->post('no_items', 'int');
                $start_hour = $DOT->post('start_hour') ? $DOT->post('start_hour'):'';
                $end_hour = $DOT->post('end_hour') ? $DOT->post('end_hour'):'';
                $sort_by = $DOT->post('sort_by') ? $DOT->post('sort_by'):'price';
                $redirect_id = $DOT->post('redirect_id') ? $DOT->post('redirect_id', 'int'):0;
                $sort_direction = $DOT->post('sort_direction') ? $DOT->post('sort_direction'):'ASC';
		
		/*
		 * Get available calendars.
		 */
		$calendars_available = $DOT->models->availability->find($check_in,
									$check_out,
									$start_hour == '' ? '00:00:00':$start_hour.':00',
									$end_hour == '' ? '00:00:00':$end_hour.':00',
									$no_items,
									-1,
									-1,
									'',
									'',
									$sort_by == 'price' ? 'price':'id',
									$sort_direction,
									1,
									2000);
                
                $query = array();
                
                $search = $wpdb->get_row($wpdb->prepare('SELECT calendars_excluded FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $id));
                $settings_search = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->settings_search.' WHERE search_id=%d',
                                                                 $id));
                
                /*
                 * Tables
                 */
                array_push($query, 'SELECT calendars.id, calendars.post_id, calendars.name, calendars.min_available, calendars.price_min, calendars.address, calendars.address_alt, calendars.coordinates, calendars.hours_interval_enabled, search.currency, search.currency_position');
                array_push($query, ' FROM '.$DOPBSP->tables->calendars.' calendars');
                array_push($query, ', '.$DOPBSP->tables->searches.' search');
                array_push($query, ' WHERE search.id='.$id);
//                array_push($query, $view == 'map' ? ' AND calendars.coordinates<>""':'');
		
		/*
		 * Available calendars list.
		 */
		if ($calendars_available !== false
			&& count($calendars_available) > 0){
		    array_push($query, ' AND calendars.id IN ('.implode(',', $calendars_available).')');
		}
                
                /*
                 * Exclude calendars.
                 */
                array_push($query, ' AND calendars.post_id<>0');
                array_push($query, ' AND IF (search.hours_enabled="true", calendars.hours_enabled="true", calendars.hours_enabled="false")');
                array_push($query, $search->calendars_excluded == '' ? '':' AND calendars.id NOT IN ('.$search->calendars_excluded.')');
                
                /*
                 * Price
                 */
		if ($calendars_available === false){
                    array_push($query, ' AND (calendars.price_min>='.$price_min.' AND calendars.price_min<='.$price_max.')');
                }
                
                /*
                 * Sort
                 */
//                array_push($query, ' ORDER BY '.($sort_by == 'price' ? 'calendars.price_min':'calendars.name').' '.$sort_direction);
                
                /*
                 * Limit
                 */
                array_push($query, ' LIMIT 0, 2000');
                
                $calendars = $wpdb->get_results(implode('', $query));
                $check_out_old = $check_out;
                
//                if(count($calendars) > 0) {
//                    $settings_calendar = $DOPBSP->classes->backend_settings->values($calendars[0]->id,  
//                                                                                    'calendar');
//                    
//                    if($settings_calendar->days_morning_check_out == true 
//                       && $check_out != ''
//                       && $check_out != $check_in) {
//                        $check_out = date( 'Y-m-d', strtotime( $check_out . ' -1 day' ) );
//                    }
//                } 
                
                $DOPBSP->classes->translation->set($language,
                                                   false,
                                                   array('frontend',
                                                         'calendar'));
                
//                $calendars = $this->available($calendars,
//                                              $check_in,
//                                              $check_out,
//                                              $start_hour,
//                                              $end_hour,
//                                              $no_items,
//                                              $settings_search);
                
                if(isset($calendars[0])
                  && $redirect_id < 1) {
                    $redirect_link = get_permalink($calendars[0]->post_id).'?check_in='.$check_in.'&check_out='.$check_out_old.'&no_items='.$no_items;  
                    
                    if($start_hour != '') {
                        $redirect_link .= '&start_hour='.$start_hour;
                    }
                    
                    if($end_hour != '') {
                        $redirect_link .= '&end_hour='.$end_hour;
                    } else if($start_hour != '') {
                        $redirect_link .= '&end_hour='.$start_hour;
                    }
                    
                    echo $redirect_link;
                } else if($redirect_id > 0) {
                    $redirect_link = get_permalink($redirect_id).'?check_in='.$check_in.'&check_out='.$check_out_old.'&no_items='.$no_items;  
                    
                    if($start_hour != '') {
                        $redirect_link .= '&start_hour='.$start_hour;
                    }
                    
                    if($end_hour != '') {
                        $redirect_link .= '&end_hour='.$end_hour;
                    } else if($start_hour != '') {
                        $redirect_link .= '&end_hour='.$start_hour;
                    }
                    
                    echo $redirect_link;
                } else {
                    echo 'no_data';
                }
                
                die();
            }
            
            function available($calendars,
                               $check_in,
                               $check_out,
                               $start_hour,
                               $end_hour,
                               $no_items,
                               $settings_search){
                global $wpdb;
                global $DOPBSP;
                
                $availability = array();
                $available = $calendars;
                
                if ($check_in == ''
                        && $start_hour == ''){
                    return $available;
                }
                elseif ($check_in != ''
                            && $start_hour == ''){
                    
                    if(!isset($settings_search->hours_enabled)) {
                        $settings_search->hours_enabled = 'false';
                    }
                    
                    if ($settings_search->hours_enabled == 'true'){
                        $availability = $wpdb->get_results($wpdb->prepare('SELECT data FROM '.$DOPBSP->tables->days_available.' WHERE day>="%s" AND day<="%s" AND hour<>"" ORDER BY unique_key',
                                                                          $check_in, $check_out));
                        
                        for ($i=0; $i<count($calendars); $i++){
                            $found = false;
                            
                            for ($j=0; $j<count($availability); $j++){
                                $data = explode('|', $availability[$j]->data);

                                if (strpos(','.$data[0].',', ','.$calendars[$i]->id.',') !== false
                                   && $calendars[$i]->min_available < 1){
                                    $found = true;
                                    break;
                                }
                            }

                            if (!$found){
                                unset($available[$i]);
                            }
                        }
                        
                        return array_values($available);
                    }
                    else{
                        $availability = $wpdb->get_results($wpdb->prepare('SELECT data FROM '.$DOPBSP->tables->days_available.' WHERE unique_key>="%s" AND unique_key<="%s" AND hour="" ORDER BY unique_key',
                                                                          $check_in.'_0', $check_out.'_0'));
                    }
                }
                elseif ($check_in == ''
                            && $start_hour != ''){
                    $availability = $wpdb->get_results($wpdb->prepare('SELECT data FROM '.$DOPBSP->tables->days_available.' WHERE hour>="%s" AND hour<="%s" AND hour<>"" ORDER BY unique_key',
                                                                      $start_hour, $end_hour));
                }
                else{
                    $availability = $wpdb->get_results($wpdb->prepare('SELECT data FROM '.$DOPBSP->tables->days_available.' WHERE day>="%s" AND day<="%s" AND hour>="%s" AND hour<="%s" AND hour<>"" ORDER BY unique_key',
                                                                      $check_in, $check_out, $start_hour, $end_hour));
                }
                
                if (count($availability) == 0){
                    
                    $available = array();
                    
                    for ($i=0; $i<count($calendars); $i++){
                        
                        if($calendars[$i]->min_available > 0
                          && $calendars[$i]->min_available >= (int)$no_items) {
                            array_push($available, $calendars[$i]);
                        }
                    }
                }
                else{
                    
                    for ($i=0; $i<count($calendars); $i++){
                        $calendar_found = false;

                        for ($j=0; $j<count($availability)-($start_hour != '' && $calendars[$i]->hours_interval_enabled == 'true' ? 1:0); $j++){
                            $data = explode('|', $availability[$j]->data);
                            
                            if($calendars[$i]->min_available > 0) {
                                $calendar_found = true;
                            }
                            
                            if ($check_in == ''
                                    && $start_hour != ''){
                                if (strpos(','.$data[0].',', ','.$calendars[$i]->id.',') !== false
                                   && (int)$calendars[$i]->min_available > 0){
                                    if ($no_items != ''){
                                        $ids = explode(',', $data[0]);
                                        $nos = explode(',', $data[1]);
                                        $calendars_no = array_combine($ids, $nos);

                                        if ((int)$calendars_no[$calendars[$i]->id] >= (int)$no_items
                                           || (int)$calendars[$i]->min_available >= (int)$no_items){
                                            $calendar_found = true;
                                        }
                                    }
                                    else{
                                        $calendar_found = true;
                                    }
                                }
                                
                            }
                            else{
                                if (strpos(','.$data[0].',', ','.$calendars[$i]->id.',') === false
                                   && $calendars[$i]->min_available < 1){
                                    unset($available[$i]);
                                }
                                else{
                                    if ($no_items != ''){
                                        $ids = explode(',', $data[0]);
                                        $nos = explode(',', $data[1]);
                                        $calendars_no = array_combine($ids, $nos);

                                        if ((int)$calendars_no[$calendars[$i]->id] < (int)$no_items
                                           && ($calendars[$i]->min_available < 1
                                              || $calendars[$i]->min_available < (int)$no_items)){
                                            unset($available[$i]);
                                        }
                                    }
                                }
                            }
                        }
                        
                        if ($check_in == ''
                                && $start_hour != ''
                                && !$calendar_found){
                            unset($available[$i]);
                        }
                    }
                }
                
                return array_values($available);
            }
            
            function locations($calendars,
                               $page,
                               $results){
                global $DOPBSP;
                
                $locations = array();

                for ($i=($page-1)*$results; $i<($page*$results > count($calendars) ? count($calendars):$page*$results); $i++){
                    $post = get_post($calendars[$i]->post_id);
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($calendars[$i]->post_id), 'medium');

                    $calendars[$i]->image = $image[0];
                    $calendars[$i]->link = get_permalink($calendars[$i]->post_id);
                    $calendars[$i]->price = str_replace('%s', 
                                                   '<span class="dopbsp-price">'.($DOPBSP->classes->price->set($calendars[$i]->price_min,
                                                                                  $DOPBSP->classes->currencies->get($calendars[$i]->currency),
                                                                                  $calendars[$i]->currency_position)).'<span>',
                                                   $DOPBSP->text('SEARCH_FRONT_END_RESULTS_PRICE'));
                    $calendars[$i]->text = $post->post_excerpt == '' ? wp_strip_all_tags(strip_shortcodes($post->post_content)):$post->post_excerpt;

                    /*
                     * Construct locations.
                     */
                    $calendar_found = false;

                    for ($j=0; $j<count($locations); $j++){
                        if ($locations[$j]['coordinates'] == $calendars[$i]->coordinates){
                            array_push($locations[$j]['calendars'], $calendars[$i]);
                            $calendar_found = true;
                        }
                    }

                    if (!$calendar_found){
                        array_push($locations, array('coordinates' => $calendars[$i]->coordinates,
                                                     'calendars' => array(0 => $calendars[$i])));
                    }
                }
                
                echo json_encode($locations).';;;;;';
                $DOPBSP->views->frontend_search_results->pagination(array('no' => count($calendars),
                                                                          'page' => $page,
                                                                          'results' => $results));
            }
        }
    }