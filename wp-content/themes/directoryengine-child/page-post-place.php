<?php
/**
 * Template Name: Post Places FullWidth
*/
global $user_ID;
get_header();
?>
<!-- Breadcrumb Blog -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>" title="<?php echo get_bloginfo( '' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="#"><?php _e("Submit Place", ET_DOMAIN); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb Blog / End -->

<!-- Page Post Place -->
<section id="blog-page">
    <div id="bar-post-place-wrapper"><h1 class="top-title-post-place">Publica tu anuncio y gana dinero con Servilisto</h1></div>

	<div class="container">
    	<div class="row">
        	<!-- Column left -->
        	<div class="col-md-12 col-xs-12">
            	<div class="post-place-warpper" id="post-place">
                    <?php 
                    // check disable payment plan or not
                    $disable_plan = ae_get_option('disable_plan', false);
                    if(!$disable_plan) {
                        // template/post-place-step1.php
                        get_template_part( 'template/post-place', 'step1' );    
                    }                    
                    if(!$user_ID) {
                        // template/post-place-step2.php
                        get_template_part( 'template/post-place', 'step2' );
                    }
                    // template/post-place-step3.php
                    get_template_part( 'template/post-place', 'step3' );
                    if(!$disable_plan) {
                        // template/post-place-step4.php
                        get_template_part( 'template/post-place', 'step4' );
                    }    
                    ?>
                </div>
                <?php
                /**
                 * tos agreement
                */
                $tos = et_get_page_link('tos', array() ,false);
                if($tos) {
                ?>
                    <div class="term-of-use">                           
                    <?php 
                        printf(__('By posting your place, you agree to our %s (*)', ET_DOMAIN) , 
                                '<a target="_blank" href="'. $tos .'">'. __("Terms of use", ET_DOMAIN) .'</a>');
                    ?>
                    </div>
                <?php } ?>
            <!-- Column left / End --> 
            </div>
	   </div>
    </div>
</section>
<!-- Page Post Place / End -->        
<?php
get_footer();
