<?php
$step = 2;
$disable_plan = ae_get_option('disable_plan', false);
if ($disable_plan) $step--;
?>
<div class="step-wrapper step-auth" id="step-auth">

    <a href="#" class="step-heading active">
        <span class="number-step"><span><?php echo $step; ?></span></span>
        <h2 class="text-heading-step"><?php _e("Regístrate o inicia sesión", ET_DOMAIN); ?></h2>
        <i class="fa fa-caret-right"></i>
    </a>

    <div class="step-content-wrapper content  " style="<?php if ($step != 1) echo "display:none;" ?>">
        <form action="" class="auth">
            <ul class="list-form-login">
                <li>
                    <div class="row">
                        <div class="col-md-4">
                	<span class="title-plan">
                		<?php _e("Already have an account?", ET_DOMAIN); ?><br>
                            <span>
                                <a class="authenticate" href="#"><?php _e("Inicia sesión", ET_DOMAIN); ?></a>
                                </span>

                    </span>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="form-field social-btn main_social_btn">
                                <p class="col-xs-12 col-md-6">
                                    <a class="btn btn-block btn-social btn-facebook the_champ_login_container_fb" href="javascript:void(0)">
                                        <i class="theChampLogin theChampFacebookLogin"
                                           title="Entrar con Facebook" onclick="theChampInitiateLogin(this)">
                                            <span> <i class="fa fa-facebook"></i> </span>Conectarse con Facebook</i>
                                    </a>
                                </p>

                                <?php if (!getIfApp()): // si no es app ponemos el boton de google plus ?>
                                <p class="col-xs-12 col-md-6">
                                    <a class="btn btn-block btn-social btn-google the_champ_login_container_goo" href="javascript:void(0)">
                                        <input type="hidden" value="1" id="flag">
                                        <i class="theChampLogin theChampGoogleLogin"
                                           title="Entrar con Google"
                                           onclick="theChampInitiateLogin(this)" style="display: block;"> <span> <i
                                                    class="fa fa-google"></i> </span> Conectarse con Google</i>
                                    </a>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
                <!--  <li>
                    <div class="row">
                        <div class="col-md-4">
                            <span class="title-plan">
                                <?php /*_e("FULL NAME", ET_DOMAIN); */ ?>
                                <span><?php /*_e("First and last name", ET_DOMAIN); */ ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="display_name" id="display_name" class="text-field input-item"
                                   placeholder="<?php /*_e("Enter your full name", ET_DOMAIN); */ ?>"/>
                        </div>
                    </div>
                </li>-->
                <!--  <li>
                	<div class="row">
                    	<div class="col-md-4">
                            <span class="title-plan">
                                <?php _e("USER NAME", ET_DOMAIN); ?>
                                <span><?php _e("Enter a username", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="user_login" class="text-field input-item" placeholder="<?php _e("Enter your username", ET_DOMAIN); ?>" />
                        </div>
                    </div>
                </li>-->
                <li>
                    <div class="row">
                        <div class="col-md-4">
                            <span class="title-plan">
                              <span class="mandatory_fields">*</span> <?php _e("EMAIL", ET_DOMAIN); ?>
                                <span><?php _e("Enter a email", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <input
                                type="text"
                                name="user_email"
                                id="user_email"
                                class="text-field input-item email onfocustop"
                                placeholder="Introduce tu email"
                                value="<?= (isset($_GET["email"])) ? $_GET["email"] : '' ?>"
                            />
                        </div>
                    </div>
                </li>

                <li>
                    <div class="row">
                        <div class="col-md-4 pull-right captcha-align">
                            <?php if (ae_get_option('gg_captcha')) { ?>
                                <div class="form-field">
                                    <?php ae_gg_recaptcha2(); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </li>
                <!--  <li>
                	<div class="row">
                    	<div class="col-md-4">
                            <span class="title-plan">
                                <?php //_e("PHONE NUMBER", ET_DOMAIN); ?>
                                <span><?php //_e("Incule your area code", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="phone" id="phone" class="text-field input-item" placeholder="<?php //_e("Enter your phone number with area code", ET_DOMAIN); ?>">
                        </div>
                    </div>
                </li>
                 <li>
                	<div class="row">
                    	<div class="col-md-4">
                            <span class="title-plan">
                                <?php //_e("PROFILE PICTURE", ET_DOMAIN); ?>
                                <span><?php //_e("Maximum size: 1MB", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <div id="user_avatar_container">
                                <span class="img-author">
                                    <span class="author-avatar image" id="user_avatar_thumbnail">
                                        <?php //echo get_avatar($user->ID, 135) ?>
                                    </span>
                                    <a href="#" class="new-look" id="user_avatar_browse_button">
                                        <i class="fa fa-pencil"></i>
                                        <?php //_e('New look', ET_DOMAIN) ?>
                                    </a>
                                </span>
                                <span class="et_ajaxnonce" id="<?php //echo wp_create_nonce('user_avatar_et_uploader'); ?>"></span>
                            </div>
                        </div>
                    </div>
                </li> -->
                <!-- <li>
                	<div class="row">
                    	<div class="col-md-4">
                            <span class="title-plan">
                                <?php //_e('PASSWORD', ET_DOMAIN); ?>
                                <span><?php //_e('Enter password', ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <input type="password" name="user_pass" id="user_pass" class="text-field input-item" placeholder="<?php //_e('Your password', ET_DOMAIN); ?>" />
                        </div>
                    </div>
                </li>
                <li>
                	<div class="row">
                    	<div class="col-md-4">
                            <span class="title-plan">
                                <?php //_e('RETYPE PASSWORD', ET_DOMAIN); ?>
                                <span><?php //_e('Retype password', ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <input type="password" name="repeat_password" id="repeat_password" class="text-field input-item" placeholder="<?php //_e('Retype your password', ET_DOMAIN); ?>" />
                        </div>
                    </div>
                </li>-->
                <li>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <input type="submit" value="<?php _e('Continue', ET_DOMAIN); ?>"
                                   class="btn btn-submit-login-form"/>
                        </div>
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <span class="oculto role_actual_login_social">advertiser</span>
</div>
<!-- Step 2 / End