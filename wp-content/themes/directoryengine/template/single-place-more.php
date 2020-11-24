<?php
global $post, $ae_post_factory;
$place_obj = $ae_post_factory->get('place');
$place = $place_obj->current_post;
 ?>
<div class="single-place-wrapper load-more" id="single-place-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" >
    <?php
        get_template_part( 'template/single-place', 'option-list' );
    ?>
    <div class="detail-place-right-wrapper">
        <?php 
            get_template_part('template/single-place', 'breadcrumb');
            get_template_part('template/single-place', 'details');
        ?>
        <div class="section-detail-wrapper padding-top-bottom-20">
        	<div class="event-active-wrapper">
                <a href="<?php echo $place->permalink; ?>" class="view-event-link"><?php _e("View this place", ET_DOMAIN); ?> 
                <i class="fa fa-caret-right"></i></a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>