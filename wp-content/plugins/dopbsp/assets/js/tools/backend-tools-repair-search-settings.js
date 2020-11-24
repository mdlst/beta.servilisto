
/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : assets/js/settings/backend-tools-repair-search-settings.js
* File Version            : 1.0.2
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end repair search settings JavaScript class.
*/

var DOPBSPBackEndToolsRepairSearchSettings = new function(){
    'use strict';
    
    /*
     * Private variables
     */
    var $ = jQuery.noConflict(),
    searches = new Array();
    
    /*
     * Constructor
     */
    this.__construct = function(){
    };
    
    /*
     * Initialize search settings repair.
     */
    this.init = function(){
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('TOOLS_REPAIR_SEARCH_SETTINGS_REPAIRING'));
        
        $.post(ajaxurl, {action: 'dopbsp_tools_repair_search_settings_display'}, function(data){
            $('#DOPBSP-column2 .dopbsp-column-content').html($.trim(data));
            
            $.post(ajaxurl, {action: 'dopbsp_tools_repair_search_settings_get'}, function(data){
                searches = $.trim(data).split(',');
            
                DOPBSPBackEndToolsRepairSearchSettings.set(0);
            }).fail(function(data){
                DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
            });
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };
    
    /*
     * Set repair to calendar settings.
     * 
     * @param no (Number): calendars array index
     */
    this.set = function(no){
        $.post(ajaxurl, {action: 'dopbsp_tools_repair_search_settings_set',
                         id: searches[no],
                         no: no}, function(data){
            $('#DOPBSP-tools-repair-search-settings tbody').append(data);
            
            if (no < searches.length-1){
                DOPBSPBackEndToolsRepairSearchSettings.set(no+1);
            }
            else{
                DOPBSPBackEndToolsRepairSearchSettings.clean();
            }
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };
    
    /*
     * Clean calendars settings tables.
     */
    this.clean = function(){
        $.post(ajaxurl, {action: 'dopbsp_tools_repair_search_settings_clean'}, function(data){
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('TOOLS_REPAIR_SEARCH_SETTINGS_SUCCESS'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };
    
    return this.__construct();
};