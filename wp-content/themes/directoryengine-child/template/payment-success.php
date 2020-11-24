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

<div class="redirect-content success">
    <div class="main-center main-content">

        <div class="row">
            <div class="col-md-9 centered">
                <div class="title">
                    <?php _e("Una vez revisado y aprobado por nuestro equipo será visible en Servilisto.com. Esto llevara poco tiempo. <br><br>Te enviaremos un email cuando tu anuncio este publicado en la nuestra web. <br>Igualmente ya puedes editar tu anuncio o en cualquier momento a traves del panel de control en tu cuenta de usuario. <br><br>- Si eres un nuevo usuario en breve recibirás un email con tus datos para que puedas acceder a tu cuenta de usuario. <br>- Si tienes cualquier duda al respecto no dudes en visitar nuestra sección de preguntas frecuentes o ponerte en contacto con nosotros.", ET_DOMAIN); ?>
                </div>
                <br>
                <br>
                <br>
            <span
                class="centered align-centered"><?= _e("Muchas gracias por utilizar Servilisto.com", ET_DOMAIN); ?></span>

                <div class="content">

                    <?php

                    if ($payment_type == 'cash') {
                        printf(__("<p>Your listing has been submitted to our website.</p> %s ", ET_DOMAIN), $response['L_MESSAAGE']);
                    }
                    ?>

                    <?php if ($payment_status == 'Pending')
                        _e("Your payment has been sent successfully but is currently set as 'pending' by Paypal. <br/>You will be notified when your listing is approved.", ET_DOMAIN);
                    ?>
                </div>
                <br/>
                <br/>
            </div>
            <div class="col-md-3 centered">
                <div class="top-btn-post-place">
                    <a class='btn btn-post-place' href="<?= $permalink ?>">Edita tu anuncio</a>
                </div>
            </div>
        </div>

    </div>
</div>	