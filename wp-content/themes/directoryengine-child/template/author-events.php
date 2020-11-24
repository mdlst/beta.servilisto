<?php
query_posts(array(
    'post_type' => 'place',
    'post_status' => 'publish',
    'author' => get_query_var('author'), 
    'paged' => get_query_var('paged'),
    'meta_query' => array(
        'meta_key' => 'de_event_post', 
        'meta_type' => 'NUMERIC'
    )  
    
));
?>
<div class="tab-pane body-tabs" id="event_place">
    <div class="section-detail-wrapper">
    <?php 
        global $post, $ae_post_factory, $user_ID;
        $post_parent = 0;
        if( have_posts() ) {
            while(have_posts()) { the_post();
                $place = $post;
                if($user_ID == get_query_var('author')) {
                    $events = get_posts(array('post_type' => 'event', 'post_parent' => $place->ID , 'post_status' => array('publish', 'archive', 'pending') ));    
                }else {
                    $events = get_posts(array('post_type' => 'event', 'post_parent' => $place->ID , 'post_status' => array('publish') ));
                }
                
                if(count($events) == 0 ) continue;
        ?>
                <div class="event-active-wrapper">
                    <div class="col-md-9">
                        <?php foreach ($events as $key => $value) { 
                            $event_object = $ae_post_factory->get('event');
                            $event = $event_object->convert($value);
                        ?>
                            <div class="event-wrapper tab-style-event">
                                <!-- <div class="triagle-setting-top"><i class="fa fa-pencil"></i></div> -->
                                <span class="img-event"><?php echo get_the_post_thumbnail( $event->ID, 'large' ); ?></span>
                                <h2 class="title-envent"><?php echo $event->post_title; ?> 
                                    <span class="ribbon-event">
                                        <span class="ribbon-event-content">
                                            <?php echo $event->ribbon ?>
                                        </span>
                                    </span>
                                </h2>
                                <?php if(current_user_can( 'edit_other_posts' ) ||  $user_ID == get_query_var('author')) { ?>
                                <ol class="edit-event-option">
                                    <li style="display:inline-block" class="status">
                                        <a href="#" class="<?php echo $post->post_status;  ?>" >
                                            <?php echo $event->status_text; ?>
                                        </a>
                                    </li>
                                </ol>
                                <?php } ?>
                                <p class="content-event"><?php echo $event->post_content; ?></p>
                                <time>
                                <?php 
                                    _e("Time remains: ", ET_DOMAIN);
                                    echo $event->event_time;
                                ?></time>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-wrapper widget-features-wrapper">
                            <ul class="list-places  fullwidth">
                            <?php 
                                $place_object = $ae_post_factory->get('place');
                                $place_object->convert($place);
                                get_template_part('template/loop', 'place-vertical'); 
                            ?>                    
                            </ul>
                        </div>
                    </div>
                </div>
        <?php 
            }
        }else { ?>
            <div class="event-active-wrapper">
                <div class="col-md-9">
                    <div class="event-wrapper tab-style-event">
                        <h2 class="title-envent"><?php _e("Currently, there are not event yet.", ET_DOMAIN); ?></h2>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php
ae_pagination($wp_query);
wp_reset_query();