<?php

/**
 * Class AE Payment is an abstract class handle function releate to payment setup , process payment
 *
 * @since 1.0
 * @package AE Payment
 * @category payment
 *
 * @property Array $no_priv_ajax Contain all no private ajax action name
 * @property Array $priv_ajax All private ajax action name
 *
 * @author Dakachi
 */
abstract class AE_Payment extends AE_Base
{

    /**
     * no private ajax
     */
    protected $no_priv_ajax = array();

    // private ajax
    protected $priv_ajax = array(
        'et-setup-payment'
    );

    function __construct()
    {

        $this->init_ajax();
    }

    /**
     * init ajax to process payment
     *
     * @return null
     *
     * @since 1.0
     * @author Dakachi
     */
    function init_ajax()
    {
        foreach ($this->no_priv_ajax as $key => $value) {
            $function = str_replace('et-', '', $value);
            $function = str_replace('-', '_', $function);
            $this->add_ajax($value, $function);
        }

        foreach ($this->priv_ajax as $key => $value) {
            $function = str_replace('et-', '', $value);
            $function = str_replace('-', '_', $function);
            $this->add_ajax($value, $function, true, false);
        }

        // catch action ae_save_option to update payment api settings
        $this->add_action('ae_save_option', 'update_payment_settings', 10, 2);

        // process payment
        $this->add_action('ae_process_payment_action', 'process_payment', 10, 2);
    }

    /**
     * callback update option for Paypal, 2checkout, cash api settings
     *
     * @param String $name The payment gateway name
     * @param String $value The payment gateway api value
     *
     * @return  null
     *
     * @since 1.0
     * @author Dakachi
     */
    public function update_payment_settings($name, $value)
    {

        // update paypal api settings
        if ($name == 'paypal') {
            ET_Paypal::set_api($value);
        }

        // update 2checkout api settings
        if ($name == '2checkout') {
            ET_2CO::set_api($value);
        }

        // update 2checkout api settings
        if ($name == 'cash') {
            ET_Cash::set_message($value['cash_message']);
        }
    }

    /**
     * abstract function get payment package for submit place
     * @since 1.0
     * @author Dakachi <ledd@youngworld.vn>
     */
    abstract public function get_plans();

    /**
     * catch action ae_process_payment_action and update post data after payment success
     *
     * @param $payment_return
     * @param $data
     *
     * @return array $payment_return
     *
     * @since 1.0
     * @author dakachi <ledd@youngworld.vn>
     */
    function process_payment($payment_return, $data)
    {
        global $ae_post_factory;
        // process user order after pay
        do_action('ae_select_process_payment', $payment_return, $data);
        $this->member_payment_process($payment_return, $data);
        //  if not exist post id
        if (!isset($data['ad_id']) || !$data['ad_id']) return $payment_return;

        $options = AE_Options::get_instance();
        $ad_id = $data['ad_id'];

        extract($data);
        if (!$payment_return['ACK']) {
            return 0;
        }

        $post = get_post($ad_id);
        /**
         * get object by post type and convert
         */
        $post_obj = $ae_post_factory->get($post->post_type);
        $ad = $post_obj->convert($post);

        if ($payment_type != 'usePackage') {
            /**
             * update seller package quantity
             */
            AE_Package::update_package_data($ad->et_payment_package, $ad->post_author);

            $post_data = array(
                'ID' => $ad_id,
                'post_status' => 'publish'
            );

            if (!is_super_admin()) {
                $post_data = array(
                    'ID' => $ad_id,
                    'post_status' => 'pending'
                );
            }
            /*Send email for admin if pending payment*/

            do_action('ae_after_process_payment', $post_data);
        }

        // disable pending and auto publish post


        /** @var string $payment_type */
        if (!$options->use_pending && ('cash' != $payment_type)) {
            $post_data['post_status'] = 'publish';
        }

        // when buy new package will got and order
        if (isset($data['order_id'])) {

            // update post order id
            update_post_meta($ad_id, 'et_ad_order', $data['order_id']);
        }

        // Change Status Publish places that posted by Admin
        if (is_super_admin()) {
            wp_update_post(array(
                'ID' => $ad_id,
                'post_status' => 'publish'
            ));
        }

        switch ($payment_type) {
            case 'cash':
                wp_update_post($post_data);

                // update unpaid payment
                update_post_meta($ad_id, 'et_paid', 0);
                return $payment_return;
            case 'free':
                wp_update_post($post_data);

                // update free payment
                update_post_meta($ad_id, 'et_paid', 2);
                return $payment_return;
            case 'usePackage':
                return $payment_return;
            default:

                // code...
                break;
        }

        /**
         * payment succeed
         */
        if ('PENDING' != strtoupper($payment_return['payment_status'])) {
            wp_update_post($post_data);

            // paid
            update_post_meta($ad_id, 'et_paid', 1);
        } else {

            /**
             * in some case the payment will be pending
             */
            wp_update_post(array(
                'ID' => $ad_id,
                'post_status' => 'pending'
            ));

            // unpaid
            update_post_meta($ad_id, 'et_paid', 0);
        }

        if (is_super_admin() || !ae_get_option('use_pending', false)) {
            do_action('ae_after_process_payment_by_admin', $ad_id);
        }

        return $payment_return;
    }

    /**
     *
     * @param snippet
     * @return snippet
     * @since snippet
     * @package snippet
     * @category snippet
     * @author Dakachi
     */
    function setup_orderdata($data)
    {
        global $user_ID;

        // remember to check isset or empty here
        $adID = isset($data['ID']) ? $data['ID'] : '';
        $author = isset($data['author']) ? $data['author'] : $user_ID;
        $packageID = isset($data['packageID']) ? $data['packageID'] : '';
        /*$paymentType = isset($data['paymentType']) ? $data['paymentType'] : ''; Miguel*/
        $paymentType = isset($data['tipo-pago']) ? $data['tipo-pago'] : '';
        $errors = array();

        // job id invalid
        if ($adID) {

            // author does not authorize job
            $job = get_post($adID);

            if ($author != $job->post_author && !current_user_can('manage_options')) {
                $author_error = __("Post author information is incorrect!", ET_DOMAIN);
                $errors[] = $author_error;
            }
        }

        // input data error
        if (!empty($errors)) {
            $response = array(
                'success' => false,
                'errors' => $errors
            );

            wp_send_json($response);
        }

        ////////////////////////////////////////////////
        ////////////// process payment//////////////////
        ////////////////////////////////////////////////

        $order_data = array(
            'payer' => $author,
            'total' => '',
            'status' => 'draft',
            'payment' => $paymentType,
            'paid_date' => '',
            'payment_plan' => $packageID,
            'post_parent' => $adID
        );

        return $order_data;
    }

    /**
     * catch ajax et-setup-payment and process order generate json send back to clien
     * json data: array
     *             - 'success' => $nvp['ACK']
     *             - 'data' => array('data' , 'url'  => 'the payment gateway url')
     *             - 'paymentType' => $paymentType
     *
     * @package AE Payment
     * @category payment
     *
     * @since  1.0
     * @author  Dakachi
     */
    function setup_payment()
    {
        $order_data = $this->setup_orderdata($_POST);
        $plans = $this->get_plans();
        if (empty($plans)) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("There is no payment plan.", ET_DOMAIN)
            ));
        }
        $adID = isset($_POST['ID']) ? $_POST['ID'] : '';
        //$author = isset($_POST['author']) ? $_POST['author'] : $user_ID;
        $packageID = isset($_POST['packageID']) ? $_POST['packageID'] : '';
        /*$paymentType = isset($_POST['paymentType']) ? $_POST['paymentType'] : ''; Miguel*/
        $paymentType = isset($_POST['tipo-pago']) ? $_POST['tipo-pago'] : '';

        foreach ($plans as $key => $value) {
            if ($value->sku == $packageID) {
                $plan = $value;
                break;
            }
        }
        $plan->ID = $plan->sku;
        // if($adID) $plan->post_id = $adID;

        // $ship    =   array( 'street_address' => isset($company_location['full_location']) ? $company_location['full_location'] : __("No location", ET_DOMAIN));
        // filter shipping
        $ship = apply_filters('ae_payment_ship', array(), $order_data, $_POST);

        /**
         * filter order data
         *
         * @param Array $order_data
         * @param Array $_POST Client submitted data
         *
         * @since  1.0
         * @author  Dakachi
         */
        $order_data = apply_filters('ae_payment_order_data', $order_data, $_POST);
        // insert order into database
        $order = new AE_Order($order_data, $ship);
        $order->add_product((array)$plan);
        $order_data = $order->generate_data_to_pay();

        // write session
        et_write_session('order_id', $order_data['ID']);
        et_write_session('ad_id', $adID);

        /*********************************************************************************************** Miguel */
        /*
         *  $arg = apply_filters('ae_payment_links', array(
            'return' => et_get_page_link('process-payment') ,
            'cancel' => et_get_page_link('process-payment')
        ));
         */
        $arg = apply_filters('ae_payment_links', array(
            'return' => get_permalink(get_page_by_title('Pasarela')),
            'cancel' => get_permalink(get_page_by_title('Pasarela'))
        ));
        /*********************************************************************************************************/

        /**
         * process payment
         */
        $paymentType_raw = $paymentType;
        $paymentType = strtoupper($paymentType);

        /**
         * factory create payment visitor
         */
        $visitor = AE_Payment_Factory::createPaymentVisitor($paymentType, $order, $paymentType_raw);

        // setup visitor setting
        $visitor->set_settings($arg);

        // accept visitor process payment
        $nvp = $order->accept($visitor);
        if ($nvp['ACK']) {
            $response = array(
                'success' => $nvp['ACK'],
                'data' => $nvp,
                /*'paymentType' => $paymentType*/
                'tipo-pago' => $paymentType
            );
        } else {
            $response = array(
                'success' => false,
               /* 'paymentType' => $paymentType, Miguel*/
                'tipo-pago' => $paymentType,
                'msg' => __("Invalid payment gateway", ET_DOMAIN)
            );
        }
        /**
         * filter $response send to client after process payment
         *
         * @param Array $response
         * @param String $paymentType The payment gateway user select
         * @param Array $order The order data
         *
         * @package  AE Payment
         * @category payment
         *
         * @since  1.0
         * @author  Dakachi
         */
        $response = apply_filters('ae_setup_payment', $response, $paymentType, $order);
        wp_send_json($response);
    }

    /**
     * action process payment update seller order data
     * @param Array $payment_return The payment return data
     * @param Array $data Order data and payment type
     * @return bool true/false
     * @since  1.0
     * @author  Dakachi
     *
     * @package AE Payment
     */
    public function member_payment_process($payment_return, $data)
    {
        extract($data);
        if (!$payment_return['ACK']) return false;
        if ($payment_type == 'free') return false;

        if ($payment_type == 'usePackage') {
            return false;
        }

        $order_pay = $data['order']->get_order_data();

        // update user current order data associate with package
        self::update_current_order($order_pay['payer'], $order_pay['payment_package'], $data['order_id']);
        AE_Package::add_package_data($order_pay['payment_package'], $order_pay['payer']);

        /**
         * do action after process user order
         * @param $order_pay ['payer'] the user id
         * @param $data The order data
         */
        do_action('ae_member_process_order', $order_pay['payer'], $order_pay);
        return true;
    }

    /**
     * return the order id user paid for the package
     * @param integer $user_id The user ID
     * @param integer $package_id The package id want to get order
     *
     * @return array $oder
     *
     * @since  1.0
     * @author  Dakachi
     */
    public static function get_current_order($user_id, $package_id = '')
    {
        $order = get_user_meta($user_id, 'ae_member_current_order', true);
        if ($package_id == '') return $order;
        else return (isset($order[$package_id]) ? $order[$package_id] : '');
    }

    /**
     * update user current order
     * @param $user_id the user pay id
     * @param $group array of order and package 'sku' => 'order_id'
     *
     * @return  null
     *
     * @since 1.0
     * @author Dakachi
     */
    public static function set_current_order($user_id, $group)
    {
        update_user_meta($user_id, 'ae_member_current_order', $group);
    }

    /**
     *  update order id user paid for package
     * @param Integer $user_id The user ID
     * @param Integer $package The package ID
     * @param Integer $order_id The order ID want to update
     * @return bool
     */
    public static function update_current_order($user_id, $package, $order_id)
    {
        $group = self::get_current_order($user_id);

        $group[$package] = $order_id;

        return self::set_current_order($user_id, $group);
    }
}