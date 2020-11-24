<?php
get_header();
global $ae_post_factory, $post, $wp_query;

// default args
$args = array(
    'post_type' => 'place', 
    'paged' => get_query_var( 'paged' ) , 
    //'meta_key' => 'rating_score',
    'post_status' => array('publish'),
    's' => $_REQUEST['s'], 
    'radius' => 10
);

if(isset($_REQUEST['l']) && $_REQUEST['l'] != '') {
    $args['location']= $_REQUEST['l'];
    $args['tax_query'] = array(
        array(
            'taxonomy'=>'location',
            'field'=> 'slug',
            'terms'=> $_REQUEST['l']
            )
        );
}
if(isset($_REQUEST['c']) && $_REQUEST['c'] != '') {
    $args['place_category'] = $_REQUEST['c'];
}
/**
 * generate nearby center
*/
if(isset($_REQUEST['center']) && $_REQUEST['center'] != '') {
    $center = explode(',', $_REQUEST['center']);
    $args['near_lat'] = $center[0];
    $args['near_lng'] = $center[1];
    unset($_REQUEST['center']);
    $args['radius'] = $_REQUEST['radius'] ;
}
// nearby radius

if(isset($_REQUEST['days']) && $_REQUEST['days']) {
    $args['date_query'] = array(
        array(
            'column' => 'post_date_gmt',
            'after' => ($_REQUEST['days'] > 1) ? $_REQUEST['days'].' days ago ' : '1 day ago ',
        )
    );
}

$place_obj = $ae_post_factory->get('place');
$wp_query   =   new WP_Query($args);

$found_posts = '<span class="found_post">'.$wp_query->found_posts.'</span>';
$plural = sprintf(__('%s places ',ET_DOMAIN), $found_posts);
$singular = sprintf(__('%s place',ET_DOMAIN),$found_posts);
$convert = false;
?>
<div>
    <!-- Bar Post Place -->
    <section id="bar-post-place-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-xs-8">
                    <h2 class="top-title-post-place">
                    <?php
                        printf( __( 'Search Results for "%s": ', ET_DOMAIN ), get_search_query() );
                    ?>
                        <span class="found_search plural <?php if( $found_posts > 1 ) { echo 'hide'; } ?>" >
                            <?php echo $plural; ?>
                        </span>
                        <span class="found_search singular <?php if( $found_posts <= 1 ) { echo 'hide'; } ?>">
                            <?php echo $singular; ?>
                        </span>
                    </h2>
                </div>
                <div class="col-md-3 col-xs-4">
                <div class="top-btn-post-place">
                    <a href="<?php echo et_get_page_link('post-place'); ?>" class="btn btn-post-place">
                        <i class="fa fa-map-marker"></i>
                        <?php _e("Submit a place", ET_DOMAIN); ?>
                    </a>
                </div>
            </div>
            </div>
        </div>
    </section>
    <!-- Bar Post Place / End -->
    <!-- List Place -->
    <section id="list-places-wrapper">
        <div class="container">
            <!-- place list with sidebar -->
            <div class="row">
                <div class="col-md-9 publish_place_wrapper" id="publish_place_wrapper">
                    <div class="filter-wrapper">
                        <?php get_template_part( 'template/place', 'filter' ); ?>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <?php if(have_posts()) { ?>
                            <ul class="list-places list-posts" id="publish-places" data-list="publish" data-thumb="medium_post_thumbnail">
                            <?php 
                            $post_arr   =   array();
                            while (have_posts()) {

                                    the_post();
                                    global $post, $ae_post_factory;
                                    $ae_post    =   $ae_post_factory->get('place');
                                    $convert    =   $ae_post->convert($post, 'medium_post_thumbnail');
                                    $post_arr[] =   $convert;
                                    get_template_part( 'template/loop' , 'place' );
                                }

                                echo '<script type="json/data" class="postdata" id="ae-publish-posts"> ' . json_encode($post_arr) . '</script>';

                                ?>

                            </ul>
                            <div class="paginations-wrapper main-pagination" >
                            <?php
                                ae_pagination($wp_query);
                                wp_reset_postdata();
                            ?>
                            </div>
                            <?php
                        }else {
                            get_template_part('template/place', 'notfound' );
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

    <?php if($convert) { ?>
    <!--<script type="json/data" id="place_id"><?php echo json_encode(array('id' => $convert->ID, 'ID' => $convert->ID)); ?></script> -->
    <script type="json/data" id="place_id"><?php echo json_encode($convert); ?></script>
    <?php } ?>
    <?php if(isset($args['near_lng'] )) { ?>
    <script type="json/data" id="nearby_location"><?php echo json_encode(array('latitude' => $args['near_lat'], 'longitude' => $args['near_lng'] )); ?></script>
    <?php } ?>

    <!-- List Place / End -->


<?php
get_footer();

