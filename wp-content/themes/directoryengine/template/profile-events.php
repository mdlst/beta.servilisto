<?php
global $user,$wp_query;
$args = array (
    'post_type'   => 'event',
    'post_status' => 'publish',
    'author'      => $user->ID,
    'paged'       => get_query_var( 'paged' ),
    'posts_per_page' => $paged,
    'showposts'		=> get_option('posts_per_page'),
);

query_posts( $args );
$backup_query = $wp_query;
?>
<div class="content-events  tab-pane fade" id="tab-event">
    <ul class="list-place-publishing">
        <?php
        global $post, $ae_post_factory, $user_ID;
        $event_object = $ae_post_factory->get( 'event' );
        $have_event = 0;
        if ( $backup_query->have_posts() ) {
            $have_event = true;
            $event_arr = array();
            foreach ( $backup_query->posts as $key=>$value) {
                $event = $value;
                $event = $event_object->convert( $event );   
                // data Place
                $post_place = get_post($event->post_parent);
                $place_object = $ae_post_factory->get( 'place' );
                $place = $place_object->convert($post_place);  
                array_push($event_arr, $event);
                
        ?>
            <li class="event-item col-md-12">
                <div class="wrap-place-publishing col-md-3 col-sm-3">
                    <div class="block-place-publishing">
                        <a href="<?php echo get_permalink($event->post_parent); ?>" title="<?php echo $place->post_title;?>" class="place-publishing-img">
                            <?php echo get_the_post_thumbnail( $event->post_parent, 'medium' ); ?>
                        </a>
                        <h2 class="place-publishing-title">
                            <a href="<?php echo get_permalink($event->post_parent); ?>" title="<?php echo $place->post_title;?>"><?php echo $place->post_title;?></a>
                        </h2>
                        <span class="place-publishing-map"><i class="fa fa-map-marker"></i>
                            <span itemprop="latitude" id="latitude" content="<?php echo $place->et_location_lat;?>"></span>
                            <span itemprop="longitude" id="longitude" content="<?php echo $place->et_location_lng;?>"></span>
                            <span class="distance"></span>
                            <?php echo $place->et_full_location;?>
                        </span>
                        <div class="rate-it" data-score="<?php echo $place->et_rate;?>" data-id="<?php echo $place->ID; ?>"></div>
                    </div>
                </div>
                <div class="wrap-content-event col-md-9 col-sm-9">
                    <?php if(ae_user_can( 'edit_others_posts' ) || $place->post_author == $user_ID) { ?>
                    <div class="config event-config dropdown">
                        <i class="fa fa-cog dropdown-toggle" data-toggle="dropdown"></i>
                        <ol class="dropdown-menu menu-edit-event" role="menu" aria-labelledby="menu1">
                            <li><a href="#" class="action edit" data-action="edit"><i class="fa fa-pencil"></i><?php _e('Edit this event',ET_DOMAIN);?></a></li>
                            <li><a href="#" class="action archive" data-action="archive"><i class="fa fa-trash-o"></i><?php _e('Put to archive',ET_DOMAIN);?></a></li>
                        </ol>
                    </div>
                    <?php } ?>
                    <h4><a href="<?php echo get_permalink($event->post_parent); ?>" title="<?php echo $event->post_title; ?>"><?php echo $event->post_title; ?></a> <span class="ribbon-event-discount"><?php echo $event->ribbon ?></span></h4>
                    <div class="desc"><?php echo wp_trim_words($event->post_content,'50'); ?></div>
                    <div class="note-event">
                        <?php echo $event->event_time;?>
                    </div>
                </div>
            </li>
        <?php 
            }
        } else {  ?>
            <li class="col-md-12" style="border:none;">
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-envent no-title-envent "><?php _e( "Currently, there are not event yet.", ET_DOMAIN ); ?></h2>
                        </div>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
    <?php
        if ( $have_event == 1 ) {
            echo '<script type="json/data" class="postdata" > ' . json_encode($event_arr) . '</script>';
            echo "<div class='paginations-wrapper'>";
                ae_pagination( $backup_query );
            echo "</div>";
        }
        wp_reset_query();
    ?>
</div>
