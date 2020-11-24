(function ($, AE, Views, Models, Collections) {
    Views.PostForm = Views.SubmitPost.extend({
        onAfterInit: function () {
            var view = this;
            view.carousels = new Views.Carousel({
                el: $('#gallery_container'),
                model: view.model,
                name_item: 'et_carousel',
                uploaderID: 'carousel',
            });


            // Serve Time
            var serve_time = view.model.get('serve_time');
            if (!$.isEmptyObject(serve_time)) {
                this.$('ul.date-list li.bdate').each(function () {
                    var name = $(this).data('name');
                    object = serve_time[name],
                        open_time = object.open_time,
                        close_time = object.close_time,
                        open_time_2 = object.open_time_2,
                        close_time_2 = object.close_time_2;

                    if ((open_time != "" && close_time != "") || (open_time_2 != "" && close_time_2 != "")) {
                        $(this).addClass('vbdate');
                        $(this).next().addClass('nbdate');
                        $(this).attr('data-original-title', open_time + ' to ' + close_time + ' to ' + open_time_2 + ' to ' + close_time_2);
                        $(this).attr('open-time', open_time);
                        $(this).attr('close-time', close_time);

                        $(this).attr('open-time-2', open_time_2);
                        $(this).attr('close-time-2', close_time_2);
                    } else {
                        $(this).removeClass('vbdate');
                        $(this).next().removeClass('nbdate');
                        $(this).attr('data-original-title', "");
                        $(this).attr('open-time', "");
                        $(this).attr('close-time', "");

                        $(this).attr('open-time-2', "");
                        $(this).attr('close-time-2', "");
                    }
                });
            } else {
                this.$('ul.date-list li.bdate').each(function () {
                    $(this).removeClass('vbdate');
                    $(this).next().removeClass('nbdate');
                    $(this).attr('data-original-title', "");
                    $(this).attr('open-time', "");
                    $(this).attr('close-time', "");

                    $(this).attr('open-time-2', "");
                    $(this).attr('close-time-2', "");
                });
            }
        },
        onBeforeShowNextStep: function (event) {
            var view = this;
            if (parseInt(this.use_plan) === 1 && this.user_login) {
                view.$('.step-wrapper .step-content-wrapper').removeClass('content');
            }
        },
        onLimitFree: function () {
            AE.pubsub.trigger('ae:notification', {
                msg: ae_globals.limit_free_msg,
                notice_type: 'error',
            });
        },
        onAfterShowNextStep: function (step) {
            $('.step-heading').find('i.fa-caret-down').removeClass('fa-caret-right fa-caret-down').addClass('fa-caret-right');
            $('.step-' + step).find('.step-heading i.fa-caret-right').removeClass('fa-caret-right').addClass('fa-caret-down');
        },
        onAfterSelectStep: function (step) {
            $('.step-heading').find('i').removeClass('fa-caret-right fa-caret-down').addClass('fa-caret-right');
            step.find('i').removeClass('fa-caret-right').addClass('fa-caret-down');
        },
        // on after Submit auth fail
        onAfterAuthFail: function (model, res) {
            AE.pubsub.trigger('ae:notification', {
                msg: res.msg,
                notice_type: 'error',
            });
        },
        onAfterPostFail: function (model, res) {
            AE.pubsub.trigger('ae:notification', {
                msg: res.msg,
                notice_type: 'error',
            });
        },
        onAfterSelectPlan: function ($step, $li) {
            var label = $li.attr('data-label');
            $step.find('.text-heading-step').html(label);
        },
        // trigger set serve time
        onBeforeSubmitPost: function (model, view) {
            var li = $('ul.date-list li.bdate');

            if (view.model.get("serve_time")) {
                model.unset("serve_time");
            }

            $(li).each(function () {

                var name = $(this).data('name');

                open_time = $(this).attr('open-time');
                close_time = $(this).attr('close-time');

                open_time_2 = $(this).attr('open-time-2');
                close_time_2 = $(this).attr('close-time-2');

                view.model.set('serve_time[' + name + '][open_time]', open_time);
                view.model.set('serve_time[' + name + '][close_time]', close_time);
                view.model.set('serve_time[' + name + '][open_time_2]', open_time_2);
                view.model.set('serve_time[' + name + '][close_time_2]', close_time_2);
            });

        },
        onAfterShowNextStep: function (next, viewstep) {
            // Scroll to ID when submit post
            if (next == 'payment') {
                $("html, body").animate({scrollTop: $('#step-post').offset().top - 150}, 1000);
            }
        }
    });

    Views.Post_Place = Backbone.View.extend({
        el: 'body',
        initialize: function () {
            $('.open-time').timepicker({
                'timeFormat': "H:i",
                'appendTo': '.container-open-time',
                'minTime': '0',
                'maxTime': '14',
                'step': 60,
                'lang': {'am': 'AM', 'pm': 'PM'},
                'noneOption': [{'label': '--', 'value': ''}]
            });

            $('.close-time').timepicker({
                'timeFormat': "H:i",
                'appendTo': '.container-close-time',
                'minTime': '0',
                'maxTime': '14',
                'step': 60,
                'lang': {'am': ' AM', 'pm': ' PM'},
                'noneOption': [{'label': '--', 'value': ''}]
            });

            $('#datepair-time-one').datepair();

            $('.open-time-2').timepicker({
                'timeFormat': "H:i",
                'ignoreReadonly': true,
                'allowInputToggle': true,
                'appendTo': '.container-open-time-2',
                'minTime': '14',
                'maxTime': '0',
                'step': 60,
                'lang': {'am': 'AM', 'pm': 'PM'},
                'noneOption': [{'label': '--', 'value': ''}]
            });
            $('.close-time-2').timepicker({
                'timeFormat': "H:i",
                'ignoreReadonly': true,
                'allowInputToggle': true,
                'appendTo': '.container-close-time-2',
                'minTime': '14',
                'maxTime': '0',
                'step': 60,
                'lang': {'am': ' AM', 'pm': ' PM'},
                'noneOption': [{'label': '--', 'value': ''}]
            });

            $('#datepair-time-two').datepair();


            $('li.bdate[data-toggle="tooltip"]').tooltip({'html': true});
            $('li.bdate').each(function (index) {
                $(this).attr('open-time', '');
                $(this).attr('close-time', '');
                $(this).attr('open-time-2', '');
                $(this).attr('close-time-2', '');
            });
        },
        events: {
            'change #et_claimable': 'setClaimable',
            'click span.select-date-all': 'SelectDateAll',
            'click li.bdate': 'SelectButtonDate',
            'change input.open-time': 'InputOpenTime',
            'change input.close-time': 'InputCloseTime',
            'change input.open-time-2': 'InputOpenTime2',
            'change input.close-time-2': 'InputCloseTime2',
            'click span.reset-all': 'ResetAllDate'
        },
        setClaimable: function (event) {
            event.preventDefault();
            var value = $("#et_claimable_value");
            value.val(value.val() == 0 ? 1 : 0);
        },
        /*
         *   Click select date all open time
         */
        SelectDateAll: function (e) {
            var ev = e.target;
            $(ev).toggleClass('active');
            if ($(ev).hasClass('active')) {
                $(ev).text(ae_globals.translate_deselect);
                $('li.bdate').addClass('active');
                $('li.bdate').addClass('recent');
                $(".open-block-sep").show();
                $(".open-times").show();

                $('li.bdate').each(function () {
                    if ($(this).hasClass('active')) {
                        $(this).attr('open-time', $('.open-time').val());
                        $(this).attr('open-time-2', $('.open-time-2').val());
                        $(this).attr('close-time', $('.close-time').val());
                        $(this).attr('close-time-2', $('.close-time-2').val());
                    }
                });

            } else {
                $(ev).text(ae_globals.translate_select);
                $('li.bdate').removeClass('active');
                $('li.bdate').removeClass('recent');
                $('li.bdate').attr('open-time', '');
                $('li.bdate').attr('close-time', '');
                $('li.bdate').attr('open-time-2', '');
                $('li.bdate').attr('close-time-2', '');


                $(".open-block-sep").hide();
                $(".open-times").hide();

                $(".open-times input").val("");
                $("#alguna_opcion_date_seleccionada").val("");
            }
        },
        /*
         *   Click button date
         */
        SelectButtonDate: function (e) {

            $(".open-block-sep").show();
            $(".open-times").show();

            var ev = e.target;
            var active = false;
            $(ev).toggleClass('active');
            $('li.bdate').removeClass('recent');
            if ($(ev).hasClass('active')) {
                $('.select-date-all').addClass('active');
                $('.select-date-all').text(ae_globals.translate_deselect);
                $(ev).attr('open-time', $('.open-time').val());
                $(ev).attr('close-time', $('.close-time').val());
                $(ev).attr('open-time-2', $('.open-time-2').val());
                $(ev).attr('close-time-2', $('.close-time-2').val());
                $(ev).addClass('recent');
            } else {
                $('.open-time').val('');
                $('.close-time').val('');
                $('.open-time-2').val('');
                $('.close-time-2').val('');
                $(ev).attr('open-time', '');
                $(ev).attr('close-time', '');
                $(ev).attr('open-time-2', '');
                $(ev).attr('close-time-2', '');

                $('li.bdate').each(function () {
                    if ($(this).hasClass('active')) {
                        active = true;
                    }
                });
                if (!active) {
                    $('.select-date-all').removeClass('active');
                    $('.select-date-all').text(ae_globals.translate_select);

                    $(".open-block-sep").hide();
                    $(".open-times").hide();
                }
            }
        },
        /*
         * Button date active
         */
        ButtonDateActive: function () {
            var active = false;
            $('li.bdate').each(function (index) {
                if ($(this).hasClass('active')) {
                    active = true;
                }
            });
            if (!active) {
                $('.select-date-all').removeClass('active');
                $('.select-date-all').text(ae_globals.translate_select);
            }
            return active;
        },
        /*
         *   Change input open time
         */
        InputOpenTime: function (e) {
            var ev = e.target;
            var _this = this;
            var active = _this.ButtonDateActive();
            // Check Timeformat
            if ($(ev).val() != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test($(ev).val())) {
                //alert(ae_globals.invalid_time);
                $(ev).val("");
                return;
            }
            if (active) {
                if ($(ev).val() == $('.close-time').val()) {
                    $(ev).val('');
                    $('.close-time').val('');
                    $('li.bdate').each(function (index) {
                        $(this).attr('open-time', '');
                        $(this).attr('close-time', '');
                    });
                }
                else {
                    $('li.bdate').each(function (index) {
                        //if(($(this).hasClass('active') && $(this).hasClass('recent')) || ($(this).hasClass('active') && !$(this).attr('open-time')) ) {
                        if ($(this).hasClass('active')) {
                            $(this).attr('open-time', $(ev).val());
                            $(this).attr('close-time', $('.close-time').val());
                            var open = $(this).attr('open-time');
                            var close = $(this).attr('close-time');

                            if (open == '--') {
                                //$('.close-time').val('');
                                //$(this).attr('data-original-title', '');
                                //$(this).removeClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().removeClass('nbdate');
                                $(this).attr('open-time', 'none');
                                $(this).attr('close-time', 'none');
                            }
                        }
                    });
                }
            } else {
                $(ev).val('');
                $('.close-time').val('');
            }
        },
        InputOpenTime2: function (e) {
            //alert('hhihi');
            var ev = e.target;
            var _this = this;
            var active = _this.ButtonDateActive();
            // Check Timeformat
            if ($(ev).val() != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test($(ev).val())) {
                //alert(ae_globals.invalid_time);
                $(ev).val("");
                return;
            }
            if (active) {
                if ($(ev).val() == $('.close-time-2').val()) {
                    $(ev).val('');
                    $('.close-time-2').val('');
                    $('li.bdate').each(function (index) {
                        $(this).attr('open-time-2', '');
                        $(this).attr('close-time-2', '');
                    });
                }
                else {
                    $('li.bdate').each(function (index) {
                        //if(($(this).hasClass('active') && $(this).hasClass('recent')) || ($(this).hasClass('active') && !$(this).attr('open-time-2')) ) {
                        if ($(this).hasClass('active')) {
                            $(this).attr('open-time-2', $(ev).val());
                            $(this).attr('close-time-2', $('.close-time-2').val());
                            var open2 = $(this).attr('open-time-2');
                            var close2 = $(this).attr('close-time-2');
                            if (open2 == '--') {
                                //$('.close-time-2').val('');
                                //$(this).attr('data-original-title', '');
                                //$(this).removeClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().removeClass('nbdate');
                                $(this).attr('open-time-2', 'none');
                                $(this).attr('close-time-2', 'none');
                            }
                        }
                    });
                }
            } else {
                $(ev).val('');
                $('.close-time-2').val('');
            }
        },
        /*
         *   Change input close time
         */
        InputCloseTime: function (e) {
            var ev = e.target;
            var _this = this;
            var active = _this.ButtonDateActive();
            // Check Timeformat
            if ($(ev).val() != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test($(ev).val())) {
                //alert(ae_globals.invalid_time);
                $(ev).val("");
                return;
            }
            if (active) {
                if ($(ev).val() == $('.open-time').val()) {
                    $(ev).val('');
                    $('.open-time').val('');
                    $('li.bdate').each(function (index) {
                        $(this).attr('open-time', '');
                        $(this).attr('close-time', '');
                    });
                }
                else {
                    $('li.bdate').each(function (index) {
                        //if(($(this).hasClass('active') && $(this).hasClass('recent')) || ($(this).hasClass('active') && !$(this).attr('close-time')) ) {
                        if ($(this).hasClass('active')) {
                            $(this).attr('close-time', $(ev).val());
                            $(this).attr('open-time', $('.open-time').val());
                            var open = $(this).attr('open-time');
                            var close = $(this).attr('close-time');

                            if (close == '--') {
                                //$('.open-time').val('');
                                //$(this).attr('data-original-title', '');
                                //$(this).removeClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().removeClass('nbdate');
                                $(this).attr('open-time', 'none');
                                $(this).attr('close-time', 'none');
                            } else {
                                if (open == '--' || open == '') {
                                    //$('.close-time').val('');
                                    //$(this).attr('data-original-title', '');
                                    //$(this).removeClass('vbdate');
                                    //$(this).removeClass('active');
                                    //$(this).next().removeClass('nbdate');
                                    $(this).attr('open-time', 'none');
                                    $(this).attr('close-time', 'none');
                                } else {
                                    //$(this).addClass('vbdate');
                                    //$(this).removeClass('active');
                                    //$(this).next().addClass('nbdate');
                                    //$(this).attr('data-original-title', open + '<span> to </span>' + close);
                                }
                            }
                        }
                    });
                }
                //$('.select-date-all').removeClass('active');
                //$('.select-date-all').text(ae_globals.translate_select);
            } else {
                $(ev).val('');
                $('.open-time').val('');
            }
        },
        InputCloseTime2: function (e) {
            var ev = e.target;
            var _this = this;
            var active = _this.ButtonDateActive();
            // Check Timeformat
            if ($(ev).val() != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test($(ev).val())) {
                //alert(ae_globals.invalid_time);
                $(ev).val("");
                return;
            }
            if (active) {
                if ($(ev).val() == $('.open-time-2').val()) {
                    $(ev).val('');
                    $('.open-time-2').val('');
                    $('li.bdate').each(function (index) {
                        $(this).attr('open-time-2', '');
                        $(this).attr('close-time-2', '');
                    });
                }
                else {
                    $('li.bdate').each(function (index) {
                        //if(($(this).hasClass('active') && $(this).hasClass('recent')) || ($(this).hasClass('active') && !$(this).attr('close-time-2')) ) {
                        if ($(this).hasClass('active')) {
                            $(this).attr('close-time-2', $(ev).val());
                            $(this).attr('open-time-2', $('.open-time-2').val());
                            var open = $(this).attr('open-time');
                            var close = $(this).attr('close-time');

                            var open2 = $(this).attr('open-time-2');
                            var close2 = $(this).attr('close-time-2');
                            if (close2 == '--') {
                                //$('.open-time-2').val('');
                                //$(this).attr('data-original-title', '');
                                //$(this).removeClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().removeClass('nbdate');
                                $(this).attr('open-time-2', 'none');
                                $(this).attr('close-time-2', 'none');
                            } else {
                                if (open2 == '--' || open2 == '') {
                                    //$('.close-time-2').val('');
                                    //$(this).attr('data-original-title', '');
                                    //$(this).removeClass('vbdate');
                                    //$(this).removeClass('active');
                                    //$(this).next().removeClass('nbdate');
                                    $(this).attr('open-time-2', 'none');
                                    $(this).attr('close-time-2', 'none');
                                } else {
                                    //$(this).addClass('vbdate');
                                    //$(this).removeClass('active');
                                    //$(this).next().addClass('nbdate');
                                    //$(this).attr('data-original-title', open + '<span> to </span>' + close+' and '+open2 + '<span> to </span>' + close2);
                                }
                            }
                        }
                    });
                    $('.select-date-all').removeClass('active');
                    $('.select-date-all').text(ae_globals.translate_select);
                }
            } else {
                $(ev).val('');
                $('.open-time-2').val('');
            }
        },
        /*
         * Reset All Date None
         */
        ResetAllDate: function () {
            $('li.bdate').each(function () {
                $(this).removeClass('vbdate').removeClass('nbdate');
                $(text - heading - stepis).attr('open-time', '').attr('open-time-2', '').attr('close-time', '').attr('close-time-2', '').attr('data-original-title', '');
                var text = $(this).text();
            });
            $('.time-picker').val('');
            $('.open-input input').val('');
        }

    });

    new Views.Post_Place();

})(jQuery, window.AE, window.AE.Views, window.AE.Models, window.AE.Collections);


