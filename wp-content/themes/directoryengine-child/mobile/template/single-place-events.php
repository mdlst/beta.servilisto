<?php 
    global $post, $ae_post_factory, $user_ID;
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->current_post;
    $events = new WP_Query(array('post_type' => 'event', 'post_status' => 'publish', 'post_parent' => $post->ID));
    if($events->have_posts()) {
        $post_object = $ae_post_factory->get('event');
        $eventdata = array();
 ?>
<div class="section-detail-wrapper padding-top-bottom-20" id="block-events" itemscope itemtype="http://schema.org/Event">
    <div class="event-active-wrapper" id="list-events" >
		<h2 class="big-title-event"><?php _e("EVENTS", ET_DOMAIN); ?></h2>
        <?php 
        while($events->have_posts()) { $events->the_post();
            $event = $post_object->convert($post); 
            $eventdata[] = $event;
        ?>
        <div class="event-wrapper event-item" >
        	<span class="img-event"><?php the_post_thumbnail( 'medium' ); ?></span>
            <h2 class="title-envent" itemprop="name">
                <?php the_title(); ?> 
                <span class="ribbon-event"><span class="ribbon-event-content"><?php echo $event->ribbon; ?></span></span>
            </h2>
            <div>
            <time>
                <div class="event-date" itemprop="startDate" content="<?php echo date('Y-m-d H:i:s',strtotime($event->open_time)); ?>">
                <?php 
                    _e("Time remains:", ET_DOMAIN); echo '&nbsp;&nbsp;'; 
                    echo $event->event_time;
                ?>
                </div>
            </time>
            </div>
            <div itemprop="location" itemscope itemtype="http://schema.org/Place">
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                      <span itemprop="addressLocality"><?php echo $place->et_full_location ?></span>
                    </div>
                  </div>
            <div class="content-event"><?php the_content(); ?></div>
			
            <div class="line-event"></div>
        </div>
        <?php 
            echo '<script type="json/data" class="postdata">'. json_encode($eventdata) .'</script>';
        } ?>
    </div>
</div>

<?php 

}else {
    echo '<div class="section-detail-wrapper padding-top-bottom-20" id="block-events"><h2 class="title-envent not-found">'. __("Currently, there are no events.", ET_DOMAIN) .'</h2></div>' ;
}
wp_reset_query();
                            