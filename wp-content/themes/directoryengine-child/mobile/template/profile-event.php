<?php 
global $user;

?>
<div class="container" >
    <div class="row" >
    <?php
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        query_posts(array(
            'post_type'   => 'event',
            'post_status' => 'publish',
            'author'      => $user->ID,
            'paged'       => $paged,
            'posts_per_page' => $paged,
            'showposts'		=> get_option('posts_per_page'),
        ));
        $backup_query = $wp_query;
    ?>
    <div class="col-md-12">
        <div class="tab-pane body-tabs" id="events-list-wrapper">
            <div class="section-detail-wrapper list-places fullwidth" id="list-events">
                <?php 
                global $post, $ae_post_factory, $user_ID;
                    $event_object = $ae_post_factory->get( 'event' );
                    $have_event = 0;
                    if ( $backup_query->have_posts() ) {
                ?>
                <div class="event-active-wrapper">
                    <div class="widget-wrapper widget-features-wrapper">
                        <ul class="list-places  fullwidth" id="place-events-list">
                            <?php 
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
                                    <li <?php post_class( 'post-item' ); ?> >
                                        <div class="place-wrapper">
                                            <a href="<?php echo $place->permalink; ?>" class="img-place">
                                                <img src="<?php echo $place->the_post_thumnail; ?>" />
                                                <?php if(isset($event->ribbon) && $event->ribbon){ ?>
                                                <div class="cat-<?php echo $tax_input->place_category[0]; ?>">
                                                    <div class="ribbon">
                                                        <span class="ribbon-content"><?php echo $event->ribbon; ?></span>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </a>
                                            <div class="place-detail-wrapper">
                                                <h2 class="title-place"><a href="<?php echo $place->permalink;  ?>" title="<?php the_title(); ?>" ><?php echo $place->post_title; ?></a></h2>
                                                <span class="address-place"><i class="fa fa-map-marker"></i>
                                                    <span itemprop="latitude" id="latitude" content="<?php echo $place->et_location_lat;?>"></span>
                                                    <span itemprop="longitude" id="longitude" content="<?php echo $place->et_location_lng;?>"></span>
                                                    <span class="distance"></span>
                                                    <?php echo $place->et_full_location; ?></span>
                                                <div class="rate-it" data-score="<?php echo $place->rating_score; ?>"></div>
                                                <?php  if(ae_user_can('edit_others_posts') && false ) { ?> 
                                                    <div class="triagle-setting mobile-setting"><i class="fa fa-cog"></i></div>
                                                <?php } ?>
                                            </div>
                                            <!--
                                            <div class="place-config">
                                                <i class="fa fa-cog edit-config"></i>
                                            </div>
                                            <div class="edit-place-post">
                                                <i class="fa fa-history place-extend"></i>
                                                <i class="fa fa-pencil place-edit"></i>
                                                <i class="fa fa-trash-o place-remove"></i>
                                            </div>
                                            -->
                                        </div>
                                        <div class="content-event">
                                            <h5><?php echo $event->post_title;?></h5>
                                            <p><?php echo wp_trim_words($event->post_content,'50');?></p>
                                            <span class="note-event"><?php echo $event->event_time;?></span>
                                        </div>
                                        <?php if(ae_user_can('edit_others_posts') && false ) { ?> 
                                            <ul class="list-option-place">
                                                <li><a href="#"><i class="fa fa-check"></i></a></li>
                                                <li><a href="#"><i class="fa fa-times"></i></a></li>
                                                <li><a href="#"><i class="fa fa-trash-o"></i></a></li>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php
                                    }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php }else { ?>
                    <div class="event-active-wrapper">
                        <div class="col-md-12">
                            <div class="event-wrapper tab-style-event">
                                <h2 class="title-envent">Actualmente, no hay ofertas</h2>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if(have_posts()) { ae_pagination( $backup_query, 1, 'load_more' ); } ?>
            </div>
        </div>
    </div>
    <?php wp_reset_query(); ?>
    </div>
</div>