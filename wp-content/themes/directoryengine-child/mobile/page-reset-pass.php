<?php

et_get_mobile_header();
if(have_posts()) { the_post();

$user_login = isset($_REQUEST['user_login']) ? $_REQUEST['user_login'] :'';
$key        = isset($_REQUEST['key']) ? $_REQUEST['key'] :'';
?>


<!-- Top bar -->
    <section id="top-bar" class="section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-centered">
                    <h1 class="title-page"><?php _e("Reset Password", ET_DOMAIN); ?></h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->
    
    <!-- List News -->
    <section id="login-page-wrapper" class="section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="login-page">
                        <div class="message">
                        </div>
                        <div class="content-news">
                            <form role="form" id="resetpass_form">
                            	<input type="hidden" value="<?php echo $user_login;?>" name="user_login" id="user_login">  
                                <input type="hidden" value="<?php echo $key;?>" name="user_key" id="user_key"> 
                                <div class="form-field user_name">
                                    <label for="new_password"><?php _e("Your new password", ET_DOMAIN); ?></label>
                                    <input type="password" name="new_password" class="new_password form-control" id="new_password" placeholder="">
                                </div>
                                <div class="form-field user_password">
                                    <label for="re_new_password"><?php _e("Retype your password", ET_DOMAIN); ?></label>
                                    <input type="password" name="re_new_password" class="re_new_password form-control" id="re_new_password" placeholder="">
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-field submit_singin" style="padding-top: 20px">
                                    <p class="form-submit">
                                        <input name="submit" type="submit" class="btn-submit" id="submit" value="<?php _e( 'SUBMIT' , ET_DOMAIN ); ?>">
                                    </p>                                                                        
                                 </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
}
et_get_mobile_footer();