<?php

/**
 * this file contain all functions related to theme
 * enqueue scripts, print style
 * ajax action
 * update post, user
 */

/**
 *
 * Class Handle User Actions
 * @param int $result : user id
 * @author Dakachi
 * @version 1.0
 * @copyright enginethemes.com team
 * @package white panda
 *
 *
 */
class AE_User_Front_Actions extends AE_Base
{
    /**
     * construct AE_User_Front_Actions
     * @param AE_Users $user
     */
    function __construct(AE_Users $user)
    {
        $this->user = $user;
        $this->mail = AE_Mailing::get_instance();
        // $this->add_action('ae_after_confirm_user', 'confirm', 10, 1);
        $this->add_action('ae_insert_user', 'after_register');
        $this->add_action('ae_user_forgot', 'user_forgot', 10, 2);
        $this->add_action('ae_user_inbox', 'user_inbox', 10, 2);
        $this->add_action('ae_upload_image', 'change_avatar', 10, 2);
        $this->add_action('ae_after_report', 'after_report');
        $this->add_ajax('ae_claim_place', 'ae_claim_place');
    }

    function ae_claim_place()
    {

        $request = $_REQUEST['content'];
        //check if action is request
        if ($request['claim_action'] == "request") {
            $users = get_users(array(
                'role' => 'administrator'
            ));

            if (!empty($users)) {
                foreach ($users as $user) {
                    $this->mail->claim_mail($user->user_email, $request);
                }
            }
            // update place meta
            $success = update_post_meta($request['place_id'], 'et_claim_info', $request);
            if (!is_wp_error($success)) {
                wp_send_json(array(
                    'success' => true,
                    'msg' => __('Your request has been sent successfully, we\'ll contact you shortly!', ET_DOMAIN)
                ));
            } else {
                wp_send_json(array(
                    'success' => false,
                    'msg' => __('An error occurs, try again later!', ET_DOMAIN)
                ));
            }
            //else if action is approve
        } else if ($request['claim_action'] == "approve") {

            $user = get_user_by('id', $request['user_request']);
            $success = wp_update_post(array(
                'ID' => $request['place_id'],
                'post_author' => $request['user_request']
            ));
            update_post_meta($request['place_id'], 'et_claim_approve', 0);
            update_post_meta($request['place_id'], 'et_claimable', 0);
            update_post_meta($request['place_id'], 'et_claim_info', null);
            if (!is_wp_error($success)) {
                //send mail to user
                $this->mail->approve_claim_mail($user->user_email, $request);
                wp_send_json(array(
                    'success' => true,
                    'msg' => __('This claim has been approved successfully!', ET_DOMAIN)
                ));
            } else {
                wp_send_json(array(
                    'success' => false,
                    'msg' => __('An error occurs, try again later!', ET_DOMAIN)
                ));
            }
        } else {

            $user = get_user_by('id', $request['user_request']);
            update_post_meta($request['place_id'], 'et_claim_approve', 0);
            $success = update_post_meta($request['place_id'], 'et_claim_info', array());

            if (!is_wp_error($success)) {
                //send mail to user
                $this->mail->reject_claim_mail($user->user_email, $request);
                wp_send_json(array(
                    'success' => true,
                    'msg' => __('This claim has been rejected successfully!', ET_DOMAIN)
                ));
            } else {
                wp_send_json(array(
                    'success' => false,
                    'msg' => __('An error occurs, try again later!', ET_DOMAIN)
                ));
            }
        }
    }

    /**
     * @param $request
     */
    function after_report($request)
    {
        $users = get_users(array(
            'role' => 'administrator'
        ));
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->mail->report_mail($user->user_email, $request);
            }
        }
    }

    /**
     * confirm user
     * @param $user_id
     */
    function confirm($user_id)
    {
        if (isset($_GET['act']) && $_GET['act'] == "confirm" && $_GET['key']) {
            if ($user_id) {
                $this->mail->confirmed_mail($user_id);
            }
        }
    }

    /**
     * send private message between 2 users
     * @param $author
     * @param $message
     */
    function user_inbox($author, $message)
    {
        global $reply_to;
        $current_user = wp_get_current_user();
        $reply_to = $current_user->user_email;
        $this->mail->inbox_mail($author, $message);
    }

    /**
     * send email forgot to user
     * @param $result
     * @param $key
     */
    function user_forgot($result, $key)
    {

        /* === Send Email Forgot === */
        $this->mail->forgot_mail($result, $key);
    }
    /**
     * check if confirm email is active
     * update user status
     * @param $result
     */
    function after_register($result)
    {
        $user = new WP_User($result);

        // add key confirm for user
        if (ae_get_option('user_confirm')) {
            update_user_meta($result, 'register_status', 'unconfirm');
            update_user_meta($result, 'key_confirm', md5($user->user_email));
        }



        /* === Send Email Register === */
        $this->mail->register_mail($result);
    }

    /**
     * update user avatar
     * @param $attach_data
     * @param $data
     */
    public function change_avatar($attach_data, $data)
    {
        if (!isset($data['author'])) return;
        $ae_users = AE_Users::get_instance();

        //update user avatar

        $user = $ae_users->update(array(
            'ID' => $data['author'],
            'et_avatar' => $attach_data['attach_id'],
            'et_avatar_url' => $attach_data['thumbnail'][0]
        ));
    }
}

add_filter('de_filter_video', 'de_filter_video');
/**
 * filter youtube video url for display in single place header
 * @param $video_url
 * @return string
 */
function de_filter_video($video_url)
{
    $query_string = array();
    parse_str(parse_url($video_url, PHP_URL_QUERY), $query_string);
    if (isset($query_string["v"])) {
        $id = $query_string["v"];
        return '//www.youtube.com/embed/' . $id;
    }

    $url = parse_url($video_url);
    if ($url['host'] === 'vimeo.com' || $url['host'] === 'www.vimeo.com') {
        $pattern = "/(https?:\/\/)?(www\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/";
        preg_match($pattern, $video_url, $match);

        if ($match) {
            return '//player.vimeo.com/video/' . $match[(count($match) - 1)];
        }
    }

    return $video_url;
}


/**
 * Function add filter orderby post status
 *
 * @param $orderby
 * @return string
 */
function order_by_post_status($orderby)
{
    global $wpdb;
    $orderby = " case {$wpdb->posts}.post_status
                         when 'reject' then 0 
                         when 'pending' then 1
                         when 'publish' then 2
                         when 'draft' then 3
                         when 'archive' then 4
                         end, 
            {$wpdb->posts}.post_date DESC";
    return $orderby;
}

/**
 *
 * Print static texts to frontend
 *
 *
 */
function de_static_texts()
{
    return array(
        'form_auth' => array(
            'error_msg' => __("Please fill out all fields required.", ET_DOMAIN),
            'error_user' => __("Please enter your user name.", ET_DOMAIN),
            'error_email' => __("Please enter a valid email address.", ET_DOMAIN),
            'error_username' => __("Please enter a valid username.", ET_DOMAIN),
            'error_repass' => __("Please enter the same password as above.", ET_DOMAIN),
            'error_url' => __("Please enter a valid URL.", ET_DOMAIN),
            'error_cb' => __("You must accept the term & privacy.", ET_DOMAIN),
        ),
        'texts' => array(
            'require_login' => __("You must be logged in to perform this action.", ET_DOMAIN),
            'enought_points' => __("You don't have enought points to perform this action.", ET_DOMAIN),
            'create_topic' => __("Create Topic", ET_DOMAIN),
            'upload_images' => __("Upload Images", ET_DOMAIN),
            'no_file_choose' => __("No file chosen.", ET_DOMAIN),
            'require_tags' => __("Please insert at least one tag.", ET_DOMAIN),
            'add_comment' => __("Add comment", ET_DOMAIN),
            'cancel' => __("Cancel", ET_DOMAIN),
            'sign_up' => __("Sign Up", ET_DOMAIN),
            'sign_in' => __("Sign In", ET_DOMAIN),
            'accept_txt' => __("Accept", ET_DOMAIN),
            'best_ans_txt' => __("Best answer", ET_DOMAIN),
            'forgotpass' => __("Forgot Password", ET_DOMAIN),
            'close_tab' => __("You have made some changes which you might want to save.", ET_DOMAIN),
            'request_geo' => "Ha rechazado la solicitud de Geolocalización. Para utilizar esta función, habilítela.",
            'location_unavailable' => __("Location information is unavailable.", ET_DOMAIN),
            'request_time_out' => __("The request to get user location timed out.", ET_DOMAIN),
            'error_occurred' => __("An unknown error occurred.", ET_DOMAIN),
            'none' => __('None', ET_DOMAIN),
        )
    );
}

/**
 * Check plugin is active or not
 * @param $plugin
 * @return bool
 */
function et_is_plugin_active($plugin)
{
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    return is_plugin_active($plugin);
}


if (!function_exists('de_map_fetch_ads_new')) {

    /**
     * add ajax fetch post data for map view
     * @author Dakachi
     * @version 1.0
     */
    add_action('wp_ajax_de_get_map_data', 'de_map_fetch_ads_new');
    add_action('wp_ajax_nopriv_de_get_map_data', 'de_map_fetch_ads_new');
    function de_map_fetch_ads_new()
    {
        global $wpdb;

        $term = '';
        $term_in = '';
        $check_place_cat = false;
        if (isset($_REQUEST['place_category']) && $_REQUEST['place_category'] !== '') {
            $check_place_cat = true;
            $category = get_term_by('slug', $_REQUEST['place_category'], 'place_category');
        }
        if (isset($_REQUEST['query']['place_category']) && $_REQUEST['query']['place_category'] !== '') {
            $check_place_cat = true;
            $category = get_term_by('slug', $_REQUEST['query']['place_category'], 'place_category');
        }
        if ($check_place_cat) {
            if ($category && !is_wp_error($category)) {
                $terms = get_terms('place_category', array('parent' => $category->term_id));

                $term_in = "(" . $category->term_id;
                foreach ($terms as $key => $term) {
                    $term_in .= "," . $term->term_id;
                }
                $term_in .= ")";

                $term = " and t.term_id IN $term_in";
            }
        }
        if (isset($_REQUEST['query']['location']) && $_REQUEST['query']['location'] !== '') {
            $location = get_term_by('slug', $_REQUEST['query']['location'], 'location');
            $term .= " and t1.term_id = $location->term_id";
        }
        if (isset($_REQUEST['query']['s']) && $_REQUEST['query']['s'] !== '') {
            $search = parse_search($_REQUEST['query']);
            $term .= $search;
        }
        /**
         * generate nearby center
         */
        if (isset($_REQUEST['query']['center']) && $_REQUEST['query']['center'] != '') {
            $center = explode(',', $_REQUEST['query']['center']);
            $args['near_lat'] = $center[0];
            $args['near_lng'] = $center[1];
            unset($_REQUEST['query']['center']);
            $args['radius'] = $_REQUEST['query']['radius'];
            // nearby radius
            if (ae_get_option('unit_measurement', 'mile') == 'km') {
                $args['radius'] = $args['radius'] / 1.609344;
            }

            // $mile = $args['radius'];
            $near_latitude = $args['near_lat'];
            $near_longitude = $args['near_lng'];

            $calc = " AND ( ( acos( sin(A.meta_value * 0.0175) * sin( $near_latitude * 0.0175) + cos(A.meta_value * 0.0175) * cos( $near_latitude * 0.0175 ) * cos( ( {$near_longitude}  * 0.0175 ) - ( B.meta_value * 0.0175 ) ) ) * 3959 ) < {$args['radius']} )";
            $term .= $calc;
        }

        $addition_join = '';
        $addition_where = '';

        //integrate with WPML
        if (defined('ICL_LANGUAGE_CODE')) {
            $addition_join = "inner join {$wpdb->prefix}icl_translations as wpml
                            on wpml.element_id = P.ID";
            $language_code = ICL_LANGUAGE_CODE;
            $addition_where = "AND wpml.language_code = '$language_code'";
        }

        $sql = "SELECT  ID, post_title, guid as permalink , 
                        A.meta_value as latitude, 
                        B.meta_value as longitude, 
                        t.term_id as term_taxonomy_id
                        
                    FROM $wpdb->posts as P
                        {$addition_join}
                        join $wpdb->postmeta  as A
                            on  A.post_id = P.ID 
                                and  A.meta_key= 'et_location_lat' 
                                and A.meta_value != ''
                        join $wpdb->postmeta as B
                            on  B.post_id = P.ID 
                                and B.meta_key= 'et_location_lng' 
                                and B.meta_value != ''
                        inner join $wpdb->term_relationships as tr 
                            on tr.object_id = P.ID
                        inner join $wpdb->term_taxonomy as tt 
                            on  tt.term_taxonomy_id  = tr.term_taxonomy_id 
                            and tt.taxonomy = 'place_category'
                        inner join $wpdb->terms as t
                            on t.term_id = tt.term_id
                        inner join $wpdb->term_relationships as tr1 
                            on tr1.object_id = P.ID
                        inner join $wpdb->term_taxonomy as tt1 
                            on  tt1.term_taxonomy_id  = tr1.term_taxonomy_id 
                            and tt1.taxonomy = 'location'
                        inner join $wpdb->terms as t1
                            on t1.term_id = tt1.term_id

                    WHERE post_status = 'publish' {$addition_where} $term group by ID";

        $myrows = $wpdb->get_results($sql);
        wp_send_json(array('sucess' => true, 'data' => $myrows, 'term' => $term_in));
    }
}

add_action('wp_ajax_nopriv_de-get-map-info', 'de_get_map_info');
add_action('wp_ajax_de-get-map-info', 'de_get_map_info');
function de_get_map_info()
{
    if (isset($_REQUEST['ID'])) {
        $post_id = $_REQUEST['ID'];
        $permalink = get_permalink($post_id);

        if (get_the_post_thumbnail($post_id, 'small_post_thumbnail')) {
            $image = get_the_post_thumbnail($post_id, 'small_post_thumbnail');
        } else {
            $default_thumbnail_img = ae_get_option('default_thumbnail_img', '');
            if ($default_thumbnail_img && isset($default_thumbnail_img['medium'][0])) {
                $attach_id = $default_thumbnail_img['attach_id'];
                $src = wp_get_attachment_image_src($attach_id, array(
                    '270',
                    '280'
                ));
                $image = "<img src='" . $src[0] . "'/>";
            }
        }
        $content = '<div class="infowindow" ><div class="post-item"><div class="place-wrapper">
            <a href="' . $permalink . '" class="img-place">
                ' . $image . '
            </a>            
            <div class="place-detail-wrapper">
                <h2 class="title-place"><a href="' . $permalink . '">' . get_the_title($post_id) . '</a></h2>
                <span class="address-place"><i class="fa fa-map-marker"></i> ' . get_post_meta($post_id, 'et_full_location', true) . '</span>
                <div class="rate-it" data-score="' . get_post_meta($post_id, 'rating_score', true) . '"></div>
            </div>
        </div></div></div>';
        wp_send_json(array('success' => true, 'data' => array('content' => $content)));
    }
    if (isset($_REQUEST['IDs'])) {
        $content = '';
        foreach ($_REQUEST['IDs'] as $key => $post_id) {
            $permalink = get_permalink($post_id);
            if (get_the_post_thumbnail($post_id, 'small_post_thumbnail')) {
                $image = get_the_post_thumbnail($post_id, 'small_post_thumbnail');
            } else {
                $default_thumbnail_img = ae_get_option('default_thumbnail_img', '');
                if ($default_thumbnail_img && isset($default_thumbnail_img['medium'][0])) {
                    $attach_id = $default_thumbnail_img['attach_id'];
                    $src = wp_get_attachment_image_src($attach_id, array(
                        '270',
                        '280'
                    ));
                    $image = "<img src='" . $src[0] . "'/>";
                }
            }
            $content .= '<div class="infowindow" ><div class="post-item"><div class="place-wrapper">
                    <a href="' . $permalink . '" class="img-place">
                        ' . $image . '
                    </a>
                    <div class="place-detail-wrapper">
                        <h2 class="title-place"><a href="' . $permalink . '">' . get_the_title($post_id) . '</a></h2>
                        <span class="address-place"><i class="fa fa-map-marker"></i> ' . get_post_meta($post_id, 'et_full_location', true) . '</span>
                        <div class="rate-it" data-score="' . get_post_meta($post_id, 'rating_score', true) . '"></div>
                    </div>
                </div></div></div>';
        }
        wp_send_json(array('success' => true, 'data' => array('content' => $content)));
    }
    wp_send_json(array('success' => false, 'data' => array('content' => '')));

}

/**
 * filter posts where to support nearby search place
 * caculate the distance and set the condition is less than radius
 * @since 2.0.1.1
 * @author Dakachi
 */
add_filter('posts_where', 'de_search_nearby_where', 10, 2);
function de_search_nearby_where($where, $query)
{

    if (isset($query->query_vars['near_lat']) && $query->query_vars['near_lng'] && $query->query_vars['radius']) {

        $near_latitude = $query->query_vars['near_lat'];
        $near_longitude = $query->query_vars['near_lng'];

        $calc = " (acos(sin(A.meta_value * 0.0175) * sin( $near_latitude * 0.0175)
                       + cos(A.meta_value * 0.0175) * cos( $near_latitude * 0.0175) * cos(( $near_longitude  * 0.0175) - (B.meta_value * 0.0175)))
                       * 3959 )";

        // 1.609344; convert radius from km to mile
        if (ae_get_option('unit_measurement', 'mile') == 'km') {
            $query->query_vars['radius'] = $query->query_vars['radius'] / 1.609344;
        }

        $mile = $query->query_vars['radius'];

        $where .= " AND {$calc} < {$mile}";
    }
    return $where;
}

/**
 * Join with wp post meta to get place latitude and longitude, support to caculcate the nearby radius
 * @since 2.0.1.1
 * @author Dakachi
 */
add_filter('posts_join', 'de_search_nearby_join', 10, 2);
function de_search_nearby_join($join, $query)
{
    global $wpdb;
    if (isset($query->query_vars['near_lat']) && $query->query_vars['near_lng'] && $query->query_vars['radius']) {

        $mile = $query->query_vars['radius'];

        $join .= " join {$wpdb->postmeta}  as A
                        on A.post_id = $wpdb->posts.ID and  A.meta_key= 'et_location_lat' and A.meta_value != ''
                    join {$wpdb->postmeta} as B
                        on B.post_id = $wpdb->posts.ID and B.meta_key= 'et_location_lng' and B.meta_value != '' ";
    }
    return $join;
}

/**
 * Group place by ID when select nearby
 * @since 2.0.1.1
 * @author Dakachi
 */
add_filter('posts_groupby', 'de_search_nearby_groupby', 10, 2);
function de_search_nearby_groupby($groupby, $query)
{
    if (isset($query->query_vars['near_lat']) && $query->query_vars['near_lng'] && $query->query_vars['radius']) {
        $groupby = 'ID';
    }
    return $groupby;
}

/**
 * Filter post orderby
 *
 * add filter post orderby to order post by nearby distance
 *
 * @since 2.1
 *
 * @param String $orderby
 * @param WP_Query $query
 * @return String
 */
add_filter('posts_orderby', 'de_nearby_order', 10, 2);
function de_nearby_order($orderby, $query)
{
    global $wpdb;
    if (isset($query->query_vars['near_lat']) && $query->query_vars['near_lng'] && $query->query_vars['radius']) {

        $near_latitude = $query->query_vars['near_lat'];
        $near_longitude = $query->query_vars['near_lng'];

        $calc = " (acos(sin(A.meta_value * 0.0175) * sin( $near_latitude * 0.0175)
                       + cos(A.meta_value * 0.0175) * cos( $near_latitude * 0.0175) * cos(( $near_longitude  * 0.0175) - (B.meta_value * 0.0175)))
                       * 3959 )";

        $orderby = "{$calc}";
    }

    return $orderby;
}

/**
 * render directory support info js use ae_get_option 'support_phone' , 'support_email'
 * @return void
 * @author Dakachi
 */
function de_support_info()
{
    $support_phone = ae_get_option('support_phone');
    $support_email = ae_get_option('support_email');
    if ($support_email || $support_phone) {

        ?>
        <li>

            <ul class="top-info visible-lg">
                <?php if ($support_phone) { ?>
                    <li><i class="fa fa-whatsapp"></i><?php
                        echo $support_phone; ?></li>
                <?php }
                if ($support_email) { ?>
                <li><i class="fa fa-envelope"></i><?php
                    echo $support_email;
                    } ?></li>
            </ul>
        </li>
        <?php
    }
    ?>
    <?php
}

/**
 * @return support phone, email
 */
function de_support_min_info()
{
    $support_phone = ae_get_option('support_phone');
    $support_email = ae_get_option('support_email');
    if ($support_phone) {
        ?>
        <li class="top-active support-phone " data-name="phone">
            <a href="javascript:void(0)" class="support-phone-btn">
                <i class="fa fa-whatsapp"></i>
            </a>
        </li>
        <?php
    }

    if ($support_email) {
        ?>
        <li class="top-active support-email visible-md visible-xs" data-name="email"><a href="javascript:void(0)"
                                                                                        class="support-email-btn"><i
                    class="fa fa-envelope "></i></a></li>
        <?php
    }

}

/**
 * @return phone number
 */
function de_support_phone_info()
{
    $support_phone = ae_get_option('support_phone');
    $support_phone = "<a href='tel:" . $support_phone . "'>" . $support_phone . "</a>";
    if ($support_phone) {
        return $support_phone;
    }
}

/**
 * @return email
 */
function de_support_email_info()
{
    $support_email = ae_get_option('support_email');
    $support_email = "<a href='mailto:" . $support_email . "'>" . $support_email . "</a>";
    if ($support_email) {
        return $support_email;
    }
}

/**
 *show header top menu beside logo
 * @author tambh
 */
function de_header_top_menu()
{
    if (has_nav_menu('et_header_top')) {
        wp_nav_menu(array(
            'container' => 'div',
            'container_class' => 'menu-header-top-container',
            'menu_id' => 'menu-header-top',
            'theme_location' => 'et_header_top',
            'walker' => new DE_Header_Top_Walker_Nav_Menu(),
            'items_wrap' => '<ul id="menu-header-top" class="menu-header-top-desk">%3$s</ul>'
        ));
    }
}

function de_unit_text()
{
    $units_array = array('mile' => __("Miles", ET_DOMAIN), 'km' => __("Km", ET_DOMAIN));
    return $units_array[ae_get_option('unit_measurement', 'mile')];
}

/**
 * Format nice lager number
 * @param $number
 * @param int $precision
 * @return int|string
 * @internal param $ash
 */
function de_nide_number($number, $precision = 0)
{
    // strip any commas
    $number = (0 + str_replace(',', '', $number));

    // make sure it's a number...
    if (!is_numeric($number)) {
        return $number;
    }

    // filter and format it
    if ($number > 1000000000000) {
        return round(($number / 1000000000000), $precision) . 'T';
    } elseif ($number > 1000000000) {
        return round(($number / 1000000000), $precision) . 'G';
    } elseif ($number > 1000000) {
        return round(($number / 1000000), $precision) . 'M';
    } elseif ($number > 1000) {
        return round(($number / 1000), $precision) . 'K';
    }

    return number_format($number);
}

/**
 * render html status user package data
 * @author Dakachi
 * @param string $user_ID
 */
function de_author_packages_data($user_ID = '')
{
    if (!$user_ID) {
        global $user_ID;
    }

    $orders = AE_Payment::get_current_order($user_ID);
    $package_data = AE_Package::get_package_data($user_ID);

    global $ae_post_factory;
    $package_instance = $ae_post_factory->get('pack');

    if (!empty($package_data)) {
        foreach ($package_data as $key => $value) {
            if ($value['qty'] > 0) {
                $package = $package_instance->get($value['ID']);
                if (!$package || is_wp_error($package)) continue;
                $order = $orders[$value['ID']];

                $status = get_post_status($order);

                ?>
                <div class="widget-area user_payment_status">
                    <p>
                        <?php
                        if ($status == 'publish')
                            printf(__("You purchased package <strong>%s</strong> and have %d post/s left.", ET_DOMAIN), $package->post_title, $value['qty']);
                        if ($status == 'pending')
                            printf(__("You purchased package <strong>%s</strong> and have %d post/s left. Your posted ad is pending until payment.", ET_DOMAIN), $package->post_title, $value['qty']);
                        ?>
                    </p>
                </div>
                <?php
            }
        }
    }
}

/**
 * list user
 * @author Tambh
 * @param array $args
 * @return array
 */
function de_list_users($args = array())
{
// $defaults = array(
//             'orderby' => 'post_count',
//             'order' => 'DESC',
//             'number' => '',
//             'optioncount' => false,
//             'exclude_admin' => true,
//             'fields' => 'all',
//             'role' => 'author' ,
//             'number' => isset($args['number']) ? $args['number'] : false,
//             'count_total' => true 
//         );
// $args   =   wp_parse_args( $args, $defaults);    
    $user = de_get_users($args);
    return $user;
}

/**
 * Retrieve list of users matching criteria.
 *
 * @since 3.1.0
 * @uses $wpdb
 * @uses WP_User_Query See for default arguments and information.
 *
 * @param array $args Optional.
 * @return array List of users.
 */
function de_get_users($args = array())
{

    if (isset($args['search']) && '' !== $args['search']) {
        $search_string = $args['search'];
        $args['search'] = "*{$search_string}*";
        $args['search_columns'] = array(
            'user_login',
            'user_nicename',
            'display_name'
        );
    }
    $args = wp_parse_args($args);
    // $args['count_total'] = false;

    $user_search = new WP_User_Query($args);

    return (array)$user_search->get_results();
}

add_filter('ae_convert_user', 'more_user_convert');

/**
 * @param $result
 * @return mixed
 */
function more_user_convert($result)
{
    $result->id = $result->ID;

    // Check page current is 'page-list-user.php' or query have field 'page_list_user'
    if ((isset($_POST['query']['page_list_user']) && $_POST['query']['page_list_user']) || is_page_template('page-list-user.php')) {
        global $ae_post_factory, $post;
        query_posts(array('post_type' => 'place', 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC', 'author' => $result->ID, 'showposts' => -1));
        $post_arr = array();
        if (have_posts()) : while (have_posts()) : the_post();
            $ae_post = $ae_post_factory->get('place');
            $convert = $ae_post->convert($post, 'thumbnail');
            array_push($post_arr, $convert);
        endwhile;
        endif;
        $result->place_list = $post_arr;
        wp_reset_query();
    }
    return $result;
}

add_filter('comment_text', 'de_esc_js');
/**
 * @param $result
 * @return string
 */
function de_esc_js($result)
{
    return esc_js($result);
}

/**
 * check register
 * @return bool|mixed|void $re
 */
function de_check_register()
{
    $re = false;
    if (is_wp_error(MULTISITE) && MULTISITE) {
        $re = users_can_register_signup_filter();
    } else {
        $re = get_option('users_can_register', 0);
    }
    return $re;
}

/**
 * get nearby places list
 *
 * @since version 1.8.4
 *
 */
add_action('wp_ajax_nopriv_de-get-nearby-places', 'de_get_nearby_places');
add_action('wp_ajax_de-get-nearby-places', 'de_get_nearby_places');
function de_get_nearby_places()
{
    $args = array(
        'post_type' => 'place',
        'post_status' => array('publish'),
        'radius' => 10
    );
    if (isset($_REQUEST['center']) && $_REQUEST['center'] != '') {
        $args['near_lat'] = $_REQUEST['center']['latitude'];
        $args['near_lng'] = $_REQUEST['center']['longitude'];
        unset($_REQUEST['center']);
    }
    global $ae_post_factory;
    $place_obj = $ae_post_factory->get('place');
    // $search_query = $place_obj->nearbyPost($args);
    $search_query = new WP_Query($args);
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            global $post, $ae_post_factory;
            $ae_post = $ae_post_factory->get('place');
            $convert = $ae_post->convert($post, 'medium_post_thumbnail');
            $post_arr[] = $convert;
        }
        wp_reset_query();
        wp_send_json(array(
                'success' => true,
                'data' => $post_arr
            )
        );
    }
    wp_send_json(array(
            'success' => false,
            'data' => __('Get the nearby place list failed!', ET_DOMAIN)
        )
    );
}

/**
 * Render custom css for category color
 *
 * This function render css style for category color style
 *
 * @since 2.1
 * @author Dakachi
 * @return void
 */
function de_category_style()
{
    echo "<style>";

    echo '

    /**** GENERAL ****/
    a:hover, a:focus{
        color:#479edc;
    }
    a, * a, a:hover{
        outline-style:none;
        text-decoration:none;
    }
    ::-moz-selection {
     background: #b3d4fc;
     text-shadow: none;
    }
    ::selection {
        background: #b3d4fc;
        text-shadow: none;
    }
    ::-webkit-input-placeholder { /* WebKit browsers */
        color:    #5f6f81;
        font-size:13px;
        font-weight:300;
    }
    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color:    #5f6f81;
        font-size:13px;
        font-weight:300;
    }
    ::-moz-placeholder { /* Mozilla Firefox 19+ */
        color:    #5f6f81;
        font-size:13px;
        font-weight:300;
    }
    :-ms-input-placeholder { /* Internet Explorer 10+ */
        color:    #5f6f81;
        font-size:13px;
        font-weight:300;
    }
    .list-option-left-wrapper .list-option-left, ul.top-menu, ul.top-menu-right, ul.top-menu li ul.top-info, .list-option-filter, .list-places, .list-places .place-wrapper .place-detail-wrapper .rate-it, .list-share-social, .description-place-wrapper .list-gallery, .list-lastest-news, .step-content-wrapper .list-form-login, .step-content-wrapper .list-price, .option-search, .list-categories, .edit-place-option, .list-place-review, .social-list-footer, .list-news-widget, .info-address-place ul, .list-user-page-info, .list-item-place-user{
        margin:0;
        padding:0;
        list-style:none;
    }
    .list-option-left-wrapper .list-option-left li a, .list-share-social li a, #add-review input[type="submit"]:hover{
        background:#1c84d4;
    }
    .list-option-left-wrapper .list-option-left li a:hover{
        background:#5f6f81
    }
    /**** PRELOADING ****/
    .mask-color {
        background:#1b83d3;
    }

    /*.categories-wrapper, .categories-wrapper:before, .list-categories li:hover .number-categories, .categories-wrapper .categories-name, .categories-wrapper .categories-name, .categories-wrapper .icon-categories i{
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }*/
    ';
    // render cat color css
    $cat = new AE_Category(array(
        'taxonomy' => 'place_category'
    ));
    $category = $cat->getAll();

    // print style for category
    foreach ($category as $key => $value) {
        if ($value->color == '' || $value->color == '0') {
            $color = '#eb5256';
        } else {
            $color = $value->color;
        }
        $text_color = get_color_hexdec($color);
        echo '.cat-' . $value->term_id . ' .categories-wrapper{ border-color : ' . $color . '; }'; // chosens border
        echo '.cat-' . $value->term_id . ' .categories-wrapper:hover .categories-name,.cat-' . $value->term_id . ' .categories-wrapper:hover .icon-categories i
        { color : ' . $text_color . ' !important; }'; // hover text color with background color
        echo ' .cat-' . $value->term_id . ' .categories-wrapper:hover .number-categories{background-color :' . $text_color . ' !important }';
        // menu category border
        echo '.list-places .place-wrapper .img-place .cat-' . $value->term_id . ' .ribbon:after { 
            border : 10px solid ' . $color . ';
                right: -15px;
                border-left-width: 1.5em;
                border-right-color: rgba(0, 0, 0, 0);
                position:absolute;
                top:0; 
        }';

        echo '.infowindow .img-place .cat-' . $value->term_id . ' .ribbon:after, 
            .widget-features-wrapper .list-places .place-wrapper .img-place .cat-' . $value->term_id . ' .ribbon:after { 
                border : 8px solid ' . $color . '; 
                right: -15px;
                border-left-width: 1.5em;
                border-right-color: rgba(0, 0, 0, 0);
        }';

        echo '.cat-' . $value->term_id . '  .ribbon-event:after {
                content: "";
                border: 10px solid ' . $color . '; 
                z-index: -1;
                bottom: 0;
                right: -15px;
                position: absolute;
                border-left-width: 1.5em;
                border-right-color: rgba(0, 0, 0, 0);
            }';
        echo 'ul.top-menu .gn-menu li.menu-place-category-' . $value->term_id . '::after{ background: ' . $color . '; }';
        // search
        echo '.cat-' . $value->term_id . ' .categories-wrapper:before,
        .chosen-container-multi .chosen-choices li.search-choice.cat-' . $value->term_id . ' { 
            background: ' . $color . ' !important; 
            color: ' . $text_color . ' !important;;
            border-radius: 0;
            -moz-border-radius: 0;
            -webkit-border-radius: 0;
        }';

        echo '.cat-' . $value->term_id . ' .categories-wrapper:before,
        .list-places .place-wrapper .img-place .cat-' . $value->term_id . ' .ribbon ,
        .cat-' . $value->term_id . ' .ribbon-event {
            background-color : ' . $color . ';
            color : ' . $text_color . ' !important;
        }';

        if ($value->parent == 0) {
            echo '.chosen-container-multi .chosen-drop .cat-' . $value->term_id . '{ border-left: 3px ' . $color . ' solid; }';
        }

    }
    echo "</style>";
}