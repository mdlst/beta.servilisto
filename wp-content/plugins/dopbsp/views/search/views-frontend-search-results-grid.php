<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : views/search/views-backend-search-results-grid.php
* File Version            : 1.0.1
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search results grid views class.
*/

    if (!class_exists('DOPBSPViewsFrontEndSearchResultsGrid')){
        class DOPBSPViewsFrontEndSearchResultsGrid extends DOPBSPViewsFrontEndSearchResults{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search results grid. 
             * 
             * @param args (array): function arguments
             *                      * calendars (array): list of calendars
             * 
             * @return search results grid HTML
             */
            function template($args = array()){
                global $DOPBSP;
                
                $calendars = $args['calendars'];
                $page = $args['page'];
                $results = $args['results'];
?>
                <ul class="dopbsp-grid">
<?php              
                if (count($calendars) > 0){
                    for ($i=($page-1)*$results; $i<($page*$results > count($calendars) ? count($calendars):$page*$results); $i++){
                        $this->item($calendars[$i]);
                    }
                }
                else{         
?>
                    <li class="dopbsp-no-data"><?php echo $DOPBSP->text('SEARCH_FRONT_END_NO_AVAILABILITY'); ?></li>
<?php
                }
?>
                </ul>
<?php
                $this->pagination(array('no' => count($calendars),
                                        'page' => $page,
                                        'results' => $results));
            }
            
            function item($calendar){
		global $DOT;
                global $DOPBSP;
                
                $post = get_post($calendar->post_id);
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($calendar->post_id), 'medium');
                $check_in = $DOT->post('check_in') != '' ? '?check_in='.$DOT->post('check_in'):'';
                $check_out = $DOT->post('check_out') != '' ? '&check_out='.$DOT->post('check_out'):'';
                $start_hour = $DOT->post('start_hour') != '' ? '&start_hour='.$DOT->post('start_hour'):'';
                $end_hour = $DOT->post('end_hour') != '' ? '&end_hour='.$DOT->post('end_hour'):'';
                $no_items = $DOT->post('no_items', 'int') != 0 ? '&no_items='.$DOT->post('no_items', 'int'):'';
                $language = '';
                if($check_in != '') {
                    if($start_hour != ''){
                        $prices = $DOT->models->availability->getPrices($calendar->id,$DOT->post('check_in'), $DOT->post('check_out'), $DOT->post('$start_hour'));
                    }
                    else{
                        $prices = $DOT->models->availability->getPrices($calendar->id,$DOT->post('check_in'), $DOT->post('check_out'));
                    }
                    $calendar->price_min = $calendar->price_min == $prices->price ? $calendar->price_min : $prices->price;
                }
                if(defined('ICL_LANGUAGE_CODE')) {
                    
                    if($check_in != '') {
                        $language = '&lang='.ICL_LANGUAGE_CODE;
                    } else {
                        $language = '?lang='.ICL_LANGUAGE_CODE;
                    }
                }
                
                if ($DOT->get('lang')){
                    if ($check_in != ''){
                        $language = '&lang='.$DOT->get('lang');
                    }
		    else{
                        $language = '?lang='.$DOT->get('lang');
                    }
                }
?>
                <li>
                    <!--
                        Image
                    -->
                    <div class="dopbsp-image">
                        <a href="<?php echo get_permalink($calendar->post_id).$check_in.$check_out.$start_hour.$end_hour.$no_items.$language; ?>" target="_self" style="background-image: url(<?php echo $image[0]; ?>);">
                            <img src="<?php echo $image[0]; ?>" alt="<?php echo $calendar->name; ?>" title="<?php echo $calendar->name; ?>" />
                        </a>
                    </div>

                    <!--
                        Content
                    -->
                    <div class="dopbsp-content">
                        <!--
                            Title
                        -->
                        <h3>
                            <a href="<?php echo get_permalink($calendar->post_id).$check_in.$check_out.$start_hour.$end_hour.$no_items.$language; ?>" target="_self"><?php echo $calendar->name; ?></a>
                        </h3>

                        <!--
                            Address
                        -->
                        <div class="dopbsp-address"><?php echo $calendar->address_alt == '' ? $calendar->address:$calendar->address_alt; ?></div>

                        <!--
                            Price
                        -->
                        <div class="dopbsp-price-wrapper">
                            <?php printf($DOPBSP->text('SEARCH_FRONT_END_RESULTS_PRICE'), '<span class="dopbsp-price">'.($DOPBSP->classes->price->set($calendar->price_min,
                                                                                                                                                      $DOPBSP->classes->currencies->get($calendar->currency),
                                                                                                                                                      $calendar->currency_position)).'<span>'); ?>
                        </div>

                        <!--
                            Text
                        -->
                        <div class="dopbsp-text">
                            <?php 
                                $description = $post->post_excerpt == '' ? wp_strip_all_tags(strip_shortcodes($post->post_content)):wp_strip_all_tags(strip_shortcodes($post->post_excerpt)); 
                                $description = preg_replace("/\[([^\[\]]++|(?R))*+\]/", "", $description);
                                echo $description;
                            ?>
                        </div>

                        <!--
                            View
                        -->
                        <a href="<?php echo get_permalink($calendar->post_id).$check_in.$check_out.$start_hour.$end_hour.$no_items.$language; ?>" target="_self" class="dopbsp-view"><?php echo $DOPBSP->text('SEARCH_FRONT_END_RESULTS_VIEW'); ?></a>
                    </div>
                </li>
<?php
            }
        }
    }