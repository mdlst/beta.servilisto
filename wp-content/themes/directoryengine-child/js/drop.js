;(function ($) {
    $.fn.drop = function (callback) {
        this.each(function (i, v) {
            $(v).bind('init.drop', $.fn.drop.init).bind('render.drop', $.fn.drop.render).trigger('init.drop');
        });
    };
    $.extend($.fn.drop, {
        init: function (e) {
            var el = $(this), id = $(el).attr('id'), html = '';
            el.hide();
            html += '<div class="chosen-container chosen-container-multi chosen" id="' + id + '_drop"><ul class="chosen-choices"><li class="search-field"><input type="text" placeholder="Selecciona aqui las categorias donde quieres aparecer" class="" autocomplete="off" style="width: 25px;"></li></ul><div class="chosen-drop"><ul class="chosen-results main-menu"></ul></div></div>';
            el.after(html);
            setTimeout(function () {
                el.trigger('render.drop');
            }, 500);
            $('#' + id + '_drop input').on('click', function () {
                $('#' + id + '_drop').addClass('chosen-with-drop chosen-container-active');
                el.trigger('render.drop');
            });
            $('#' + id + '_drop input').on('keyup', function () {
                var search = $(this).val().toLowerCase();
                if (search.length != 0) {
                    $('#' + id + '_drop .chosen-results li').hide();
                    $('#' + id + '_drop .chosen-results a span').each(function () {
                        var word = $(this).html().toLowerCase();
                        if (word.indexOf(search) > -1) {
                            $(this).parents('.chosen-results li').show();
                            $(this).parents('.level-0').addClass('active');
                            $(this).parents('.level-1').addClass('active');
                        }
                    });
                } else
                    el.trigger('render.drop');
            });
            $('body').on('click', function (e) {
                var target = $(e.target);
                if (target.find('#' + id + '_drop').length != 0) {
                    $('#' + id + '_drop').removeClass('chosen-with-drop chosen-container-active');
                    el.trigger('render.drop');
                }
            });
        }, render: function (e) {
            var el = $(this), id = $(el).attr('id'), text = '', level = '', clas = '', selected = '',
                selected_text = '';
            $('#' + id + '_drop .search-choice').remove();
            $('#' + id + ' option').each(function () {
                var disabled = '';
                //selected = $(this).attr('selected');
                selected = $(this).is(":checked");
                if (selected != undefined && selected != '') {
                    selected_text += '<li class="search-choice"><span>' + $(this).html() + '</span><a class="search-choice-close" data-option-array-index="' + $(this).attr('value') + '"></a></li>';
                    disabled = ' selected';
                }
                text = text + '<li class="active-result' + disabled + ' ' + $(this).attr('class') + '" data-id = "' + $(this).attr('value') + '">' + $(this).html() + '</li>';
            });
            $('#' + id + '_drop .search-field').before(selected_text);
            $('#' + id + '_drop .chosen-results').html(text);
            $('#' + id + '_drop .chosen-results li').each(function () {
                var classList = $(this).attr('class').split(/\s+/);
                $.each(classList, function (index, item) {
                    if (item.match("^cat-")) {
                        if ($('#' + id + '_drop .child-' + item)[0]) {
                            if ($.inArray('level-0', classList) > -1) {
                                clas = 'class="categories-wrapper"';
                                level = 'level2';
                            } else if ($.inArray('level-1', classList) > -1) {
                                level = 'level3';
                                clas = '';
                            }
                            var text = $('#' + id + '_drop .' + item).text();
                            var html = '<a href="javascript:void(0)" ' + clas + '><span>' + text + '</span><i class="fa fa-angle-double-right fa-6" aria-hidden="true"></i></a><ul class="' + level + '">';
                            $('#' + id + '_drop .child-' + item).each(function () {
                                html = html + $(this)[0].outerHTML;
                                $(this).remove();
                            });
                            html = html + '</ul></li>';
                            $('#' + id + '_drop .' + item).html(html);
                        } else {
                            if ($.inArray('level-0', classList) > -1) {
                                clas = 'class="categories-wrapper"';
                                level = 'level2';
                            } else if ($.inArray('level-1', classList) > -1) {
                                level = 'level3';
                                clas = '';
                            }
                            var text = $('#' + id + '_drop .' + item).text();
                            $('#' + id + '_drop .' + item).html('<a ' + clas + ' href="javascript:void(0)"><span>' + text + '</span></a>');
                        }
                    }
                });
            });
            $('#' + id + '_drop .main-menu > li.level-0').on('click', function () {
                if ($(this).hasClass('active')) {
                    $('.main-menu > li').removeClass('active');
                } else {
                    $('.main-menu > li').removeClass('active');
                    $(this).addClass('active');
                }
            });
            $('#' + id + '_drop .level2 > li').on('click', function (event) {
                event.stopPropagation()
                if ($(this).hasClass('active')) {
                    $('.level2 > li').removeClass('active');
                } else {
                    $('.level2 > li').removeClass('active');
                    $(this).addClass('active');
                }
            });
            $('#' + id + '_drop .chosen-results a').on('click', function () {
                if (!$(this).next('ul')[0]) {
                    var value = $(this).parent().attr('data-id');
                    $('#' + id + ' option[value="' + value + '"]').attr('selected', 'selected');
                    $('#' + id).parent().removeClass('error').find('.message').remove();
                    $('#' + id + '_drop').toggleClass('chosen-with-drop chosen-container-active');
                    $('#' + id + '_drop input').val('');
                    el.trigger('render.drop');
                }
            });
            $('#' + id + '_drop .chosen-choices .search-choice-close').on('click', function () {
                var value = $(this).attr('data-option-array-index');
                $('#' + id + ' option[value="' + value + '"]').removeAttr('selected');
                el.trigger('render.drop');
            });
        }
    });
    $.fn.dropsingle = function (callback) {
        var el = $(this), id = $(el).attr('id'), html = '';
        init();

        function init() {
            el.hide();
            html += '<div class="chosen-container chosen-container-single" id="' + id + '_drop" style="width:270px"><a class="chosen-single" tabindex="-1"><span><font><font>Selecciona tu categoría</font></font></span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results main-menu"></ul></div></div>';
            el.after(html);
        }

        function render() {
            var text = '', level = '', clas = '', selected = '', selected1 = '', selected_text = '';
            $('#' + id + ' option').each(function () {
                var disabled = '';
                selected = $(this).is(":checked");

                if (selected != undefined && selected != '') {
                    $('#' + id + '_drop .chosen-single span').html($(this).html());
                }
                if (typeof($(this).attr('class')) != "undefined") text = text + '<li class="active-result ' + $(this).attr('class') + '" data-id = "' + $(this).attr('value') + '">' + $(this).html() + '</li>';
            });
            $('#' + id + '_drop .chosen-results').html(text);
            $('#' + id + '_drop .chosen-results li').each(function () {
                var classList = $(this).attr('class').split(/\s+/);
                $.each(classList, function (index, item) {
                    if (item.match("^cat-")) {
                        if ($('#' + id + '_drop .child-' + item)[0]) {
                            if ($.inArray('level-0', classList) > -1) {
                                clas = 'class="categories-wrapper"';
                                level = 'level2';
                            } else if ($.inArray('level-1', classList) > -1) {
                                level = 'level3';
                                clas = '';
                            }
                            var text = $('#' + id + '_drop .' + item).text();
                            var html = '<a href="javascript:void(0)" ' + clas + '><span>' + text + '</span><i class="fa fa-angle-double-right fa-6" aria-hidden="true"></i></a><ul class="' + level + '">';
                            $('#' + id + '_drop .child-' + item).each(function () {
                                html = html + $(this)[0].outerHTML;
                                $(this).remove();
                            });
                            html = html + '</ul></li>';
                            $('#' + id + '_drop .' + item).html(html);
                        } else {
                            if ($.inArray('level-0', classList) > -1) {
                                clas = 'class="categories-wrapper"';
                                level = 'level2';
                            } else if ($.inArray('level-1', classList) > -1) {
                                level = 'level3';
                                clas = '';
                            }
                            var text = $('#' + id + '_drop .' + item).text();
                            $('#' + id + '_drop .' + item).html('<a ' + clas + ' href="javascript:void(0)"><span>' + text + '</span></a>');
                        }
                    }
                });
            });
            $('#' + id + '_drop .chosen-drop').css('background', 'none').css('border', 'none');
            $('#' + id + '_drop .main-menu > li.level-0').on('click', function () {
                if ($(this).hasClass('active')) {
                    $('.main-menu > li').removeClass('active');
                } else {
                    $('.main-menu > li').removeClass('active');
                    $(this).addClass('active');
                }
            });
            $('#' + id + '_drop .level2 > li').on('click', function (event) {
                event.stopPropagation()
                if ($(this).hasClass('active')) {
                    $('.level2 > li').removeClass('active');
                } else {
                    $('.level2 > li').removeClass('active');
                    $(this).addClass('active');
                }
            });
            $('#' + id + '_drop .chosen-results a').on('click', function () {
                if (!$(this).next('ul')[0]) {

                    /* limpiamos los selected antiguos. Miguel*/
                    $('#' + id + ' option').each(function () {
                            $(this).attr("selected", false);
                        }
                    );

                    var value = $(this).parent().attr('data-id');
                    $('#' + id + ' option[value="' + value + '"]').attr('selected', 'selected');
                    $('#' + id + '_drop').toggleClass('chosen-with-drop chosen-container-active');
                    $('#' + id + '_drop input').val('');

                    if ($('#' + id).hasClass("con-click-event")) { // miguel, generamos el evento change
                        $('#' + id).change();
                    }

                    render()
                } else if ($(this).parent().hasClass('active')) {
                    var value = $(this).parent().attr('data-id');
                    $('#' + id + ' option[value="' + value + '"]').attr('selected', 'selected');
                    $('#' + id + '_drop').toggleClass('chosen-with-drop chosen-container-active');
                    $('#' + id + '_drop input').val('');
                    render()
                }
            });
        }

        $('#' + id + '_drop .chosen-single').on('click', function () {
            if ($('#' + id + '_drop').hasClass('chosen-with-drop chosen-container-active')) $('#' + id + '_drop').removeClass('chosen-with-drop chosen-container-active')
            else
                $('#' + id + '_drop').addClass('chosen-with-drop chosen-container-active')
            render();
        });
        $('#' + id + '_drop input').on('keyup', function () {
            var search = $(this).val().toLowerCase();
            if (search.length != 0) {
                $('#' + id + '_drop .chosen-results li').hide();
                $('#' + id + '_drop .chosen-results a span').each(function () {
                    var word = $(this).html().toLowerCase();
                    if (word.indexOf(search) > -1) {
                        $(this).parents('.chosen-results li').show();
                        $(this).parents('.level-0').addClass('active');
                        $(this).parents('.level-1').addClass('active');
                    }
                });
            } else
                render();
        });
        $('body').on('click', function (elem) {
            var target = $(elem.target);
            if (target.parents('#' + id + '_drop').length == 0) {
                $('#' + id + '_drop').removeClass('chosen-with-drop chosen-container-active');
                render();
            }
        });
    };
})(jQuery);