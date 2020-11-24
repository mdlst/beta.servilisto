<?php
/**
 * Loop events on page author
 */
$args = array (
    'post_type'   => 'event',
    'post_status' => 'publish',
    'author'      => get_query_var( 'author' ),
    'paged'       => get_query_var( 'paged' ),

);

if ( current_user_can( 'edit_other_posts' ) || $user_ID == get_query_var( 'author' ) ) {
    $args['post_status'] = array ( 'publish', 'archive', 'pending' );
}

query_posts( $args );
$backup_query = $wp_query;
?>
    <div class="tab-pane body-tabs" id="event_place">
        <div class="section-detail-wrapper">
            <?php
            global $post, $ae_post_factory, $user_ID;
            $have_event = 0;
            if ( have_posts() ) {
                $have_event = true;
                    ?>
                    <div class="event-active-wrapper">
                        <div class="col-md-12">
                            <?php while ( have_posts() ) {
                                the_post();
                                $event = $post;
                                $event_object = $ae_post_factory->get( 'event' );
                                $event = $event_object->convert( $event );
                                ?>
                                <div class="event-wrapper tab-style-event">
                                    <!-- <div class="triagle-setting-top"><i class="fa fa-pencil"></i></div> -->
                                    <span
                                        class="img-event"><?php echo get_the_post_thumbnail( $event->ID, 'large' ); ?></span>

                                    <h2 class="title-event"><a href="<?php echo get_permalink($event->post_parent); ?>"><?php echo $event->post_title; ?></a>
                                        <span class="ribbon-event">
                                            <span class="ribbon-event-content">
                                                <?php echo $event->ribbon ?>
                                            </span>
                                        </span>
                                        <?php if ( current_user_can( 'edit_other_posts' ) || $user_ID == get_query_var( 'author' ) ) { ?>
                                            <ol class="edit-event-option">
                                                <li style="display:inline-block" class="status">
                                                    <a href="#" class="<?php echo $post->post_status; ?>">
                                                        <?php echo $event->status_text; ?>
                                                    </a>
                                                </li>
                                            </ol>
                                        <?php } ?>
                                    </h2>
                                    <div class="content-event"><?php echo $event->post_content; ?></div>
                                    <time>
                                        <?php
                                        _e( "Time remains: ", ET_DOMAIN );
                                        echo $event->event_time;
                                        ?></time>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                } else { ?>
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-event no-title-envent "><?php _e( "Currently, there are not event yet.", ET_DOMAIN ); ?></h2>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php
if ( $have_event == 1 ) {
    ae_pagination( $backup_query );
}
wp_reset_query();