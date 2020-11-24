<?php
    global $ae_post_factory;
    $ae_post    =   $ae_post_factory->get('post');
    $post = $ae_post->current_post;
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="media">
        <?php if($post->the_post_thumnail !== '' ) { ?>
        <a class="pull-left img-blog featured-img" href="<?php the_permalink(); ?>">
            <img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>" />
        </a>
        <?php } ?>
        <div class="media-body">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
                <h2 class="media-heading title-blog"><?php the_title(); ?></h2>
            </a>
            <span class="time-calendar"><i class="fa fa-calendar"></i><?php echo get_the_date(get_option( 'date_format' ), $post->ID);; ?></span>

            <div class="clearfix"></div>
            <div class="content-event"><?php the_excerpt(); ?></div>
            <a title="<?php printf(__("View %s details", ET_DOMAIN), get_the_title()); ?>" href="<?php the_permalink(); ?>" class="see-more"><?php _e("See more", ET_DOMAIN); ?></a>
        </div>
    </div>
</li>