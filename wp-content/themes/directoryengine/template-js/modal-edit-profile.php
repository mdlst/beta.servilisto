<?php
    global $current_user;
    if(is_user_logged_in()){
    	$ae_user 	   = new AE_Users(array('facebook', 'phone'));
		//$ae_user       = AE_Users::get_instance();
		$user          = $ae_user->convert($current_user);
		//$user_email    = $user->user_email;
		$user_location = $user->location;
		$display_name  = $user->display_name;
		$facebook      = $user->facebook;
		$user_phone    = $user->phone;
    } else {
        $user_email = $user_location = $display_name = $facebook = $user_phone = '';
    }
?>
<div class="modal fade modal-submit-questions" id="edit_profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title modal-title-sign-in">
					<?php _e("Edit Profile", ET_DOMAIN) ?>
				</h4>
			</div>
			<ul class="nav nav-tabs list-edit-place" role="tablist" id="myTab">
                <li class="active"><a href="#user_information" role="tab" data-toggle="tab"><?php _e("Information", ET_DOMAIN); ?></a></li>
                <li><a href="#pass_information" role="tab" data-toggle="tab"><?php _e("Password", ET_DOMAIN); ?></a></li>
                
            </ul>
			<div class="modal-body">	

		        <form id="submit_edit_profile" class="form_modal_style edit_profile_form">
		        	<div class="tab-content">
                        <!-- Tabs 1 / Start -->
                        <div class="tab-pane fade active body-tabs in" id="user_information">	
				        	<div class="form-field">
		                        <label><?php _e("FULL NAME", ET_DOMAIN) ?></label>
		        				<input type="text" class="text-field submit-input" id="display_name" name="display_name" value="<?php echo $display_name; ?>" />
		                    </div>
				            <div class="form-field">
					            <label><?php _e("ADDRESS", ET_DOMAIN) ?></label>
					            <input type="text" class="text-field submit-input" id="location" name="location" value="<?php echo $user_location; ?>" />
				            </div>
				            <div class="form-field">
					            <label><?php _e("PHONE", ET_DOMAIN) ?></label>
					            <input type="text" class="text-field submit-input" id="phone" name="phone" value="<?php echo $user_phone; ?>" />	
				            </div>
				            <div class="form-field">	            
					            <label><?php _e("FACEBOOK", ET_DOMAIN) ?></label>
					            <input type="text" class="text-field submit-input" id="facebook" name="facebook" value="<?php echo $facebook; ?>" />
				            </div>	
				        </div>
                    	<!-- Tabs 1 / Start -->
                        <div class="tab-pane fade body-tabs in" id="pass_information">	
                        	<div class="form-field">
					            <label><?php _e("OLD PASSWORD", ET_DOMAIN) ?></label>
					            <input type="password" class="text-field submit-input" id="old_password" name="old_password" value="" />	
				            </div>
				            <div class="form-field">
					            <label><?php _e("NEW PASSWORD", ET_DOMAIN) ?></label>
					            <input type="password" class="text-field submit-input" id="new_password" name="new_password" value="" />	
				            </div>
				            <div class="form-field">
					            <label><?php _e("REPEAT NEW PASSWORD", ET_DOMAIN) ?></label>
					            <input type="password" class="text-field submit-input" id="renew_password" name="renew_password" value="" />	
				            </div>	                        

				            <div class="clearfix"></div>
				        </div>
				    </div>
		            <div class="form-field submit-style">
		            	<input type="submit" name="submit" value="<?php _e("Update Profile", ET_DOMAIN) ?>" class="btn-submit update_profile" />
		            </div>
		        </form>
			</div>
		</div>
	</div>
</div>