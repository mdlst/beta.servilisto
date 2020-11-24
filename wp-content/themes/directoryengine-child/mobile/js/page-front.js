jQuery(document).ready(function ($) {

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
    /**::           where: 'MILE' is statute miles (default)                         :*/
    /**::                  'KM' is kilometers                                      :*/
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


    if (parseInt(ae_globals.geolocation) === 1) {
        // List Place item
        if ($('.post-item').length > 0) {
            var location_lat, location_lng;
            if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {

                // Get GeoLocation of device
                location_lat = frontGetCookie('current_user_lat');
                location_lng = frontGetCookie('current_user_lng');

                $('.post-item').each(function () {
                    var lat_item = $('#latitude', this).attr('content'),
                        lng_item = $('#longitude', this).attr('content');
                    var dist = distance(lat_item, lng_item, location_lat, location_lng);
                    $('.distance', this).text('a ' + dist + ' de tí -');
                });
            }
        }

        // List place review item
        if ($('.review-item').length > 0) {
            var location_lat, location_lng;
            if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {

                // Get GeoLocation of device
                location_lat = frontGetCookie('current_user_lat');
                location_lng = frontGetCookie('current_user_lng');

                $('.review-item').each(function () {
                    var lat_item = $('#latitude', this).attr('content'),
                        lng_item = $('#longitude', this).attr('content');
                    var dist = distance(lat_item, lng_item, location_lat, location_lng);
                    $('.distance', this).text('a ' + dist + ' de tí -');
                });
            }
        }

        if ($('body.single').length > 0) {
            var location_lat, location_lng;
            if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {

                // Get GeoLocation of device
                location_lat = frontGetCookie('current_user_lat');
                location_lng = frontGetCookie('current_user_lng');

                $('body.single').each(function () {
                    var lat_item = $('#latitude', this).attr('content'),
                        lng_item = $('#longitude', this).attr('content');
                    var dist = distance(lat_item, lng_item, location_lat, location_lng);
                    $('.distance', this).text('a ' + dist + ' de tí -');
                });

            }
        }
    }


    /** End Distance between two points */

    $('#dl-menu').dlmenu();
    $('.dl-trigger').click(function () {
        //$('#menu-footer').find('.active').removeClass('active');
        if ($(this).hasClass('dl-active')) {
            window.scrollTo(0, 0);
            //$(this).addClass('active');
        }
    });
    $('.triagle-setting.mobile-setting').click(function (event) {
        $(this).parents('.place-wrapper').toggleClass('active');
        $(this).parents('.post-item').find('.list-option-place li').toggleClass('active');
    });
    // Tab
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('.postform > option').each(function () {
        if ($(this).hasClass('level-0')) {
            $(this).css('font-weight', 'bold');
        }
        if ($(this).hasClass('level-1')) {
            $(this).prepend('&nbsp;&nbsp;&nbsp;');
        }
        if ($(this).hasClass('level-2')) {
            $(this).prepend('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        }
    });

    // Menu Bottom
    var height_1 = $('#menu-footer').height();
    var lastScrollTop = 0;
    $(window).scroll(function () {
        var st = $(this).scrollTop();
        if (st > lastScrollTop && st > 50) {
            // scrolling down
            if ($('#menu-footer').data('size') === 'big') {
                $('#menu-footer').data('size', 'small');
                $('#menu-footer').stop().animate({
                    bottom: '-' + height_1 + 'px',
                    opacity: '0'
                }, 300);
            }
        } else {
            // scrolling up
            if ($('#menu-footer').data('size') === 'small') {
                $('#menu-footer').data('size', 'big');
                $('#menu-footer').stop().animate({
                    bottom: '0',
                    opacity: '1'
                }, 300);
            }
        }
        lastScrollTop = st;
    });
    /**
     * toggle search form
     */
    $('.search-btn').click(function (e) {
        $('.search-field').focus();
        $('body').toggleClass('overflow-hidden');
        $option_search = $('.search-form-wrapper');
        $marsk = $('.marsk-black');
        // toggle search form
        $marsk.fadeToggle(300);
        //$btn_topsearch.toggleClass('active');
        $option_search.slideToggle(300, 'easeInOutSine', function (event) {
            $('.slider-ranger').slider();
        });
    });

    $('.btn-close-form').click(function (e) {
        $('.search-field').focus();
        $('.search-field').val("");

    });


    $('.fancybox').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        },
        // other options
    });
    $('.rate-it').raty({
        readOnly: true,
        half: true,
        score: function () {
            return $(this).attr('data-score');
        },
        hints: raty.hint
    });
    $('.rating-it').raty({
        half: true,
        hints: raty.hint
    });

    /*
     *   change selec place action
     */
    $('#choose-place-action').change(function (event) {
        $('.place-action').removeClass('active');
        var id = event.target.options[event.target.selectedIndex].value;
        $('#' + id).addClass('active');
    });

    // $('.edit-config').click(function() {
    //     console.log($(this).parents('.place-wrapper').next());
    //     $(this).parents('.place-wrapper').next().slideToggle("slow");
    // });

});
(function ($) {
    $(document).ready(function () {
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
        /**::           where: 'MILE' is statute miles (default)                         :*/
        /**::                  'KM' is kilometers                                      :*/
        /**::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        function distance(lat1, lon1, lat2, lon2) {
            var radlat1 = Math.PI * lat1 / 180;
            var radlat2 = Math.PI * lat2 / 180;
            var radlon1 = Math.PI * lon1 / 180;
            var radlon2 = Math.PI * lon2 / 180;
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


        new AE.Views.SearchForm();
        Authentication = Backbone.View.extend({
            events: {
                // open signup form
                'click .link_sign_up': 'toggleSignupForm',
                // open login form
                'click .link_sign_in': 'toggleSigninForm',
                // open forgot pass form
                'click .link_forgot_pass': 'toggleForgotPassForm',
                // user submit login form
                'submit form#signin_form': 'login',
                // user subnmit register form
                'submit form#signup_form': 'register',
                // user forgot password
                'submit form#forgotpass_form': 'requestPassword',
            },
            initialize: function () {
                this.user = new AE.Models.User();
                this.blockUi = new AE.Views.BlockUi();
            },
            /**
             * hide signin form and show register form
             */
            toggleSignupForm: function (event) {
                event.preventDefault();
                $('#login').fadeOut();
                $('#register').fadeIn(500);
            },
            /**
             * hide register form and show login form
             */
            toggleSigninForm: function (event) {
                event.preventDefault();
                $('#register').fadeOut();
                $('#forgotpass').fadeOut();
                $('#login').fadeIn(500);
            },
            /**
             * show forgot pass form
             */
            toggleForgotPassForm: function (event) {
                event.preventDefault();
                $('#login').fadeOut();
                $('#forgotpass').fadeIn(500);
            },
            /**
             * login request
             */
            login: function (event) {
                event.preventDefault();
                var form = $(event.currentTarget),
                    button = form.find('input[type="submit"]'),
                    view = this,
                    message = form.parents('.login-page').find('.message');
                /**
                 * scan all fields in form and set the value to model user
                 */
                form.find('input, textarea, select').each(function () {
                    view.user.set($(this).attr('name'), $(this).val());
                });
                // check form validate and process sign-in
                view.user.set('do', 'login');
                view.user.request('read', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
                        if (status.success) {
                            window.location.reload();
                        } else {
                            message.html('').append('<p>' + status.msg + '</p>');
                            message.addClass('error').removeClass('success');
                        }
                    }
                });
            },
            /**
             * register request
             */
            register: function (event) {
                event.preventDefault();
                event.preventDefault();
                var form = $(event.currentTarget),
                    button = form.find('input[type="submit"]'),
                    view = this,
                    message = form.parents('.login-page').find('.message');
                /**
                 * scan all fields in form and set the value to model user
                 */
                form.find('input, textarea, select').each(function () {
                    view.user.set($(this).attr('name'), $(this).val());
                });
                // check form validate and process sign-in
                view.user.set('do', 'register');
                view.user.request('create', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
                        if (status.success) {
                            message.html('').append('<p>' + status.msg + '</p>');
                            message.addClass('success').removeClass('error');
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        } else {
                            message.html('').append('<p>' + status.msg + '</p>');
                            message.addClass('error').removeClass('success');
                        }
                    }
                });
            },
            /**
             * request password
             */
            requestPassword: function (event) {
                event.preventDefault();
                var form = $(event.currentTarget),
                    button = form.find('input[type="submit"]'),
                    email = form.find('input.email').val(),
                    view = this,
                    message = form.parents('.login-page').find('.message');
                // check form validate and process sign-in
                view.user.set('do', 'forgot');
                this.user.set('user_login', email);
                view.user.request('read', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:forgotpass', user, status, jqXHR);
                        message.html('').append('<p>' + status.msg + '</p>');
                        if (status.success) {
                            message.addClass('success').removeClass('error');
                        } else {
                            message.addClass('error').removeClass('success');
                        }
                    }
                });
            }

        });
        new Authentication({
            el: $('#page-authentication')
        });

        /**
         * contact modal
         */
        DE_Contact = Backbone.View.extend({
            events: {
                'click a.contact-owner': 'openContactModal'
            },
            initialize: function () {
                this.user = new AE.Models.User();
                this.blockUi = new AE.Views.BlockUi();
            },
            openContactModal: function (event) {
                event.preventDefault();
                var $target = $(event.currentTarget);
                if (typeof this.editContactmodal === 'undefined') {
                    this.editContactmodal = new AE.Views.ContactModal({
                        el: $("#contact_message"),
                        model: this.user,
                        user_id: $target.attr('data-user')
                    });
                }
                this.editContactmodal.user_id = $target.attr('data-user');
                this.editContactmodal.openModal();
            },
        });
        new DE_Contact({
            el: $('body')
        });

        //mobile block control
        DE_Mobile = AE.Views.BlockControl.extend({
            onAfterInit: function () {
                var view = this;
                if ($(window).scrollTop() === $(document).height() - $(window).height()) {
                    view.$('.inview').click();
                }
                var inviewid = view.$('.inview').attr('id');
                $('#' + inviewid).bind('inview', function (event, isVisible) {
                    if (!isVisible || view.scrolled) {
                        this.inViewVisible = false;
                        return;
                    }
                    view.loadMore(event);
                });
            },
            onAfterLoadMore: function (result, res) {
                var view = this;
                if (res.success) {
                    if (res.max_num_pages <= view.page || res.data.length === 0) {
                        view.$('.inview').hide();
                    }
                } else {
                    view.$('.inview').hide();
                }
            },
            onAfterFetch: function (result, res) {
                var view = this;
                if (res.success && res.max_num_pages > 1) {
                    view.$('.inview').show();
                } else {
                    view.$('.inview').hide();
                }
            }
        });

        /**
         * place list control
         */
        if ($('#place-list').length > 0) {
            PostItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-place-loop').html()),
                className: 'post-item',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('rating_score'),
                        readOnly: true,
                        hints: raty.hint
                    });
                    // Render distance between two points

                    if (parseInt(ae_globals.geolocation) === 1) {
                        var location_lat, location_lng;
                        if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {

                            // Get GeoLocation of device
                            location_lat = frontGetCookie('current_user_lat');
                            location_lng = frontGetCookie('current_user_lng');

                            var lat_item = view.model.get('et_location_lat'),
                                lng_item = view.model.get('et_location_lng');
                            var dist = distance(lat_item, lng_item, location_lat, location_lng);
                            view.$('.distance').text('a ' + dist + ' de tí -');

                        }

                    }
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListPlace = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: PostItem,
                itemClass: 'post-item'
            });
            if ($('.postdata').length > 0) {
                var collection = new AE.Collections.Posts(JSON.parse($('.postdata').html()));
            } else {
                var collection = new AE.Collections.Posts();
            }
            new ListPlace({
                itemView: PostItem,
                collection: collection,
                el: '#place-list'
            });
            new DE_Mobile({
                collection: collection,
                el: '#place-list-wrapper'
            });
        } //place list control

        /**
         * place pending list control
         */
        if ($('#place-pending-list').length > 0) {
            PostItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-place-loop').html()),
                className: 'post-item',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('rating_score'),
                        readOnly: true,
                        hints: raty.hint
                    });
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListPlace = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: PostItem,
                itemClass: 'post-item'
            });
            if ($('.postdata').length > 0) {
                var collection = new AE.Collections.Posts(JSON.parse($('.postdata').html()));
            } else {
                var collection = new AE.Collections.Posts();
            }
            new ListPlace({
                itemView: PostItem,
                collection: collection,
                el: '#place-pending-list'
            });
            new DE_Mobile({
                collection: collection,
                el: '#place-list-wrapper'
            });
        } //place pending list control

        /**
         * place overdue list control
         */
        if ($('#place-overdue-list').length > 0) {
            PostItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-place-loop').html()),
                className: 'post-item',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('rating_score'),
                        readOnly: true,
                        hints: raty.hint
                    });
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListPlace = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: PostItem,
                itemClass: 'post-item'
            });
            if ($('.postdata').length > 0) {
                var collection = new AE.Collections.Posts(JSON.parse($('.postdata').html()));
            } else {
                var collection = new AE.Collections.Posts();
            }
            new ListPlace({
                itemView: PostItem,
                collection: collection,
                el: '#place-overdue-list'
            });
            new DE_Mobile({
                collection: collection,
                el: '#place-list-wrapper'
            });
        } //place overdue list control

        /**
         * place overdue list control
         */
        if ($('#place-rejected-list').length > 0) {
            PostItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-place-loop').html()),
                className: 'post-item',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('rating_score'),
                        readOnly: true,
                        hints: raty.hint
                    });
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListPlace = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: PostItem,
                itemClass: 'post-item'
            });
            if ($('.postdata').length > 0) {
                var collection = new AE.Collections.Posts(JSON.parse($('.postdata').html()));
            } else {
                var collection = new AE.Collections.Posts();
            }
            new ListPlace({
                itemView: PostItem,
                collection: collection,
                el: '#place-rejected-list'
            });
            new DE_Mobile({
                collection: collection,
                el: '#place-list-wrapper'
            });
        } //place overdue list control

        /**
         * place overdue list control
         */
        if ($('#place-draft-list').length > 0) {
            PostItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-place-loop').html()),
                className: 'post-item',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('rating_score'),
                        readOnly: true,
                        hints: raty.hint
                    });
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListPlace = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: PostItem,
                itemClass: 'post-item'
            });
            if ($('.postdata').length > 0) {
                var collection = new AE.Collections.Posts(JSON.parse($('.postdata').html()));
            } else {
                var collection = new AE.Collections.Posts();
            }
            new ListPlace({
                itemView: PostItem,
                collection: collection,
                el: '#place-draft-list'
            });
            new DE_Mobile({
                collection: collection,
                el: '#place-list-wrapper'
            });
        } //place overdue list control

        /**
         * place overdue list control
         */
        if ($('#place-events-list').length > 0) {
            PostItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-place-loop').html()),
                className: 'post-item',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('rating_score'),
                        readOnly: true,
                        hints: raty.hint
                    });
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListPlace = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: PostItem,
                itemClass: 'post-item'
            });
            if ($('.postdata').length > 0) {
                var collection = new AE.Collections.Posts(JSON.parse($('.postdata').html()));
            } else {
                var collection = new AE.Collections.Posts();
            }
            new ListPlace({
                itemView: PostItem,
                collection: collection,
                el: '#place-events-list'
            });
            new DE_Mobile({
                collection: collection,
                el: '#place-list-wrapper'
            });
        } //place overdue list control


        /**
         * review list control
         */
        if ($('#list-reviews').length > 0) {
            //console.log("test");
            /**
             * review block control
             */
            ReviewItem = AE.Views.PostItem.extend({
                template: _.template($('#de-review-item').html()),
                className: 'col-xs-12',
                events: {
                    'click .edit-config': 'showEditConfig'
                },
                onItemRendered: function () {
                    var view = this;
                    this.$('.rate-it').raty({
                        half: true,
                        score: view.model.get('et_rate'),
                        readOnly: true,
                        hints: raty.hint
                    });

                    // Render distance between two points
                    if (parseInt(ae_globals.geolocation) == 1) {

                        if (frontGetCookie('current_user_lat') && frontGetCookie('current_user_lng')) {

                            // Get GeoLocation of device
                            location_lat = frontGetCookie('current_user_lat');
                            location_lng = frontGetCookie('current_user_lng');

                            var post_data = view.model.get('post_data'),
                                lat_item = post_data['et_location_lat'],
                                lng_item = post_data['et_location_lng'];
                            var dist = distance(lat_item, lng_item, location_lat, location_lng);
                            view.$('.distance', this).text('a ' + dist + ' de tí -');

                        }
                    }
                },
                showEditConfig: function (event) {
                    $target = $(event.currentTarget);
                    $target.parents('.place-wrapper').toggleClass('active-edit');
                    $target.parents('.place-wrapper').children('.edit-place-post').slideToggle("slow");
                }
            });
            ListReview = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: ReviewItem,
                itemClass: 'col-xs-12'
            });
            var collection = new AE.Collections.Comments();
            new ListReview({
                itemView: ReviewItem,
                collection: collection,
                el: '#list-reviews'
            });
            new DE_Mobile({
                collection: collection,
                el: '#reviews-list-wrapper'
            });
        } //review list control
        if ($('.list-user-page-info').length > 0) {
            /**
             * review block control
             */
            UserListItem = AE.Views.PostItem.extend({
                tagName: 'li',
                className: 'user-list-item',
                template: _.template($('#de-user-item').html()),
                onItemBeforeRender: function () {
                    // before render view
                },
                onItemRendered: function () {
                    // after render view
                }
            });
            ListUsers = AE.Views.ListPost.extend({
                tagName: 'ul',
                itemView: UserListItem,
                itemClass: 'user-list-item'
            });
            if ($('.list-user-page-info').length > 0) {
                $('.list-user-page-info').each(function () {
                    if ($(this).find('.userdata').length > 0) {
                        var userdata = JSON.parse($(this).find('.userdata').html()),
                            collection = new AE.Collections.Users(userdata);
                    } else {
                        collection = new AE.Collections.Users();
                    }
                    new ListUsers({
                        el: $(this),
                        collection: collection,
                        itemView: UserListItem,
                        itemClass: 'user-list-item'
                    });
                    /**
                     * init block control list blog
                     */
                    new AE.Views.BlockControl({
                        collection: collection,
                        el: $('body')
                    });
                });
            }
        } //review list control
        /**
         * block list control
         */
        if ($('#list-blog').length > 0) {
            /**
             * review block control
             */
            BlogItem = AE.Views.PostItem.extend({
                template: _.template($('#ae-post-loop').html()),
                className: 'news-wrapper',
                tagName: 'div'
            });
            ListBlog = AE.Views.ListPost.extend({
                tagName: 'div',
                itemView: BlogItem,
                itemClass: 'col-xs-12'
            });
            var collection = new AE.Collections.Blogs();
            new ListBlog({
                itemView: BlogItem,
                collection: collection,
                el: '#list-blog'
            });
            new DE_Mobile({
                collection: collection,
                el: '#list-news'
            });
        }
        /**
         * // blog list control
         */

        /**
         * block control events on mobile
         */
        DE_MobileEvents = Backbone.View.extend({
            events: {
                // ajax load more
                'click a.load-more-post': 'loadMore'
            },
            initialize: function () {
                var view = this;
                _.bindAll(this, 'loadMore');
                if (this.$('.ae_query').length > 0) {
                    this.query = JSON.parse(this.$('.ae_query').html());
                    this.query.paged = 1;
                    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                        view.loadMore();
                    }
                    var inviewid = view.$('.inview').attr('id');
                    $('#' + inviewid).bind('inview', function (event, isVisible) {
                        if (!isVisible || view.scrolled) {
                            this.inViewVisible = false;
                            return;
                        }
                        view.loadMore();
                    });
                } else {
                    this.$('.paginations').remove();
                }
                this.blockUi = new AE.Views.BlockUi();
            },
            /**
             * load more places
             */
            loadMore: function (e) {
                var view = this;
                view.page = view.query.paged;
                view.page++;
                view.query.paged++;
                // collection fetch
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'get',
                    data: {
                        query: view.query,
                        page: view.page,
                        action: 'de-mobile-fetch-events',
                        paginate: true
                    },
                    beforeSend: function () {
                        view.blockUi.block(view.$('.inview'));
                        view.query.paged++;
                    },
                    success: function (res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            $('#list-events').append(res.data);
                            view.$('.rate-it').raty({
                                half: true,
                                score: function () {
                                    return $(this).attr('data-score');
                                },
                                readOnly: true,
                                hints: raty.hint
                            });
                        } else {
                            view.$('.inview').hide();
                        }
                    }
                });
            }
        });
        new DE_MobileEvents({
            el: $('#events-list-wrapper')
        });
        // block control mobile
        $('#search-nearby').click(function (event) {
            $(".loader_full_mobile").show();
            navigator.geolocation.getCurrentPosition(searchNearby, errorHandle, {timeout: 30000});
        });

        function searchNearby(position) {

            AE.pubsub.trigger('ae:notification', {
                msg: "Geolocalizado correctamente",
                notice_type: 'success'
            });


            var coords = position.coords;
            $('#nearby').find('input').val(coords.latitude + ',' + coords.longitude);
            $('#nearby').submit();
        }

        function errorHandle(error) {
            $(".loader_full_mobile").hide();

            switch (error.code) {
                case error.PERMISSION_DENIED:
                    AE.pubsub.trigger('ae:notification', {
                        msg: "Para utilizar esta función, habilite la geolocalización desde el centro de notificaciones.",
                        notice_type: 'error'
                    });
                    return;
                    break;
                case error.POSITION_UNAVAILABLE:
                    AE.pubsub.trigger('ae:notification', {
                        msg: "La información de ubicación no está disponible.",
                        notice_type: 'error'
                    });
                    return;
                    break;
                case error.TIMEOUT:
                    AE.pubsub.trigger('ae:notification', {
                        msg: "Se ha agotado el tiempo de espera para obtener su ubicación.",
                        notice_type: 'error'
                    });
                    return;
                    break;
                case error.UNKNOWN_ERROR:
                    AE.pubsub.trigger('ae:notification', {
                        msg: "Ha ocurrido un error desconocido",
                        notice_type: 'error'
                    });
                    return;
                    break;
            }
        }

        $('form').validate();
    });
})(jQuery);