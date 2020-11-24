<?php
/**
 * Write Blog Theme Customizer
 *
 * @package Write_Blog
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function write_blog_customize_register( $wp_customize ) {

    /*Load custom controls for customizer.*/
    require get_template_directory() . '/inc/customizer/controls.php';

    /*Load sanitization functions.*/
    require get_template_directory() . '/inc/customizer/sanitize.php';

    /*Load customize callback.*/
    require get_template_directory() . '/inc/customizer/callback.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'write_blog_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'write_blog_customize_partial_blogdescription',
		) );
	}

    /*Load customizer options.*/
    require get_template_directory() . '/inc/customizer/options.php';

    // Register custom section types.
    $wp_customize->register_section_type( 'Write_Blog_Customize_Section_Upsell' );

    // Register sections.
    $wp_customize->add_section(
    	new Write_Blog_Customize_Section_Upsell(
    		$wp_customize,
    		'theme_upsell',
    		array(
    			'title'    => esc_html__( 'Write Blog Pro', 'write-blog' ),
    			'pro_text' => esc_html__( 'Upgrade To Pro', 'write-blog' ),
    			'pro_url'  => 'http://www.thememattic.com/theme/write-blog-pro/',
    			'priority'  => 1,
    		)
    	)
    );
}
add_action( 'customize_register', 'write_blog_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function write_blog_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function write_blog_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function write_blog_customize_preview_js() {
	wp_enqueue_script(
	    'write-blog-themecustomizer',
        get_template_directory_uri() . '/assets/thememattic/js/customizer.js',
        array( 'jquery','customize-preview' ),
        '',
        true
    );
}
add_action( 'customize_preview_init', 'write_blog_customize_preview_js' );

/**
 * Customizer control scripts and styles.
 *
 * @since 1.0.0
 */
function write_blog_customizer_control_scripts(){
    wp_enqueue_style('write-blog-customizer-css', get_template_directory_uri() . '/assets/thememattic/css/admin.css');
    wp_enqueue_script( 'write-blog-customize-controls', get_template_directory_uri() . '/assets/thememattic/js/customizer-admin.js', array( 'customize-controls' ) );
}
add_action('customize_controls_enqueue_scripts', 'write_blog_customizer_control_scripts', 0);