(function(Views, Models, $, Backbone) {
    /**
     * modal edit profile
     */
    Views.EditProfileModal = AE.Views.Modal_Box.extend({
        events: {
            'submit form#submit_edit_profile': 'saveProfile',
        },
        initialize: function() {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new AE.Views.BlockUi();
            this.user    = this.model;
        },
        resetUploader: function() {
            this.avatar_uploader.controller.splice();
            this.avatar_uploader.controller.refresh();
            this.avatar_uploader.controller.destroy();
        },
        saveProfile: function(event) {
            event.preventDefault();

            this.submit_validator = $("form#submit_edit_profile").validate({
                rules: {
                    display_name: "required",
                    user_location: "required",
                    facebook: {
                        url: true
                    },
                }
            });
            var form = $(event.currentTarget),
                $button = form.find("input.btn-submit"),
                data = form.serializeObject(),
                view = this;

            /**
             * scan all fields in form and set the value to model user
             */
            form.find('input, textarea, select').each(function() {
                view.user.set($(this).attr('name'), $(this).val());
            })

            if (this.submit_validator.form() && !form.hasClass("processing")) {

                this.user.set('do', 'profile');
                this.user.request('update', {
                    beforeSend: function() {
                        view.blockUi.block($button);
                        form.addClass('processing');
                    },
                    success: function(result, status, jqXHR) {
                        form.removeClass('processing');
                        if (status.success) {
                            //render data
                            var ul = $("ul#author_info"),
                                location = ul.find('li.location span'),
                                phone = ul.find('li.phone span'),
                                facebook = ul.find('li.facebook span a');

                            location.text(result.get('location'));
                            phone.text(result.get('phone'));

                            if( result.get('facebook') !== "" )
                                facebook.text(result.get('facebook')).attr('href', result.get('facebook'));
                            else
                                ul.find('li.facebook span').html('<a href="#">No facebook</a>');

                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'success',
                            });
                            view.closeModal();
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
        },
        changePassword: function(event) {
            event.preventDefault();

            this.change_pass_validator = this.$("form#submit_edit_password").validate({
                rules: {
                    old_password: "required",
                    new_password: "required",
                    re_password: {
                        required: true,
                        equalTo: "#new_password1"
                    },
                }
            });

            var form = $(event.currentTarget),
                $button = form.find("input.btn-submit"),
                data = form.serializeObject(),
                view = this;

            if (this.change_pass_validator.form()) {

                this.user.set('content', data);
                this.user.save('do_action', 'changePassword', {
                    beforeSend: function() {
                        view.blockUi.block($button);
                        console.log('chay');
                    },
                    success: function(result, status, jqXHR) {
                        if (status.success) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'success',
                            });
                            window.location.href = status.redirect;
                        } else {
                            $('#edit_profile').modal('hide');
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'error',
                            });
                        }
                        view.blockUi.unblock();
                    }
                });
            }
        }
    });
    /**
     * front-end control
     */
    Views.Author = Backbone.View.extend({
        el: 'body.author',
        model: [],
        events: {
            'click a.edit-profile': 'openEditProfileModal'
        },
        initialize: function() {

            this.blockUi    = new AE.Views.BlockUi();
            this.user       = this.model;
            this.uploaderID = 'user_avatar';
            var $container = $("#user_avatar_container"),
                view = this;

            if (typeof this.avatar_uploader === "undefined") {
                this.avatar_uploader = new AE.Views.File_Uploader({
                    el: $container,
                    uploaderID: this.uploaderID,

                    thumbsize: 'thumbnail',
                    multipart_params: {
                        _ajax_nonce: $container.find('.et_ajaxnonce').attr('id'),
                        data: {
                            method: 'change_avatar',
                            author: view.user.get('ID')
                        },
                        imgType: this.uploaderID,
                    },
                    cbUploaded: function(up, file, res) {
                        if (res.success) {
                            $('#' + this.container).parents('.desc').find('.error').remove();
                        } else {
                            $('#' + this.container).parents('.desc').append('<div class="error">' + res.msg + '</div>');
                        }
                    },
                    beforeSend: function(ele) {
                        button = $(ele).find('.image');
                        view.blockUi.block(button);
                    },
                    success: function(res) {
                        if (res.success === false) {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error',
                            });
                        }
                        view.blockUi.unblock();
                    }
                });
            }
        },
        /**
         * Open Edit Profile if current user is logged in
         */
        openEditProfileModal: function(event) {
            event.preventDefault();

            if (typeof this.editProfilemodal === 'undefined') {
                this.editProfilemodal = new Views.EditProfileModal({
                    el: $("#edit_profile"),
                    model: this.user
                });
            }

            this.editProfilemodal.openModal();
        }
    });
})(AE.Views, AE.Models, jQuery, Backbone);