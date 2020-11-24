<?php


$payment_type = get_query_var('tipo-pago');
/*$payment_type = get_query_var('paymentType'); Miguel*/

$session = et_read_session();
//processs payment
$payment_return = ae_process_payment($payment_type, $session);

$ad_id = $session['ad_id'];

et_get_mobile_header();

global $ad, $payment_return;

$payment_return = wp_parse_args($payment_return, array('ACK' => false, 'payment_status' => ''));
extract($payment_return);
if ($session['ad_id'])
    $ad = get_post($session['ad_id']);
else
    $ad = false;

/************************************************************************************************** Miguel */

$test_mode=false;
if ((isset($ACK) && $ACK) || (isset($test_mode) && $test_mode)) {  // pago ok
    $pago_ok = true;
} else {
    $pago_ok = false;
}

/************************************************************************************************************/

?>
    <section id="top-bar" class="section-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-centered text-centered">
                    <h1 class="title-page"><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section id="blog-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="blog-wrapper">
                        <!-- post title -->
                        <div class="section-detail-wrapper padding-top-bottom-30">
                            <h1 class="media-heading title-blog">
                                <?= ($pago_ok)
                                    ? _e("Su anuncio ha sido enviado correctamente", ET_DOMAIN)
                                    : _e("Ha habido un problema con su anuncio", ET_DOMAIN); ?>
                            </h1>

                            <div class="clearfix"></div>
                        </div>
                        <!--// post title -->
                        <div class="section-detail-wrapper padding-top-bottom-30">
                            <?php
                            if ($pago_ok) {
                                if ($ad) {
                                    $permalink = get_permalink($ad->ID);
                                } else {
                                    $permalink = home_url();
                                }

                                /** template payment success */
                                get_template_part('template/payment', 'success');

                            } else {

                                ($ad)
                                    ? $permalink = get_permalink(get_page_by_title('Publica tu anuncio')) . "?id=" . $ad->ID
                                    : $permalink = home_url();

                                /** template payment fail*/
                                get_template_part('template/payment', 'fail');

                            }
                            // clear session
                            et_destroy_session();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
et_get_mobile_footer();