<?php
global $ae_post_factory, $user_ID;
$event_obj = $ae_post_factory->get('event');
$event = $event_obj->current_post;
$post_data = $event->post_data;
$tax_input = $post_data->tax_input;
?>
<li <?php post_class( 'post-item' ); ?> >
    <div class="place-wrapper">
        <a href="<?php the_permalink(); ?>" class="img-place">
            <img src="<?php echo $event->the_post_thumnail; ?>" />
            <?php if(isset($event->ribbon) && $event->ribbon){ ?>
            <div class="cat-<?php echo $tax_input->place_category[0]; ?>">
                <div class="ribbon">
                    <span class="ribbon-content"><?php echo $event->ribbon; ?></span>
                </div>
            </div>
            <?php } ?>
        </a>
        <div class="place-detail-wrapper">
            <h2 class="title-place"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php echo $post_data->post_title; ?></a></h2>
            <span class="address-place"><i class="fa fa-map-marker"></i> <?php echo $post_data->et_full_location; ?></span>
            <div class="rate-it" data-score="<?php echo $post_data->rating_score; ?>"></div>
            <span class="warning-overdue"><i class="fa fa-warning"></i>0 day</span>
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
        <p><?php echo $event->post_content;?></p>
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