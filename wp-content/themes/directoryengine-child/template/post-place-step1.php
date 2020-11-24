<!-- Step 1 -->
<?php
global $user_ID, $ae_post_factory;
$ae_pack = $ae_post_factory->get('pack');
$step = 1;
$packs = $ae_pack->fetch();

$package_data = AE_Package::get_package_data($user_ID);  // si es "" es porque es pack Gratis
$orders = AE_Payment::get_current_order($user_ID);


/**************** Miguel */

if (is_user_logged_in()) {
    global $current_user;
    if ($current_user) {
        $args = array(
            'author' => $current_user->ID, // I could also use $user_ID, right?
            'post_type' => 'place',
            'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit')
        );
        $current_user_posts = get_posts($args);
        if (sizeof($current_user_posts) > 0) {

            if ($package_data == "") {  // si tiene anuncios pero su paquete es vacio, es gratis
                $package_data["sku"] = "001";
            }

        }
    }
}
/***************************/
?>

<div class="step-wrapper step-plan" id="step-plan">
    <a href="#" class="step-heading active">
        <span class="number-step"><span><?php echo $step; ?></span></span>
        <h2 class="text-heading-step"><?php _e('Elige tu anuncio', ET_DOMAIN); ?></h2>
        <i class="fa fa-caret-down"></i>
    </a>

    <div class="step-content-wrapper content" style="<?php if ($step != 1) echo "display:none;" ?>">
        <ul class="list-price">
            <?php foreach ($packs as $key => $package) {
                $number_of_post = $package->et_number_posts;
                $sku = $package->sku;
                $text = '';
                $order = false;
                if ($number_of_post > 1) {
                    // get package current order
                    if (isset($orders[$sku])) {
                        $order = get_post($orders[$sku]);
                    }

                    if (isset($package_data[$sku]) && $package_data[$sku]['qty'] > 0) {
                        /**
                         * print text when company has job left in package
                         */
                        $number_of_post = $package_data[$sku]['qty'];
                        if ($number_of_post > 1) {
                            $text = sprintf(__("You can submit %d posts using this plan.", ET_DOMAIN), $number_of_post);
                        } else {
                            $text = sprintf(__("You can submit %d post using this plan.", ET_DOMAIN), $number_of_post);
                        }
                    } else {
                        /**
                         * print normal text if company dont have job left in this package
                         */
                        $text = sprintf(__("You can submit %d posts using this plan.", ET_DOMAIN), $number_of_post);
                    }

                }

                $class_select = '';
                if ($package->et_price > 0 && isset($package_data[$sku]['qty']) && $package_data[$sku]['qty'] > 0) {
                    $order = get_post($orders[$sku]);
                    if ($order && !is_wp_error($order)) {
                        $class_select = 'auto-select ' . $order->post_status;
                    }
                }

                ?>

                <?php
                if (isset($package_data['sku']) && ($package_data['sku'] == $package->sku)) {
                    $actual = true;
                } else {
                    $actual = false;
                }
                ?>

                <li
                    class="<?= $class_select; ?> <?= ($actual) ? 'opcion_usuario_seleccionado' : ''; ?>"
                    data-sku="<?php echo $package->sku ?>"
                    data-id="<?php echo $package->ID ?>"
                    data-price="<?php echo $package->et_price; ?>"
                    <?php if ($package->et_price) { ?>
                        data-label="<?php printf(__("Has elegido el anuncio: %s", ET_DOMAIN), $package->post_title); ?>"
                    <?php } else { ?>
                        data-label="<?php _e("Has elegido el anuncio Gratis", ET_DOMAIN); ?>"
                    <?php } ?>
                >

                    <?php
                    if ($actual) { ?>
                        <div class="ribbon"><span>Plan actual</span></div>
                    <?php } ?>

                    <span class="price price_desk">
                        <?php if ($package->et_price > 0) {
                            echo ae_price($package->et_price) . ' / mes';
                        } else {
                            echo 'Gratis';
                        } ?></span>
                <span class="title-plan">
                    <?php echo $package->post_title;
                    if ($text) {
                        echo ' - ' . $text;
                    } ?>
                    <span class="price price_mobile"><?php if ($package->et_price > 0) {
                            echo ae_price($package->et_price) . ' / mes';
                        } else {
                            echo 'Gratis';
                        } ?></span>
                    <span><?php echo $package->post_content; ?></span>
                </span>
                    <?php
                    if (!$actual) { ?>
                        <span class="block-select-plan">
                            <a href="#"
                               class="btn btn-submit-price-plan select-plan"><?php _e('Select', ET_DOMAIN); ?></a>
                        </span>
                    <?php } ?>


                    <div class="clearfix"></div>
                </li>
            <?php }
            echo '<script type="data/json" id="package_plans">' . json_encode($packs) . '</script>';
            ?>
        </ul>
    </div>
</div>

<!-- Step 1 / End -->