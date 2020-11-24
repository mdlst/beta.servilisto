<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Write_Blog
 */

get_header(); ?>

    <section class="section-block section-latest-block">
        <div class="container-fluid">

            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                        <?php
                        if (have_posts()) :
                            $class = 'write-blog-posts-lists';

                            /*Check for masonry settings*/
                            $enable_masonry_post_archive = write_blog_get_option( 'enable_masonry_post_archive', true );
                            if( $enable_masonry_post_archive ){
                                $class = 'masonry-grid masonry-col';
                            }
                            /**/

                            echo '<div class="'.esc_attr($class).'">';
                                /* Start the Loop */
                                while (have_posts()) : the_post();
                                    /*
                                     * Include the Post-Format-specific template for the content.
                                     * If you want to override this in a child theme, then include a file
                                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                     */
                                    get_template_part('template-parts/content', get_post_format());
                                endwhile;
                            echo '</div>';

                            /**
                             * Hook - write_blog_posts_navigation.
                             *
                             * @hooked: write_blog_display_posts_navigation - 10
                             */
                            do_action( 'write_blog_posts_navigation' );

                        else :

                            get_template_part('template-parts/content', 'none');
                        endif; ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php
            $page_layout = write_blog_get_page_layout();
            if( 'no-sidebar' != $page_layout ){
                get_sidebar();
            }
            ?>
        </div>
    </section>
<?php
get_footer();