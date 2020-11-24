(function ($, AE, Views, Models, Collections, Backbone) {
    AE.pubsub = AE.pubsub || {};
    _.extend(AE.pubsub, Backbone.Events);
    var pubsub = pubsub || AE.pubsub;
    Models.Event = Backbone.Model.extend({
        action: 'ae-sync-event', initialize: function () {
        }
    });
    Views.AuthModal = Views.Modal_Box.extend({
        events: {
            'submit form.signin_form': 'doLogin',
            'submit form.signup_form': 'doRegister',
            'submit form.forgotpass_form': 'doSendPassword',
            'click  button.close': 'resetAuthForm',
            'click a.link_sign_up': 'openSingup',
            'click a.link_forgot_pass': 'openForgot',
            'click a.link_sign_in': 'openSingin'
        }, initialize: function () {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
            this.user = this.model;
            this.initValidator();
        }, openSingup: function (event) {
            event.preventDefault();
            $('#signin_form').fadeOut("slow", function () {
                $(this).css({'z-index': 1});
                $('.modal-title-sign-in').empty().text(de_front.texts.sign_up);
                $('#signup_form').fadeIn(500).css({'z-index': 2});
            });
        }, openForgot: function (event) {
            event.preventDefault();
            $('#signin_form').fadeOut("slow", function () {
                $(this).css({'z-index': 1});
                $('.modal-title-sign-in').empty().text(de_front.texts.forgotpass);
                $('#forgotpass_form').fadeIn(500).css({'z-index': 2});
            });
        }, openSingin: function (event) {
            event.preventDefault();
            $('#signup_form').fadeOut("slow", function () {
                $(this).css({'z-index': 1});
                $('.modal-title-sign-in').empty().text(de_front.texts.sign_in);
                $('#signin_form').fadeIn(500).css({'z-index': 2});
            });
        }, initValidator: function () {
            this.login_validator = $("form.signin_form").validate({
                rules: {
                    user_login: "required",
                    user_pass: "required"
                }
            });

            //modal para registrarse
            this.register_validator = $("form.signup_form").validate({
                ignore: ".ignore",
                rules: {
                    user_login: "required",
                    user_pass: "required",
                    user_email: {required: true, email: true},
                    re_password: {required: true, equalTo: "#reg_pass"},
                    recaptcha_register_1: "required"
                }
            });
            this.forgot_validator = $("form.forgotpass_form").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                }
            });
        }, doLogin: function (event) {
            event.preventDefault();
            event.stopPropagation();
            this.initValidator();
            var form = $(event.currentTarget), button = form.find('input.btn-submit'), view = this;
            form.find('input, textarea, select').each(function () {
                view.user.set($(this).attr('name'), $(this).val());
            });
            if (this.login_validator.form() && !form.hasClass("processing")) {
                this.user.set('do', 'login');
                this.user.request('read', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    }, success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
                        view.closeModal();
                    }
                });
            }
        }, doRegister: function (event) {
            event.preventDefault();
            event.stopPropagation();
            this.initValidator();
            var form = $(event.currentTarget), button = form.find('input.btn-submit'), view = this;
            form.find('input, textarea, select').each(function () {
                view.user.set($(this).attr('name'), $(this).val());
            });
            if (this.register_validator.form() && !form.hasClass("processing")) {
                this.user.set('do', 'register');
                this.user.request('create', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    }, success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
                        view.closeModal();
                    }
                });
            }
        }, doSendPassword: function (event) {
            event.preventDefault();
            event.stopPropagation();
            this.initValidator();
            var form = $(event.currentTarget), email = form.find('input.email').val(), button = form.find('input.btn-submit'), view = this;
            if (this.forgot_validator.form() && !form.hasClass("processing")) {
                this.user.set('user_login', email);
                this.user.set('do', 'forgot');
                this.user.request('read', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    }, success: function (user, status, jqXHR) {
                        form.removeClass('processing');
                        view.blockUi.unblock();
                        if (status.success) {
                            view.closeModal();
                            AE.pubsub.trigger('ae:notification', {msg: status.msg, notice_type: 'success'});
                        } else {
                            AE.pubsub.trigger('ae:notification', {msg: status.msg, notice_type: 'error'});
                        }
                    }
                });
            }
        }, resetAuthForm: function (event) {
            event.preventDefault();
            var view = this;
            this.$("form.signin_form").fadeIn('slow', function () {
                $('.modal-title-sign-in').empty().text(de_front.texts.sign_in);
                view.$("form.signup_form").hide();
                view.$("form.forgotpass_form").hide();
            });
        }
    });
    Views.CreateEvent = Views.Modal_Box.extend({
        events: {'submit form#event_form': 'submit'},
        initialize: function (options) {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
            this.model = new Models.Event();
            this.initValidator;
            DPGlobal.dates = ae_globals.dates;
            var nowTemp = new Date(), now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0), checkin = $('#event_start_date').datepicker({
                appendTo: '.event-start-date',
                weekStart: parseInt(ae_globals.start_of_week),
            }).on('changeDate', function (ev) {
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date);
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('#event_close_date')[0].focus();
            }).data('datepicker');
            var checkout = $('#event_close_date').datepicker({
                appendTo: '.event-close-date',
                weekStart: parseInt(ae_globals.start_of_week)
            }).on('changeDate', function (ev) {
                checkout.hide();
            }).data('datepicker');
            this.uploaderID = 'event_banner';
            var $container = $("#event_banner_container"), view = this;
            if (typeof this.banner_uploader === "undefined") {
                this.banner_uploader = new AE.Views.File_Uploader({
                    el: $container,
                    uploaderID: this.uploaderID,
                    thumbsize: 'medium',
                    multipart_params: {
                        _ajax_nonce: $container.find('.et_ajaxnonce').attr('id'),
                        data: {method: 'event_banner'},
                        imgType: this.uploaderID,
                    },
                    cbUploaded: function (up, file, res) {
                        if (res.success) {
                            $('#' + this.container).parents('.desc').find('.error').remove();
                        } else {
                            $('#' + this.container).parents('.desc').append('<div class="error">' + res.msg + '</div>');
                        }
                    },
                    beforeSend: function () {
                        view.blockUi.block($container);
                    },
                    success: function (res) {
                        if (res.success === false) {
                            AE.pubsub.trigger('ae:notification', {msg: res.msg, notice_type: 'error',});
                        } else {
                            view.model.set('featured_image', res.data.attach_id);
                        }
                        view.blockUi.unblock();
                    }
                });
            }
        },
        initValidator: function () {
            $("form#event_form").validate({
                ignore: "",
                rules: {post_title: "required", short_tag: "required", event_content: "required",},
                errorPlacement: function (label, element) {
                    if (element.is("textarea")) {
                        label.insertAfter(element.next());
                    } else {
                        $(element).closest('div').append(label);
                    }
                    AE.pubsub.trigger('ae:notification', {msg: ae_globals.msg, notice_type: 'error',});
                }
            });
        },
        onCreateEvent: function (place) {
            this.model.set('post_parent', place.get('ID'));
            this.openModal();
            setTimeout(function () {
                if (typeof tinyMCE !== 'undefined') {
                    tinymce.EditorManager.execCommand('mceAddEditor', true, "event_content");
                }
            }, 500);
        },
        onEditEvent: function (model) {
            this.model = model;
            this.setupFields();
            this.openModal();
        },
        setupFields: function () {
            var view = this, form_field = view.$('.form-field'), location = this.model.get('location'), cover_image = view.model.get('large_thumbnail');
            AE.pubsub.trigger('AE:beforeSetupFields', this.model);
            setTimeout(function () {
                if (typeof tinyMCE !== 'undefined') {
                    tinymce.EditorManager.execCommand('mceAddEditor', true, "event_content");
                    tinymce.EditorManager.get('event_content').setContent(view.model.get('post_content'));
                }
            }, 500);
            form_field.find('input[type="text"],input[type="hidden"], textarea,select').each(function () {
                var $input = $(this);
                $input.val(view.model.get($input.attr('name')));
                if ($input.get(0).nodeName === "SELECT") {
                    $input.trigger('chosen:updated');
                }
            });
            form_field.find('input[type="radio"]').each(function () {
                var $input = $(this), name = $input.attr('name');
                if ($input.val() === view.model.get(name)) {
                    $input.attr('checked', true);
                }
            });
            if (cover_image) {
                view.$('#event_banner_thumbnail').html('').append('<img style="width: 300px;" src="' + cover_image + '" />');
            }
        },
        submit: function (event) {
            event.preventDefault();
            var $form = $(event.currentTarget), temp = new Array(), view = this;
            if ($('form#event_form').valid()) {
                view.$el.find('input,textarea,select').each(function () {
                    view.model.set($(this).attr('name'), $(this).val());
                });
                view.$el.find('input[type=checkbox]:checked').each(function () {
                    var name = $(this).attr('name');
                    temp.push($(this).val());
                    view.model.set(name, temp);
                });
                view.$el.find('input[type=radio]:checked').each(function () {
                    view.model.set($(this).attr('name'), $(this).val());
                });
                view.model.save('', '', {
                    beforeSend: function () {
                        view.blockUi.block($form);
                    }, success: function (result, status, jqXHR) {
                        view.blockUi.unblock();
                        if (status.success) {
                            pubsub.trigger('ae:notification', {msg: status.msg, notice_type: 'success',});
                            window.location.reload();
                        } else {
                            pubsub.trigger('ae:notification', {notice_type: 'error', msg: status.msg});
                        }
                    }
                });
            }
        }
    });
    Views.CreateCalender = Views.Modal_Box.extend({
        events: {'submit form#event_form': 'submit'},
        initialize: function (options) {
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
            this.model = new Models.Event();
            this.initValidator;
            DPGlobal.dates = ae_globals.dates;
            var nowTemp = new Date(), now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0), checkin = $('#event_start_date').datepicker({appendTo: '.event-start-date'}).on('changeDate', function (ev) {
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date);
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('#event_close_date')[0].focus();
            }).data('datepicker');
            var checkout = $('#event_close_date').datepicker({appendTo: '.event-close-date'}).on('changeDate', function (ev) {
                checkout.hide();
            }).data('datepicker');
            this.uploaderID = 'event_banner';
            var $container = $("#event_banner_container"), view = this;
            if (typeof this.banner_uploader === "undefined") {
                this.banner_uploader = new AE.Views.File_Uploader({
                    el: $container,
                    uploaderID: this.uploaderID,
                    thumbsize: 'medium',
                    multipart_params: {
                        _ajax_nonce: $container.find('.et_ajaxnonce').attr('id'),
                        data: {method: 'event_banner'},
                        imgType: this.uploaderID,
                    },
                    cbUploaded: function (up, file, res) {
                        if (res.success) {
                            $('#' + this.container).parents('.desc').find('.error').remove();
                        } else {
                            $('#' + this.container).parents('.desc').append('<div class="error">' + res.msg + '</div>');
                        }
                    },
                    beforeSend: function () {
                        view.blockUi.block($container);
                    },
                    success: function (res) {
                        if (res.success == false) {
                            AE.pubsub.trigger('ae:notification', {msg: res.msg, notice_type: 'error',});
                        } else {
                            view.model.set('featured_image', res.data.attach_id);
                        }
                        view.blockUi.unblock();
                    }
                });
            }
        },
        initValidator: function () {
            $("form#event_form").validate({
                ignore: "",
                rules: {post_title: "required", short_tag: "required", event_content: "required",},
                errorPlacement: function (label, element) {
                    if (element.is("textarea")) {
                        label.insertAfter(element.next());
                    } else {
                        $(element).closest('div').append(label);
                    }
                    AE.pubsub.trigger('ae:notification', {msg: ae_globals.msg, notice_type: 'error',});
                }
            });
        },
        onCreateCalender: function (place) {
            this.openModal();
            setTimeout(function () {
                if (typeof tinyMCE !== 'undefined') {
                    tinymce.EditorManager.execCommand('mceAddEditor', true, "event_content");
                }
            }, 500);
        },
        onEditCalender: function (model) {
            this.model = model;
            this.setupFields();
            this.openModal();
        },
        setupFields: function () {
            var view = this, form_field = view.$('.form-field'), location = this.model.get('location'), cover_image = view.model.get('large_thumbnail');
            AE.pubsub.trigger('AE:beforeSetupFields', this.model);
            setTimeout(function () {
                if (typeof tinyMCE !== 'undefined') {
                    tinymce.EditorManager.execCommand('mceAddEditor', true, "event_content");
                    tinymce.EditorManager.get('event_content').setContent(view.model.get('post_content'));
                }
            }, 500);
            form_field.find('input[type="text"],input[type="hidden"], textarea,select').each(function () {
                var $input = $(this);
                $input.val(view.model.get($input.attr('name')));
                if ($input.get(0).nodeName === "SELECT")$input.trigger('chosen:updated');
            });
            form_field.find('input[type="radio"]').each(function () {
                var $input = $(this), name = $input.attr('name');
                if ($input.val() == view.model.get(name)) {
                    $input.attr('checked', true);
                }
            });
            if (cover_image) {
                view.$('#event_banner_thumbnail').html('').append('<img style="width: 300px;" src="' + cover_image + '" />');
            }
        },
        submit: function (event) {
            event.preventDefault();
            var $form = $(event.currentTarget), temp = new Array(), view = this;
            if ($('form#event_form').valid()) {
                view.$el.find('input,textarea,select').each(function () {
                    view.model.set($(this).attr('name'), $(this).val());
                });
                view.$el.find('input[type=checkbox]:checked').each(function () {
                    var name = $(this).attr('name');
                    temp.push($(this).val());
                    view.model.set(name, temp);
                });
                view.$el.find('input[type=radio]:checked').each(function () {
                    view.model.set($(this).attr('name'), $(this).val());
                });
                view.model.save('', '', {
                    beforeSend: function () {
                        view.blockUi.block($form);
                    }, success: function (result, status, jqXHR) {
                        view.blockUi.unblock();
                        if (status.success) {
                            pubsub.trigger('ae:notification', {msg: status.msg, notice_type: 'success',});
                            window.location.reload();
                        } else {
                            pubsub.trigger('ae:notification', {notice_type: 'error', msg: status.msg});
                        }
                    }
                });
            }
        }
    });
    Views.SearchForm = Backbone.View.extend({
        el: '#header-wrapper',
        events: {'click .search-btn': 'triggerSearchForm'},
        initialize: function (options) {
            _.bindAll(this, 'showMap', 'errorHandle');
            var view = this;
            this.$('.slider-ranger').on('slide', function (ev) {
                var value = ev.value;
                $('#' + $(this).attr('data-name')).val(value);
                $('.' + $(this).attr('data-name')).html(value);
            });
            this.$('.nearby').on('slideStart', function () {
                navigator.geolocation.getCurrentPosition(view.showMap, view.errorHandle);
            });
            this.$('form').validate();
        },
        triggerSearchForm: function (event) {
            $option_search = $('.option-search-form-wrapper');
            $marsk = $('.marsk-black');
            $btn_topsearch = $('ul.top-menu-right li.top-search');
            $marsk.fadeToggle(300);
            $btn_topsearch.toggleClass('active');
            $option_search.slideToggle(300, 'easeInOutSine', function (event) {
                $('.slider-ranger').slider({tooltip: 'always'});
            });
        },
        showMap: function (position) {
            var coords = position.coords;
            $('#center').val(coords.latitude + ',' + coords.longitude);
            AE.pubsub.trigger('de:getCurrentPosition', position.coords);
        },
        errorHandle: function () {
            alert('Ha rechazado la solicitud de Geolocalización. Para utilizar esta función, habilítela.');
        }
    });
})(jQuery, window.AE, window.AE.Views, window.AE.Models, window.AE.Collections, Backbone);
jQuery.fn.serializeObject = function () {
    var self = this, json = {}, push_counters = {}, patterns = {
        "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
        "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
        "push": /^$/,
        "fixed": /^\d+$/,
        "named": /^[a-zA-Z0-9_]+$/
    };
    this.build = function (base, key, value) {
        base[key] = value;
        return base;
    };
    this.push_counter = function (key) {
        if (push_counters[key] === undefined) {
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };
    jQuery.each(jQuery(this).serializeArray(), function () {
        if (!patterns.validate.test(this.name)) {
            return;
        }
        var k, keys = this.name.match(patterns.key), merge = this.value, reverse_key = this.name;
        while ((k = keys.pop()) !== undefined) {
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
            if (k.match(patterns.push)) {
                merge = self.build([], self.push_counter(reverse_key), merge);
            } else if (k.match(patterns.fixed)) {
                merge = self.build([], k, merge);
            } else if (k.match(patterns.named)) {
                merge = self.build({}, k, merge);
            }
        }
        json = jQuery.extend(true, json, merge);
    });
    return json;
};