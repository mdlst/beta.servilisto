<?php
get_header();

?>

    <!-- Breadcrumb Blog -->
    <div class="section-detail-wrapper breadcrumb-blog-page">
        <ol class="breadcrumb">
            <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
            <li><a href="<?php echo home_url('blog/') ?>" title="Blog">Blog</a></li>
        </ol>
    </div>

    <!-- Page Blog -->
    <section id="blog-page">
        <div class="container">
            <?php get_sidebar('top'); ?>
            <div class="row">
                <div id="bar-post-place-wrapper">
                    <h1 class="top-title-post-place">Blog de trabajo y servicios a domicilio Servilisto</h1>
                </div>

                <!-- Column left -->
                <div class="col-md-9 col-xs-12">

                    <div class="blog-wrapper">
                        <?php get_template_part('template/publish', 'blog'); ?>
                    </div>
                </div>
                <!-- Column left / End -->

                <!-- Column right -->
                <?php get_sidebar(); ?>
                <!-- Column right / End -->
            </div>
            <?php get_sidebar('bottom'); ?>
        </div>
    </section>
    <!-- Page Blog / End -->

<?php
get_footer();
