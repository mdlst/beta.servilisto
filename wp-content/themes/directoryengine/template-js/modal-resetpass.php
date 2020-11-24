<div class="modal fade modal-submit-questions" id="reset_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title modal-title-sign-in" ><?php _e("Reset Password", ET_DOMAIN) ?></h4>
      </div>
      <div class="modal-body">

        <form id="resetpass_form" class="form_modal_style">
			<input type="hidden" id="user_login" name="user_login" value="<?php if(isset($_GET['user_login'])) echo $_GET['user_login'] ?>" />
			<input type="hidden" id="user_key" name="user_key" value="<?php if(isset($_GET['key'])) echo $_GET['key'] ?>" />            	
        	<label><?php _e("Enter your new password here", ET_DOMAIN) ?></label>
          <div class="form-field">
        	 <input type="password" class="name_user" name="new_password" id="new_password" />
          </div>
          <div class="form-field">
        	 <input type="password" class="name_user" name="re_new_password" id="re_new_password" />
          </div>
          <div class="form-field">
        	<input type="submit" name="submit" value="<?php _e("Reset", ET_DOMAIN) ?>" class="btn-submit" />
        </div>
        </form>	 
               
      </div>
    </div>
  </div>
</div>