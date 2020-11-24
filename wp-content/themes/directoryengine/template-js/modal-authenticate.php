<div class="modal fade modal-submit-questions" id="login_register" tabindex="-1" role="dialog" aria-hidden="true">
 	<div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
	        <h4 class="modal-title modal-title-sign-in"><?php _e("Sign In", ET_DOMAIN) ?></h4>
	      </div>
	      <div class="modal-body">

	        <form class="signin_form form_modal_style" id="signin_form">
	        	<div class="form-field user_name">
		        	<label><?php _e("Username or Email", ET_DOMAIN) ?></label>
		        	<input type="text" class="text-field email_user"  name="user_login" id="sig_name" />
				</div>
				<div class="form-field user_password">
		            <label><?php _e("Password", ET_DOMAIN) ?></label>
		        	<input type="password" class="text-field password_user" id="sig_pass" name="user_pass"/>
                    <a href="#" class="link_forgot_pass"><?php _e("Forgot password", ET_DOMAIN) ?>&nbsp;<i class="fa fa-question-circle"></i></a>
				</div>
	            <div class="clearfix"></div>
	            <div class="form-field submit_singin">
		            <input type="submit" name="submit" value="<?php _e("Sign in", ET_DOMAIN) ?>" class="btn-submit"/>
		            <a href="#" class="link_sign_up"><?php _e("Sign up", ET_DOMAIN) ?></a>
		            <?php
                        if( function_exists('ae_render_social_button')){
                        	$icon_classes = array(
                        		'fb' => 'fa fa-facebook-square',
                        		'gplus' => 'fa fa-google-plus-square',
                        		'tw' => 'fa fa-twitter-square',
                        		'lkin' => 'fa fa-linkedin-square'
                        		);
                        	$button_classes = array(
                        		'fb' => 'sc-icon color-facebook',
                        		'gplus' => 'sc-icon color-google',
                        		'tw' => 'sc-icon color-twitter',
                        		'lkin' => 'sc-icon color-linkedin'
                        		);
                            ae_render_social_button( $icon_classes, $button_classes ); 
                        }
                     ?>
		         </div>
	        </form>

	        <form class="signup_form form_modal_style" id="signup_form" style="display:none;">
				<div class="form-field">
		        	<label><?php _e("Username", ET_DOMAIN) ?></label>
		        	<input type="text" class="text-field name_user" name="user_login" id="reg_name" />
				</div>
				<div class="form-field">
		            <label><?php _e("Email", ET_DOMAIN) ?></label>
		        	<input type="text" class="text-field email_user" name="user_email" id="user_email" />
	        	</div>
	        	<?php //if(!get_option( 'user_confirm' )){ ?>
				<!-- password -->
				<div class="form-field">
		            <label><?php _e("Password", ET_DOMAIN) ?></label>
		        	<input type="password" class="text-field password_user_signup" id="reg_pass" name="user_pass" />
	        	</div>
	        	<div class="form-field">
		            <label><?php _e("Retype Password", ET_DOMAIN) ?></label>
		        	<input type="password" class="text-field repeat_password_user_signup" id="re_password" name="re_password" />
		        </div>
		        <?php if(ae_get_option('gg_captcha')){ ?>
	                <div class="form-field">
	                	<div class="signup-captcha">
	                    	<?php ae_gg_recaptcha(); ?>
	                    </div>
	                </div>
                <?php } ?>
				<!--// password -->
				<?php //} ?>
	            <div class="clearfix"></div>
	            <div class="form-field submit_signup">
		            <input type="submit" name="submit" value="<?php _e("Sign up", ET_DOMAIN) ?>" class="btn-submit"/>
		            <a href="#" class="link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
	            </div>
	            <div class="clearfix"></div>
				<?php 
                $tos = et_get_page_link('tos', array() ,false);
                if($tos) { ?>
	                <p class="policy-sign-up term-of-use">
		            	<?php 
	                        printf(__('By clicking "Sign up" you indicate that you have read and agree with our %s (*)', ET_DOMAIN) , 
	                                '<a target="_blank" href="'. $tos .'">'. __("Terms of use", ET_DOMAIN) .'</a>');
	                    ?>
		            </p>
                <?php } ?>

	        </form>

	        <form class="forgotpass_form form_modal_style collapse" id="forgotpass_form">
	        	<div class="form-field">
		        	<label><?php _e("Enter your email here", ET_DOMAIN) ?></label>
		        	<input type="text" class="text-field name_user email" name="email" id="forgot_email" />
		        </div>
	        	<input type="submit" name="submit" value="<?php _e("Send", ET_DOMAIN) ?>" class="btn-submit" />
	        </form>	 
	               
	      </div>
	    </div>
  </div>
</div>