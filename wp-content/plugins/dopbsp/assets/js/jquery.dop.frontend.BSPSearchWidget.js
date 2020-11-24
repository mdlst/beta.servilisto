
/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : assets/js/jquery.dop.frontend.BSPSearch.js
* File Version            : 1.0.1
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Front end search jQuery plugin.
*/

(function($){
    'use strict';
    
    $.fn.DOPBSPSearchWidget = function(options){
        /*
         * Private variables. 
         */
        var Data = {"availability": {"data": {"enabled": false,
                                              "max": 10,
                                              "min": 1},
                                     "text": {"title": "No. book items"}},
                    "currency": {"data": {"code": "USD",
                                          "position": "before",
                                          "sign": "$"},
                                 "text":{},         
                    "days": {"data": {"first": 1,
                                      "multipleSelect": true},
                             "text": {"names": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                                      "shortNames": ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]}},
                    "hours": {"data": {"ampm": false,
                                       "definitions": [{"value": "00:00"},{"value": "01:00"},{"value": "02:00"},{"value": "03:00"},{"value": "04:00"},{"value": "05:00"},{"value": "06:00"},{"value": "07:00"},{"value": "08:00"},{"value": "09:00"},{"value": "10:00"},{"value": "11:00"},{"value": "12:00"},{"value": "13:00"},{"value": "14:00"},{"value": "15:00"},{"value": "16:00"},{"value": "17:00"},{"value": "18:00"},{"value": "19:00"},{"value": "20:00"},{"value": "21:00"},{"value": "22:00"},{"value": "23:00"}],
                                       "enabled": false,
                                       "multipleSelect": true},
                              "text": {}},
                    "ID": 0,
                    "REDIRECT_ID": 0,
                    "months": {"data": {},
                               "text": {"names": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                                        "nextMonth": "Next month",
                                        "previousMonth": "Previous month",
                                        "shortNames": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]}},
                    "price": {"data": {"enabled": true,
                                       "max": 1000,
                                       "min": 0},
                              "text": {}},
                    "search": {"data": {"dateType": 1,
                                        "enabled": false,
                                        "language": 'en',
                                        "template": 'default'},
                               "text": {"checkIn": "Check in",
                                        "checkOut": "Check out",
                                        "hourEnd": "Finish at",
                                        "hourStart": "Start at",
                                        "title": "Search"}},  
                    "sort": {"data": {},
                             "text": {"name": "Name",
                                      "price": "Price",
                                      "title": "Sort by"}},
                    "URL": "",
                    "view": {"data": {"default": "list",
                                      "gridEnabled": false,
                                      "listEnabled": true,
                                      "mapEnabled": false,
                                      "results": 10},
                             "text": {"grid": "Grid view",
                                      "list": "List view",
                                      "map": "Map view"}}}},
                              
        ajaxRequestInProgress,
        ajaxURL = '',
        Container = this,
        ID = 0,
        REDIRECT_ID = 0,
        
// ***************************************************************************** Main methods.

// 1. Main methods.

        methods = {
            init:function(){
                /*
                 * Initialize jQuery plugin.
                 */
                return this.each(function(){
                    if (options){
                        $.extend(Data, options);
                    }

                    methods.parse();
                    $(window).bind('resize.DOPBSPSearchWidget', methods.rp);                          
                });
            },
            parse:function(){
                ajaxURL = prototypes.acaoBuster(Data['URL']);
                
                methods_availability.data = Data['availability']['data'];
                methods_availability.text = Data['availability']['text'];
                
                methods_currency.data = Data['currency']['data'];
                methods_currency.text = Data['currency']['text'];
                
                methods_days.data = Data['days']['data'];
                methods_days.text = Data['days']['text'];
                
                methods_hours.data = Data['hours']['data'];
                methods_hours.text = Data['hours']['text'];
                
                ID = Data['ID'];
                REDIRECT_ID = Data['REDIRECT_ID'];
                
                methods_months.data = Data['months']['data'];
                methods_months.text = Data['months']['text'];
                
                methods_price.data = Data['price']['data'];
                methods_price.text = Data['price']['text'];
                
                methods_search.data = Data['search']['data'];
                methods_search.text = Data['search']['text'];
                
                methods_sort.data = Data['sort']['data'];
                methods_sort.text = Data['sort']['text'];
                
                methods_view.data = Data['view']['data'];
                methods_view.text = Data['view']['text'];
                
                methods_components.init();
            },
            rp:function(){
                var containerW = parseInt($('#DOPBSPSearchWidget'+ID).parent().width()),
                    widgetClass = 'DOPBSPSearchWidget-360';
                
                if(containerW >= 360
                  && containerW <= 740) {
                    widgetClass = 'DOPBSPSearchWidget-360';
                } else if(containerW >= 740
                  && containerW <= 1000) {
                    widgetClass = 'DOPBSPSearchWidget-740';
                } else if(containerW >= 1000
                  && containerW <= 1230) {
                    widgetClass = 'DOPBSPSearchWidget-1000';
                } else if(containerW >= 1230) {
                    widgetClass = 'DOPBSPSearchWidget-1230';
                }
                
                $('#DOPBSPSearchWidget'+ID).removeClass('DOPBSPSearchWidget-360');
                $('#DOPBSPSearchWidget'+ID).removeClass('DOPBSPSearchWidget-740');
                $('#DOPBSPSearchWidget'+ID).removeClass('DOPBSPSearchWidget-1000');
                $('#DOPBSPSearchWidget'+ID).removeClass('DOPBSPSearchWidget-1230');
                $('#DOPBSPSearchWidget'+ID).addClass(widgetClass);
                
            }
        },
                
// 2. Components
        
        methods_components = {
            init:function(){
            /*
             * Initialize search components.
             */ 
                /*
                 * Initialize today date.
                 */
                methods_search.vars.todayDate = new Date();
                methods_search.vars.todayDay = methods_search.vars.todayDate.getDate();
                methods_search.vars.todayMonth = methods_search.vars.todayDate.getMonth()+1;
                methods_search.vars.todayYear = methods_search.vars.todayDate.getFullYear(); 
                
                /*
                 * Initialize map.
                 */
                if (methods_view.data['mapEnabled']){
                    methods_map.load();
                }
                
                /*
                 * Initialize sidebar.
                 */
                methods_sidebar.init();
                methods.rp();
                
                $('#DOPBSPSearchWidget-loader'+ID).remove();
                $(Container).removeClass('DOPBSPSearchWidget-hidden');
                
                methods_search.get();
            }
        },  
                
// 3. Currency
        
        methods_currency = {
            data:{},
            text:{},
            vars: {currencies: {}},
            
            init: function() {
                //methods_currency.parse();
            },
            
            parse: function(){
                var currencies = {};
                
                if (typeof methods_currency.data['currencies'] !== 'undefined') {
                    var currenciesJSON = methods_currency.data['currencies'].replace(new RegExp('"', 'g'), ""),
                        currenciesJSON = currenciesJSON.replace(new RegExp(';;;', 'g'), '"');
                
                        currencies = JSON.parse(currenciesJSON);
                }
            
                methods_currency.vars.currencies = currencies;
                
            },
            
            exchange: function(amount, currency_code, type){ 
                var currencies = methods_currency.vars.currencies;
                //methods_currency.data['sign']
                if (methods_currency.data['code'] !== currency_code) {
                    
                    for (var index in currencies){

                        if (currencies[index]['code'] === currency_code) {
                            
                            if (type === 'exchange') {
                                amount = parseInt(amount*currencies[index]['rate']);
                            } else {
                                amount = parseInt(amount*currencies[index]['inverse']);
                            }
                        }
                    }
                }
                
                return amount;
            }
            
        },   
   
// ***************************************************************************** Search methods.

// 4. Search
        
        methods_search = {
            data: {},
            text: {},
            vars: {todayDate: new Date(),
                   todayDay: new Date(),
                   todayMonth: new Date(),
                   todayYear: new Date()},
            
            init: function(){
                methods_days.init();
                
                if (methods_hours.data['enabled']){
                    methods_hours.init();
                }
                
                if (methods_availability.data['enabled']){
                    methods_availability.init();
                }
                
                methods_check_availability.init();
                
                if (methods_price.data['enabled']){
                    methods_price.init();
                }
                
                methods_sort.init();
                methods_view.init();
            },
            
            get:function(page){
                var $checkIn = $('#DOPBSPSearchWidget-check-in'+ID).val(),
                $checkOut = $('#DOPBSPSearchWidget-check-out'+ID).val(),
                $startHour = $('#DOPBSPSearchWidget-start-hour'+ID).val(),
                $endHour = $('#DOPBSPSearchWidget-end-hour'+ID).val(),
                checkIn = $checkIn === undefined ? '':$checkIn,
                checkOut = $checkOut === undefined || $checkOut === '' ? checkIn:$checkOut,
                startHour = $startHour === undefined ? '':$startHour,
                endHour = $endHour === undefined || $endHour === '' ? startHour:$endHour;
        
                page = page === undefined ? 1:page;
                
                if (ajaxRequestInProgress !== undefined){
                    ajaxRequestInProgress.abort();
                }
                $('#DOPBSPSearchWidget-results-loader'+ID).removeClass('DOPBSPSearchWidget-hidden');
                $('#DOPBSPSearchWidget-results'+ID).html('');
                
                ajaxRequestInProgress = $.post(ajaxURL, {action: 'dopbsp_search_results_get',
                                                         dopbsp_frontend_ajax_request: true,
                                                         id: ID,
                                                         language: methods_search.data['language'],
                                                         check_in: checkIn,
                                                         check_out: checkOut,
                                                         start_hour: startHour,
                                                         end_hour: endHour,
                                                         no_items: $('#DOPBSPSearchWidget-no-items'+ID).val() === undefined ? '':$('#DOPBSPSearchWidget-no-items'+ID).val(),
                                                         price_min: $('#DOPBSPSearchWidget-price-min-value'+ID).val() === undefined ? '':$('#DOPBSPSearchWidget-price-min-value'+ID).val(),
                                                         price_max: $('#DOPBSPSearchWidget-price-max-value'+ID).val() === undefined ? '':$('#DOPBSPSearchWidget-price-max-value'+ID).val(),
                                                         sort_by: $('#DOPBSPSearchWidget-sort-by'+ID).val() === undefined ? 'price':$('#DOPBSPSearchWidget-sort-by'+ID).val(),
                                                         sort_direction: $('#DOPBSPSearchWidget-sort-direction-value'+ID).val() === undefined ? 'ASC':$('#DOPBSPSearchWidget-sort-direction-value'+ID).val(),
                                                         view: $('#DOPBSPSearchWidget-view'+ID).val() === undefined ? 'list':$('#DOPBSPSearchWidget-view'+ID).val(),
                                                         results: methods_view.data['results'],
                                                         page: page}, function(data){
                        data = $.trim(data);
                        
                        switch ($('#DOPBSPSearchWidget-view'+ID).val()){
                            case 'map':
                                methods_map.display(data);
                                break;
                            default:
                                $('#DOPBSPSearchWidget-results-loader'+ID).addClass('DOPBSPSearchWidget-hidden');
                                $('#DOPBSPSearchWidget-results'+ID).html(data);
                                methods_search.events();
                        }
                });
            },
            
            events:function(){
                $('#DOPBSPSearchWidget-results'+ID+' .dopbsp-pagination li').unbind('click');
                $('#DOPBSPSearchWidget-results'+ID+' .dopbsp-pagination li').bind('click', function(){
                    if (!$(this).hasClass('selected')){
                        methods_search.get($(this).attr('class').split('dopbsp-page')[1]);
                    }
                });
            }
        },      
        
// 5. Sidebar
        
        methods_sidebar = {
            data:{},
            text:{},
            
            init:function(){
                methods_search.init();
                
                // $('.dopbsp-search-sidebar-form', Container).isotope({itemSelector: '.dopbsp-module', layoutMode: 'fitRows' });
            }
        },    
                
// 6. Months
        
        methods_months = {
            data:{},
            text:{}
        },
                
// 7. Days
        
        methods_days = {
            data:{},
            text:{},
            
            init:function(){
            /*
             * Initialize sidebar search days.
             */ 
                methods_days.events.init();
            },
            initDatepicker:function(id,
                                    altId,    
                                    minDate){
            /*
             * Initialize sidebar search datepicker.
             * 
             * @param id (String): input(text) field ID
             * @param aldId (String): alternative input(hidden) field ID
             * @param minDate (Number): start date from today
             */                            
                minDate = minDate === undefined ? 0:minDate;  

                $(id).datepicker('destroy');
                $(id).datepicker({altField: altId,
                                  altFormat: 'yy-mm-dd',
                                  beforeShow: function(input, inst){
                                    $('#ui-datepicker-div').removeClass('DOPBSPSearchWidget-datepicker')
                                                           .addClass('DOPBSPSearchWidget-datepicker');
                                  },
                                  dateFormat: methods_search.data['dateType'] === 1 ? 'MM dd, yy':'dd MM yy',
                                  dayNames: methods_days.text['names'],
                                  dayNamesMin: methods_days.text['shortNames'],
                                  firstDay: methods_days.data['first'],
                                  minDate: minDate,
                                  monthNames: methods_months.text['names'],
                                  monthNamesMin: methods_months.text['shortNames'],
                                  nextText: methods_months.text['nextMonth'],
                                  prevText: methods_months.text['previousMonth']});
                $('.ui-datepicker').removeClass('notranslate').addClass('notranslate');
            },
            validate:function(day){
            /*
             * Validate day format.
             * 
             * @param day (String): day format to be verified
             * 
             * @return true if format is "YYYY-MM-DD"
             */    
                var dayPieces = day.split('-');

                if (day === ''
                        || dayPieces.length !== 3
                        || dayPieces[0].length !== 4
                        || dayPieces[1].length !== 2
                        || dayPieces[2].length !== 2){
                    return false;
                }
                else{
                    return true;
                }
            },

            events: {
                init:function(){
                /*
                 * Initialize sidebar search days events.
                 */    
                    /*
                     * Initialize check in datepicker.
                     */
                    methods_days.initDatepicker('#DOPBSPSearchWidget-check-in-view'+ID,
                                                       '#DOPBSPSearchWidget-check-in'+ID);
                    methods_days.events.checkIn();

                    if (methods_days.data['multipleSelect']){
                        /*
                         * Initialize check out datepicker.
                         */
                        methods_days.initDatepicker('#DOPBSPSearchWidget-check-out-view'+ID,
                                                           '#DOPBSPSearchWidget-check-out'+ID);
                        methods_days.events.checkOut();
                    }
                },
                checkIn:function(){
                /*
                 * Initialize sidebar search days events when multiple days need to be selected.
                 */
                    /*
                     * Check in click event.
                     */
                    $('#DOPBSPSearchWidget-check-in-view'+ID).unbind('click');
                    $('#DOPBSPSearchWidget-check-in-view'+ID).bind('click', function(){
                        $(this).val('');
                        $('#DOPBSPSearchWidget-check-in'+ID).val('');

                    });

                    /*
                     * Check in blur event.
                     */
                    $('#DOPBSPSearchWidget-check-in-view'+ID).unbind('blur');
                    $('#DOPBSPSearchWidget-check-in-view'+ID).bind('blur', function(){  
                        var $this = $(this);

                        if ($this.val() === ''){
                            $this.val(methods_search.text['checkIn']);
                            $('#DOPBSPSearchWidget-check-in'+ID).val('');
                        }
                    });

                    /*
                     * Check in change event.
                     */
                    $('#DOPBSPSearchWidget-check-in-view'+ID).unbind('change');
                    $('#DOPBSPSearchWidget-check-in-view'+ID).bind('change', function(){
                        var ciDay = $('#DOPBSPSearchWidget-check-in'+ID).val(),
                        minDateValue;

                        if (methods_days.validate(ciDay)){
                            if (methods_days.data['multipleSelect']){
                                minDateValue = prototypes.getNoDays(prototypes.getToday(), ciDay)-1;

                                methods_days.initDatepicker('#DOPBSPSearchWidget-check-out-view'+ID,
                                                            '#DOPBSPSearchWidget-check-out'+ID,
                                                            minDateValue);

                                if (($('#DOPBSPSearchWidget-check-in'+ID).val() > $('#DOPBSPSearchWidget-check-out'+ID).val()
                                                && $('#DOPBSPSearchWidget-check-out'+ID).val() !== '')
                                        || $('#DOPBSPSearchWidget-check-out'+ID).val() === ''){
                                    setTimeout(function(){
                                        $('#DOPBSPSearchWidget-check-out-view'+ID).val('')
                                                                              .select();  
                                        $('#DOPBSPSearchWidget-check-out'+ID).val('');
                                    }, 100);
                                }
                                else{
                                    methods_search.get();
                                }
                            }
                            else{
                                methods_search.get();
                            }
                        }
                        else{
                            $('#DOPBSPSearchWidget-check-in-view'+ID).val(methods_search.text['checkIn']);
                            $('#DOPBSPSearchWidget-check-in'+ID).val('');
                        }
                    });
                },
                checkOut:function(){
                    /*
                     * Check out click event.
                     */
                    $('#DOPBSPSearchWidget-check-out-view'+ID).unbind('click');
                    $('#DOPBSPSearchWidget-check-out-view'+ID).bind('click', function(){  
                        $(this).val(''); 
                        $('#DOPBSPSearchWidget-check-out'+ID).val('');
                    });

                    /*
                     * Check out blur event.
                     */
                    $('#DOPBSPSearchWidget-check-out-view'+ID).unbind('blur');
                    $('#DOPBSPSearchWidget-check-out-view'+ID).bind('blur', function(){ 
                        var $this = $(this);

                        if ($this.val() === ''){
                            $this.val(methods_search.text['checkOut']);
                            $('#DOPBSPSearchWidget-check-out'+ID).val('');
                        }
                    });

                    /*
                     * Check out change event.
                     */
                    $('#DOPBSPSearchWidget-check-out-view'+ID).unbind('change');
                    $('#DOPBSPSearchWidget-check-out-view'+ID).bind('change', function(){
                        var coDay = $('#DOPBSPSearchWidget-check-out'+ID).val();
                        
                        if (methods_days.validate(coDay)){
                            if ($('#DOPBSPSearchWidget-check-in'+ID).val() !== ''){
                                methods_search.get();
                            }
                        }
                        else{
                            $('#DOPBSPSearchWidget-check-out-view'+ID).val(methods_search.text['checkOut']);
                            $('#DOPBSPSearchWidget-check-out'+ID).val('');
                        }
                    });
                }
            }
        },
                
// 8. Hours
        
        methods_hours = {
            data:{},
            text:{},
                
            init:function(){
                $('#DOPBSPSearchWidget-start-hour'+ID).DOPSelect();

                if (methods_hours.data['multipleSelect']){
                    $('#DOPBSPSearchWidget-end-hour'+ID).DOPSelect();
                }
                methods_hours.events();
            },
            set:function(){
                var HTML = new Array(),
                hours = methods_hours.data['definitions'],
                i,
                startHour = $('#DOPBSPSearchWidget-start-hour'+ID).val(),
                endHour = $('#DOPBSPSearchWidget-end-hour'+ID).val();

                HTML.push('<select id="DOPBSPSearchWidget-end-hour'+ID+'" class="dopbsp-small">');

                for (i=0; i<hours.length; i++){
                    if (startHour <= hours[i]['value']){
                        HTML.push('     <option value="'+hours[i]['value']+'"'+(endHour === hours[i]['value'] ? ' selected="selected"':'')+'>'+prototypes.getAMPM(hours[i]['value'])+'</option>');
                    }
                }
                HTML.push('</select>');

                $('#DOPSelect-DOPBSPSearchWidget-end-hour'+ID).replaceWith(HTML.join());
                $('#DOPBSPSearchWidget-end-hour'+ID).DOPSelect();
                methods_hours.events();
            },

            events:function(){
                $('#DOPBSPSearchWidget-start-hour'+ID).unbind('change');
                $('#DOPBSPSearchWidget-start-hour'+ID).bind('change',function(){
                    if (methods_hours.data['multipleSelect']){
                        methods_hours.set();
                    }
                    methods_search.get();
                });

                if (methods_hours.data['multipleSelect']){
                    $('#DOPBSPSearchWidget-end-hour'+ID).unbind('change');
                    $('#DOPBSPSearchWidget-end-hour'+ID).bind('change',function(){
                        if ($('#DOPBSPSearchWidget-start-hour'+ID).val() !== ''){
                            methods_search.get();
                        }
                    });
                }
            }
        },
                
// 9. No items.
        
        methods_availability = {
            data:{},
            text:{},
            
            init:function(){
                $('#DOPBSPSearchWidget-no-items'+ID).DOPSelect();
                methods_availability.events();
            },

            events:function(){
                $('#DOPBSPSearchWidget-no-items'+ID).unbind('change');
                $('#DOPBSPSearchWidget-no-items'+ID).bind('change',function(){
                    methods_search.get();
                });
            }
        },
                
// 11. Check Availability         
                
        methods_check_availability = {
            data:{},
            text:{},
            
            init:function(){
                $('#DOPBSPSearchWidget-check-availability'+ID).unbind('click');
                $('#DOPBSPSearchWidget-check-availability'+ID).bind('click', function(){
                    var $checkIn = $('#DOPBSPSearchWidget-check-in'+ID).val(),
                    $checkOut = $('#DOPBSPSearchWidget-check-out'+ID).val(),
                    $startHour = $('#DOPBSPSearchWidget-start-hour'+ID).val(),
                    $endHour = $('#DOPBSPSearchWidget-end-hour'+ID).val(),
                    checkIn = $checkIn === undefined ? '':$checkIn,
                    checkOut = $checkOut === undefined || $checkOut === '' ? checkIn:$checkOut,
                    startHour = $startHour === undefined ? '':$startHour,
                    endHour = $endHour === undefined || $endHour === '' ? startHour:$endHour,
                    no_items = $('#DOPBSPSearchWidget-no-items'+ID).val();
                    $('#DOPBSPSearchWidget-error'+ID).remove();
                    
                    $.post(ajaxURL, {action: 'dopbsp_search_widget_get',
                                     dopbsp_frontend_ajax_request: true,
                                     id: ID,
                                     redirect_id: REDIRECT_ID,
                                     language: methods_search.data['language'],
                                     check_in: checkIn,
                                     check_out: checkOut,
                                     start_hour: startHour,
                                     end_hour: endHour,
                                     no_items: no_items
                                     }, function(data){
                        data = $.trim(data);
                        
                        if(data === 'no_data') {
                            $('#DOPBSPSearchWidget-check-availability'+ID).parent().after('<div class="dopbsp-input-wrapper"><div id="DOPBSPSearchWidget-error'+ID+'" class="dopbsp-error">No services available, please choose other dates.</div></div>');
						} 
                        else {
                            window.location.href = data;
                        }
                    });
                });
            }
        },
                
                
                
// 12. Price         
                
        methods_price = {
            data:{},
            text:{},
            
            init:function(){
                $('#DOPBSPSearchWidget-price'+ID).slider({max: methods_price.data['max'],
                                                    min: methods_price.data['min'],
                                                    values: [methods_price.data['min'], methods_price.data['max']],
                                                    range: true,
                                                    step: 1,
                                                    create:function(event, ui){
                                                        $('#DOPBSPSearchWidget-price-min'+ID).html(methods_price.set(methods_price.data['min']));
                                                        $('#DOPBSPSearchWidget-price-max'+ID).html(methods_price.set(methods_price.data['max']));
                                                        
                                                        $('#DOPBSPSearchWidget-price-min-value'+ID).val(methods_price.data['min']);
                                                        $('#DOPBSPSearchWidget-price-max-value'+ID).val(methods_price.data['max']);
                                                    },
                                                    slide:function(event, ui){
                                                        $('#DOPBSPSearchWidget-price-min'+ID).html(methods_price.set(ui.values[0]));
                                                        $('#DOPBSPSearchWidget-price-max'+ID).html(methods_price.set(ui.values[1]));
                                                        
                                                        $('#DOPBSPSearchWidget-price-min-value'+ID).val(ui.values[0]);
                                                        $('#DOPBSPSearchWidget-price-max-value'+ID).val(ui.values[1]);
                                                    },
                                                    stop:function(event, ui){
                                                        methods_search.get();
                                                    }});
            },
            
            set:function(price){
            /*
             * Display price with currency in set format.
             * 
             * @param price (Number): price value
             * 
             * @return price with currency
             */ 
                var priceDisplayed = '';
                
                price = prototypes.getWithDecimals(Math.abs(price), 
                                                   2);
                                                   
                switch (methods_currency.data['position']){
                    case 'after':
                        priceDisplayed =  price+methods_currency.data['sign'];
                        break;
                    case 'after_with_space':
                        priceDisplayed =  price+' '+methods_currency.data['sign'];
                        break;
                    case 'before_with_space':
                        priceDisplayed =  methods_currency.data['sign']+' '+price;
                        break;
                    default:
                        priceDisplayed = methods_currency.data['sign']+price;
                }
                
                return priceDisplayed;
            }
        },   
                
// 13. Sort
        
        methods_sort = {
            data:{},
            text:{},
            
            init:function(){
                $('#DOPBSPSearchWidget-sort-by'+ID).DOPSelect();
                methods_sort.events();
            },

            events: function(){
                $('#DOPBSPSearchWidget-sort-by'+ID).unbind('change');
                $('#DOPBSPSearchWidget-sort-by'+ID).bind('change',function(){
                    methods_search.get();
                });

                $('#DOPBSPSearchWidget-sort-direction'+ID).unbind('click');
                $('#DOPBSPSearchWidget-sort-direction'+ID).bind('click',function(){
                    if ($(this).hasClass('dopbsp-asc')){
                        $(this).removeClass('dopbsp-asc')
                               .addClass('dopbsp-desc');
                        $('#DOPBSPSearchWidget-sort-direction-value'+ID).val('DESC');
                    }
                    else{
                        $(this).removeClass('dopbsp-desc')
                               .addClass('dopbsp-asc');
                        $('#DOPBSPSearchWidget-sort-direction-value'+ID).val('ASC');
                    }
                    methods_search.get();
                });
            }
        },
                
// 14. View
        
        methods_view = {
            data:{},
            text:{},
            
            init:function(){
                methods_view.events();
            },
            
            events:function(){
                if (methods_view.data['listEnabled']){
                    $('#DOPBSPSearchWidget-view-list'+ID).unbind('click');
                    $('#DOPBSPSearchWidget-view-list'+ID).bind('click',function(){
                        if (!$(this).hasClass('dopbsp-selected')){
                            methods_view.data['gridEnabled'] ? $('#DOPBSPSearchWidget-view-grid'+ID).removeClass('dopbsp-selected'):'';
                            methods_view.data['mapEnabled'] ? $('#DOPBSPSearchWidget-view-map'+ID).removeClass('dopbsp-selected'):'';
                            $(this).addClass('dopbsp-selected');
                            
                            $('#DOPBSPSearchWidget-view'+ID).val('list');
                            methods_search.get();
                        }
                    });
                }
                
                if (methods_view.data['gridEnabled']){
                    $('#DOPBSPSearchWidget-view-grid'+ID).unbind('click');
                    $('#DOPBSPSearchWidget-view-grid'+ID).bind('click',function(){
                        if (!$(this).hasClass('dopbsp-selected')){
                            methods_view.data['listEnabled'] ? $('#DOPBSPSearchWidget-view-list'+ID).removeClass('dopbsp-selected'):'';
                            methods_view.data['mapEnabled'] ? $('#DOPBSPSearchWidget-view-map'+ID).removeClass('dopbsp-selected'):'';
                            $(this).addClass('dopbsp-selected');
                            
                            $('#DOPBSPSearchWidget-view'+ID).val('grid');
                            methods_search.get();
                        }
                    });
                }
                
                if (methods_view.data['mapEnabled']){
                    $('#DOPBSPSearchWidget-view-map'+ID).unbind('click');
                    $('#DOPBSPSearchWidget-view-map'+ID).bind('click',function(){
                        if (!$(this).hasClass('dopbsp-selected')){
                            methods_view.data['listEnabled'] ? $('#DOPBSPSearchWidget-view-list'+ID).removeClass('dopbsp-selected'):'';
                            methods_view.data['gridEnabled'] ? $('#DOPBSPSearchWidget-view-grid'+ID).removeClass('dopbsp-selected'):'';
                            $(this).addClass('dopbsp-selected');
                            
                            $('#DOPBSPSearchWidget-view'+ID).val('map');
                            methods_search.get();
                        }
                    });
                }
            }
        },
                
// 15. Map
        
        methods_map = {
            vars:{locations: new Array(),
                  map: null},
            
            load:function(){
                if (typeof google !== 'object' 
                        || typeof google.maps !== 'object'){
                    var script = document.createElement('script');
                    
                    script.type = 'text/JavaScript';
                    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&callback=DOPBSPSearchWidgetLoadInfobox';
                    
                    $('body').append(script);
                }
                else{
                    DOPBSPSearchWidgetLoadInfobox();
                }
            },
            
            display:function(data){
                var HTML = new Array();
                if (typeof google === 'object' 
                        && typeof google.maps === 'object'
                        && typeof InfoBox === 'function'){
                    methods_map.vars.locations = JSON.parse(data.split(';;;;;')[0]);
                    
                    HTML.push('<div id="DOPBSPSearchWidget-results-map'+ID+'" class="dopbsp-map"></div>');
                    HTML.push(data.split(';;;;;')[1]);
                    
                    $('#DOPBSPSearchWidget-results-loader'+ID).addClass('DOPBSPSearchWidget-hidden');
                    $('#DOPBSPSearchWidget-results'+ID).html(HTML.join(''));
                    
                    methods_search.events();
                    
                    setTimeout(function(){
                        google.maps.event.addDomListener(window, 'load', methods_map.init());
                    }, 100);
                }
                else{
                    setTimeout(function(){
                        methods_map.display();
                    }, 500);
                }
                
            },
            init:function(){
                var //$coordinates = $('#DOPBSP-location-coordinates').val(),
                //coordinates = $coordinates === undefined || $coordinates === '' || $coordinates === ' ' ? [0, 0]:JSON.parse($coordinates),
                options;
               // zoom = coordinates[0] === 0 && coordinates[1] === 0  ? 2:17;

                options = {center: new google.maps.LatLng(0, 0),
                           mapTypeId: google.maps.MapTypeId.ROADMAP,
                           zoom: 2};
                /*
                 * Create the map
                 */          
                methods_map.vars.map = new google.maps.Map(document.getElementById('DOPBSPSearchWidget-results-map'+ID), options);

//                DOPBSPLocationMapMarker.set(map,
//                                            coordinates);
                methods_map.set();
            },
            set:function(){
                var bounds = new google.maps.LatLngBounds(),
                coordinates,
                icon = new google.maps.MarkerImage(Data['pluginURL']+'templates/'+methods_search.data['template']+'/images/marker.png',
                                                   new google.maps.Size(36, 52),
                                                   new google.maps.Point(1, 0),
                                                   new google.maps.Point(18, 52)),
                i,
                locations = methods_map.vars.locations,
                markers = new Array(),
                position = new Array(),
                shadow = new google.maps.MarkerImage(Data['pluginURL']+'templates/'+methods_search.data['template']+'/images/marker.png',
                                                     new google.maps.Size(36, 52),
                                                     new google.maps.Point(1, 0),
                                                     new google.maps.Point(18, 52)),
                shape = {coord: [0, 0, 36, 0, 36, 52, 0, 52],
                         type: 'poly'};
                     
                for (i=0; i<locations.length; i++){
                    coordinates = JSON.parse(locations[i]['coordinates']);
                    position = new google.maps.LatLng(coordinates[0], coordinates[1]);
                    bounds.extend(position);
                    
                    markers[i] = new google.maps.Marker({animation: null,
                                                         clickable: true,
                                                         draggable: false,
                                                         icon: icon,
                                                         map: methods_map.vars.map,
                                                         position: position,
                                                         shadow: shadow,
                                                         shape: shape});

                    markers[i].info = new InfoBox({alignBottom: true,
                                                   boxClass: 'dopbsp-infobox',
                                                   closeBoxMargin: '0px',
                                                   closeBoxURL: Data['pluginURL']+'templates/'+methods_search.data['template']+'/images/close.png',
                                                   disableAutoPan: false,
                                                   content: methods_map.get(locations[i]['calendars'], i),
                                                   isHidden: false,
                                                   infoBoxClearance: new google.maps.Size(20, 20),
                                                   pixelOffset: new google.maps.Size(-190, -60),
                                                   position: position});
                                               
                    markers[i].index = i;
                                               
                    google.maps.event.addListener(markers[i], 'click', function(){
                        var index = this.index;
                        
                        for (i=0; i<locations.length; i++){
                            markers[i].info.close();
                        }
                        this.info.open(methods_map.vars.map, this);
                        
                        setTimeout(function(){
                            methods_map.events(index);
                        }, 100);
                    });
                }
                methods_map.vars.map.fitBounds(bounds);
            },
            
            get:function(calendars,
                         index){
                var HTML = new Array(),
                i;
                
                HTML.push('<ul class="dopbsp-locations" id="DOPBSPSearchWidget-locations-'+ID+'-'+index+'">');
                
                for (i=0; i<calendars.length; i++){
                    HTML.push('<li>');
                    HTML.push('     <div class="dopbsp-image">');
                    HTML.push('         <a href="'+calendars[i]['link']+'" target="_self" style="background-image: url('+calendars[i]['image']+');">');
                    HTML.push('             <img src="'+calendars[i]['image']+'" alt="'+calendars[i]['name']+'" title="'+calendars[i]['name']+'" />');
                    HTML.push('         </a>');
                    HTML.push('     </div>');
                    HTML.push('     <div class="dopbsp-content">');
                    HTML.push('         <h3>');
                    HTML.push('             <a href="'+calendars[i]['link']+'" target="_self">'+calendars[i]['name']+'</a>');
                    HTML.push('         </h3>');
                    HTML.push('         <div class="dopbsp-address">'+(calendars[i]['address_alt'] === '' ? calendars[i]['address']:calendars[i]['address_alt'])+'</div>');
                    HTML.push('         <div class="dopbsp-price-wrapper">'+calendars[i]['price']+'</div>');
                    HTML.push('     </div>');
                    HTML.push('</li>');
                }
                HTML.push('</ul>');
                
                if (calendars.length > 1){
                    HTML.push('<div class="dopbsp-navigation" id="DOPBSPSearchWidget-locations-navigation-'+ID+'-'+index+'">');
                    HTML.push('     <a href="javascript:void(0)" class="dopbsp-prev dopbsp-disabled"></a>');
                    HTML.push('     <a href="javascript:void(0)" class="dopbsp-next"></a>');
                    HTML.push('</div>');
                }
                
                return HTML.join('');
            },
            
            events:function(i){
                $('#DOPBSPSearchWidget-locations-navigation-'+ID+'-'+i+' .dopbsp-prev').unbind('click');
                $('#DOPBSPSearchWidget-locations-navigation-'+ID+'-'+i+' .dopbsp-prev').bind('click', function(){
                    var $this = $(this),
                    id = $this.parent().attr('id').split('DOPBSPSearchWidget-locations-navigation-')[1],
                    $li = $('#DOPBSPSearchWidget-locations-'+id+' li:first-child');
                    
                    if (!$this.hasClass('dopbsp-disabled')){
                        $('#DOPBSPSearchWidget-locations-navigation-'+ID+'-'+i+' .dopbsp-next').removeClass('dopbsp-disabled');
                        $li.css('margin-top', parseInt($li.css('margin-top'))+($li.height()+parseInt($li.css('margin-bottom'))));
                        
                        setTimeout(function(){
                            if (parseInt($li.css('margin-top')) >= 0){
                                $this.addClass('dopbsp-disabled');
                            }
                        }, 150);
                    }
                });
                
                $('#DOPBSPSearchWidget-locations-navigation-'+ID+'-'+i+' .dopbsp-next').unbind('click');
                $('#DOPBSPSearchWidget-locations-navigation-'+ID+'-'+i+' .dopbsp-next').bind('click', function(){
                    var $this = $(this),
                    id = $this.parent().attr('id').split('DOPBSPSearchWidget-locations-navigation-')[1],
                    $li = $('#DOPBSPSearchWidget-locations-'+id+' li:first-child'),
                    locations = methods_map.vars.locations;
                    
                    if (!$this.hasClass('dopbsp-disabled')){
                        $('#DOPBSPSearchWidget-locations-navigation-'+ID+'-'+i+' .dopbsp-prev').removeClass('dopbsp-disabled');
                        $li.css('margin-top', parseInt($li.css('margin-top'))-($li.height()+parseInt($li.css('margin-bottom'))));
                        
                        setTimeout(function(){
                            if (-1*parseInt($li.css('margin-top')) >= ($li.height()+parseInt($li.css('margin-bottom')))*(locations.length-1)){
                                $this.addClass('dopbsp-disabled');
                            }
                        }, 150);
                    }
                });
            }
        },

// ***************************************************************************** Prototypes
        
// 14. Prototypes

        prototypes = {
// Actions                  
            doHiddenBuster:function(item){
            /*
             * Make all parents & current item visible.
             * 
             * @param item (element): item for which all parens are going to be made visible
             * 
             * @return list of parents
             */
                var parent = item.parent(),
                items = new Array();

                if (item.prop('tagName') !== undefined 
                        && item.prop('tagName').toLowerCase() !== 'body'){
                    items = prototypes.doHiddenBuster(parent);
                }

                if (item.css('display') === 'none'){
                    item.css('display', 'block');
                    items.push(item);
                }

                return items;
            },
            undoHiddenBuster:function(items){
            /*
             * Hide all items from list. The list is returned by function doHiddenBuster().
             * 
             * @param items (Array): list of items to be hidden
             */    
                var i;

                for (i=0; i<items.length; i++){
                    items[i].css('display', 'none');
                }
            },
            openLink:function(url,
                              target){
            /*
             * Open a link.
             * 
             * @param url (String): link URL
             * @param target (String): link target (_blank, _parent, _self, _top)
             */
                switch (target.toLowerCase()){
                    case '_blank':
                        window.open(url);
                        break;
                    case '_parent':
                        parent.location.href = url;
                        break;
                    case '_top':
                        top.location.href = url;
                        break;
                    default:    
                        window.location = url;
                }
            },
            randomizeArray:function(theArray){
            /*
             * Randomize the items of an array.
             * 
             * @param theArray (Array): the array to be mixed
             * 
             * return array with mixed items
             */
                theArray.sort(function(){
                    return 0.5-Math.random();
                });
                return theArray;
            },
            scrollToY:function(position,
                               speed){
            /*
             * Scroll vertically to position.
             * 
             * @param position (Number): position to scroll to
             * @param speed (Number): scroll speed 
             */  
                speed = speed !== undefined ? speed: 300;

                $('html').stop(true, true)
                         .animate({'scrollTop': position}, 
                                  speed);
                $('body').stop(true, true)
                         .animate({'scrollTop': position}, 
                                  speed);
            },
            touchNavigation:function(parent,
                                     child){
            /*
             * One finger navigation for touchscreen devices.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             */
                var prevX, 
                prevY, 
                currX, 
                currY, 
                touch, 
                childX, 
                childY;

                parent.bind('touchstart', function(e){
                    touch = e.originalEvent.touches[0];
                    prevX = touch.clientX;
                    prevY = touch.clientY;
                });

                parent.bind('touchmove', function(e){                                
                    touch = e.originalEvent.touches[0];
                    currX = touch.clientX;
                    currY = touch.clientY;
                    childX = currX>prevX ? parseInt(child.css('margin-left'))+(currX-prevX):parseInt(child.css('margin-left'))-(prevX-currX);
                    childY = currY>prevY ? parseInt(child.css('margin-top'))+(currY-prevY):parseInt(child.css('margin-top'))-(prevY-currY);

                    if (childX < (-1)*(child.width()-parent.width())){
                        childX = (-1)*(child.width()-parent.width());
                    }
                    else if (childX > 0){
                        childX = 0;
                    }
                    else{                                    
                        e.preventDefault();
                    }

                    if (childY < (-1)*(child.height()-parent.height())){
                        childY = (-1)*(child.height()-parent.height());
                    }
                    else if (childY > 0){
                        childY = 0;
                    }
                    else{                                    
                        e.preventDefault();
                    }

                    prevX = currX;
                    prevY = currY;

                    if (parent.width() < child.width()){
                        child.css('margin-left', childX);
                    }

                    if (parent.height() < child.height()){
                        child.css('margin-top', childY);
                    }
                });

                parent.bind('touchstart', function(e){
                    if (!prototypes.isChromeMobileBrowser()){
                        e.preventDefault();
                    }
                });
            },

// Browsers & devices
            isAndroid:function(){
            /*
             * Check if operating system is Android.
             * 
             * @return true/false
             */
                var isAndroid = false,
                agent = navigator.userAgent.toLowerCase();

                if (agent.indexOf('android') !== -1){
                    isAndroid = true;
                }
                return isAndroid;
            },
            isChromeMobileBrowser:function(){
            /*
             * Check if browser is Chrome on mobile..
             * 
             * @return true/false
             */
                var isChromeMobile = false,
                agent = navigator.userAgent.toLowerCase();

                if ((agent.indexOf('chrome') !== -1 
                                || agent.indexOf('crios') !== -1) 
                        && prototypes.isTouchDevice()){
                    isChromeMobile = true;
                }
                return isChromeMobile;
            },
            isIE8Browser:function(){
            /*
             * Check if browser is IE8.
             * 
             * @return true/false
             */
                var isIE8 = false,
                agent = navigator.userAgent.toLowerCase();

                if (agent.indexOf('msie 8') !== -1){
                    isIE8 = true;
                }
                return isIE8;
            },
            isIEBrowser:function(){
            /*
             * Check if browser is IE..
             * 
             * @return true/false
             */
                var isIE = false,
                agent = navigator.userAgent.toLowerCase();

                if (agent.indexOf('msie') !== -1){
                    isIE = true;
                }
                return isIE;
            },
            isTouchDevice:function(){
            /*
             * Detect touchscreen devices.
             * 
             * @return true/false
             */
                var os = navigator.platform;

                if (os.toLowerCase().indexOf('win') !== -1){
                    return window.navigator.msMaxTouchPoints;
                }
                else {
                    return 'ontouchstart' in document;
                }
            },

// Cookies
            deleteCookie:function(name,
                                  path,
                                  domain){
            /*
             * Delete cookie.
             * 
             * @param name (String): cookie name
             * @param path (String): cookie path
             * @param domain (String): cookie domain
             */
                if (prototypes.getCookie(name)){
                    document.cookie = name+'='+((path) ? ';path='+path:'')+((domain) ? ';domain='+domain:'')+';expires=Thu, 01-Jan-1970 00:00:01 GMT';
                }
            },
            getCookie:function(name){
            /*
             * Get cookie.
             * 
             * @param name (String): cookie name
             */    
                var namePiece = name+"=",
                cookie = document.cookie.split(";"),
                i;

                for (i=0; i<cookie.length; i++){
                    var cookiePiece = cookie[i];

                    while (cookiePiece.charAt(0) === ' '){
                        cookiePiece = cookiePiece.substring(1, cookiePiece.length);            
                    } 

                    if (cookiePiece.indexOf(namePiece) === 0){
                        return unescape(cookiePiece.substring(namePiece.length, cookiePiece.length));
                    } 
                }
                return null;
            },
            setCookie:function(name,
                               value,
                               expire){
            /*
             * Set cookie.
             * 
             * @param name (String): cookie name
             * @param value (String): cookie value
             * @param expire (String): the number of days after which the cookie will expire
             */
                var expirationDate = new Date();

                expirationDate.setDate(expirationDate.getDate()+expire);
                document.cookie = name+'='+escape(value)+((expire === null) ? '': ';expires='+expirationDate.toUTCString())+';javahere=yes;path=/';
            },

// Date & time          
            getAMPM:function(time){
            /*
             * Converts time to AM/PM format.
             *
             * @param time (String): the time that will be converted (HH:MM)
             *
             * @return time to AM/PM format
             */
                var hour = parseInt(time.split(':')[0], 10),
                minutes = time.split(':')[1],
                result = '';

                if (hour === 0){
                    result = '12';
                }
                else if (hour > 12){
                    result = prototypes.getLeadingZero(hour-12);
                }
                else{
                    result = prototypes.getLeadingZero(hour);
                }

                result += ':'+minutes+' '+(hour < 12 ? 'AM':'PM');

                return result;
            },
            getDatesDifference:function(date1,
                                        date2,
                                        type,
                                        valueType,
                                        noDecimals){
            /*
             * Returns difference between 2 dates.
             * 
             * @param date1 (Date): first date (YYYY-MM-DD)
             * @param date2 (Date): second date (YYYY-MM-DD)
             * @param type (String): diference type
             *                       "seconds"
             *                       "minutes"
             *                       "hours"
             *                       "days"
             * @param valueType (String): type of number returned
             *                            "float"
             *                            "integer"
             * @param noDecimals (Number): number of decimals returned with the float value (-1 to display all decimals)
             * 
             * @return dates diference
             */
                var y1 = date1.split('-')[0],
                m1 = date1.split('-')[1],
                d1 = date1.split('-')[2],
                y2 = date2.split('-')[0],
                m2 = date2.split('-')[1],
                d2 = date2.split('-')[2],
                time1 = (new Date(y1, m1-1, d1)).getTime(),
                time2 = (new Date(y2, m2-1, d2)).getTime(),
                diff = Math.abs(time1-time2);
        
                if (type === undefined){
                    type = 'seconds';
                }
                
                if (valueType === undefined){
                    valueType = 'float';
                }
                
                if (noDecimals === undefined){
                    noDecimals = -1;
                }
                
                switch (type){
                    case 'days':
                        diff = diff/(1000*60*60*24);
                        break;
                    case 'hours':
                        diff = diff/(1000*60*60);
                        break;
                    case 'minutes':
                        diff = diff/(1000*60);
                        break;
                    default:
                        diff = diff/(1000);
                }
                
                if (valueType === 'float'){
                    return noDecimals === -1 ? diff:prototypes.getWithDecimals(diff, noDecimals);
                }
                else{
                    return Math.ceil(diff);
                }
            },
            getHoursDifference:function(hour1,
                                        hour2,
                                        type,
                                        valueType,
                                        noDecimals){
            /*
             * Returns difference between 2 hours.
             * 
             * @param hour1 (Date): first hour (HH:MM, HH:MM:SS)
             * @param hour2 (Date): second hour (HH:MM, HH:MM:SS)
             * @param type (String): diference type
             *                       "seconds"
             *                       "minutes"
             *                       "hours"
             * @param valueType (String): type of number returned
             *                            "float"
             *                            "integer"
             * @param noDecimals (Number): number of decimals returned with the float value (-1 to display all decimals)
             * 
             * @return hours difference
             */
                var hours1 = parseInt(hour1.split(':')[0], 10),
                minutes1 = parseInt(hour1.split(':')[1], 10),
                seconds1 = hour1.split(':')[2] !== undefined ? parseInt(hour1.split(':')[2], 10):0,
                hours2 = parseInt(hour2.split(':')[0], 10),
                minutes2 = parseInt(hour2.split(':')[1], 10),
                seconds2 = hour2.split(':')[2] !== undefined ? parseInt(hour2.split(':')[2], 10):0,
                time1,
                time2,
                diff;
        
                if (type === undefined){
                    type = 'seconds';
                }
                
                if (valueType === undefined){
                    valueType = 'float';
                }
                
                if (noDecimals === undefined){
                    noDecimals = -1;
                }
                
                switch (type){
                    case 'hours':
                        time1 = hours1+minutes1/60+seconds1/60/60;
                        time2 = hours2+minutes2/60+seconds2/60/60;
                        break;
                    case 'minutes':
                        time1 = hours1*60+minutes1+seconds1/60;
                        time2 = hours2*60+minutes2+seconds2/60;
                        break;
                    default:
                        time1 = hours1*60*60+minutes1*60+seconds1;
                        time2 = hours2*60*60+minutes2*60+seconds2;
                }
                
                diff = Math.abs(time1-time2);
                
                if (valueType === 'float'){
                    return noDecimals === -1 ? diff:prototypes.getWithDecimals(diff, noDecimals);
                }
                else{
                    return Math.ceil(diff);
                }
            },
            getNextDay:function(date){
            /*
             * Returns next day.
             * 
             * @param date (Date): current date (YYYY-MM-DD)
             * 
             * @return next day (YYYY-MM-DD)
             */
                var nextDay = new Date(),
                parts = date.split('-');

                nextDay.setFullYear(parts[0], parts[1], parts[2]);
                nextDay.setTime(nextDay.getTime()+86400000);

                return nextDay.getFullYear()+'-'+prototypes.getLeadingZero(nextDay.getMonth())+'-'+prototypes.getLeadingZero(nextDay.getDate());
            },
            getNoDays:function(date1,
                               date2){
            /*
             * Returns number of days between 2 dates.
             * 
             * @param date1 (Date): first date (YYYY-MM-DD)
             * @param date2 (Date): second date (YYYY-MM-DD)
             * 
             * @return number of days
             */
                var y1 = date1.split('-')[0],
                m1 = date1.split('-')[1],
                d1 = date1.split('-')[2],
                y2 = date2.split('-')[0],
                m2 = date2.split('-')[1],
                d2 = date2.split('-')[2],
                time1 = (new Date(y1, m1-1, d1)).getTime(),
                time2 = (new Date(y2, m2-1, d2)).getTime(),
                diff = Math.abs(time1-time2);
        
                return Math.round(diff/(1000*60*60*24))+1;
            },
            getPrevDay:function(date){
            /*
             * Returns previous day.
             * 
             * @param date (Date): current date (YYYY-MM-DD)
             * 
             * @return previous day (YYYY-MM-DD)
             */
                var previousDay = new Date(),
                parts = date.split('-');

                previousDay.setFullYear(parts[0],
                                        parseInt(parts[1])-1, 
                                        parts[2]);
                previousDay.setTime(previousDay.getTime()-86400000);

                return previousDay.getFullYear()+'-'+prototypes.getLeadingZero(previousDay.getMonth()+1)+'-'+prototypes.getLeadingZero(previousDay.getDate());                        
            },
            getPrevTime:function(time,
                                 diff,
                                 diffBy){
            /*
             * Returns previous time by hours, minutes, seconds.
             * 
             * @param time (String): time (HH, HH:MM, HH:MM:SS)
             * @param diff (Number): diference for previous time
             * @param diffBy (Number): diference by 
             *                         "hours"
             *                         "minutes"
             *                         "seconds"
             * 
             * @return previus hour (HH, HH:MM, HH:MM:SS)
             */
                var timePieces = time.split(':'),
                hours = parseInt(timePieces[0], 10),
                minutes = timePieces[1] === undefined ? 0:parseInt(timePieces[1], 10),
                seconds = timePieces[2] === undefined ? 0:parseInt(timePieces[2], 10);

                switch (diffBy){
                    case 'seconds':
                        seconds = seconds-diff;

                        if (seconds < 0){
                            seconds = 60+seconds;
                            minutes = minutes-1;

                            if (minutes < 0){
                                minutes = 60+minutes;
                                hours = hours-1 < 0 ? 0:hours-1;
                            }
                        }
                        break;
                    case 'minutes':
                            minutes = minutes-diff;

                            if (minutes < 0){
                                minutes = 60+minutes;
                                hours = hours-1 < 0 ? 0:hours-1;
                            }
                        break;
                    default:
                        hours = hours-diff < 0 ? 0:hours-diff;
                }

                return prototypes.getLeadingZero(hours)+(timePieces[1] === undefined ? '':':'+prototypes.getLeadingZero(minutes)+(timePieces[2] === undefined ? '':':'+prototypes.getLeadingZero(seconds)));
            },
            getToday:function(){
            /*
             * Returns today date.
             * 
             * @return today (YYYY-MM-DD)
             */    
                var today = new Date();
              
                return today.getFullYear()+'-'+prototypes.getLeadingZero(today.getMonth()+1)+'-'+prototypes.getLeadingZero(today.getDate());
            },
            getWeekDay:function(date){
            /*
             * Returns week day.
             * 
             * @param date (String): date for which the function get day of the week (YYYY-MM-DD)
             * 
             * @return week day index (0 for Sunday)
             */    
                var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                year = date.split('-')[0],
                month = date.split('-')[1],
                day = date.split('-')[2],
                newDate = new Date(eval('"'+day+' '+months[parseInt(month, 10)-1]+', '+year+'"'));

                return newDate.getDay();
            },

// Domains & URLs                        
            $_GET:function(name){
            /*
             * Parse a $_GET variable.
             * 
             * @param name (String): variable name
             * 
             * @return variable vaue or "undefined" if it doesn't exist
             */
                var url = window.location.href.split('?')[1],
                variables = url !== undefined ? url.split('&'):[],
                i; 

                for (i=0; i<variables.length; i++){
                    if (variables[i].indexOf(name) !== -1){
                        return variables[i].split('=')[1];
                        break;
                    }
                }

                return undefined;
            },
            acaoBuster:function(url){
            /*
             * Access-Control-Allow-Origin buster. Modifies URL to be the same as browser URL.
             * 
             * @param url (String): URL
             * 
             * @return modified URL
             */
                var browserURL = window.location.href,
                pathPiece1 = '', pathPiece2 = '';

                if (prototypes.getDomain(browserURL) === prototypes.getDomain(url)){
                    if (url.indexOf('https') !== -1 
                            || url.indexOf('http') !== -1){
                        if (browserURL.indexOf('http://www.') !== -1){
                            pathPiece1 = 'http://www.';
                        }
                        else if (browserURL.indexOf('http://') !== -1){
                            pathPiece1 = 'http://';
                        }
                        else if (browserURL.indexOf('https://www.') !== -1){
                            pathPiece1 = 'https://www.';
                        }
                        else if (browserURL.indexOf('https://') !== -1){
                            pathPiece1 = 'https://';
                        }

                        if (url.indexOf('http://www.') !== -1){
                            pathPiece2 = url.split('http://www.')[1];
                        }
                        else if (url.indexOf('http://') !== -1){
                            pathPiece2 = url.split('http://')[1];
                        }
                        else if (url.indexOf('https://www.') !== -1){
                            pathPiece2 = url.split('https://www.')[1];
                        }
                        else if (url.indexOf('https://') !== -1){
                            pathPiece2 = url.split('https://')[1];
                        }

                        return pathPiece1+pathPiece2;
                    }
                    else{
                        return url;
                    }
                }
                else{
                    return url;
                }
            },
            getDomain:function(url){
            /*
             * Get current domain.
             *
             * @param url (String): the URL from which the domain will be extracted
             *
             * @return current domain
             */ 
                var domain = url;

                /*
                 * Remove white spaces from the begining of the URL.
                 */
                domain = domain.replace(new RegExp(/^\s+/),"");

                /*
                 * Remove white spaces from the end of the URL.
                 */
                domain = domain.replace(new RegExp(/\s+$/),"");

                /*
                 * If found , convert back slashes to forward slashes.
                 */
                domain = domain.replace(new RegExp(/\\/g),"/");

                /*
                 * If there, removes "http://", "https://" or "ftp://" from the begining.
                 */
                domain = domain.replace(new RegExp(/^http\:\/\/|^https\:\/\/|^ftp\:\/\//i),"");

                /*
                 * If there, removes 'www.' from the begining.
                 */
                domain = domain.replace(new RegExp(/^www\./i),"");

                /*
                 * Remove complete string from first forward slash on.
                 */
                domain = domain.replace(new RegExp(/\/(.*)/),"");

                return domain;
            },
            hasSubdomain:function(url){
            /*
             * Check if current URL has a subdomain.
             *
             * @param url (String): URL that will be checked
             *
             * @return true/false
             */ 
                var subdomain;

                /*
                 * Remove white spaces from the begining of the URL.
                 */
                url = url.replace(new RegExp(/^\s+/),"");

                /*
                 * Remove white spaces from the end of the URL.
                 */
                url = url.replace(new RegExp(/\s+$/),"");

                /*
                 * If found , convert back slashes to forward slashes.
                 */
                url = url.replace(new RegExp(/\\/g),"/");

                /*
                 * If there, removes 'http://', 'https://' or 'ftp://' from the begining.
                 */
                url = url.replace(new RegExp(/^http\:\/\/|^https\:\/\/|^ftp\:\/\//i),"");

                /*
                 * If there, removes 'www.' from the begining.
                 */
                url = url.replace(new RegExp(/^www\./i),"");

                /*
                 * Remove complete string from first forward slaash on.
                 */
                url = url.replace(new RegExp(/\/(.*)/),""); // 

                if (url.match(new RegExp(/\.[a-z]{2,3}\.[a-z]{2}$/i))){
                    /*
                     * Remove ".??.??" or ".???.??" from end - e.g. ".CO.UK", ".COM.AU"
                     */
                    url = url.replace(new RegExp(/\.[a-z]{2,3}\.[a-z]{2}$/i),"");
                }
                else if (url.match(new RegExp(/\.[a-z]{2,4}$/i))){
                    /*
                     * Removes ".??" or ".???" or ".????" from end - e.g. ".US", ".COM", ".INFO"
                     */
                    url = url.replace(new RegExp(/\.[a-z]{2,4}$/i),"");
                }

                /*
                 * Check to see if there is a dot "." left in the string.
                 */
                subdomain = (url.match(new RegExp(/\./g))) ? true : false;

                return(subdomain);
            },

// Resize & position                        
            rp:function(parent,
                        child,
                        pw,
                        ph,
                        cw,
                        ch,
                        pos,
                        type){
            /*
             * Resize & position an item inside a parent.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): parent width
             * @param ph (Number): parent height
             * @param cw (Number): child width
             * @param ch (Number): child height
             * @param pos (String): set child position in parent (bottom, bottom-center, bottom-left, bottom-right, center, left, horizontal-center, middle-left, middle-right, right, top, top-center, top-left, top-right, vertical-center)
             * @param type (String): resize type
             *                       "fill" fill parent (child will be cropped)
             *                       "fit" child resize to fit in parent
             */
                var newW = 0,
                newH = 0;

                /*
                 * Resize child.
                 */
                if (cw <= pw 
                        && ch <= ph){
                    newW = cw;
                    newH = ch;
                }
                else{
                    switch (type){
                        case 'fill':
                            newH = ph;
                            newW = (cw*ph)/ch;

                            if (newW < pw){
                                newW = pw;
                                newH = (ch*pw)/cw;
                            }
                            break;
                        default:
                            newH = ph;
                            newW = (cw*ph)/ch;

                            if (newW > pw){
                                newW = pw;
                                newH = (ch*pw)/cw;
                            }
                            break;
                    }
                }

                child.width(newW);
                child.height(newH);

                /*
                 * Position child.
                 */
                switch(pos.toLowerCase()){
                    case 'bottom':
                        prototypes.rpBottom(parent,
                                            child, 
                                            ph);
                        break;
                    case 'bottom-center':
                        prototypes.rpBottomCenter(parent, 
                                                  child, 
                                                  pw, 
                                                  ph);
                        break;
                    case 'bottom-left':
                        prototypes.rpBottomLeft(parent, 
                                                child, 
                                                pw, 
                                                ph);
                        break;
                    case 'bottom-right':
                        prototypes.rpBottomRight(parent,
                                                 child, 
                                                 pw, 
                                                 ph);
                        break;
                    case 'center':
                        prototypes.rpCenter(parent, 
                                            child, 
                                            pw, 
                                            ph);
                        break;
                    case 'left':
                        prototypes.rpLeft(parent, 
                                          child, 
                                          pw);
                        break;
                    case 'horizontal-center':
                        prototypes.rpCenterHorizontally(parent, 
                                                        child, 
                                                        pw);
                        break;
                    case 'middle-left':
                        prototypes.rpMiddleLeft(parent, 
                                                child, 
                                                pw, 
                                                ph);
                        break;
                    case 'middle-right':
                        prototypes.rpMiddleRight(parent, 
                                                 child, 
                                                 pw, 
                                                 ph);
                        break;
                    case 'right':
                        prototypes.rpRight(parent, 
                                           child, 
                                           pw);
                        break;
                    case 'top':
                        prototypes.rpTop(parent, 
                                         child, 
                                         ph);
                        break;
                    case 'top-center':
                        prototypes.rpTopCenter(parent, 
                                               child, 
                                               pw, 
                                               ph);
                        break;
                    case 'top-left':
                        prototypes.rpTopLeft(parent, 
                                             child, 
                                             pw, 
                                             ph);
                        break;
                    case 'top-right':
                        prototypes.rpTopRight(parent, 
                                              child,
                                              pw, 
                                              ph);
                        break;
                    case 'vertical-center':
                        prototypes.rpCenterVertically(parent, 
                                                      child, 
                                                      ph);
                        break;
                }
            },
            rpBottom:function(parent,
                              child,
                              ph){
            /*
             * Position item on bottom.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param ph (Number): height to which the parent is going to be set
             */
                if (ph !== undefined){
                    parent.height(ph);
                }
                child.css('margin-top', parent.height()-child.height());
            },
            rpBottomCenter:function(parent,
                                    child,
                                    pw,
                                    ph){
            /*
             * Position item on bottom-left.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpBottom(parent, 
                                    child, 
                                    ph);
                prototypes.rpCenterHorizontally(parent, 
                                                child, 
                                                pw);
            },
            rpBottomLeft:function(parent,
                                  child,
                                  pw,
                                  ph){
            /*
             * Position item on bottom-left.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpBottom(parent, 
                                    child, 
                                    ph);
                prototypes.rpLeft(parent, 
                                  child, 
                                  pw);
            },
            rpBottomRight:function(parent,
                                   child,
                                   pw,
                                   ph){
            /*
             * Position item on bottom-left.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpBottom(parent, 
                                    child, 
                                    ph);
                prototypes.rpRight(parent, 
                                   child, 
                                   pw);
            },
            rpCenter:function(parent,
                              child,
                              pw,
                              ph){
            /*
             * Position item on center.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpCenterHorizontally(parent, 
                                                child, 
                                                pw);
                prototypes.rpCenterVertically(parent, 
                                              child, 
                                              ph);
            },
            rpCenterHorizontally:function(parent,
                                          child,
                                          pw){
            /*
             * Center item horizontally.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             */
                if (pw !== undefined){
                    parent.width(pw);
                }
                child.css('margin-left', (parent.width()-child.width())/2);
            },
            rpCenterVertically:function(parent,
                                        child,
                                        ph){
            /*
             * Center item vertically.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param ph (Number): height to which the parent is going to be set
             */
                if (ph !== undefined){
                    parent.height(ph);
                }
                child.css('margin-top', (parent.height()-child.height())/2);
            },
            rpLeft:function(parent,
                            child,
                            pw){
            /*
             * Position item on left.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             */
                if (pw !== undefined){
                    parent.width(pw);
                }
                child.css('margin-left', 0);
            },
            rpMiddleLeft:function(parent,
                                  child,
                                  pw,
                                  ph){
            /*
             * Position item on middle-left.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpCenterVertically(parent,
                                              child, 
                                              ph);
                prototypes.rpLeft(parent,
                                  child, 
                                  pw);
            },
            rpMiddleRight:function(parent,
                                   child,
                                   pw,
                                   ph){
            /*
             * Position item on middle-right.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpCenterVertically(parent,
                                              child, 
                                              ph);
                prototypes.rpRight(parent, 
                                   child, 
                                   pw);
            },
            rpRight:function(parent,
                             child,
                             pw){
            /*
             * Position item on right.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             */
                if (pw !== undefined){
                    parent.width(pw);
                }
                child.css('margin-left', parent.width()-child.width());
            },
            rpTop:function(parent,
                           child,
                           ph){
            /*
             * Position item on top.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param ph (Number): height to which the parent is going to be set
             */
                if (ph !== undefined){
                    parent.height(ph);
                }
                child.css('margin-top', 0);
            },
            rpTopCenter:function(parent,
                                 child,
                                 pw,
                                 ph){
            /*
             * Position item on top-center.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpTop(parent, 
                                 child, 
                                 ph);
                prototypes.rpCenterHorizontally(parent, 
                                                child, 
                                                pw);
            },
            rpTopLeft:function(parent,
                               child,
                               pw,
                               ph){
            /*
             * Position item on top-left.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpTop(parent, 
                                 child, 
                                 ph);
                prototypes.rpLeft(parent, 
                                  child, 
                                  pw);
            },
            rpTopRight:function(parent,
                                child,
                                pw,
                                ph){
            /*
             * Position item on top-right.
             * 
             * @param parent (element): parent item
             * @param child (element): child item
             * @param pw (Number): width to which the parent is going to be set
             * @param ph (Number): height to which the parent is going to be set
             */
                prototypes.rpTop(parent, 
                                 child, 
                                 ph);
                prototypes.rpRight(parent, 
                                   child, 
                                   pw);
            },

// Strings & numbers
            cleanInput:function(input,
                                allowedCharacters,
                                firstNotAllowed,
                                min){
            /*
             * Clean an input from unwanted characters.
             * 
             * @param input (element): the input to be checked
             * @param allowedCharacters (String): the string of allowed characters
             * @param firstNotAllowed (String): the character which can't be on the first position
             * @param min (Number/String): the minimum value that is allowed in the input
             * 
             * @return clean string
             */ 
                var characters = input.val().split(''),
                returnStr = '', 
                i, 
                startIndex = 0;

                /*
                 * Check first character.
                 */
                if (characters.length > 1
                        && characters[0] === firstNotAllowed){
                    startIndex = 1;
                }

                /*
                 * Check characters.
                 */
                for (i=startIndex; i<characters.length; i++){
                    if (allowedCharacters.indexOf(characters[i]) !== -1){
                        returnStr += characters[i];
                    }
                }

                /*
                 * Check the minimum value.
                 */
                if (min > returnStr){
                    returnStr = min;
                }

                input.val(returnStr);
            },
            getLeadingZero:function(no){
            /*
             * Adds a leading 0 if number smaller than 10.
             * 
             * @param no (Number): the number
             * 
             * @return number with leading 0 if needed
             */
                if (no < 10){
                    return '0'+no;
                }
                else{
                    return no;
                }
            },
            getRandomString:function(stringLength,
                                     allowedCharacters){
            /*
             * Creates a string with random characters.
             * 
             * @param stringLength (Number): the length of the returned string
             * @param allowedCharacters (String): the string of allowed characters
             * 
             * @return random string
             */
                var randomString = '',
                charactersPosition,
                i;

                allowedCharacters = allowedCharacters !== undefined ? allowedCharacters:'0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';

                for (i=0; i<stringLength; i++){
                    charactersPosition = Math.floor(Math.random()*allowedCharacters.length);
                    randomString += allowedCharacters.substring(charactersPosition, charactersPosition+1);
                }
                return randomString;
            },
            getShortString:function(str,
                                    size){
            /*
             * Returns a part of a string followed by 3 dots.
             * 
             * @param str (String): the string
             * @param size (Number): the number of characters that will be displayed minus 3 dots
             * 
             * @return short string ...
             */
                var newStr = new Array(),
                pieces = str.split(''), i;

                if (pieces.length <= size){
                    newStr.push(str);
                }
                else{
                    for (i=0; i<size-3; i++){
                        newStr.push(pieces[i]);
                    }
                    newStr.push('...');
                }

                return newStr.join('');
            },
            getWithDecimals:function(number,
                                     no){
            /*
             * Returns a number with a predefined number of decimals.
             * 
             * @param number (Number): the number
             * @param no (Number): the number of decimals
             * 
             * @return string with number and decimals
             */
                no = no === undefined ? 2:no;
                return parseInt(number) === number ? String(number):parseFloat(number).toFixed(no);
            },
            validateCharacters:function(str,
                                        allowedCharacters){
            /*
             * Verify if a string contains allowed characters.
             * 
             * @param str (String): string to be checked
             * @param allowedCharacters (String): the string of allowed characters
             * 
             * @return true/false
             */
                var characters = str.split(''), i;

                for (i=0; i<characters.length; i++){
                    if (allowedCharacters.indexOf(characters[i]) === -1){
                        return false;
                    }
                }
                return true;
            },
            validEmail:function(email){
            /*
             * Email validation.
             * 
             * @param email (String): email to be checked
             * 
             * @return true/false
             */
                var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

                if (filter.test(email)){
                    return true;
                }
                return false;
            },
            stripSlashes:function(str){
            /*
             * Remove slashes from string.
             * 
             * @param str (String): the string
             * 
             * @return string without slashes
             */
                return (str + '').replace(/\\(.?)/g, function (s, n1){
                    switch (n1){
                        case '\\':
                            return '\\';
                        case '0':
                            return '\u0000';
                        case '':
                            return '';
                        default:
                            return n1;
                    }
                });
            },

// Styles
            getHEXfromRGB:function(rgb){
            /*
             * Convert RGB color to HEX.
             * 
             * @param rgb (String): RGB color
             * 
             * @return color HEX
             */
                var hexDigits = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');

                rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

                return (isNaN(rgb[1]) ? '00':hexDigits[(rgb[1]-rgb[1]%16)/16]+hexDigits[rgb[1]%16])+
                       (isNaN(rgb[2]) ? '00':hexDigits[(rgb[2]-rgb[2]%16)/16]+hexDigits[rgb[2]%16])+
                       (isNaN(rgb[3]) ? '00':hexDigits[(rgb[3]-rgb[3]%16)/16]+hexDigits[rgb[3]%16]);
            },
            getIdealTextColor:function(bgColor){
            /*
             * Set text color depending on the background color.
             * 
             * @param bgColor(String): background color
             * 
             * return white/black
             */
                var rgb = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(bgColor);

                if (rgb !== null){
                    return parseInt(rgb[1], 10)+parseInt(rgb[2], 10)+parseInt(rgb[3], 10) < 3*256/2 ? 'white' : 'black';
                }
                else{
                    return parseInt(bgColor.substring(0, 2), 16)+parseInt(bgColor.substring(2, 4), 16)+parseInt(bgColor.substring(4, 6), 16) < 3*256/2 ? 'white' : 'black';
                }
            }
        };
        
        return methods.init.apply(this);
    };
})(jQuery);

function DOPBSPSearchWidgetLoadInfobox(){
    var script = document.createElement('script');
                    
    script.type = 'text/JavaScript';
    script.src = 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js';
    
    jQuery('body').append(script);
    
    return true;
}