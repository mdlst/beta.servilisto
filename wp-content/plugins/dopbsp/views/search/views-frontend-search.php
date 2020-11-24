<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : views/search/views-backend-search.php
* File Version            : 1.0.4
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search views class.
*/

    if (!class_exists('DOPBSPViewsFrontEndSearch')){
        class DOPBSPViewsFrontEndSearch extends DOPBSPViewsFrontEnd{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search.
             * 
             * @param args (array): function arguments
             *                      * atts (object): shortcode attributes
             *                      * search (object): search data
             * 
             * @return search HTML
             */
            function template($args = array()){
                global $DOPBSP;
        
                $atts = $args['atts'];
                $search = $args['search'];
                
                $DOPBSP->classes->translation->set($atts['lang'],
                                                   false,
                                                   array('frontend',
                                                         'calendar'));
                $id = $atts['id'];
                $settings_search = $DOPBSP->classes->backend_settings->values($id,  
                                                                              'search');
                $settings_general = $DOPBSP->classes->backend_settings->values(0,  
                                                                               'general');
                
                $html = array();
                

// HOOK (dopbsp_action_frontend_search_content_before) ****************************** Add content before calendar.
                ob_start();
                    do_action('dopbsp_action_frontend_search_content_before');
                    $content = ob_get_contents();
                ob_end_clean();
                array_push($html, $content);
                
                $template = $settings_search->template;
                
                if (!strpos($template, 'templates') !== false) {
                    $template = $DOPBSP->paths->url.'templates/'.$template;
                }
                
                /*
                 * Search HTML.
                 */
                array_push($html, '<link rel="stylesheet" type="text/css" href="'.$template.'/css/jquery.dop.frontend.BSPSearch.css" />');
                
                array_push($html, '<script type="text/JavaScript">');
                array_push($html, '    dopbspGoogleAPIkey = "'.$settings_general->google_map_api_key.'";');
                
                array_push($html, '    jQuery(document).ready(function(){');
                array_push($html, '        jQuery("#DOPBSPSearch'.$id.'").DOPBSPSearch('.$DOPBSP->classes->frontend_search->getJSON($atts,
                                                                                                                                    $search,
                                                                                                                                    $settings_search).');');
                array_push($html, '    });');
                array_push($html, '</script>');
                
                array_push($html, '<div id="DOPBSPSearch-loader'.$id.'" class="DOPBSPSearch-loader"></div>');

                array_push($html, '<table id="DOPBSPSearch'.$id.'" class="DOPBSPSearch-wrapper DOPBSPSearch-hidden notranslate">');
                array_push($html, ' <colgroup>');
                
                switch ($settings_search->view_sidebar_position){
                    case 'right':
                        array_push($html, '     <col class="dopbsp-results-style" />');
                        array_push($html, '     <col class="dopbsp-column-separator-style" />');
                        array_push($html, '     <col class="dopbsp-sidebar-style" />');
                        break;
                    case 'top':
                        array_push($html, '     <col />');
                        break;
                    default:
                        array_push($html, '     <col class="dopbsp-sidebar-style" />');
                        array_push($html, '     <col class="dopbsp-column-separator-style" />');
                        array_push($html, '     <col class="dopbsp-results-style" />');
                    
                }
                array_push($html, ' </colgroup>');
                array_push($html, ' <tbody>');
                
                switch ($settings_search->view_sidebar_position){
                    case 'right':
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearch-content">');
                        array_push($html, $DOPBSP->views->frontend_search_sort->template(array('atts' => $atts)));
                        array_push($html, $DOPBSP->views->frontend_search_view->template(array('atts' => $atts, 'settings_search' => $settings_search)));
                        array_push($html, '     <br class="DOPBSPSearch-clear" />');
                        array_push($html, '     <hr />');
                        array_push($html, $DOPBSP->views->frontend_search_results->template(array('atts' => $atts, 'settings_search' => $settings_search)));
                        array_push($html, '     </td>');
                        array_push($html, '     <td class="dopbsp-column-separator"></td>');
                        array_push($html, '     <td class="DOPBSPSearch-sidebar">'.$DOPBSP->views->frontend_search_sidebar->template(array('atts' => $atts, 'settings_search' => $settings_search)).'</td>');
                        array_push($html, ' </tr>');
                        break;
                    case 'top':
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearch-sidebar">'.$DOPBSP->views->frontend_search_sidebar->template(array('atts' => $atts, 'settings_search' => $settings_search)).'</td>');
                        array_push($html, ' </tr>');
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearch-content">');
                        array_push($html, $DOPBSP->views->frontend_search_sort->template(array('atts' => $atts)));
                        array_push($html, $DOPBSP->views->frontend_search_view->template(array('atts' => $atts, 'settings_search' => $settings_search)));
                        array_push($html, '     <br class="DOPBSPSearch-clear" />');
                        array_push($html, '     <hr />');
                        array_push($html, $DOPBSP->views->frontend_search_results->template(array('atts' => $atts, 'settings_search' => $settings_search)));
                        array_push($html, '     </td>');
                        array_push($html, ' </tr>');
                        break;
                    default:
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearch-sidebar">'.$DOPBSP->views->frontend_search_sidebar->template(array('atts' => $atts, 'settings_search' => $settings_search)).'</td>');
                        array_push($html, '     <td class="dopbsp-column-separator"></td>');
                        array_push($html, '     <td class="DOPBSPSearch-content">');
                        array_push($html, $DOPBSP->views->frontend_search_sort->template(array('atts' => $atts)));
                        array_push($html, $DOPBSP->views->frontend_search_view->template(array('atts' => $atts, 'settings_search' => $settings_search)));
                        array_push($html, '     <br class="DOPBSPSearch-clear" />');
                        array_push($html, '     <hr />');
                        array_push($html, $DOPBSP->views->frontend_search_results->template(array('atts' => $atts, 'settings_search' => $settings_search)));
                        array_push($html, '     </td>');
                        array_push($html, ' </tr>');
                    
                }
                array_push($html, ' </tbody>');
                array_push($html, '</table>');
                
// HOOK (dopbsp_frontend_content_after_calendar) ******************************* Add content after calendar.
                ob_start();
                    do_action('dopbsp_action_frontend_search_content_after');
                    $content = ob_get_contents();
                ob_end_clean();
                array_push($html, $content);
                
                return implode("\n", $html);
            }
            
            
            
            
            /*
             * Returns search.
             * 
             * @param args (array): function arguments
             *                      * atts (object): shortcode attributes
             *                      * search (object): search data
             * 
             * @return search HTML
             */
            function templateWidget($args = array()){
                global $DOPBSP;
        
                $atts = $args['atts'];
                $search = $args['search'];
                
                $DOPBSP->classes->translation->set($atts['lang'],
                                                   false);
                $id = $atts['id'];
                $settings_search = $DOPBSP->classes->backend_settings->values($id,  
                                                                              'search');
                
                $html = array();

// HOOK (dopbsp_action_frontend_search_content_before) ****************************** Add content before calendar.
                ob_start();
                    do_action('dopbsp_action_frontend_search_widget_content_before');
                    $content = ob_get_contents();
                ob_end_clean();
                array_push($html, $content);
                $settings_search->view_sidebar_position = 'top';
                
                $template = $settings_search->template;
                
                if (!strpos($template, 'templates') !== false) {
                    $template = $DOPBSP->paths->url.'templates/'.$template;
                }
                /*
                 * Search HTML.
                 */
                array_push($html, '<link rel="stylesheet" type="text/css" href="'.$template.'/css/jquery.dop.frontend.BSPSearchWidget.css" />');
                
                array_push($html, '<script type="text/JavaScript">');
                array_push($html, '    jQuery(document).ready(function(){');
                array_push($html, '        jQuery("#DOPBSPSearchWidget'.$id.'").DOPBSPSearchWidget('.$DOPBSP->classes->frontend_search->getJSON($atts,
                                                                                                                                    $search,
                                                                                                                                    $settings_search).');');
                array_push($html, '    });');
                array_push($html, '</script>');
                
                array_push($html, '<div id="DOPBSPSearchWidget-loader'.$id.'" class="DOPBSPSearchWidget-loader"></div>');

                array_push($html, '<table id="DOPBSPSearchWidget'.$id.'" class="DOPBSPSearchWidget-wrapper DOPBSPSearchWidget-hidden notranslate">');
                array_push($html, ' <colgroup>');
                
                switch ($settings_search->view_sidebar_position){
                    case 'right':
                        array_push($html, '     <col class="dopbsp-sidebar-style" />');
                        break;
                    case 'top':
                        array_push($html, '     <col />');
                        break;
                    default:
                        array_push($html, '     <col class="dopbsp-sidebar-style" />');
                    
                }
                array_push($html, ' </colgroup>');
                array_push($html, ' <tbody>');
                
                switch ($settings_search->view_sidebar_position){
                    case 'right':
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearchWidget-sidebar">'.$DOPBSP->views->frontend_search_sidebar->templateWidget(array('atts' => $atts, 'settings_search' => $settings_search)).'</td>');
                        array_push($html, ' </tr>');
                        break;
                    case 'top':
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearchWidget-sidebar">'.$DOPBSP->views->frontend_search_sidebar->templateWidget(array('atts' => $atts, 'settings_search' => $settings_search)).'</td>');
                        array_push($html, ' </tr>');
                        break;
                    default:
                        array_push($html, ' <tr>');
                        array_push($html, '     <td class="DOPBSPSearchWidget-sidebar">'.$DOPBSP->views->frontend_search_sidebar->templateWidget(array('atts' => $atts, 'settings_search' => $settings_search)).'</td>');
                        array_push($html, ' </tr>');
                    
                }
                array_push($html, ' </tbody>');
                array_push($html, '</table>');
                
// HOOK (dopbsp_frontend_content_after_calendar) ******************************* Add content after calendar.
                ob_start();
                    do_action('dopbsp_action_frontend_search_widget_content_after');
                    $content = ob_get_contents();
                ob_end_clean();
                array_push($html, $content);
                
                return implode("\n", $html);
            }
        }
    }