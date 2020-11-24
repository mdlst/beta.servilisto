<?php
global $post;
get_header();

?>

<!-- Breadcrumb Blog -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="#" title=""><?php _e("Page not found", ET_DOMAIN); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb Blog / End -->

<!-- Page Blog -->
<section id="blog-page">
    <div class="container">
        <div class="row">
        
            <!-- Column left -->
            <div class="col-md-12">
            	<div class="img-404">
                	<img src="<?php echo get_template_directory_uri() ?>/img/404.png">
                    <p><?php _e( 'Sorry, but the page you were looking for is not here.' , ET_DOMAIN ); ?></p>
                    <a href="<?php echo home_url() ?>"><?php _e( 'Back to home page ' , ET_DOMAIN ); ?></a>
                </div>
            </div>
            <!-- Column left / End --> 

        </div>
    </div>
</section>
<!-- Page Blog / End -->   

<?php

get_footer();

