<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Write_Blog
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : ?>

			<?php

			/* Start the Loop */
            $class = 'write-blog-posts-lists';

            /*Check for masonry settings*/
            $enable_masonry_post_archive = write_blog_get_option( 'enable_masonry_post_archive', true );
            if( $enable_masonry_post_archive ){
                $class = 'masonry-grid masonry-col';
            }
            /**/

            echo '<div class="'.esc_attr($class).'">';

			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;
            echo '</div>';

            /**
             * Hook - write_blog_posts_navigation.
             *
             * @hooked: write_blog_display_posts_navigation - 10
             */
            do_action( 'write_blog_posts_navigation' );

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
$page_layout = write_blog_get_page_layout();
if( 'no-sidebar' != $page_layout ){
    get_sidebar();
}
get_footer();
