<?php 
	global $current_user, $post;

	$claim_info = get_post_meta( $post->ID, 'et_claim_info', true );
	if(!empty($claim_info)){
		$user_request = $claim_info['user_request'];
		$display_name = $claim_info['display_name'];
		$location     = $claim_info['location'];
		$phone        = $claim_info['phone'];
		$message      = $claim_info['message'];
	} else {
		$display_name = $location = $phone = $message = '';
	}
?>
<div class="modal fade modal-submit-questions" id="claim_modal" role="dialog" aria-labelledby="myModalLabel_claim" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title modal-title-sign-in" id="myModalLabel_claim">
					<?php _e("Claim this Place", ET_DOMAIN) ?>
				</h4>
			</div>
			<div class="modal-body">		
		        <form id="submit_claim" class="form_modal_style edit_profile_form">
		         <div class="tab-content">
                        <!-- Tabs 1 / Start -->
                        <div class="tab-pane fade active body-tabs in" id="information_place">		
				        	<input type="hidden" name="user_request" value="<?php echo isset($user_request) ? $user_request : $current_user->ID ?>" />
				        	<input type="hidden" id="claim_action" name="claim_action" value="<?php echo empty($claim_info) ? 'request' : 'approve'; ?>" />
							<div class="form-field">
								<label><?php _e("FULL NAME", ET_DOMAIN) ?></label>
								<input type="text" class="text-field submit-input" id="display_name" name="display_name" value="<?php echo $display_name ?>" />
							</div>

							<div class="form-field">
								<label><?php _e("ADDRESS", ET_DOMAIN) ?></label>
								<input type="text" class="text-field submit-input" id="location" name="location" value="<?php echo $location ?>" />
							</div>

							<div class="form-field">
								<label><?php _e("PHONE", ET_DOMAIN) ?></label>
								<input type="text" class="text-field submit-input" id="phone" name="phone" value="<?php echo $phone ?>" />	
							</div>	

				            <div class="form-field">	            
					            <label><?php _e("Note", ET_DOMAIN) ?></label>
					            <textarea id="message" name="message"><?php echo $message ?></textarea>
				            </div>		          

				            <div class="clearfix"></div>

		            	</div>
			            <div class="form-field submit-style">
			            	<input type="submit" name="submit" value="<?php echo empty($claim_info) ? __("Send", ET_DOMAIN) : __("Approve", ET_DOMAIN); ?>" class="btn-submit trigger-claim" />
			            	<?php if(current_user_can( 'manage_options' )){ ?>
			            	<a href="#" class="deny-claim"><?php _e("Deny", ET_DOMAIN) ?> </a>
			            	<?php } ?>
			            </div>
		            </div>
		        </form>
			</div>
		</div>
	</div>
</div>