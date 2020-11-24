<?php 
if(is_singular( 'place' )) {
    global $post, $ae_post_factory;
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->current_post;  
    if($place->et_video !== '') {
        ?>
        <div class="container">
            <div class="row">
                <div class="pop-up-video <?php echo $place->video_position; ?>">
                	<div class="video-wrapper">
                    	<a href="<?php echo  $place->et_video  ?>" class="mark-wrapper popup-video">
                        	<span class="mark-video"></span>
                    		<iframe width="300" height="200" src="<?php echo apply_filters('de_filter_video', $place->et_video ); ?>" frameborder="0" allowfullscreen></iframe>
                        </a>
                    </div>
                    <div class="title-video"><?php printf(__("The video intro for %s", ET_DOMAIN), '<br/>'. $place->post_title); ?></div>
                </div>
            </div>
        </div>
    <?php 
    }
} ?>