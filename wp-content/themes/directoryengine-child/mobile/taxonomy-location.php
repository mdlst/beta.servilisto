<?php
global $wp_query;
$curpage = isset($wp_query->query_vars['taxonomy']) ? $wp_query->query_vars['taxonomy'] : '';

if ($curpage=="location"){  // es pagina location. /provincia/granada.
    $_REQUEST['l']=$term;  //Para cargar en el filtro la provincia
}

et_get_mobile_header();

?>
<!-- Top bar -->
<div id="place-list-wrapper" >
    <section id="top-bar" class="section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-6" style="padding: 0 3px;">
                    <div class="cat_left tax-location">
                        <?php
                        ae_tax_dropdown( 'place_category',
                            array( 'hierarchical' => true,
                                'hide_empty' => true,
                                'show_option_all' => __("Categories", ET_DOMAIN),
                                'value' => 'slug',
                                'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : '',
                            )); 	  ?>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0 3px;">
                    <div class="loc_right">
                        <?php
                        ae_tax_dropdown( 'location',
                            array( 'hierarchical' => true,
                                'hide_empty' => true,
                                'show_option_all' => __("Location", ET_DOMAIN),
                                'value' => 'slug',
                                'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : '',
                            )); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->
    
    <!-- Top bar -->
    <section class="section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php 
                        get_template_part( 'mobile/template/publish', 'places' );
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
    <?php  et_get_mobile_footer(); ?>