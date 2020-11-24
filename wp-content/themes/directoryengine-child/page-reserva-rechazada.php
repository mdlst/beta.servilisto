<?php

global $post, $DOPBSP, $wpdb;

/**
 * exito == 0 -> error
 * exito == 1 -> exito
 * exito == 2 -> ya está procesada
 */
$exito = 0;

if (isset($_GET["rejectres"]) && $_GET["rejectres"]) {
    $decodido = base64_decode($_GET["rejectres"]);
    $decode_explode = explode("&", $decodido);

    if (sizeof($decode_explode) == 5 && $decode_explode[0] == "reject" && $decode_explode[4] == "pending") {

        $reservation = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $DOPBSP->tables->reservations . ' WHERE id=%d', $decode_explode[1]));
        if ($reservation->status == 'pending') {  // en
            $res = $DOPBSP->classes->backend_reservation->reject($decode_explode[1]);
            if ($res == "") $exito = 1;
        } else {
            $exito = 2;
        }

    }
}

$title = "";
$mensaje = "";

if ($exito == 0) {
    $title = "Ha habido un problema con la reserva";
    $mensaje = "<p>Ha habido un problema con el rechazo de la reserva.</p>
                <p>Si el problema persiste, puedes intentar rechazar la reserva
                   entrando a tu
                   <a href='" . get_bloginfo('url') . "/mi-perfil" . "'>panel de usuario</a>
                   dentro de la zona de administración de Servilisto.com</p>";
} elseif ($exito == 1) {
    $title = "Reserva rechazada";
    $mensaje = "<p>La reserva ha sido rechazada correctamente.</p>
                <p>Esperamos próximamente pueda aceptar la próxima solicitud de trabajo.</p>";
} else {
    $title = "Esta reserva ya está tramitada";
    $mensaje = "<p>Esta reserva ya ha sido tramitada.</p>
                <p>Puedes ver más detalles sobre esta reserva entrando a tu zona de usuario desde el
                <a href='" . get_bloginfo('url') . "/mi-perfil" . "'>panel de usuario</a> de Servilisto.com</p>";
}

get_header();

if (have_posts()) {
    the_post();

    ?>

    <!-- Breadcrumb Blog -->
    <div class="section-detail-wrapper breadcrumb-blog-page">
        <ol class="breadcrumb">
            <li><a href="<?php echo home_url() ?>"
                   title="<?php echo get_bloginfo('name'); ?>"><?php _e("Home", ET_DOMAIN); ?></a></li>
            <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
        </ol>
    </div>
    <!-- Breadcrumb Blog / End -->

    <!-- Page Blog -->
    <section id="blog-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="blog-wrapper">
                        <!-- post title -->
                        <div class="section-detail-wrapper padding-top-bottom-20">
                            <h1 class="media-heading title-blog"><?= $title ?></h1>

                            <div class="clearfix"></div>
                        </div>
                        <!--// post title -->
                        <div class="section-detail-wrapper padding-top-bottom-20">

                            <?= $mensaje ?>

                            <p>&nbsp;</p>

                            <a class="btn btn-blue-square centered" href="<?= bloginfo('url') ?>">
                                <span>Volver a Servilisto.com</span>
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Blog / End -->

    <?php
}

get_footer();

