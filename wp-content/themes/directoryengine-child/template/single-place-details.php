<?php
global $post, $ae_post_factory;

$place_obj = $ae_post_factory->get('place');
$place = $place_obj->current_post;
/******************************************************************************/
$et_payment_package = get_post_meta($place->ID, 'et_payment_package', true);
$paid_place = false;
if ($et_payment_package == '002' or $et_payment_package == '003' or $et_payment_package == '004' or $et_payment_package == '005' or
    $et_payment_package == '2' or $et_payment_package == '3' or $et_payment_package == '4' or $et_payment_package == '5'
) {
    $paid_place = true;
}
/******************************************************************************/
?>
<div class="section-detail-wrapper padding-top-bottom-20 print-only">
    <div class="info-address-place-wrapper">
    	<span class="img-small-place">
            <img width="300" alt="<?php the_title(); ?>" src="<?= $place->the_post_thumnail; ?>">
        </span>
        <?php do_action('de_multirating_render_review'); ?>

        <div class="info-address-place print-only">
            <ul>
                <li>
                    <span class="title-single-place-details poner-aqui-distance-calculada">
                         <i title="Dirección" class="fa fa-map-marker"></i>
                        Dirección
                    </span>
                    <span class="address-place content-single-place-details">
                        <?= ($place->et_full_location) ? $place->et_full_location : "Dirección no especificada" ?>
                    </span>
                    <span itemprop="latitude" id="latitude" content="<?= $post->et_location_lat; ?>"></span>
                    <span itemprop="longitude" id="longitude" content="<?= $post->et_location_lng; ?>"></span>
                </li>
                <li>
                    <span class="title-single-place-details">
                         <i title="Provincia" class="fa fa-globe"></i>
                      Provincia
                    </span>
                    <span class="provincia-place content-single-place-details" title="Dirección">
                        <?= (isset($place->tax_input["location"][0]->name) && $place->tax_input["location"][0]->name) ? $place->tax_input["location"][0]->name : "Provincia no especificada"; ?>
                    </span>
                </li>

                <?php
                if ($place->et_phone and $paid_place) { ?>
                    <li>
                    <span class="title-single-place-details">
                         <i title="Teléfono" class="fa fa-phone"></i>
                        Teléfono
                    </span>

                        <span class="phone-place content-single-place-details" title="Teléfono">
                        <?= ($place->et_phone) ? $place->et_phone : "Teléfono no especificado" ?>
                    </span>
                    </li>
                    <?php
                }

                if ($place->et_url and $paid_place) { ?>

                    <li>
                         <span class="title-single-place-details">
                         <i title="Sitio Web" class="fa fa-link"></i>
                            Sitio Web
                    </span>

                        <span class="website-place content-single-place-details" title="Sitio Web">
                        <?= ($place->et_url) ? '<a rel="nofollow" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_url) . '" >' . $place->et_url . '</a>' : "Sitio Web no especificado" ?>
                    </span>

                    </li>
                <?php } ?>


                <?php if ($place->serve_time) { ?>
                    <li class="date-time-place">
                         <span class="title-single-place-details">
                         <i title="Disponibilidad" class="fa fa-clock-o"></i>
                             Disponibilidad
                    </span>

                        <div class="date-time content-single-place-details" title="Disponibilidad">
                            <?= display_serve_time($place->serve_time); ?>
                        </div>
                    </li>
                <?php } else {
                    if ($place->open_time && $place->close_time) { ?>
                        <li class="time-place">
                        <span class="time-place limit-display">
                            <i class="fa fa-clock-o"></i>
                            <?php
                            if ($place->open_time && $place->close_time) {
                                printf(__("%s to %s", ET_DOMAIN), $place->open_time, $place->close_time);
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
                        </span>
                        </li>
                    <?php } ?>
                    <?php
                    if ($place->open_time_2 && $place->close_time_2) { ?>
                        <li class="time-place">
                        <span class="time-place limit-display">
                            <i class="fa fa-clock-o"></i>
                            <?php
                            if ($place->open_time_2 && $place->close_time_2) {
                                printf(__("%s to %s", ET_DOMAIN), $place->open_time_2, $place->close_time_2);
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
                        </span>
                        </li>
                    <?php } ?>

                    <li class="calendar-place">
                    <span class="time-place limit-display" title="<?php _e("Open days", ET_DOMAIN); ?>">
                        <i class="fa fa-calendar"></i>
                        <?php de_serve_day($place->serve_day); ?>
                    </span>
                    </li>
                <?php } ?>

                <?php if ($place->et_distance) { ?>
                    <li>
                        <span class="title-single-place-details">
                            <i title="Distancia" class="fa fa-circle-o"></i>
                            Distancia
                         </span>

                        <span class="website-place content-single-place-details" title="Distancia">
                        <?= $place->et_distance; ?>
                    </span>
                    </li>
                <?php } ?>

                <?php if ($place->et_have_car) { ?>
                    <li>
                         <span class="title-single-place-details">
                            <i title="Tiene vehículo" class="fa fa-car"></i>
                             Tiene vehículo
                         </span>

                        <span class="car-place content-single-place-details" title="Tiene vehículo">
                        <?php
                        if ($place->et_have_car == "yes") echo "Si";
                        elseif ($place->et_have_car == "no") echo "No";
                        else echo $place->et_have_car;
                        ?>
                    </span>
                    </li>
                <?php } ?>

                <?php

                if ($place->et_fb_url and $paid_place) { ?>

                    <li>

                        <span class="title-single-place-details">
                            <i title="Facebook" class="fa fa-facebook"></i>
                         Facebook
                         </span>

                        <span class="website-place content-single-place-details" title="Facebook">
                        <?= ($place->et_fb_url) ? '<a rel="nofollow" target="_blank" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_fb_url) . '" >' . $place->et_fb_url . '</a>' : "Dirección de Facebook no especificada"; ?>
                    </span>

                    </li>

                <?php } ?>

                <?php
                if ($place->et_google_url and $paid_place) { ?>

                    <li>
                        <span class="title-single-place-details">
                            <i title="Google+" class="fa fa-google-plus"></i>
                         Google+
                         </span>

                        <span class="website-place content-single-place-details" title="Google+">
                            <?= ($place->et_google_url) ? '<a rel="nofollow" target="_blank" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_google_url) . '" >' . $place->et_google_url . '</a>' : "Dirección deGoogle+ no especificada"; ?>
                        </span>

                    </li>

                <?php } ?>

                <?php
                if ($place->et_twitter_url and $paid_place) { ?>

                    <li>
                     <span class="title-single-place-details">
                            <i title="Twitter" class="fa fa-twitter"></i>
                     Twitter
                     </span>

                        <span class="website-place content-single-place-details" title="Twitter">
                        <?= ($place->et_twitter_url) ? '<a rel="nofollow" target="_blank" href="http://' . str_replace(array('http://', 'https://'), '', $place->et_twitter_url) . '" >' . $place->et_twitter_url . '</a>' : "Dirección de Twitter no especificada"; ?>
                    </span>

                    </li>

                <?php } ?>

                <li class="categories">
                    <span class="title-single-place-details">
                            <i title="Categorías" class="fa fa-tags"></i>
                      Categorías
                     </span>

                    <div class="chosen-container chosen-container-multi chosen  content-single-place-details">
                        <ul class="chosen-choices">
                            <?php
                            foreach ($place->tax_input['place_category'] as $category_choosen) {
                                ?>
                                <h2
                                        class="search-choice <?= $category_choosen->slug; ?> level-<?= $category_choosen->term_group ?>">
                                    <?= $category_choosen->name; ?></h2>
                                <?php
                            } ?>
                        </ul>
                    </div>
                </li>

            </ul>

        </div>

        <br/>

        <a data-user="<?= $place->post_author; ?>" href="#"
           class="print-no contact-owner-link <?= (is_user_logged_in()) ? "contact-owner" : "authenticate" ?>">
            Contactar con el anunciante
        </a>

    </div>


    <div class="description-place-wrapper print-only">


        <h1 class="title-place"><?php the_title(); ?></h1>
        <?php if (!isset($place->multi_rating_score) || !$place->multi_rating_score || empty($place->multi_rating_score)) { ?>
            <div class="rate-wrapper">

                <div class="rate-it rating" data-score="<?php echo $place->rating_score; ?>"></div>

                <a class="number-review <?php if (is_singular()) {
                    echo 'sroll-review';
                } ?>" href="<?php if (is_singular()) {
                    echo '#review-list';
                } else {
                    the_permalink();
                } ?>">

                    (
                    <?php ($place->reviews_count > 1) ? printf(__('%d reviews', ET_DOMAIN), $place->reviews_count) : printf(__('%d review', ET_DOMAIN), $place->reviews_count); ?>
                    )

                </a>

            </div>
        <?php } ?>


        <div class="clearfix"></div>

        <!-- place gallery -->

        <ul class="list-gallery">

            <?php

            $attachment = get_children(array(
                'numberposts' => -1,
                'order' => 'ASC',
                'post_mime_type' => 'image',
                'post_parent' => $post->ID,
                'post_type' => 'attachment'
            ), OBJECT);


            $total = count($attachment);

            $i = 0;

            foreach ($attachment as $key => $att) {

                $image = wp_get_attachment_image_src($att->ID, 'thumbnail');
                $image_full = wp_get_attachment_image_src($att->ID, 'full');
                $title = get_the_title();

                if ($i < 4) {
                    echo '<li><a class="fancybox" title="' . $title . '" href="' . $image_full[0] . '">
                                <img alt="' . $title . '" src="' . $image[0] . '"></a>
                            </li>';
                }

                if ($i === 4 && $total >= 6) {
                    echo '<li class="last">
                                <a class="fancybox" title="' . $title . '" href="' . $image_full[0] . '">
                                    ' . sprintf(__("See more %s", ET_DOMAIN), '<span class="carousel-number">' . ($total - 3) . '+</span>') . '
                                </a>
                            </li>';
                }


                if ($i > 4) {
                    if ($total >= 6) {
                        echo '<li style="display:none;"><a class="fancybox" title="' . $title . '" href="' . $image_full[0] . '">
                                <img alt="' . $title . '" src="' . $image[0] . '"></a>
                            </li>';
                    } else {
                        echo '<li><a class="fancybox" title="' . $title . '" href="' . $image_full[0] . '">
                            <img alt="' . $title . '" src="' . $image[0] . '"></a>
                        </li>';
                    }
                }
                $i++;
            }

            ?>

        </ul>

        <!--// place gallery -->

        <div class="content-description">

            <?php the_content(); ?>
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

                    <input type="hidden" name="DOPBSP-calendar-ID" id="DOPBSP-calendar-ID" value="<?= $calid; ?>"/>
                    <input type="hidden" name="DOPBSP-calendar-jump-to-day" id="DOPBSP-calendar-jump-to-day" value=""/>

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

    <div class="clearfix"></div>

</div>