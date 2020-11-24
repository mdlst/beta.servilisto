<?php
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->current_post;

$col = is_author() ? 'col-md-3 col-xs-6 in-author' : ae_get_option('de_grid', 'col-md-3 col-xs-6');
?>
<li id="post-<?php the_ID(); ?>" <?php post_class($col.' place-item'); ?> >
    <div class="place-wrapper">
        <div class="hidden-img">
        <?php if($post->the_post_thumnail) { ?> 
        <a href="<?php the_permalink(); ?>" class="img-place" title="<?php the_title(); ?>">
            <img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
            <!-- <div class="ribbon">
                <span class="ribbon-content">-50%</span>
            </div> -->
        </a>
        <?php } ?>
        </div>
        <div class="place-detail-wrapper">
        	<h2 class="title-place">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h2>
            <span class="address-place"><i class="fa fa-map-marker"></i> <?php echo $post->et_full_location ?></span>
            <div class="content-place"><?php the_excerpt(); ?></div>
            <div class="rate-it" data-score="<?php echo $post->rating_score ?>" data-id="<?php echo $post->ID; ?>"></div>
            
        </div>
    </div>
</li>