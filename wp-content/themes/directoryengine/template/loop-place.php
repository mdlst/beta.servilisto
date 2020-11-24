<?php
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->get_current_post();
/**
 * Set default display for place grid/list
 */
$cl = ae_get_option('de_grid', 'col-md-3 col-xs-6');
if(isset($post->defaultdisplay) && $post->defaultdisplay){
    if($post->defaultdisplay === '1'){
        $cl = 'col-md-12';
    }
}
$col = is_author() ? 'col-md-3 col-xs-6 in-author' : $cl;
?>
<li id="post-<?php the_ID(); ?>" <?php post_class($col.' place-item'); ?> >
    <div class="place-wrapper">
        <div class="hidden-img">
            <!-- button event for admin control  -->
            <?php if( ae_user_can( 'edit_others_posts' ) || (is_author() && $user_ID == get_query_var( 'author' ) ) ) { ?>
            <ol class="edit-place-option">
                <?php if($post->post_status === 'pending' && ae_user_can( 'edit_others_posts' ) ) { ?>
                <li style="display:inline-block">
                    <a href="#" class=" paid-status" data-action="">
                    <?php 
                        if(!$post->et_paid) _e("UNPAID", ET_DOMAIN);
                        if($post->et_paid == 1) _e("PAID", ET_DOMAIN);
                        if($post->et_paid == 2) _e("FREE", ET_DOMAIN);
                    ?>
                    </a>
                </li>
                <li style="display:inline-block"><a href="#" class="action approve" data-action="approve"><i class="fa fa-check"></i></a></li> 
                <li style="display:inline-block"><a href="#" class="action reject" data-action="reject"><i class="fa fa-times"></i></a></li>               
                <?php }
                ?>
                <li style="display:inline-block"><a href="#edit_place" class="action edit" data-target="#" data-action="edit"><i class="fa fa-pencil"></i></a></li>
                <?php if($post->post_status == 'publish') { ?>
                <li style="display:inline-block"><a href="#" class="action archive 3" data-action="archive"><i class="fa fa-trash-o"></i></a></li>
                <?php }?> 
            </ol>
            <?php } ?>
            <!--// button event for admin control  -->
            <?php if($post->the_post_thumnail) { ?> 
            <a href="<?php the_permalink(); ?>" class="img-place" title="<?php the_title(); ?>">
                <img class="lazy" src="<?php echo TEMPLATEURL . '/img/lazy-loading.gif' ?>" data-original="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
                <?php if(isset($post->ribbon) && $post->ribbon){ ?>
                <div class="cat-<?php echo $post->place_category[0]; ?>">
                    <div class="ribbon">
                        <span class="ribbon-content" title="<?php echo $post->ribbon; ?>"><?php echo $post->ribbon; ?></span>
                    </div>
                </div>
                <?php } ?>
            </a>
            <?php } ?>
            <?php if($post->et_featured == 1){?>
                <span class="tag-featured"><i class="fa fa-flag"></i><?php _e('Featured',ET_DOMAIN)?></span>
            <?php } ?>
        </div>
    	
        <div class="place-detail-wrapper">
        	<h2 class="title-place">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();?></a>
            </h2>
            <span class="address-place"><i class="fa fa-map-marker"></i>
                <span itemprop="latitude" id="latitude" content="<?php echo $post->et_location_lat;?>"></span>
                <span itemprop="longitude" id="longitude" content="<?php echo $post->et_location_lng;?>"></span>
                <span class="distance"></span>
                <?php echo $post->et_full_location ?></span>
            <div class="content-place"><?php echo $post->trim_post_content; ?></div>

            <div class="clearfix rate-view">
                <div class="rate-it rate-cus" data-score="<?php echo $post->rating_score ?>" data-id="<?php echo $post->ID; ?>"></div>
                <?php if( ae_get_option("enable_view_counter",false) ): ?>
                    <div class="view-count limit-display tooltip-style" data-toggle="tooltip" data-placement="top" title="<?php echo $post->view_count ?>">
                        <i class="fa fa-eye"></i> <?php echo $post->view_count?>
                    </div>
                <?php endif; ?>
                
                <?php do_action("de_loop_after_rate");?>

            </div>
            
        </div>
    </div>
</li>