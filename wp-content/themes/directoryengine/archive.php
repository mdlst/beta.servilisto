<?php
get_header();
?>
<!-- Breadcrumb Blog -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>"><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="#">
            <?php
                if ( is_day() ) :
                    printf( __( 'Daily Archives: %s', ET_DOMAIN ), get_the_date() );
                elseif ( is_month() ) :
                    printf( __( 'Monthly Archives: %s', ET_DOMAIN ), get_the_date( _x( 'F Y', 'monthly archives date format', ET_DOMAIN ) ) );
                elseif ( is_year() ) :
                    printf( __( 'Yearly Archives: %s', ET_DOMAIN ), get_the_date( _x( 'Y', 'yearly archives date format', ET_DOMAIN ) ) );
                else :
                    _e( 'Archives', ET_DOMAIN );
                endif;
            ?>
            </a></li>
    </ol>
</div>
<!-- Breadcrumb Blog / End -->

<!-- Page Blog -->
<section id="blog-page">
    <div class="container">
        <div class="row">
            <!-- Column left -->
            <div class="col-md-9 col-xs-12">
                <div class="blog-wrapper" id="blogwrapper">
                    <?php get_template_part( 'template/publish', 'blog' ); ?>
                </div>
            </div>
            <!-- Column left / End --> 
            
            <!-- Column right -->
            <?php get_sidebar( 'single' ); ?>
            <!-- Column right / End -->
        </div>
    </div>
</section>
<!-- Page Blog / End -->        
<?php
get_footer();
