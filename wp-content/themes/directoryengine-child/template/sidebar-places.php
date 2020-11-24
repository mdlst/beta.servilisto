<!-- 
single sidebar 
 -->
<div class="col-md-3 col-xs-12 single-sidebar" style=" margin-top: 25px; ">
    <?php // load map if single place exist cover photo
    if(is_singular( 'place' )) { 
        global $ae_post_factory;

        $place_obj = $ae_post_factory->get('place');
        $place = $place_obj->current_post;

        if($place->cover_image_url) {
         ?>
    	<div class="widget-wrapper">
    		<h2 class="widget-heading"><?php printf(__("Map of %s", ET_DOMAIN), $place->post_title); ?></h2>
    		<?php get_template_part('template/section' , 'map');  ?>
    	</div>
        <?php
        }
    }

    if(is_page_template( 'page-post-place.php' )) {
        echo '<div class="widget-wrapper">';
        de_author_packages_data();
        echo '</div>';
    }

        dynamic_sidebar( 'de-single-places' );
    ?>
</div>