<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : views/search/views-backend-search-sort.php
* File Version            : 1.0.1
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search sort views class.
*/

    if (!class_exists('DOPBSPViewsFrontEndSearchSort')){
        class DOPBSPViewsFrontEndSearchSort extends DOPBSPViewsFrontEndSearch{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search filters.
             * 
             * @param args (array): function arguments
             *                      * atts (object): shortcode attributes
             * 
             * @return search HTML
             */
            function template($args = array()){
                global $DOPBSP;
                
                $atts = $args['atts'];
                $id = $atts['id'];
                
                $html = array();
                
                array_push($html, '<div class="DOPBSPSearch-sort">');
                array_push($html, '     <h5>'.$DOPBSP->text('SEARCH_FRONT_END_SORT_TITLE').'</h5>');
                
                array_push($html, '     <select id="DOPBSPSearch-sort-by'.$id.'" class="dopbsp-small">');
                array_push($html, '         <option value="price">'.$DOPBSP->text('SEARCH_FRONT_END_SORT_PRICE').'</option>');
                array_push($html, '         <option value="name">'.$DOPBSP->text('SEARCH_FRONT_END_SORT_NAME').'</option>');
                array_push($html, '     <select>');
                
                array_push($html, '     <input type="hidden" name="DOPBSPSearch-sort-direction-value'.$id.'" id="DOPBSPSearch-sort-direction-value'.$id.'" value="ASC" />');
                array_push($html, '     <a href="javascript:void(0)" id="DOPBSPSearch-sort-direction'.$id.'" class="dopbsp-asc">');
                array_push($html, '         <span class="dopbsp-info dopbsp-asc">'.$DOPBSP->text('SEARCH_FRONT_END_SORT_ASC').'</span>');
                array_push($html, '         <span class="dopbsp-info dopbsp-desc">'.$DOPBSP->text('SEARCH_FRONT_END_SORT_DESC').'</span>');
                array_push($html, '     </a>');
                
                array_push($html, '</div>');
                
                return implode("\n", $html);
            }
        }
    }