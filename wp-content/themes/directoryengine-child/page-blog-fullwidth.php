<?php
/**
 * Template Name: BlogPage FULL WIDTH
*/
get_header();
?>
<!-- Breadcrumb List users -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb List users / End -->
<section id="blog-page">
    <div class="container">
        <div class="row">
            <!-- Column left -->
            <div class="col-md-12 col-xs-12">
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
            
        </div>
    </div>
</section>       
<?php
get_footer();