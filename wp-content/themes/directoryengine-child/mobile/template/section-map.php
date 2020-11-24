<!-- Map -->
<div class="google-map-wrapper">
    <?php 
    if(is_singular( 'place' )) {
        global $post, $ae_post_factory;
        $place_obj = $ae_post_factory->get('place');
        $place = $place_obj->current_post;  
        if( $place->et_video !== '' && !($place->cover_image) ) {
            get_template_part( 'template/section', 'video' ); 
        }
    }
    ?>
    <section id="map-top-wrapper"></section>
    <?php
    if(is_singular( 'place' )) {
        if(($place->cover_image)) {
            $address = $place->et_full_location;
            if($place->et_location_lat != '') {
                $address = $place->et_location_lat .','.$place->et_location_lng;    
            }        
         ?>
            <div class="view-direction-btn">
                <a target="_blank" href="https://maps.google.com?saddr=Current+Location&daddr=<?php echo $address; ?>">
                    <?php _e("View direction", ET_DOMAIN); ?>
                </a>
            </div>
    <?php 
        } 
    } ?>
</div>
<!-- Map / End -->