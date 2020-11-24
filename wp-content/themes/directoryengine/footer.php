<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @since DirectoryEngine 1.0
 */
if(!(is_singular() && !is_page_template('page-front.php')) && !is_category() && !is_date() && !is_author() ) {
    get_sidebar( 'fullwidth-bottom' );
}
?>  
    </div>
	<!-- FOOTER -->
    <?php 
        if( is_active_sidebar( 'de-footer-1' )    || is_active_sidebar( 'de-footer-2' ) 
            || is_active_sidebar( 'de-footer-3' ) || is_active_sidebar( 'de-footer-4' )
            )
        { ?>
    <footer>
    	<div class="container">
        	<div class="row">
            	<div class="col-md-3 col-sm-6">
                	<?php if( is_active_sidebar( 'de-footer-1' ) ) dynamic_sidebar( 'de-footer-1' );?>
                </div>
                <div class="col-md-3 col-sm-6">
                	<?php if( is_active_sidebar( 'de-footer-2' ) ) dynamic_sidebar( 'de-footer-2' );?>
                </div>
                <div class="col-md-3 col-sm-6">
                	<?php if( is_active_sidebar( 'de-footer-3' ) ) dynamic_sidebar( 'de-footer-3' );?>
                </div>
                <div class="col-md-3 col-sm-6">
                	<?php if( is_active_sidebar( 'de-footer-4' ) ) dynamic_sidebar( 'de-footer-4' );?>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER / End -->
    <?php }
    
    $copyright = ae_get_option('copyright');
    $has_nav_menu = has_nav_menu( 'et_footer' );
    $col = 'col-md-6 col-sm-6';
    if($has_nav_menu) $col = 'col-lg-4';
    ?>
    <!-- Copyright -->
    <div class="copyright-wrapper">
    	<div class="container">
        	<div class="row">
            	<div class="<?php echo $col ?>">
               		<a href="<?php echo home_url(); ?>" title="<?php echo ae_get_option('blogname'); ?>" class="logo">
                        <?php echo ae_get_option('blogname'); ?>
                    </a>
                </div>
                <?php if($has_nav_menu) { ?>
                <div class="col-lg-4 footer-menu">
                    <?php
                        wp_nav_menu( array('theme_location' =>'et_footer') );
                    ?>
                </div>
                <?php 
                }
                if($copyright) { ?>
                <div class="<?php echo $col ?>">
                	<p class="text-copyright"><?php echo str_replace( '\\', '', $copyright ) ; ?></p>
                </div>
                <?php } ?>
            </div>
        </div>  
    </div>
    <!-- Copyright / End -->
    <?php
    wp_footer();?>
</body>
</html>