<div class="news-wrapper">
    <?php if(has_post_thumbnail()) { ?>
    	<span class="img-news">
        <?php 
            $id = get_post_thumbnail_id( );
            $img = wp_get_attachment_image_src( $id, 'thumbnail' );
            echo '<img src="'.$img [0].'" class="attachment-thumbnail wp-post-image" alt="'. get_the_title() . '">';
        ?>
        </span>
    <?php } ?>
    
    <h2 class="title-news">
    	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <span class="time"><i class="fa fa-calendar"></i><?php echo get_the_date(); ?></span>
    </h2>
    <div class="clearfix"></div>
    <div class="content-news">
        <?php the_excerpt(); ?>
    </div>
</div>