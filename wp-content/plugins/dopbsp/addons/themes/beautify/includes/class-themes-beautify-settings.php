<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.3
* File                    : addons/themes/beautify/includes/class-themes-beautify-settings.php
* File Version            : 1.0
* Created / Last Modified : 12 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Beautify theme settings PHP class.
*/

    if (!class_exists('DOPBSPThemesBeautifySettings')){
        class DOPBSPThemesBeautifySettings{
            /*
             * Constructor
             */
            function __construct(){
                add_filter('dopbsp_filter_default_settings_calendar', array(&$this, 'setCalendar'), 10);
                add_filter('dopbsp_filter_default_settings_search', array(&$this, 'setSearch'), 10);
            }
            
            /*
             * Set default calendar settings for Beautify theme.
             * 
             * @param default_calendar (array): default calendar options values
             * 
             * @return default calendar settings array for Beautify theme
             */
            function setCalendar($default_calendar){
                $default_calendar['template'] = 'beautify';
                $default_calendar['hours_definitions'] = '[{"value": "00:00"},{"value": "01:00"},{"value": "02:00"},{"value": "03:00"},{"value": "04:00"},{"value": "05:00"},{"value": "06:00"},{"value": "07:00"},{"value": "08:00"},{"value": "09:00"},{"value": "10:00"},{"value": "11:00"},{"value": "12:00"},{"value": "13:00"},{"value": "14:00"},{"value": "15:00"},{"value": "16:00"},{"value": "17:00"},{"value": "18:00"},{"value": "19:00"},{"value": "20:00"},{"value": "21:00"},{"value": "22:00"},{"value": "23:00"}]';
                $default_calendar['hours_enabled'] = 'true';
                                          
                return $default_calendar;
            }
            
            /*
             * Set default search settings for Beautify theme.
             * 
             * @param default_search (array): default search options values
             * 
             * @return default search settings array for Beautify theme
             */
            function setSearch($default_search){
                $default_search['template'] = 'beautify';
                $default_search['view_default'] = 'grid';
                $default_search['view_list_enabled'] = 'true';
                $default_search['view_grid_enabled'] = 'true';
                $default_search['hours_enabled'] = 'true';
                $default_search['availability_enabled'] = 'true';
                
                return $default_search;
            }
        }
    }