<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Write_Blog
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function write_blog_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    $site_layout = write_blog_get_option('site_layout',true);

	if( 'fullwidth' == $site_layout){
        $classes[] = 'thememattic-full-layout';
    }
    if( 'boxed' == $site_layout ){
        $classes[] = 'thememattic-boxed-layout';
    }

    $page_layout = write_blog_get_page_layout();
    $classes[] = esc_attr($page_layout);

	return $classes;
}
add_filter( 'body_class', 'write_blog_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function write_blog_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'write_blog_pingback_header' );