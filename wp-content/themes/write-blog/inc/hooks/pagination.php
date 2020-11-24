<?php 

if ( ! function_exists( 'write_blog_display_posts_navigation' ) ) :

	/**
	 * Display Pagination.
	 *
	 * @since 1.0.0
	 */
	function write_blog_display_posts_navigation() {

        $pagination_type = write_blog_get_option( 'pagination_type', true );
        switch ( $pagination_type ) {

            case 'default':
                the_posts_navigation();
                break;

            case 'numeric':
                the_posts_pagination();
                break;

            case 'infinite_scroll_load':
                write_blog_ajax_pagination('scroll');
                break;

            case 'button_click_load':
                write_blog_ajax_pagination('click');
                break;

            default:
                break;
        }
		return;
	}

endif;

add_action( 'write_blog_posts_navigation', 'write_blog_display_posts_navigation' );
