<?php
/**
 * Link update Dicretory Engine Theme
 */
define("ET_UPDATE_PATH", "http://update.enginethemes.com/?do=product-update");

/**
 * Directory Engine Theme Version
 */
define("ET_VERSION", '2.1.6');

if (!defined('ET_URL'))
    /**
     * Website EngineTheme
     */
    define('ET_URL', 'http://www.enginethemes.com/');

if (!defined('ET_CONTENT_DIR'))
    /**
     * Path to folder et-content
     */
    define('ET_CONTENT_DIR', WP_CONTENT_DIR . '/et-content/');

/**
 * Path to folder Template
 */
define('TEMPLATEURL', get_template_directory_uri());

$theme_name = 'directoryengine';

/**
 * Theme Name
 */
define('THEME_NAME', $theme_name);
/**
 * Domain name
 */
define('ET_DOMAIN', 'enginetheme');
/**
 * Path to folder mobile in theme DirectoryEngine
 */
define('MOBILE_PATH', TEMPLATEPATH . '/mobile/');

if (!defined('THEME_CONTENT_DIR '))
    /**
     * Path to folder wp-content/et-content/{theme_name}
     */
    define('THEME_CONTENT_DIR', WP_CONTENT_DIR . '/et-content' . '/' . $theme_name);
if (!defined('THEME_CONTENT_URL'))
    /**
     * Path to folder wp-content/et-content/{theme_name}
     */
    define('THEME_CONTENT_URL', content_url() . '/et-content' . '/' . $theme_name);

// theme language path
if (!defined('THEME_LANGUAGE_PATH'))
    /**
     * Path to folder language of theme
     */
    define('THEME_LANGUAGE_PATH', THEME_CONTENT_DIR . '/lang/');

if (!defined('ET_LANGUAGE_PATH'))
    /**
     * Path to folder language of theme
     */
    define('ET_LANGUAGE_PATH', THEME_CONTENT_DIR . '/lang');

if (!defined('ET_CSS_PATH'))
    /**
     * Path to folder style of theme
     */
    define('ET_CSS_PATH', THEME_CONTENT_DIR . '/css');

if (!defined('USE_SOCIAL'))
    /**
     * Enable function Social
     */
    define('USE_SOCIAL', 1);

require_once TEMPLATEPATH . '/includes/index.php';
require_once TEMPLATEPATH . '/customizer/customizer.php';

if (!class_exists('AE_Base')) return;

//require_once TEMPLATEPATH . '/mobile/functions.php';

/**
 * Class ET_DirectoryEngine
 */
class ET_DirectoryEngine extends AE_Base
{
    /**
     * @var $instance
     */
    static $instance;


    /**
     * return class $instance
     */
    public static function get_instance()
    {
        if (self::$instance == null) {

            self::$instance = new ET_DirectoryEngine();
        }
        return self::$instance;
    }

    /**
     * construct ET_DirectoryEngine
     */
    function __construct()
    {

        /**
         * add image size
         */
        add_image_size('big_post_thumbnail', 270, 280, true);
        add_image_size('medium_post_thumbnail', 200, 175, true);
        add_image_size('small_post_thumbnail', 70, 65, true);
        add_image_size('review_post_thumbnail', 255, 160, true);
        add_image_size('place_cover_preview', 540, 200, false);

        $this->version = ET_VERSION;

        // init theme setting
        $this->add_action('init', 'de_init');

        /**
         * add class to body
         */
        $this->add_filter('body_class', 'body_class');

        /**
         * filter post thumnail image, if not set use no image
         */
        // $this->add_filter('post_thumbnail_html', 'post_thumbnail_html', 10, 5);

        /**
         * enqueue front end scripts
         */
        $this->add_action('wp_enqueue_scripts', 'on_add_scripts');
        $this->add_action('wp_enqueue_scripts', 'on_deregister_script');
        /**
         * enqueue front end styles
         */
        $this->add_action('wp_print_styles', 'on_add_styles', 10);

        /**
         * init js view
         */
        $this->add_action('wp_footer', 'footer_script', 100);

        /**
         * init js view
         */
        $this->add_action('wp_head', 'wp_head');
        /**
         * Filter user data
         * @since version 1.8.3
         */
        $this->add_filter('ae_pre_insert_user', 'ae_check_role_user');
        /**
         * add query vars
         */
        $this->add_filter('query_vars', 'add_query_vars');

        /**
         * add action admin menu prevent seller enter admin area
         */
        $this->add_action('admin_menu', 'redirect_seller');
        $this->add_action('login_init', 'redirect_login');
        $this->add_action('admin_print_styles', 'print_styles');

        $this->add_filter('excerpt_length', 'custom_excerpt_length');
        if (!isset($_GET['vc_editable'])) {

            // Filter to Replace default css class for vc_row shortcode and vc_column
            $this->add_filter('vc_shortcodes_css_class', 'vc_row_and_vc_column', 10, 2);
        }
        /**
         * add comment type filter dropdow
         */
        $this->add_filter('admin_comment_types_dropdown', 'admin_comment_types_dropdown');

        /**
         * Add action to check view count
         */
        $this->add_action("before_single_place", "may_increase_view_count");
        // custom template feed
        // remove_all_actions( 'do_feed_rss2' );
        // $this->add_action('do_feed_rss2','custom_template_feed');
        $this->add_action('template_redirect', 'template_redirect');

        // catch the action after insert sample data and update front page option
        $this->add_action('ae_insert_sample_data_success', 'ee_insert_sample_data');

        // synce place areas
        $this->add_ajax('ae-sync-areas', 'areas_sync');

        /**
         * init place action object
         */
        new DE_PlaceAction();
        new DE_EventAction();
        new DE_PicturesAction();

        /**
         * init areas action object
         */
        new DE_LocationAction();

        /**
         * init place meta post
         */
        new AE_PostMeta();
        /**
         * init payment process
         */
        new DE_Payment();
        /**
         * user front end control  : edit profile, update avatar
         */
        new AE_User_Front_Actions (new AE_Users());
        /**
         * hook to control comment post in place
         */
        new DE_Multi_Review();
        // add schedule cron time
        $disable_plan = ae_get_option('disable_plan');
        if (!$disable_plan) {
            new AE_Schedule('place');
            new AE_Schedule('event');
        }

        update_option('revslider-valid-notice', 'false');

    }

    /**
     * hook to init and initialize theme settings
     */
    function de_init()
    {

        // disable admin bar if user can not manage options
        if (!current_user_can('manage_options') || et_load_mobile()) :
            show_admin_bar(false);
        endif;
        // register menu
        register_nav_menu('et_header', __("Header menu", ET_DOMAIN));
        register_nav_menu('et_mobile_header', __("Mobile Header menu", ET_DOMAIN));

        register_nav_menu('et_header_top', __("Header top menu", ET_DOMAIN));
        register_nav_menu('et_footer', __("Footer menu", ET_DOMAIN));

        /**
         * override author link
         */
        global $wp_rewrite;
        $wp_rewrite->author_base = ae_get_option('author_base', 'author');
        $wp_rewrite->author_structure = '/' . $wp_rewrite->author_base . '/%author%';
        /**
         * add review to end point
         */
        //$review_url =   ae_get_option('author_review_url', 'reviews');
        // add_rewrite_rule( $wp_rewrite->author_base.'/([^/]+)/$review_url/', 'index.php?author_name=$matches[1]&$review_url=1', 'top' );
        // add_rewrite_rule( $wp_rewrite->author_base.'/([^/]+)/$review_url/page/?([0-9]{1,})/?$', 'index.php?author_name=$matches[1]&$review_url=1&paged=$matches[2]', 'top' );
        //add_rewrite_endpoint( $review_url, EP_AUTHORS | EP_PAGES );

        add_rewrite_rule($wp_rewrite->author_base . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?author_name=$matches[1]&paged=$matches[2]', 'top');
        add_rewrite_rule($wp_rewrite->author_base . '/([^/]*)/([^/]*)/page/([0-9]+)', 'index.php?author_name=$matches[1]&author_tab=$matches[2]&paged=$matches[3]', 'top');
        add_rewrite_rule($wp_rewrite->author_base . '/([^/]*)/([^/]*)', 'index.php?author_name=$matches[1]&author_tab=$matches[2]', 'top');

        $rules = get_option('rewrite_rules');

        if (!isset($rules[$wp_rewrite->author_base . '/([^/]*)/([^/]*)/page/([0-9]+)'])) {
            $wp_rewrite->flush_rules();
        }
        if (function_exists('init_social_login')) {
            init_social_login();
        }

        /**
         * Disable the emoji's
         */
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    /**
     * Deregister script
     */
    function on_deregister_script()
    {
        wp_deregister_script('word-count');
        wp_dequeue_script('admin-bar');
        wp_deregister_script('jquery-ui-menu');
    }

    /**
     * filter body class
     * @param $body_class
     * @return array
     */
    function body_class($body_class)
    {
        if (et_load_tablet()) {
            $body_class[] = 'touch';
        } else {
            $body_class[] = 'no-touch';
        }
        return $body_class;
    }

    /**
     * Enqueue scripts for the front end.
     *
     * @since Directory Engine 1.0
     *
     * @return void
     */
    function on_add_scripts()
    {
        $this->add_existed_script('jquery');

        $this->add_existed_script('backbone');
        $this->add_existed_script('underscore');
        $this->add_existed_script('plupload');

        // jquery auto complete for search users
        $this->add_existed_script('jquery-ui-autocomplete');
        // add script validator
        $this->add_existed_script('jquery-validator');

        /**
         * map api
         */
        $this->add_existed_script('et-googlemap-api');
        $this->add_existed_script('gmap');

        $this->add_existed_script('bootstrap');

        /**
         * bootstrap slider for search form
         */
        $this->add_existed_script('slider-bt');

        /**
         * slider and rating
         */
        $this->add_script('magnific-raty', get_template_directory_uri() . '/js/jquery.magnific-raty.js', array('jquery'), true);
        $this->add_script('mCustomScrollbar', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.min.js', array('jquery'), true);
        $this->add_script('lazy-load', get_template_directory_uri() . '/js/jquery.lazyload.js', array('jquery'), true);
        $this->add_script('jquery-timepicker', get_template_directory_uri() . '/js/jquery.timepicker.min.js', array('jquery'), true);
        $this->add_script('jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.js', array('jquery'), true);
        /**
         * directory script function BlockPost and Block Review Control
         */
        $this->add_script('functions', get_template_directory_uri() . '/js/functions.js', array(
            'jquery',
            'backbone',
            'underscore',
            'appengine'
        ), ET_VERSION, true);

        /*
         * Adds JavaScript to pages with the comment form to support
         * sites with threaded comments (when in use).
        */
        if (is_singular() && comments_open() && get_option('thread_comments')) $this->add_existed_script('comment-reply');

        wp_localize_script('magnific-raty', 'raty', array(
            'hint' => array(
                __('bad', ET_DOMAIN),
                __('poor', ET_DOMAIN),
                __('nice', ET_DOMAIN),
                __('good', ET_DOMAIN),
                __('gorgeous', ET_DOMAIN)
            )
        ));

        if (is_page("reiniciar-contrasena")) {
            $this->add_script('reset-pass', get_template_directory_uri() . '/js/reset-pass.js', array('appengine'), ET_VERSION);
        }


        // Rate
        $this->add_existed_script('marker');

        /**
         * control menu
         */
        $this->add_script('gnmenu', get_template_directory_uri() . '/js/gnmenu.js', array(), true);
        /**
         * front end control
         */
       $this->add_script('front', get_template_directory_uri() . '/js/front.js', array(
            'jquery',
            'backbone',
            'underscore',
            'functions',
            'magnific-raty',
            'gnmenu'
        ), ET_VERSION,true);

        wp_localize_script('front', 'de_front', de_static_texts());

        /* 15_10_2016 Id : 2 S*/
        if (is_page_template('page-post-place.php') || is_page_template('page-post-place-fullwidth.php')) {
            /* 15_10_2016 Id : 2 E*/
            $this->add_script('submit-post', get_template_directory_uri() . '/js/post-place.js', array(
                'appengine',
                'marionette'
            ), true);
        }


        // Adds Masonry to handle vertical alignment of footer widgets.
        if (is_active_sidebar('de-footer-1')) $this->add_existed_script('jquery-masonry');

        // wp_enqueue_script('classie', get_template_directory_uri() . '/js/classie.js', array() , true);

     /*   $this->add_script('index', get_template_directory_uri() . '/js/index.js', array(
            'appengine',
            'marionette'
        ), true);*/


        if (is_author()) {
            $this->add_script('author', get_template_directory_uri() . '/js/author.js', array(
                'jquery',
                'backbone',
                'underscore',
                'appengine',
                'marionette',
                'functions',
                'front'
            ));
        }

        if (is_page_template('page-profile.php')) {
            $this->add_script('author', get_template_directory_uri() . '/js/profile.js', array(
                'jquery',
                'backbone',
                'underscore',
                'appengine',
                'marionette',
                'functions',
                'front'
            ));
        }

        // js for list user page
        if (is_page_template('page-list-user.php')) {
            $this->add_script('list-users', get_template_directory_uri() . '/js/list-users.js', array(
                'jquery',
                'backbone',
                'underscore',
                'appengine'
            ), true);
        }

        // $this->localize_script();
    }

    function print_styles()
    {
        $this->add_style('admin-font-icon', get_template_directory_uri() . '/css/font-awesome.min.css', array(), ET_VERSION);
    }

    /**
     * Enqueue styles for the front end.
     *
     * @since Directory Engine 1.0
     *
     * @return void
     */
    function on_add_styles()
    {
        // Loads the Internet Explorer specific stylesheet.
        $this->add_existed_style('bootstrap');
        // Loads our main stylesheet.
        $this->add_style('font-icon', get_template_directory_uri() . '/css/font-awesome.min.css', array(), ET_VERSION);
        // Add style css for mobile version.
        if (et_load_mobile()) {
            $this->add_style('custom', get_template_directory_uri() . '/mobile/css/main.css', array(
                'bootstrap'
            ), ET_VERSION);
            $this->add_style('mobile', get_template_directory_uri() . '/mobile/css/mobile.css', array(
                'bootstrap', 'custom'
            ), ET_VERSION);
            if (!is_child_theme()) {
                $this->add_style('style-mobile', get_template_directory_uri() . '/mobile/style.css', array(
                    'bootstrap'
                ), ET_VERSION);
            }
            return;
        }

        $this->add_style('mCustomScrollbar', get_template_directory_uri() . '/css/jquery.mCustomScrollbar.css', array(), ET_VERSION);
        /**
         * theme css
         */
        $this->add_style('custom', get_template_directory_uri() . '/css/custom.css', array(
            'bootstrap'
        ), ET_VERSION);
        $this->add_style('customized', get_template_directory_uri() . '/css/customized.css', array(
            'bootstrap', 'custom'
        ), ET_VERSION);



    }

    /**
     * footer script to init all view
     */
    function footer_script()
    {
        global $user_ID, $current_user;
        // render map template
        $this->map_template();

        $disable_plan = ae_get_option('disable_plan');
        $limit_free_plan = ae_get_option('limit_free_plan');
        $ae_user = AE_Users::get_instance();

        do_action('de_before_render_script');

        if ($user_ID) {
            echo '<script type="data/json"  id="user_id">' . json_encode(array('id' => $user_ID, 'ID' => $user_ID)) . '</script>';
        }
        ?>
        <script type="text/javascript" id="frontend_scripts">
            (function ($, Views, Models, AE) {
                $(document).ready(function () {
                    var post;
                    var currentUser;
                    // Layy load Image
                    if ($('img.lazy').length > 0) {
                        $("img.lazy").lazyload({
                            effect: "fadeIn"
                        });
                    }
                    // init post
                    if ($('#place_id').length > 0) {
                        post = new Models.Post(JSON.parse($('#place_id').html()));
                        post.fetch();
                    }
                    // init post
                    if ($('#user_id').length > 0) {
                        currentUser = new Models.User(JSON.parse($('#user_id').html()));
                        currentUser.fetch();
                    } else {
                        currentUser = new Models.User();
                    }
                    //create new front view
                    if (typeof Views.Front !== 'undefined') {
                        AE.App = new Views.Front({user: currentUser});
                    }
                    // create new author view
                    <?php if(is_author() || is_page_template('page-profile.php')){ ?>
                    if (typeof Views.Author !== 'undefined') {
                        AE.AuthorView = new Views.Author({model: currentUser});
                    }
                    <?php } ?>
                    // create post form view
                    if (typeof Views.PostForm !== 'undefined') {
                        <?php
                            $options = array(
                                'use_plan'        => $disable_plan,
                                'user_login'      => $user_ID,
                                'free_plan_used'  => AE_Package::get_used_free_plan($user_ID),
                                'limit_free_plan' => $limit_free_plan,
                                'el'              => '#post-place',
                                'step' => (ae_get_option('disable_plan', false) ? 2 : 4)
                            );

                            echo "
                            var options = ". json_encode($options) ."
                            AE.PostFormView = new Views.PostForm(options);
                            ";
                        ?>
                    }

                    <?php if(is_singular('place')){ ?>
                    if (typeof Views.SinglePost !== 'undefined') {
                        AE.Single = new Views.SinglePost({model: post});
                    }
                    <?php } ?>

                    if (typeof Views.Map !== 'undefined') {
                        AE.MapView = new Views.Map({
                            el: $('body'),
                            latitude: ae_globals.map_center.latitude,
                            longitude: ae_globals.map_center.longitude,
                            model: post
                        });
                    }
                });

            })(jQuery, AE.Views, AE.Models, window.AE);


        </script>

        <?php

        do_action('de_after_render_script');

        // render category json data
        $cat = new AE_Category(array('taxonomy' => 'place_category'));
        $category = $cat->getAll(array('orderby' => 'parent', 'order' => 'ASC', 'hide_empty' => false, 'pad_counts' => false));
        echo '<script type="data/json" id="de-categories-data">' . json_encode($category) . '</script>';
    }

    /**
     * map item template
     */
    function map_template()
    {
        get_template_part('template-js/item', 'map');
    }

    /**
     * wp head render block ie 8.0 script && print style for category color
     */
    function wp_head()
    {

        // do not add style and script if is mobile
        if (et_load_mobile()) return;

        ae_block_ie('8.0', 'page-unsupported.php');


    }

    /**
     * add query var
     * @param $vars
     * @return
     */
    function add_query_vars($vars)
    {
        /*array_push($vars, 'paymentType'); Miguel */
        array_push($vars, 'tipo-pago');
        array_push($vars, 'author_tab');
        return $vars;
    }

    /**
     * redirect wp
     */
    function redirect_seller()
    {
        if (!(current_user_can('manage_options') || current_user_can('editor'))) {
            wp_redirect(home_url());
            exit;
        }
    }

    function redirect_login()
    {
        $flag = ae_get_option('login-init');
        if (!is_user_logged_in()) {
            $re = (de_check_register()) ? true : false;
            if ($flag && $re) {
                wp_redirect(home_url());
            }
        }
    }

    /**
     * filter excerpt length
     * @return int
     */
    function custom_excerpt_length()
    {
        return 20;
    }

    /*================  Replace default class of Visual Composer  ================ */
    /**
     * Replace default class of Visual Composer
     * @param $class_string
     * @param $tag
     * @return mixed
     */
    function vc_row_and_vc_column($class_string, $tag)
    {

        if ($tag == 'vc_column' || $tag == 'vc_column_inner' || $tag == "vc-element") {
            $class_string = str_replace('vc_col-sm', 'vc_col-md', $class_string);
        }

        return $class_string;
    }

    /**
     * hook to filter comment type dropdown and add review favorite to filter comment
     * @param Array $comment_types
     * @return Array result $comment_types
     */
    function admin_comment_types_dropdown($comment_types)
    {
        $comment_types['review'] = __("Review", ET_DOMAIN);
        $comment_types['favorite'] = __("Favorite", ET_DOMAIN);
        $comment_types['report'] = __("Report", ET_DOMAIN);
        return $comment_types;
    }


    function template_redirect()
    {
        if (is_page_template('page-reiniciar-contrasena.php') && is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        $re = (de_check_register()) ? true : false;
        if (!$re) {
            if (!is_user_logged_in() && is_page_template('page-post-place.php')) {
                wp_redirect(home_url());
                return;
            }
        }

    }


    /**
     * The home page after insert sample data id is 29
     * @since 1.8.9
     * @author Dakachi
     */
    function ee_insert_sample_data()
    {
        update_option('page_on_front', 29);
        update_option('show_on_front', 'page');
    }

    /**
     * Catch ajax function to load more areas
     * @since 2.0.1.1
     * @author Tuandq
     */
    function areas_sync()
    {
        $query = $_REQUEST['query'];
        $orderby = isset($query['orderby']) ? $query['orderby'] : '';
        $order = isset($query['order']) ? $query['order'] : '';
        $showposts = isset($query['showposts']) ? $query['showposts'] : '';
        $hide_empty = isset($query['hide_empty']) ? $query['hide_empty'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
        //$current_page = intval($page) + 1;
        $offset = ($page - 1) * $showposts;
        $args = array(
            'orderby' => $orderby,
            'order' => $order,
            'hide_empty' => $hide_empty,
            'number' => $showposts,
            'offset' => $offset,
        );
        $terms = get_terms('location', $args);
        if ($terms) {
            $data = array();
            foreach ($terms as $term) {
                $loca_link = get_term_link($term->term_id, 'location');
                $loca_image = et_taxonomy_image_url($term->term_id, 'medium', TRUE);
                $data[] = array(
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'link' => $loca_link,
                    'count' => $term->count,
                    'image' => $loca_image,
                    'show_count' => $query['show_count'],
                );
            }
        }
        $query_args[] = array(
            'orderby' => $orderby,
            'order' => $order,
            'showposts' => $showposts,
            'hide_empty' => $hide_empty,
            'page' => $page,

        );
        $content_query = '<script type="application/json" class="ae_query">' . json_encode($query_args) . '</script>';
        if (!empty($data))
            wp_send_json(array(
                'success' => true,
                'data' => $data,
                'query_noti' => $content_query,
            ));
        else
            wp_send_json(array(
                'success' => false,
                'query_noti' => $content_query,
            ));
    }

    /**
     * Filter the ORDERBY clause of the terms query.
     *
     * @since 1.0
     *
     * @param string $orderby ORDERBY clause of the terms query.
     * @param array $args An array of terms query arguments.
     * @param string|array $taxonomies A taxonomy or array of taxonomies.
     * @author Dakachi
     * @return string
     */
    public function order_terms($orderby, $args, $taxonomies)
    {
        $taxonomy = array_pop($taxonomies);

        // get taxonomies sort from option
        switch ($taxonomy) {
            case 'place_category':
                $_orderby = ae_get_option('place_category_order', 'name');
                break;

            case 'location':
                $_orderby = ae_get_option('location_order', 'name');
                break;

            default:
                return $orderby;
        }

        // $_orderby = strtolower( $args['orderby'] );
        if ('count' == $_orderby) {
            $orderby = 'tt.count';
        } else if ('name' == $_orderby) {
            $orderby = 't.name';
        } else if ('slug' == $_orderby) {
            $orderby = 't.slug';
        } else if ('term_group' == $_orderby) {
            $orderby = 't.term_group';
        } else if ('none' == $_orderby) {
            $orderby = '';
        } elseif (empty($_orderby) || 'id' == $_orderby) {
            $orderby = 't.term_id';
        } else {
            $orderby = 't.name';
        }

        return $orderby;
    }

    // /**
    //  * custom feed 2
    // */
    // function custom_template_feed( $for_comment ){
    //     $rss_template = get_template_directory() . '/feeds-rss2.php';
    //     if( get_query_var( 'post_type' ) == 'place' and file_exists( $rss_template ) )
    //         load_template( $rss_template );
    //     else
    //         do_feed_rss2( $for_comment ); // Call default function
    // }

    /**
     * add rewrite rule for author page
     * @param string|int $rules
     * @return string|int $rules
     */
    function add_rewrite_rules($rules)
    {
        global $wp_rewrite;
        $types = apply_filters('de_author_slugs', array('reviews', 'togos'));

        foreach ($types as $key => $value) {
            $newrules[$wp_rewrite->author_base . '/([^/]+)/' . $value . '/?$'] = 'index.php?author_name=$matches[1]&a_view=' . $value;
            $newrules[$wp_rewrite->author_base . '/([^/]+)/' . $value . '/page/?([0-9]{1,})/?$'] = 'index.php?author_name=$matches[1]&a_view=' . $value . '&paged=$matches[2]';
        }

        $rules = $newrules + $rules;
        return $rules;
    }

    /**
     * Check if need increase view count
     * @param null $post_id
     * @return bool|mixed $is_increased
     */
    function may_increase_view_count($post_id = null)
    {
        if (!is_singular('place')) {
            return;
        }

        if ($post_id == null) {
            global $post;
            $post_id = $post->ID;
        }

        $viewed_places = array();
        $is_increased = false;
        if (isset($_COOKIE['viewed_places'])) {
            $viewed_places = explode("|", $_COOKIE['viewed_places']);

            if (!in_array($post->ID, $viewed_places)) {
                /**
                 * User had not view this place
                 */
                $is_increased = $this->increase_view_count($post_id);
                $viewed_places[] = $post_id;
            }
        } else {
            /**
             * User had not view this place
             */
            $is_increased = $this->increase_view_count($post_id);
            $viewed_places[] = $post_id;
        }

        /**
         * update cookie
         */
        $viewed_places = implode("|", $viewed_places);
        $secure = ('https' === parse_url(site_url(), PHP_URL_SCHEME) && 'https' === parse_url(home_url(), PHP_URL_SCHEME));

        setcookie('viewed_places', $viewed_places, 0, COOKIEPATH, COOKIE_DOMAIN, $secure);
        if (SITECOOKIEPATH != COOKIEPATH) {
            setcookie('viewed_places', $viewed_places, 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure);
        }

        return $is_increased;
    }

    /**
     * Increase post view count
     *
     * @param $post_id
     * @return mixed
     */
    function increase_view_count($post_id)
    {
        $current_view_count = get_post_meta($post_id, 'view_count', true);
        if ($current_view_count) {
            $current_view_count = $current_view_count + 1;
        } else {
            $current_view_count = 1;
        }
        return update_post_meta($post_id, 'view_count', $current_view_count);
    }

    /**
     * Prevent user add other roles
     * @param array $user_data
     * @return array|WP_Error
     */
    function ae_check_role_user($user_data)
    {
        if (isset($user_data['role'])) {
            if (strtolower($user_data['role']) == 'administrator' || strtolower($user_data['role']) == 'editor') {
                return new WP_Error('user_role_error', __("You can't create an administrator account.", ET_DOMAIN));
                exit();
            }
        }
        if (isset($user_data['role']) && strtolower($user_data['role']) != 'author') {
            unset($user_data['role']);
        }
        return $user_data;
    }

}


global $et_directory;
add_action('after_setup_theme', 'de_setup_theme');
function de_setup_theme()
{
    global $et_directory;
    $et_directory = ET_DirectoryEngine::get_instance();
    if (is_admin() || current_user_can('manage_options')) {
        // init admin setup
        $admin = new ET_Admin();
    }

}

/**
 * add more ae_globals mobile zoom map number
 * @param array $vars
 * @return array $vars
 */
function add_more_global_variable($vars)
{

    global $user_ID;

    $vars['mobile_map_zoom'] = ae_get_option('mobile_map_zoom_default', 1);
    $vars['map_typestyle'] = ae_get_option('map_typestyle', 0);
    $start_of_week = get_option('start_of_week');
    $vars['start_of_week'] = $start_of_week;
    $vars['single_map_marker'] = ae_get_option('single_map_marker');
    $vars['current_possition_title'] = __('You are here', ET_DOMAIN);
    $vars['current_possition_img'] = get_template_directory_uri() . '/img/geolocation-icon.png';
    $vars['map_gohome_title'] = __('Show my location', ET_DOMAIN);
    $vars['geolocation_failed'] = "Su dispositivo no esta compartiendo su localización.<br> Asegúrese que tiene activada la geolocalización en su navegador.";
    $vars['geolocation_success'] = "Has sido geolocalizado correctamente";
    $vars['browser_supported'] = "Su navegador no soporta la localización";
    $vars['geo_direction'] = array(
        'driving' => __('DRIVING', ET_DOMAIN),
        'walking' => __('WALKING', ET_DOMAIN),
        'bicycling' => __('BICYCLING', ET_DOMAIN),
        'transit' => __('TRANSIT', ET_DOMAIN)
    );
    $vars['user_ID'] = $user_ID;
    $vars['units_of_measurement'] = ae_get_option('unit_measurement', 'mile');
    $vars['invalid_time'] = __('Please enter a valid time.', ET_DOMAIN);
    $vars['is_search'] = (is_search()) ? '1' : '0';
    $vars['is_tax'] = (is_tax('place_category')) ? '1' : '0';
    $vars['max_images_comment'] = ae_get_option('max_carousel_comment', 6);
    $vars['translate_select'] = __('Select All', ET_DOMAIN);
    $vars['translate_deselect'] = __('Deselect', ET_DOMAIN);
    return $vars;
}

add_filter('ae_globals', 'add_more_global_variable');

/**
 * Check search query follow wordpress
 * @param $q
 * @return string
 */
function parse_search($q)
{
    global $wpdb;

    $search = '';

    // added slashes screw with quote grouping when done early, so done later
    $q['s'] = stripslashes($q['s']);
    $q['s'] = str_replace(array("\r", "\n"), '', $q['s']);
    $q['search_terms_count'] = 1;
    if (!empty($q['sentence'])) {
        $q['search_terms'] = array($q['s']);
    } else {
        if (preg_match_all('/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $q['s'], $matches)) {
            $q['search_terms_count'] = count($matches[0]);
            $q['search_terms'] = parse_search_terms($matches[0]);
            // if the search string has only short terms or stopwords, or is 10+ terms long, match it as sentence
            if (empty($q['search_terms']) || count($q['search_terms']) > 9)
                $q['search_terms'] = array($q['s']);
        } else {
            $q['search_terms'] = array($q['s']);
        }
    }

    $n = !empty($q['exact']) ? '' : '%';
    $searchand = '';
    $q['search_orderby_title'] = array();
    foreach ($q['search_terms'] as $term) {
        if ($n) {
            $like = '%' . $wpdb->esc_like($term) . '%';
            $q['search_orderby_title'][] = $wpdb->prepare("P.post_title LIKE %s", $like);
        }

        $like = $n . $wpdb->esc_like($term) . $n;
        $search .= $wpdb->prepare("{$searchand}((P.post_title LIKE %s) OR (P.post_content LIKE %s))", $like, $like);
        $searchand = ' AND ';
    }

    if (!empty($search)) {
        $search = " AND ({$search}) ";
        if (!is_user_logged_in())
            $search .= " AND (P.post_password = '') ";
    }

    return $search;
}

/**
 * Strip whitespace (or other characters)
 * @param $terms
 * @return array
 */
function parse_search_terms($terms)
{
    $strtolower = function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower';
    $checked = array();

    $stopwords = get_search_stopwords();

    foreach ($terms as $term) {
        // keep before/after spaces when term is for exact match
        if (preg_match('/^".+"$/', $term))
            $term = trim($term, "\"'");
        else
            $term = trim($term, "\"' ");

        // Avoid single A-Z.
        if (!$term || (1 === strlen($term) && preg_match('/^[a-z]$/i', $term)))
            continue;

        if (in_array(call_user_func($strtolower, $term), $stopwords, true))
            continue;

        $checked[] = $term;
    }

    return $checked;
}

/**
 * @return array|mixed|void
 */
function get_search_stopwords()
{
    if (isset($stopwords))
        return stopwords;

    /* translators: This is a comma-separated list of very common words that should be excluded from a search,
     * like a, an, and the. These are usually called "stopwords". You should not simply translate these individual
     * words into your language. Instead, look for and provide commonly accepted stopwords in your language.
     */
    $words = explode(',', _x('about,an,are,as,at,be,by,com,for,from,how,in,is,it,of,on,or,that,the,this,to,was,what,when,where,who,will,with,www',
        'Comma-separated list of search stopwords in your language'));

    $stopwords = array();
    foreach ($words as $word) {
        $word = trim($word, "\r\n\t ");
        if ($word)
            $stopwords[] = $word;
    }

    /**
     * Filter stopwords used when parsing search terms.
     *
     * @since 3.7.0
     *
     * @param array $stopwords Stopwords.
     */
    $stopwords = apply_filters('wp_search_stopwords', $stopwords);
    return $stopwords;
}

add_filter('comment_text', 'filter_commnet_text');
/**
 * @param $comment
 * @return mixed
 */
function filter_commnet_text($comment)
{
    $comment = str_replace('\n', '<br/>', $comment);
    return $comment;
}

remove_filter('comment_text', 'make_clickable', 9);

// add_action('init', 'remove_prevent_user_access_backend');
// function remove_prevent_user_access_backend() {
//     global $et_directory;
//     remove_action('login_init', array($et_directory, 'redirect_login'));
// }


// echo wp_create_nonce('ad_carousels_et_uploader');

// class AE_LimitUserPost extends AE_Base
// {
//     function __construct() {
//         $this->post_type = 'place';
//         $this->add_filter('ae_pre_insert_place', 'limit_user_post', 11);
//     }

//     function limit_user_post($args) {
//         global $user_ID;
//         $count_post_by_user = $this->count_user_posts_by_type($user_ID, 'place');
//         if($count_post_by_user > 1 ) return new WP_Error('limit_user_post', __("You just can add one place.", ET_DOMAIN));
//         return $args;
//     }

//     function count_user_posts_by_type( $userid, $post_type = 'post' ) {
//         global $wpdb;

//         $where = get_posts_by_author_sql( $post_type, true, $userid );

//         $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

//         return apply_filters( 'get_usernumposts', $count, $userid );
//     }

// }
// new AE_LimitUserPost();
/* add place category to post type url */
// function de_place_category_post_link( $post_link, $id = 0 ){
//     $post = get_post($id);
//     if ( is_object( $post ) && $post->post_type == 'place' ){
//         $terms = wp_get_object_terms( $post->ID, 'place_category' );
//         if( $terms ){
//             return str_replace( '%place_category%' , $terms[0]->slug , $post_link );
//         }
//     }
//     return $post_link;
// }
// add_filter( 'post_type_link', 'de_place_category_post_link', 1, 2 );

/**
 * Modify the content in "inbox_mail"
 *
 * @since 1.8.4
 * @param $args
 * @return array $args after modify email content
 */
function de_modify_inbox_mail_content($args)
{
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'inbox') {
        if (isset($args['message']) && isset($_REQUEST['place_link']) && $_REQUEST['place_link'] != '') {
            $message = $args['message'];
            $message = str_ireplace('[place_link]', $_REQUEST['place_link'], $message);
            $args['message'] = $message;
        }
    }
    return $args;
}

add_filter('wp_mail', 'de_modify_inbox_mail_content');
/**
 * check to show top fullwidth sidebar
 *
 * @param void
 * @return bool true if allow to show map
 * @since 1.8.7
 * @author Tambh
 */
function is_show_top_fullwidth_map()
{
    $is_show = false;
    if (!(is_singular() && !is_page_template('page-front.php')) && !is_category() && (is_search() || !is_date()) && !is_author()) {
        $is_show = true;
    }
    return apply_filters('show_top_fullwidth_map', $is_show);
}


/**
 * Calculate differences between two dates with precise semantics. Based on PHPs DateTime::diff()
 *
 * @param int $start
 * @param int $end
 * @param int $adj
 * @param int $a
 * @param int $b
 * @param array $result
 * @return array $result
 */

function _date_range_limit($start, $end, $adj, $a, $b, $result)
{
    if ($result[$a] < $start) {
        $result[$b] -= intval(($start - $result[$a] - 1) / $adj) + 1;
        $result[$a] += $adj * intval(($start - $result[$a] - 1) / $adj + 1);
    }

    if ($result[$a] >= $end) {
        $result[$b] += intval($result[$a] / $adj);
        $result[$a] -= $adj * intval($result[$a] / $adj);
    }

    return $result;
}

/**
 * Date range limit days
 * @param array $base
 * @param array $result
 * @return mixed $result
 */
function _date_range_limit_days($base, $result)
{
    $days_in_month_leap = array(31, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $days_in_month = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    _date_range_limit(1, 13, 12, "m", "y", $base);

    $year = $base["y"];
    $month = $base["m"];

    if (!$result["invert"]) {
        while ($result["d"] < 0) {
            $month--;
            if ($month < 1) {
                $month += 12;
                $year--;
            }

            $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
            $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

            $result["d"] += $days;
            $result["m"]--;
        }
    } else {
        while ($result["d"] < 0) {
            $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
            $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

            $result["d"] += $days;
            $result["m"]--;

            $month++;
            if ($month > 12) {
                $month -= 12;
                $year++;
            }
        }
    }

    return $result;
}

/**
 * @param $base
 * @param $result
 * @return array
 */
function _date_normalize($base, $result)
{
    $result = _date_range_limit(0, 60, 60, "s", "i", $result);
    $result = _date_range_limit(0, 60, 60, "i", "h", $result);
    $result = _date_range_limit(0, 24, 24, "h", "d", $result);
    $result = _date_range_limit(0, 12, 12, "m", "y", $result);

    $result = _date_range_limit_days($base, $result);

    $result = _date_range_limit(0, 12, 12, "m", "y", $result);

    return $result;
}

/**
 * Accepts two unix timestamps.
 * @param int $one
 * @param int $two
 * @return string
 */
function _date_diff($one, $two)
{
    $invert = false;
    if ($one > $two) {
        list($one, $two) = array($two, $one);
        $invert = true;
    }

    $key = array("y", "m", "d", "h", "i", "s");
    $a = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $one))));
    $b = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $two))));

    $result = array();
    $result["y"] = $b["y"] - $a["y"];
    $result["m"] = $b["m"] - $a["m"];
    $result["d"] = $b["d"] - $a["d"];
    $result["h"] = $b["h"] - $a["h"];
    $result["i"] = $b["i"] - $a["i"];
    $result["s"] = $b["s"] - $a["s"];
    $result["invert"] = $invert ? 1 : 0;
    $result["days"] = intval(abs(($one - $two) / 86400));
    $result['week'] = intval(abs(($one - $two) / (60 * 60 * 24 * 7)));
    if ($invert) {
        _date_normalize($a, $result);
    } else {
        _date_normalize($b, $result);
    }

    $res = '';
    if ($result['week'] > 0) {
        // Show week
        if ($result['week'] > 1)
            $res = sprintf(__('%s weeks', ET_DOMAIN), $result['week']);
        else
            $res = sprintf(__('%s week', ET_DOMAIN), $result['week']);
    } elseif ($result['days'] > 0) {
        // Show day
        if ($result['days'] > 1)
            $res = sprintf(__('%s days', ET_DOMAIN), $result['days']);
        else
            $res = sprintf(__('%s day', ET_DOMAIN), $result['days']);
    } elseif ($result['h'] > 0) {
        // Show hour
        if ($result['h'] > 1)
            $res = sprintf(__('%s hours', ET_DOMAIN), $result['h']);
        else
            $res = sprintf(__('%s hour', ET_DOMAIN), $result['h']);
    }

    return $res;
}

/**
 * Create Unique Arrays using an md5 hash
 *
 * @param array $array
 * @param boolean $preserveKeys
 * @author ThanhTu
 * @since 1.0
 * @return array
 */
function arrayUnique($array, $preserveKeys = false)
{
    // Unique Array for return
    $arrayRewrite = array();
    // Array with the md5 hashes
    $arrayHashes = array();
    foreach ($array as $key => $item) {
        // Serialize the current element and create a md5 hash
        $hash = md5(serialize($item));
        // If the md5 didn't come up yet, add the element to
        // to arrayRewrite, otherwise drop it
        if (!isset($arrayHashes[$hash])) {
            // Save the current element hash
            $arrayHashes[$hash] = $hash;
            // Add element to the unique Array
            if ($preserveKeys) {
                $arrayRewrite[$key] = $item;
            } else {
                $arrayRewrite[] = $item;
            }
        }
    }
    return $arrayRewrite;
}

/**
 * function return HTML the days time
 * @param Array $serve_time
 * @param Array $array
 * @param Array $order_day
 * @param Array $days
 * @author ThanhTu
 * @since 1.0
 * @return String
 */
function loop_array_day_time($serve_time, $array, $order_day, $days)
{
    $term_arr = array();
    foreach ($serve_time as $key => $value) {
        if (strtoupper($value['open_time']) == strtoupper($array['open_time']) &&
            strtoupper($value['close_time']) == strtoupper($array['close_time'])
        ) {
            $term_arr[$key] = $key;
            $open_time = $value['open_time'];
            $close_time = $value['close_time'];
        }
    }
    $arr_inter = array_intersect($order_day, $term_arr);

    $array_translate = array();
    $num = array_keys($arr_inter);
    $num_first = $num[0];
    foreach ($arr_inter as $key => $value) {
        $continuous = ($key === $num_first) ? true : false;
        $array_translate[] = $days[$value];
        $num_first++;
    }

    // HTML display serve time
    $result = '<div class="god">';
    if (count($array_translate) == 1) {
        // only time
        if ($open_time != "" && $close_time != "") {
            $result .= '<span class="open-date">' . array_shift($array_translate) . '</span>:';
            $result .= '<span class="open-time">' . sprintf(__('%s to %s', ET_DOMAIN), $open_time, $close_time) . '</span>';
        } else {
            $result .= '<span class="open-date">' . array_shift($array_translate) . '</span>:';
            $result .= '<span class="open-time">' . __('None', ET_DOMAIN) . '</span>';
        }
    } else {
        if ($open_time != "" && $close_time != "") {
            // have open/close time
            if ($continuous) {
                // continuous day and same time
                $result .= '<span class="open-date">' . array_shift($array_translate) . ' - ' . array_pop($array_translate) . '</span>:';
                $result .= '<span class="open-time">' . sprintf(__('%s to %s', ET_DOMAIN), ucfirst($open_time), $close_time) . '</span>';
            } else {
                // not continuous day but same time
                $result .= '<span class="open-date">' . implode(", ", $array_translate) . '</span>:';
                $result .= '<span class="open-time">' . sprintf(__('%s to %s', ET_DOMAIN), $open_time, $close_time) . '</span>';
            }
        } else {
            if ($continuous) {
                // Not open/close time
                $result .= '<span class="open-date">' . array_shift($array_translate) . ' - ' . array_pop($array_translate) . '</span>:';
                $result .= '<span class="open-time">' . __('None', ET_DOMAIN) . '</span>';
            } else {
                // Not open/close time
                $result .= '<span class="open-date">' . implode(", ", $array_translate) . '</span>:';
                $result .= '<span class="open-time">' . __('None', ET_DOMAIN) . '</span>';
            }
        }
    }
    $result .= '</div>';
    return $result;
}

/**
 * Get Color Hexdec
 * @param $hex
 * @return string
 */
function get_color_hexdec($hex)
{
    // returns brightness value from 0 to 255
    // strip off any leading #
    $hex = str_replace('#', '', $hex);

    $c_r = hexdec(substr($hex, 0, 2));
    $c_g = hexdec(substr($hex, 2, 2));
    $c_b = hexdec(substr($hex, 4, 2));

    $brightness = (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;

    $color = ($brightness < 200) ? "#FFFFFF" : "#000000";
    return $color;
}

/**
 * @return void
 */
if (is_plugin_active('ae_fields/ae_fields.php')) {
    /**
     * render post custome meta field
     * @param object $post
     * @since 1.0
     * @author Dakachi
     */
    function et_render_custom_meta_theme($post)
    {
        global $ae_post_factory;
        $post_type = $post->post_type;

        global $ae_post_factory;
        $post_type = $post->post_type;

        $field_object = $ae_post_factory->get('ae_field');
        $custom_fields = $field_object->fetch('ae_field', array(
            'meta_query' => array(
                array(
                    'key' => 'field_for',
                    'value' => $post_type,
                    'compare' => '='
                )
            )
        ));

        if (!empty($custom_fields)) {
            foreach ($custom_fields as $key => $field) {
                $tax_list = '';
                $field_value = '';
                if ($field->field_for != $post_type || $field->type == 'tax') continue;
                // check field value is empty or not
                $field_value = et_get_field($field->post_title, $post->ID);
                // change nl to br
                if ($field->field_type == 'textarea') {
                    $field_value = wpautop($field_value);
                }
                if (!$field_value) continue;
                // render field
                echo '<li class="customfield"><div class="custom-field-wrapper ' . $field->post_title . '-wrapper" >';
                echo '<span class="ae-field-title ' . $field->post_title . '-title">' . $field->label . ':</span>' . $field_value;
                echo '</div></li>';
            }
        }
    }

    function et_render_custom_taxonomy_theme($post)
    {
        global $ae_post_factory;
        $post_type = $post->post_type;

        $field_object = $ae_post_factory->get('ae_field');
        $custom_fields = $field_object->fetch('ae_field', array(
            'meta_query' => array(
                array(
                    'key' => 'field_for',
                    'value' => $post_type,
                    'compare' => '='
                )
            )
        ));

        if (!empty($custom_fields)) {
            foreach ($custom_fields as $key => $field) {
                $tax_list = '';
                $field_value = '';
                if ($field->field_for != $post_type || $field->type != 'tax') continue;
                // check tax value is empty or notif ($field->type == 'tax') {
                $tax_list = get_the_taxonomy_list($field->post_title, $post);
                if ($tax_list == '') continue;

                // render field
                echo '<li class="customfield"><div class="custom-field-wrapper ' . $field->post_title . '-wrapper" >';
                echo '<span class="ae-field-title ' . $field->post_title . '-title">' . $field->label . ':</span>';

                echo $tax_list;

                echo '</div></li>';
            }
        }
    }
}

// Remove Cookie "notification" when logout

function de_remove_cookie_notification()
{
    if (isset($_COOKIE['view-notification'])) {
        unset($_COOKIE['view-notification']);
        setcookie('view-notification', '1');
        return true;
    } else {
        return false;
    }
}

add_action('wp_logout', 'de_remove_cookie_notification');

function de_set_cookie_notifition()
{
    setcookie('view-notification', '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
}

add_action('ae_login_user', 'de_set_cookie_notifition');

// Email notification only to the admin when have comment
function et_comment_moderation_recipients($emails, $comment_id)
{
    $emails = array(get_option('admin_email'));
    return $emails;
}

add_filter('comment_moderation_recipients', 'et_comment_moderation_recipients', 11, 2);
add_filter('comment_notification_recipients', 'et_comment_moderation_recipients', 11, 2);
