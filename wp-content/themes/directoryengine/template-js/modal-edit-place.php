<div class="modal fade modal-submit-questions" id="edit_place" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title modal-title-sign-in"><?php _e("Place Info", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
            	<form id="submit_form" class="form_modal_style">
                    <ul class="nav nav-tabs list-edit-place" role="tablist" id="myTab">
                        <li class="active"><a href="#information_place" role="tab" data-toggle="tab"><?php _e("Information", ET_DOMAIN); ?></a></li>
                        <li><a href="#cover_container" role="tab" data-toggle="tab"><?php _e("Header", ET_DOMAIN); ?></a></li>
                        <li><a href="#gallery_place" role="tab" data-toggle="tab"><?php _e("Gallery", ET_DOMAIN); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Tabs 1 / Start -->
                            <div class="tab-pane fade active body-tabs in" id="information_place">				
                                <div class="form-field">
                                    <label><?php _e("NAME", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                                    <input type="text" class="text-field required" name="post_title" id="post_title" />
                                </div>

                                <div class="form-field icon-input">
                                    <label><?php _e("ADDRESS", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                                    <input type="text" class="text-field required" name="et_full_location" id="et_full_location" />
                                    <input type="hidden" class="" name="et_location_lat" id="et_location_lat" />
                                    <input type="hidden" class="" name="et_location_lng" id="et_location_lng" />
                                    <style type="text/css">.map{height:200px !important;width:100%!important;margin-top:10px!important;}</style>
                                    <div id="map" class="map" style="display: none;" ></div>
                                </div>
    
                                <div class="form-field icon-input">
                                    <label><?php _e("LOCATION", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                                    <?php ae_tax_dropdown( 'location' , 
                                                            array(  'class' => 'chosen-single tax-item required', 
                                                                    'hide_empty' => false, 
                                                                    'hierarchical' => true , 
                                                                    'id' => 'location' , 
                                                                    'show_option_all' => __("Select your location", ET_DOMAIN) 
                                                                ) 
                                                            ) ;
                                                        ?> 
                                </div>
                                
                                <div class="form-field icon-input">
                                    <label><?php _e("PHONE", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field" name="et_phone" id="et_phone" />
                                </div>
                                <div class="form-field icon-input">
                                    <label><?php _e("WEBSITE", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field" name="et_url" id="et_url" />
                                </div>

                                 <div class="form-field icon-input">
                                    <label><?php _e("FACEBOOK", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field" name="et_fb_url" id="et_fb_url" />
                                </div>

                                 <div class="form-field icon-input">
                                    <label><?php _e("GOOGLE PLUS", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field" name="et_google_url" id="et_google_url" />
                                </div>

                                 <div class="form-field icon-input">
                                    <label><?php _e("TWITTER", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field" name="et_twitter_url" id="et_twitter_url" />
                                </div>
                                <?php if(current_user_can( 'manage_options' )){ ?>
                                <div class="form-field icon-input">
                                    <label><?php _e("CLAIMABLE", ET_DOMAIN) ?></label>
                                    <div class="inputCheck">
                                        <input class="checkbox-field" name="et_claimable_check" id="et_claimable" type="checkbox">
                                        <label class="check" for="et_claimable"></label>
                                    </div>
                                    <input type="hidden" class="input-item" id="et_claimable_value" name="et_claimable"/>

                                </div>
                                <?php } ?>
                                <div class="form-field" style="height: 110px;">
                                    <label><?php _e('OPENING TIME', ET_DOMAIN);?></label>
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
                                                <li id="Mon" class="bdate" data-name="Mon" data-toggle="tooltip" data-placement="bottom"><?php _e("Mon", ET_DOMAIN);?></li>
                                                <li id="Tue" class="bdate" data-name="Tue" data-toggle="tooltip" data-placement="bottom"><?php _e("Tue", ET_DOMAIN);?></li>
                                                <li id="Wed" class="bdate" data-name="Wed" data-toggle="tooltip" data-placement="bottom"><?php _e("Wed", ET_DOMAIN);?></li>
                                                <li id="Thu" class="bdate" data-name="Thu" data-toggle="tooltip" data-placement="bottom"><?php _e("Thu", ET_DOMAIN);?></li>
                                                <li id="Fri" class="bdate" data-name="Fri" data-toggle="tooltip" data-placement="bottom"><?php _e("Fri", ET_DOMAIN);?></li>
                                                <li id="Sat" class="bdate" data-name="Sat" data-toggle="tooltip" data-placement="bottom"><?php _e("Sat", ET_DOMAIN);?></li>
                                                <li id="Sun" class="bdate lbdate" data-name="Sun" data-toggle="tooltip" data-placement="bottom"><?php _e("Sun", ET_DOMAIN);?></li>
                                            </ul>
                                            <span class="select-date-all mselect-date-all"><?php _e("Select All", ET_DOMAIN);?></span>
                                            <span class="reset-all"><?php _e("Reset All", ET_DOMAIN);?></span>
                                            <span class="open-input">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <label><?php _e("CATEGORIES", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                                    <?php ae_tax_dropdown( 'place_category' , 
                                                            array(  'attr' => 'multiple data-placeholder="'.__("Choose categories", ET_DOMAIN).'"', 
                                                                    'class' => 'chosen multi-tax-item required ', 
                                                                    'hide_empty' => false, 
                                                                    'hierarchical' => true , 
                                                                    'id' => 'place_category' , 
                                                                    'show_option_all' => false 
                                                                ) 
                                                        ) ;
                                    ?> 
                                </div>
                                <!-- MULTIRATING -->
                                <?php 
                                    // $post = '';
                                    $critical = ae_get_option('enable_critical');
                                    if (!$critical) {
                                        do_action('select_review_criteria'); 
                                    } else { 
                                        do_action('select_cate_critical');
                                    }
                                ?>
                                
                                <!-- AE_CUSTOM_FIELD -->
                                <?php do_action( 'ae_edit_post_form', 'place', $post );?>
                                <div class="form-field">
                                    <label><?php _e("POST CONTENT", ET_DOMAIN) ?></label>
                                    <?php wp_editor( '', 'post_content', ae_editor_settings()  );  ?>
                                </div>
                                <div class="clearfix"></div>

                            </div>
                            <!-- Tabs 2 / Start -->
                            <div class="tab-pane fade body-tabs" id="cover_container">
                                <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'cover_et_uploader' ); ?>"></span>
                                <div class="form-field edit-cover-image">
                                    <label><?php _e("Cover Image", ET_DOMAIN) ?></label>
                                    <p><?php _e("Your cover image's minimum size must be 1440x500. ", ET_DOMAIN); ?>
                                    <br><?php _e("Tips: Remember the video space when designing your image.", ET_DOMAIN); ?></p>
                                    <ul class="option-cover-image">
                                        <li><span class="image-cover" id="cover_browse_button">
                                                <span id="cover_thumbnail" ></span>
                                                <i class="fa fa-cloud-upload"></i>
                                            </span>
                                        </li>
                                        <li><a id="delete-cover-image" href="#"><i class="fa fa-trash-o"></i> <?php _e("Delete image", ET_DOMAIN); ?></a></li>
                                        <!-- <li><a href="#"><i class="fa fa-cloud-upload"></i> <?php _e("Upload new image", ET_DOMAIN); ?></a></li> -->
                                    </ul>
                                </div>
                                <div class="form-field edit-cover-image">
                                    <label><?php _e("VIDEO", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field" name="et_video" id="et_video" placeholder="<?php _e('e.g. https://www.youtube.com/watch?v=d7MY1l3kcvo', ET_DOMAIN); ?>"/>
                                </div>
                                <div class="form-field icon-radio">
                                    <div class="video-position">
                                        <div class="inputRadio">
                                            <input value="left" class="checkbox-field video-position" name="video_position" id="video_left" type="radio">
                                            <label for="video_left"></label>
                                        </div>
                                        <span><?php _e("Left", ET_DOMAIN); ?></span>
                                    </div>
                                    <div class="video-position">
                                        <div class="inputRadio">
                                            <input value="right" class="checkbox-field video-position" name="video_position" id="video_right" type="radio" checked>
                                            <label for="video_right"></label>
                                        </div>
                                        <span><?php _e("Right", ET_DOMAIN); ?></span>
                                    </div>
                                </div>
                                <div class="form-field edit-cover-image">
                                    <label><?php _e("PREVIEW HEADER", ET_DOMAIN) ?></label>
                                    <span class="img-preview image" id="cover_background">
                                        <img src="<?php echo get_template_directory_uri() ?>/img/demo-preview-video.jpg" class="left-img-preview">
                                    </span>
                                </div>
                                <div class="clearfix"></div>      
                            </div>
                            <!-- Tabs 3 / Start -->
                            <div class="tab-pane fade body-tabs" id="gallery_place">
                                <div class="form-field edit-gallery-image" id="gallery_container" >
                                    <label><?php _e("PHOTOS", ET_DOMAIN) ?></label>
                                    <p><?php _e("Select one picture for your featured image", ET_DOMAIN); ?></p>
                                    <ul class="gallery-image carousel-list" id="image-list">
                                        <li>
                                            <div class="plupload_buttons" id="carousel_container">
                                                <span class="img-gallery" id="carousel_browse_button">
                                                    <a href="#" class="add-img"><i class="fa fa-plus"></i></a>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                    <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                    </div>
                    <div class="submit-style">
                        <input type="submit" value="<?php _e("Submit", ET_DOMAIN); ?>" class="btn-submit" />
                    </div>
                </form>  
			</div>
		</div>
	</div>
</div>