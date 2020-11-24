<div class="modal fade modal-submit-questions" id="reject_post" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
				<h4 class="modal-title modal-title-sign-in" >
                    Rechazar Anuncio
                </h4>
			</div>
			<div class="modal-body">
            	<form class="reject-ad form_modal_style">
                    		
                    <div class="form-field">
                        <label><?php _e("MESSAGE", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                        <textarea name="reject_message" class="required" required ></textarea>
                    </div>  
                    <div class="clearfix"></div>                 
                    <div class="form-field">
                        <input type="submit" value="<?php _e("Submit", ET_DOMAIN); ?>" class="btn-submit" />
                    </div>              
                    
                </form>  
			</div>
		</div>
	</div>
</div>