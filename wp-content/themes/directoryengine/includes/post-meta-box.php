<?php
/**
 * class AE_PostMeta
 * render and control post type metabox for this post type
 * @author Dakachi
 * @package AE
 * @version 1.0
 */
class AE_PostMeta extends AE_Base
{
    /**
     * @param string $post_type
     */
    public function __construct($post_type = 'place') {
        $this->post_type = $post_type;
        $this->nonce = 'et_nonce_' . $post_type;

        /**
         * add places metabox
         */
        if (ae_user_can('edit_posts')) {
            add_action('add_meta_boxes', array(
                $this,
                'add_meta_boxes'
            ));

            $this->add_action('save_post', 'save_meta_fields');

            if ((basename($_SERVER['SCRIPT_FILENAME']) == 'post.php' && isset($_GET['action']) && $_GET['action'] == 'edit')
                || (basename($_SERVER['SCRIPT_FILENAME']) == 'post-new.php' && (isset($_GET['post_type']) && $_GET['post_type'] == $this->post_type))
            ) {
                add_action('admin_head', array(
                    $this,
                    'add_meta_script'
                ));
                add_filter('wp_dropdown_users', array(
                    $this,
                    'wp_dropdown_users'
                ));
            }
        }


    }

    /**
     * All about meta boxes in backend
     */
    public function add_meta_boxes() {
        add_meta_box('place_info', __('Places Information', ET_DOMAIN) , array(
            $this,
            'meta_view'
        ) , $this->post_type, 'normal', 'high');
    }

    /**
     * add script for metabox
     * control address with map, date pick for date input
     * @author Dakachi
     * @since 1.0
     */
    public function add_meta_script() {

        global $wp_scripts;
        $ui = $wp_scripts->query('jquery-ui-core');
        $url = "//code.jquery.com/ui/{$ui->ver}/themes/smoothness/jquery-ui.css";
        wp_enqueue_style('jquery-ui-redmond', $url, false, $ui->ver);

        wp_enqueue_script('jquery');

        // jquery auto complete for search users
        wp_enqueue_script('jquery-ui-autocomplete');

        // date pick for date input
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-datepicker');

        // google map api
        wp_enqueue_script('et-googlemap-api');

        // gmap library
        $this->add_existed_script('gmap');

        wp_enqueue_script('edit-ad', TEMPLATEURL . '/js/edit-post.js', array(
            'jquery',
            'jquery-ui-autocomplete',
            'jquery-ui-datepicker',
            'gmap'
        ));

        $replace = array(
            'd' => 'dd',

            // two digi date
            'j' => 'd',

            // no leading zero date
            'm' => 'mm',

            // two digi month
            'n' => 'm',

            // no leading zero month
            'l' => 'DD',

            // date name long
            'D' => 'D',

            // date name short
            'F' => 'MM',

            // month name long
            'M' => 'M',

            // month name shỏrt
            'Y' => 'yy',

            // 4 digits year
            'y' => 'y',
        );
        $date_format = str_replace(array_keys($replace) , array_values($replace) , get_option('date_format'));

        wp_localize_script('edit-ad', 'edit_ad', array(
            'dateFormat' => $date_format
        ));
    }

    /**
     * filter wp dropdown users function
     * @param $output
     * @return null|string
     */
    public function wp_dropdown_users($output) {
        global $user_ID;
        $post = false;
        if( isset($_REQUEST['post']) ) {
            $post_id = $_REQUEST['post'];
            $post = get_post($post_id);
        }
        /**
         * remove filter to prevent loop
         */
        remove_filter('wp_dropdown_users', array(
            $this,
            'wp_dropdown_users'
        ));

        $output = wp_dropdown_users(array(
            'who' => '',
            'name' => 'post_author_override',
            'selected' => ($post && isset($post->ID) ) ? $post->post_author : $user_ID,
            'include_selected' => true,
            'echo' => false
        ));

        return $output;
    }

    /**
     * render post type meta view
     * @author Dakachi
     * @since 1.0
     * @package AE
     * @param array $post
     */
    public function meta_view($post) {
        global $ae_post_factory;
        $ae_pack = $ae_post_factory->get('pack');

        $payment_package = $ae_pack->fetch();


        $place_obj = $ae_post_factory->get($this->post_type);
        $ad = (array)$place_obj->convert($post);
?>
        <table class="form-table ad-info">
            <input type="hidden" name="_et_nonce" value="<?php echo wp_create_nonce($this->nonce) ?>" />
            <tbody>
            <tr valign="top">
                <th scope="row"><label for=""><strong><?php _e("Packages:", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <?php
                    if(!empty($payment_package)) {
                    foreach ($payment_package as $key => $plan) { ?>
                    <p>
                        <input data-duration="<?php echo $plan->et_duration ?>" class="ad-package" type="radio" id="et_ad_package_<?php
                            echo $plan->sku; ?>" name="et_payment_package" value="<?php
                            echo $plan->sku; ?>" <?php
                            checked($plan->sku, $ad['et_payment_package'], true); ?>
                        />
                        <label for="et_ad_package_<?php
                            echo $plan->sku; ?>"><strong><?php
                            echo $plan->post_title; ?>  <?php
                            echo $plan->et_price; ae_currency_sign(); ?></strong> - <?php
                            echo $plan->backend_text; ?>
                        </label>
                    </p>
                    <?php
                    }} ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="et_featured"><strong><?php _e("Featured post:", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <input type="hidden" value="0" name="et_featured" />
                    <input value="1"  name="et_featured" type="checkbox" id="et_featured" <?php checked(1, $ad['et_featured'], true); ?> />
                    <p class="description"><label for="et_featured" ><?php _e("Make this post featured in listing.", ET_DOMAIN); ?></label></p>
                </td>

            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="et_claimable">
                        <strong><?php _e("Claim Request:", ET_DOMAIN); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="hidden" value="0" name="et_claimable" />
                    <input type="hidden" value="0" name="et_claim_approve" />
                    <input value="1"  name="et_claimable" type="checkbox" id="et_claimable" <?php checked(1, $ad['et_claimable'], true); ?> />
                    <p class="description">
                        <label for="et_claimable" >
                            <?php _e("Make this place is claimable for users.", ET_DOMAIN); ?>
                        </label>
                    </p>
                </td>

            </tr>

            <tr valign="top">
                <th scope="row"><label for="et_expired_date"><strong><?php _e("Expired Date:", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <input  name="et_expired_date" type="text" id="et_expired_date" value="<?php echo $ad['et_expired_date'] ?>" class="regular-text" />
                    <p class="description"><?php _e("Specify a date when ad will be archived.", ET_DOMAIN); ?></p>
                </td>

            </tr>
            <tr valign="top">
                <th scope="row"><label for="et_full_location"><strong><?php _e("Address details:", ET_DOMAIN); ?></strong> </label></th>
                <td>
                    <input name="et_full_location" type="text" id="address" value="<?php echo $ad['et_full_location'] ?>" class="regular-text ltr">
                    <p class="description"><?php _e("This address is used for contact purpose.", ET_DOMAIN); ?></p>
                    <label for="et_location_lat"><strong><?php _e("latitude:", ET_DOMAIN); ?></strong> </label><br>
                    <input type="text" name="et_location_lat" id="et_location_lat" value="<?php echo $ad['et_location_lat'] ?>" class="regular-text ltr"><br>
                    <label for="et_location_lng"><strong><?php _e("longitude:", ET_DOMAIN); ?></strong> </label><br>
                    <input type="text" name="et_location_lng" id="et_location_lng" value="<?php echo $ad['et_location_lng'] ?>" class="regular-text ltr" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="et_fb_url"><strong><?php _e("FACEBOOK", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <input  name="et_fb_url" type="text" id="et_fb_url" value="<?php echo $ad['et_fb_url'] ?>" class="regular-text" />
                    <p class="description"><?php _e("URL to your shop's Facebook page", ET_DOMAIN); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="et_url"><strong><?php _e("WEBSITE", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <input  name="et_url" type="text" id="et_url" value="<?php echo $ad['et_url'] ?>" class="regular-text" />
                    <p class="description"><?php _e("Your place's website url", ET_DOMAIN); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="et_google_url"><strong><?php _e("GOOGLE PLUS", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <input  name="et_google_url" type="text" id="et_google_url" value="<?php echo $ad['et_google_url'] ?>" class="regular-text" />
                    <p class="description"><?php _e("Your place's goole plus url", ET_DOMAIN); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="et_phone"><strong><?php _e("PHONE", ET_DOMAIN); ?></strong></label></th>
                <td>
                    <input  name="et_phone" type="text" id="et_phone" value="<?php echo $ad['et_phone'] ?>" class="regular-text" />
                    <p class="description"><?php _e("Your place's contact phone", ET_DOMAIN); ?></p>
                </td>
            </tr>
            <?php do_action('et_meta_fields', $ad); ?>
            </tbody>
        </table>
        <?php

        // print users list
        // $users = get_users();
        $template = array();
        // foreach ($users as $user) {
        //     $template[] = array(
        //         'value' => $user->ID,
        //         'label' => $user->display_name
        //     );
        // }
?>
        <script type="text/template" id="et_users">
            <?php echo json_encode($template); ?>
        </script>
    <?php
    }

    /**
     * When the post is saved, saves our custom data.
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta_fields($post_id) {

        if (!isset($_POST['_et_nonce']) || !wp_verify_nonce($_POST['_et_nonce'], $this->nonce)) return;
        unset($_POST['_et_nonce']);

        // cancel if current post isn't job
        if (!isset($_POST['post_type']) || $_POST['post_type'] != $this->post_type) return;

        global $ae_post_factory;
        $ce_ad = $ae_post_factory->get($this->post_type);

        /**
         * check expired date
         */
        if (isset($_POST['et_expired_date']) && $_POST['et_expired_date'] == '') {
            unset($_POST['et_expired_date']);
        } else {
            $_POST['et_expired_date'] = date('Y-m-d h:i:s', strtotime($_POST['et_expired_date']));
        }

        $request = $_POST;

        $request['ID'] = $_POST['post_ID'];
        $request['method'] = 'update';

        /**
         * sync post data
         */
        // $ce_ad->sync($request);
        // update place expired date
        if(isset($request['et_expired_date'])) {
            update_post_meta($request['ID'], 'et_expired_date', $request['et_expired_date']);
        }
        // update place location
        update_post_meta($request['ID'], 'et_full_location', $request['et_full_location']);
        update_post_meta($request['ID'], 'et_location_lat', $request['et_location_lat']);
        update_post_meta($request['ID'], 'et_location_lng', $request['et_location_lng']);
        if ( isset($request['et_url']) )
            update_post_meta($request['ID'], 'et_url', $request['et_url']);
        if( isset($request['et_fb_url']) )
            update_post_meta($request['ID'], 'et_fb_url', $request['et_fb_url']);
        if( isset($request['et_phone']) )
            update_post_meta($request['ID'], 'et_phone', $request['et_phone']);

        if(isset($request['et_payment_package'])) {
            // update payment package
            update_post_meta($request['ID'], 'et_payment_package', $request['et_payment_package']);
            // update place to permanent
            $pack = $ae_post_factory->get('pack');

            $package = $pack->get($request['et_payment_package']);

            // delete
            if ($package->et_not_duration == 1) {
                delete_post_meta($request['ID'], 'et_expired_date');
            }
        }
        // update featured
        update_post_meta($request['ID'], 'et_featured', $request['et_featured']);
        // update claim
        update_post_meta($request['ID'], 'et_claimable', $request['et_claimable']);
        // update claim approve
        update_post_meta($request['ID'], 'et_claim_approve', $request['et_claim_approve']);



        return ;
        /**
         * check post order
         */
        $order = get_post_meta($post_id, 'et_ad_order', true);
        if ($order) {

            /**
             * update order status
             */
            wp_update_post(array(
                'ID' => $order,
                'post_status' => 'publish'
            ));
        }
    }
}
