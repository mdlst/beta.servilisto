<?php
$status = '';
if (is_post_type_archive('place')) {
    global $wp_query;
    if ($wp_query->post_count > 1) {
        $status = sprintf(__('%d places', ET_DOMAIN), $wp_query->found_posts);
    } else {
        $status = sprintf(__('%d place', ET_DOMAIN), $wp_query->found_posts);
    }
} else {
    $queried_object = get_queried_object();
    // place tag status
    if (is_tax('place_tag')) {
        $status = sprintf(__('Tag: %s', 'twentyfourteen'), single_tag_title('', false));
    }

    //  category, location status
    if (!$status) {
        if ($queried_object->count > 1) {
            $status = sprintf(__('%d places in "%s"', ET_DOMAIN), $queried_object->count, $queried_object->name);
        } else {
            $status = sprintf(__('%d place in "%s"', ET_DOMAIN), $queried_object->count, $queried_object->name);
        }
    }
}

?>
<!-- Bar Post Place -->
<section id="bar-post-place-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="el_container_filter">
                    <p class="top-title-post-place oculto" id="place-status" style="">
                        <?php echo $status; ?>
                    </p>

                    <?php


                    if (isset($_REQUEST['l'])) {
                        if ($_REQUEST['l'] == '') {
                            echo " <span id='your_current_location_search'>Anuncios encontrados en toda España</span>";
                        } else if ($_REQUEST['l'] == 'cerca-de-ti' && $_COOKIE['current_user_province']) {

                            $localizacion_user = "";
                            if ($_COOKIE['current_user_province']) {
                                $localizacion_user = $_COOKIE['current_user_province'];
                            }
                            if ($_COOKIE['current_user_locality'] && $_COOKIE['current_user_province']) {
                                $localizacion_user = $_COOKIE['current_user_locality'] . ", " . $_COOKIE['current_user_province'];
                            }

                            $radio = (ae_get_option('nearby_distance')) ? ae_get_option('nearby_distance') : 10;
                            $radio .= "Km";

                            echo " <span id='your_current_location_search'>Anuncios encontrados cerca de ti ($localizacion_user, a $radio) <span><span class='quitar_geo'><img src='" . get_stylesheet_directory_uri() . "/img/loading.gif' /><span class='txt'>No utilizar<br>geolocalización</span></span></span></span>";
                        } else {
                            echo " <span id='your_current_location_search'>Anuncios encontrados en la provincia de \"" . ucfirst($_REQUEST['l']) . "\"</span>";
                        }
                    } else {  //pagina de categorías
                        echo " <span id='your_current_location_search'>Anuncios encontrados en toda España</span>";
                    }
                    ?>

                    <div class="col-md-12 publish_place_wrapper" id="">
                        <div class="filter-wrapper">
                            <?php get_template_part('template/place', 'filter'); ?>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Bar Post Place / End -->