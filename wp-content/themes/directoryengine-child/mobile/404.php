<?php
et_get_mobile_header(); 
?>
<!-- Page Blog -->
<section id="blog-page">
    <div class="container">
        <div class="row">
        
            <!-- Column left -->
            <div class="col-md-12">
            	<div class="img-404">
                	<img src="<?php echo get_template_directory_uri() ?>/img/404.png">
                    <p><?php _e( 'Sorry, but the page you were looking for is not here.' , ET_DOMAIN ); ?></p>
                    <a href="<?php echo home_url(); ?>"><?php _e( 'Back to home page' , ET_DOMAIN ); ?></a>
                </div>
            </div>
            <!-- Column left / End --> 

        </div>
    </div>
</section>
<!-- Page Blog / End -->   
<?php
    et_get_mobile_footer();
?>