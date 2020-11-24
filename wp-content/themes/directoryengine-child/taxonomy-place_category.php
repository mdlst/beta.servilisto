<?php
get_header();

//con esto ponemos en su lugar las categorias y las location en sus filtros correspondientes.
if (isset($place_category)) {
    $_REQUEST['c'] = $place_category;
}
if (isset($_REQUEST['en'])) {
    $_REQUEST['l'] = $_REQUEST['en'];
}

get_template_part('template/section', 'map');
get_template_part('template/section', 'status');
?>

    <!-- List Place -->
    <section id="list-places-wrapper">
        <div class="container">
            <?php
            /*if (ae_user_can('manage_options')) {
                get_template_part('template/pending', 'places');
            }
            get_sidebar('top');*/
            ?>
            <!-- place list with sidebar -->
            <div class="row">

                <div class="col-md-9 publish_place_wrapper" id="">

                    <div class="clearfix"></div>

                    <div class="row">

                        <?php if (have_posts()) { ?>
                            <ul class="list-places list-posts" id="publish-places" data-list="publish"
                                data-thumb="medium_post_thumbnail">
                                <?php

                                $post_arr = array();
                                $place_marker = array();

                                while (have_posts()) {
                                    the_post();
                                    global $post, $ae_post_factory;
                                    $ae_post = $ae_post_factory->get('place');
                                    $convert = $ae_post->convert($post, 'medium_post_thumbnail');
                                    $post_arr[] = $convert;

                                    get_template_part('template/loop', 'place');


                                    $place_obj = $ae_post_factory->get('place');
                                    $place = $place_obj->convert($post, 'big_post_thumbnail');
                                    $sum = 0;
                                    $cats = $place->tax_input['place_category'];

                                    if ($place->et_location_lat != '' && $place->et_location_lng != '') {
                                        if (isset($cats['0'])) {
                                            $sum = $cats['0']->count;
                                            array_push($place_marker, array('ID' => $place->ID, 'post_title' => $place->post_title, 'permalink' => $post->guid, 'latitude' => $place->et_location_lat, 'longitude' => $place->et_location_lng, 'term_taxonomy_id' => $cats['0']->term_id));

                                        } else {
                                            array_push($place_marker, array('ID' => $place->ID, 'post_title' => $place->post_title, 'permalink' => $post->guid, 'latitude' => $place->et_location_lat, 'longitude' => $place->et_location_lng));
                                        }
                                    }
                                }

                                echo '<div class="replace"><script type="data/json"  id="total_place">' . json_encode(array('number' => $sum, 'current_place' => $place_marker)) . '</script>';
                                echo '<script type="json/data" class="postdata" id="ae-publish-posts"> ' . json_encode($post_arr) . '</script></div>';

                                ?>

                            </ul>

                            <div class="paginations-wrapper main-pagination">
                                <?php
                                ae_pagination($wp_query);
                                wp_reset_postdata();
                                ?>
                            </div>
                            <?php

                        } else {
                            get_template_part('template/place', 'notfound');
                        }

                        ?>
                    </div>
                    <?php if (category_description()) : ?>

                        <div class="category_description_seo">
                            <?php
                            $descripcion = category_description();
                            if (isset($_REQUEST['l']) && $_REQUEST['l']) {
                                $descripcion = str_replace("[*Provincia*]", " en " . ucfirst($_REQUEST['l']), $descripcion);
                            } else {
                                $descripcion = str_replace("[*Provincia*]", "", $descripcion);
                            }
                            ?>
                            <?= $descripcion; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                get_sidebar();
                ?>

            </div>
            <?php //get_sidebar('bottom'); ?>
        </div>
    </section>


    <!-- List Place / End -->

<?php
get_footer();