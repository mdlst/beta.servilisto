<?php
global $post, $ae_post_factory, $current_user;
et_get_mobile_header();
if (have_posts()) {
    the_post();

    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->convert($post, 'big_post_thumbnail');
    $place_marker = array('ID' => $place->ID, 'post_title' => $place->post_title, 'tipo_reserva' => $place->tipo_reserva, 'permalink' => $post->guid, 'latitude' => $place->et_location_lat, 'longitude' => $place->et_location_lng);
    $sum = 0;
    $cats = $place->tax_input['place_category'];
    if (isset($cats['0'])) {
        $sum = $cats['0']->count;
        $place_marker = wp_parse_args(array('term_taxonomy_id' => $cats['0']->term_id), $place_marker);
    }
    echo '<script type="data/json"  id="total_place">' . json_encode(array('number' => $sum, 'current_place' => $place_marker)) . '</script>';
    if (isset($cats['0']->slug)) {
        echo '<script type="data/json"  id="place_cat_slug">' . json_encode(array('slug' => $cats['0']->slug)) . '</script>';
    }
    if ($place->cover_image_url) {
        $cover = $place->cover_image;
        $cover_image_url = wp_get_attachment_image_src($cover, 'full');
        $cover_image_url = $cover_image_url[0];
        ?>
        <!-- Top bar -->
        <section id="top-bar" class="section-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-centered">
                        <h1 class="title-page"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <!-- Top bar / End -->

        <!-- Image Place -->
        <section id="img-place" class="section-wrapper"
                 style="background:url(<?= $cover_image_url; ?>) no-repeat center center / cover; height:150px;">
            <div itemscope itemtype="http://schema.org/ImageObject">
                <img itemprop="contentUrl" src="<?php echo $cover_image_url; ?>" height="0px">
            </div>
        </section>
        <?php
    } else {
        get_template_part('template/section', 'map');
    }
    ?>
    <!-- Image Place / End -->

    <!-- Tabs -->

    <section id="tabs-place-review-wrapper" class="section-wrapper">

        <ul class="nav nav-tabs list-user-info list-place-info" role="tablist" id="myTab">

            <li class="active">

                <a href="#information_place" role="tab" data-toggle="tab">
                    <i class="fa fa-info-circle"></i>
                    Información
                </a>
            </li>

            <li>

                <a href="#gallery_place" role="tab" data-toggle="tab">
                    <i class="fa fa-picture-o"></i>
                    Galería
                </a>

            </li>

            <li class="oculto">

                <a href="#event_place" role="tab" data-toggle="tab">
                    <i class="fa fa-calendar"></i>
                    Ofertas
                </a>

            </li>

            <li>

                <a href="#review_place" role="tab" data-toggle="tab">
                    <i class="fa fa-comments"></i>
                    Comentarios
                </a>

            </li>

        </ul>

        <div class="tab-content">

            <!-- Tabs 1 / Start -->

            <div class="tab-pane fade active body-tabs in" id="information_place">

                <div class="container">

                    <div class="row">

                        <div class="col-xs-12">

                            <div class="single-place-wrapper " data-id="<?php the_ID(); ?>" id="main-single">

                                <?php get_template_part('template/single-place', 'breadcrumb'); ?>

                            </div>

                            <div class="info-place-wrapper">

                                <h2 class="title-place"><?php the_title(); ?></h2>

                                <div class="rate-it" data-score="<?= $place->rating_score; ?>"></div>

                                <ul class="info-place">

                                    <li>
                                        <i class="fa fa-map-marker" title="Dirección"></i>
                                        <?php echo ($place->et_full_location) ? $place->et_full_location : 'Dirección no especificada'; ?>
                                    </li>

                                    <li>
                                        <i class="fa fa-globe" title="Provincia"></i>
                                        <?= (isset($place->tax_input["location"][0]->name) && $place->tax_input["location"][0]->name) ? $place->tax_input["location"][0]->name : 'Provincia no especificada' ?>
                                    </li>


                                    <?php if ($place->et_phone and $paid_place) { ?>
                                        <li class="phone-place" title="Teléfono">

                                            <i class="fa fa-phone"></i><?php echo ($place->et_phone) ? $place->et_phone : 'Teléfono no especificado' ?>

                                        </li>
                                    <?php } ?>
                                    <?php if ($place->et_url and $paid_place) { ?>
                                        <li class="website-place" title="Sitio web">
                                            <i class="fa fa-link"></i><?php echo ($place->et_url) ? $place->et_url : 'Sitio web no especificado' ?>

                                        </li>
                                    <?php } ?>

                                    <?php if ($place->serve_time) { ?>
                                        <li class="date-time-place">
                                            <div class="date-time">
                                                <i class="fa fa-clock-o"></i>
                                                <?php echo display_serve_time($place->serve_time); ?>
                                            </div>
                                        </li>
                                    <?php } else {
                                        if ($place->open_time && $place->close_time) { ?>
                                            <li class="time-place">
                                                <i class="fa fa-clock-o"></i>
                                                <?php
                                                if ($place->open_time && $place->close_time) {
                                                    printf(__("%s to %s.", ET_DOMAIN), $place->open_time, $place->close_time);
                                                } else {
                                                    // no specify serve time
                                                    if (!$place->open_time && !$place->close_time) {
                                                        _e("No specify serve time", ET_DOMAIN);
                                                    }
                                                    // specify open time
                                                    if ($place->open_time) {
                                                        printf(__("Open at: %s", ET_DOMAIN), $place->open_time);
                                                    }
                                                    // specify close time
                                                    if ($place->close_time) {
                                                        printf(__("Close at: %s", ET_DOMAIN), $place->close_time);
                                                    }
                                                }
                                                ?>
                                            </li>
                                        <?php } ?>
                                        <?php
                                        if ($place->open_time_2 && $place->close_time_2) { ?>
                                            <li class="time-place">
                                                <i class="fa fa-clock-o"></i>
                                                <?php
                                                if ($place->open_time_2 && $place->close_time_2) {
                                                    printf(__("%s to %s.", ET_DOMAIN), $place->open_time_2, $place->close_time_2);
                                                } else {
                                                    // no specify serve time
                                                    if (!$place->open_time_2 && !$place->close_time_2) {
                                                        _e("No specify serve time", ET_DOMAIN);
                                                    }
                                                    // specify open time
                                                    if ($place->open_time_2) {
                                                        printf(__("Open at: %s", ET_DOMAIN), $place->open_time_2);
                                                    }
                                                    // specify close time
                                                    if ($place->close_time_2) {
                                                        printf(__("Close at: %s", ET_DOMAIN), $place->close_time_2);
                                                    }
                                                }
                                                ?>
                                            </li>
                                        <?php } ?>

                                        <li class="calendar-place">
                                            <i class="fa fa-calendar"></i>
                                            <?php de_serve_day($place->serve_day); ?>
                                        </li>
                                    <?php } ?>

                                    <?php if ($place->et_distance) { ?>
                                        <li>
                                            <i class="fa fa-dot-circle-o" title="Distancia"></i>
                                            <?= "Distancia: " . $place->et_distance; ?>
                                        </li>
                                    <?php } ?>
                                    <?php if ($place->et_have_car) { ?>
                                        <li>
                                            <i class="fa fa-car" title="Tiene vehiculo"></i>
                                            <?= "Tiene vehiculo: " . $place->et_have_car; ?>
                                        </li>
                                    <?php } ?>


                                    <?php if ($place->et_fb_url and $paid_place) { ?>

                                        <li>
                                            <i class="fa fa-facebook" title="Facebook"></i>

                                            <?php
                                            echo ($place->et_fb_url) ? '<a rel="nofollow" target="_blank" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_fb_url) . '" >' . $place->et_fb_url . '</a>' : 'Dirección de Facebook no especificada';

                                            ?>
                                        </li>

                                    <?php } ?>

                                    <?php if ($place->et_google_url and $paid_place) { ?>

                                        <li>
                                            <i class="fa fa-google-plus"
                                               title="<?php _e("Google plus", ET_DOMAIN); ?>"></i>

                                            <?php
                                            echo ($place->et_google_url) ? '<a rel="nofollow" target="_blank" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_google_url) . '" >' . $place->et_google_url . '</a>' : 'Dirección de G+ no especificada';
                                            ?>
                                        </li>

                                    <?php } ?>

                                    <?php if ($place->et_twitter_url and $paid_place) { ?>

                                        <li>
                                            <i class="fa fa-twitter" title="<?php _e("Twitter", ET_DOMAIN); ?>"></i>
                                            <?php
                                            echo ($place->et_twitter_url) ? '<a rel="nofollow" target="_blank" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_twitter_url) . '" >' . $place->et_twitter_url . '</a>' : 'Dirección de Twitter no especificada';
                                            ?>
                                        </li>

                                    <?php } ?>

                                    <li>

                                        <i class="fa fa-tags" title="<?php _e("Categories", ET_DOMAIN); ?>"></i>

                                        <div
                                                class="chosen-container chosen-container-multi chosen  content-single-place-details">
                                            <ul class="chosen-choices">
                                                <?php
                                                foreach ($place->tax_input['place_category'] as $category_choosen) {
                                                    ?>
                                                    <span
                                                            class="search-choice <?= $category_choosen->slug; ?> level-<?= $category_choosen->term_group ?>">
                                                        <?= $category_choosen->name; ?></span>
                                                    <?php
                                                } ?>
                                            </ul>
                                        </div>
                                    </li>

                                </ul>

                            </div>

                            <div class="des-place-wrapper">
                                <h2 class="title-des">Descripción</h2>

                                <div class="content">
                                    <?php the_content(); ?>
                                </div>
                            </div>

                            <a data-user="<?php echo $place->post_author; ?>"
                               href="<?php if (is_user_logged_in()) {
                                   echo 'javascript:void(0)';
                               } else {
                                   echo et_get_page_link('login', array('redirect' => get_permalink($place->ID)));
                               } ?>"
                               class="print-no contact-owner-link <?= (is_user_logged_in()) ? 'contact-owner' : 'authenticate'; ?>">

                                Contactar con el anunciante

                            </a>
                        </div>
                    </div>

                </div>


                <div class="description-place-wrapper print-only sss5">


                    <div class="content-description">

                        <div class="front-end-calender">
                            <?php
                            global $wpdb;

                            $post_id = $post->ID;
                            $pUserId = $post->post_author;
                            $calendar = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'dopbsp_calendars WHERE user_id = ' . $pUserId . ' and post_id="' . $post_id . '" ORDER BY id');

                            /********************* HAY CALENDARIO **************************/
                            if (isset($calendar[0]->id)) {
                                $calid = $calendar[0]->id;
                                ?>

                                <div id="DOPBSP-messages-box">
                                    <a href="javascript:DOPBSPBackEnd.toggleMessages()" class="dopbsp-close"></a>

                                    <div class="dopbsp-icon-active"></div>
                                    <div class="dopbsp-icon-success"></div>
                                    <div class="dopbsp-icon-error"></div>
                                    <div class="dopbsp-message"></div>
                                </div>
                                <?= do_shortcode('[dopbsp id="' . $calid . '"]'); ?>

                                <input type="hidden" name="DOPBSP-calendar-ID" id="DOPBSP-calendar-ID"
                                       value="<?= $calid; ?>"/>
                                <input type="hidden" name="DOPBSP-calendar-jump-to-day" id="DOPBSP-calendar-jump-to-day"
                                       value=""/>


                                <?php
                            } /********************* NO HAY CALENDARIO (Nos mandamos un email) **************************/
                            else {

                                $permalink = get_permalink();
                                $title = get_the_title();

                                $mensaje = "Hay un problema a la hora de cargar el calendario del siguiente anuncio: <br><br>";
                                $mensaje .= "<p><span style='font-weight: bold;'>Título:</span> $title</p>";
                                $mensaje .= "<p><span style='font-weight: bold;'>Post ID:</span> $post_id</p>";
                                $mensaje .= "<p><span style='font-weight: bold;'>Url:</span> <a target='new' href='$permalink'>Ir al anuncio</a></p>";

                                wp_mail('servilisto.com@gmail.com, yotengounmovil@hotmail.com, moyaperez@hotmail.com', 'SERVILISTO - Problema al cargar el calendario de un anuncio', $mensaje);

                            } ?>

                        </div>
                    </div>

                    <?= get_the_term_list($post, 'place_tag', '<div class="place-meta"><span class="tag-links">', '', '</span></div>'); ?>

                </div>
            </div>

            <!-- Tabs 1 / End -->

            <!-- Tabs 2 / Start -->

            <div class="tab-pane fade body-tabs" id="gallery_place">

                <div class="container">

                    <div class="row">

                        <ul class="gallery-image">

                            <?php

                            $attachment = get_children(array(
                                'numberposts' => 15,
                                'order' => 'ASC',
                                'post_mime_type' => 'image',
                                'post_parent' => $post->ID,
                                'post_type' => 'attachment'

                            ), OBJECT);

                            if ($attachment) {

                                foreach ($attachment as $key => $att) {

                                    $image = wp_get_attachment_image_src($att->ID, 'thumbnail');

                                    $image_full = wp_get_attachment_image_src($att->ID, 'full');

                                    echo '<li class="col-xs-4">
                                        <a class="fancybox" title="' . get_the_title() . '" href="' . $image_full[0] . '">
                                            <img alt="' . get_the_title() . '" src="' . $image[0] . '">
                                        </a>
                                    </li>';
                                }
                            } else {  // no hay imagenes
                                echo '<div class="align-centered">
                                        <p>Actualmente no has subido imagenes</p>
                                        <p>Dirijase a "Info" y pulse en "Editar anuncio" para subir fotos al anuncio</p>
                                    </div>';

                            }

                            ?>
                        </ul>

                    </div>

                </div>


            </div>

            <!-- Tabs 2 / End -->

            <!-- Tabs 3 / Start -->

            <div class="tab-pane fade body-tabs" id="event_place">

                <div class="container">

                    <div class="row">

                        <div class="col-xs-12">

                            <?php get_template_part('template/single-place', 'events'); ?>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Tabs 3 / End -->

            <!-- Tabs 4 / Start -->

            <div class="tab-pane fade body-tabs" id="review_place">

                <div class="container">

                    <div class="row">

                        <?php comments_template(); ?>

                    </div>

                </div>

            </div>

            <!-- Tabs 4 / End -->

        </div>

    </section>

    <!-- Tabs / End -->


    <div class="clearfix"></div>

<?php } ?>

    <!-- Single Place / End -->
<?php
$array_place = (array)$place;
$args = wp_parse_args($array_place, $more);

?>
    <script type="json/data" id="place_id"><?php echo json_encode($args); ?></script>
<?php
$next = ae_next_post('place_category');
$prev = ae_prev_post('place_category');
if ($next) {
    ?>
    <script type="json/data"
            id="next_id"><?php echo json_encode(array('id' => $next->ID, 'ID' => $next->ID)); ?></script>
    <?php
}
if ($prev) {
    ?>
    <script type="json/data"
            id="prev_id"><?php echo json_encode(array('id' => $prev->ID, 'ID' => $prev->ID)); ?></script>
    <?php
}

et_get_mobile_footer();

