<?php


global $wpdb, $current_user;

// obtenemos los anuncios del usuario
$anuncios_con_chat = $wpdb->get_results("SELECT * FROM chat WHERE implicados LIKE '%[$current_user->ID]%' GROUP BY id_anuncio");

?>


<div class="content-reviews tab-pane fade" id="tab-chat">

    <ul class="list-place-publishing">
        <li class="col-md-12 nopadding-mobile">
            <div class="event-active-wrapper">
                <div class="col-md-12 nopadding-mobile">
                    <div class="event-wrapper tab-style-event">
                        <?php
                        if ($anuncios_con_chat) {
                            ?>

                            <?php
                            // obtenemos los diferentes chats

                            foreach ($anuncios_con_chat as $anuncio) {

                                $id_anuncio = $anuncio->id_anuncio;


                                $anuncio = get_post($id_anuncio);

                                $implicados_consulta =
                                    $wpdb->get_results("SELECT * FROM chat WHERE id_anuncio=$id_anuncio AND implicados LIKE '%[$current_user->ID]%'");

                                // buscamos el implicado que no sea el autor, es decir, el destinatario
                                $destinatario_procesado = array();
                                foreach ($implicados_consulta as $implicados) {


                                    $id_destinatario = getImplicadoChat($implicados->implicados, $current_user->ID);

                                    if (!in_array($id_destinatario, $destinatario_procesado)) {


                                        array_push($destinatario_procesado, $id_destinatario);
                                        $user = get_user_by("id", $id_destinatario);

                                        $url_image = get_the_post_thumbnail_url($id_anuncio);
                                        if (!$url_image){  // cogemos la defecto
                                            $url_image=site_url()."/wp-content/uploads/2017/06/sinfoto27.280.jpg";
                                        }

                                        ?>
                                        <div class="caja_mensaje_chat row">
                                            <div class="cabecera_chat">
                                                <div class="col-md-3 col-sm-2 hidden-xs">
                                                    <div class="container-center-img">
                                                        <img class="foto-anuncio" src="<?= $url_image ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6  col-sm-8  col-xs-12">
                                                    <p class="titulo_anuncio"><?= $anuncio->post_title ?></p>
                                                    <p class="publicado_el">
                                                        <span>Usuario: </span><?= $user->display_name ?></p>
                                                </div>
                                                <div class="col-md-3 col-sm-4  col-xs-12 donde-va-notificaciones">

                                                    <?php

                                                    $nleidos = tieneChatNoLeidos_inAnuncio_entreUsuarios($id_anuncio, $current_user->ID, $user->ID);
                                                    if ($nleidos) {
                                                        echo ' <span class="notifications">Tiene mensajes nuevos</span>';
                                                    }
                                                    ?>

                                                </div>
                                                <div class="clear"></div>
                                            </div>

                                            <div data-id_useractual="<?= $current_user->ID ?>"
                                                 data-id_otrouser="<?= $user->ID ?>"
                                                 data-id_anuncio="<?= $id_anuncio ?>"
                                                 class="caja_diferentes_mensajes col-md-12">
                                                <div class="historial">

                                                    <?php

                                                    $diferentes_mensajes = getMensajesEntreUsers_byIdAnuncio($id_anuncio, $current_user->ID, $user->ID);

                                                    $dia_actual = 0;
                                                    foreach ($diferentes_mensajes as $mensaje) {
                                                        if ($mensaje->fecha_formateada != $dia_actual) {
                                                            $dia_actual = $mensaje->fecha_formateada;
                                                            echo " <p class='dia'>$dia_actual</p>";
                                                        }

                                                        $implicados_array = explode(",", $mensaje->implicados);
                                                        ?>


                                                        <div class="mensaje <?= ($implicados_array[0] == "[" . $current_user->ID . "]") ? "propio" : "npropio" ?>">
                                                            <span class="texto"><?= str_replace("\n", "<br>", $mensaje->mensaje) ?></span>
                                                            <span class="hora"><?= $mensaje->hora ?></span>
                                                        </div>
                                                        <div class="clear"></div>

                                                        <?php

                                                        // los mostramos como cargados
                                                        ponerMensajeComoCargado($mensaje->id, $current_user->ID);

                                                    }
                                                    ?>

                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="caja-responder col-md-12">
                                                    <div class="col-md-10 col-sm-10  col-xs-10">
                                                        <textarea class="textarea"></textarea>
                                                        <span class="mensaje-error-textarea"></span>
                                                        <span class="mensaje-success-textarea"></span>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2  col-xs-2 div-enviar hover">
                                                        <span class="submit">ENVIAR</span>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <?php
                                    }
                                }
                            }
                        } else { ?>
                            <h2 class="title-envent no-title-envent ">Actualmente, no tiene ningún mensaje.</h2>
                            <?php
                        }
                        ?>


                    </div>
                </div>
            </div>
        </li>
    </ul>

</div>

<!--FUNCIONALIDAD DE CHAT-->

<?php
if (isset($_GET["tab-chat"])) {
    ?>
    <!--carga la pestaña de chat directamente-->
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(".chat a").click()
        });
    </script>
    <?php
}
?>

<!--tiempo real y funciones para el chat-->
<script type="text/javascript">

    var intervalo_tiempo = 7000;
    if (location.hostname == "localhost") intervalo_tiempo = 15000;

    go_tr();

    function go_tr() {
        setInterval(bucle_tr, intervalo_tiempo)
    }
    function bucle_tr() {
        jQuery(".caja_mensaje_chat").each(function () {
            tiemporeal_chat(this)
        });
    }
    function tiemporeal_chat(el_this) {
        var caja = jQuery(el_this);
        var id_useractual = jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_useractual");
        var id_otrouser = jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_otrouser");
        var id_anuncio = jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_anuncio");
        var last_day_en_mensaje = jQuery(caja).find(".historial .dia").last().html();

        jQuery.ajax({
            data: {
                id_useractual: id_useractual,
                id_otrouser: id_otrouser,
                id_anuncio: id_anuncio,
                last_day_en_mensaje: last_day_en_mensaje,
                action: 'tiempo-real-chat'
            },
            url: ae_globals.ajaxURL,
            dataType: 'json',
            type: 'POST',
            success: function (response) {
                if (response.code == 1) {
                    jQuery(caja).find(".historial").append(response.append);

                    // me voy en el scroll al final
                    var historial_this = jQuery(caja).find(".historial")[0];
                    var scrollHeight = Math.max(historial_this.scrollHeight, historial_this.clientHeight);
                    historial_this.scrollTop = scrollHeight - historial_this.clientHeight;

                    // si el usuario tiene cerrada la caja y no tiene puesto el "tiene notificaciones se lo ponemos"

                    if (!jQuery(caja).find(".caja_diferentes_mensajes ").hasClass("active")) { // cerrada
                        if (jQuery(caja).find(".notifications").size() == 0) { // no lo tiene puesto
                            jQuery(caja).find(".donde-va-notificaciones").append('<span class="notifications">Tiene mensajes nuevos</span>')
                        }
                    }
                    else {  // tiene la caja abierta, se lo marcamos como leido

                        jQuery(".tienenotificaciones").remove();
                        jQuery(".notifications_has_link").remove();


                        jQuery.ajax({
                            data: {
                                id_useractual: id_useractual,
                                id_otrouser: id_otrouser,
                                id_anuncio: id_anuncio,
                                action: 'poner-como-leidos-chat',
                            },
                            url: ae_globals.ajaxURL,
                            dataType: 'json',
                            type: 'POST',
                            beforeSend: function () {
                                jQuery(caja).find(".notifications").remove();
                            }
                        });

                    }
                }

            }
        });
    }


    jQuery(document).ready(function () {


        jQuery("#tab-chat .caja_mensaje_chat textarea").keypress(function (e) {

            if (e.which == 13 && e.shiftKey) {
                e.preventDefault(); //Stops enter from creating a new line
                jQuery(this).val(jQuery(this).val() + "\n");
            }
            else if (e.which == 13) {
                e.preventDefault(); //Stops enter from creating a new line
                jQuery("#tab-chat .caja-responder .div-enviar .submit").click();
            }


        });


        jQuery("#tab-chat .caja_mensaje_chat .cabecera_chat").click(function () {
            var caja = jQuery(this).closest(".caja_mensaje_chat");

            if (jQuery(caja).find(".caja_diferentes_mensajes").hasClass("active")) {
                jQuery(caja).find(".caja_diferentes_mensajes").removeClass("active")

                //escondemos los mensajes de error si los hubiera
                jQuery(caja).find(".mensaje-error-textarea").hide();
                jQuery(caja).find(".mensaje-error-textarea").html("");

                jQuery(caja).find(".mensaje-success-textarea").hide();
                jQuery(caja).find(".mensaje-success-textarea").html("");

            }
            else {
                jQuery(caja).find(".caja_diferentes_mensajes").addClass("active");

                // me voy en el scroll al final
                var historial_this = jQuery(caja).find(".historial")[0];
                var scrollHeight = Math.max(historial_this.scrollHeight, historial_this.clientHeight);
                historial_this.scrollTop = scrollHeight - historial_this.clientHeight;

                // dejamos los mensajes como leidos
                jQuery.ajax({
                    data: {
                        id_useractual: jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_useractual"),
                        id_otrouser: jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_otrouser"),
                        id_anuncio: jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_anuncio"),
                        action: 'poner-como-leidos-chat',
                    },
                    url: ae_globals.ajaxURL,
                    dataType: 'json',
                    type: 'POST',
                    beforeSend: function () {
                        jQuery(caja).find(".notifications").remove();
                    }
                });

            }
        });

        jQuery("#tab-chat .caja-responder .div-enviar .submit").click(function () {
            var caja = jQuery(this).closest(".caja_mensaje_chat");
            var textarea = jQuery(caja).find("textarea");
            var mensaje_error = jQuery(caja).find(".mensaje-error-textarea");
            var mensaje_exito = jQuery(caja).find(".mensaje-success-textarea");
            var btn_submit = jQuery(caja).find(".submit")
            var loader = jQuery(caja).find(".loader")


            if (jQuery(textarea).val() == "") {
                jQuery(mensaje_error).show();
                jQuery(mensaje_error).html("*Debe escribir un mensaje");
            }
            else {
                jQuery(mensaje_error).hide();
                jQuery(mensaje_error).html("");

                //añadimos el mensaje a la caja y luego enviamos

                var mensaje = jQuery(textarea).val()
                mensaje = mensaje.replace("\n", "<br>");

                var append = "";
                var date = new Date();
                var hora = addZero(date.getHours()) + ":" + addZero(date.getMinutes()) + ":" + addZero(date.getSeconds());
                var fecha = date.getDateHoy();

                if (jQuery(caja).find(".historial .dia").last().html() != fecha) {
                    append = "<p class='dia'>" + fecha + "</p>";
                }
                append += "<div class='mensaje propio'><span class='texto'>" + mensaje + "</span><span class='hora'>" + hora + "</span></div><div class='clear'></div>";
                jQuery(caja).find(".historial").append(append);

                // me voy en el scroll al final
                var historial_this = jQuery(caja).find(".historial")[0];
                var scrollHeight = Math.max(historial_this.scrollHeight, historial_this.clientHeight);
                historial_this.scrollTop = scrollHeight - historial_this.clientHeight;

                jQuery(textarea).val("");


                // enviamos el mensaje
                jQuery.ajax({
                    data: {
                        id_useractual: jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_useractual"),
                        id_otrouser: jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_otrouser"),
                        id_anuncio: jQuery(caja).find(".caja_diferentes_mensajes").attr("data-id_anuncio"),
                        mensaje: mensaje,
                        action: 'enviar-mensaje-chat'
                    },
                    url: ae_globals.ajaxURL,
                    dataType: 'json',
                    type: 'POST',
                    beforeSend: function () {

                    },
                    success: function (response) {

                    }
                });
            }
        });
    });

</script>