<?php
get_header();
?>

<!-- Breadcrumb Blog -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>"><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="#"><?php printf( __( 'Tag Archives: %s', ET_DOMAIN ), single_tag_title( '', false ) ); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb Blog / End -->

<!-- Page Blog -->
<section id="blog-page">
	<div class="container">
    	<div class="row">
        
        	<!-- Column left -->
        	<div class="col-md-9 col-xs-12">
            	<?php get_template_part( 'template/publish', 'blog' ); ?>
            </div>
            <!-- Column left / End --> 
            
            <!-- Column right -->
            <?php get_sidebar( 'de-main' ); ?>
                <!-- Column right / End -->
		</div>
    </div>
</section>
<!-- Page Blog / End -->        

<?php
get_footer();
