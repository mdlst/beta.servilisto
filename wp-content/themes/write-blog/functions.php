<?php
/**
 * Write Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Write_Blog
 */

if ( ! function_exists( 'write_blog_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function write_blog_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Write Blog, use a find and replace
		 * to change 'write-blog' to the name of your theme in all the template files.
		 */
        load_theme_textdomain( 'write-blog', get_template_directory() . '/languages' );
        

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'write-blog' ),
			'social-nav' => esc_html__( 'Social Nav', 'write-blog' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'write_blog_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

         /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support( 'post-formats', array(
            'image',
            'video',
            'quote',
            'gallery',
            'audio',
        ) );

        add_image_size( 'write-blog-medium-img', 480 );

    }
endif;
add_action( 'after_setup_theme', 'write_blog_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function write_blog_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'write_blog_content_width', 640 );
}
add_action( 'after_setup_theme', 'write_blog_content_width', 0 );

/**
 * function for google fonts
 */
if (!function_exists('write_blog_fonts_url')) :

    /**
     * Return fonts URL.
     *
     * @since 1.0.0
     * @return string Fonts URL.
     */
    function write_blog_fonts_url(){
        
        $fonts_url = '';
        $fonts = array();

        $write_blog_primary_font   = write_blog_get_option('primary_font',true);
        $write_blog_secondary_font = write_blog_get_option('secondary_font',true);

        $write_blog_fonts   = array();
        $write_blog_fonts[] = $write_blog_primary_font;
        $write_blog_fonts[] = $write_blog_secondary_font;

        for ($i = 0; $i < count($write_blog_fonts); $i++) {

            if ('off' !== sprintf(_x('on', '%s font: on or off', 'write-blog'), $write_blog_fonts[$i])) {
                $fonts[] = $write_blog_fonts[$i];
            }

        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urldecode(implode('|', $fonts)),
            ), 'https://fonts.googleapis.com/css');
        }

        return $fonts_url;
    }
endif;

/**
 * Enqueue scripts and styles.
 *
 * @since Write Blog 1.0
 *
 */
function write_blog_scripts() {

    $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    wp_enqueue_style('ionicons', get_template_directory_uri() . '/assets/lib/ionicons/css/ionicons' . $min . '.css');
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/lib/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style('magnific-popup', get_template_directory_uri().'/assets/lib/magnific-popup/magnific-popup.css');
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/lib/slick/css/slick' . $min . '.css');
    wp_enqueue_style( 'wp-mediaelement' );
    wp_enqueue_style( 'write-blog-style', get_stylesheet_uri() );
    wp_add_inline_style('write-blog-style', write_blog_inline_css());
    $fonts_url = write_blog_fonts_url();
    if (!empty($fonts_url)) {
        wp_enqueue_style('write-blog-google-fonts', $fonts_url, array(), null);
    }

    wp_enqueue_script( 'write-blog-skip-link-focus-fix', get_template_directory_uri() . '/assets/thememattic/js/skip-link-focus-fix.js', array(), '20151215', true );
    wp_enqueue_script('jquery-bootstrap', get_template_directory_uri() . '/assets/lib/bootstrap/js/bootstrap' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/assets/lib/slick/js/slick' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('jquery-magnific-popup', get_template_directory_uri().'/assets/lib/magnific-popup/jquery.magnific-popup'.$min.'.js', array('jquery'), '', true);
    wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script('masonry');
    wp_enqueue_script('theiaStickySidebar', get_template_directory_uri() . '/assets/lib/theiaStickySidebar/theia-sticky-sidebar.min.js', array('jquery'), '', true);


    $args = write_blog_get_localized_variables();

	wp_enqueue_script('script', get_template_directory_uri() . '/assets/thememattic/js/script.js', array( 'jquery', 'wp-mediaelement' ), '', true);
    wp_localize_script( 'script', 'writeBlogVal', $args );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'write_blog_scripts' );

/**
 * Enqueue admin scripts and styles.
 *
 * @since Write Blog 1.0
 */
function write_blog_admin_scripts($hook){
    if ('widgets.php' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('write_blog-widgets-js', get_template_directory_uri() . '/assets/thememattic/js/widgets.js', array('jquery','wp-util'), '1.0.0', true);
        wp_enqueue_style('write_blog-widgets-css', get_template_directory_uri() . '/assets/thememattic/css/widgets.css');

    }
}
add_action('admin_enqueue_scripts', 'write_blog_admin_scripts');

/**
 * Print admin widget styles.
 *
 * @since Write Blog 1.0
 */
function write_blog_widget_styles(){
   ?>
    <style>
        .me-widefat{
            border-spacing: 0;
            width: 90%;
            clear: both;
            margin: 5px 0;
        }
        .me-remove-youtube-url{
            color: red;
            cursor: pointer;
        }
    </style>
<?php
}
add_action( "admin_print_styles-widgets.php", 'write_blog_widget_styles' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Write Blog 1.0
 *
 */
function write_blog_post_nav_background() {
    if ( ! is_single() ) {
        return;
    }

    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );
    $css      = '';

    if ( is_attachment() && 'attachment' == $previous->post_type ) {
        return;
    }

    if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
        $prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
        $css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
    }

    if ( $next && has_post_thumbnail( $next->ID ) ) {
        $nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
        $css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); border-top: 0; }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
    }

    wp_add_inline_style( 'write-blog-style', $css );
}
add_action( 'wp_enqueue_scripts', 'write_blog_post_nav_background' );

function write_blog_get_customizer_value(){
    global $write_blog;
    $write_blog = write_blog_get_options();
}
add_action('init','write_blog_get_customizer_value');

/**
 * Load all required files.
 */
require get_template_directory() . '/inc/init.php';
