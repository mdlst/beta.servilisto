<!-- Top bar -->
<div id="step-plan">
    <section class="top-bar section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <h1 class="title-page"><?php _e("Post a Place", ET_DOMAIN); ?></h1>
                </div>
                <div class="col-xs-6">
                    <span class="title-step-number"><?php printf(__("Step %s of %s", ET_DOMAIN), '<strong>1</strong>', '<strong>3</strong>') ?></span>
                </div>
            </div>
        </div>
    </section>
    <?php
        global $user_ID, $ae_post_factory;
        $ae_pack = $ae_post_factory->get('pack');

        $packs = $ae_pack->fetch();

        $package_data = AE_Package::get_package_data( $user_ID );
    ?>

    <section id="plan-post-place" class="section-wrapper"> 
        <div class="step-content-wrapper form-post-wrapper content">
            <ul class="list-price">
            <?php foreach ($packs as $key => $package) { 
                $number_of_post =   $package->et_number_posts;
                $sku = $package->sku;
                $text = '';
                if($number_of_post > 1 ) {
                    if( isset($package_data[$sku] ) && $package_data[$sku]['qty'] > 0 ) {
                        /**
                         * print text when company has job left in package
                        */
                        $number_of_post =   $package_data[$sku]['qty'];
                        if($number_of_post > 1 ) {
                            $text = sprintf(__("You can submit %d posts using this plan.", ET_DOMAIN) , $number_of_post);
                        }
                        else  {
                            $text = sprintf(__("You can submit %d post using this plan.", ET_DOMAIN) , $number_of_post);
                        }
                    }else {
                        /**
                         * print normal text if company dont have job left in this package
                        */
                        $text = sprintf(__("You can submit %d posts using this plan.", ET_DOMAIN) , $number_of_post);       
                    }
                    
                } 
            ?>
                <li data-sku="<?php echo $package->sku ?>" data-id="<?php echo $package->ID ?>" data-price="<?php echo $package->et_price; ?>" >
                    <span class="price"><?php ae_price($package->et_price); ?></span>
                    
                    <a href="#" class="btn btn-submit-price-plan select-plan"><?php _e( 'Select' , ET_DOMAIN ); ?></a>
                    <span class="title-plan">
                        <?php echo $package->post_title; if($text) { echo ' - '. $text; } ?> 
                        <span><?php echo $package->post_content; ?></span>
                    </span>
                </li>
            <?php } ?>
            </ul>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" class="btn-submit-post-place" name="" value="Continue"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->
</div>