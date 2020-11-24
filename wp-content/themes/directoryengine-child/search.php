<?php

get_header();

global $ae_post_factory, $post, $wp_query;

// default args
$args = array(
    'post_type' => 'place',
    'paged' => get_query_var('paged'),
    //'meta_key' => 'rating_score',
    'post_status' => array('publish'),
    's' => $_REQUEST['s'],
    'radius' => (ae_get_option('nearby_distance')) ? ae_get_option('nearby_distance') : 10
);

if (isset($_REQUEST['radius']) && $_REQUEST['radius'] != '') {
    $args['radius'] = $_REQUEST['radius'];
}

if (isset($_REQUEST['l']) && $_REQUEST['l'] != '') {
    $args['location'] = $_REQUEST['l'];
}

if (isset($_REQUEST['c']) && $_REQUEST['c'] != '') {
    $args['place_category'] = $_REQUEST['c'];
}

if (isset($_COOKIE['current_user_lat']) && isset($_COOKIE['current_user_lng']) && isset($_REQUEST['l']) && $_REQUEST['l'] == 'cerca-de-ti') {
    if ($_COOKIE['current_user_lat'] != '' && $_COOKIE['current_user_lng'] != '') {
        $args['near_lat'] = $_COOKIE['current_user_lat'];
        $args['near_lng'] = $_COOKIE['current_user_lng'];
    }
}

// nearby radius

if (isset($_REQUEST['day']) && $_REQUEST['day']) {
    $args['date_query'] = array(
        array(
            'column' => 'post_date_gmt',
            'after' => ($_REQUEST['day'] > 1) ? $_REQUEST['day'] . ' days ago ' : '1 day ago ',
        )
    );
}

$place_obj = $ae_post_factory->get('place');
if (!isset($args['orderby']) and !isset($args['meta_key'])) {
    $args['meta_key'] = 'et_featured';
    $args['orderby'] = 'meta_value';
    $args['order'] = 'DESC';
}
$search_query = $place_obj->nearbyPost($args);

$wp_query = $search_query;

$found_posts = '<span class="found_post">' . $wp_query->found_posts . '</span>';
$plural = sprintf(__('%s places ', ET_DOMAIN), $found_posts);
$singular = sprintf(__('%s place', ET_DOMAIN), $found_posts);
$convert = false;

get_template_part('template/section', 'map');
?>

    <div id="bar-post-place-wrapper">
        <h1 class="top-title-post-place">Busca y contrata servicios totalmente gratis</h1>
    </div>

<?php get_template_part('template/section', 'status'); ?>

    <div id="publish_place_wrapper">
        <!-- List Place -->

        <section id="list-places-wrapper">
            <div class="container">
                <!-- place list with sidebar -->
                <div class="row">
                    <div class="col-md-9 publish_place_wrapper" id="">

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
                    </div>
                    <?php
                    get_sidebar();
                    ?>

                </div>
            </div>
        </section>
    </div>

    <!-- List Place / End -->


<?php if ($convert) { ?>
    <script type="json/data" id="place_id">
        <?php echo json_encode(array('id' => $convert->ID, 'ID' => $convert->ID)); ?>



    </script>
<?php } ?>

<?php if (isset($args['near_lng'])) { ?>
    <script type="json/data" id="nearby_location">
        <?php echo json_encode(array('latitude' => $args['near_lat'], 'longitude' => $args['near_lng'])); ?></script>

<?php } ?>
    <!-- List Place / End -->
<?php

get_footer();