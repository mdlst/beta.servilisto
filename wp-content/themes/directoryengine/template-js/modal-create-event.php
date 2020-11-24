<div class="modal fade modal-submit-questions" id="create_event"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title modal-title-sign-in"><?php _e("Create an event", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
            	<form id="event_form" class="form_modal_style">
                    <div class="tab-content">
                        <!-- Tabs 1 / Start -->
                        <div class="tab-pane fade active body-tabs in">				
                            <div class="form-field">
                                <label><?php _e("EVENT TITLE", ET_DOMAIN) ?></label>
                                <input required type="text" class="text-field required" name="post_title" id="event_title" />
                            </div>
                            
                            <div class="form-field">
                                <label><?php _e("RIBBON TEXT (keep it short and clear)", ET_DOMAIN) ?></label>
                                <input required type="text" class="text-field required" name="ribbon" id="ribbon" placeholder="<?php _e("e.g -20% or Free ", ET_DOMAIN); ?>"/>
                            </div>
        
                            <div class="form-field">
                                <label><?php _e("STORY", ET_DOMAIN) ?></label>
                                <textarea required class="text-field required" name="post_content" id="event_content" placeholder=""></textarea>
                            </div>
        					
                            <div class="form-field icon-input">
                                <label><?php _e("WHEN", ET_DOMAIN) ?></label>
                                <div class="time-picker-body">
                                    <div class="event-start-date" style="display:inline-block">
                                        <input id="event_start_date" name="open_time" class="text-field date-picker open-time"  type="text" placeholder="<?php _e("Start Event ", ET_DOMAIN); ?>" />
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    - <?php _e("to", ET_DOMAIN); ?> -
                                    <div class="event-close-date" style="display:inline-block">
                                        <input id="event_close_date" name="close_time" class="text-field date-picker close-time"  type="text" placeholder="<?php _e("End Event ", ET_DOMAIN); ?>" />
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-field edit-cover-image" id="event_banner_container">
                                    <label><?php _e("BANNER", ET_DOMAIN); ?></label>
                                    <ul class="option-cover-image">
                                        <li><span class="image-cover" id="event_banner_browse_button" style="position: relative; z-index: 1;">
                                                <span id="cover_thumbnail"></span>
                                                <i class="fa fa-cloud-upload"></i>
                                            </span>
                                        </li>
                                        <li>
                                            <span id="event_banner_thumbnail"></span>
                                        </li>
                                    </ul>
                                    <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'event_banner_et_uploader' ); ?>"></span>
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