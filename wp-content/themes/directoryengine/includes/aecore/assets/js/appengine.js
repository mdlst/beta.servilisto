// declare everything inside this object
window.AE = window.AE || {};
(function (AE, $, Backbone) {
    AE.Models = AE.Models || {};
    AE.Collections = AE.Collections || {};
    AE.Views = AE.Views || {};
    AE.Routers = AE.Routers || {};
    // the pub/sub object for managing event throughout the app
    AE.pubsub = AE.pubsub || {};
    _.extend(AE.pubsub, Backbone.Events);
    AE.globals = ae_globals;
    /**
     * override backbone sync function
     */
    Backbone.Model.prototype.sync = function (method, model, options) {
        var data = model.attributes;
        data.action = model.action || 'ae-sync';
        switch (method) {
            case 'create':
                data.method = 'create';
                break;
            case 'update':
                data.method = 'update';
                break;
            case 'delete':
                data.method = 'remove';
                break;
            case 'read':
                data.method = 'read';
                break;
        }
        var ajaxParams = {
            type: 'POST',
            dataType: 'json',
            data: data,
            url: ae_globals.ajaxURL,
            contentType: 'application/x-www-form-urlencoded;charset=UTF-8'
        };
        ajaxParams = _.extend(ajaxParams, options);
        if (options.beforeSend !== 'undefined') ajaxParams.beforeSend = options.beforeSend;
        ajaxParams.success = function (result, status, jqXHR) {
            AE.pubsub.trigger('ae:success', result, status, jqXHR);
            options.success(result, status, jqXHR);
        };
        ajaxParams.error = function (jqXHR, status, errorThrown) {
            AE.pubsub.trigger('ae:error', jqXHR, status, errorThrown);
            options.error(jqXHR, status, errorThrown);
        };
        $.ajax(ajaxParams);
    };
    /**
     * override backbone collection sync
     */
    Backbone.Collection.prototype.sync = function (method, collection, options) {
        var ajaxParams = {
            type: 'POST',
            dataType: 'json',
            data: {},
            url: ae_globals.ajaxURL,
            contentType: 'application/x-www-form-urlencoded;charset=UTF-8'
        };
        ajaxParams.data = _.extend(ajaxParams.data, options.data);
        if (typeof collection.action !== 'undefined') {
            ajaxParams.data.action = collection.action;
        }
        /**
         * add beforsend function
         */
        if (options.beforeSend !== 'undefined') ajaxParams.beforeSend = options.beforeSend;
        /**
         * success function
         */
        ajaxParams.success = function (result, status, jqXHR) {
            AE.pubsub.trigger('ae:success', result, status, jqXHR);
            options.success(result, status, jqXHR);
        };
        ajaxParams.error = function (jqXHR, status, errorThrown) {
            AE.pubsub.trigger('ae:error', jqXHR, status, errorThrown);
            options.error(jqXHR, status, errorThrown);
        };
        $.ajax(ajaxParams);
    };
    /**
     * override backbone model parse function
     */
    Backbone.Model.prototype.parse = function (result) {
        if (_.isObject(result.data)) {
            return result.data;
        } else {
            return result;
        }
    };
    /**
     * override backbone model parse function
     */
    Backbone.Collection.prototype.parse = function (result) {
        if (_.isObject(result) && _.isObject(result.data)) {
            return result.data;
        } else {
            return [];
        }
    };
    // create a shorthand for our pubsub
})(window.AE, jQuery, Backbone);

// override underscore template tag
_.templateSettings = {
    evaluate: /\<\#(.+?)\#\>/g,
    interpolate: /\{\{=(.+?)\}\}/g,
    escape: /\{\{-(.+?)\}\}/g
};

// build basic view
(function (AE, $, Backbone, Views, Models, Collections) {
    // create a shorthand for the params used in most ajax request
    AE.ajaxParams = {
        type: 'POST',
        dataType: 'json',
        url: AE.globals.ajaxURL,
        contentType: 'application/x-www-form-urlencoded;charset=UTF-8'
    };
    var ajaxParams = AE.ajaxParams;
    /**
     * loading effec view
     */
    AE.Views.LoadingEffect = Backbone.View.extend({
        initialize: function () {
        },
        render: function () {
            this.$el.html(AE.globals.loadingImg);
            return this;
        },
        finish: function () {
            this.$el.html(AE.globals.loadingFinish);
            var view = this;
            setTimeout(function () {
                view.$el.fadeOut(500, function () {
                    $(this).remove();
                });
            }, 1000);
        },
        remove: function () {
            view.$el.remove();
        }
    });
    /**
     * blockui view
     * block an Dom Element with loading image
     */
    AE.Views.BlockUi = Backbone.View.extend({
        defaults: {
            image: AE.globals.imgURL + 'loading.gif',
            opacity: '0.6',
            background_position: 'center center',
            background_color: '#FFFFFF'
        },
        isLoading: false,
        initialize: function (options) {
            //var defaults = _.clone(this.defaults);
            options = _.extend(_.clone(this.defaults), options);
            var loadingImg = options.image;
            this.overlay = $('<div class="loading-blur loading"><div class="loading-overlay"></div><div class="loading-img"></div></div>');
            this.overlay.find('.loading-img').css({
                'background-image': 'url(' + options.image + ')',
                'background-position': options.background_position
            });
            this.overlay.find('.loading-overlay').css({
                'opacity': options.opacity,
                'filter': 'alpha(opacity=' + options.opacity * 100 + ')',
                'background-color': options.background_color
            });
            this.$el.html(this.overlay);
            this.isLoading = false;
        },
        render: function () {
            this.$el.html(this.overlay);
            return this;
        },
        block: function (element, caption) {
            var $ele = $(element);
            // if ( $ele.css('position') !== 'absolute' || $ele.css('position') !== 'relative'){
            //         $ele.css('position', 'relative');
            // }
            this.overlay.css({
                'position': 'absolute',
                'z-index': 20000,
                'top': $ele.offset().top,
                'left': $ele.offset().left,
                'width': $ele.outerWidth(),
                'height': $ele.outerHeight()
            });
            this.isLoading = true;
            this.render().$el.show().appendTo($('body'));
            if (caption) {
                this.$el.find('.loading-img').text(caption);
            }
        },
        setMessage: function (message) {
            if (this.$el) {
                this.$el.find('.loading-img').text(message);
            }
        },
        unblock: function () {
            this.$el.remove();
            this.isLoading = false;
        },
        finish: function () {
            this.$el.fadeOut(500, function () {
                $(this).remove();
            });
            this.isLoading = false;
        }
    });
    AE.Views.LoadingButton = Backbone.View.extend({
        dotCount: 3,
        isLoading: false,
        initialize: function () {
            if (this.$el.length <= 0) return false;
            var dom = this.$el[0];
            //if ( this.$el[0].tagName != 'BUTTON' && (this.$el[0].tagName != 'INPUT') ) return false;
            if (this.$el[0].tagName == 'INPUT') {
                this.title = this.$el.val();
            } else {
                this.title = this.$el.html();
            }
            this.isLoading = false;
        },
        loopFunc: function (view) {
            var dots = '';
            for (i = 0; i < view.dotCount; i++) dots = dots + '.';
            view.dotCount = (view.dotCount + 1) % 3;
            view.setTitle(AE.globals.loading + dots);
        },
        setTitle: function (title) {
            if (this.$el[0].tagName === 'INPUT') {
                this.$el.val(title);
            } else {
                this.$el.html(title);
            }
        },
        loading: function () {
            //if ( this.$el[0].tagName != 'BUTTON' && this.$el[0].tagName != 'A' && (this.$el[0].tagName != 'INPUT') ) return false;
            this.setTitle(AE.globals.loading);
            this.$el.addClass('disabled');
            var view = this;
            view.isLoading = true;
            view.dots = '...';
            view.setTitle(AE.globals.loading + view.dots);
            this.loop = setInterval(function () {
                if (view.dots === '...') view.dots = '';
                else if (view.dots === '..') view.dots = '...';
                else if (view.dots === '.') view.dots = '..';
                else view.dots = '.';
                view.setTitle(AE.globals.loading + view.dots);
            }, 500);
        },
        finish: function () {
            var dom = this.$el[0];
            this.isLoading = false;
            clearInterval(this.loop);
            this.setTitle(this.title);
            this.$el.removeClass('disabled');
        }
    });
    // View: Modal Box
    AE.Views.Modal_Box = Backbone.View.extend({
        defaults: {
            top: 100,
            overlay: 0.5
        },
        $overlay: null,
        initialize: function () {
            // bind all functions of this object to itself
            //_.bindAll(this.openModal);
            // update custom options if having any
            this.options = $.extend(this.defaults, this.options);
        },
        /**
         * open modal
         */
        openModal: function () {
            var view = this;
            this.$el.modal('show');
        },
        /**
         * close modal
         */
        closeModal: function (time, callback) {
            var modal = this;
            modal.$el.modal('hide');
            return false;
        },
        /**
         * add block ui, block loading
         */
        loading: function () {
            if (typeof this.blockUi === 'undefined') {
                this.blockUi = new AE.Views.BlockUi();
            }
            this.blockUi.block(this.$el.find('input[type="submit"]'));
        },
        /**
         * finish ajax
         */
        finish: function () {
            this.blockUi.unblock();
        },
        // trigger pubsub error
        error: function (res) {
            AE.pubsub.trigger('ae:notification', {
                msg: res.msg,
                notice_type: 'error',
            });
        },
        // trigger pubsub notification success
        success: function (res) {
            AE.pubsub.trigger('ae:notification', {
                msg: res.msg,
                notice_type: 'success',
            });
        }
    });
    plupload.addFileFilter('resolution_limit', function (limit, file, cb) {
        var self = this;
        var reader = new FileReader();
        var image = new Image();
        var blodFile = file.getNative();
        reader.readAsDataURL(blodFile);
        reader.onload = function (_file) {
            image.src = _file.target.result;
            image.onload = function () {
                var width = this.width;
                var height = this.height;
                var isValid = true;
                var errorMesage = '';
                if (typeof limit.min != 'undefined') {
                    if (typeof limit.min.width != 'undefined') {
                        if (width < limit.min.width) {
                            isValid = false;
                            errorMesage = plupload.translate('The width of the image must be greater than ') + limit.min.width + 'px';
                        }
                    }
                    if (typeof limit.min.height != 'undefined') {
                        if (height < limit.min.height) {
                            isValid = false;
                            errorMesage = plupload.translate('The height of the image must be greater than ') + limit.min.height + 'px';
                        }
                    }
                }

                if (typeof  limit.max != 'undefined') {
                    if (typeof limit.max.width != 'undefined') {
                        if (width > limit.max.width) {
                            isValid = false;
                            errorMesage = plupload.translate('The height of the image must be less than ') + limit.max.width + 'px';
                        }
                    }
                    if (typeof limit.max.height != 'undefined') {
                        if (height > limit.max.height) {
                            isValid = false;
                            errorMesage = plupload.translate('The height of the image must be less than ') + limit.max.height + 'px';
                        }
                    }
                }

                if (!isValid) {
                    self.trigger('Error', {
                        code: plupload.IMAGE_DIMENSIONS_ERROR,
                        message: errorMesage,
                        file: file
                    });

                }
                cb(isValid);
            };

            image.onerror = function (e) {
                console.log(e);
                self.trigger('Error', {
                    code: plupload.IMAGE_FORMAT_ERROR,
                    message: plupload.translate('Image format error.'),
                    file: file
                });
                cb(false);
            };
        };
    });
    /*
     /*AE File uploader
     */
    AE.Views.File_Uploader = Backbone.View.extend({
        //options            : [],
        events: {
            'click .btn-remove-option-image': 'removeOptionImage'
        },
        initialize: function (options) {
            _.bindAll(this, 'onFileUploaded', 'onFileAdded', 'onFilesBeforeSend', 'onUploadComplete', 'onUploadErrorResponse');
            this.options = options;
            this.uploaderID = (this.options.uploaderID) ? this.options.uploaderID : 'et_uploader';

            this.processEl = $("#" + this.uploaderID + '_progress');
            this.config = {
                runtimes: 'html5,gears,flash,silverlight,browserplus,html4',
                multiple_queues: true,
                multipart: true,
                urlstream_upload: true,
                multi_selection: false,
                upload_later: false,
                container: this.uploaderID + '_container',
                drop_element: this.uploaderID + '_container',
                browse_button: this.uploaderID + '_browse_button',
                remove_button: this.uploaderID + '_remove_button',
                thumbnail: this.uploaderID + '_thumbnail',
                thumbsize: 'thumbnail',
                file_data_name: this.uploaderID,
                max_file_size: '1mb',
                //chunk_size                         : '1mb',
                // this filters is an array so if we declare it when init Uploader View, this filters will be replaced instead of extend
                filters: {
                    mime_types: [{
                        title: 'Image Files',
                        extensions: (this.options.extensions) ? this.options.extensions : 'pdf,jpg,jpeg,gif,png,ico'
                    }]
                },
                multipart_params: {
                    fileID: this.uploaderID,
                    action: 'et-upload-image'
                },
                onProgress: function (up, file) {
                    if (this.processEl) {
                        this.processEl.text(file.percent + "%");
                    }
                },
                // prevent_duplicates: true,
                Error: function (up, error) {
                    alert(error.message);
                }
            };

            if (typeof this.options.filters != 'undefined') {
                jQuery.extend(true, this.options.filters, this.config.filters);
            }
            jQuery.extend(true, this.config, AE.globals.plupload_config, this.options);
            this.controller = new plupload.Uploader(this.config);
            this.controller.init();
            this.controller.bind('FileUploaded', this.onFileUploaded);
            this.controller.bind('FilesAdded', this.onFileAdded);
            this.controller.bind('BeforeUpload', this.onFilesBeforeSend);
            this.bind('UploadSuccessfully', this.onUploadComplete);
            this.bind('UploadErrorResponse', this.onUploadErrorResponse);
            if (typeof this.controller.settings.onProgress === 'function') {
                this.controller.bind('UploadProgress', this.controller.settings.onProgress);
            }
            if (typeof this.controller.settings.onError === 'function') {
                this.controller.bind('Error', this.controller.settings.onError);
            } else {
                this.controller.bind('Error', this.errorLog);
            }
            if (typeof this.controller.settings.cbRemoved === 'function') {
                this.controller.bind('FilesRemoved', this.controller.settings.cbRemoved);
            }
            this.blockUi = new AE.Views.BlockUi();
            if (this.$el.find('#' + this.config.remove_button).length > 0 && this.$el.find('#' + this.config.remove_button).attr('data-id') != '') {
                this.$el.find('#' + this.config.remove_button).show();
            }
        },
        getControler: function () {
            return this.controller;
        },
        errorLog: function (e, b) {
            /* 31_10_2016 Id : 5 S*/
            alert(b.message);
            /* 31_10_2016 Id : 5 E*/
        },
        onFileAdded: function (up, files) {
            if (typeof this.controller.settings.cbAdded === 'function') {
                this.controller.settings.cbAdded(up, files);
            }
            if (!this.controller.settings.upload_later) {
                up.refresh();
                up.start();
            }
        },
        onFileUploaded: function (up, file, res) {
            res = $.parseJSON(res.response);
            if (typeof this.controller.settings.cbUploaded === 'function') {
                this.controller.settings.cbUploaded(up, file, res);
            }
            if (res.success) {
                this.updateThumbnail(res.data);
                this.trigger('UploadSuccessfully', res);
            }
            else {
                this.trigger('UploadErrorResponse', res);
            }
        },
        updateThumbnail: function (res) {
            var that = this,
                $thumb_div = this.$('#' + this.controller.settings['thumbnail']),
                $existing_imgs, thumbsize;
            if ($thumb_div.length > 0) {
                $existing_imgs = $thumb_div.find('img'),
                    thumbsize = this.controller.settings['thumbsize'];
                if ($existing_imgs.length > 0) {
                    $existing_imgs.fadeOut(100, function () {
                        $existing_imgs.remove();
                        if (_.isArray(res[thumbsize])) {
                            that.insertThumb(res[thumbsize][0], $thumb_div);
                        }
                    });
                } else if (_.isArray(res[thumbsize])) {
                    this.insertThumb(res[thumbsize][0], $thumb_div);
                }
            }
        },
        insertThumb: function (src, target) {
            $('<img>').attr({
                'id': this.uploaderID + '_thumb',
                'src': src
            })
            // .hide()
                .appendTo(target).fadeIn(300);
        },
        updateConfig: function (options) {
            if ('updateThumbnail' in options && 'data' in options) {
                this.updateThumbnail(options.data);
            }
            $.extend(true, this.controller.settings, options);
            this.controller.refresh();
        },
        onFilesBeforeSend: function () {
            if ('beforeSend' in this.options && typeof this.options.beforeSend === 'function') {
                this.options.beforeSend(this.$el);
            }
        },
        onUploadComplete: function (res) {
            var view = this;
            if ('success' in this.options && typeof this.options.success === 'function') {
                this.options.success(res);
                $('#' + this.config.remove_button).attr('data-id', res.data.attach_id);
                $('#' + this.config.remove_button).show();
            }
        },
        onUploadErrorResponse: function (res) {
            if ('errorResponsed' in this.options && typeof this.options.errorResponsed === 'function') {
                this.options.errorResponsed(res);
            }
            else {
                if (res.hasOwnProperty('msg')) {
                    console.log(res);
                    alert(res.msg);
                }
            }
        },
        removeOptionImage: function (e) {
            var view = this;
            e.preventDefault();
            $target = $(e.currentTarget);
            var param = {
                url: ae_globals.ajaxURL,
                type: 'POST',
                data: {
                    ID: $target.attr('data-id'),
                    name: $target.attr('data-name'),
                    action: 'ae-remove-option-image',
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (resp) {
                    if (resp.success) {
                        view.$el.find('.image img').attr('src', '');
                        AE.pubsub.trigger('ae:notification', {
                            msg: resp.msg,
                            notice_type: 'success'
                        });
                        $target.hide();
                    }
                },
                complete: function () {
                    view.blockUi.unblock();
                }
            };
            $.ajax(param);
        }
    });
    /**
     * USER VIEW
     */
    /**
     * User item
     */
    AE.Views.UserItem = Backbone.View.extend({
        tagName: 'li',
        className: 'et-member',
        template: '',
        /**
         * this view content model user
         */
        model: [],
        /**
         * initialize view
         */
        events: {
            /**
             * trigger action on model, link should contain attribute data-name and data-value
             * name value pair for model example model.set(a.attr('data-name') , a.attr('data-value')) then a.save();
             */
            'click a.action': 'acting',
            /**
             * input regular change update model
             */
            'change .regular-input': 'change',
            /**
             * ban & unbar a user
             */
            'click .et-act-ban': 'renderBanForm',
            'click .et-act-unban': 'unbanUser',
            /**
             * change user role, this option should be use in admin setting
             */
            // 'change select.role-change' : 'changeRole'
        },
        /**
         * initialize view
         */
        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
            /**
             * can override template by change template content, but should keep the template id
             */
            if ($('#user-item-template').length > 0) {
                this.template = _.template($('#user-item-template').html());
            }
            this.blockUi = new AE.Views.BlockUi();
        },
        /**
         * render view fill template with model data
         */
        render: function () {
            if (this.template) {
                this.$el.html(this.template(this.model.toJSON()));
            }
            return this;
        },
        /**
         * action on model
         */
        acting: function (e) {
            e.preventDefault();
            var target = $(e.currentTarget),
                action = target.attr('data-act'),
                view = this;
            if (action == "confirm") {
                this.model.save('register_status', '', {
                    beforeSend: function () {
                        view.blockUi.block(view.$el);
                    },
                    success: function (result, status, xhr) {
                        view.blockUi.unblock();
                        view.$el.find('a.et-act-confirm').fadeOut();
                    }
                });
            }
        },
        /**
         * update user role
         */
        change: function (e) {
            var $target = $(e.currentTarget);
            name = $target.attr('name'),
                val = $target.val(),
                view = this;
            this.model.save(name, val, {
                beforeSend: function () {
                    view.blockUi.block(view.$el);
                },
                success: function (result, status, xhr) {
                    view.blockUi.unblock();
                }
            });
        },
        /**
         * render ban modal
         */
        renderBanForm: function (e) {
            var form = $('#form_ban_user');
            $('#ban_modal .modal-header .display-name').text(this.model.get('display_name'));
            $('#form_ban_user input[name=id]').val(this.model.get('id'));
        },

        /**
         * unban a user
         */
        unbanUser: function (e) {
            var view = this;

            var params = {
                url: ae_globals.ajaxURL,
                type: 'POST',
                data: {
                    ID: this.model.get('id'),
                    action: 'ae-sync-user',
                    method: 'update',
                    do: 'unban',
                },
                beforeSend: function () {
                    view.blockUi.block(view.$el);
                },
                success: function (resp) {
                    if (resp.success) {
                        // Reset model
                        view.model.set(resp.data);
                        // Re-render model
                        view.render();
                    }
                },
                complete: function () {
                    view.blockUi.unblock();
                }
            };

            $.ajax(params);

            return false;
        }
    });
    /**
     * view of users list
     */
    AE.Views.ListUsers = Backbone.View.extend({});
    // USER VIEW
    /**
     * POST VIEW
     */
    /**
     * view of post item
     */
    // AE.Views.PostItem = Backbone.Marionette.ItemView.extend({});
    /**
     * post item extend Marionette item view
     */
    Views.PostItem = Backbone.Marionette.ItemView.extend({
        // view html tag
        tagName: "li",
        // view class
        className: 'col-md-3 col-xs-6 place-item ae-item',
        /**
         * view events
         */
        events: {
            // user click on action button such as edit, archive, reject
            'click .action': 'acting'
        },
        /**
         * list all model events
         */
        modelEvents: {
            "change": "modelChanged",
            "change:post_status": "statusChange"
        },
        /**
         * model in view change callback function
         * update model data to database
         */
        modelChanged: function (model) {
            this.render();
        },
        statusChange: function (model) {
            AE.pubsub.trigger('ae:model:statuschange', model);
        },
        /**
         * catch event on view render bind raty for star rating
         */
        // onRender: function() {
        //     var view = this;
        //     this.$('.rate-it').raty({
        //         half: true,
        //         score: view.model.get('rating_score'),
        //         readOnly: true,
        //         hints: raty.hint
        //     });
        // },
        /**
         * event callback when user click on action button
         * edit
         * archive
         * reject
         * toggleFeatured
         * approve
         */
        acting: function (e) {
            e.preventDefault();
            var target = $(e.currentTarget),
                action = target.attr('data-action'),
                view = this;
            switch (action) {
                case 'edit':
                    //trigger an event will be catch by AE.App to open modal edit
                    AE.pubsub.trigger('ae:model:onEdit', this.model);
                    $('#place_category').trigger('render.drop');  //Miguel. Fix. Hace que se muestren las categorias seleccionadas del drop.js
                    break;
                case 'reject':
                    //trigger an event will be catch by AE.App to open modal reject
                    AE.pubsub.trigger('ae:model:onReject', this.model);
                    break;
                case 'archive':
                    if (confirm(ae_globals.confirm_message)) {
                        // archive a model
                        this.model.set('archive', 1);
                        this.model.set('alguna_opcion_date_seleccionada', 1);

                        this.model.save('archive', '1', {
                            beforeSend: function () {
                                view.blockItem();
                            },
                            success: function (result, res, xhr) {
                                AE.pubsub.trigger('ae:post:archiveSuccess', result, res, xhr);
                                view.unblockItem();
                            }
                        });
                    } else {
                        return false;
                    }
                    break;
                case 'toggleFeature':
                    // toggle featured
                    this.model.save('et_featured', 1);
                    break;
                case 'approve':
                    // publish a model
                    this.model.save('publish', '1', {
                        beforeSend: function () {
                            view.blockItem();
                        },
                        success: function (result, res, xhr) {
                            view.triggerMethod("before:approve", view, res);
                            view.unblockItem();
                        }
                    });
                    break;
                case 'delete':
                    if (confirm(ae_globals.confirm_message)) {
                        // archive a model
                        this.model.save('delete', '1', {
                            beforeSend: function () {
                                view.blockItem();
                            },
                            success: function (result, res, xhr) {
                                view.unblockItem();
                                if (res.success) {
                                    view.model.destroy();
                                    location.reload();
                                }
                            }
                        });
                    }
                    break;
                default:
                    //trigger an event will be catch by AE.App to open modal edit
                    AE.pubsub.trigger('ae:model:on' + action, this.model);
                    break;
            }
        },
        /**
         * load block item
         */
        blockItem: function () {
            if (typeof this.blockUi === 'undefined') {
                this.blockUi = new Views.BlockUi();
            }
            this.blockUi.block(this.$el);
        },
        /**
         * unblock loading
         */
        unblockItem: function () {
            this.blockUi.unblock();
        }
    });
    /**
     * view of posts list
     */
    Views.ListPost = Backbone.Marionette.CollectionView.extend({
        // tagName: 'ul',
        // itemView: PostItem,
        // itemClass: 'li'
        constructor: function (options) {
            var view = this;
            Marionette.CollectionView.prototype.constructor.apply(this, arguments);
            if (typeof this.collection !== 'undefined') {
                this.collection.each(function (pack, index, col) {
                    var el = view.$('.' + view.itemClass).eq(index);
                    itemView = view.getItemView(pack);
                    // this view is about to be added
                    view.triggerMethod("before:item:added", view);
                    view.children.add(new itemView({
                        el: el,
                        model: pack
                    }));
                    // this view was added
                    view.triggerMethod("after:item:added", view);
                });
            }
        }
    });
    // view control composite listviewitem
    Views.Index = Backbone.View.extend({
        initialize: function (options) {
            // bind event to view
            _.bindAll(this, 'onModelChange');
            var view = this;
            // view collections list
            view.collections = {};
            // list of listViewItem
            view.list = {};
            this.options = _.extend(this, options);
            /**
             * init list view control
             */
            // list of collection associate with list container data list
            if (this.pending.length > 0) {
                view.list['pending'] = new ListView({
                    itemView: PostItem,
                    collection: this.pending,
                    el: '#pending-places',
                    thumb: $('#pending-places').attr('data-thumb')
                });
            }
            if (this.publish.length > 0) {
                view.list['publish'] = new ListView({
                    itemView: PostItem,
                    collection: this.publish,
                    el: '#publish-places',
                    thumb: $('#publish-places').attr('data-thumb')
                });
            }
            // catch event when a model change status
            AE.pubsub.on('ae:model:statuschange', view.onModelChange, this);
        },
        /**
         * this function trigger when a model change status
         * call this function to add model to the list associate with its status
         */
        onModelChange: function (model) {
            var status = model.get('post_status'),
                view = this;
            // remove model from pending collection
            if (status == 'publish' || status == 'reject' || status == 'trash') {
                if (typeof view.pending !== 'undefined') {
                    view.pending.remove(model);
                    view.publish.add(model);
                }
            }
            // remove model from publish collection
            if (status == 'archive') {
                if (typeof view.publish !== 'undefined') {
                    view.publish.remove(model);
                }
            }
        }
    });
    /*
     *
     * S K I L L  I T E M  V I E W S
     *
     */
    Views.Skill_Item = Backbone.View.extend({
        'tagName': 'li',
        'className': 'skill-item',
        events: {
            'click a.delete': 'deleteItem'
        },
        //template  : _.template( $('#tag_item').html() ),
        initialize: function () {
            /* get skill item template */
            if ($('#skill_item').length > 0) {
                this.template = _.template($('#skill_item').html());
            } else {
                alert('Hi dev, you forgot add item view skill_item');
            }
            this.model.on('remove', this.removeView, this);
            this.model.on('destroy', this.removeView, this);
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
        removeView: function () {
            this.$el.fadeOut('normal', function () {
                $(this).remove();
            });
        },
        deleteItem: function (event) {
            event.preventDefault();
            this.model.destroy();
        }
    });
    /*
     *
     * S K I L L S  C O N T R O L  V I E W S
     *
     */
    Views.Skill_Control = Backbone.View.extend({
        // el: '.skill-control',
        max_length: 5,
        events: {
            'keypress input.skill': 'onAddSkill',
            'click .add-skill': 'onClickAddSkill',
        },
        initialize: function (options) {
            _.bindAll(this, 'addOne', 'removeOne');
            var view = this;
            this.options = _.extend(this, options);
            if (typeof this.options.name === 'undefined') {
                this.options.name = 'skill';
            }
            /**
             * skills list container
             */
            if (typeof this.collection === "undefined") {
                this.collection = new Collections.Skills();
            }
            // this.options = _.extend(this, options);
            this.skill_list = this.$('ul.skills-list');
            view.skills = {};
            this.$el.find('input.skill').typeahead({
                minLength: 0,
                items: 99,
                source: function (query, process) {
                    if (view.skills.length > 0) return view.skills;
                    return $.getJSON(ae_globals.ajaxURL, {
                        action: 'fre_get_skills',
                        query: query
                    }, function (data) {
                        view.skills = data;
                        return process(data);
                    });
                },
                updater: function (item) {
                    var filter = $('#filter_skill').val();
                    view.addSkill(item);
                }
            });
            this.collection.on('add', this.addOne, this);
            this.collection.on('remove', this.removeOne, this);
            if (typeof this.model !== 'undefined') {
                this.render();
            }
        },
        // set a model to view
        setModel: function (model) {
            this.model = model;
            this.render();
        },
        render: function () {
            var tax_input = this.model.get('tax_input'),
                skills = (typeof tax_input !== 'undefined') ? tax_input[this.options.name] : [],
                skill = [];
            this.collection.reset();
            for (var i = skills.length - 1; i >= 0; i--) {
                var model = new Models.Skill(skills[i]);
                this.collection.add(model);
            }
            ;
        },
        /*add model to skill callback
         *  handle to render item view and append to list
         */
        addOne: function (model) {
            var skillView = new Views.Skill_Item({
                model: model
            });
            this.model.set(this.options.name, this.collection.toJSON());
            this.skill_list.append(skillView.render().$el);
        },
        // remove skill from collection
        removeOne: function (model) {
            this.model.set(this.options.name, this.collection.toJSON());
        },
        /**
         * add tag to modal, render tagItem base on in put tag
         */
        addSkill: function (skill) {
            // this.model.set('skill', []);
            var duplicates = this.skill_list.find('input[type=hidden][value="' + skill + '"]'),
                count = this.skill_list.find('li');
            var max_length = this.max_length;
            if (typeof this.options.max_length !== 'undefined') {
                max_length = parseInt(this.options.max_length);
            }
            if (duplicates.length == 0 && skill != '' && count.length < max_length) {
                var data = {
                    'name': skill
                };
                this.collection.add(new Models.Skill(data));
                $('input#skill').val('');
            }
        },
        onClickAddSkill: function (e) {
            e.preventDefault();
            var val = this.$("input#skill").val();
            if (val) this.addSkill(val);
        },
        /**
         * catch event user enter in skill input, call function addSkill to render tag item
         */
        onAddSkill: function (event) {
            var val = $(event.currentTarget).val();
            var filter = $('#filter_skill').val();
            if (event.which == 13) {
                this.addSkill(val);
            }
            return event.which != 13;
        },
        /**
         * list profile page filter follow skill
         */
        filterSkill: function () {
        }
    });
    FilterRouter = Backbone.Router.extend({
        routes: {
            '!filter/:query': 'filter'
        },
        filter: function (query) {
            this.trigger('filter', query);
        }
    });
    Views.BlockControl = Backbone.Marionette.View.extend({
        initialize: function (options) {
            _.bindAll(this, 'addPost', 'onModelChange');
            var view = this;
            this.page = 1;
            this.options = _.extend(this, options);
            this.blockUi = new Views.BlockUi();
            if (this.$('.ae_query').length > 0) {
                this.query = JSON.parse(this.$('.ae_query').html());
            } else {
                this.$('.paginations').remove();
            }
            // bind event add to collection
            this.collection.on('add', this.addPost, this);
            // bind event when model change
            AE.pubsub.on('ae:model:statuschange', this.onModelChange, this);
            // init grid view
            this.grid = (options.grid) ? options.grid : 'grid';
            this.searchDebounce = _.debounce(this.onSearchDebounce, 500);
            if (this.$('.skill-control').length > 0 && this.$('.skill_filter').val() != '') {
                // init collection skill
                this.post = new Models.Post();
                this.skill_view = new Views.Skill_Control({
                    collection: this.skills,
                    model: this.post,
                    el: view.$('.skill-control')
                });
                // bind event collection skill change, add, remove filter
                this.skills.on('add', this.filterSkill, this);
                this.skills.on('remove', this.filterSkill, this);
            }

            view.triggerMethod("after:init");
        },
        filterSkill: function () {
            var skill = this.skills.toJSON();
            var view = this;
            var input_skill = $('.skill');
            skill = _.map(skill, function (element) {
                return element['name'];
            });
            view.query['skill'] = skill;
            view.page = 1;
            view.fetch(input_skill);
        },
        events: {
            // ajax load more
            'click a.load-more-post': 'loadMore',
            // select a page in pagination list
            'click .paginations a.page-numbers': 'selectPage',
            // previous page
            'click .paginations a.prev': 'prev',
            // next page
            'click .paginations a.next': 'next',
            // filter
            'change select ': 'selectFilter',
            // order post list by date/rating
            'click a.orderby': 'order',
            // filter post_status
            'click a.click-type': 'clickType',
            // switch view between grid and list
            'click .icon-view': 'changeView',
            // search post
            'keyup input.search': 'search',
            // Slider range drag
            'slideStop .slider-ranger': 'filterRange',
            'change .slider-ranger': 'filterRange',
            // Change date filer
            'changeDate .datepicker': 'filterDate',
            // Fefresh post
            'click .refresh-post': 'Refresh',
        },
        /**
         * handle on change search field
         */
        search: function (event) {
            var target = $(event.currentTarget);
            this.searchDebounce(target);
        },
        /**
         * handle ajax search
         */
        onSearchDebounce: function ($target) {
            var name = $target.attr('name'),
                view = this;
            if (name !== 'undefined') {
                view.query[name] = $target.val();
                view.page = 1;
                // fetch page
                view.fetch($target);
            }
        },
        /**
         * catch event add post to collection and add current page to model
         */
        addPost: function (post, col, options) {
            post.set('page', this.page);
        },
        /**
         * load more places
         */
        loadMore: function (e) {
            e.preventDefault();
            var view = this,
                $target = $(e.currentTarget);
            view.page++;
            // collection fetch
            view.collection.fetch({
                remove: false,
                data: {
                    query: view.query,
                    page: view.page,
                    paged: view.page,
                    action: 'ae-fetch-posts',
                    paginate: true,
                    thumbnail: view.thumbnail,
                },
                // get the thumbnail size of post and send to server
                thumbnail: view.thumbnail,
                beforeSend: function () {
                    view.blockUi.block($target);
                    view.triggerMethod("before:loadMore");
                },
                success: function (result, res, xhr) {
                    view.blockUi.unblock();
                    view.$('.paginations-wrapper').html(res.paginate);
                    AE.pubsub.trigger('aeBlockControl:after:loadMore', result, res);
                    if (res.max_num_pages == view.page || !res.success) {
                        $target.parents('.paginations').hide();
                        $target.remove();
                    }
                    view.switchTo();
                    view.triggerMethod("after:loadMore", result, res);
                }
            });
        },
        selectFilter: function (event) {
            /* 07_11_2016 Id : 2 S*/
            event.stopImmediatePropagation();
            /* 07_11_2016 Id : 2 E*/
            var $target = $(event.currentTarget),
                name = $target.attr('name'),
                view = this;
            if (name !== 'undefined') {
                view.query[name] = $target.val();
                view.page = 1;
                // fetch page
                view.fetch($target);
            }
        },
        clickType: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                name = $target.attr('data-name'),
                type = $target.attr('data-type'),
                view = this;
            if ($target.hasClass('active')) return;

            if (name !== 'undefined') {
                view.$el.find('.click-type').parent().removeClass('active');
                $target.parent().addClass('active');
                /**
                 * set post_status arg to query
                 */
                view.query[name] = type;
                view.page = 1;
                //fetch post
                view.fetch($target);
            }
        },
        order: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                name = $target.attr('data-sort'),
                theme = $target.attr('data-theme'),
                view = this;
            if (name !== 'undefined') {
                view.$('.orderby').removeClass('active');
                $target.addClass('active');

                /* 18_10_2016 Id : 1 S*/
                if (name == "hourly_rate1") {
                    if ($target.next('i').hasClass('fa-sort-numeric-desc')) {
                        view.query['extra'] = 'ASC';
                        $target.next('i').removeClass('fa-sort-numeric-desc').addClass('fa-sort-numeric-asc');
                    }
                    else {
                        view.query['extra'] = 'DESC';
                        $target.next('i').removeClass('fa-sort-numeric-asc').addClass('fa-sort-numeric-desc');
                    }
                }
                /* 18_10_2016 Id : 1 S*/

                /**
                 * set orderby arg to query
                 */
                view.query['orderby'] = name;
                //For DE theme
                if (theme == "de") {
                    var meta_key = $target.attr('data-meta-key'),
                        random = $target.attr('data-random'),
                        post_in = $target.attr('data-post-in');
                    if (meta_key === 'latest')
                        view.query['meta_key'] = '';
                    else if (meta_key === 'rating_score') {
                        view.query['orderby'] = meta_key;
                        view.query['meta_key'] = meta_key;
                    }
                    if (post_in !== "")
                        view.query['post__in'] = post_in;
                    view.query['order'] = "DESC";
                }
                view.page = 1;
                // fetch post
                view.fetch($target);
            }
        },
        /**
         * toggle view between grid and list
         */
        changeView: function (event) {
            var $target = $(event.currentTarget),
                view = this;
            // return if target is active
            if ($target.hasClass('active')) return;
            // add class active to current targets
            this.$('.icon-view').removeClass('active');
            $target.addClass('active');
            // update view grid
            if ($target.hasClass('grid-style')) {
                view.grid = 'grid';
            } else {
                view.grid = 'list';
            }
            // switch view
            view.switchTo();
            view.triggerMethod("after:changeView", $target);
        },
        /**
         * filer range for budget
         */
        filterRange: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                name = $target.attr('name');
            view.query[name] = $target.val();
            view.page = 1;
            view.fetch($target);
        },
        /**
         *
         */
        filterDate: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                name = $target.attr('name');
            view.query[name] = $target.val();
            $(event.currentTarget).datepicker('hide');
            view.fetch($target);
        },
        /**
         * select a page in paginate
         */
        selectPage: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                page = parseInt($target.text().replace(/,/g, '')),
                view = this;
            if ($target.hasClass('current') || $target.hasClass('next') || $target.hasClass('prev')) return;
            view.page = page;
            // fetch posts
            view.fetch($target);
            //scroll to block control id
            $('html, body').animate({
                scrollTop: view.$el.offset().top - 180
            }, 800);
        },
        // prev page
        prev: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this;
            // descrease page
            view.page--;
            // fetch posts
            view.fetch($target);
        },
        // next page
        next: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this;
            // increase page
            view.page = view.page + 1;
            view.fetch($target);
        },
        // fetch post
        fetch: function ($target) {
            var view = this,
                page = view.page;

            view.collection.fetch({
                wait: true,
                remove: true,
                reset: true,
                data: {
                    query: view.query,
                    page: view.page,
                    paged: view.page,
                    paginate: view.query.paginate,
                    thumbnail: view.thumbnail,
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                    view.triggerMethod("before:fetch");
                },
                success: function (result, res, xhr) {
                    view.blockUi.unblock();

                    var address = "";
                    if (res && !res.success) {

                        var html_no_encontrado = '<div class="event-active-wrapper not-result"><div class="col-md-12"><div class="event-wrapper tab-style-event"><h2 class="title-envent">Ups, no hemos encontrado anuncios</h2></div></div></div>';
                        view.$('.paginations-wrapper').html(html_no_encontrado);
                        view.$('.paginations').remove();
                        view.$('.found_post').html(0);
                        view.$('.plural').addClass('hide');
                        view.$('.singular').removeClass('hide');
                        address = "spain";  // para mostrar el mapa con todos los resultados en España
                        var post = "";

                    } else {
                        if (jQuery("#place-list .not-result").length){
                            jQuery("#place-list .not-result").remove();
                        }
                        view.$('.paginations-wrapper').show();
                        view.$('.paginations-wrapper').html(res.paginate);
                        view.$('.replace').html(res.map);
                        //AE.Views.Map.prototype.initialize();
                        $('#place-status').html(res.status);
                        view.$('.found_post').html(res.total);
                        if (res.total > 1) {
                            view.$('.plural').removeClass('hide');
                            view.$('.singular').addClass('hide');
                        } else {
                            view.$('.plural').addClass('hide');
                            view.$('.singular').removeClass('hide');
                        }
                        view.switchTo();

                        address = $('select[name="location"]').val() + ' spain';
                        post = new Models.Post(JSON.parse($('#place_id').html())); // para mostrar todos los resultados

                    }


                    // mostramos mapa

                    GMaps.geocode({
                        region: "es",
                        address: address,
                        componentRestrictions: {country: 'ES'},
                        callback: function (results, status) {
                            if (status === 'OK') {
                                var latlng = results[0].geometry.location;
                                var lat = latlng.lat();
                                var lon = latlng.lng();
                                AE.MapView = new Views.Map({
                                    el: $('body'),
                                    model: post,
                                    ajax: '1',
                                    latitude: lat,
                                    longitude: lon
                                });


                            }
                            else {
                                if (address == "") {
                                    var lat = ae_globals.map_center.latitude;
                                    var lon = ae_globals.map_center.longitude;
                                }
                                AE.MapView = new Views.Map({
                                    el: $('body'),
                                    model: post,
                                    ajax: '1',
                                    latitude: lat,
                                    longitude: lon,
                                });
                            }
                        }
                    });


                    if (jQuery("#your_current_location_search").length > 0) {
                        if (view.query.location == "") {
                            jQuery("#your_current_location_search").html('Anuncios encontrados en toda España');
                        }
                        else {  // puede ser provincia o "cerca-de-ti"
                            if (view.query.location == "cerca-de-ti") {

                                var localizacion_user = "";
                                if (frontGetCookie('current_user_province')) {
                                    localizacion_user = frontGetCookie('current_user_province');
                                }
                                if (frontGetCookie('current_user_locality') && frontGetCookie('current_user_province')) {
                                    localizacion_user = frontGetCookie('current_user_locality') + ", " + frontGetCookie('current_user_province');
                                }

                                jQuery("#your_current_location_search").html('Anuncios encontrados cerca de ti (' + localizacion_user + ', a ' + radius + 'Km) <span><span class="quitar_geo"><img src="' + theme_url + '/img/loading.gif"><span class="txt">No utilizar<br>geolocalización</span></span></span>');
                            }
                            else {
                                jQuery("#your_current_location_search").html('Anuncios encontrados en la provincia de "' + capitalizeFirstLetter(view.query.location) + '" <span><span class="quitar_geo"><img src="' + theme_url + '/img/loading.gif"><span class="txt">No utilizar<br>geolocalización</span></span></span>');
                            }
                        }

                    }


                    view.triggerMethod("after:fetch", result, res);
                }
            });
        },
        /**
         * on model change update collection
         */
        onModelChange: function (model) {
            var post_status = model.get('post_status');
            if (post_status === 'archive' || post_status === 'reject' || 'post_status' == 'trash') {
                this.collection.remove(model);
            }
        },
        /**
         * switch between grid and list
         */
        switchTo: function () {
            if (this.$('.list-option-filter').length == 0) return;
            var view = this;
            if (view.grid == 'grid') {
                view.$('ul > li').addClass('col-md-3 col-xs-6').removeClass('col-md-12');
                // view.$('ul > li').addClass('col-md-4').removeClass('col-md-12');
                view.$('ul').removeClass('fullwidth');
            } else {
                view.$('ul > li').removeClass('col-md-3 col-xs-6').addClass('col-md-12');
                // view.$('ul > li').removeClass('col-md-4').addClass('col-md-12');
                view.$('ul').addClass('fullwidth');
            }
        },
        /**
         * refresh post
         */
        Refresh: function (event) {
            event.preventDefault();
            var view = this;
            var $target = $(event.currentTarget);
            var meta_key = $target.attr('data-meta-key');
            view['query']['orderby'] = 'rand';
            view['query']['meta_key'] = meta_key;
            view['query']['paginate'] = 'refresh';
            view.page = 1;
            view.fetch($target);
        },
    });
    /**
     * modal reject : render reject ad view help admin can reject an ad, and send seller a message
     */
    Views.RejectPostModal = Views.Modal_Box.extend({
        events: {
            'submit form.reject-ad': 'submitReject'
        },
        initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
        },
        /**
         * set model to modal view
         */
        onReject: function (model) {
            this.model = model;
            this.openModal();
            this.$el.find('input[name=id]').val(model.get('ID'));
            this.$el.find('span.post_name').text(model.get('post_title'));
        },
        /**
         * submit reject and send message to owner
         */
        submitReject: function (event) {
            event.preventDefault();
            var view = this,
                form = $(event.target),
                message = $(form).find('textarea[name=reject_message]').val();
            this.model.set('reject_message', message);
            this.model.save('post_status', 'reject', {
                beforeSend: function () {
                    view.blockUi.block(form);
                },
                success: function (model, res) {
                    view.blockUi.unblock();
                    var type = 'error';
                    if (res.success) {
                        type = 'success';
                        view.closeModal();
                    }
                    // for remove ad from list pending ad
                    AE.pubsub.trigger('ae:post:afterReject', model, res);
                    // for render header in single-job
                    AE.pubsub.trigger('ae:afterRejectPost', model, res);
                    AE.pubsub.trigger('ae:notification', {
                        msg: res.msg,
                        notice_type: type
                    });
                }
            });
        }
    });
    /**
     * Carousel View control view insert carousel for a model
     * author Dakachi
     */
    Views.Carousel = Backbone.View.extend({
        action: 'ae_request_thumb',
        events: {
            'hover .catelory-img-upload': 'hoverCarousel',
            'mouseleave .catelory-img-upload': 'unhoverCarousel',
            'click  .delete ': 'removeCarousel',
            'click .catelory-img-upload img': 'setFeatured',
            /* 31_10_2016 Id : 1 S*/
            'click .rotat': 'rotate'
            /* 31_10_2016 Id : 1 E*/
        },
        // template: _.template($('#carousels-item-template').html()),
        initialize: function (options) {
            this.maxFileUpload = ae_globals.max_images;
            this.options = options;
            this.setupView();
            // catch event handle auth to update ajax nonce
            AE.pubsub.on('ae:user:auth', this.handleAuth, this);
            /**
             * setup ae carousel template
             */
            if ($('#ae_carousel_template').length > 0) {
                this.template = _.template($('#ae_carousel_template').html());
            } else {
                alert('Hi dev, to user ad carousels you have to add a template for image item ae_carousel_template ');
            }
        },
        /**
         * handle authentication to update ajax nonce
         */
        handleAuth: function (model, resp, jqXHR) {
            if (resp.success) {
                this.carousel_uploader.config.multipart_params._ajax_nonce = resp.data.ajaxnonce;
            }
        },
        /**
         * bind a model to view
         */
        setModel: function (model) {
            this.model = model;
            // this.resetUploader();
        },
        /**
         *
         */
        setupView: function () {
            var that = this,
                $carousel = this.$el,
                i = 0,
                j = 0;
            this.carousels = [];
            this.carousels = this.model.get('et_carousels') || [];
            this.featured_image = this.model.get('featured_image') || '';

            this.blockUi = new Views.BlockUi();
            that.numberOfFile = this.carousels.length;

            /**
             * clear the list
             */
            this.$('#image-list').find('li.image-item').remove();
            /**
             * get model image and init view
             */
            var items = [];
            $.each(this.carousels, function (index, item) {
                items.push(item);
            });
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'get',
                data: {
                    item: items,
                    action: that.action
                },
                beforeSend: function () {
                },
                success: function (res) {
                    if (res.success) {
                        $.each(res.data, function (index, item) {
                            if (typeof item.thumbnail !== 'undefined') {
                                var $ul = $('#image-list');
                                if (item.attach_id === that.model.get('featured_image')) item.is_feature = true;
                                var li = that.template(item);
                                $ul.prepend(li);
                            }
                        });

                        if (res.data) {
                            $('#et_have_image').val(1); // para que no falle el validador
                        }

                        /**
                         * hide add button when file >= maxfile
                         */
                        if (res.data.length >= that.maxFileUpload) {
                            $('#carousel_browse_button').hide('slow');
                        }
                    }
                }
            });
            var uploaderID = 'carousel';
            if (typeof this.carousel_uploader === 'undefined') this.carousel_uploader = new Views.File_Uploader({
                el: $carousel,
                extensions: (this.options.extensions) ? this.options.extensions : 'jpg,jpeg,gif,png,ico',
                uploaderID: 'carousel',
                thumbsize: 'thumbnail',
                multi_selection: true,
                multipart_params: {
                    _ajax_nonce: $carousel.find('.et_ajaxnonce').attr('id'),
                    // action: 'et-carousel-upload',
                    imgType: 'ad_carousels',
                    author: that.model.get('post_author'),
                    data: uploaderID
                },
                filters: [{
                    title: 'Image Files',
                    extensions: (this.options.extensions) ? this.options.extensions : 'jpg,jpeg,gif,png,ico'
                }],
                cbUploaded: function (up, file, res) {
                    if (res.success) {
                        var $ul = $('#image-list');
                        var li = that.template(res.data);
                        $ul.prepend(li);
                        // update carousel list item
                        //carousel_list = carousel_list+','+res.data.attach_id;
                        that.carousels.push(res.data.attach_id);
                        //$('.carousel-list').find('#carousels').val(carousel_list);
                        that.model.set('et_carousels', that.carousels);
                    }
                },
                cbAdded: function (up, files) {
                    var max_files = that.maxFileUpload;
                    var carousels = that.model.get('et_carousels') || [];
                    that.numberOfFile = that.$('.image-item').length;
                    j = that.numberOfFile;
                    i = that.numberOfFile;
                    if (files.length > (max_files - that.numberOfFile)) {
                        //alert('You are allowed to add only ' + max_files + ' files.');
                        alert('You are allowed to add only ' + (max_files - that.numberOfFile) + ' files.');
                    }
                    plupload.each(files, function (file) {
                        if (files.length > (max_files - that.numberOfFile)) {
                            //alert('You are allowed to add only ' + max_files + ' files.');
                            up.removeFile(file);
                            //alert('You are allowed to add only ' + max_files - that.numberOfFile + ' files.');
                        } else {
                            i++;
                        }
                    });
                    that.numberOfFile = i;
                    if (that.numberOfFile >= max_files) {
                        $('#carousel_browse_button').hide('slow');
                    }
                },
                beforeSend: function (element) {
                    // pubsub.trigger ('ce:carousels:uploading');
                    that.model.set('uploadingCarousel', true);
                    that.blockUi.block($('#carousel_container'));
                },
                success: function () {
                    var max_files = that.maxFileUpload;
                    if ($('#image-list').find('li.image-item').size() > 0) {
                        j = $('#image-list').find('li.image-item').size();
                    }

                    if (j == max_files) {

                        that.blockUi.unblock();
                        $("#carousel_browse_button").hide('slow');
                    }

                    if (j == that.numberOfFile) {
                        that.model.set('uploadingCarousel', false);
                    }
                    var featured = that.$el.find('span.featured');
                    if (featured.length == 0) {
                        var last = that.$el.find('.catelory-img-upload:last');
                        last.addClass('featured');
                        that.model.set('featured_image', last.attr('id'));
                    }
                    that.blockUi.unblock();
                    j++;
                    /* 31_10_2016 Id : 2 S*/
                    var photo_cnt = $('.image-item').length;
                    if (photo_cnt > 0) {
                        $('#et_have_image').val(photo_cnt);
                        $("#et_have_image").parent().removeClass("error")
                        $("#et_have_image").parent().find("div.message").remove();
                    } else {
                        $('#et_have_image').val('');
                    }
                    /* 31_10_2016 Id : 2 E*/
                }
            });
        },
        /* 31_10_2016 Id : 3 S*/
        rotate: function (e) {
            e.preventDefault();
            var that = this;
            this.blockUi = new Views.BlockUi();
            var selc = $('input[name=featured_image]:checked').val();
            var img = $('img[data-id=' + selc + ']').attr('src');
            if (selc) {
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        id: selc,
                        action: 'ae-sync-image-rotate'
                    },
                    beforeSend: function () {
                        that.blockUi.block($('#image-list'));
                    },
                    success: function () {
                        $('img[data-id=' + selc + ']').attr('src', img + '?timestamp=' + new Date().getTime());  // visor
                        $(".img-small-place img").attr("src", $(".img-small-place img").attr("src") + '?timestamp=' + new Date().getTime())  // imagen principal

                        $(".list-gallery img").each(function () {  // todas las imagenes de la galería
                            var attr = $(this).attr("src");
                            $(this).attr("src", attr + '?timestamp=' + new Date().getTime())
                        });


                        that.blockUi.unblock();
                    }
                });
            }
        },
        /* 31_10_2016 Id : 3 E*/
        resetUploader: function () {
            if (typeof this.carousel_uploader === 'undefined') return;
            this.carousel_uploader.controller.splice();
            this.carousel_uploader.controller.refresh();
            this.carousel_uploader.controller.destroy();
        },
        removeCarousel: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                $span = $target.parents('.image-item'),
                id = $span.attr('id'),
                that = this,
                max_files = that.maxFileUpload;

            var carousels = this.carousels;
            carousels = $.grep(carousels, function (a) {
                return a != id;
            });
            this.model.set('et_carousels', carousels);
            $.ajax({
                type: 'post',
                url: ae_globals.ajaxURL,
                data: {
                    action: 'ae_remove_carousel',
                    id: id
                },
                beforeSend: function () {
                },
                success: function () {
                    $('#carousel_browse_button').show('slow');
                }
            });
            $span.remove();
            this.numberOfFile = this.numberOfFile - 1;
            var total_image = $('#image-list').find('li.image-item').size();

            if (total_image < max_files) {
                $('#carousel_browse_button').show('slow');
            }
            /* 31_10_2016 Id : 4 S*/
            var photo_cnt = $('.image-item').length;
            if (photo_cnt > 0) {
                $('#et_have_image').val(photo_cnt);
                $("#et_have_image").parent().removeClass("error")
                $("#et_have_image").parent().find("div.message").remove();
            } else {
                $('#et_have_image').val('');
            }
            /* 31_10_2016 Id : 4 E*/
        },
        setFeatured: function (event) {
            var $target = $(event.currentTarget);

            this.model.set('featured_image', $target.attr('data-id'));
            $('.catelory-img-upload').removeClass('featured');
            $target.parents('.catelory-img-upload').addClass('featured');

        },
        hoverCarousel: function (event) {
            var $target = $(event.currentTarget);
            $target.find('img').animate({
                'opacity': '0.5'
            }, 200);
            $target.find('.delete').animate({
                'opacity': '1'
            }, 200);
        },
        unhoverCarousel: function (event) {
            var $target = $(event.currentTarget);
            $target.find('img').animate({
                'opacity': '1'
            }, 200);
            $target.find('.delete').animate({
                'opacity': '0'
            }, 200);
        }
    });
    /**
     * submit post view
     */
    Views.SubmitPost = Backbone.Marionette.View.extend({
        events: {
            // select plan, you should add class select-plan to select button
            'click .select-plan': 'selectPlan',
            // submit authentication form
            'submit form.auth': 'submitAuth',
            // submit post form
            'submit form.post': 'submitPost',
            // select a payment gateway
            'click .select-payment': 'selectPayment',
            'click .other-payment': 'extendPayment',
            // user select a step
            'click .step-heading': 'selectStep',
            // update map lat long
            'keyup input#et_full_location': 'gecodeMap',
            'keyup input#et_full_location2': 'gecodeMap',
        },
        // model event
        modelEvents: {
            // when user change payment plan sync with server
            'change:et_payment_package': 'updatePayment'
        },
        /**
         * update model payment plan when change it
         */
        updatePayment: function () {
            if (!this.model.isNew()) {
                this.model.save();
            }
        },
        /**
         * init submit post view
         * @params array options
         * - step : number of step
         * - steps : array of step name
         * - use_plan : submit post with payment plan or not
         * - limit_free_plan : the maximum number of free plan user can use
         * - free_plan_used : current user free plan used
         */
        initialize: function (options) {
            _.bindAll(this, 'userLogin');
            this.step = 4;
            this.steps = ['plan', 'auth', 'post', 'payment'];
            this.options = _.extend(this, options);


            if ($('#edit_postdata').length > 0) { // rellenamos los campos
                var postdata = JSON.parse($('#edit_postdata').html());

                /***************** Ponemos el cp + calle en los campos correspondientes ******************** Miguel ***/
                if (postdata.et_full_location.length > 0) {
                    var direccion = postdata.et_full_location;
                    if (direccion.match(/\d{5}/)) { // hay CP
                        var cp = direccion.match(/\d{5}/)[0];
                        if (cp.length > 0) {
                            postdata.et_full_location = cp;
                            var dash = direccion.indexOf(', ');
                            if (dash > 0) {  // hay dirección
                                var calle = direccion.substring(0, dash);
                                postdata.et_full_location2 = calle;
                            }
                        }
                    }
                }

                /***************** Ponemos la provincia en su select ******************* Miguel ***/
                if (postdata.location.length > 0) {  // tenemos provincia
                    var cadena = postdata.location[0];
                    $('#location').val(cadena).trigger('chosen:updated');
                }

                if (postdata.place_category.length > 0) {  // tenemos CATEGORÍA
                    $('#place_category').val(postdata.place_category).trigger('chosen:updated');
                }

                /***************** Ponemos si tiene coche en los campos correspondientes ******************* Miguel ***/
                if (postdata.et_have_car.length > 0) {
                    var cadena = postdata.et_have_car;
                    $('#et_have_car_sel').val(cadena).trigger('chosen:updated');

                }


                /***********************************************************************************************/

                $("#terms_conditions").prop('checked', true);  // le aceptamos los terminos y condiciones

                /******************************************************************************************/


                this.model = new Models.Post(postdata);
                this.model.set('renew', 1);
                this.model.set('alguna_opcion_date_seleccionada', 1);


                this.setupFields();

            } else {
                this.model = new Models.Post();
            }
            this.user = AE.App.user;
            // init block ui
            this.blockUi = new Views.BlockUi();
            this.formValidate();
            this.finishStep = [];
            // trigger method before init
            this.triggerMethod("after:init", this);
            // handle current user
            this.user.on('change:id', this.userLogin);
            if (parseInt(this.step) == 2) this.currentStep = 'auth';
            if (parseInt(this.step) == 4) this.currentStep = 'plan';
            this.initMap();
            this.setupFirstStep();
        },
        setupFirstStep: function () {
            var $target = $('.auto-select');
            if ($target.length == 0) return;
            if ($('.auto-select.publish').length > 0) {
                $target = $($('.auto-select.publish').get(0)).find('.select-plan');
            } else {
                $target = $($('.auto-select.pending').get(0)).find('.select-plan');
            }
            this.choosePlan($target);
        },
        /**
         *
         */
        setupFields: function () {
            var view = this,
                form_field = view.$('#step-post'),
                location = this.model.get('location');
            /*************************************/
            /**
             * update form value for input, textarea select
             */
            form_field.find('input.input-item,input[type="text"],input[type="hidden"], textarea').each(function () {
                var $input = $(this);
                $input.val(view.model.get($input.attr('name')));

            });
            form_field.find('input[type="radio"]').each(function () {
                var $input = $(this),
                    name = $input.attr('name');
                if ($input.val() == view.model.get(name)) {
                    $input.attr('checked', true);
                }
            });

            /******************************/


            if (jQuery("input[name='tipo_reserva']:checked")) {
                jQuery("input[name='tipo_reserva']:checked").click();
            }


        },
        /**
         * add validator to check form validation
         */
        formValidate: function () {
            /**
             * auth form validate
             */
            $("form.auth").validate({
                ignore: ".ignore",
                rules: {
                    user_login: 'required',
                    user_pass: "required",
                    repeat_password: {
                        equalTo: "#user_pass"
                    },
                    user_email: {
                        required: true,
                        email: true
                        // remote: ae_globals.ajaxURL + '?action=et_email_check_used'
                    },
                    recaptcha_publica_tu_anuncio_1: 'required',

                }
            });
            /**
             * post form validate
             */
            $("form.post").validate({
                /* Miguel */
                ignore: "",
                rules: {
                    post_title: {
                        required: true,
                        maxlength: 65,
                        minlength: 5,
                        remote: {
                            url: ae_globals.ajaxURL,
                            type: "post",
                            data: {
                                post_title: function () {
                                    return $("#post_title").val();
                                },
                                is_ajax: true,
                                action: 'checkIfExistTitle'
                            }
                        }
                    },
                    et_full_location: {
                        required: true,
                        number: true,
                        maxlength: 5,
                        minlength: 5
                    },
                    place_category: "required",
                    post_content: "required",
                    et_have_image: {
                        required: function () {
                            if (getIfApp == 0) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    location: "required",
                    tipo_reserva: "required",
                    hourly_rate1: {
                        required: {
                            depends: function (element) {
                                return jQuery("input[type='radio']:checked").val() == "horas" || jQuery("input[type='radio']:checked").val() == "servicio"
                            }
                        },
                        number: true
                    },
                    duracion_servicio: {
                        required: {
                            depends: function (element) {
                                return jQuery("input[type='radio']:checked").val() == "servicio"
                            }
                        }
                    },
                    et_phone: {
                        number: true,
                        minlength: 9,
                        maxlength: 9
                    },
                    et_url: {
                        url2: true
                    },
                    et_fb_url: {
                        url2: true
                    },
                    et_google_url: {
                        url2: true
                    },
                    et_twitter_url: {
                        url2: true
                    },
                    alguna_opcion_date_seleccionada: "required",
                    terms_conditions: "required"
                },
                messages: {
                    post_title: {
                        remote: "Este título ya está en uso."
                    }
                },
                errorPlacement: function (label, element) {

                    if (element.is("textarea")) {
                        $(".message_error_textarea").closest('div').append(label);
                    } else {
                        $(element).closest('div').append(label);
                    }

                },
                focusInvalid: false,
                invalidHandler: function (form, validator) {
                    // llevamos al usuario al error más arriba
                    scrollToErrorDatos($(validator.errorList[0].element));

                }
            });
        },
        /**
         * catch event select plan when user select plan
         * @author Dakachi
         */
        selectPlan: function (event) {
            event.preventDefault();
            this.choosePlan($(event.currentTarget));
        },
        // call when user select a payment plan
        /* 21_10_2016 Id : 3 S*/
        choosePlan: function ($target, model, res) {
            /* 21_10_2016 Id : 3 E*/
            var $li = $target.closest('li'),
                amount = $li.attr('data-price'),
                $step = $li.closest('div.step-wrapper'),
                view = this;
            this.currentStep = 'plan';
            /**
             * call function beforeSelectPlan
             */
            this.triggerMethod("before:selectPlan", $step, $li);
            /**
             * set payment package to model
             */
            // set the job package of job model & free status
            if (parseFloat(amount) === 0) { // check selected plan price
                /**
                 * check free plan
                 */
                if (parseInt(this.limit_free_plan) > 0) { // check limit free plan
                    var used = view.free_plan_used;
                    // user have reached the limit free plan
                    if (parseInt(used) >= parseInt(view.limit_free_plan)) {
                        /**
                         * trigger method limit free, you can add a function onLimitFree to control your purpose
                         */
                        view.triggerMethod("limit:free", view);
                        return false;
                    }
                }
                // set post model free
                view.model.set({
                    is_free: 1
                });

                /* 21_10_2016 Id : 1 S*/

                $('#step-payment').hide();


                if (jQuery("input[name='tipo_reserva']").size() == 3) {
                    //añadir el "a convenir"
                    jQuery(".tipo_servicio .radio-inline.tres").remove();
                    jQuery(".tipo_servicio label").removeClass("col-md-4").removeClass("col-sm-4").removeClass("col-xs-4");
                    jQuery(".tipo_servicio label").addClass("col-md-6").removeClass("col-sm-6").removeClass("col-xs-6");

                }

                // add step auth to finish step
                view.addFinishStep('step-post');

                // show nex step
                view.showNextStep();
                // trigger method onSubmitPostSuccess with params are model user and res
                view.triggerMethod('after:postSuccess', model, res);

                /* 21_10_2016 Id : 1 E*/


            } else { // not free
                view.model.set({
                    is_free: 0
                });

                $('#step-payment').show();

                if (jQuery("input[name='tipo_reserva']").size() == 2) {
                    //añadir el "a convenir"
                    jQuery(".tipo_servicio").append("<label class='radio-inline tres col-md-4 col-sm-4 col-xs-4'><input type='radio' name='tipo_reserva' value='convenir'>A convenir</label>");
                    jQuery(".tipo_servicio label").removeClass("col-md-6").removeClass("col-sm-6").removeClass("col-xs-6");
                    jQuery(".tipo_servicio label").addClass("col-md-4").removeClass("col-sm-4").removeClass("col-xs-4");

                }

            }
            this.model.set('et_payment_package', $li.attr('data-sku'));
            if (typeof $li.attr('data-package-type') !== 'undefined') {
                this.model.set('et_package_type', $li.attr('data-package-type'));
            }
            /**
             * control button view by add class selected
             */
            this.$('.list-price li').removeClass('selected');
            $target.parents('li').addClass('selected');
            // hide all content step
            $li.closest('div.step-wrapper').addClass('complete');
            // add step plan to finish array
            // if(ae_globals.user_ID == 0){
            this.addFinishStep('step-plan');
            // }
            // this.$('.step-auth .content').slideDown();
            // show next step
            this.showNextStep();
            /**
             * trigger method onAfterSelectPlan for extended view
             */
            this.triggerMethod("after:selectPlan", $step, $li);
        },
        /**
         * user submit auth form to login or register acount
         */
        submitAuth: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this;
            $target.find('.input-item').each(function () {
                view.user.set($(this).attr('name'), $(this).val());
            });

            /* 24_10_2016 Id : 1 S*/
            /*if( view.$el.find('textarea').length > 0 ){
             view.$el.find('textarea').each(function() {
             view.user.set( $(this).attr('name'),  $(this).val() );
             });
             }*/
            /* 24_10_2016 Id : 1 E*/

            // trigger method before submit Auth
            view.triggerMethod('before:submitAuth', view.user, view);
            /* 24_10_2016 Id : 2 S*/
            view.user.set('flag', '1');
            /* 24_10_2016 Id : 2 E*/
            view.user.save('do', 'register', {
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (model, res, jqXHR) {
                    view.blockUi.unblock($target);
                    if (res.success) {
                        view.currentStep = 'auth';
                        // add step auth to finish step
                        view.addFinishStep('step-auth');
                        // set user login is true
                        view.user_login = true;
                        // show nex step
                        view.showNextStep();
                        /*trigger event user authentication sucess*/
                        AE.pubsub.trigger('ae:user:auth', model, res, jqXHR);
                        // trigger method onSubmitAuthSuccess with params are model user and res
                        view.triggerMethod('after:authSuccess', model, res);

                        /* 03_12_2016 Id : 2 S*/
                        setTimeout(function () {
                            view.map = new GMaps({
                                div: '#map',
                                lat: ae_globals.map_center.latitude,
                                lng: ae_globals.map_center.longitude,
                                zoom: 5,
                                panControl: false,
                                zoomControl: true,
                                mapTypeControl: false
                            });
                            view.map.setCenter(ae_globals.map_center.latitude, ae_globals.map_center.longitude);
                        }, 500);
                        /* 03_12_2016 Id : 2 E*/

                    } else {
                        view.user_login = false;
                        // trigger method onSubmitAuthFail with params are model user and res
                        view.triggerMethod('after:authFail', model, res);
                    }
                }
            });
        },
        /*
         * catch event user change and update authentication step
         */
        userLogin: function (model) {
            var view = this;

            // if user have selected plan
            if (this.finishStep.length > 0 || view.currentStep == 'auth') {
                view.addFinishStep('step-auth');
            }

            if (parseInt(this.step) == 2 || this.finishStep.length > 1) {
                view.showNextStep();
            }

            // set user login is true
            view.user_login = true;
            // remove content of step auth
            this.$('.step-auth .content').remove();
            // update step auth heading text
            this.$('.step-auth .text-heading-step').html(model.get('label'));
            view.triggerMethod("after:showNextStep", 'post', view.currentStep);

            /* 03_12_2016 Id : 1 S*/
            setTimeout(function () {
                view.map = new GMaps({
                    div: '#map',
                    lat: ae_globals.map_center.latitude,
                    lng: ae_globals.map_center.longitude,
                    zoom: 5,
                    panControl: false,
                    zoomControl: true,
                    mapTypeControl: false
                });
                view.map.setCenter(ae_globals.map_center.latitude, ae_globals.map_center.longitude);
            }, 500);
            /* 03_12_2016 Id : 1 E*/

        },
        /**
         * user submit form.post to submit a post
         */
        submitPost: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                view = this,
                temp = [];
            if (view.model.get('uploadingCarousel')) return false;
            /**
             * update model data
             */
            $target.find('.input-item, .wp-editor-area').each(function () {
                view.model.set($(this).attr('name'), $(this).val());
            });
            $target.find('.tax-item').each(function () {
                view.model.set($(this).attr('name'), $(this).val());
            });
            // trigger method before SubmitPost
            view.triggerMethod('before:submitPost', view.model, view);
            /**
             * update input check box to model
             */
            view.$el.find('input[type=checkbox]').each(function () {
                var name = $(this).attr('name');
                view.model.set(name, []);
            });


            view.$el.find('input[type=checkbox]:checked').each(function () {
                var name = $(this).attr('name');
                if (name == "et_claimable_check") return false;
                if (typeof temp[name] !== 'object') {
                    temp[name] = new Array();
                }
                temp[name].push($(this).val());
                view.model.set(name, temp[name]);
            });
            /**
             * update input radio to model
             */
            /* 01_11_2016 Id : 2 S*/
            /*view.$el.find('input[type=radio]').each(function() {
             var name = $(this).attr('name');
             view.model.set(name, '');
             });*/
            /* 01_11_2016 Id : 2 E*/
            view.$el.find('input[type=radio]:checked').each(function () {
                view.model.set($(this).attr('name'), $(this).val());
            });

            // duracion_servicio
            if ($("input[name='tipo_reserva']:checked").val() == "servicio") {
                view.model.set('duracion_servicio', $("select#duracion_servicio").val());
            }

            /**
             * save model
             */
            view.model.set('post_author', view.user.get('id'));

            /***********************************************************************************************  Miguel *
             * Concatenamos et_full_location con et_full_location2 (si existe et_full_location2)
             */
            if (view.model.get('et_full_location2') != "") {
                view.model.set('et_full_location', view.model.get('et_full_location2') + ", " + view.model.get("et_full_location"));
            }
            else {
                view.model.set('et_full_location', view.model.get("et_full_location"));
            }
            /*********************************************************************************************************/

            if (($(".open-time").val() != "" && $(".close-time").val() != "") ||
                ($(".open-time-2").val() != "" && $(".close-time-2").val() != "")) {
                view.model.set('alguna_opcion_date_seleccionada', 1);
            }
            else {
                view.model.set('alguna_opcion_date_seleccionada', "");
            }
            /*********************************************************************************************************/

            view.model.set('necesary_terms', 1);

            if (getIfApp != 0) { // es app
                view.model.set('isAPP', 1);
            }

            if (!view.model.get('uploadingCarousel')) {
                view.model.save('', '', {
                    beforeSend: function () {
                        view.blockUi.block($target);
                    },
                    success: function (model, res) {
                        view.blockUi.unblock();
                        if (res.success) {
                            // redirect to process payment if exist redirect url
                            if (typeof res.data.redirect_url !== 'undefined') {
                                window.location.href = res.data.redirect_url;
                            } else {
                                view.currentStep = 'post';
                                // add step auth to finish step
                                view.addFinishStep('step-post');
                                // show nex step
                                view.showNextStep();
                                // trigger method onSubmitPostSuccess with params are model user and res
                                view.triggerMethod('after:postSuccess', model, res);
                            }
                        } else {
                            // trigger method onSubmitPostFail with params are model user and res
                            view.triggerMethod('after:postFail', model, res);
                        }
                    }
                });
            }
        },
        // user use payment which is not supported in theme
        extendPayment: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                tipopago = $target.attr('data-type'),
                data = {
                    action: 'et-setup-payment',
                    // post id
                    ID: this.model.id,
                    // author
                    author: this.model.get('post_author'),
                    // package sku id
                    packageID: this.model.get('et_payment_package'),
                    //package type
                    packageType: this.model.get('et_package_type'),
                    // payment gateway
                    'tipo-pago': tipopago,
                    // send coupon code if exist
                    coupon_code: view.$('#coupon_code').val()
                };
            AE.pubsub.trigger('ae:submitPost:extendGateway', data, event);
        },
        /**
         * user select a payment gateway to submit post
         */
        selectPayment: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                tipopago = $target.attr('data-type'),
                $button = $target.find('button'),
                view = this;
            $.ajax({
                url: ae_globals.ajaxURL,
                type: 'post',
                contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
                // build data and send
                data: {
                    action: 'et-setup-payment',
                    // post id
                    ID: this.model.id,
                    // author
                    author: this.model.get('post_author'),
                    // package sku id
                    packageID: this.model.get('et_payment_package'),
                    //package type
                    packageType: this.model.get('et_package_type'),
                    // payment gateway
                    'tipo-pago': tipopago,
                    // send coupon code if exist
                    coupon_code: view.$('#coupon_code').val()
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function (response) {
                    view.blockUi.unblock();
                    if (response.data.ACK) {
                        // call method onSubmitPaymenSuccess
                        view.triggerMethod('submit:paymentSuccess', response);
                        // update form check out and submit
                        $('#checkout_form').attr('action', response.data.url);
                        if ($('#checkout_form .packageType').length > 0) {
                            $('#checkout_form .packageType').val(view.model.get('et_package_type'));
                        }
                        if (typeof response.data.extend !== "undefined") {
                            $('#checkout_form .payment_info').html('').append(response.data.extend.extend_fields);
                        }
                        // trigger click on submit button
                        $('#payment_submit').click();
                    } else {
                        // call method onSubmitPaymentFail
                        view.triggerMethod('submit:paymentFail', response);
                    }
                }
            });
        },
        initMap: function () {
            var view = this;
            if ($('#map').length > 0) {
                view.map = new GMaps({
                    div: '#map',
                    /* 12_11_2016 Id (M): 2 S */
                    lat: ae_globals.map_center.latitude,
                    lng: ae_globals.map_center.longitude,
                    // zoom: 1,
                    /* lat: "40.4378698",  // para que se centre en madrid
                     lng: "-3.8196226",*/
                    zoom: 5,  // para que se centre en España
                    /* 12_11_2016 Id (M): 2 E */
                    panControl: false,
                    zoomControl: true,
                    mapTypeControl: false
                });

                if (ae_globals.map_typestyle != 1) {
                    // remove point and transit on mobile
                    view.map.set('styles', [
                        {featureType: "poi", stylers: [{visibility: 'off'}]},
                        {featureType: "transit.station.bus", stylers: [{visibility: 'off'}]}
                    ]);
                }

                if ($('#et_location_lat').val() !== '' && $('#et_location_lng').val() !== '') {
                    var lat = $('#et_location_lat').val(),
                        lng = $('#et_location_lng').val();
                    view.map.setCenter(lat, lng);
                    view.map.addMarker({
                        lat: lat,
                        lng: lng,
                        draggable: true,
                        dragend: function (e) {
                            var location = e.latLng;
                            $('#et_location_lat').val(location.lat());
                            $('#et_location_lng').val(location.lng());
                            view.model.set('et_location_lat', location.lat());
                            view.model.set('et_location_lng', location.lng());
                        }
                    });
                }

            }
        },
        /**
         * init map gecode an address
         */
        gecodeMap: function (event) {

            /* 12_11_2016 Id (M): 1 S */
            // var address = $(event.currentTarget).val(),

            var cp = $("#et_full_location").val();
            var dir = $("#et_full_location2").val();
            if (cp.length < 5) return;
            var address = dir + ", " + cp + ", España",  // para que solo bubtn-submit-login-formsque en España
                /* 12_11_2016 Id (M): 1 E */

                view = this;
            //gmaps = new GMaps
            if (typeof(GMaps) !== 'undefined') GMaps.geocode({
                address: address,
                callback: function (results, status) {
                    if (status == 'OK') {
                        var latlng = results[0].geometry.location;
                        $('#et_location_lat').val(latlng.lat());
                        $('#et_location_lng').val(latlng.lng());
                        // set value to model
                        view.model.set('et_location_lng', latlng.lng());
                        view.model.set('et_location_lat', latlng.lat());
                        view.map.setZoom(14);
                        view.map.setCenter(latlng.lat(), latlng.lng());
                        view.map.removeMarkers();
                        view.map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                            draggable: true,
                            dragend: function (e) {
                                var location = e.latLng;
                                $('#et_location_lat').val(location.lat());
                                $('#et_location_lng').val(location.lng());
                                view.model.set('et_location_lat', location.lat());
                                view.model.set('et_location_lng', location.lng());
                            }
                        });

                        /* 12_11_2016 Id (M): 3 S */
                        // rellenamos la provincia si es de españa

                        var country;
                        for (i = 0; i < results[0].address_components.length; i++) {
                            for (j = 0; j < results[0].address_components[i].types.length; j++) {
                                if (results[0].address_components[i].types[j] == "country")
                                    country = results[0].address_components[i].short_name
                            }
                        }

                        if (country == "ES") {

                            // obtenemos la provincia
                            var provincia;
                            for (i = 0; i < results[0].address_components.length; i++) {
                                for (j = 0; j < results[0].address_components[i].types.length; j++) {
                                    if (results[0].address_components[i].types[j] == "administrative_area_level_2")
                                        provincia = results[0].address_components[i].short_name
                                }
                            }

                            //buscamos el VALUE

                            if (jQuery("#location ." + provincia.toLowerCase()).val() !== 'undefined') {
                                var value_provincia = jQuery("#location ." + provincia.toLowerCase()).val();

                                jQuery('#location').val(value_provincia);
                                jQuery('#location').trigger("chosen:updated");
                                jQuery('#location').parent().removeClass("error");
                                jQuery('#location').parent().find("div.message").remove();
                            }
                            else {
                                jQuery('#location').val(-1);  //selecciona una opción
                                jQuery('#location').trigger("chosen:updated");
                            }
                        }
                        else {
                            jQuery('#location').val(-1);  //selecciona una opción
                            jQuery('#location').trigger("chosen:updated");
                        }
                        /* 12_11_2016 Id (M): 3 E */

                    }
                }
            });
        },


        /**
         * show next step
         */
        showNextStep: function () {
            this.triggerMethod("before:showNextStep", this);
            var next = 'auth',
                view = this;
            view.$('.step-wrapper').removeClass('current');
            this.$('.content').slideUp(500, 'easeOutExpo', function () {
                // this.$('.content').;
                // current step is plan
                if (view.currentStep === 'plan') {
                    if (view.user_login) { // user login skip step auth
                        next = 'post';
                    }
                }
                // current step is auth
                if (view.currentStep == 'auth') {
                    // update user_login
                    view.user_login = true;
                    next = 'post';
                }
                // current step is post
                if (view.currentStep == 'post') {
                    view.user_login = true;
                    next = 'payment';
                }

                view.$('.step-' + view.currentStep + '  .content').closest('div.step-wrapper').addClass('complete');
                // show next step
                view.$('.step-' + next + '  .content').slideDown(10, 'easeOutExpo').end();
                view.$('.step-' + next).addClass('current');
            });
            /**
             * refresh map
             */
            if (typeof this.map !== 'undefined') {
                this.map.refresh();
            }
            // trigger onAfterShowNextStep
            view.triggerMethod("after:showNextStep", next, view.currentStep);
        },
        /**
         * user select a step
         */
        selectStep: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                $wrapper = $target.parents('.step-wrapper'),
                view = this,
                select = $wrapper.attr('id');
            // step authentication
            if (select == 'step-auth') {
                if (this.finishStep.length < 1) return;
            }
            // step post
            if (select == 'step-post') {
                if ($('#step-auth').length > 0 && this.finishStep.length < 2) return;
                if ($('#step-auth').length == 0 && this.finishStep.length < 1) return;
            }
            // step payment
            if (select == 'step-payment') {
                if ($('#step-auth').length > 0 && this.finishStep.length < 3) return;
                if ($('#step-auth').length == 0 && this.finishStep.length < 2) return;
            }
            if (!$target.closest('div').hasClass('current')) {
                // trigger to call view beforeSelectStep
                this.triggerMethod('before:selectStep', $target);
                // toggle content of selected step
                view.$('.step-wrapper').removeClass('current');
                this.$('.content').slideUp(500, 'easeOutExpo');
                $target.closest('div').addClass('current').find('.content').slideDown(500, 'easeOutExpo');
                // trigger to call view afterSelectStep
                this.triggerMethod('after:selectStep', $target, this);
            }
        },
        /**
         * add a step to finish array
         */
        addFinishStep: function (step) {
            if (typeof this.finishStep === 'undefined') {
                this.finishStep = [];
            }
            $('#' + step).find('.number-step').html('<span class="fa fa-check"></span>');
            this.$('.' + step).addClass('complete');
            this.finishStep.push(step);
        }
    });
    /**
     * modal contact message
     */
    Views.ContactModal = AE.Views.Modal_Box.extend({
        events: {
            'submit form#submit_contact': 'sendMessage',
        },
        initialize: function (options) {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new AE.Views.BlockUi();
            this.options = _.extend(this, options);
            this.user = this.model;
        },
        sendMessage: function (event) {
            event.preventDefault();
            this.submit_validator = $("form#submit_contact").validate({
                rules: {
                    message: "required"
                }
            });
            var form = $(event.currentTarget),
                $button = form.find(".btn-submit"),
                data = form.serializeObject(),
                view = this;
            /**
             * scan all fields in form and set the value to model user
             */
            form.find('input, textarea, select').each(function () {
                view.user.set($(this).attr('name'), $(this).val());
            });
            this.model.set('send_to', view.user_id);
            if (this.submit_validator.form() && !form.hasClass("processing")) {
                this.user.set('do', 'inbox');
                this.user.request('fetch', {
                    beforeSend: function () {
                        view.blockUi.block($button);
                        form.addClass('processing');
                    },
                    success: function (result, status, jqXHR) {
                        form.removeClass('processing');
                        if (status.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'success',
                            });
                            view.closeModal();
                            form.trigger('reset');
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'error',
                            });
                            view.closeModal();
                        }
                        view.blockUi.unblock();
                    }
                });
            }
        }
    });
    // POST VIEW
})(window.AE, jQuery, Backbone, window.AE.Views, window.AE.Models, window.AE.Collections);
// build basic model
(function (AE, $, Backbone) {
    AE.Models.User = Backbone.Model.extend({
        action: 'ae-sync-user',
        initialize: function () {
        },
        request: function (method, options) {
            if (this.get('do') == "register" || method == "update") {
                this.save('', '', options);
            } else {
                this.fetch(options);
            }
        },
        resetpass: function (options) {
            this.save('do', 'resetpass', options);
        },
        confirmMail: function (options) {
            this.set('do', 'confirm_mail');
            this.fetch(options);
        }
    });
    AE.Models.Post = Backbone.Model.extend({
        action: 'ae-sync-post',
        initialize: function () {
        }
    });
    AE.Models.Comment = Backbone.Model.extend({
        action: 'ae-sync-comment',
        initialize: function () {
        }
    });
    /**
     * model favorite
     */
    AE.Models.Favorite = Backbone.Model.extend({
        action: 'ae-sync-favorite',
        initialize: function () {
        }
    });
    /*
     *
     * S K I L L  M O D E L
     *
     */
    AE.Models.Skill = Backbone.Model.extend({
        action: 'ae-skill-sync',
        initialize: function () {
        }
    });
})(window.AE, jQuery, Backbone);
// build basic collection
(function (AE, $, Backbone) {
    AE.Collections.Users = Backbone.Collection.extend({
        model: AE.Models.User,
        action: 'ae-fetch-users',
        initialize: function () {
            this.paged = 1;
        }
    });
    AE.Collections.Posts = Backbone.Collection.extend({
        model: AE.Models.Post,
        action: 'ae-fetch-posts',
        initialize: function () {
            this.paged = 1;
        }
    });
    AE.Collections.Comments = Backbone.Collection.extend({
        model: AE.Models.Comment,
        action: 'ae-fetch-comments',
        initialize: function () {
            this.paged = 1;
        }
    });
    AE.Collections.Blogs = Backbone.Collection.extend({
        model: AE.Models.Comment,
        action: 'ae-fetch-blogs',
        initialize: function () {
            this.paged = 1;
        }
    });
    /*
     *
     * S K I L L  C O L L E C T I O N S
     *
     */
    AE.Collections.Skills = Backbone.Collection.extend({
        model: AE.Models.Skill,
        action: 'ae-fetch-skills',
        initialize: function () {
        }
    });
})(window.AE, jQuery, Backbone, window.AE.Views);