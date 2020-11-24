<?php
/**
 * Template Name: Desktop login page
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

get_header();

?>

    <!-- Breadcrumb Blog -->
    <div class="section-detail-wrapper breadcrumb-blog-page">
        <ol class="breadcrumb">
            <li><a href="<?php echo home_url() ?>"
                   title="<?php echo get_bloginfo('name'); ?>"><?php _e("Home", ET_DOMAIN); ?></a></li>
            <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
        </ol>
    </div>
    <!-- Breadcrumb Blog / End -->

    <!-- Page Blog -->
    <section id="login-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-12 centered">
                    <div class="blog-wrapper">
                        <div class="section-detail-wrapper padding-top-bottom-20">
                            <h1 class="media-heading title-blog"><?php the_title(); ?></h1>

                            <div class="clearfix"></div>
                        </div>
                        <div class="section-detail-wrapper padding-top-bottom-20">

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
                                           onclick="theChampInitiateLogin(this)" style="display: block;"> <span> <i
                                                    class="fa fa-google"></i> </span> Conectarse con Google</i>
                                    </a>
                                </p>

                                <div class="clear"></div>
                            </div>
                            <span class="o align-centered separation-login">ó</span>

                            <form class="signin_form form_modal_style" id="page_signin_form">
                                <div class="form-field user_name">
                                    <label><?php _e("Username or Email", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field email_user" name="user_login" id="sig_name"/>
                                </div>
                                <div class="form-field user_password">
                                    <label><?php _e("Password", ET_DOMAIN) ?></label>
                                    <input type="password" class="text-field password_user" id="sig_pass"
                                           name="user_pass"/>
                                    <a href="#" class="page_link_forgot_pass"><?php _e("Forgot password", ET_DOMAIN) ?>
                                        &nbsp;<i class="fa fa-question-circle"></i></a>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-field submit_singin">
                                    <input type="submit" name="submit" value="<?php _e("Sign in", ET_DOMAIN) ?>"
                                           class="btn-submit"/>
                                    <a href="#" class="page_link_sign_up"><?php _e("Sign up", ET_DOMAIN) ?></a>
                                    <?php
                                    if (function_exists('ae_render_social_button')) {
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
                                        ae_render_social_button($icon_classes, $button_classes);
                                    }
                                    ?>
                                </div>
                            </form>

                            <form class="signup_form form_modal_style" id="page_signup_form">
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
                                    <input type="password" class="text-field repeat_password_user_signup"
                                           id="re_password" name="re_password"/>
                                </div>
                                <!--// password -->
                                <?php //} ?>
                                <?php if (ae_get_option('gg_captcha')) { ?>
                                    <div class="form-field">
                                        <?php ae_gg_recaptcha(); ?>
                                    </div>
                                <?php } ?>

                                <div class="clearfix"></div>
                                <div class="form-field">
                                    <input type="submit" name="submit" value="<?php _e("Sign up", ET_DOMAIN) ?>"
                                           class="btn-submit"/>
                                    <a href="#" class="page_link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
                                </div>
                                <div class="clearfix"></div>

                                <p class="policy-sign-up term-of-use">
                                    (*) Al hacer click en "Registrate", estás indicando que has leido y aceptas nuestros <a
                                        target="new" href="<?= get_bloginfo('url'); ?>/terminos-y-condiciones/">términos
                                        y condiciones</a>
                                </p>
                            </form>

                            <form class="forgotpass_form form_modal_style collapse" id="page_forgotpass_form">
                                <div class="form-field">
                                    <label><?php _e("Enter your email here", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field name_user email" name="email"
                                           id="forgot_email"/>
                                </div>
                                <input type="submit" name="submit" value="<?php _e("Send", ET_DOMAIN) ?>"
                                       class="btn-submit"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span class="oculto role_actual_login_social">user</span>
    </section>
    <!-- Page Blog / End -->


<?php
get_footer();

