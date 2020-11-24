<?php
/**
 * this template for payment success, you can overide this template by child theme
 */
global $ad, $payment_return;
extract($payment_return);
$permalink = get_permalink($ad->ID);
/*$payment_type = get_query_var('paymentType'); Miguel*/
$payment_type = get_query_var('tipo-pago');
?>

<div class="redirect-content ">
    <div class="main-center main-content">

        <?php

        if ($payment_type == 'cash') {
            printf(__("<p>Your listing has been submitted to our website.</p> %s ", ET_DOMAIN), $response['L_MESSAAGE']);
        }
        ?>
        <div class="title"><?php _e("Success, friend", ET_DOMAIN); ?></div>
        <div class="content">
            <?php if ($payment_status == 'Pending')
                _e("Your payment has been sent successfully but is currently set as 'pending' by Paypal. <br/>You will be notified when your listing is approved.", ET_DOMAIN);
            ?>
            <br/>
           <!-- <?php /*_e("You are now redirected to your listing page ... ", ET_DOMAIN); */?> <br/>
            --><?php /*printf(__('Time left: %s', ET_DOMAIN), '<span class="count_down">10</span>'); */?>
        </div>
        <?php echo '<a href="' . $permalink . '" >' . get_the_title($ad->ID) . '</a>'; ?>
    </div>
</div>	