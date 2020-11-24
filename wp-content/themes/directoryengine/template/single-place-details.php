<?php
global $post, $ae_post_factory;
$place_obj = $ae_post_factory->get('place');
$place = $place_obj->current_post;

// Password Protected
if(post_password_required( $post )){
    echo '<div class="section-detail-wrapper padding-top-bottom-20 print-only">';
    print( get_the_password_form( $post ));
    echo '</div>';
    return;
}
?>
<div class="section-detail-wrapper padding-top-bottom-20 print-only">
    <div class="info-address-place-wrapper">
        <div itemscope itemtype="http://schema.org/ImageObject">
            <span class="img-small-place" >
                <img itemprop="contentUrl"  width="300" alt="<?php the_title(); ?>" src="<?php echo $place->the_post_thumnail; ?>" >
            </span>
        </div>
        <?php do_action('de_multirating_render_review'); ?>
        <!-- place info -->
        <div class="info-address-place print-only">
            <ul>
                <li class="address-place">
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <span class="address-place" <?php _e("Address", ET_DOMAIN); ?> itemprop="addressLocality">
                            <i class="fa fa-map-marker"></i>
                            <meta id="latitude" content="<?php echo $place->et_location_lat;?>">
                            <meta id="longitude" content="<?php echo $place->et_location_lng;?>">
                            <var class="distance"></var> 
                            <?php echo ($place->et_full_location) ? $place->et_full_location : __( 'No specify address' , ET_DOMAIN );; ?>
                        </span>
                    </div> 
                    <div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
                    <meta itemprop="latitude" content="<?php echo $place->et_location_lat; ?>" />
                    <meta itemprop="longitude" content="<?php echo $place->et_location_lng; ?>" />
                  </div>   
                </li>
            <?php if($place->et_phone){ ?>
                <li class="phone-place">
                    <span itemprop="telephone" class="phone-place limit-display" <?php _e("Phone", ET_DOMAIN); ?>>
                        <i class="fa fa-phone"></i><?php echo ($place->et_phone) ? $place->et_phone : __( 'No specify phone' , ET_DOMAIN );; ?>
                    </span>
                </li>
            <?php } ?>    
            <?php if($place->et_url){ ?>
                <li class="website-place">
                    <span class="website-place limit-display" title="<?php _e("Website", ET_DOMAIN); ?>">
                        <i class="fa fa-link"></i>
                        <?php 
                            echo ($place->et_url) ? '<a rel="nofollow" target="_Blank" href="http://'.str_replace(array('http://', 'https://'), '',$place->et_url) .'" >'.$place->et_url.'</a>' : __( 'No specify website' , ET_DOMAIN );; 
                        ?>
                    </span>
                </li>
            <?php } ?>
                
            <?php if($place->serve_time){ ?>
                <li class="date-time-place">
                    <div class="date-time">
                        <i class="fa fa-clock-o"></i>
                        <?php echo display_serve_time($place->serve_time);?>
                    </div>
                </li>
            <?php }else{
                if($place->open_time && $place->close_time){ ?>
                <li class="time-place">
                    <span class="time-place limit-display">
                        <i class="fa fa-clock-o"></i>
                        <?php 
                            if($place->open_time && $place->close_time) {
                                printf(__("%s to %s", ET_DOMAIN), $place->open_time, $place->close_time);
                            }else{
                                // no specify serve time
                                if(!$place->open_time && !$place->close_time) {
                                    _e("No specify serve time", ET_DOMAIN);
                                }
                                // specify open time
                                if( $place->open_time ) {
                                    printf(__("Open at: %s", ET_DOMAIN) , $place->open_time);
                                }
                                // specify close time
                                if( $place->close_time ) {
                                    printf(__("Close at: %s", ET_DOMAIN) , $place->close_time );
                                }
                            } 
                        ?>
                    </span>
                </li>
            <?php } ?>
                <li class="calendar-place">
                    <span class="time-place limit-display" title="<?php _e("Open days", ET_DOMAIN); ?>">
                        <i class="fa fa-calendar"></i>
                        <?php de_serve_day($place->serve_day); ?>
                    </span>
                </li>
            <?php }?>
            <?php 
                if(function_exists('et_render_custom_field')) { 
                    et_render_custom_meta_theme($post); 
                    et_render_custom_taxonomy_theme($post);
                } 
            ?>
            <?php if($place->et_fb_url || $place->et_google_url || $place->et_twitter_url) {?>
                <li class="social-place">
                    <span class="social-place limit-display" title="<?php _e("Social", ET_DOMAIN); ?>">
                        <i class="fa fa-share-alt"></i>
                        <?php if($place->et_fb_url){ ?>
                            <a href="http://<?php echo str_replace(array('http://', 'https://'), '',$place->et_fb_url )?>" rel="nofollow" target="_blank" title="<?php _e("Facebook", ET_DOMAIN); ?>">
                                <i class="fa fa-facebook"/></i>
                            </a>
                        <?php } ?>
                        <?php if($place->et_google_url){ ?>
                            <a href="http://<?php echo str_replace(array('http://', 'https://'), '',$place->et_google_url )?>" rel="nofollow" target="_blank" title="<?php _e("Google plus", ET_DOMAIN); ?>">
                                <i class="fa fa-google-plus"/></i>
                            </a>
                        <?php } ?>
                        <?php if($place->et_twitter_url){ ?>
                            <a href="http://<?php echo str_replace(array('http://', 'https://'), '',$place->et_twitter_url )?>" rel="nofollow" target="_blank" title="<?php _e("Twitter", ET_DOMAIN); ?>">
                                <i class="fa fa-twitter"/></i>
                            </a>
                        <?php } ?>
                    </span>
                </li>
            <?php } ?>
            
            <?php  
            do_action('list_info_place');
            ?>
             </ul>   
        </div>
        <!--// place info -->
        <a data-user="<?php echo $place->post_author; ?>" href="#" class="print-no contact-owner-link <?php if(is_user_logged_in()) { echo 'contact-owner'; }else { echo 'authenticate'; } ?>">
            <?php _e("CONTACT OWNER", ET_DOMAIN); ?>
        </a>
    </div>
    <div class="description-place-wrapper print-only">

        <h1 class="title-place" itemprop="name" ><?php echo $place->post_title; ?></h1>
        <?php if(!isset($place->multi_rating_score) || !$place->multi_rating_score || empty($place->multi_rating_score)){ ?>
        <div class="rate-wrapper">
            <div itemscope itemtype="http://schema.org/Product">
                <span itemprop="name" style="display:none"><?php echo $place->post_title;?></span>
                <?php if($place->rating_score != 0 || $place->reviews_count != 0){?>
                    <div style="display:none" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                        <span itemprop="ratingValue"><?php echo $place->rating_score; ?></span>
                        <span itemprop="reviewCount"><?php echo $place->reviews_count;?></span>
                    </div>
                <?php } ?>
                <div class="rate-it rating" data-score="<?php echo $place->rating_score; ?>"></div>
                <a class="number-review <?php if(is_singular()) {echo 'sroll-review' ;} ?>" href="<?php if(is_singular()) {echo '#review-list' ;}  else { the_permalink();} ?>">
                    (<?php ($place->reviews_count > 1) ? printf(__( '%d reviews' , ET_DOMAIN ), $place->reviews_count) : printf(__( '%d review' , ET_DOMAIN ), $place->reviews_count); ?>
                    <?php if( ae_get_option("enable_view_counter",false) ){ 
                        if( (int)$place->view_count > 1){
                            printf(__( '/ %d views' , ET_DOMAIN ), $place->view_count);
                        }else{
                            printf(__( '/ %d view' , ET_DOMAIN ), $place->view_count);
                        }
                    } ?> )
                </a>
            </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
        <!-- place gallery -->
        <ul class="list-gallery">
            <?php 
            $attachment = get_children( array(
                'numberposts' => -1,
                'order' => 'ASC',
                'post_mime_type' => 'image',
                'post_parent' => $place->ID,
                'post_type' => 'attachment'
              ), OBJECT );

            $total = count($attachment);
            $i = 0;
            $detector = AE_MobileDetect::get_instance();
            foreach ($attachment as $key => $att) {
                $image = wp_get_attachment_image_src( $att->ID, 'thumbnail' );
                $image_full = wp_get_attachment_image_src( $att->ID, 'full' );
                if($detector->isTablet()) {
                    if($i < 3) {
                        echo    '<li><a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                    <img alt="'. get_the_title() .'" src="'. $image[0] .'"></a>
                                </li>';
                    }
                    if($i === 3 && $total >= 4 ) {
                        echo    '<li class="last">
                                    <a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                        '. sprintf(__("See all %s", ET_DOMAIN), '<span class="carousel-number">' .($total-3) . '+</span>' ) .'
                                    </a>
                                </li>';
                    }

                    if( $i > 3 ) {
                        if($total >= 4) {
                            echo    '<li style="display:none;"><a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                    <img alt="'. get_the_title() .'" src="'. $image[0] .'"></a>
                                </li>';
                        }else {
                            echo    '<li><a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                <img alt="'. get_the_title() .'" src="'. $image[0] .'"></a>
                            </li>';
                        }                    
                    }
                    $i ++;
                } else {
                    if($i < 4) {
                        echo    '<li><a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                    <img title="'. get_the_title() .'" alt="'. get_the_title() .'" src="'. $image[0] .'"></a>
                                </li>';
                    }
                    if($i === 4 && $total >= 5 ) {
                        echo    '<li class="last">
                                    <a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                        '. sprintf(__("See all %s", ET_DOMAIN), '<span class="carousel-number">' .($total-4) . '+</span>' ) .'
                                    </a>
                                </li>';
                    }

                    if( $i > 4 ) {
                        if($total >= 5) {
                            echo    '<li style="display:none;"><a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                    <img title="'. get_the_title() .'" alt="'. get_the_title() .'" src="'. $image[0] .'"></a>
                                </li>';
                        }else {
                            echo    '<li><a class="fancybox" rel="gallery" title="'. get_the_title() .'" href="'. $image_full[0] .'">
                                <img title="'. get_the_title() .'" alt="'. get_the_title() .'" src="'. $image[0] .'"></a>
                            </li>';
                        }                    
                    }
                    $i ++;
                }
            }
            echo '<input type="hidden" id="mfp-image-alt" value="'.get_the_title().'"">';
            ?>
        </ul>
        <!--// place gallery -->
        <div class="content-description">
            <?php echo $place->post_content; ?>
        </div>

        <?php echo get_the_term_list($post, 'place_tag', '<div class="place-meta"><span class="tag-links">', '', '</span></div>' ); ?>
    </div>
    <div class="clearfix"></div>
</div>