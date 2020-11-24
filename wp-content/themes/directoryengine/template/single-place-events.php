<?php 
    global $post, $ae_post_factory, $user_ID;
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->current_post;
    $tax = $place->tax_input;
    $location = $tax['location'][0];
    if($user_ID == $post->post_author || current_user_can( 'edit_others_posts' )) {
        $events = new WP_Query(array('post_type' => 'event', 'post_status' => array('publish', 'pending'), 'post_parent' => $post->ID));
    }else{
        $events = new WP_Query(array('post_type' => 'event', 'post_status' => 'publish', 'post_parent' => $post->ID));
    }

    if($events->have_posts()) {

        $post_object = $ae_post_factory->get('event');
        $eventdata = array();
 ?>
<div class="section-detail-wrapper padding-top-bottom-20" id="block-events">
    <div class="event-active-wrapper" id="list-events" >
		<div class="title-event-active"><?php _e("ACTIVE EVENTS", ET_DOMAIN); ?></div>
        <?php 
        while($events->have_posts()) { $events->the_post();
            $event = $post_object->convert($post); 
            $eventdata[] = $event;
        ?>
        <div class="event-wrapper event-item" >
            <div class="event-wrapper" itemscope itemtype="http://schema.org/Event">
                <?php if(has_post_thumbnail()) { ?>
            	<span class="img-event"><?php the_post_thumbnail( 'large' ); ?></span>
                <?php } ?>
                <h3 class="title-envent">
                    <span itemprop="name"><?php the_title(); ?> </span>
                    <span class="ribbon-event"><span class="ribbon-event-content"><?php echo $event->ribbon; ?></span></span>
                    <?php if(ae_user_can( 'edit_others_posts' ) || $user_ID == $post->post_author) { ?> 
                        <ol class="edit-event-option">
                            <li style="display:inline-block" class="status">
                                <a href="#" class="<?php echo $post->post_status;  ?>" >
                                    <?php echo $event->status_text; ?>
                                </a>
                            </li>
                            <?php if($post->post_status === 'pending' && ae_user_can( 'edit_others_posts' )) { ?>
                            <li style="display:inline-block"><a href="#" class="action approve" data-action="approve"><i class="fa fa-check"></i></a></li>                 
                            <?php }
                            ?>
                            <li style="display:inline-block"><a href="#" class="action edit" data-action="edit"><i class="fa fa-pencil"></i></a></li>
                            <?php if($post->post_status == 'publish') { ?>
                            <li style="display:inline-block"><a href="#" class="action archive" data-action="archive"><i class="fa fa-trash-o"></i></a></li>                
                            <?php }?> 
                        </ol>
                    <?php } ?>
                </h3>
                <div class="content-event"><?php the_content(); ?></div>
    			<time>
                    <span itemprop="startDate" content="<?php echo date('Y-m-d H:i:s',strtotime($event->open_time)); ?>"></span>
                    <span itemprop="endDate" content="<?php echo date('Y-m-d H:i:s',strtotime($event->close_time)); ?>"></span>
                    <div class="event-date">
                    <?php 
                        _e("<span>Time remains:</span>", ET_DOMAIN); 
                        echo $event->event_time;
                    ?>
                    </div>
                </time>
                <div itemprop="location" itemscope itemtype="http://schema.org/Place">
                    <span itemprop="name" content="<?php the_title(); ?>"></span>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <span itemprop="streetAddress"><?php echo $place->et_full_location ?></span>, 
                        <span itemprop="addressLocality"><?php echo $location->name;?></span>
                    </div>
                </div>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="url" content="<?php echo $place->permalink; ?>"></span>
                </span>
                <div class="line-event"></div>
            </div>
        </div>
        <?php 
        } 
            echo '<script type="json/data" class="postdata">'. json_encode($eventdata) .'</script>';
        ?>
    </div>
</div>

<?php 

}
wp_reset_postdata();
                            