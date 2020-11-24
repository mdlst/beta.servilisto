<!-- Step 3 -->
<?php 
    global $user_ID;
    $step = 3;

    $disable_plan = ae_get_option('disable_plan', false);
    if($disable_plan) $step--;
    if($user_ID) $step--;

    $post = '';
    if(isset($_REQUEST['id'])) {
        $post = get_post($_REQUEST['id']);
        if($post) {
            global $ae_post_factory;
            $post_object = $ae_post_factory->get($post->post_type);
            echo '<script type="data/json"  id="edit_postdata">'. json_encode($post_object->convert($post)) .'</script>';
        }
    }

?>
<div class="step-wrapper step-post" id="step-post">
	<a href="#" class="step-heading active">
    	<span class="number-step"><span><?php if($step > 1 ) echo $step; else echo '<i class="fa fa-rocket"></i>'; ?></span></span>
        <span class="text-heading-step"><?php _e("Enter your place details", ET_DOMAIN); ?></span>
        <i class="fa fa-caret-right"></i>
    </a>
    <div class="step-content-wrapper content" style="<?php if($step != 1) echo "display:none;" ?>" >
    	<form action="" class="post">
        	<ul class="list-form-login">
                <li>
                	<div class="row">
                    	<div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("PLACE NAME", ET_DOMAIN); ?>
                                <span><?php _e("Keep it short & clear", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" name="post_title" id="post_title" class="text-field input-item required" placeholder="<?php _e('Enter the name of your place', ET_DOMAIN); ?>" />
                        </div>
                    </div>
                </li>
                <li>
                	<div class="row">
                    	<div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("ADDRESS", ET_DOMAIN); ?>
                                <span><?php _e("Your place's address", ET_DOMAIN); ?></span>
                            </span>
                        </div>

                        <div class="col-md-8 col-sm-8">
                            <input type="text" name="et_full_location" id="et_full_location" class="text-field input-item required" placeholder="<?php _e("Enter your place's address", ET_DOMAIN); ?>" />
                            <input type="hidden" name="et_location_lat" id="et_location_lat" class="text-field input-item " />
                            <input type="hidden" name="et_location_lng" id="et_location_lng" class="text-field input-item " />
                            <style type="text/css">#map{height:200px !important;width:100%!important;margin-top:10px!important;}</style>
                            <div id="map" style="" ></div>
                            <span style=" font-size: 0.8em; font-style: italic;"><?php _e("Drag the marker to specify correct coords.", ET_DOMAIN); ?></span>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("LOCATION", ET_DOMAIN); ?>
                                <span><?php _e("Your place's City, Area", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            
                            <?php ae_tax_dropdown( 'location' , 
                                                    array(  'class' => 'chosen-single tax-item required', 
                                                            'hide_empty' => false, 
                                                            'hierarchical' => true , 
                                                            'id' => 'location' , 
                                                            'show_option_all' => __("Select your location", ET_DOMAIN) 
                                                        ) 
                                                    ) ;?> 

                        </div>
                    </div>
                </li>

                <li class="form-field icon-input">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("PHONE", ET_DOMAIN) ?>
                                <span><?php _e("Your place's contact phone", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="text-field input-item" name="et_phone" id="et_phone" />
                        </div>
                    </div>
                </li>

                <li class="form-field icon-input">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("WEBSITE", ET_DOMAIN) ?>
                                <span><?php _e("Your place's website url", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="text-field input-item is_url" name="et_url" id="et_url" />
                        </div>
                    </div>
                </li>
                 <li class="form-field icon-input">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("FACEBOOK", ET_DOMAIN) ?>
                                <span><?php _e("URL to your shop's Facebook page", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="text-field input-item is_url" name="et_fb_url" id="et_fb_url" />
                        </div>
                    </div>
                </li>
                 <li class="form-field icon-input">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("GOOGLE PLUS", ET_DOMAIN) ?>
                                <span><?php _e("Your place's goole plus url", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="text-field input-item is_url" name="et_google_url" id="et_google_url" />
                        </div>
                    </div>
                </li>

                 <li class="form-field icon-input">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("TWITTER", ET_DOMAIN) ?>
                                <span><?php _e("Your place's twitter url", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="text-field input-item is_url" name="et_twitter_url" id="et_twitter_url" />
                        </div>
                    </div>
                </li>
                <?php if(current_user_can( 'manage_options' )){ ?>
                <li class="form-field icon-input display-none field-claimable">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("CLAIMABLE", ET_DOMAIN) ?>
                                <span><?php _e("Set this place is claimable or not", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="inputCheck">
                                <input type="hidden" class="input-item" id="et_claimable_value" name="et_claimable" value="0" />
                                <input class="checkbox-field" type="checkbox" name="et_claimable_check" id="et_claimable" />
                                <label class="check" for="et_claimable"></label>
                            </div>
                        </div>
                    </div>
                </li>
                <?php } ?>
                <li class="form-field icon-input">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e('OPENING TIME', ET_DOMAIN);?>
                                <span><?php _e("Your place's serve time",ET_DOMAIN)?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="open-block">
                                <div class="open-times">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="container-open-time">
                                                <input name="open_time" class="text-field time-picker open-time" data-template="modal" data-minute-step="1" data-modal-backdrop="true" type="text" placeholder="-- : -- --" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="container-close-time">
                                                <input name="close_time" class="text-field time-picker close-time" data-template="modal" data-minute-step="1" data-modal-backdrop="true" type="text" placeholder="-- : -- --"/>
                                            </div>
                                        </div>
                                    </div>
                                    <span><?php _e("to", ET_DOMAIN);?></span>
                                </div>
                                <div class="open-date">
                                    <span class="select-date-all dselect-date-all"><?php _e("Select All", ET_DOMAIN);?></span>
                                    <ul class="date-list">
                                        <li class="bdate" data-name="Mon" data-toggle="tooltip" data-placement="bottom"><?php _e("Mon", ET_DOMAIN);?></li>
                                        <li class="bdate" data-name="Tue" data-toggle="tooltip" data-placement="bottom"><?php _e("Tue", ET_DOMAIN);?></li>
                                        <li class="bdate" data-name="Wed" data-toggle="tooltip" data-placement="bottom"><?php _e("Wed", ET_DOMAIN);?></li>
                                        <li class="bdate" data-name="Thu" data-toggle="tooltip" data-placement="bottom"><?php _e("Thu", ET_DOMAIN);?></li>
                                        <li class="bdate" data-name="Fri" data-toggle="tooltip" data-placement="bottom"><?php _e("Fri", ET_DOMAIN);?></li>
                                        <li class="bdate" data-name="Sat" data-toggle="tooltip" data-placement="bottom"><?php _e("Sat", ET_DOMAIN);?></li>
                                        <li class="bdate lbdate" data-name="Sun" data-toggle="tooltip" data-placement="bottom"><?php _e("Sun", ET_DOMAIN);?></li>
                                    </ul>
                                    <span class="select-date-all mselect-date-all"><?php _e("Select All", ET_DOMAIN);?></span>
                                    <span class="reset-all"><?php _e("Reset All", ET_DOMAIN);?></span>
                                    <span class="open-input">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="row">
                    	<div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("CATEGORY", ET_DOMAIN); ?>
                                <span><?php _e("Select the most suitable one for your business", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8 search-category">
                            <?php 
                            ae_tax_dropdown( 'place_category' , 
                                                    array(  'attr' => 'multiple data-placeholder="'.__("Choose categories", ET_DOMAIN).'"', 
                                                            'class' => 'chosen multi-tax-item tax-item required', 
                                                            'hide_empty' => false, 
                                                            'hierarchical' => true , 
                                                            'id' => 'place_category' , 
                                                            'show_option_all' => false 
                                                        ) 
                                                ) ;?> 
                        </div>
                    </div>
                </li>
                <!-- MULTIRATING -->
                <?php 
                $critical = ae_get_option('enable_critical');
                if (!$critical) {
                    do_action('select_review_criteria'); 
                } else { 
                    do_action('select_cate_critical');
                } 
                ?> 
                <?php do_action('ae_submit_post_form','place', $post); ?>
                <li>
                	<div class="row" id="gallery_place">
                    	<div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("PHOTOS", ET_DOMAIN); ?>
                                <span><?php printf(__("Up to %s pictures<br>Select one picture for your featured image", ET_DOMAIN), ae_get_option('max_carousel', 5)); ?></span>
                            </span>
                        </div>
                        <div class="form-group clearfix form-field edit-gallery-image col-md-8 col-sm-8 gallery_container" id="gallery_container">
                            <ul class="gallery-image carousel-list image-list" id="image-list">
                                <li>
                                    <div class="plupload_buttons carousel_container" id="carousel_container">
                                        <span class="img-gallery carousel_browse_button" id="carousel_browse_button">
                                            <a href="#" class="add-img"><i class="fa fa-plus"></i></a>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
                            
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <span class="title-plan">
                                <?php _e("DESCRIPTION", ET_DOMAIN); ?>
                                <span><?php _e("Ideally 3 short paragraphs", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <?php wp_editor( '', 'post_content', ae_editor_settings()  );  ?>
                            <!-- <textarea name="post_content" id="post_content" class="text-field text-editor" placeholder=""></textarea> -->
                        </div>
                    </div>
                </li>
                <!-- Captcha -->
                <?php if(!current_user_can( 'administrator' ) && ae_get_option('gg_captcha')){ ?>
                    <li class="gg-captcha">
                        <div class="row">
                            <div class="col-md-4 col-sm-4"></div>
                            <div class="col-md-8 col-sm-8">
                                <?php ae_gg_recaptcha(); ?>
                            </div>
                        </div>
                    </li>
                <?php } ?>
                <li>
                	<div class="row">
                    	<div class="col-md-4 col-sm-4"></div>
                        <div class="col-md-8 col-sm-8">
                            <input type="hidden" class="input-item" id="user_confirm" name="user_confirm" value="<?php echo ae_get_option('user_confirm'); ?>">
                        	<input type="submit" value="<?php echo (!$disable_plan) ? __("Continue", ET_DOMAIN) : __("Submit", ET_DOMAIN); ?>" class="btn btn-submit-login-form" />
                        </div>
                    </div>
                </li>
            </ul>
        </form>
    </div>
</div>
<!-- Step 3 / End -->