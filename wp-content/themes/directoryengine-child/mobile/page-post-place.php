<?php
/**
 * Template Name: Post Places FullWidth
*/
global $user_ID;
et_get_mobile_header();
?>
<!-- Top bar -->
<section id="top-bar" class="section-wrapper"> 
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-centered text-centered">
                <h1 class="title-page"><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
</section>
<!-- Top bar / End -->
<!-- Breadcrumb Blog / End -->

<!-- Page Post Place -->
<section id="blog-page">
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
                if($tos) { ?>
                    <div class="term-of-use">                           
                    <?php 
                        printf(__('By posting your place, you agree to our %s (*)', ET_DOMAIN) , 
                                '<a target="_blank" href="'. $tos .'">'. __("Terms of use", ET_DOMAIN) .'</a>');
                    ?>
                    </div>
                <?php } ?>
                
            <!-- Column left / End --> 
            </div>
            <!-- Column right -->

            <!-- Column right / End -->
	   </div>
    </div>
</section>
<!-- Page Post Place / End -->        

<?php
et_get_mobile_footer();
