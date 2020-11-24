<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : views/search/views-backend-search-results.php
* File Version            : 1.0.1
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search results views class.
*/

    if (!class_exists('DOPBSPViewsFrontEndSearchResults')){
        class DOPBSPViewsFrontEndSearchResults extends DOPBSPViewsFrontEndSearch{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search results.
             * 
             * @param args (array): function arguments
             *                      * atts (object): shortcode attributes
             * 
             * @return search results HTML
             */
            function template($args = array()){
                $atts = $args['atts'];
                $id = $atts['id'];
                
                $html = array();
                
                array_push($html, '<div id="DOPBSPSearch-results-loader'.$id.'" class="dopbsp-loader"></div>');
                array_push($html, '<div id="DOPBSPSearch-results'.$id.'" class="DOPBSPSearch-results"></div>');
                
                return implode('', $html);
            }
            
            function pagination($args = array()){
                $no = $args['no'];
                $page = $args['page'];
                $results = $args['results'];
                
                $html = array();
                
                if ($no > 0){
                    array_push($html, '<hr />');
                    array_push($html, '<ul class="dopbsp-pagination">');
                    
                    for ($i=1; $i<=($no%$results == 0 ? $no/$results:(int)$no/$results+1); $i++){
                        array_push($html, '<li class="dopbsp-page'.$i.($page == $i ? ' dopbsp-selected':'').'">'.$i.'</li>');
                    }
                    array_push($html, '</ul>');
                }
                
                echo implode('', $html);
            }
        }
    }