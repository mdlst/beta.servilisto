var $jDOPBSP = jQuery.noConflict();
(function ($, Views, Models, Collections) {
    $(document).ready(function () {
        $('.not_is_tablet #main-single .pinned-custom').pin({
            containerSelector: "#main-single",
            padding: {
                top: 100,
                bottom: 120
            }
        });
        Collections.Events = Backbone.Collection.extend({
            model: AE.Models.Event,
            action: 'ae-fetch-events',
            initialize: function () {
                this.paged = 1;
            }
        });
        EventItem = Views.PostItem.extend({
            template: _.template($('#de-event-item').html()),
            // class name define column
            className: 'event-wrapper event-item',
            onItemRendered: function () {
                // console.log('rendered');
            }
        });
        ListEvent = Views.ListPost.extend({
            tagName: 'div',
            itemView: EventItem,
            itemClass: 'event-item'
        });
        if ($('#block-events .postdata').length > 0) {
            var postdata = JSON.parse($('#block-events').find('.postdata').html()),
                collection = new Collections.Events(postdata);
            new ListEvent({
                el: $('#list-events'),
                collection: collection
            });
        }
    });

    /**
     * control single place view
     */
    Views.SinglePost = Backbone.Marionette.View.extend({
        el: 'body.single',
        events: {
            // user click on action button such as edit, archive, reject
            'click a.place-action': 'acting',
            // slide to review section
            'click a.write-review': 'slideToReview',
            'click a.sroll-review': 'jumbToReview',
            // add to favorite
            'click a.favorite': 'favorite',
            // add to favorite
            'click a.loved': 'removeFavorite',
            // send report to admins
            'click a.report': 'openReportModal',
            //claim a place
            'click a.claim-place': 'openClaimModal',
            // trigger when user load next post
            'click a.load-more-post': 'loadNextPost',

            'mouseover  .not_is_tablet .list-option-left .share-social': 'showShare',
            'mouseleave  .not_is_tablet .list-option-left .share-social': 'hideShare',
            'click  .is_tablet .list-option-left .share-social': 'toggleShare'
        },
        /**
         * Open Report Modal
         */
        openReportModal: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this;
            if (typeof this.Reportmodal === 'undefined') {
                this.Reportmodal = new Views.ReportModal({
                    el: $("#report"),
                    place_id: $target.attr('data-id'),
                    user_id: $target.attr('data-user'),
                    model: view.model
                });
            }
            this.Reportmodal.place_id = $target.attr('data-id');
            this.Reportmodal.user_id = $target.attr('data-user');
            this.Reportmodal.openModal();
        },
        /**
         * Open Claim Modal
         */
        openClaimModal: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this;
            if (typeof this.claimModal === 'undefined') {
                this.claimModal = new Views.ClaimModal({
                    el: $("#claim_modal"),
                    place_id: $target.attr('data-id'),
                    user_id: $target.attr('data-user'),
                    model: view.model
                });
            }
            this.claimModal.place_id = $target.attr('data-id');
            this.claimModal.user_id = $target.attr('data-user');
            this.claimModal.openModal();
        },
        showShare: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget);
            $target.find('.list-share-social').addClass('active');
        },
        hideShare: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget);
            $target.find('.list-share-social').removeClass('active');
        },
        toggleShare: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget);
            $target.find('.list-share-social').toggleClass('active').toggle();
        },

        initialize: function (options) {
            var view = this;
            // bind all external event
            _.bindAll(this, 'updateUrl');
            this.options = _.extend(options, this.options);
            //AE.pubsub.on('ae:after:editPost', 'editPostSucess');
            $('.tooltip-style').tooltip();
            // get next post id
            if ($('#next_id').length > 0) {
                var next = JSON.parse($('#next_id').html());
                this.next_post = next.id;
                this.load = 'next';
            }
            // get prev post id
            if ($('#prev_id').length > 0) {
                var prev = JSON.parse($('#prev_id').html());
                this.prev_post = prev.id;
            }
            view.blockUi = new Views.BlockUi();
            view.collection = new Collections.Posts();
            view.collection.add(view.model);
            $('.popup-video').magnificPopup({
                type: 'iframe'
            });
            //this.$('.single-place-wrapper').bind('inview', view.updateUrl);
        },
        /**
         * event callback when user click on action button
         * edit
         * archive
         * reject
         * toggleFeatured
         * approve
         */
        acting: function (e) {
            // e.preventDefault();
            var target = $(e.currentTarget),
                action = target.attr('data-action'),
                id = target.parents('.single-place-wrapper').attr('data-id'),
                model = this.collection.get(id),
                view = this;
            // fetch model data
            switch (action) {
                case 'edit_calender':

                    //trigger an event will be catch by AE.App to open modal edit
                    AE.pubsub.trigger('ae:model:onCreateCalender', model);

                    var id = $jDOPBSP('#DOPBSP-calendar-ID').val();
                    //alert(calendarId);

                    DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
                    DOPBSPBackEndSettings.toggle(id, 'calendar');
                    $.post(ajaxurl, {action: 'dopbsp_calendar_get_options', id: id},
                        function (data) {
                            $('#DOPBSP-column2 .dopbsp-column-content').html('<div id="DOPBSP-calendar"></div>');
                            $('#DOPBSP-calendar').DOPBSPCalendarBackEnd($.parseJSON(data));

                            $.post(ajaxurl, {action: 'dopbsp_get_new_reservations', id: id}, function (data) {
                                if (parseInt(data) !== 0) {
                                    $('#DOPBSP-new-reservations').addClass('dopbsp-new');
                                    $('#DOPBSP-new-reservations span').html(data);
                                }
                            });
                        }).fail(function (data) {
                        DOPBSPBackEnd.toggleMessages('error', data.status + ': ' + data.statusText);
                    });


                    /*$jDOPBSP.post(ajaxurl, {action:'dopbsp_show_calendar', calendar_id:calendarId}, function(data){

                     var DOPBSP_user_role = 'administrator';
                     var HeaderHTML = new Array();
                     if (DOPBSP_user_role == 'administrator'){
                     var post_type = dopbspGetUrlVars()["post_type"];
                     var post_action = dopbspGetUrlVars()["action"];
                     }

                     if (DOPBSP_user_role == 'administrator'){
                     HeaderHTML.push('<a href="javascript:void()" class="header-help"><span>'+DOPBSP_CALENDAR_EDIT_ADMINISTRATOR_HELP+'</span></a>');
                     }
                     else{
                     HeaderHTML.push('<a href="javascript:void()" class="header-help"><span>'+DOPBSP_CALENDAR_EDIT_HELP+'</span></a>');
                     }

                     $jDOPBSP('.column-header', '.column2', '.DOPBSP-admin').html(HeaderHTML.join(''));
                     $jDOPBSP('.column-content', '.column2', '.DOPBSP-admin').html('<div id="DOPBSP-Calendar"></div>');
                     $jDOPBSP('#DOPBSP-Calendar').DOPBookingSystemPRO2($jDOPBSP.parseJSON(data));

                     $jDOPBSP.post(ajaxurl, {action:'dopbsp_show_new_reservations', calendar_id:calendarId}, function(data){
                     if (parseInt(data) != 0){
                     $jDOPBSP('#DOPBSP-new-reservations').addClass('new');
                     $jDOPBSP('#DOPBSP-new-reservations span').html(data);
                     }
                     });
                     });*/

                    break;


                case 'create_event':
                    //trigger an event will be catch by AE.App to open modal edit
                    $.ajax({
                        type: 'get',
                        url: ae_globals.ajaxURL,
                        data: {
                            action: 'ae-check-event',
                            post_parent: model.get('ID')
                        },
                        beforeSend: function () {
                            view.blockUi.block(target.parents('.dropdown'));
                        },
                        success: function (res) {
                            view.blockUi.unblock();
                            if (res.success) {
                                AE.pubsub.trigger('ae:model:onCreateEvent', model);
                            } else {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: res.msg,
                                    notice_type: 'error',
                                });
                            }
                        }
                    });
                    break;
                case 'edit':
                    //trigger an event will be catch by AE.App to open modal edit
                    AE.pubsub.trigger('ae:model:onEdit', model);
                    $('#place_category').trigger('render.drop'); //Miguel. Fix. Hace que se muestren las categorias seleccionadas del drop.js
                    break;
                case 'reject':
                    //trigger an event will be catch by AE.App to open modal reject
                    AE.pubsub.trigger('ae:model:onReject', model);
                    break;
                case 'archive':
                    // archive a model
                    //model.set('do', 'archivePlace');
                    if (confirm(ae_globals.confirm_message)) {
                        model.save('post_status', 'archive', {
                            beforeSend: function () {
                            },
                            success: function (result, status) {
                                if (status.success) {
                                    AE.pubsub.trigger('ae:notification', {
                                        msg: status.msg,
                                        notice_type: 'success',
                                    });
                                    window.location.reload();
                                } else {
                                    AE.pubsub.trigger('ae:notification', {
                                        msg: status.msg,
                                        notice_type: 'error',
                                    });
                                }
                            }
                        });
                    }
                    break;
                case 'toggleFeature':
                    // toggle featured
                    //model.set('do', 'toggleFeature');
                    if (parseInt(model.get('et_featured')) === 1) {
                        model.set('et_featured', 0);
                    } else {
                        model.set('et_featured', 1);
                    }
                    model.save('', '', {
                        beforeSend: function () {
                        },
                        success: function (result, status) {
                            if (status.success) {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: status.msg,
                                    notice_type: 'success',
                                });
                                window.location.reload();
                            } else {
                                AE.pubsub.trigger('ae:notification', {
                                    msg: status.msg,
                                    notice_type: 'error',
                                });
                            }
                        }
                    });
                    break;
                case 'approve':
                    // publish a model
                    model.save('publish', '1', {
                        beforeSend: function () {
                        },
                        success: function (result, status) {
                            if (status.success) {
                                window.location.href = model.get('permalink');
                            }
                        }
                    });
                    break;
                default:
                    break;
            }
        },
        // slide to review
        slideToReview: function (event) {
            //prevent the default action for the click event
            event.preventDefault();
            //get the top offset of the target anchor
            if (ae_globals.user_ID === '0') {
                var target_offset = $(".ae-comment-reply-title").offset();
            }
            else {
                var target_offset = $("#review").offset();
            }
            var target_top = target_offset.top;
            //goto that anchor by setting the body scroll top to anchor top
            $('html, body').animate({
                scrollTop: target_top - 200
            }, 500, 'easeOutExpo');
        },
        jumbToReview: function (event) {
            //prevent the default action for the click event
            event.preventDefault();
            //get the top offset of the target anchor
            var target_offset = $("#review-list").offset();
            var target_top = target_offset.top;
            //goto that anchor by setting the body scroll top to anchor top
            $('html, body').animate({
                scrollTop: target_top - 200
            }, 500, 'easeOutExpo');
        },
        /**
         * add place to favorite list ( togos list )
         */
        favorite: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this,
                favorite = new Models.Favorite({
                    comment_post_ID: $target.attr('data-id'),
                    sync: 'add'
                });
            favorite.save('sync', 'add', {
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (result, res, xhr) {
                    view.blockUi.unblock();
                    if (res.success === true) {
                        $target.closest('li').attr('title', res.text);
                        $target.addClass('loved').removeClass('favorite');
                        $target.attr('data-favorite-id', res.data);
                        AE.pubsub.trigger('ae:notification', {msg: res.msg, notice_type: 'success'});
                    }
                    else {
                        AE.pubsub.trigger('ae:notification', {msg: res.msg, notice_type: 'error'});
                    }
                }
            });
        },
        /**
         * add place to favorite list ( togos list )
         */
        removeFavorite: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this,
                favorite = new Models.Favorite({
                    id: $target.attr('data-favorite-id'),
                    ID: $target.attr('data-favorite-id'),
                    sync: 'remove'
                });
            favorite.save('sync', 'remove', {
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (result, res, xhr) {
                    view.blockUi.unblock();
                    if (res.success === true) {
                        $target.closest('li').attr('title', res.text);
                        $target.addClass('favorite').removeClass('loved');
                        AE.pubsub.trigger('ae:notification', {msg: res.msg, notice_type: 'success'});
                    }
                    else {
                        AE.pubsub.trigger('ae:notification', {msg: res.msg, notice_type: 'error'});
                    }
                }
            });
        },
        // after edit post reload
        editPostSucess: function (model) {
            if (res.success) {
                window.location.reload();
            }
        },
        /**
         * load next post
         */
        loadNextPost: function (event) {
            event.preventDefault();
            var model = this.next_post,
                $target = $(event.currentTarget),
                view = this;
            // default is load next post, if next post is undefined 
            // load previous post
            if (!model) {
                model = this.prev_post;
                // set load is prev
                this.load = 'prev';
            }
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'get',
                data: {
                    action: 'de-next-place',
                    id: model,
                    current: view.model.get('id'),
                    load: view.load
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (res) {
                    view.blockUi.unblock();
                    if (res.success) {
                        // append content to view
                        $('#single-more-place').append(res.content);
                        // Code that will load the dynamic content
                        // Once that's all done, call addthis.toolbox()
                        // addthis.toolbox('.addthis_toolbox');
                        // bind popup gallery to place images
                        $('#single-place-' + res.post_id).find('.fancybox').magnificPopup({
                            type: 'image',
                            gallery: {
                                enabled: true
                            },
                            // other options
                        });
                        $('.not_is_tablet #single-place-' + res.post_id).find(".list-option-left").pin({
                            containerSelector: '#single-place-' + res.post_id,
                            padding: {
                                top: 100,
                                bottom: 120
                            }
                        });
                        // update next post if load is next
                        if (view.load === 'next') {
                            view.next_post = res.next;
                        }
                        // update prev post if load is prev
                        if (view.load === 'prev') {
                            view.prev_post = res.prev;
                        }
                        /**
                         * create model place to control data
                         */
                        var model = new Models.Post({
                            id: res.post_id,
                            ID: res.post_id,
                            pageTitle: res.pageTitle,
                            link: res.link
                        });
                        // update document title and link
                        document.title = res.pageTitle;
                        //console.log(res.pageTitle);
                        // update url 
                        window.history.pushState({
                            "html": res.content,
                            "pageTitle": res.pageTitle
                        }, "", res.link);
                        // add model place to single place collection
                        view.collection.add(model);
                        // bind inview to change title, url when user scroll to place
                        view.bindInview();
                        // fetch model data
                        model.fetch();
                        // update rating score
                        $('.rate-it').raty({
                            readOnly: true,
                            half: true,
                            score: function () {
                                return $(this).attr('data-score');
                            },
                            hints: raty.hint
                        });

                        // Render Distance  //geolocation
                        var location_lat, location_lng;

                        if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {
                            location_lat = frontGetCookie('current_user_lat');
                            location_lng = frontGetCookie('current_user_lng');

                            var $itemSinglePlace = $('#single-place-' + res.post_id),
                                lat_item = $itemSinglePlace.find('#latitude').attr('content'),
                                lng_item = $itemSinglePlace.find('#longitude').attr('content');

                            var dist = distance(lat_item, lng_item, location_lat, location_lng);
                            $itemSinglePlace.find('.distance').text("a " + dist + ' de tí - ');

                        }


                    } else {
                        $target.remove();
                    }
                }
            });
        },
        // bind inview to place item to update url
        bindInview: function () {
            var view = this;
            this.$('.single-place-wrapper').bind('inview', view.updateUrl);
        },
        // update browser url when scroll to an item in group
        updateUrl: function (event, isVisible) {
            var view = this;
            if (!isVisible) {
                this.inViewVisible = false;
                return;
            }
            var $target = $(event.currentTarget),
                id = $target.attr('data-id'),
                model = this.collection.get(parseInt(id));
            if (model.get('post_title')) {
                document.title = model.get('post_title');
            } else {
                document.title = model.get('pageTitle');
            }

            window.history.pushState({
                "html": $target.html(),
                "pageTitle": model.get('pageTitle')
            }, "", model.get('link'));
        }
    });
    /*
     *
     * Claim Modal Views
     * @place
     *
     */
    Views.ClaimModal = Views.Modal_Box.extend({
        events: {
            'submit form#submit_claim': 'submitClaim',
            'click a.deny-claim': 'denyClaim'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
            this.initValidator();
        },
        initValidator: function () {
            /**
             * post form validate
             */
            $("form#submit_claim").validate({
                ignore: "",
                rules: {
                    display_name: "required",
                    location: "required",
                    phone: {
                        required: true,
                        number: true
                    },
                    message: "required",
                },
                errorPlacement: function (label, element) {
                    // position error label after generated textarea
                    if (element.is("textarea")) {
                        label.insertAfter(element.next());
                    } else {
                        $(element).closest('div').append(label);
                    }
                }
            });
        },
        denyClaim: function (event) {
            event.preventDefault();
            var btn = $(event.currentTarget);
            this.blockUi.block(btn);
            $("input#claim_action").val('deny');
            $('.trigger-claim').trigger('click');
        },
        submitClaim: function (event) {
            event.preventDefault();
            event.stopPropagation();

            var $form = $(event.currentTarget),
                button = $form.find('.btn-submit'),
                textarea = $form.find('textarea'),
                content = $form.serializeObject(),
                view = this;

            content.place_id = this.place_id;

            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'POST',
                data: {
                    action: 'ae_claim_place',
                    content: content
                },
                beforeSend: function () {
                    view.blockUi.block(button);
                },
                success: function (res) {
                    AE.pubsub.trigger('ae:notification', {
                        msg: res.msg,
                        notice_type: 'success'
                    });
                    //reload
                    window.location.reload();
                    //reset form
                    $form[0].reset();
                    //unblock button                  
                    view.blockUi.unblock();
                    //close modal
                    view.closeModal();
                }
            });
        },
    });
    /*
     *
     * Report Modal Views
     * @place
     *
     */
    Views.ReportModal = Views.Modal_Box.extend({
        events: {
            'submit form#submit_report': 'submitReport',
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
            this.initValidator();
        },
        initValidator: function () {
            /**
             * post form validate
             */
            $("form#submit_report").validate({
                ignore: "",
                rules: {
                    message: "required",
                },
                errorPlacement: function (label, element) {
                    // position error label after generated textarea
                    if (element.is("textarea")) {
                        label.insertAfter(element.next());
                    } else {
                        $(element).closest('div').append(label);
                    }
                }
            });
        },
        submitReport: function (event) {
            event.preventDefault();
            event.stopPropagation();

            var $form = $(event.currentTarget),
                button = $form.find('.btn-submit'),
                textarea = $form.find('textarea'),
                view = this,
                report = new Models.Report({
                    comment_post_ID: view.place_id,
                    comment_content: textarea.val(),
                    user_report: view.user_id,
                    sync: 'add'
                });

            report.save('sync', 'add', {
                beforeSend: function () {
                    view.blockUi.block(button);
                },
                success: function (result, res, xhr) {
                    AE.pubsub.trigger('ae:notification', {
                        msg: res.msg,
                        notice_type: 'success'
                    });
                    //reset form
                    $form[0].reset();
                    //remove button
                    $('a#report_' + view.place_id).remove();
                    //unblock button                  
                    view.blockUi.unblock();
                    //close modal
                    view.closeModal();
                }
            });
        },
    });
    /**
     * model favorite
     */
    Models.Favorite = Backbone.Model.extend({
        action: 'ae-sync-favorite',
        initialize: function () {
        }
    });
    /**
     * model report
     */
    Models.Report = Backbone.Model.extend({
        action: 'ae-sync-report',
        initialize: function () {
        }
    });

    /**::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /**::                                                                         :*/
    /**::  This routine calculates the distance between two points (given the     :*/
    /**::  latitude/longitude of those points).                                   :*/
    /**::                                                                         :*/
    /**::  Definitions:                                                           :*/
    /**::    South latitudes are negative, east longitudes are positive           :*/
    /**::                                                                         :*/
    /**::  Passed to function:                                                    :*/
    /**::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
    /**::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
    /**::    unit = the unit you desire for results                               :*/
    /**::           where: 'MILE' is statute miles (default)                      :*/
    /**::                  'KM' is kilometers                                     :*/
    /**::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function distance(lat1, lon1, lat2, lon2) {

        var radlat1 = Math.PI * lat1 / 180;
        var radlat2 = Math.PI * lat2 / 180;
        var theta = lon1 - lon2;
        var radtheta = Math.PI * theta / 180;
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        dist = Math.acos(dist);
        dist = dist * 180 / Math.PI;
        // don vi dam
        dist = dist * 60 * 1.1515;
        var unit = ae_globals.units_of_measurement;
        if (unit === 'km') {
            // If distance smaller than 1 Km => convert unit to Metre
            if (dist < 1) {
                dist = Math.ceil(dist * 1000) + ' m';
            } else {
                // Convert unit to Kilometer
                // 1 Mi = 1.609 Km
                dist = Math.ceil(dist * 1.609) + ' Km';
            }
        } else {
            // Convert unit to Miles
            dist = Math.ceil(dist) + ' Mi';
        }
        return dist;
    }

    if ($('body.single').length > 0) {
        var location_lat, location_lng;
        if (parseInt(ae_globals.geolocation)) {

            if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {
                location_lat = frontGetCookie('current_user_lat');
                location_lng = frontGetCookie('current_user_lng');
                $('body.single').each(function () {
                    var lat_item = $('#latitude', this).attr('content'), lng_item = $('#longitude', this).attr('content');
                    var dist = distance(lat_item, lng_item, location_lat, location_lng);

                    $('.poner-aqui-distance-calculada',this).append("(A "+ dist+ " de tí)");
                });

            }


        }
    }
})(jQuery, AE.Views, AE.Models, AE.Collections);