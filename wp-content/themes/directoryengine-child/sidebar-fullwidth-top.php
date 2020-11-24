<?php

if (is_active_sidebar('de-fullwidth-top')) {
    ?>

<!--    <div class="sidebar-fullwidth-top">
        <?php /*dynamic_sidebar('de-fullwidth-top'); */?>
    </div>-->
    <div class="searchslide">
        <div class="container">
            <form action="<?php echo home_url(); ?>" method="get">
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <input value="<?php echo(isset($_REQUEST['s']) ? $_REQUEST['s'] : '') ?>" type="text" name="s"
                               class="option-search-textfield"
                               placeholder="<?php _e("Enter your keyword", ET_DOMAIN); ?>"/>
                    </div>
                    <div class="col-sm-3">
                        <?php ae_tax_dropdown('location',
                            array('class' => 'chosen-single tax-item',
                                'hide_empty' => true,
                                'hierarchical' => true,
                                // 'id' => 'location' ,
                                'show_option_all' => __("Select your location", ET_DOMAIN),
                                'value' => 'slug',
                                'name' => 'l',
                                'name_option' => 'l',
                                'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
                            )
                        ); ?>
                    </div>
                    <!-- /* 18_11_2016 Id : 1 S*/ -->
                    <div class="col-sm-3">
                        <?php ae_tax_dropdown('place_category',
                            array('class' => 'chosen-single-category tax-item',
                                'show_option_all' => __("Select your category", ET_DOMAIN),
                                'hide_empty' => true,
                                'hierarchical' => true,
                                'id' => 'place_category-single' ,
                                'value' => 'slug',
                                'name' => 'c',
                                'name_option' => 'c',
                                'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
                            )
                        ); ?>

                    </div>
                    <!-- /* 18_11_2016 Id : 1 E*/ -->

                    <div class="col-sm-3">
                        <input type="hidden" name="radius" id="radius"/>
                        <input type="hidden" name="days" id="days"/>
                        <input type="hidden" name="price" id="price"/>
                        <input type="hidden" name="center" id="center"/>
                        <input class="btn-search" type="submit" value="<?php _e("Search", ET_DOMAIN); ?>"/>
                    </div>
                    </ul>
                </div>
            </form>
        </div>
    </div>

    <?php
}