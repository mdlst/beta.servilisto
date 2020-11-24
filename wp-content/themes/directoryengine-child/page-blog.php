<?php
/**
 * Template Name: Blog Page
 * version 1.0
 * @author: enginethemes
 */
get_header();
?>
<!-- Breadcrumb List users -->
<div class="section-detail-wrapper col-md-9 col-xs-12readcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb List users / End -->
<section id="blog-page">



    <div class="container">
        <div class="row">
            <div class="col-md-9 col-xs-12">
                <h1 class="top-title-post-place">Blog de trabajo y servicios a domicilio Servilisto</h1>
                
                <?php
                    global $post, $wp_query, $user_ID;
                    $paged = get_query_var( 'paged', 1 );
                    $args = array(
                        'post_type' => 'post',
                        'post_status'=> 'publish',
                        'orderby'=>'date',
                        'order' => 'DESC',
                        'page' => $paged
                        );
                    $post = query_posts( $args );
                    get_template_part( 'template/publish' , 'blog');
                    wp_reset_query();
                ?>
            </div>

            <!-- Column right -->
            <?php get_sidebar(); ?>

        </div>
        <?php get_sidebar('bottom'); ?>

    </div>
</section>       
<?php
get_footer();