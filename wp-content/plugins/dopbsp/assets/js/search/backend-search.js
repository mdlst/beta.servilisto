
/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.8
* File                    : assets/js/search/backend-search.js
* File Version            : 1.0.4
* Created / Last Modified : 14 March 2016
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search JavaScript class.
*/

var DOPBSPBackEndSearch = new function(){
    'use strict';
    
    /*
     * Private variables.
     */
    var $ = jQuery.noConflict();

    /*
     * Public variables
     */
    this.ajaxRequestInProgress;
    this.ajaxRequestTimeout;
    
    /*
     * Constructor
     */
    this.__construct = function(){
    };
    
    /*
     * Display search.
     * 
     * @param id (Number): search ID
     * @param language (String): search current editing language
     * @param clearSearch (Boolean): clear search data diplay
     */
    this.display = function(id,
                            language,
                            clearSearch){
        var HTML = new Array();
        
        language = language === undefined ? ($('#DOPBSP-search-language').val() === undefined ? '':$('#DOPBSP-search-language').val()):language;
        clearSearch = clearSearch === undefined ? true:false;
        language = clearSearch ? '':language;
        
        if (clearSearch){
            DOPBSPBackEnd.clearColumns(2);
        }
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-search-ID-'+id).addClass('dopbsp-selected');
        $('#DOPBSP-search-ID').val(id);
        
        $.post(ajaxurl, {action: 'dopbsp_search_display', 
                         id: id,
                         language: language}, function(data){
            HTML.push('<a href="javascript:DOPBSPBackEndSearch.display('+id+')" class="dopbsp-button dopbsp-search dopbsp-selected"><span class="dopbsp-info">'+DOPBSPBackEnd.text('SEARCHES_EDIT_SEARCH')+'</span></a>');
            HTML.push('<a href="javascript:DOPBSPBackEndSettingsSearch.display('+id+')" class="dopbsp-button dopbsp-settings"><span class="dopbsp-info">'+DOPBSPBackEnd.text('SEARCHES_EDIT_SEARCH_SETTINGS')+'</span></a>');
            HTML.push('<a href="javascript:DOPBSPBackEnd.confirmation(\'SEARCHES_DELETE_SEARCH_CONFIRMATION\', \'DOPBSPBackEndSearch.delete('+id+')\')" class="dopbsp-button dopbsp-delete"><span class="dopbsp-info">'+DOPBSPBackEnd.text('SEARCHES_DELETE_SEARCH_SUBMIT')+'</span></a>');
            HTML.push('<a href="'+DOPBSP_CONFIG_HELP_DOCUMENTATION_URL+'" target="_blank" class="dopbsp-button dopbsp-help">');
            HTML.push(' <span class="dopbsp-info dopbsp-help">');
            HTML.push(DOPBSPBackEnd.text('SEARCHES_EDIT_SEARCH_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('SEARCHES_EDIT_SEARCH_SETTINGS_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('SEARCHES_EDIT_SEARCH_DELETE_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('SEARCHES_EDIT_SEARCH_EXCLUDED_CALENDARS_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('HELP_VIEW_DOCUMENTATION'));
            HTML.push(' </span>');
            HTML.push('</a>');
            
            $('#DOPBSP-column2 .dopbsp-column-header').html(HTML.join(''));
            $('#DOPBSP-column2 .dopbsp-column-content').html(data);
            
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('SEARCHES_SEARCH_LOADED'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    /*
     * Add search.
     */
    this.add = function(){
        DOPBSPBackEnd.clearColumns(2);
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('SEARCHES_ADD_SEARCH_ADDING'));

        $.post(ajaxurl, {action: 'dopbsp_search_add'}, function(data){
            $('#DOPBSP-column1 .dopbsp-column-content').html(data);
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('SEARCHES_ADD_SEARCH_SUCCESS'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    /*
     * Edit search.
     * 
     * @param id (Number): search ID
     * @param type (String): field type
     * @param field (String): field name
     * @param value (String): field value
     * @param onBlur (Boolean): true if function has been called on blur event
     */
    this.edit = function(id, 
                         type,
                         field,
                         value, 
                         onBlur){
        var i,
        calendars = new Array();

        onBlur = onBlur === undefined ? false:true;
        
        this.ajaxRequestInProgress !== undefined && !onBlur ? this.ajaxRequestInProgress.abort():'';
        this.ajaxRequestTimeout !== undefined ? clearTimeout(this.ajaxRequestTimeout):'';
        
        switch (field){
            case 'calendars_excluded':
                $('#DOPBSP-search-excluded-calendars input[type=checkbox]').each(function(){
                    if ($(this).is(':checked')){
                        calendars.push($(this).attr('id').split('DOPBSP-search-calendar')[1]);
                        $(this).parent().addClass('dopbsp-selected');
                    }
                    else{
                        $(this).parent().removeClass('dopbsp-selected');
                    }
                });
                value = calendars.join(',');
                break;
            case 'name':
                $('#DOPBSP-search-ID-'+id+' .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        if (onBlur  
                || type === 'checkbox'){
            if (!onBlur){
                DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));
            }
            
            $.post(ajaxurl, {action: 'dopbsp_search_edit',
                             id: id,
                             field: field,
                             value: value}, function(data){
                if (!onBlur){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }
            }).fail(function(data){
                DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
            });
        }
        else{
            DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));

            this.ajaxRequestTimeout = setTimeout(function(){
                clearTimeout(this.ajaxRequestTimeout);

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_search_edit',
                                                              id: id,
                                                              field: field,
                                                              value: value}, function(data){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }).fail(function(data){
                    DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
                });
            }, 600);
        }
    };

    /*
     * Delete search.
     * 
     * @param id (Number): search ID
     */
    this.delete = function(id){
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('SEARCHES_DELETE_SEARCH_DELETING'));

        $.post(ajaxurl, {action: 'dopbsp_search_delete', 
                         id: id}, function(data){
            DOPBSPBackEnd.clearColumns(2);

            $('#DOPBSP-search-ID-'+id).stop(true, true)
                                      .animate({'opacity':0}, 600, function(){
                $(this).remove();

                if (data === '0'){
                    $('#DOPBSP-column1 .dopbsp-column-content').html('<ul><li class="dopbsp-no-data">'+DOPBSPBackEnd.text('SEARCHES_NO_SEARCHES')+'</li></ul>');
                }
                DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('SEARCHES_DELETE_SEARCH_SUCCESS'));
            });
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    return this.__construct();
};