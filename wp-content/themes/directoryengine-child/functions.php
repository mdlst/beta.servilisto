<?php

require_once get_stylesheet_directory() . '/includes/views-backend-reservations-front.php';

/***********************************************************************************************************************/
function log_error_custom($message)
{
    $que = get_theme_root();
    $fp = fopen($que . "/error_custom.txt", "a+");

    $separador = " -> ";

    $date = date('d-m-Y h:i:s');

    $file1 = pathinfo(__FILE__, PATHINFO_DIRNAME);
    $file2 = pathinfo(__FILE__, PATHINFO_BASENAME);
    $file_total = $file1 . "\\" . $file2;

    fwrite($fp, $date . $separador . $file_total . $separador . $message . "\n");
    fclose($fp);
}

/***********************************************************************************************************************/

function bhww_ssl_template_redirect()
{
    if (!is_ssl() && !is_admin()) {
        if (0 === strpos($_SERVER['REQUEST_URI'], 'https')) {
            wp_redirect(preg_replace('|^https://|', 'https://', $_SERVER['REQUEST_URI']), 301);
            exit();
        } else {
            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
            exit();
        }
    }
}

add_action('template_redirect', 'bhww_ssl_template_redirect', 1);


/***********************************************************************************************************************/
function on_add_scripts()
{
    global $DOPBSP;

    /*wp_register_script('DOPBSP-js-backend', $DOPBSP->paths->url . 'assets/js/backend.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-backend-language', $DOPBSP->paths->url . 'assets/js/languages/backend-language.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-jquery-backend-calendar', $DOPBSP->paths->url . 'assets/js/jquery.dop.backend.BSPCalendar.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-backend-reservations', $DOPBSP->paths->url . 'assets/js/reservations/backend-reservations.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-backend-reservations-list', $DOPBSP->paths->url . 'assets/js/reservations/backend-reservations-list.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-backend-reservation', $DOPBSP->paths->url . 'assets/js/reservations/backend-reservation.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-backend-reservations-calendar', $DOPBSP->paths->url . 'assets/js/reservations/backend-reservations-calendar.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-jquery-backend-reservations-add', $DOPBSP->paths->url . 'assets/js/jquery.dop.backend.BSPReservationsAdd.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-jquery-backend-reservations-calendar', $DOPBSP->paths->url . 'assets/js/jquery.dop.backend.BSPReservationsCalendar.js', array('jquery'), false, true);
    wp_register_script('DOPBSP-js-backend-reservations-add', $DOPBSP->paths->url . 'assets/js/reservations/backend-reservations-add.js', array('jquery'), false, true);

    wp_register_script('DOP-js-prototypes', $DOPBSP->paths->url . 'libraries/js/dop-prototypes.js', array('jquery'));
    wp_register_script('DOPBSP-js-isotope', $DOPBSP->paths->url . 'libraries/js/isotope.pkgd.min.js', array('jquery'), false, true);

    wp_enqueue_script('DOPBSP-js-backend');
    wp_enqueue_script('DOPBSP-js-backend-language');
    wp_enqueue_script('DOPBSP-js-jquery-backend-calendar');
    wp_enqueue_script('DOPBSP-js-backend-reservations');
    wp_enqueue_script('DOPBSP-js-backend-reservations-list');
    wp_enqueue_script('DOPBSP-js-backend-reservation');
    wp_enqueue_script('DOPBSP-js-backend-reservations-calendar');
    wp_enqueue_script('DOPBSP-js-jquery-backend-reservations-add');
    wp_enqueue_script('DOPBSP-js-jquery-backend-reservations-calendar');
    wp_enqueue_script('DOPBSP-js-backend-reservations-add');

    wp_enqueue_script('DOP-js-prototypes');
    wp_enqueue_script('DOPBSP-js-isotope');*/


    wp_localize_script('magnific-raty', 'raty', array(
        'hint' => array(
            __('bad', ET_DOMAIN),
            __('poor', ET_DOMAIN),
            __('nice', ET_DOMAIN),
            __('good', ET_DOMAIN),
            __('gorgeous', ET_DOMAIN)
        )
    ));

    /* 15_10_2016 Id : 1 S*/
    if (is_page_template('page-post-place.php') || is_page_template('page-post-place-fullwidth.php')) {
        /* 15_10_2016 Id : 1 E*/
        add_script('signup-place2', get_stylesheet_directory_uri() . '/js/signup-place.js', array(
            'appengine',
            'functions',
            'front'
        ), true);
    }
    wp_localize_script('front', 'de_front', de_static_texts());


    add_script('my_functions', get_stylesheet_directory_uri() . '/js/my_functions.js', array('jquery'), ET_VERSION, true);
    add_script('jquery-ui', get_stylesheet_directory_uri() . '/js/jquery-ui.js', array('jquery'), ET_VERSION, true);

    if (is_singular('place')) {
        wp_deregister_script('single-place');
        add_script('single-place', get_stylesheet_directory_uri() . '/js/single-place.js', array('appengine', 'my_functions'), true);
    }
    add_script('ch_index', get_stylesheet_directory_uri() . '/js/index.js', array(
        'appengine',
        'my_functions',
        'marionette'
    ), true);

}

add_action('wp_enqueue_scripts', 'on_add_scripts', 999);

/***********************************************************************************************************************/
function footer_script()
{
    /* 15_10_2016 Id : 3 S*/
    if (is_page_template('page-post-place.php') || is_page_template('page-post-place-fullwidth.php')) {
        /* 15_10_2016 Id : 3 E*/
        ?>
        <script>
            (function ($, Views, Models, AE) {
                $(document).ready(function () {
                    var post;
                    var currentUser;
                    if (typeof Views.SinglePost !== 'undefined') {
                        AE.Single = new Views.SinglePost();
                    }
                })
            })(jQuery, AE.Views, AE.Models, window.AE);
        </script>

    <?php } ?>
    <!--Analytics Code-->
    <script>
        <?php $ua = (preg_match("/beta/", site_url())) ? "UA-96767550-2" : "UA-96767550-1" ?>
        <?php $ruta_analitycs_js = get_stylesheet_directory_uri() . "/js/analytics.js" ?>

        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '<?= $ruta_analitycs_js ?> ', 'ga');

        ga('create', '<?= $ua ?>', 'auto');
        ga('send', 'pageview');

    </script>


    <!-- Facebook Pixel Code -->
    <script>
        <?php $ruta_fb_js = get_stylesheet_directory_uri() . "/js/fbevents.js" ?>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', '<?= $ruta_fb_js ?>');
        fbq('init', '1218875451532250'); // Insert your pixel ID here.
        fbq('track', 'PageView');
    </script>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->

    <?php
}

add_action('wp_footer', 'footer_script', 100);

/***********************************************************************************************************************/

function on_add_styles()
{
    wp_deregister_style('custom');

    add_style('categorias-colores', get_stylesheet_directory_uri() . '/css/categorias-colores.css', array('bootstrap'), ET_VERSION); // ruta al child

    //directoryengine/style.css
    add_style('directoryengine-style', get_stylesheet_uri(), array('bootstrap'), ET_VERSION);

    if (et_load_mobile()) { //si es movil,cargamos l ahoja movil
        add_style('main_mobile', get_stylesheet_directory_uri() . '/mobile/css/movil.css', array('bootstrap'), ET_VERSION);
    }

}

add_action('wp_print_styles', 'on_add_styles', 11);
/***********************************************************************************************************************/
function add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
    $style = apply_filters('et_enqueue_style', array(
        'handle' => $handle,
        'src' => $src,
        'deps' => $deps,
        'ver' => $ver,
        'media' => $media
    ));

    wp_register_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
    wp_enqueue_style($style['handle']);

}

/***********************************************************************************************************************/
function add_script($handle, $src, $deps = array(), $ver = false, $in_footer = true)
{
    $scripts = apply_filters("et_enqueue_script", array(
        'handle' => $handle,
        'src' => $src,
        'deps' => $deps,
        'ver' => $ver,
        'in_footer' => $in_footer
    ));
    wp_register_script($scripts['handle'], $scripts['src'], $scripts['deps'], $scripts['ver'], $scripts['in_footer']);
    wp_enqueue_script($scripts['handle']);
}


/***********************************************************************************************************************/
if (class_exists("DOPBSPFrontEnd")) {
    $DOPBSP_pluginSeries_reservations_front = new DOPBSPFrontEnd();

}
/***********************************************************************************************************************/

$DOPBSP_pluginSeries_reservations_front = new DOPBSPFrontEnd();
//add_action('wp_ajax_dopbsp_show_new_reservations_front', array(&$DOPBSP_pluginSeries_reservations_front, 'showNewReservations'));
add_action('wp_ajax_dopbsp_init_reservations_front', array(&$DOPBSP_pluginSeries_reservations_front, 'initReservations'));
add_action('wp_ajax_dopbsp_init_add_reservation_front', array(&$DOPBSP_pluginSeries_reservations_front, 'initAddReservation'));
//add_action('wp_ajax_dopbsp_add_reservation_front', array(&$DOPBSP_pluginSeries_reservations_front, 'addReservation'));
add_action('wp_ajax_dopbsp_get_list_reservations_front', array(&$DOPBSP_pluginSeries_reservations_front, 'getListReservations'));
//add_action('wp_ajax_dopbsp_get_calendar_reservations_front', array(&$DOPBSP_pluginSeries_reservations_front, 'getCalendarReservations'));
add_action('wp_ajax_dopbsp_approve_reservation_front', array(&$DOPBSP_pluginSeries_reservations_front, 'approveReservation'));
add_action('wp_ajax_dopbsp_reject_reservation_front', array(&$DOPBSP_pluginSeries_reservations_front, 'rejectReservation'));
add_action('wp_ajax_dopbsp_cancel_reservation_front', array(&$DOPBSP_pluginSeries_reservations_front, 'cancelReservation'));
add_action('wp_ajax_dopbsp_delete_reservation_front', array(&$DOPBSP_pluginSeries_reservations_front, 'deleteReservation'));


/***********************************************************************************************************************/
function directory_custom_admin_scripts_rec()
{
    wp_register_script('admin_customjs', get_stylesheet_directory_uri() . '/js/admin_customjs.js', 'jquery', TRUE);
    wp_register_style('admin_custom_style', get_stylesheet_directory_uri() . '/css/admin_custom_style.css');

    wp_enqueue_script('admin_customjs');
    wp_enqueue_style('admin_custom_style');

}

add_action('admin_enqueue_scripts', 'directory_custom_admin_scripts_rec', 1000);
/***********************************************************************************************************************/

function bookingSystemScripts()
{
    if (!is_admin()) {
        $DOPBSP_pluginSeries = new DOPBSPBackEnd();
        wp_register_script('DOPBSP-js-backend-settings', plugins_url() . '/dopbsp/assets/js/settings/backend-settings.js', array('jquery'), false, true);

        wp_register_script('single-place', get_stylesheet_directory_uri() . '/js/signup-place.js', array(
            'appengine',
            'functions',
            'front'
        ), true);
        // Enqueue JavaScript.
        if (!wp_script_is('jquery', 'queue')) {
            wp_enqueue_script('jquery');
        }

        if (!wp_script_is('jquery-ui-datepicker', 'queue')) {
            wp_enqueue_script('jquery-ui-datepicker');
        }

        if (!wp_script_is('jquery-ui-sortable', 'queue')) {
            wp_enqueue_script('jquery-ui-sortable');
        }

        wp_enqueue_script('DOPBSP-js-backend-settings');

        wp_enqueue_script('single-place');


        // Enqueue Styles.
        wp_enqueue_style('thickbox');

    }
}

add_action('init', 'bookingSystemScripts');

/***********************************************************************************************************************/
function get_commision_percentage_value()
{

    global $wpdb;

    $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'tbl_point_settings');

    $percentage_value = $results[0]->percentage_earning;

    if (filter_var($percentage_value, FILTER_VALIDATE_FLOAT) !== false) {

    } else {
        $percentage_value = 5;
    }

    return $percentage_value;

}

/***********************************************************************************************************************/
function cliv_create_single_schedule()
{
    //check if event scheduled before
    if (!wp_next_scheduled('cliv_single_cron_job'))
        $midnight = strtotime("midnight", current_time('timestamp'));
    wp_schedule_single_event(strtotime('first day of this month', $midnight), 'cliv_single_cron_job');
}

add_action('cliv_single_cron_job', 'cliv_single_cron_function');

/***********************************************************************************************************************/

function get_status_posibles_reservation_bsp()
{
    global $wpdb;

    $status_record = @$wpdb->get_results("select distinct status from " . $wpdb->base_prefix . "dopbsp_reservations");
    return $status_record;
}

/***********************************************************************************************************************/

function get_users_from_calander_reservations($selected_year, $selected_month)
{
    global $wpdb;


    if ($selected_year != '') {
        $year_qry = " and YEAR(dr.date_created)=" . $selected_year . " ";
    } else {
        $year_qry = "";
    }
    if ($selected_month != '') {
        $month_qry = " and MONTH(dr.date_created)=" . $selected_month . " ";
    } else {
        $month_qry = "";
    }

    $user_records = $wpdb->get_results("SELECT SUM(dr.price) as total_amount, dr.reserver_user_id FROM " . $wpdb->base_prefix . "dopbsp_reservations as dr INNER JOIN " . $wpdb->base_prefix . "dopbsp_calendars as dc ON dr.calendar_id=dc.id WHERE dr.approved_status='approved' " . $month_qry . $year_qry . "  group by dr.reserver_user_id");

    if ($user_records) {
        foreach ($user_records as $user_key => $user_val) {
            $user_arr[] = $user_val->reserver_user_id;
        }

        return $user_arr;
    }
    return null;

}


/***********************************************************************************************************************/
function after_comment_post_add_points($comment_id)
{
//echo $comment_id;

    global $wpdb;
    $comment_post_result = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "comments WHERE comment_ID=" . $comment_id . " ");

    $comment_post_ID = $comment_post_result->comment_post_ID;

    $table = $wpdb->prefix . 'tbl_point_settings';

    $querystr = "SELECT * FROM $table";
    $review_points = $wpdb->get_results($querystr, OBJECT);
    $review_pts = $review_points[0]->review_points;
    $user_ID = get_current_user_id();
    $current_date = date("Y-m-d H:i:s");

    $table_2 = $wpdb->prefix . 'tbl_points_log';
    $qry = "SELECT * FROM $table_2 WHERE post_id =" . $comment_post_ID . " and user_id =" . $user_ID . " ";
    $res = $wpdb->get_results($qry, OBJECT);

    if (!empty($res)) {

    } else {
        $data = array(
            'user_id' => $user_ID,
            'post_id' => $comment_post_ID,
            'points_added' => $review_pts,
            'type' => 'review_points',
            'curr_date' => $current_date
        );
        $wpdb->insert($table_2, $data);

        if (!session_id())
            session_start();

        $_SESSION['points_added_successfully'] = "yes";

    }
}

add_action('comment_post', 'after_comment_post_add_points');

/***********************************************************************************************************************/
function check_if_booking_reserved_for_user($post_id)
{

    global $wpdb;
    $user_ID = get_current_user_id();
    $post_results = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "dopbsp_reservations as dr INNER JOIN " . $wpdb->base_prefix . "dopbsp_calendars as dc ON dr.calendar_id=dc.id WHERE dc.post_id=" . $post_id . " and dr.reserver_user_id=" . $user_ID . "");

    return count($post_results);
}

/***********************************************************************************************************************/
function pointsPopup($message)
{
    ?>
    <style>
        #points-popup {
            position: fixed;
            bottom: 0;
            left: 0;
            background-color: #1c84d4;
            opacity: 0.9;
            padding: 40px 20px;
            color: #FFF;
            z-index: 9999;
            display: none;
        }

        #points-popup a {
            color: #000 !important;
            cursor: pointer;
            position: absolute;
            right: 4px;
            top: 0;
        }

        #points-popup a:before {
            content: "\f00d";
            font-family: 'FontAwesome';
        }
    </style>
    <script>
        jQuery(document).ready(function () {
            jQuery('#points-popup #close-popup').click(function () {
                jQuery('#points-popup').slideUp('slow');
            });
            setTimeout(function () {
                jQuery('#points-popup').slideDown('slow', function () {
                    setTimeout(function () {
                        jQuery('#points-popup').slideUp(2000, function () {

                        });
                    }, 5000);

                });
            }, 500);
        })
    </script>
    <div id="points-popup">
        <a id="close-popup"></a>
        <?= $message; ?>
    </div>
    <?php
}

/***********************************************************************************************************************/
function count_user_reviews_reservation($post_id)
{
    global $wpdb;
    $user_ID = get_current_user_id();
    $review_results = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "comments WHERE comment_post_ID=" . $post_id . " and user_id=" . $user_ID . " and comment_type='review'");
    return count($review_results);
}

/***********************************************************************************************************************/
function added_redeem_fun()
{
    global $wpdb;
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;

    $added_redeem = $wpdb->get_results("SELECT (COALESCE(sum(points_added),0) - COALESCE(sum(points_redeem),0)) as tpoint FROM " . $wpdb->base_prefix . "tbl_points_log WHERE user_id=" . $current_user_id . "");

    return ($added_redeem);
}

/***********************************************************************************************************************/
function get_place_calendar_reserveration_price($place_id)
{
    global $wpdb;

    $price = "N/D";
    $row_result = $wpdb->get_row("SELECT * FROM " . $wpdb->base_prefix . "dopbsp_calendars WHERE post_id=" . $place_id . "");
    if ($row_result) {
        $calendar_id = $row_result->id;
        $days_result = $wpdb->get_row("SELECT * FROM " . $wpdb->base_prefix . "dopbsp_days WHERE calendar_id=" . $calendar_id . " order by day desc limit 1");

        if ($days_result) {
            if ((isset($days_result->price) && $days_result->price)) {
                $price = $days_result->price;
            } else {
                log_error_custom("No hay precio para el calendario id=$calendar_id");
            }
        } else {
            log_error_custom("No hay dias para el calendar_id=$calendar_id");
        }

    } else {
        log_error_custom("No hay calendario para el post_id=$place_id");
    }
    return $price;


}

/***********************************************************************************************************************/
function de_categories_list($args = array('hide_empty' => false))
{

    $args = wp_parse_args($args, array(
            'style' => 'horizontal',
            'count' => false,
            'hide_empty' => true,
        )
    );
    $category = get_terms("place_category", $args);

    $col = 'col-md-3';
    if ($args['style'] == 'horizontal') $col = 'col-md-12';

    ?>

    <!-- Categories -->
    <div class="row cssmenu">
        <ul class="list-categories">

            <?php

            foreach ($category as $key => $cat) {
                $term_id = $cat->term_id;
                $taxonomy_name = $cat->taxonomy;
                $termchildren = get_term_children($term_id, $taxonomy_name);
                $child_class1 = '';
                $child_flag1 = false;
                $stop = '';
                if (is_array($termchildren) && count($termchildren) > 0) {
                    $child_class1 = ' has_term_childeren';
                    $child_flag1 = true;
                    $stop = 'stop_yr';
                }

                ?>
                <li class="<?= $col . $child_class1; ?> cat-<?php echo $cat->term_id; ?>">
                    <a href="<?= get_term_link($cat, 'place_category') ?>"
                       class="categories-wrapper <?= $stop; ?>">
                        <span class="icon-categories">
                            <i class="fa <?= $cat->icon; ?>"></i>
                        </span>

                        <h3 class="categories-name"><?= $cat->name; ?></h3>

                        <?php if ($args['count']) { ?>
                            <span class="number-categories"><?= $cat->count; ?></span>
                        <?php } ?>

                    </a>

                    <?php

                    if ($child_flag1) {
                        echo '<ul>';
                        $all_terms_array = array();
                        foreach ($termchildren as $child) {
                            $class = "";

                            $term = get_term_by('id', $child, $taxonomy_name);
                            $all_terms_array[] = $term->term_id;
                            $term_id2 = $term->term_id;
                            $taxonomy_name2 = $term->taxonomy;

                            if ($term->count == 0) continue;  // Miguel. cambio. ocultamos las categorias sin anuncios

                            $termchildren2 = get_term_children($term_id2, $taxonomy_name2);
                            $child_class2 = '';
                            if ($termchildren2) {
                                $class = "has-sub";
                                $child_class2 = ' has_term_childeren';
                            }

                            if (in_array($term->parent, $all_terms_array)) continue;

                            echo '<li  class="' . $class . $child_class2 . '">';
                            echo '<a href="' . get_term_link($child, $taxonomy_name) . '">' . $term->name . '</a>';
                            echo '<ul>';

                            foreach ($termchildren2 as $child2) {
                                $term2 = get_term_by('id', $child2, $taxonomy_name2);
                                echo '<li><a href="' . get_term_link($child2, $taxonomy_name2) . '">' . $term2->name . '</a></li>';
                            }
                            echo '</ul>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<ul>';
                        foreach ($termchildren as $child) {
                            $term = get_term_by('id', $child, $taxonomy_name);
                            echo '<li class=""><a href="' . get_term_link($child, $taxonomy_name) . '">' . $term->name . '</a></li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

/***********************************************************************************************************************/

function modify_places_query_featured_posts_child($query)
{

    global $current_user;
    $post_type_val = 'place';

    /**
     * filter orderby
     */

    if (isset($_REQUEST['sortby']) && ($query->is_main_query() || (isset($query->query_vars['meta_key']) && $query->query_vars['meta_key'] == 'rating_score'))) {

        if ($_REQUEST['sortby'] !== 'date') {
            $query->set('orderby', 'meta_value_num');
        } else {
            $query->set('orderby', 'date');
        }
        $query->set('order', 'DESC');
    }


    if ($query->is_main_query() && is_author()) {
        $query->set('post_type', $post_type_val);
        if ($current_user->user_login == @$query->query['author_name']) {
            $query->set('post_status', array('pending', 'reject', 'archive', 'draft', 'publish'));
        }
    }


    if ($query->is_main_query() && (is_tax('place_category') || is_tax('location'))) {
        $query->set('post_type', $post_type_val);
        $query->set('post_status', 'publish');
    }

    /**
     * is main query and cach request showposts
     */

    if (isset($_REQUEST['showposts']) && $query->is_main_query()) {
        $query->set('showposts', $_REQUEST['showposts']);
    }

    /**
     * if is post type archive set status to publish
     */
    if (is_post_type_archive($post_type_val) && !is_admin()) {
        if (!isset($query->query_vars['post_status'])) {
            $query->set('post_status', 'publish');
        }
    }

    if (et_load_mobile()) {

        if (!is_author() && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == $post_type_val) {
            $query->set('meta_key', 'et_featured');
            $query->set('orderby', 'meta_value_num date');
            $query->set('meta_query', array( //check to see if et_featured has been filled out
                    'relation' => 'OR',
                    array( //check to see if date has been filled out
                        'key' => 'et_featured',
                        'compare' => 'IN',
                        'value' => array(0, 1)
                    ),
                    array( //if no et_featured has been added show these posts too
                        'key' => 'et_featured',
                        'value' => 0,
                        'compare' => 'NOT EXISTS'
                    )
                )
            );
        }
    }
    return $query;
}

add_action('pre_get_posts', 'modify_places_query_featured_posts_child');

/***********************************************************************************************************************/
function remove_some_parent_theme_features()
{
    remove_action('wp_footer', 'de_footer_template');
}

add_action('after_setup_theme', 'remove_some_parent_theme_features');

/***********************************************************************************************************************/

function de_footer_template_child()
{
    global $user_ID;

    get_template_part('template-js/loop', 'area');
    // user not login in render template modal authencation
    if (!is_user_logged_in()) {
        get_template_part('template-js/modal', 'authenticate');
    }

    // user login and on template author
    if (is_user_logged_in() && is_author() || is_page_template('page-profile.php')) {
        get_template_part('template-js/modal', 'edit-profile');
    }

    // user logged in and in author or single place
    if (is_user_logged_in() && (is_author() || is_singular('place'))) {
        get_template_part('template-js/modal', 'contact');
    }

    // print report modal
    if (is_singular('place')) {
        get_template_part('template-js/modal', 'report');
        get_template_part('template-js/modal', 'claim');
    }

    global $post;

    if (is_user_logged_in() && ae_user_can('edit_others_posts')) {
        get_template_part('template-js/loop', 'notification');
    }

    $mob = (et_load_mobile() == "1") ? 'mobile/' : '';
    /* 15_10_2016 Id : 4 S*/
    if ((!is_page_template('page-post-place.php') && !is_page_template('page-post-place-fullwidth.php')) && ae_user_can('edit_others_posts')  // user can edit others post
        || (is_singular('place') && $user_ID == $post->post_author) // user owned the post in single page
        || (is_author() && $user_ID == get_query_var('author')
            || is_page_template('page-profile.php'))
    ) {
        /* 15_10_2016 Id : 4 E*/
        // render template modal edit place
        get_template_part('template-js/modal', 'edit-place');
        get_template_part('template-js/modal', 'create-calender'); // new hammad
    }

    /* 15_10_2016 Id : 5 S*/
    if (is_page_template('page-post-place.php') || is_page_template('page-post-place-fullwidth.php')) { // new hammad
        /* 15_10_2016 Id : 5 E*/
        get_template_part('template-js/modal', 'create-calender');
    }

    if (ae_user_can('edit_others_posts')) {
        // render modal reject template
        get_template_part('template-js/modal', 'reject');
    }
    // ce_categories_json();

    get_template_part('template-js/modal', 'create-event');
    get_template_part('template-js/event', 'item');

    if (is_page_template('page-profile.php')) {
        get_template_part('template-js/author', 'loop-place');
        get_template_part('template-js/author', 'loop-review');
        get_template_part('template-js/author', 'loop-togo');
        get_template_part('template-js/author', 'loop-picture');
        get_template_part('template-js/author', 'loop-event');
    } elseif (is_author()) {
        get_template_part('template-js/author', 'loop-review');
        get_template_part('template-js/author', 'loop-togo');
        get_template_part('template-js/loop', 'place');
        get_template_part('template-js/author', 'loop-event');
    } else {
        get_template_part('template-js/loop', 'place');
    }
    get_template_part('template-js/loop', 'place-nearby');
    get_template_part('template-js/loop', 'review');
    get_template_part('template-js/loop', 'post');

    if (is_page_template('page-list-user.php')) {
        get_template_part('template-js/user', 'item');
    }
    ?>
    <script type="text/template" id="ae_carousel_template">
        <li class="image-item" id="{{= attach_id }}"><span class="img-gallery">
            <img title="" data-id="{{= attach_id }}" src="{{= thumbnail[0] }}"/>
            <a href="" title="<?php _e("Delete", ET_DOMAIN); ?>" class="delete-img delete"><i
                        class="fa fa-times"></i></a>
            </span>

            <div class="inputRadio">
                <input class="checkbox-field" name="featured_image" value="{{= attach_id }}"
                       title="<?php _e("click to select a featured image", ET_DOMAIN); ?>"
                       id="check-image-{{= attach_id }}" type="radio"
                <# if(typeof is_feature !== "undefined" ) { #> checked="true"
                    <# } #> />
                        <label for="check-image-{{= attach_id }}"></label>
            </div>
        </li>
    </script>

    <?php


    if (is_user_logged_in() && !is_page_template('page-profile.php')) {
        global $current_user;
        $tiene_notificaciones_chat = tieneChatNoLeidos($current_user->ID);
        if ($tiene_notificaciones_chat) {
            get_template_part('template-js/modal', 'ir-a-mensajes');
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    if (frontGetCookie('no_ver_modal_mensajes') != 1) {
                        jQuery("#ir-a-mensaje").modal()
                    }
                });
            </script>

            <?php
        } else {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    frontEraseCookie('no_ver_modal_mensajes')
                });
            </script>

            <?php
        }

    }

}

add_action('wp_footer', 'de_footer_template_child');
/***********************************************************************************************************************/

function gls_display_rating_result2($atts = array())
{
    // get the post id
    global $post;
    if (!isset($post_id) && isset($post)) {
        $post_id = $post->ID;
    } else if (!isset($post) && !isset($post_id)) {
        return; // No post Id available to display rating form
    }
    $comment_id = $atts['comment_id'];
    $rating_form_id = $atts['rating_form_id'];

    $rating_result = get_rating_items_gls($post_id, $rating_form_id, $comment_id);
    $params = array(
        'no_rating_results_text' => $no_rating_results_text,
        'show_rich_snippets' => $show_rich_snippets,
        'show_title' => $show_title,
        'show_date' => $show_date,
        'show_count' => $show_count,
        'no_rating_results_text' => $no_rating_results_text,
        'result_type' => $result_type,
        'class' => $class . ' rating-result-' . $rating_form_id . '-' . $post_id
    );
    $html = get_rating_result_type_html_gls($rating_result, $params);
    return $html;
}

add_shortcode('display_rating_result2', 'gls_display_rating_result2');

/***********************************************************************************************************************/

function get_rating_items_gls($post_id, $rating_form_id, $comment_id)
{
    global $wpdb;
    // base query
    $rating_items_query = 'SELECT ri.rating_item_id, ri.rating_id, ri.description, ri.default_option_value, '
        . 'ri.max_option_value, ri.weight, ri.active, ri.type, ri.option_value_text, ri.include_zero,riev.value FROM '
        . $wpdb->prefix . MRP_Multi_Rating::RATING_ITEM_TBL_NAME . ' as ri';

    $rating_items_query .= ', ' . $wpdb->prefix . MRP_Multi_Rating::RATING_ITEM_ENTRY_TBL_NAME . ' AS rie, '
        . $wpdb->prefix . MRP_Multi_Rating::RATING_ITEM_ENTRY_VALUE_TBL_NAME . ' AS riev';
    $rating_items_query .= ' WHERE';
    $rating_items_query .= ' riev.rating_item_entry_id = rie.rating_item_entry_id AND ri.rating_item_id = riev.rating_item_id';
    // rating_item_entry_id
    $rating_items_query .= ' AND';
    $rating_items_query .= ' rie.comment_id =  "' . $comment_id . '"';
    // post_id
    $rating_items_query .= ' AND';
    $rating_items_query .= ' rie.post_id = "' . $post_id . '"';
    //}
    $rating_items_query .= ' GROUP BY ri.rating_item_id';
    //echo $rating_items_query."<br/>";
    $rating_item_rows = $wpdb->get_results($rating_items_query);
    // construct rating items array
    $rating_items = array();
    /*echo "HERERE:<pre>";
    print_r($rating_item_rows);exit;*/
    foreach ($rating_item_rows as $rating_item_row) {
        $rating_item_id = $rating_item_row->rating_item_id;
        $weight = $rating_item_row->weight;
        $description = $rating_item_row->description;
        $default_option_value = $rating_item_row->default_option_value;
        $max_option_value = $rating_item_row->max_option_value;
        $option_value_text = $rating_item_row->option_value_text;
        $type = $rating_item_row->type;
        $include_zero = $rating_item_row->include_zero ? true : false;
        $rating_items[$rating_item_id] = array(
            'max_option_value' => $max_option_value,
            'weight' => $weight,
            'rating_item_id' => $rating_item_id,
            'description' => $description,
            'default_option_value' => $default_option_value,
            'option_value_text' => $option_value_text,
            'type' => $type,
            'include_zero' => $include_zero,
            'value' => $rating_item_row->value
        );
    }
    return $rating_items;
}

/***********************************************************************************************************************/

function get_rating_result_type_html_gls($rating_result, $params = array())
{
    extract(wp_parse_args($params, array(
        'show_title' => false,
        'show_date' => false,
        'show_rich_snippets' => false,
        'show_count' => true,
        'date' => null,
        'before_date' => '(',
        'after_date' => ')',
        'result_type' => MRP_Multi_Rating::STAR_RATING_RESULT_TYPE,
        'no_rating_results_text' => '',
        'ignore_count' => false,
        'class' => '',
        'preserve_max_option' => false
    )));
    $html = '<div class="rating-result ' . $class . '"';

    if ($show_rich_snippets && $result_type == MRP_Multi_Rating::STAR_RATING_RESULT_TYPE) {
        $html .= ' itemscope itemtype="http://schema.org/Article"';
    }
    $html .= '>';
    foreach ($rating_result as $rating_res) {
        $html .= get_star_rating_html_gls($rating_res);
    }

    $html .= '</span>';
    return $html;
}

/***********************************************************************************************************************/

function get_star_rating_html_gls($rating_res)
{
    $max_stars = $rating_res['max_option_value'];
    $star_result = $rating_res['value'];
    $type = $rating_res['type'];

    $style_settings = (array)get_option(MRP_Multi_Rating::STYLE_SETTINGS);
    $star_rating_colour = $style_settings[MRP_Multi_Rating::STAR_RATING_COLOUR_OPTION];
    $font_awesome_version = $style_settings[MRP_Multi_Rating::FONT_AWESOME_VERSION_OPTION];
    $icon_classes = MRP_Utils::get_icon_classes($font_awesome_version);
    $html = '<p class="rating-item mrp"><label class="description">' . $rating_res['description'] . '</label>';
    $html .= '<span class="mrp-star-rating" style="color: ' . $star_rating_colour . ' !important;">';
    if ($type == 'star_rating') {
        $index = 0;
        for ($index; $index < $max_stars; $index++) {
            $class = $icon_classes['star_full'];
            if ($star_result < $index + 1) {
                $diff = $star_result - $index;
                if ($diff > 0) {
                    if ($diff >= 0.3 && $diff <= 0.7) {
                        $class = $icon_classes['star_half'];
                    } else if ($diff < 0.3) {
                        $class = $icon_classes['star_empty'];
                    } else {
                        $class = $icon_classes['star_full'];
                    }
                } else {
                    $class = $icon_classes['star_empty'];
                }
            } else {
                $class = $icon_classes['star_full'];
            }
            $html .= '<i class="' . $class . '"></i>';
        }
        $html .= '</span>';
    } else {
        $html .= '<span class="thumbs" style="color:#5f6f81 !important">';
        if ($star_result == 1) {
            $html .= '<i class="fa fa-thumbs-up mrp-thumbs-up-on"></i>';
        } else {
            $html .= '<i class="fa fa-thumbs-down mrp-thumbs-down-on"></i>';

        }
        $html .= '</span>';
    }
    $html .= '</p>';
    //$html .= '<span class="star-result">' . round(doubleval( $star_result ), 2) . '/' . $max_stars . '</span>';
    return $html;
}

/***********************************************************************************************************************/

function add_register_in_gente_mailings($result, $args)
{
    if ($args["user_email"] != "") {  // antes de hacer un update asegurarnos de que el where no es vacío
        global $wpdb;

        $tiempo = date('Y-m-d H:i:s');
        $res = $wpdb->get_results(
            "UPDATE gente_mailings SET registrado='$tiempo' WHERE cv_mail_detect LIKE '%" . $args["user_email"] . "%'"
        );
    }
}

add_action('ae_insert_user', 'add_register_in_gente_mailings', 10, 2);
/***********************************************************************************************************************/

function adjuntar_calendario_y_dias_a_anuncio($result, $args)
{
    global $wpdb, $current_user;

    /*
     * Insertamos el calendario
     */

    $calendario = $wpdb->get_results(
        'SELECT * FROM ' . $wpdb->prefix . 'dopbsp_calendars WHERE user_id="' . $args['post_author'] . '"AND post_id = "' . $args['ID'] . '" order by id desc'
    );

    if (count($calendario) == 0) {  // nuevo calendario

        $wpdb->insert($wpdb->prefix . 'dopbsp_calendars',
            array(
                'user_id' => $args['post_author'],
                'post_id' => $args['ID'],
                'name' => $args['post_title']
            )
        );
        $calid = $wpdb->insert_id;

        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'unique_key', 'value' => '0'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'terms_and_conditions_link', 'value' => get_bloginfo('url') . '/terminos-y-condiciones/'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'terms_and_conditions_enabled', 'value' => 'true'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'days_multiple_select', 'value' => 'false'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'hours_enabled', 'value' => 'true'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'currency_position', 'value' => 'after'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'currency', 'value' => 'EUR'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'hours_definitions', 'value' => json_encode(array('value' => '00:00'))));

        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'sidebar_no_items_enabled', 'value' => 'false'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'hours_interval_enabled', 'value' => 'true'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_calendar', array('calendar_id' => $calid, 'unique_key' => 'date_type', 'value' => '2'));

        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_notifications', array('calendar_id' => $calid, 'unique_key' => 'email', 'value' => $current_user->user_email));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_notifications', array('calendar_id' => $calid, 'unique_key' => 'method_admin', 'value' => 'wp'));
        $wpdb->insert($wpdb->prefix . 'dopbsp_settings_notifications', array('calendar_id' => $calid, 'unique_key' => 'method_user', 'value' => 'wp'));

    } else
        $calid = $calendario[0]->id;

    $rec['calendar_id'] = $calid;

    /*
     * insertamos los datos en la tabla days
     */

    $days_row = $wpdb->get_results(
        "SELECT * FROM wp_dopbsp_days WHERE calendar_id='$calid'"
    );
    if (count($days_row) == 0) {  // nuevo calendario
        foreach ($args['serve_time'] as $key => $days) {
            if ($days['open_time'] != "" && $days['close_time'] != "" && $days['open_time'] != $days['close_time']) {
                $opentime1 = $days['open_time'];
                $closetime1 = $days['close_time'];
                $weeks[] = $key;
            }
            if ($days['open_time_2'] != "" && $days['close_time_2'] != "" && $days['open_time_2'] != $days['close_time_2']) {
                $opentime2 = $days['open_time_2'];
                $closetime2 = $days['close_time_2'];
                $weeks[] = $key;
            }
        }


        if (isset($opentime1) && isset($closetime1))
            $arr[] = $opentime1 . '-' . $closetime1;
        if (isset($opentime2) && isset($closetime2))
            $arr[] = $opentime2 . '-' . $closetime2;


        $today = explode("-", date('d-m-Y', time()));
        $nextday = explode("-", date('d-m-Y', strtotime('+6 months')));
        $rec['start_date'] = mktime(0, 0, 0, $today[1], $today[0], $today[2]);
        $rec['end_date'] = mktime(0, 0, 0, $nextday[1], $nextday[0], $nextday[2]);
        $rec['days'] = implode(",", array_unique($weeks));
        $rec['data'] = implode(",", $arr);
        $rec['status'] = 1;
        $rec['price'] = $args['hourly_rate1'];


        $table = $wpdb->prefix . 'dopbsp_days';
        $dias_id = $wpdb->insert($table, $rec);

    } else {
        $dias_id = $days_row[0]->id;
    }
    // control de seguridad, comprobamos que el calendario ha sido creado y los dias han sido insertados

    if (!$dias_id || !$calid) {  // algun fallo

        $msg = '<br />
				<h3>Detalles del error</h3>
			<table>
				<tbody>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Nombre de Usuario:</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $current_user->user_nicename . '</em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">ID de Usuario:</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $args['post_author'] . '</em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Email de Usuario:</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $current_user->user_email . '</em></td>
					</tr>
                  <br />
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Post ID (Anuncio)</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $args['ID'] . '</em></td>
					</tr>
	                <tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Url Anuncio:</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;"><a href="' . get_permalink($args['ID']) . '">Ir a anuncio</a></em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Nombre (Anuncio)</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $args['post_title'] . '</em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Fecha</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . date('d-m-Y h:i:s') . '</em></td>
					</tr>
                   <br />
                    <tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Calendario ID</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $calid . '</em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Dias ID</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . $dias_id . '</em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Array (Data)</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">' . json_encode($rec) . '</em></td>
					</tr>
				</tbody>
			</table>';

        wp_mail("yotengounmovil@hotmail.com,servilisto.com@gmail.com,moyaperez@hotmail.com", "ERROR Anuncio/Calendario/Dias (Urgente)", $msg);
    }

    if ($current_user->user_email != "") {  // antes de hacer un update asegurarnos de que el where no es vacío
        global $wpdb, $current_user;

        $tiempo = date('Y-m-d H:i:s');
        $res = $wpdb->get_results(
            "UPDATE gente_mailings SET anuncio_publicado='$tiempo' WHERE cv_mail_detect LIKE '%" . $current_user->user_email . "%'"
        );
    }

}

add_action('ae_insert_place', 'adjuntar_calendario_y_dias_a_anuncio', 10, 2);
add_action('ae_update_place', 'adjuntar_calendario_y_dias_a_anuncio', 10, 2);

/***********************************************************************************************************************/
function delete_availability_cal($post_id)
{

    if (get_post_type($post_id) == "place") {
        global $wpdb, $DOPBSP;

        $calendario = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'dopbsp_calendars WHERE post_id=' . $post_id . '');

        if (count($calendario) > 0) {
            $id_calendario = $calendario[0]->id;

            $wpdb->delete($DOPBSP->tables->calendars, array('post_id' => $post_id));
            $wpdb->delete($DOPBSP->tables->days, array('calendar_id' => $id_calendario));
            $wpdb->delete($DOPBSP->tables->reservations, array('calendar_id' => $id_calendario));
            $wpdb->delete($DOPBSP->tables->settings_calendar, array('calendar_id' => $id_calendario));
            $wpdb->delete($DOPBSP->tables->settings_notifications, array('calendar_id' => $id_calendario));
            $wpdb->delete($DOPBSP->tables->settings_payment, array('calendar_id' => $id_calendario));
            /*
             * Delete users permissions.
             */
            $users = get_users();
            foreach ($users as $user) {
                if ($DOPBSP->classes->backend_settings_users->permission($user->ID, 'use-calendar', $id_calendario)) {
                    $DOPBSP->classes->backend_settings_users->set(array(
                        'calendar_id' => $id_calendario,
                        'id' => $user->ID,
                        'slug' => '',
                        'value' => 0
                    ));
                }
            }
        }


    }
}

add_action('delete_post', 'delete_availability_cal', 10, 1);
/***********************************************************************************************************************/
function mis_scripts()
{

    if (!is_admin()) {

        global $DOPBSP;
        /*wp_register_script('DOP-js-jquery-dopselect2', $DOPBSP->paths->url . 'libraries/js/jquery.dop.Select.js', array('jquery'), '', false);
        wp_enqueue_script('DOP-js-jquery-dopselect2');*/

        // este es para los select de categorias personalizados
        wp_register_script('drop', get_stylesheet_directory_uri() . '/js/drop.js', array('jquery'), '', false);
        wp_enqueue_script('drop');

        wp_register_script('date-pair', get_stylesheet_directory_uri() . '/js/jquery.datepair.min.js', array('jquery', 'jquery-timepicker'), false, true);
        wp_enqueue_script('date-pair');

    }

    wp_enqueue_script('jquery-eu-cookie-law-popup', get_stylesheet_directory_uri() . '/js/jquery-eu-cookie-law-popup.js', array('jquery'), ET_VERSION, true);
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array('jquery'), ET_VERSION, true);
    wp_enqueue_script('et-googlemap-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCdCbihsv-CElefbB7Gikt6ZomYyVqBdiw', array('jquery'), ET_VERSION, true);
    wp_enqueue_script('recaptcha', "https://www.google.com/recaptcha/api.js?onload=onloadCallbackRecaptcha&render=explicit", array('jquery'), ET_VERSION, true);  // esté está insertado en el footer manualmente para pagespeed insights
}

add_filter('wp_enqueue_scripts', 'mis_scripts');
/***********************************************************************************************************************/

function mis_styles()
{

//mios particulares
    add_style('jquery-eu-cookie-law-popup', get_stylesheet_directory_uri() . '/css/jquery-eu-cookie-law-popup.css', NULL, ET_VERSION); // ruta al child
    add_style('fonts-google', get_stylesheet_directory_uri() . '/css/fonts-google.css', NULL, ET_VERSION); // ruta al child
    add_style('drop', get_stylesheet_directory_uri() . '/css/drop.css', NULL, ET_VERSION); // ruta al child
}

add_action('wp_print_styles', 'mis_styles');

/***********************************************************************************************************************/
function usuario_mas_columnas($columnas)
{
    $columnas['anuncios'] = __("Anuncios");
    $columnas['user_registered'] = __("Registrado");
    return $columnas;
}

add_filter('manage_users_columns', 'usuario_mas_columnas');

/***********************************************************************************************************************/
function usuario_mostrar_fecha_registro($vacio, $nombre_columna, $id_usuario)
{
    if ($nombre_columna == 'user_registered') {
        $user = get_userdata($id_usuario);
        return mysql2date('d/m/Y H:i', $user->user_registered, true);
    }

    if ($nombre_columna == "anuncios") {

        $args = array(
            'post_type' => 'place',
            'author' => $id_usuario
        );
        $query = new WP_Query($args);

        $div = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $post;
                $div .= '<p><a href="' . admin_url('post.php?post=' . $post->ID . '&action=edit') . '">- ' . $post->post_title . '</p>';
            }
        }
        return $div;
    }

    return;

}

add_filter('manage_users_custom_column', 'usuario_mostrar_fecha_registro', 10, 3);

/***********************************************************************************************************************/
function prefix_sortable_columns($columns)
{
    $columns['user_registered'] = 'user_registered';
    return $columns;
}

add_filter('manage_users_sortable_columns', 'prefix_sortable_columns');

/**********************************************************************************************************/

add_filter('wp_mail_content_type', 'set_content_type');
function set_content_type($content_type)
{
    return 'text/html';
}

/**********************************************************************************************************/

function display_serve_time($serve_time)
{
    $res = '';
    /* $serve_time = array();
     $serve_time["Mon"] = array('open_time' => '08:00', 'close_time' => '12:00', 'open_time_2' => '15:00', 'close_time_2' => '16:00');
     $serve_time["Fri"] = array('open_time' => '08:00', 'close_time' => '12:00');*/
    $days = array('Mon' => __('Monday', ET_DOMAIN), 'Tue' => __('Tuesday', ET_DOMAIN), 'Wed' => __('Wednesday', ET_DOMAIN),
        'Thu' => __('Thursday', ET_DOMAIN), 'Fri' => __('Friday', ET_DOMAIN), 'Sat' => __('Saturday', ET_DOMAIN), 'Sun' => __('Sunday', ET_DOMAIN));


    $hay_alguna_disponibilidad = false;
    if ($serve_time) {

        $res = '<div class="god">';

        foreach ($serve_time as $key => $value) {


            if (($value['open_time'] && $value['open_time'] != "") && ($value['close_time'] && $value['close_time'] != "") ||
                ($value['open_time_2'] && $value['open_time_2'] != "") && ($value['close_time_2'] && $value['close_time_2'] != "")
            ) {

                $hay_alguna_disponibilidad = true;

                $res .= '<div class="day row">';
                $res .= '<span class="open-date col-md-6 col-xs-6">' . $days[$key] . '</span>';

                if (($value['open_time'] && $value['open_time'] != "") && ($value['close_time'] && $value['close_time'] != "") &&
                    ($value['open_time_2'] && $value['open_time_2'] != "") && ($value['close_time_2'] && $value['close_time_2'] != "")
                ) {
                    $res .= '<span class="open-time col-md-6 col-xs-6">
                                De ' . $value['open_time'] . ' a ' . $value['close_time'] .
                        '<br>De ' . $value['open_time_2'] . ' a ' . $value['close_time_2'] .
                        '</span>';
                } else if (($value['open_time'] && $value['open_time'] != "") && ($value['close_time'] && $value['close_time'] != ""))
                    $res .= '<span class="open-time col-md-6  col-xs-6">De ' . $value['open_time'] . ' a ' . $value['close_time'] . '</span>';
                else if (($value['open_time_2'] && $value['open_time_2'] != "") && ($value['close_time_2'] && $value['close_time_2'] != "")) {
                    $res .= '<span class="open-time col-md-6  col-xs-6">De ' . $value['open_time_2'] . ' a ' . $value['close_time_2'] . '</span>';
                }
                $res .= '</div>';
            }

        }


        $res .= '</div>';
    }

    if (!$hay_alguna_disponibilidad) {
        $res = "<span class='sin-disponibilidad-place content-single-place-details'>Sin disponibilidad actual</span>";
    }


    return $res;

}

/**********************************************************************************************************/

function cambio_logo_admin()
{ ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/servilisto-transparente.png);
        }
    </style>
<?php }

add_action('login_enqueue_scripts', 'cambio_logo_admin');


/***********************************************************************************************************************/
/**
 * Construye el username de un usuario que no escribe username (PARTEDEARROBA@email.com) devolverá "PARTEDEARROBA"
 */
function my_sanitize_username($user, $raw_user, $strict)
{
    if (is_email($user)) {
        $user = preg_replace('/@.*?$/', '', $user);
    }
    return $user;
}

add_filter('sanitize_user', 'my_sanitize_username', 10, 3);

/***********************************************************************************************************************/
/**
 * Ordena la lista de usuarios en /wp-admin/users.php por orden de registro
 */
function sort_userlist_by_registered($query_args)
{
    if (is_admin() && !isset($_GET['orderby'])) {
        $query_args->query_vars['orderby'] = 'user_registered DESC';
        $query_args->query_vars['order'] = 'DESC';
    }
    return $query_args;
}

add_action('pre_get_users', 'sort_userlist_by_registered');

/***********************************************************************************************************************/
function remove_api()
{
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
}

add_action('after_setup_theme', 'remove_api');
/***********************************************************************************************************************/
/**
 * @return string Cambia la imagen de los mails que se mandan
 */
function cambiar_logo_email()
{
    return wp_upload_dir()["baseurl"] . "/2017/03/logo_mail.png";
}

add_action('ae_mail_logo_url', 'cambiar_logo_email');


/***********************************************************************************************************************/
/**
 * Modifica la url sustituyendo %categoria% y %provincia% (Cuidado, tambien está en htaccess la redirección)
 */
function filter_post_type_link($link, $post)
{

    if ($post->post_type == 'place') {

        //$location = wp_get_post_terms($post->ID, "location", array("fields" => "slugs"))[0];
        $category = wp_get_post_terms($post->ID, "place_category", array("fields" => "slugs"))[0];

        //$link = str_replace("busco", "busco/$category/$location", $link);
        $link = str_replace("last_place_category", $category, $link);

    }
    return $link;

}

//add_filter('post_type_link', 'filter_post_type_link', 1000, 2); // uno mas que permalink manager


/***********************************************************************************************************************/
/* Quitar actualizaciones de los plugins de la lista "unset"*/
function disable_pluggins_updates($value)
{
    unset($value->response['super-socializer/super_socializer.php']);

    return @$value;
}

add_filter('site_transient_update_plugins', 'disable_pluggins_updates');

/***********************************************************************************************************************/
/**
 * Funcion que pone los mensajes a leido=1, cuando el usuario clica las cabeceras
 */
function poner_como_leidos_chat()
{

    global $wpdb;

    $request = $_REQUEST;

    $id_anuncio = $request["id_anuncio"];
    $id_useractual = $request["id_useractual"];
    $id_otrouser = $request["id_otrouser"];

    if (isset($id_anuncio) && isset($id_useractual) && isset($id_otrouser)) {

        // veo quien lo ha leido
        $cadena1 = "[$id_useractual],[$id_otrouser]";
        $cadena2 = "[$id_otrouser],[$id_useractual]";

        $consulta = "SELECT * FROM chat WHERE id_anuncio=$id_anuncio AND ( implicados='$cadena1' or implicados='$cadena2')";
        $leidos = $wpdb->get_results($consulta);

        foreach ($leidos as $leido) {
            ponerMensajeComoCargado($leido->id, $id_useractual);
            ponerMensajeComoLeido($leido->id, $id_useractual);
        }


        wp_send_json(array('success' => true, 'code' => 0));

    } else {
        wp_send_json(array('success' => false, 'code' => -1));
    }
}

add_action('wp_ajax_poner-como-leidos-chat', 'poner_como_leidos_chat');

/***********************************************************************************************************************/
/**
 * Obtiene los datos de usuario y del anuncio para completar los campos de la reserva
 */
function get_data_form_reserva()
{

    $id_user = get_current_user_id();

    $return = array(
        'success' => false
    );

    if ($id_user) { // obtenemos el nombre y el email del usuario actual
        $return1 = get_userdata($id_user);
        $return['success'] = true;
        $return["nombre"] = $return1->display_name;
        $return["email"] = $return1->user_email;
        $return["direccion"] = get_user_meta($id_user, 'location', true);
        $return["telefono"] = get_user_meta($id_user, 'phone', true);
    }

    wp_send_json($return);
}

add_action('wp_ajax_my_get_data_form_reserva', 'get_data_form_reserva'); // para usuarios logueados
//add_action('wp_ajax_nopriv_my_get_data_form_reserva', 'get_data_form_reserva');  // para usuarios no logeuados

/***********************************************************************************************************************/
/**
 * Funcion que envia el mensaje de chat
 */
function enviar_mensaje_chat()
{

    global $wpdb;

    $request = $_REQUEST;

    $id_anuncio = $request["id_anuncio"];
    $id_useractual = $request["id_useractual"];
    $id_otrouser = $request["id_otrouser"];
    $mensaje = $request["mensaje"];
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $implicados = "[$id_useractual],[$id_otrouser]";

    if (isset($id_anuncio) && isset($id_useractual) && isset($id_otrouser) && isset($mensaje)) {

        $insert = $wpdb->query("INSERT INTO chat (id_anuncio,implicados,mensaje,fecha,hora,leido,cargado) VALUES
 ('$id_anuncio','$implicados','$mensaje','$fecha','$hora','[$id_useractual]','[$id_useractual]') ");

        if ($insert) {

            wp_send_json(
                array('success' => true,
                    'code' => 1,
                ));
        } else {
            wp_send_json(array('success' => false, 'code' => -1));
        }


    } else {
        wp_send_json(array('success' => false, 'code' => -1));
    }
}

add_action('wp_ajax_enviar-mensaje-chat', 'enviar_mensaje_chat');

/***********************************************************************************************************************/
/**
 * Funcion que genera el tiempo real entre chats
 * Trae los no leidos, y los carga en pantalla
 */
function tiempo_real_chat()
{
    global $wpdb;

    $request = $_REQUEST;

    $id_anuncio = $request["id_anuncio"];
    $id_useractual = $request["id_useractual"];
    $id_otrouser = $request["id_otrouser"];

    $last_day_en_mensaje = $request["last_day_en_mensaje"];
    $hora = date("H:i:s");

    if (isset($id_anuncio) && isset($id_useractual) && isset($id_otrouser) && isset($last_day_en_mensaje)) {

        // veo quien lo ha leido
        $cadena1 = "[$id_useractual],[$id_otrouser]";
        $cadena2 = "[$id_otrouser],[$id_useractual]";

        $consulta = "SELECT * FROM chat WHERE id_anuncio=$id_anuncio AND ( implicados='$cadena1' or implicados='$cadena2') AND (cargado NOT LIKE '%[$id_useractual]%')";
        $no_cargados_por_user = $wpdb->get_results($consulta);

        if ($no_cargados_por_user) {
            $enviar_append = "";
            foreach ($no_cargados_por_user as $mensaje_no_cargado) {

                $mensaje = str_replace("\n", "<br>", $mensaje_no_cargado->mensaje);

                if ($last_day_en_mensaje != date('d/m/Y')) {
                    $enviar_append = "<p class='dia'>" . date('d/m/Y') . "</p>";
                }
                $enviar_append .= "<div class='mensaje npropio'><span class='texto'>$mensaje</span><span class='hora'>$hora</span></div><div class='clear'></div>";

                // actualizamos el cargado
                ponerMensajeComoCargado($mensaje_no_cargado->id, $id_useractual);

            }
            wp_send_json(
                array('success' => true,
                    'code' => 1,
                    'mensajes_traidos' => count($no_cargados_por_user),
                    'append' => $enviar_append
                ));
        } else {
            wp_send_json(array('success' => true, 'code' => 0, 'mensaje' => 'Todo actualizado'));
        }
    }

}

add_action('wp_ajax_tiempo-real-chat', 'tiempo_real_chat');

/***********************************************************************************************************************/
function getImplicadoChat($pareja, $id_user_actual)
{  // le entra [xxx],[yyy]

    $pareja_array = explode(",", $pareja);

    if ($pareja_array[0] == "[$id_user_actual]") {
        $destinatario = $pareja_array[1];
    } else {
        $destinatario = $pareja_array[0];
    }

    $destinatario = substr($destinatario, 1, strlen($destinatario) - 2);  // le quitamos los corchetes

    return $destinatario;

}

/***********************************************************************************************************************/
function tieneChatNoLeidos($id_usuario)
{
    global $wpdb;
    $consulta = "SELECT * FROM chat WHERE (implicados LIKE '%[$id_usuario]%') AND (leido NOT LIKE '%[$id_usuario]%')";
    $nleidos = $wpdb->get_results($consulta);
    if ($nleidos) {
        return true;
    }
    return false;
}

/***********************************************************************************************************************/
function tieneChatNoLeidos_inAnuncio_entreUsuarios($id_anuncio, $id_useractual, $id_otrouser)
{
    global $wpdb;

    $cadena1 = "[$id_useractual],[$id_otrouser]";
    $cadena2 = "[$id_otrouser],[$id_useractual]";

    $consulta = "SELECT * FROM chat WHERE id_anuncio=$id_anuncio AND (( implicados='$cadena1' or implicados='$cadena2')) AND (leido NOT LIKE '%[$id_useractual]%')";
    $nleidos = $wpdb->get_results($consulta);
    if ($nleidos) {
        return true;
    }
    return false;
}

/***********************************************************************************************************************/
function getMensajesEntreUsers_byIdAnuncio($id_anuncio, $id_user1, $id_user2)
{
    global $wpdb;

    $cadena1 = "[$id_user1],[$id_user2]";
    $cadena2 = "[$id_user2],[$id_user1]";
    $diferentes_mensajes = $wpdb->get_results("SELECT *,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha_formateada FROM chat WHERE id_anuncio=$id_anuncio  AND (implicados='$cadena1' or implicados='$cadena2') ORDER BY fecha,hora");
    return $diferentes_mensajes;
}

/***********************************************************************************************************************/
function ponerMensajeComoCargado($id_mensaje, $id_usuario)
{
    global $wpdb;

    $consulta = "SELECT cargado FROM chat WHERE id='$id_mensaje'";
    $quien_cargado = $wpdb->get_row($consulta);

    if ($quien_cargado->cargado == "0") {
        $cadena_cargado = "[$id_usuario]";
        $wpdb->query("update chat set cargado='$cadena_cargado' where id=$id_mensaje");
    } else {
        if (strpos($quien_cargado->cargado, strval($id_usuario)) === false) { // no está
            $cadena_cargado = $quien_cargado->cargado . ",[$id_usuario]";
            $wpdb->query("update chat set cargado='$cadena_cargado' where id=$id_mensaje");
        }
    }

}

/***********************************************************************************************************************/
function ponerMensajeComoLeido($id_mensaje, $id_usuario)
{
    global $wpdb;

    $consulta = "SELECT leido FROM chat WHERE id='$id_mensaje'";
    $quien_leido = $wpdb->get_row($consulta);

    if ($quien_leido->leido == "0") {
        $cadena_leido = "[$id_usuario]";
        $wpdb->query("update chat set leido='$cadena_leido' where id=$id_mensaje");
    } else {
        if (strpos($quien_leido->leido, strval($id_usuario)) === false) { // no está
            $cadena_leido = $quien_leido->leido . ",[$id_usuario]";
            $wpdb->query("update chat set leido='$cadena_leido' where id=$id_mensaje");
        }
    }

}

/***********************************************************************************************************************/
function get_rol_useractual()
{
    global $current_user;
    return $current_user->roles[0];

}

/***********************************************************************************************************************/
/**
 * Esto está en profile-reservas.php
 */
function carga_calendario_al_perfil()
{
    echo '<script type="text/javascript"> DOPBSPBackEndReservations.display();</script>';
}

/***********************************************************************************************************************/
function traduce_estados_anuncio($status)
{
    if (strtolower($status) == "publish") return "Publicado";
    if (strtolower($status) == "pending") return "Pendiente";
    if (strtolower($status) == "draft") return "Borrador";
    if (strtolower($status) == "archive") return "Papelera";
    if (strtolower($status) == "reject") return "Rechazado";
}

/***********************************************************************************************************************/
function trash_post_by_user_profile($args)
{
    global $user_ID;
    // si el usuario está eliminando el anuncio, le cambiamos el rol si es author y le bajamos el ae_free_plan_usedsi solo tiene  un anuncio
    if (isset($args['post_status']) && $args['post_status'] == 'trash') {

        $userdata = get_userdata($user_ID);

        if (in_array("author", $userdata->roles)) {
            $anuncios_gratis = get_user_meta($user_ID, "ae_free_plan_used", true);
            if ($anuncios_gratis == 1) {
                $user_id = wp_update_user(array('ID' => $user_ID, 'role' => 'user'));
                update_user_meta($user_ID, "ae_free_plan_used", 0);
            }

        }

    }
}

add_action('ae_trash_post', 'trash_post_by_user_profile');

/***********************************************************************************************************************/
/**
 * El usuario elimina el anuncio desde su perfil
 */
function trashed_post_por_admin($post_id)
{

    $post = get_post($post_id);
    $user_ID = $post->post_author;
    $userdata = get_userdata($user_ID);

    if ($post->post_type == "place") {
        if (in_array("author", $userdata->roles)) {
            $anuncios_gratis = get_user_meta($user_ID, "ae_free_plan_used", true);
            if ($anuncios_gratis == 1) {
                $user_id = wp_update_user(array('ID' => $user_ID, 'role' => 'user'));
                update_user_meta($user_ID, "ae_free_plan_used", 0);
            }
        }
    }
}

add_action('trashed_post', 'trashed_post_por_admin');

/***********************************************************************************************************************/
function untrashed_post_por_admin($post_id)
{
    $post = get_post($post_id);
    $user_ID = $post->post_author;
    $userdata = get_userdata($user_ID);

    if ($post->post_type == "place") {

        if (in_array("user", $userdata->roles) || in_array("advertiser", $userdata->roles)) {
            $anuncios_gratis = get_user_meta($user_ID, "ae_free_plan_used", true);
            if ($anuncios_gratis == 0) {
                $user_id = wp_update_user(array('ID' => $user_ID, 'role' => 'author'));
                update_user_meta($user_ID, "ae_free_plan_used", 1);
            }
        }
    }
}

add_action('untrashed_post', 'untrashed_post_por_admin');

/***********************************************************************************************************************/
function get_tipo_reserva($post_id)
{
    $tipo_reserva = get_post_meta($post_id, "tipo_reserva", true);
    if (!$tipo_reserva) $tipo_reserva = "horas";

    return $tipo_reserva;
}

/***********************************************************************************************************************/
/*
 ** Eliminar el script jquery-migrate.min
*/
function dequeue_jquery_migrate(&$scripts)
{
    if (!is_admin()) {
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), '1.10.2');
    }
}

add_filter('wp_default_scripts', 'dequeue_jquery_migrate');

/***********************************************************************************************************************/
/*
 ** Al actualizar algun anuncio, comprobamos si el usuario tiene anuncios gratis, sino, le bajamos el ae_free_plan_used
 * para que pueda publicar el suyo gratis.
*/
function eliminar_ae_free_plan_used_ifempty($result, $args)
{

    $tiene_gratis = false;
    if (is_user_logged_in()) {
        global $current_user;
        if ($current_user) {
            $args = array(
                'author' => $current_user->ID, // I could also use $user_ID, right?
                'post_type' => 'place',
                'post_status' => array('publish', 'pending', 'draft', 'archive', 'reject'),
            );
            $current_user_posts = get_posts($args);

            if ($current_user_posts) {
                foreach ($current_user_posts as $anuncios_user) {
                    if ($anuncios_user->et_payment_package == "001") {
                        $tiene_gratis = true;
                        break;
                    }
                }
            }
        }
    }

    if (!$tiene_gratis) {
        update_user_meta($current_user->ID, 'ae_free_plan_used', 0);
    }
}

add_action('ae_update_place', 'eliminar_ae_free_plan_used_ifempty');

function is_blogpage()
{
    if (isset($_SERVER["REDIRECT_URL"]) && $_SERVER["REDIRECT_URL"]) {
        if (preg_match("/\/blog\//", $_SERVER["REDIRECT_URL"])) {
            return true;
        }
    }
    return false;

}

function is_categorypage()
{
    $current_term_object = get_queried_object();
    if (isset($current_term_object->taxonomy) && $current_term_object->taxonomy == "place_category") {
        return true;
    }
}


/**
 * Detecta si el usuario viene de:
 *     - App Iphone
 *     - App Android
 *     - Navegador (Sin App)
 */
function getIfApp()
{

    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false) {
        return 1;
    } else {
        if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == "com.servilisto.android.play") {
            return 2;
        }
    }
    return 0; // NO APP


}

function wp_exist_place_by_title($title, $id_except)
{
    global $wpdb;
    if (!$id_except) {
        $return = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = '" . $title . "' && post_status = 'publish' && post_type = 'place' ", 'ARRAY_N');
    } else {
        $return = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = '" . $title . "' && ID!='$id_except' && post_status = 'publish' && post_type = 'place' ", 'ARRAY_N');
    }
    if (empty($return)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Comprueba que no haya titulos repetidos a la hora de insertar un anuncio
 */
function checkIfExistTitle($titulo = null)
{

    $is_ajax = false;
    $post_id = false;

    if (isset($_POST['is_ajax'])) {
        $titulo = $_POST["post_title"];
        $is_ajax = true;
        $post_id = $_POST['post_id'];
    }

    $return = true;
    if (wp_exist_place_by_title($titulo, $post_id)) {
        $return = false;
    }

    if ($is_ajax) wp_send_json($return);
    else return $return;
}

add_action('wp_ajax_checkIfExistTitle', 'checkIfExistTitle');


/**
 * Si viene el parametro $_GET['en'] y es una pagina de categoría
 */
function filtra_provincia_en_categoria()
{
    // comprobamos que sea una pagina de categoria
    if (is_categorypage()) {
        if (isset($_REQUEST["en"]) && $_REQUEST['en']) {
            set_query_var('location', $_REQUEST['en']);
        }
    }
}

add_action('pre_get_posts', 'filtra_provincia_en_categoria');

function redirect_categoria_provincia_to_getParameter()
{

    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if (preg_match("/\/en-/", $uri_path)) {  // es una url con parametro /en-

        $new_url = str_replace("en-", "?en=", $uri_path);
        if (substr($new_url, -1) == "/") $new_url = substr($new_url, 0, strlen($new_url) - 1); // si termina en barra se lo quitamos

        wp_redirect($new_url);
        exit;
    }
}

add_action('init', 'redirect_categoria_provincia_to_getParameter');


add_action('bwp_gxs_modules_built', 'bwp_gxs_add_modules');
function bwp_gxs_add_modules()
{
    global $bwp_gxs;
    $bwp_gxs->add_module('taxonomy', 'category provincia');
}


function modificar_last_place_category_anuncios($default_uri, $native_slug, $post, $post_name, $native_uri)
{
    preg_match_all("/%__(.[^\%]+)%/", $default_uri, $custom_fields);

    if (!empty($custom_fields[1])) {
        foreach ($custom_fields[1] as $i => $custom_field) {

            if ($custom_field == "last_place_category") {  // miguel.modificacion
                $custom_field_value = wp_get_post_terms($post->ID, "place_category", array("fields" => "slugs"))[0];
            } else {
                $custom_field_value = apply_filters('permalink_manager_custom_field_value', get_post_meta($post->ID, $custom_field, true), $custom_field, $post);
            }

            // Make sure that custom field is a string
            if (!empty($custom_field_value) && is_string($custom_field_value)) {
                $default_uri = str_replace($custom_fields[0][$i], sanitize_title($custom_field_value), $default_uri);
            }
        }
    }

    return $default_uri;

}

add_filter('permalink_manager_filter_default_post_uri', 'modificar_last_place_category_anuncios', 10, 5);


/******************************************     MODIFICACIÓN YOAST     ************************************************/
/**
 * Añade [*Provincia*] de la Canonical de YOAST
 */
function anadir_canonical_provincia_yoast($string)
{
    if (isset($_REQUEST['en']) && $_REQUEST['en']) {
        $string .= "/en-" . $_REQUEST['en'] . "/";
    }
    return $string;
}

add_filter('wpseo_canonical', 'anadir_canonical_provincia_yoast');

/**
 * Reemplaza el [*Provincia*] del titulo de YOAST
 */
function modificar_titulo_yoast($string)
{
    if (isset($_REQUEST['en']) && $_REQUEST['en']) {
        $string = str_replace("[*Provincia*]", " en " . ucfirst($_REQUEST['en']), $string);
    } else {
        $string = str_replace("[*Provincia*]", "", $string);
    }
    return $string;
}

add_filter('wpseo_title', 'modificar_titulo_yoast');

/**
 * Reemplaza el [*Provincia*] de la Descripción de YOAST
 */
function modificar_desc_yoast($string)
{
    if (isset($_REQUEST['en']) && $_REQUEST['en']) {
        $string = str_replace("[*Provincia*]", " en " . ucfirst($_REQUEST['en']), $string);
    } else {
        $string = str_replace("[*Provincia*]", "", $string);
    }
    return $string;
}

add_filter('wpseo_metadesc', 'modificar_desc_yoast');

/**
 * Inserta meta tags personalizados
 */
function add_meta_tags()
{
    global $post;

    $keywords = @get_post_meta($post->ID, '_yoast_wpseo_focuskw', true);
    if (is_blogpage()) { // si es blog es especial (rectificamos)
        $keywords = get_post_meta(get_option('page_for_posts'), '_yoast_wpseo_focuskw', true);
    }
    if (is_categorypage()) {
        $term = get_queried_object();
        $keywords = WPSEO_Taxonomy_Meta::get_term_meta($term->term_id, $term->taxonomy)["wpseo_focuskw"];
    }

    if (isset($_REQUEST['en']) && $_REQUEST['en']) {
        $keywords = str_replace("[*Provincia*]", " en " . ucfirst($_REQUEST['en']), $keywords);
    } else {
        $keywords = str_replace("[*Provincia*]", "", $keywords);
    }


    if ($keywords) echo '<meta name="keywords" content="' . $keywords . '" />' . "\n";
}

add_action('wp_head', 'add_meta_tags', 2);