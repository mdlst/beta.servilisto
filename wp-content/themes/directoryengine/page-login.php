<?php
/**
 * Template Name: Desktop login page
*/
global $user_ID;
// user already login redirect to home page
if($user_ID) {
    // isset redirect url
    if(isset($_REQUEST['redirect'])) {
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
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb Blog / End -->

<!-- Page Blog -->
<section id="login-page">
    <div class="container">
            <div class="row">
                <!-- Column left -->
                <div class="col-md-9 col-xs-12">
                    <div class="blog-wrapper">
                        <div class="section-detail-wrapper padding-top-bottom-20">
                            <h1 class="media-heading title-blog"><?php the_title(); ?></h1>
                            <div class="clearfix"></div>
                        </div>
                        <div class="section-detail-wrapper padding-top-bottom-20">
                            <form class="signin_form form_modal_style" id="page_signin_form">
                                <div class="form-field user_name">
                                    <label><?php _e("Username or Email", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field email_user"  name="user_login" id="sig_name" />
                                </div>
                                <div class="form-field user_password">
                                    <label><?php _e("Password", ET_DOMAIN) ?></label>
                                    <input type="password" class="text-field password_user" id="sig_pass" name="user_pass"/>
                                    <a href="#" class="page_link_forgot_pass"><?php _e("Forgot password", ET_DOMAIN) ?>&nbsp;<i class="fa fa-question-circle"></i></a>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-field submit_singin">
                                    <input type="submit" name="submit" value="<?php _e("Sign in", ET_DOMAIN) ?>" class="btn-submit"/>
                                    <a href="#" class="page_link_sign_up"><?php _e("Sign up", ET_DOMAIN) ?></a>
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

                            <form class="signup_form form_modal_style" id="page_signup_form">
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
                                <!--// password -->
                                <?php if(ae_get_option('gg_captcha')){ ?>
                                <div class="form-field">
                                    <?php ae_gg_recaptcha(); ?>
                                </div>
                                <?php } ?>
                                <?php //} ?>
                                <div class="clearfix"></div>
                                <div class="form-field">
                                    <input type="submit" name="submit" value="<?php _e("Sign up", ET_DOMAIN) ?>" class="btn-submit"/>
                                    <a href="#" class="page_link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
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

                            <form class="forgotpass_form form_modal_style collapse" id="page_forgotpass_form">
                                <div class="form-field">
                                    <label><?php _e("Enter your email here", ET_DOMAIN) ?></label>
                                    <input type="text" class="text-field name_user email" name="email" id="forgot_email" />
                                </div>
                                <input type="submit" name="submit" value="<?php _e("Send", ET_DOMAIN) ?>" class="btn-submit"/>
                            </form>  
                        </div>
                    </div>
                </div>
                <!-- Column left / End --> 
                
                <!-- Column right -->
                <?php get_sidebar( 'single' ); ?>
                <!-- Column right / End -->
        </div>
    </div>
</section>
<!-- Page Blog / End -->   

<?php
get_footer();

