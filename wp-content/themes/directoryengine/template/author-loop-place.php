<?php
/**
 * Loop place on page author
 */
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->current_post;

$status = array(    'reject' => __("REJECTED", ET_DOMAIN) , 
                    'archive' =>  __("ARCHIVED", ET_DOMAIN), 
                    'pending' => __("PENDING", ET_DOMAIN) , 
                    'draft' => __("DRAFT", ET_DOMAIN), 
                    'publish' => __("ACTIVE", ET_DOMAIN)
            );
?>
<li id="post-<?php the_ID(); ?>" <?php post_class('col-md-12 col-xs-12  place-item'); ?> >
    <div class="place-wrapper">
    	<!-- button event for admin control  -->
		<?php if( ae_user_can( 'edit_others_posts' ) || (is_author() && $user_ID == get_query_var( 'author' ) ) ) { ?>
        <ol class="edit-place-option">
            <li style="display:inline-block;<?php echo ($post->post_status == 'pending') ? 'width:100%': '' ;?>" class="status">
                <a href="#" class="<?php echo $post->post_status;  ?>" >
                    <?php echo $status[$post->post_status]; ?>
                </a>
            </li>
            <?php if($post->post_status == 'pending') { // edit post ?>
            <li style="display:inline-block;"><a href="#" class="action reject" data-action="reject"><i class="fa fa-ban"></i></a></li>
            <?php } ?>
            <?php if($post->post_status == 'pending' || $post->post_status == 'reject' || $post->post_status == 'publish' ) { // edit post ?>
                <li style="display:inline-block"><a href="#" class="action edit" data-action="edit"><i class="fa fa-pencil"></i></a></li>
                <li style="display:inline-block"><a href="#" class="action archive 2" data-action="archive"><i class="fa fa-trash-o"></i></a></li>
            <?php
            } if( $post->post_status === 'archive' || $post->post_status === 'draft' ) { // renew post ?> 
                <li style="display:inline-block">
                    <a href="<?php echo et_get_page_link('post-place', array('id' => $post->ID)) ?>" class="edit" data-action="edit">
                        <?php if($post->post_status == 'archive') { ?> 
                            <i class="fa fa-refresh" title="<?php _e("Renew", ET_DOMAIN); ?>"></i>
                        <?php }else { ?> 
                            <i class="fa fa-pencil"></i>
                        <?php } ?>
                        
                    </a>
                </li>
                <li style="display:inline-block">
                    <a href="#" title="<?php _e("Delete Permanently", ET_DOMAIN); ?>" class="action delete" data-action="delete">
                        <i class="fa fa-times"></i>
                    </a>
                </li>
            <?php }?>                        
        </ol>
        <?php } ?>
        <!--// button event for admin control  -->
        <?php if($post->the_post_thumnail) { ?> 
        <a href="<?php the_permalink(); ?>" class="img-place" title="<?php the_title(); ?>">
            <img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
            <!-- <div class="ribbon">
                <span class="ribbon-content">-50%</span>
            </div> -->
        </a>
        <?php } ?>
        <div class="place-detail-wrapper">
            
        	<h2 class="title-place">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h2>
            <span class="address-place"><i class="fa fa-map-marker"></i>
                <span itemprop="latitude" id="latitude" content="<?php echo $post->et_location_lat;?>"></span>
                <span itemprop="longitude" id="longitude" content="<?php echo $post->et_location_lng;?>"></span>
                <span class="distance"></span>
                <?php echo $post->et_full_location ?></span>
            <div class="content-place"><?php the_excerpt(); ?></div>
            <div class="rate-it" data-score="<?php echo $post->rating_score ?>" data-id="<?php echo $post->ID; ?>"></div>
            
        </div>
    </div>
</li>