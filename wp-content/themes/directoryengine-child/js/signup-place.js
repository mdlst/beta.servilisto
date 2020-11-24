var $jDOPBSP = jQuery.noConflict();
(function($, Views, Models, Collections) {
    $(document).ready(function() {
        $('.not_is_tablet #main-single .list-option-left').pin({
            containerSelector: "#main-single",
            padding: {
                top: 100,
                bottom: 120
            }
        });
        Collections.Events = Backbone.Collection.extend({
            model: AE.Models.Event,
            action: 'ae-fetch-events',
            initialize: function() {
                this.paged = 1;
            }
        });
        EventItem = Views.PostItem.extend({
            template: _.template($('#de-event-item').html()),
            // class name define column 
            className: 'event-wrapper event-item',
            onItemRendered: function() {
                //console.log('rendered');
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
        el: 'body.page-template-page-post-place-php',
        events: {
            // user click on action button such as edit, archive, reject
            'click a.place-action': 'acting',
        },
        initialize: function(options) {
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
        acting: function(e) {
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
					$.post(ajaxurl, {action: 'dopbsp_calendar_get_options',id: id}, 
						function(data){
							$('#DOPBSP-column2 .dopbsp-column-content').html('<div id="DOPBSP-calendar"></div>');
							$('#DOPBSP-calendar').DOPBSPCalendarBackEnd($.parseJSON(data));
			
							$.post(ajaxurl, {action: 'dopbsp_get_new_reservations',id: id}, function(data){
							if (parseInt(data) !== 0){
								$('#DOPBSP-new-reservations').addClass('dopbsp-new');
								$('#DOPBSP-new-reservations span').html(data);
							}
						});
					}).fail(function(data){
						DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
					});
					
					/*var calendarId = $jDOPBSP('#calendar_id').val();
					$jDOPBSP.post(ajaxurl, {action:'dopbsp_show_calendar', calendar_id:calendarId}, function(data){
					var DOPBSP_user_role = 'administrator';
					var HeaderHTML = new Array();
					if (DOPBSP_user_role == 'administrator'){
						var post_type = dopbspGetUrlVars()["post_type"];
						var post_action = dopbspGetUrlVars()["action"];
					}
					if (DOPBSP_user_role == 'administrator'){
						HeaderHTML.push('<a href="javascript:void()" class="header-help"><span>'+DOPBSP_CALENDAR_EDIT_ADMINISTRATOR_HELP+'</span></a>');
					}else{
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
				
                case 'edit':

                    //trigger an event will be catch by AE.App to open modal edit

                    AE.pubsub.trigger('ae:model:onEdit', model);

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

                            beforeSend: function() {},

                            success: function(result, status) {

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

                    if (parseInt(model.get('et_featured')) == 1) {

                        model.set('et_featured', 0);

                    } else {

                        model.set('et_featured', 1);

                    }

                    model.save('', '', {

                        beforeSend: function() {},

                        success: function(result, status) {

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

                        beforeSend: function() {},

                        success: function(result, status) {

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

        slideToReview: function(event) {

            //prevent the default action for the click event

            event.preventDefault();

            //get the top offset of the target anchor

            var target_offset = $("#review").offset();

            var target_top = target_offset.top;

            //goto that anchor by setting the body scroll top to anchor top

            $('html, body').animate({

                scrollTop: target_top - 200

            }, 500, 'easeOutExpo');

        },

        jumbToReview: function(event) {

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

        favorite: function(event) {

            event.preventDefault();

            var $target = $(event.currentTarget),

                view = this,

                favorite = new Models.Favorite({

                    comment_post_ID: $target.attr('data-id'),

                    sync: 'add'

                });

            favorite.save('sync', 'add', {

                beforeSend: function() {

                    view.blockUi.block($target);

                },

                success: function(result, res, xhr) {

                    $target.closest('li').attr('data-original-title', res.text);

                    $target.addClass('loved').removeClass('favorite');

                    $target.attr('data-favorite-id', res.data);

                    view.blockUi.unblock();

                }

            });

        },

        /**

         * add place to favorite list ( togos list )

         */

        removeFavorite: function(event) {

            event.preventDefault();

            var $target = $(event.currentTarget),

                view = this,

                favorite = new Models.Favorite({

                    id: $target.attr('data-favorite-id'),

                    ID: $target.attr('data-favorite-id'),

                    sync: 'remove'

                });

            favorite.save('sync', 'remove', {

                beforeSend: function() {

                    view.blockUi.block($target);

                },

                success: function(result, res, xhr) {

                    $target.closest('li').attr('data-original-title', res.text);

                    $target.addClass('favorite').removeClass('loved');

                    view.blockUi.unblock();

                }

            });

        },

        // after edit post reload

        editPostSucess: function(model) {

            console.log('a');

            if (res.success) {

                window.location.reload();

            }

        },

        /**

         * load next post

         */

        loadNextPost: function(event) {

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

                beforeSend: function() {

                    view.blockUi.block($target);

                },

                success: function(res) {

                    view.blockUi.unblock();

                    if (res.success) {

                        // append content to view

                        $('#single-more-place').append(res.content);

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

                                bottom : 120

                            }

                        });

                        // update next post if load is next

                        if (view.load == 'next') {

                            view.next_post = res.next;

                        }

                        // update prev post if load is prev

                        if (view.load == 'prev') {

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

                            score: function() {

                                return $(this).attr('data-score');

                            },

                            hints: raty.hint

                        });

                    } else {

                        $target.remove();

                    }

                }

            });

        },

        // bind inview to place item to update url

        bindInview: function() {

            var view = this;

            this.$('.single-place-wrapper').bind('inview', view.updateUrl);

        },

        // update browser url when scroll to an item in group

        updateUrl: function(event, isVisible) {

            var view = this;

            if (!isVisible) {

                this.inViewVisible = false;

                return;

            }

            var $target = $(event.currentTarget),

                id = $target.attr('data-id'),

                model = this.collection.get(parseInt(id));

            document.title = model.get('pageTitle');

            window.history.pushState({

                "html": $target.html(),

                "pageTitle": model.get('pageTitle')

            }, "", model.get('link'));

        }

    });

    /**

     * model favorite

     */

    Models.Favorite = Backbone.Model.extend({

        action: 'ae-sync-favorite',

        initialize: function() {}

    });

})(jQuery, AE.Views, AE.Models, AE.Collections);

function dopbspGetUrlVars(){
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}