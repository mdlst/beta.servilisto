/**
 * Created by Miguel on 14/11/2016.
 */

/**
 * Carga los dos captchas (login y publica-tu-anuncio)
 */
function onloadCallbackRecaptcha() {
    if (jQuery("#recaptcha_register").length) {
        grecaptcha.render('recaptcha_register', {
            'sitekey': '6LfqjzIUAAAAAMp7IcTdBrXwtaN6XhYTizYiVLe4', //Replace this with your Site key
            'theme': 'light'
        });
    }
    if (jQuery("#recaptcha_publica_tu_anuncio").length) {
        grecaptcha.render('recaptcha_publica_tu_anuncio', {
            'sitekey': '6LfqjzIUAAAAAMp7IcTdBrXwtaN6XhYTizYiVLe4', //Replace this with your Site key
            'theme': 'light'
        });
    }

}

function captchaCallbackRegister() {
    jQuery("#recaptcha_register_1").val(1);
}
function captchaCallbackPublishPlace() {
    jQuery("#recaptcha_publica_tu_anuncio_1").val(1);
}
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

Date.prototype.getDateHoy = function () {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
    var dd = this.getDate().toString();
    return (dd[1] ? dd : "0" + dd[0]) + "/" + (mm[1] ? mm : "0" + mm[0]) + "/" + yyyy; // padding
};

function getParamsGET_URL(name, url) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    return results == null ? null : results[1];
}


function setParamsGET_URL(url, param, value) {
    var hash = {};
    var parser = document.createElement('a');

    parser.href = url;

    var parameters = parser.search.split(/\?|&/);

    for (var i = 0; i < parameters.length; i++) {
        if (!parameters[i])
            continue;

        var ary = parameters[i].split('=');
        hash[ary[0]] = ary[1];
    }

    hash[param] = value;

    var list = [];
    Object.keys(hash).forEach(function (key) {
        list.push(key + '=' + hash[key]);
    });

    parser.search = '?' + list.join('&');
    return parser.href;
}

function geolocalizacion(forzar, refrescar) {

    if (parseInt(ae_globals.geolocation)) {

        if (forzar == 1) {
            frontEraseCookie('current_user_preguntado');
            frontEraseCookie('current_user_lat');
            frontEraseCookie('current_user_lng');
            frontEraseCookie('current_user_locality');
            frontEraseCookie('current_user_province');
            frontEraseCookie('current_user_postal_code');
        }

        if (frontGetCookie('current_user_preguntado') == 0 || frontGetCookie('current_user_preguntado') == null) {
            GMaps.geolocate({
                success: function (position) {

                    frontSetCookie('current_user_preguntado', 1, 1);

                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    var geocoder = new google.maps.Geocoder();

                    var latlng = new google.maps.LatLng(lat, lng);

                    geocoder.geocode({'latLng': latlng}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {

                            if (results[1]) {
                                for (var i = 0; i < results[0].address_components.length; i++) {
                                    for (var b = 0; b < results[0].address_components[i].types.length; b++) {

                                        //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate

                                        if (results[0].address_components[i].types[b] == "locality") {
                                            //this is the object you are looking for
                                            var locality = results[0].address_components[i];
                                            break;
                                        }
                                        if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                                            //this is the object you are looking for
                                            var provincia = results[0].address_components[i];
                                            break;
                                        }

                                        if (results[0].address_components[i].types[b] == "postal_code") {
                                            //this is the object you are looking for
                                            var codigo_postal = results[0].address_components[i];
                                            break;
                                        }

                                    }
                                }

                                /* Establecemos las cookies de geolocalización */
                                frontSetCookie('current_user_lat', lat, 1);
                                frontSetCookie('current_user_lng', lng, 1);

                                if (locality.long_name) {
                                    frontSetCookie('current_user_locality', locality.long_name, 1);
                                }

                                if (provincia.long_name)
                                    frontSetCookie('current_user_province', provincia.long_name, 1);

                                if (codigo_postal.long_name)
                                    frontSetCookie('current_user_postal_code', codigo_postal.long_name, 1);

                                AE.pubsub.trigger('ae:notification', {
                                    msg: ae_globals.geolocation_success,
                                    notice_type: 'success'
                                });

                                if (refrescar == 1) {  // en caso de exito y forzado, refrescamos

                                    location.href = setParamsGET_URL(location.href, 'l', 'cerca-de-ti');
                                }

                                rellenarCamposConLocalizacion();
                                /////////////////////////////////////////////////////


                            } else {

                            }
                        } else {

                        }
                    });


                },
                error: function (error) {

                    frontSetCookie('current_user_preguntado', 2, 1);

                    AE.pubsub.trigger('ae:notification', {
                        msg: ae_globals.geolocation_failed,
                        notice_type: 'error'
                    });

                    frontEraseCookie('current_user_lat');  // lo reiniciamos
                    frontEraseCookie('current_user_lng');
                    frontEraseCookie('current_user_locality');
                    frontEraseCookie('current_user_province');
                    frontEraseCookie('current_user_postal_code');
                    /*           frontEraseCookie('current_user_preguntado');  // volvemos a preguntar*/

                },
                not_supported: function () {
                    frontSetCookie('current_user_preguntado', 3, 1);

                    AE.pubsub.trigger('ae:notification', {
                        msg: ae_globals.browser_supported,
                        notice_type: 'error'
                    });
                    frontEraseCookie('current_user_lat');  // lo reiniciamos
                    frontEraseCookie('current_user_lng');
                    frontEraseCookie('current_user_locality');
                    frontEraseCookie('current_user_province');
                    frontEraseCookie('current_user_postal_code');

                }
            });
        }

    }
}


function rellenarCamposConLocalizacion() {

    var localizacion_user = "";
    if (frontGetCookie('current_user_province')) {
        localizacion_user = frontGetCookie('current_user_province');
    }
    if (frontGetCookie('current_user_locality') && frontGetCookie('current_user_province')) {
        localizacion_user = frontGetCookie('current_user_locality') + ", " + frontGetCookie('current_user_province');
    }

    // HEADER -> en el buscador avanzado, en el select de las provincias
    if (jQuery('#location-advanced-search').length > 0 && localizacion_user) {
        jQuery(('<option class="" value="cerca-de-ti">Cerca de ti (' + localizacion_user + ')</option>')).insertAfter("#location-advanced-search option[data='por-defecto']");
        jQuery('#location-advanced-search').val("cerca-de-ti");
        jQuery('#location-advanced-search').trigger("chosen:updated");
    }

    // PUBLICA-TU-ANUNCIO -> en el buscador avanzado, en el select de las provincias
    if (jQuery('#post-place #et_full_location').length > 0 && frontGetCookie('current_user_postal_code')) {
        var postal_code = frontGetCookie('current_user_postal_code').toLowerCase();
        jQuery('#post-place #et_full_location').val(postal_code);
        jQuery('#post-place #et_full_location').keyup()
    }


    // HEADER -> en el buscador avanzado, en el select de las provincias
    if (jQuery('#location-advanced-search-home').length > 0 && frontGetCookie('current_user_province')) {
        jQuery(('<option class="" value="cerca-de-ti">Cerca de ti (' + localizacion_user + ')</option>')).insertAfter("#location-advanced-search-home option[data='por-defecto']");
        jQuery('#location-advanced-search-home').val("cerca-de-ti");
        jQuery('#location-advanced-search-home').trigger("chosen:updated");
    }

    // en Search.php -> en el buscador de provincias de place-filter
    if (jQuery('#place-filter-select-provincias').length > 0 && frontGetCookie('current_user_province') && getParamsGET_URL("l") == "cerca-de-ti") {
        jQuery(('<option class="" value="cerca-de-ti">Cerca de ti (' + localizacion_user + ')</option>')).insertAfter("#place-filter-select-provincias option[data='por-defecto']");
        jQuery('#place-filter-select-provincias').val("cerca-de-ti");
        jQuery('#place-filter-select-provincias').trigger("chosen:updated");
    }


}
function scrollToErrorDatos(id) {

    if (!id.length)
        return;

    var offset = id.parent().offset().top - 100;
    if (jQuery("html,body").is(':animated') == false) {
        jQuery('html,body').animate({
            scrollTop: offset
        }, 800);
    }
}

function frontSetCookie(c_name, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : "; path=/ ; expires=" + exdate.toUTCString());
}
function frontGetCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) c_end = document.cookie.length
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return ""
}
function frontEraseCookie(name) {
    frontSetCookie(name, "", -1);
}


jQuery(document).ready(function () {

    jQuery.ajaxSetup({
        cache: false
    });

    /**
     * Bloqueamos la subida de fotos desde APP
     */
    jQuery(".aviso-movil-upload").on("click", function (e) { // getIfApp
        var mimsg = "";

        if (jQuery(this).hasClass('modal-edit-place-cabecera-fotos')) {
            mimsg = "Para modificar las imagenes del anuncio, use la versión escritorio de Servilisto desde un ordenador";
        }
        else if (jQuery(this).hasClass('modal-edit-place-imageanuncio')) {
            mimsg = "Para modificar las imagen del anuncio, use la versión escritorio de Servilisto desde un ordenador";
        }
        else if (jQuery(this).hasClass('page-profile-imagen')) {
            mimsg = "Para modificar tu imagen de perfil, use la versión escritorio de Servilisto desde un ordenador";
        }
        else if (jQuery(this).hasClass('post-place-step3-anuncio')) {
            mimsg = "Para subir una imagen a tu anuncio, use la versión escritorio de Servilisto desde un ordenador";
        }
        else {
            mimsg = "Para modificar imagenes, use la versión escritorio de Servilisto desde un ordenador";
        }

        AE.pubsub.trigger('ae:notification', {
            msg: mimsg,
            notice_type: 'warning'
        });

        return true;
    });

    rellenarCamposConLocalizacion();  // rellenar todos los campos posibles si tenemos las cookies de localización

    jQuery("#startGeolocation").on("click", function (e) {
        var refrescar = 0;
        if (jQuery(this).hasClass("search") ||
            jQuery(this).hasClass("single-place") ||
            jQuery(this).hasClass("category") ||
            jQuery(this).hasClass("mobile")) {
            refrescar = 1;
        }
        geolocalizacion(1, refrescar);
    });

    jQuery('.open-time,.close-time,.open-time-2,.close-time-2').on('changeTime', function () {

        if (this.value != "") {
            jQuery("#alguna_opcion_date_seleccionada").val(1);
            jQuery('.open-block').removeClass("error");
            jQuery('.open-block').find("div.message").remove();
        }
        else {
            jQuery("#alguna_opcion_date_seleccionada").val("");
            jQuery('.open-block').addClass("error");
            jQuery('.open-block').find("div.message").remove();
            jQuery(".open-block").append("<div for='alguna_opcion_date_seleccionada' class='message'>Este campo es obligatorio</div>");
        }

    });

    // para poder validar urls (sin http)
    jQuery.validator.addMethod("url2", function (val, elem) {

        if (val.length == 0) {
            return true;
        }

        if (!/^(https?|ftp):\/\//i.test(val)) {
            val = 'http://' + val;
            jQuery(elem).val(val);
        }

        return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
    }, jQuery.validator.messages.url);


    // cierra el menu header pulsando fuera
    jQuery("html,body").click(function (event) {
        if (!jQuery(event.target).closest("header").length) {
            jQuery("header .top-menu-center").removeClass("abierto");
            jQuery("header .top-menu-right").removeClass("abierto");
        }
    });

    jQuery("header .menu-toogle").click(function () {

        if (window.outerWidth > 768 && window.outerWidth < 992) { //tablet
            jQuery("header .top-menu-center").toggleClass("abierto");
        }
        else {  //movil
            jQuery("header .top-menu-center").toggleClass("abierto");
            jQuery("header .top-menu-right").toggleClass("abierto");
        }
    });
    /*
     jQuery(document.body).on('click', '.category_description_seo i.fa', function (e) {
     if (jQuery(this).hasClass("fa-caret-up")) {
     jQuery(".category_description_seo").data("height-original", jQuery(".category_description_seo").outerHeight())
     jQuery(this).parent().animate({height: 50}, 200);
     jQuery(this).addClass("fa-caret-down").removeClass("fa-caret-up");

     }
     else {
     jQuery(this).parent().animate({height: jQuery(".category_description_seo").data("height-original")}, 200);
     jQuery(this).addClass("fa-caret-up").removeClass("fa-caret-down");
     }
     });*/


    jQuery("#ir-a-mensaje .close").click(function () {
        frontSetCookie('no_ver_modal_mensajes', 1, 1);
    });

    jQuery(document).on('click', '.tipo_servicio input', function (e) {
        if (jQuery(this).val() == "horas") {
            jQuery(".tipo_servicio_hora").show()
            jQuery(".tipo_servicio .aclaratoria_tipo_servicio").html("Los usuarios podrán reservar hora a hora").css("display", "block")
            jQuery(".tipo_servicio_hora .tiempo_servicio").hide()
            jQuery(".tipo_servicio_hora .title-plan").html("<span class='mandatory_fields'>*</span>PRECIO POR HORA<span>Indique el precio por hora</span>");
            jQuery(".tipo_servicio_hora .title-plan-modal").html("<label>PRECIO POR HORA<span class='alert-icon'>*</span></label>");
        }
        if (jQuery(this).val() == "servicio") {
            jQuery(".tipo_servicio_hora").show()
            jQuery(".tipo_servicio .aclaratoria_tipo_servicio").html("Los usuarios reservarán por servicios completos").css("display", "block")
            jQuery(".tipo_servicio_hora .tiempo_servicio").show()
            jQuery(".tipo_servicio_hora .title-plan").html("<span class='mandatory_fields'>*</span>PRECIO POR SERVICIO<span>Indique el precio y la duración del servicio</span>");
            jQuery(".tipo_servicio_hora .title-plan-modal").html("<label>PRECIO POR SERVICIO<span class='alert-icon'>*</span></label>");
        }
        if (jQuery(this).val() == "convenir") {
            jQuery(".tipo_servicio_hora").hide()
            jQuery(".tipo_servicio .aclaratoria_tipo_servicio").html("El tiempo y la duración será negociado individualmente con los usuarios").css("display", "block")
            jQuery("#hourly_rate1").val("")
            jQuery("#duracion_servicio").val("").trigger('chosen:updated');
        }

    });


    jQuery(".box_home .fa.fa-map-marker").click(function () {
        geolocalizacion(1, 0);

    });

    jQuery(document).on('click', '.quitar_geo', function (e) {

        jQuery(".quitar_geo img").show();

        frontEraseCookie('current_user_preguntado');
        frontEraseCookie('current_user_lat');
        frontEraseCookie('current_user_lng');
        frontEraseCookie('current_user_locality');
        frontEraseCookie('current_user_province');
        frontEraseCookie('current_user_postal_code');

        location.href = setParamsGET_URL(location.href, 'l', '');


    });

    jQuery(".select-category-with-clear-button .fa-times").click(function () {
        var id_select = "#" + jQuery(this).parent().find("select").attr("id");

        jQuery(id_select).val("");
        jQuery(id_select).trigger("chosen:updated");
        jQuery(id_select).change()

    });

    /**
     * En version movil añadimos el autoscroll al EDITOR
     */
    if (jQuery("#post-place").length) {
        setTimeout(function () {

            var a = tinymce.editors[0].on("focus", function (el_this) {
                var center = jQuery(window).height() / 4;
                var top = jQuery("#wp-post_content-wrap").offset().top;
                if (top > center) {
                    jQuery('html, body').animate({scrollTop: top - center}, '2000');
                }
            });
        }, 300);
    }

    /**
     * En version movil añadimos el autoscroll a los campos con .onfocustop
     */
    jQuery("#post-place .onfocustop").on('click', function () {
        var center = jQuery(window).height() / 4;
        var top = jQuery(this).offset().top;
        if (top > center) {
            jQuery('html, body').animate({scrollTop: top - center}, '2000');
        }


    });

    /**
     * Para que en el buscador principal de la home salte a paginas y no al search.php
     */
    jQuery(".box_home form").submit(function (event) {

        var cadena_enviar = "?l=&s=";  // search.php
        if (jQuery("#place_category-single").val() != null && (jQuery("#place_category-single").val())[0] != "") {
            cadena_enviar = (jQuery("#place_category-single").val())[0]
            if (jQuery("#location-advanced-search-home").val()) {
                cadena_enviar += "/en-" + (jQuery("#location-advanced-search-home").val());
            }
        }
        else {
            if (jQuery("#location-advanced-search-home").val()) {
                cadena_enviar = jQuery("#location-advanced-search-home").val();
            }
        }
        
        window.location.replace(site_url + "/" + cadena_enviar);
        return false;
    });

});

