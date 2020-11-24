<?php
function apwp_admin_script()
{
    wp_register_style('apwp_admin_style', APWP_CSS . 'apwp-admin-style.css');
    wp_enqueue_style('apwp_admin_style');
    wp_enqueue_script('apwp_front_js', APWP_JS . 'apwp-admin-scripts.js', array(), '1.0.0', false);
    wp_enqueue_script('apwp_front_js');
}

add_action('admin_enqueue_scripts', 'apwp_admin_script');

function apwp_front_end_script()
{

    if (!is_front_page()) {
        wp_enqueue_script('jquery');

        wp_register_style('apwp_front_style', APWP_CSS . 'apwp-front-style.css');
        wp_enqueue_style('apwp_front_style');

        wp_enqueue_script('apwp_front_js', APWP_JS . 'apwp-front-script.js', array(), '1.0.0', false);
        wp_enqueue_script('apwp_front_js');
    }
}

add_action('wp_enqueue_scripts', 'apwp_front_end_script');

//------------------------------------------------------------------- Shortcode to show form

add_shortcode('apwp', 'frontend_payment_form');

function frontend_payment_form()
{
    ob_start();
    include('templates/frontend-payment-form.php');
    return ob_get_clean();
}

//---------------------------------------------------------------------

add_action('wp_head', 'apwp_ajaxurl');

function apwp_ajaxurl()
{ ?>
    <script type="text/javascript">
        var apwp_ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";
    </script>
    <?php
}

function populate_dynamic_sinceto()
{
    echo "hereeeeeee";
    exit;
}

add_action('wp_ajax_populate_dynamic_sinceto', 'populate_dynamic_sinceto');
add_action('wp_ajax_nopriv_populate_dynamic_sinceto', 'populate_dynamic_sinceto');