<?php 
global $post, $ae_post_factory, $user_ID, $wp_query;
$post_parent = 0;
    while(have_posts()) { the_post();
        $place = $post;
        if($user_ID == get_query_var('author')) {
            $events = get_posts(array('post_type' => 'event', 'post_parent' => $place->ID , 'post_status' => array('publish', 'archive', 'pending') ));    
        }else {
            $events = get_posts(array('post_type' => 'event', 'post_parent' => $place->ID , 'post_status' => array('publish')));
        }
        
        if(count($events) == 0 ) //continue;

?>
        <div class="event-active-wrapper">
            <div class="widget-wrapper widget-features-wrapper">
                <ul class="list-places  fullwidth" id="place-events-list">
                <?php 
                    foreach ($events as $key => $value) { 
                        $event_object = $ae_post_factory->get('event');
                        $event = $event_object->convert($value);
                        get_template_part('mobile/template/loop', 'place-events'); 
                    }
                ?>
                <?php 
                    // $place_object = $ae_post_factory->get('place');
                    // $place_object->convert($place);
                    // get_template_part('mobile/template/loop', 'place-events'); 
                ?>                    
                </ul>
            </div>
        </div>
<?php 
	
    }