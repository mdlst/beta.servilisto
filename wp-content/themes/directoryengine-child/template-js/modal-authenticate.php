<div class="modal fade modal-submit-questions" id="login_register" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel_authenticate" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title modal-title-sign-in"
                    id="myModalLabel_authenticate"><?php _e("Sign In", ET_DOMAIN) ?></h4>
            </div>
            <div class="modal-body">

                <form class="signin_form form_modal_style" id="signin_form">
                    <div class="col-xs-12 col-md-6 login-left">
                        <h4 class="title-place"> Inicia sesión </h4>

                        <div class="form-field social-btn">
                            <p class="col-xs-12 col-md-6">
                                <a class="btn btn-block btn-social btn-facebook the_champ_login_container_fb" href="javascript:void(0)">
                                    <i class="theChampLogin theChampFacebookLogin"
                                       title="Entrar con Facebook" onclick="theChampInitiateLogin(this)"
                                       style="display: block;">
                                        <span> <i class="fa fa-facebook"></i> </span>Conectarse con Facebook</i>
                                </a>
                            </p>

                            <?php if (!getIfApp()): // si no es app ponemos el boton de google plus ?>
                            <p class="col-xs-12 col-md-6">
                                <a class="btn btn-block btn-social btn-google the_champ_login_container_goo" href="javascript:void(0)">
                                    <i class="theChampLogin theChampGoogleLogin"
                                       title="Entrar con Google"
                                       onclick="theChampInitiateLogin(this)" style="display: block;">
                                        <span> <i class="fa fa-google"></i> </span> Conectarse con Google</i>
                                </a>
                            </p>
                            <?php endif; ?>

                            <div class="clear"></div>
                        </div>
                        <span class="o align-centered separation-login">ó</span>

                        <div class="small-box">
                            <p class="into-dec"> Introduce tu <b> email </b> y tu <b> contrasena </b>

                            <div class="form-field user_name">
                                <input type="text" class="text-field email_user" placeholder="Email" name="user_login"
                                       id="sig_name"/>
                            </div>

                            <div class="form-field user_password">
                                <input type="password" class="text-field password_user" placeholder="Contrasena"
                                       id="sig_pass" name="user_pass"/>
                            </div>
                            <div class="form-field recomend-user">
                                <label><input type="checkbox"> Recordarme </label>
                            </div>
                            <div class="form-field submit_singin">
                                <input type="submit" name="submit" value="<?php _e("Sign in", ET_DOMAIN) ?>"
                                       class="btn-submit"/>
                            </div>
                            <div class="form-field forgot-user">
                                <a href="#" class="link_forgot_pass"><?php _e("Forgot password", ET_DOMAIN) ?>&nbsp;<i
                                        class="fa fa-question-circle"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 login-right">
                        <div class="register-page">
                            <h4 class="title-place"> Registrate </h4>
                            <ul class="dis-list">
                                <li>
                                    <span class="sign-img two-click">
                                        <i class="fa fa-hand-peace-o"></i>
                                    </span>
                                    <p> Contrata en 2 Clicks </p></li>
                                <li>
                                    <span class="sign-img">
                                        <i class="fa fa-eur"></i>
                                    </span>
                                    <p> Acumula €uro-puntos y disfruta de descuentos y servicios Gratis </p>
                                </li>
                                <li><span class="sign-img">
                                        <i class="fa fa-star"></i>
                                    </span>
                                    <p> Anade tus Candidatos come favoritos </p>
                                </li>
                            </ul>
                            <div class="form-field">
                                <a href="#" class="link_sign_up"><?php _e("Sign up", ET_DOMAIN) ?></a>
                            </div>
                        </div>
                    </div>
                </form>

                <form class="signup_form form_modal_style registro row" id="signup_form" style="display:none;">

                    <div class="col-md-6 col-xs-12 centered">
                        <div class="form-field social-btn">
                            <p class="col-xs-12 col-md-6">
                                <a class="btn btn-block btn-social btn-facebook the_champ_login_container_fb" href="javascript:void(0)">
                                    <i class="theChampLogin theChampFacebookLogin"
                                       title="Entrar con Facebook" onclick="theChampInitiateLogin(this)">
                                        <span> <i class="fa fa-facebook"></i> </span>Conectarse con Facebook</i>
                                </a>
                            </p>

                            <p class="col-xs-12 col-md-6">
                                <a class="btn btn-block btn-social btn-google the_champ_login_container_goo" href="javascript:void(0)">
                                    <i class="theChampLogin theChampGoogleLogin"
                                       title="Entrar con Google"
                                       onclick="theChampInitiateLogin(this)" style="display: block;"> <span>
                                            <i class="fa fa-google"></i> </span> Conectarse con Google</i>
                                </a>
                            </p>

                            <div class="clear"></div>
                        </div>
                        <span class="o align-centered separation-login">ó</span>

                        <div class="form-field">
                            <label><?php _e("Username", ET_DOMAIN) ?></label>
                            <input type="text" class="text-field name_user" name="user_login" id="reg_name"/>
                        </div>
                        <div class="form-field">
                            <label><?php _e("Email", ET_DOMAIN) ?></label>
                            <input type="text" class="text-field email_user" name="user_email" id="user_email"/>
                        </div>
                        <?php //if(!get_option( 'user_confirm' )){ ?>
                        <!-- password -->
                        <div class="form-field">
                            <label><?php _e("Password", ET_DOMAIN) ?></label>
                            <input type="password" class="text-field password_user_signup" id="reg_pass"
                                   name="user_pass"/>
                        </div>
                        <div class="form-field">
                            <label><?php _e("Retype Password", ET_DOMAIN) ?></label>
                            <input type="password" class="text-field repeat_password_user_signup" id="re_password"
                                   name="re_password"/>
                        </div>
                        <!--// password -->
                        <?php //} ?>

                        <?php if (ae_get_option('gg_captcha')) { ?>
                            <div class="form-field">
                                <?php ae_gg_recaptcha(); ?>
                            </div>
                        <?php } ?>


                        <div class="clearfix"></div>
                        <div class="form-field submit_signup">
                            <input type="submit" name="submit" value="<?php _e("Sign up", ET_DOMAIN) ?>"
                                   class="btn-submit"/>
                            <a href="#" class="link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
                        </div>

                        <div class="clearfix"></div>

                        <p class="policy-sign-up term-of-use">
                            (*) Al hacer click en "Registrate", estás indicando que has leido y aceptas nuestros <a
                                target="new" href="<?= get_bloginfo('url'); ?>/terminos-y-condiciones/">términos
                                y condiciones</a>
                        </p>

                    </div>
                </form>

                <form class="forgotpass_form form_modal_style collapse" id="forgotpass_form">
                    <div class="form-field">
                        <label><?php _e("Enter your email here", ET_DOMAIN) ?></label>
                        <input type="text" class="text-field name_user email" name="email" id="forgot_email"/>
                    </div>
                    <input type="submit" name="submit" value="<?php _e("Send", ET_DOMAIN) ?>" class="btn-submit"/>
                </form>

            </div>
        </div>
    </div>
    <span class="oculto role_actual_login_social">user</span>
</div>