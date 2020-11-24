<?php
/**
 * Template Name: Login
 */
global $user_ID;
// user already login redirect to home page
if ($user_ID) {
    // isset redirect url
    if (isset($_REQUEST['redirect'])) {
        wp_redirect($_REQUEST['redirect']);
        exit;
    }
    wp_redirect(home_url());
    exit;
}

et_get_mobile_header();
if (have_posts()) {
    the_post();
    ?>

    <div id="page-authentication">
        <div id="login">
            <!-- Top bar -->
            <section id="top-bar" class="section-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-centered">
                            <h1 class="title-page sss7">Inicia sesión</h1>
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
                                    <form method="post" class="signin_form form_modal_style" id="signin_form">


                                        <div class="clearfix"></div>
                                        <div class="form-field social-btn">
                                            <p class="col-xs-12 col-md-6">
                                                <a class="btn btn-block btn-social btn-facebook the_champ_login_container_fb"
                                                   href="javascript:void(0)">
                                                    <i class="theChampLogin theChampFacebookLogin"
                                                       title="Entrar con Facebook"
                                                       onclick="theChampInitiateLogin(this)">
                                                        <span> <i class="fa fa-facebook"></i>
                                                        </span>Conectarse con Facebook</i>
                                                </a>
                                            </p>

                                            <?php if (!getIfApp()): // si no es app ponemos el boton de google plus ?>
                                                <p class="col-xs-12 col-md-6">
                                                    <a class="btn btn-block btn-social btn-google the_champ_login_container_goo"
                                                       href="javascript:void(0)">
                                                        <i class="theChampLogin theChampGoogleLogin"
                                                           title="Entrar con Google"
                                                           onclick="theChampInitiateLogin(this)"
                                                           style="display: block;">
                                                        <span> <i class="fa fa-google"></i>
                                                        </span> Conectarse con Google</i>
                                                    </a>
                                                </p>
                                            <?php endif; ?>

                                        </div>
                                        <div class="clearfix"></div>
                                        <span class="o align-centered separation-login">ó</span>

                                        <div class="clearfix"></div>
                                        <div class="form-field user_name">
                                            <label
                                                    style="display: block"><?php _e("Username or Email", ET_DOMAIN) ?></label>
                                            <input type="text"
                                                   class="email_user"
                                                   name="user_login"
                                                   id="sig_username_email"
                                                   placeholder="Usuario/email"
                                            />
                                        </div>
                                        <div class="form-field user_password">
                                            <label style="display: block"><?php _e("Password", ET_DOMAIN) ?></label>
                                            <input type="password"
                                                   class="password_user"
                                                   id="sig_pass"
                                                   name="user_pass"
                                                   placeholder="Contraseña"
                                            />
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="form-field submit_singin" style="padding-top: 20px">
                                            <p class="form-submit">
                                                <input name="submit" type="submit" id="submit"
                                                       value="<?php _e('SIGN IN', ET_DOMAIN); ?>">
                                                &nbsp;<a href="#"
                                                         class="link_sign_up"><?php _e("Sign up", ET_DOMAIN) ?></a>
                                            </p>

                                            <p><a href="#"
                                                  class="link_forgot_pass"><?php _e("Forgot password", ET_DOMAIN) ?>
                                                    &nbsp;<i class="fa fa-question-circle"></i></a></p>
                                        </div>


                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- List News / End -->
        </div>

        <div id="register" style="display:none;">
            <!-- Top bar -->
            <section id="top-bar" class="section-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-centered">
                            <h1 class="title-page sss7">Regístrate</h1>
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
                                    <form method="post" class="signin_form form_modal_style" id="signup_form">
                                        <div class="form-field user_name">
                                            <label style="display: block"><?php _e("Username", ET_DOMAIN) ?></label>
                                            <input type="text" class="email_user" name="user_login"
                                                   id="signup_username"/>
                                        </div>
                                        <div class="form-field user_name">
                                            <label style="display: block"><?php _e("Email", ET_DOMAIN) ?></label>
                                            <input type="text" class="email_user" name="user_email"
                                                   id="signup_useremail"/>
                                        </div>
                                        <div class="form-field user_password">
                                            <label style="display: block"><?php _e("Password", ET_DOMAIN) ?></label>
                                            <input type="password" class="password_user" id="sig_pass" name="user_pass">
                                        </div>
                                        <div class="form-field user_password">
                                            <label
                                                    style="display: block"><?php _e("Retype  Password", ET_DOMAIN) ?></label>
                                            <input type="password" class="password_user" id="repeat_pass"
                                                   name="repeat_pass">
                                        </div>
                                        <?php if (ae_get_option('gg_captcha')) { ?>
                                            <div class="form-field">
                                                <?php ae_gg_recaptcha(); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                        <div class="form-field submit_singin" style="padding-top: 20px">
                                            <p class="form-submit">
                                                <input name="submit" type="submit" id="submit"
                                                       value="<?php _e('SIGN UP', ET_DOMAIN); ?>">
                                                &nbsp<a href="#"
                                                        class="link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- List News / End -->
        </div>

        <div id="forgotpass" style="display:none;">
            <!-- Top bar -->
            <section id="top-bar" class="section-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-centered">
                            <h1 class="title-page"><?php _e("Request password", ET_DOMAIN); ?></h1>
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
                                    <form method="post" class="signin_form form_modal_style" id="forgotpass_form">
                                        <div class="form-field user_name">
                                            <label style="display: block"><?php _e("Your Email", ET_DOMAIN) ?></label>
                                            <input type="text" class="email email_user" name="user_email"
                                                   id="forgotpassmail"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-field submit_singin" style="padding-top: 20px">
                                            <p class="form-submit">
                                                <input name="submit" type="submit" id="submit"
                                                       value="<?php _e('SUBMIT', ET_DOMAIN); ?>">
                                                &nbsp<a href="#"
                                                        class="link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- List News / End -->
        </div>
    </div>

    <?php
}
et_get_mobile_footer();