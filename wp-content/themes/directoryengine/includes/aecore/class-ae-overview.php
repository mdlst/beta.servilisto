<?php

/**
 * AE overview
 * show all post, payment, order status on site
 * @package AE
 * @version 1.0
 * @author Dakachi
 */
class AE_Overview extends AE_Page
{

    public function __construct($post_types, $payment = false)
    {
        parent::__construct();
        if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'et-overview') {
            $this->add_action('admin_enqueue_scripts', 'overview_scripts');
            $this->add_action('admin_print_styles', 'overview_styles');
        }

        $this->post_types = $post_types;
        $this->payment = $payment;
    }

    /**
     * render container element
     */
    function render()
    {

        echo '<div class="ae-overview">';

        $payment = $this->payment;

        $post_types = $this->post_types;

        $daily_data = array();
        $weekly_data = array();
        $monthly_data = array();

        foreach ($post_types as $post_type) {
            $obj = get_post_type_object($post_type);

            $label_mostrar = $obj->labels->name;
            if ($label_mostrar == "Anuncio") {
                $label_mostrar = "Altas anuncios";
            }

            $monthly = $this->get_monthly_stat($post_type);
            $monthly_data[] = array(
                'label' => $label_mostrar,
                'data' => $monthly,
                'title' => __("Overview", ET_DOMAIN)
            );

            $weekly = $this->get_weekly_stat($post_type);
            $weekly_data[] = array(
                'label' => $label_mostrar,
                'data' => $weekly,
                'title' => __("Visión Semanal", ET_DOMAIN)
            );

            $daily = $this->get_daily_stat($post_type);
            $daily_data[] = array(
                'label' => $label_mostrar,
                'data' => $daily,
                'title' => __("Vista Diaria", ET_DOMAIN)
            );
        }

        $daily_register = $this->get_daily_registration();
        if (!empty($daily_register)) {
            $daily_data[] = array(
                'label' => __("Altas usuarios", ET_DOMAIN),
                'data' => $daily_register,
                'title' => __("Overview", ET_DOMAIN)
            );
        }

        $weekly_register = $this->get_daily_registration();
        if (!empty($weekly_register)) {
            $weekly_data[] = array(
                'label' => __("Altas usuarios", ET_DOMAIN),
                'data' => $weekly_register,
                'title' => __("Overview", ET_DOMAIN)
            );
        }

        $monthly_register = $this->get_monthly_registration();
        if (!empty($monthly_register)) {
            $monthly_data[] = array(
                'label' => __("Altas usuarios", ET_DOMAIN),
                'data' => $monthly_register,
                'title' => __("Overview", ET_DOMAIN)
            );
        }

        if ($payment) {
            $currency = ae_currency_sign(false);
            $daily_payment = $this->get_daily_payment('publish');
            $weekly_payment = $this->get_weekly_payment('publish');
            $monthly_payment = $this->get_monthly_payment();

            if (!empty($daily_payment)) {
                $daily_data[] = array(
                    'label' => sprintf(__("Revenue(%s)", ET_DOMAIN), $currency),
                    'data' => $daily_payment,
                    'title' => __("Revenue", ET_DOMAIN)
                );
            }

            if (!empty($weekly_payment)) {
                $weekly_data[] = array(
                    'label' => sprintf(__("Revenue(%s)", ET_DOMAIN), $currency),
                    'data' => $weekly_payment,
                    'title' => __("Revenue", ET_DOMAIN)
                );
            }
            if (!empty($monthly_payment)) {
                $monthly_data[] = array(
                    'label' => sprintf(__("Revenue(%s)", ET_DOMAIN), $currency),
                    'data' => $monthly_payment,
                    'title' => __("Revenue", ET_DOMAIN)
                );
            }
        }

        echo '<script type="application/json" id="monthly_data">' . json_encode($monthly_data) . '</script>';
        echo '<script type="application/json" id="weekly_data">' . json_encode($weekly_data) . '</script>';
        echo '<script type="application/json" id="daily_data">' . json_encode($daily_data) . '</script>';

        ?>


        <div class="charts" style="">
            <div id="daily_chart" style=""></div>
            <div id="weekly_chart" style=""></div>
            <div id="monthly_chart" style=""></div>
        </div>
        <!-- <div class="details" style="">
            <ul>
                <strong>Today</strong>
                <label for=""></label>
                <li>dakl dlkasd aslk kldja l</li>
                Yesterday
                <li>dakl dlkasd aslk kldja l</li>
                This week
                <li>dakl dlkasd aslk kldja l</li>
                Lastweek
                <li>dakl dlkasd aslk kldja l</li>

            </ul>
        </div>	 -->

        <?php
        echo '</div>';
    }

    /**
     * get daily registration
     */
    protected function get_daily_registration()
    {
        global $wpdb;

        $key = $wpdb->prefix . 'capabilities';

        $from = strtotime('-2 weeks');
        $from_date = date('Y-m-d 00:00:00', $from);

        $sql = "SELECT date({$wpdb->users}.user_registered) as date, count({$wpdb->users}.ID) as count FROM {$wpdb->users}
				INNER JOIN {$wpdb->usermeta} ON {$wpdb->usermeta}.user_id = {$wpdb->users}.ID AND {$wpdb->usermeta}.meta_key = '$key'
				WHERE
					STRCMP(user_registered, '$from_date') >= 0
				GROUP BY date({$wpdb->users}.user_registered)";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {
            if ($index > 0) {
                $distance = (strtotime($row['date']) - strtotime($result[($index - 1)]['date'])) / (24 * 3600);

                if ($distance > 1) {
                    for ($i = 0; $i < $distance - 1; $i++) {
                        $week = $i + 1;
                        $statistic[] = array(
                            date('F j, Y', strtotime($result[($index - 1)]['date']) + $week * 60 * 60 * 24),
                            0
                        );
                    }
                }
            }
            $statistic[] = array(
                date('F j, Y', strtotime($row['date'])),
                $row['count']
            );
        }

        return $statistic;
    }

    /**
     * get weekly registration
     */
    protected function get_weekly_registration()
    {
        global $wpdb;

        $key = $wpdb->prefix . 'capabilities';

        $from = strtotime('-3 months');
        $from_date = date('Y-m-d 00:00:00', $from);

        $sql = "SELECT WEEK({$wpdb->users}.user_registered) as `date`, count({$wpdb->users}.ID) as count FROM {$wpdb->users}
				INNER JOIN {$wpdb->usermeta} ON {$wpdb->usermeta}.user_id = {$wpdb->users}.ID AND {$wpdb->usermeta}.meta_key = '$key'
				WHERE
					STRCMP(user_registered, '$from_date') >= 0
				GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {
            $date = $row['date'] * 7;

            if ($index > 0) {
                $distance = $row['date'] - $result[($index - 1)]['date'];
                if ($distance > 1) {
                    for ($i = 0; $i < $distance - 1; $i++) {
                        $week = ($result[($index - 1)]['date'] + ($i + 1)) * 7;
                        $statistic[] = array(
                            date('F j, Y', strtotime('01 January 2014') + $week * 60 * 60 * 24),
                            0
                        );
                    }
                }
            }

            $statistic[] = array(
                date('F j, Y', strtotime('01 January 2014') + $date * 60 * 60 * 24),
                $row['count']
            );
        }

        return $statistic;
    }

    /**
     * get monthly registration
     */
    protected function get_monthly_registration()
    {
        global $wpdb;

        $key = $wpdb->prefix . 'capabilities';

        // if ( $from == false ) $from = strtotime('-2 weeks');
        $from_date = date('Y-m-d 00:00:00', strtotime('01-01-' . date("Y")));

        $sql = "SELECT MONTH({$wpdb->users}.user_registered) as date, user_registered as post_date , count({$wpdb->users}.ID) as count FROM {$wpdb->users}
				INNER JOIN {$wpdb->usermeta} ON {$wpdb->usermeta}.user_id = {$wpdb->users}.ID AND {$wpdb->usermeta}.meta_key = '$key'
				WHERE
					STRCMP(user_registered, '$from_date') >= 0
				GROUP BY date";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {
            $year = date("Y", strtotime($row['post_date']));
            $statistic[] = array(
                date('F j, Y', strtotime('01-' . $row['date'] . '-' . $year)),
                $row['count']
            );
        }

        return $statistic;
    }

    /**
     * Retrieve site's monthly stat
     * @param String $post_type The post type want to retrieve
     * @return array $statistic
     * @since 1.0
     * @author Toan
     */
    protected function get_monthly_stat($post_type)
    {

        global $wpdb;

        $from_date = date('Y-m-d 00:00:00', strtotime('01-01-' . date("Y")));

        // $to_date 	= date('Y-m-d 00:00:00', $to);

        $sql = "SELECT MONTH(post_date) AS `date`, post_date ,  COUNT(ID) as `count` FROM {$wpdb->posts}
				WHERE 	post_type = '$post_type' AND
                        STRCMP(post_date, '$from_date') >= 0 AND
						post_status IN ('publish','pending','closed', 'archive', 'reject')
				GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {
            $year = date("Y", strtotime($row['post_date']));
            $statistic[] = array(
                date('F j, Y', strtotime('01-' . $row['date'] . '-' . $year)),
                $row['count']
            );
        }

        return $statistic;
    }

    /**
     * Retrieve site's weekly stat
     * @param String $post_type The post type want to retrieve
     * @return array $statistic
     * @since 1.0
     * @author Dakachi
     */
    protected function get_weekly_stat($post_type)
    {

        global $wpdb;

        $from = strtotime('-3 months');
        $from_date = date('Y-m-d 00:00:00', $from);

        $sql = "SELECT WEEK(post_date) AS `date`, post_date ,  COUNT(ID) as `count` FROM {$wpdb->posts}
				WHERE 	post_type = '$post_type' AND
						STRCMP(post_date, '$from_date') >= 0 AND
						post_status IN ('publish','pending','closed', 'archive', 'reject')
				GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {

            $date = $row['date'] * 7;
            if ($index > 0) {
                $distance = $row['date'] - $result[($index - 1)]['date'];
                if ($distance > 1) {
                    for ($i = 0; $i < $distance - 1; $i++) {
                        $week = ($result[($index - 1)]['date'] + ($i + 1)) * 7;
                        $statistic[] = array(
                            date('F j, Y', strtotime('01 January 2014') + $week * 60 * 60 * 24),
                            0
                        );
                    }
                }
            }
            $statistic[] = array(
                date('F j, Y', strtotime('01 January 2014') + $date * 60 * 60 * 24),
                $row['count']
            );
        }

        return $statistic;
    }

    /**
     * Retrieve site's daily stat
     * @param String $post_type The post type want to retrieve
     * @return array $statistic
     * @since 1.0
     * @author Dakachi
     */
    protected function get_daily_stat($post_type)
    {

        global $wpdb;

        $from = strtotime('-2 weeks');
        $from_date = date('Y-m-d 00:00:00', $from);

        $sql = "SELECT date(post_date) AS `date` , post_date, COUNT(ID) as `count` FROM {$wpdb->posts}
				WHERE 	post_type = '$post_type' AND
						STRCMP(post_date, '$from_date') >= 0 AND
						post_status IN ('publish','pending','closed', 'archive', 'reject')
				GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {

            if ($index > 0) {
                $distance = (strtotime($row['date']) - strtotime($result[($index - 1)]['date'])) / (24 * 3600);

                if ($distance > 1) {
                    for ($i = 0; $i < $distance - 1; $i++) {
                        $week = $i + 1;
                        $statistic[] = array(
                            date('F j, Y', strtotime($result[($index - 1)]['date']) + $week * 60 * 60 * 24),
                            0
                        );
                    }
                }
            }

            $statistic[] = array(
                $row['date'],
                $row['count']
            );
        }

        return $statistic;
    }

    protected function get_daily_payment($status = '')
    {
        global $wpdb;

        $status = 'publish';

        $from = strtotime('-2 weeks');
        $from_date = date('Y-m-d 00:00:00', $from);

        $sql = "SELECT date(post_date) AS `date` , post_date, COUNT(ID) as `count`, sum(meta_value) as revenue
                FROM ( {$wpdb->posts} as P
                     JOIN {$wpdb->postmeta} as M
                        ON P.ID = M.post_id AND  M.meta_key = 'et_order_total' )
                WHERE   P.post_type = 'order' AND
                        STRCMP(post_date, '$from_date') >= 0 AND
                        post_status = '{$status}'
                GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();
        foreach ($result as $index => $row) {

            if ($index > 0) {
                $distance = (strtotime($row['date']) - strtotime($result[($index - 1)]['date'])) / (24 * 3600);

                if ($distance > 1) {
                    for ($i = 0; $i < $distance - 1; $i++) {
                        $week = $i + 1;
                        $statistic[] = array(
                            date('F j, Y', strtotime($result[($index - 1)]['date']) + $week * 60 * 60 * 24),
                            0
                        );
                    }
                }
            }

            $statistic[] = array(
                $row['date'],
                $row['revenue'],
                $row['count']
            );
        }
        return $statistic;
    }


    public function get_weekly_payment($status = 'publish')
    {
        global $wpdb;

        $status = 'publish';

        $from = strtotime('-3 months');
        $from_date = date('Y-m-d 00:00:00', $from);

        $sql = "SELECT WEEK(post_date) AS `date` , post_date, COUNT(ID) as `count`, sum(meta_value) as revenue
                FROM ( {$wpdb->posts} as P
                     JOIN {$wpdb->postmeta} as M
                        ON P.ID = M.post_id AND  M.meta_key = 'et_order_total' )
                WHERE   P.post_type = 'order' AND
                        STRCMP(post_date, '$from_date') >= 0 AND
                        post_status = '{$status}'
                GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();
        foreach ($result as $index => $row) {

            $date = $row['date'] * 7;
            if ($index > 0) {
                $distance = $row['date'] - $result[($index - 1)]['date'];
                if ($distance > 1) {
                    for ($i = 0; $i < $distance - 1; $i++) {
                        $week = ($result[($index - 1)]['date'] + ($i + 1)) * 7;
                        $statistic[] = array(
                            date('F j, Y', strtotime('01 January 2014') + $week * 60 * 60 * 24),
                            0
                        );
                    }
                }
            }
            $statistic[] = array(
                date('F j, Y', strtotime('01 January 2014') + $date * 60 * 60 * 24),
                $row['revenue']
            );
        }

        return $statistic;
    }

    public function get_monthly_payment()
    {
        global $wpdb;

        $status = 'publish';

        $from_date = date('Y-m-d 00:00:00', strtotime('01-01-' . date("Y")));

        $sql = "SELECT MONTH(post_date) AS `date` , post_date, COUNT(ID) as `count`, sum(meta_value) as revenue
                FROM ( {$wpdb->posts} as P
                     JOIN {$wpdb->postmeta} as M
                        ON P.ID = M.post_id AND  M.meta_key = 'et_order_total' )
                WHERE   P.post_type = 'order' AND
                        STRCMP(post_date, '$from_date') >= 0 AND
                        post_status = '{$status}'
                GROUP BY `date`";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $statistic = array();

        foreach ($result as $index => $row) {
            $year = date("Y", strtotime($row['post_date']));
            $statistic[] = array(
                date('F j, Y', strtotime('01-' . $row['date'] . '-' . $year)),
                $row['revenue'],
                $row['count']

            );
        }

        return $statistic;
    }

    public function overview_scripts()
    {
        ?>
        <!--[if lt IE 9]> <?php
        $this->add_script('excanvas', ae_get_url() . '/assets/js/excanvas.min.js'); ?> <![endif]-->
        <?php

        $this->add_script('jqplot', ae_get_url() . '/assets/js/jquery.jqplot.min.js', array(
            'jquery'
        ));
        $this->add_script('jqplot-plugins', ae_get_url() . '/assets/js/jqplot.plugins.js', array(
            'jquery',
            'jqplot'
        ));

        $this->add_script('ae-overview', ae_get_url() . '/assets/js/overview.js', array(
            'jquery',
            'jqplot',
            'appengine'
        ));
    }

    public function overview_styles()
    {
        $this->add_style('jqplot_style', ae_get_url() . '/assets/css/jquery.jqplot.min.css', array(), false, 'all');
    }
}

// if(!function_exists('et_get_customization')) {
// /**
//  * Get and return customization values for
//  * @since 1.0
//  */
