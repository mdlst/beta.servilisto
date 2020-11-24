<?php
	global $post;
	$send_to = is_author() ? get_query_var( 'author' ) : $post->post_author;
?>
<div class="modal fade modal-submit-questions" id="contact_message" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title modal-title-sign-in">
					<?php _e("Private Message", ET_DOMAIN) ?>
				</h4>
			</div>
			<div class="modal-body">		
		        <form id="submit_contact" class="form_modal_style edit_profile_form">
		        	<input type="hidden" name="send_to" id="send_to" value="<?php echo $send_to ?>" />
		        	<input type="hidden" name="place_link" id="place_link" value="<?php echo $place_link;?>" />
                    <input type="hidden" name="id_anuncio" id="id_anuncio" value="<?php echo $post->ID;?>" />
		            <div class="form-field">	            
			            <label><?php _e("Message", ET_DOMAIN) ?></label>
			            <textarea id="message" name="message"></textarea>
		            </div>		            
		            <div class="clearfix"></div>
		            <div class="form-field">
		            	<input type="submit" name="submit" value="<?php _e("Send", ET_DOMAIN) ?>" class="btn-submit update_profile" />
		            </div>
		        </form>
			</div>
		</div>
	</div>
</div>