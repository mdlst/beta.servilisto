<?php
/**
 *    Template Name: Process Payment
 */

/*$payment_type = get_query_var('paymentType'); Miguel*/
$payment_type = get_query_var('tipo-pago');

$session = et_read_session();
//processs payment
$payment_return = ae_process_payment($payment_type, $session);

$ad_id = $session['ad_id'];

get_header();

global $ad, $payment_return;

$payment_return = wp_parse_args($payment_return, array('ACK' => false, 'payment_status' => ''));
extract($payment_return);
if ($session['ad_id'])
    $ad = get_post($session['ad_id']);
else
    $ad = false;

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
    <section id="blog-page">
        <div class="container">
            <div class="row">
                <!-- Column left -->
                <div class="col-md-9 col-xs-12">
                    <div class="blog-wrapper">
                        <!-- post title -->
                        <div class="section-detail-wrapper padding-top-bottom-20">
                            <h1 class="media-heading title-blog"><?php the_title(); ?></h1>

                            <div class="clearfix"></div>
                        </div>
                        <!--// post title -->
                        <div class="section-detail-wrapper padding-top-bottom-20">
                            <?php
                            if ((isset($ACK) && $ACK) || (isset($test_mode) && $test_mode)) {
                                if ($ad) {
                                    $permalink = get_permalink($ad->ID);
                                } else {
                                    $permalink = home_url();
                                }

                                /**
                                 * template payment success
                                 */
                                get_template_part('template/payment', 'success');

                            } else {

                                if ($ad)
                                    $permalink = et_get_page_link('post-place', array('id' => $ad->ID));
                                else
                                    $permalink = home_url();

                                /**
                                 * template payment fail
                                 */
                                get_template_part('template/payment', 'fail');

                            }
                            // clear session
                            et_destroy_session();
                            ?>

                            <script type="text/javascript">
                                jQuery(document).ready (function () {
                                    var $count_down = jQuery('.count_down');
                                    setTimeout(function () {
                                        window.location = '<?php echo $permalink ?>';
                                    }, 10000);
                                    setInterval(function () {
                                        if ($count_down.length > 0) {
                                            var i = $count_down.html();
                                            $count_down.html(parseInt(i) - 1);
                                        }
                                    }, 1000);
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <!-- Column left / End -->

                <!-- Column right -->
                <?php get_sidebar('single'); ?>
                <!-- Column right / End -->
            </div>
        </div>
    </section>
    <!-- Page Blog / End -->

<?php
get_footer();