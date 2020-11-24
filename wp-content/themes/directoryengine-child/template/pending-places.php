<?php
$args = array(
    'post_type' => 'place',
    'showposts' => -1,
    'post_status' => 'pending'
);

$pending    =   new WP_Query($args);
if( $pending->have_posts() ) {
?>
<div id="seccion_pending-places" class="row">
    <div class="col-md-12">
        <div class="filter-wrapper">
            <h2 class="title-filter"><?php _e("PENDING PLACES", ET_DOMAIN); ?></h2>
        </div>

    </div>
    <div class="clearfix"></div>
    <ul class="list-posts list-places" id="pending-places" data-list='pending' >
        <?php

        $post_arr   =   array();
        if($pending->have_posts()) {
            while ($pending->have_posts()) {
                $pending->the_post();
                global $post, $ae_post_factory;
                /**
                 * get ae post object and convert post data
                */
                $ae_post    =   $ae_post_factory->get('place');
                // convert post data
                $convert    =   $ae_post->convert($post, 'big_post_thumbnail');
                $post_arr[] =   $convert;
                // get template render place details
                get_template_part( 'template/loop' , 'place' );
            }
        } else {
            _e("No place found", ET_DOMAIN);
        }

        // render json data for js
        echo '<script type="json/data" class="postdata" id="ae-posts-data"> ' . json_encode($post_arr) . '
        </script>';

        ?>

    </ul>
    <?php
        // reset wp query
        wp_reset_query();

    ?>
</div>
<?php }