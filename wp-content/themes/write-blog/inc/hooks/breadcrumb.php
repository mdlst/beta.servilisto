<?php 

if ( ! function_exists( 'write_blog_breadcrumb_content' ) ) :

	/**
	 * Display breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function write_blog_breadcrumb_content() {

		// Bail if Breadcrumb disabled.
		$breadcrumb_type = write_blog_get_option( 'breadcrumb_type', true );
		// Render breadcrumb.
		switch ( $breadcrumb_type ) {
			case 'simple':
				write_blog_get_breadcrumb();
			break;
			case 'advanced':
				if ( function_exists( 'bcn_display' ) ) {
					bcn_display();
				}
			break;
			default:
			break;
		}
		return;
	}

endif;

add_action( 'write_blog_display_breadcrumb', 'write_blog_breadcrumb_content' );
