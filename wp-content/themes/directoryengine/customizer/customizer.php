<?php
// return;
require_once dirname(__FILE__) . '/less.inc.php';
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since DirectoryEngine 1.0
 */
class AE_Customize
{
    
    /**
     * This hooks into 'customize_register' (available as of WP 3.4) and allows
     * you to add new sections and controls to the Theme Customize screen.
     *
     * Note: To enable instant preview, we have to actually write a bit of custom
     * javascript. See live_preview() for more.
     *
     * @see add_action('customize_register',$func)
     * @param \WP_Customize_Manager $wp_customize
     * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
     * @since DirectoryEngine 1.0
     */
    public static function register($wp_customize) {
        
        //1. Define a new section (if desired) to the Theme Customizer
        $wp_customize->add_section('de_customizer_options', array(
            'title' => __('DE Options', ET_DOMAIN) ,
            'priority' => 35,
            'capability' => 'edit_theme_options',
            'description' => __('Allows you to customize some example settings for DirectoryEngine.', ET_DOMAIN) ,
             //Descriptive tooltip
            
        ));
        
        //2. Register new settings to the WP database...
        $wp_customize->add_setting('header_bg_color', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_setting('body_bg_color', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_setting('footer_bg_color', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_setting('btm_footer_color', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_setting('main_color', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
        ));
        
        //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', array(
            'label' => __('Heaher Background Color', ET_DOMAIN) ,
            'section' => 'colors',
            'settings' => 'header_bg_color',
            'priority' => 10,
        )));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_bg_color', array(
            'label' => __('Body Background Color', ET_DOMAIN) ,
            'section' => 'colors',
            'settings' => 'body_bg_color',
            'priority' => 10,
        )));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', array(
            'label' => __('Footer Background Color', ET_DOMAIN) ,
            'section' => 'colors',
            'settings' => 'footer_bg_color',
            'priority' => 10,
        )));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'btm_footer_color', array(
            'label' => __('Copyright Background Color', ET_DOMAIN) ,
            'section' => 'colors',
            'settings' => 'btm_footer_color',
            'priority' => 10,
        )));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'main_color', array(
            'label' => __('Main Color', ET_DOMAIN) ,
            'section' => 'colors',
            'settings' => 'main_color',
            'priority' => 10,
        )));
        
        //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
        $wp_customize->get_setting('blogname')->transport = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
        $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
        $wp_customize->get_setting('background_color')->transport = 'postMessage';
    }
    
    /**
     * This will output the custom WordPress settings to the live theme's WP head.
     *
     * Used by hook: 'wp_head'
     *
     * @see add_action('wp_head',$func)
     * @since MyTheme 1.0
     */
    public static function header_output() {
        if(et_load_mobile()) return ;
?>
			<!--Customizer CSS--> 
			<style type="text/css" id="header_output">
				<?php
        self::generate_css('#menu-top', 'background-color', 'header_bg_color'); ?> 
				<?php
        self::generate_css('body', 'background-color', 'body_bg_color'); ?>
				<?php
        self::generate_css('footer', 'background-color', 'footer_bg_color') ?>
				<?php
        self::generate_css('.copyright-wrapper', 'background-color', 'btm_footer_color') ?>
				<?php
        self::generate_css('.list-option-filter .icon-list-view .icon-view.active i, .list-option-filter .icon-list-view .icon-view:hover i, .list-option-filter .sort-rates-lastest a.sort-icon.active, .list-option-filter .sort-rates-lastest a.sort-icon:hover, a:hover, a:focus', 'color', 'main_color') ?>
				<?php
        self::generate_css('.step-content-wrapper .list-price li .btn.btn-submit-price-plan, .btn.btn-submit-login-form, .btn-post-place, .mask-color, .slider-selection, .option-search.right input[type="submit"]', 'background-color', 'main_color');
?>
				.option-search.right input[type="submit"] {
					color: #fff;
				}
			</style> 
			<!--/Customizer CSS-->
			<?php
    }
    
    /**
     * This outputs the javascript needed to automate the live settings preview.
     * Also keep in mind that this function isn't necessary unless your settings
     * are using 'transport'=>'postMessage' instead of the default 'transport'
     * => 'refresh'
     *
     * Used by hook: 'customize_preview_init'
     *
     * @see add_action('customize_preview_init',$func)
     * @since DirectoryEngine 1.0
     */
    public static function live_preview() {
        wp_enqueue_script('de-themecustomizer',
             // Give the script a unique ID
            get_template_directory_uri() . '/customizer/customizer.js',
             // Define the path to the JS file
            array(
                'jquery',
                'customize-preview'
            ) ,
             // Define dependencies
            '',
             // Define a version (optional)
            true
            
            // Specify whether to put in footer (leave this true)
        );

        et_customizer_print_styles();
        echo '<link rel="stylesheet/less" type="txt/less" href="'. get_template_directory_uri() .'/customizer/admin-define.less">';
        wp_enqueue_script( 'lessc', get_template_directory_uri() .'/customizer/less.js', array(), true );   
        
    }
    
    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     *
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since DirectoryEngine 1.0
     */
    public static function generate_css($selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true) {
        $return = '';
        $mod = get_theme_mod($mod_name);
        if (!empty($mod)) {
            $return = sprintf('%s { %s:%s ; }', $selector, $style, $prefix . $mod . $postfix);
            if ($echo) {
                echo $return;
            }
        }
        return $return;
    }
}

//Setup the Theme Customizer settings and controls...
// add_action('customize_register', array(
//     'AE_Customize',
//     'register'
// ));

// // Output custom CSS to live site
// add_action('wp_footer', array(
//     'AE_Customize',
//     'header_output'
// ));

// // Enqueue live preview javascript in Theme Customizer admin screen
// add_action('customize_preview_init', array(
//     'AE_Customize',
//     'live_preview'
// ));

add_action( 'customize_save_after', 'ae_save_customize' );
function ae_save_customize() {
    $style = array();
    $style = wp_parse_args($style, array(
        'background' => get_theme_mod( 'body_bg_color') ? get_theme_mod( 'body_bg_color') : '#ECF0F1',
        'header'    => get_theme_mod( 'header_bg_color' ) ? get_theme_mod( 'header_bg_color' ) : '#ffffff',
        'heading'    => '#525252',
        'footer_bottom'   => get_theme_mod( 'footer_bg_color' ) ? get_theme_mod( 'footer_bg_color') : '#2C3E50',
        'footer'    => get_theme_mod( 'btm_footer_color' ) ? get_theme_mod( 'btm_footer_color') : '#34495E',
        'text'      => '#7b7b7b',
        'action_1'  => get_theme_mod( 'main_color' ) ? get_theme_mod( 'main_color' ) : '#8E44AD',       
    ));
    $customzize = et_less2css($style);
    $customzize = et_mobile_less2css($style);
}

if(!function_exists('et_get_customization')) {
    /**
     * Get and return customization values for 
     * @since 1.0
     */
    function et_get_customization(){
        $style = get_option('ae_theme_customization', true);
        $style = wp_parse_args( $style, array(
            'background' => '#ECF0F1',
            'header'    => '#ffffff',
            'heading'    => '#525252',
            'footer_bottom'   => '#2C3E50',
            'footer'    => '#34495E',
            'text'      => '#7b7b7b',
            'action_1'  => '#8E44AD',  
            'paginate'  => 'f1c40f',     
            'font-heading'          => 'Raleway',
            'font-heading-weight'   => 'normal',
            'font-heading-style'    => 'normal',
            'font-heading-size'     => '14px',
            'font-text'             => 'Raleway, sans-serif',
            'font-text-weight'      => 'normal',
            'font-text-style'       => 'normal',
            'font-text-size'        => '12px',
            'header_modal'          => '#5f6e81',
            'header_menu'           => '#5f6f81'
            ));

        return $style;
    }
}

function et_customizer_print_styles() {
    if (current_user_can('manage_options') && !is_admin()) {
        
        et_enqueue_gfont();
        echo '<link rel="stylesheet/less" type="txt/less" href="'. get_template_directory_uri() .'/customizer/admin-define.less">';
        wp_register_style('et_colorpicker', TEMPLATEURL . '/customizer/css/colorpicker.css', array(
            'custom'
        ));
        wp_enqueue_style('et_colorpicker');
        wp_register_style('et_customizer_css', TEMPLATEURL . '/customizer/css/customizer.css', array(
            'custom'
        ));
        wp_enqueue_style('et_customizer_css');
?>
    <script type="text/javascript" id="ae-customizer-script">
        var customizer = {};
<?php
        $style = et_get_customization();
        foreach ($style as $key => $value) {
            $variable = $key;
            
            //$variable = str_replace('-', '_', $key);
            if (preg_match('/^rgb/', $value)) {
                preg_match('/rgb\(([0-9]+), ([0-9]+), ([0-9]+)\)/', $value, $matches);
                $val = rgb2html($matches[1], $matches[2], $matches[3]);
                echo "customizer['{$variable}'] = '{$val}';\n";
            } else {
                echo "customizer['{$variable}'] = '" . stripslashes($value) . "';\n";
            }
        }
?>
    </script>
    <?php
    }
}

function et_get_scheme() {
    return array(
        '#8E44AD',
        '#998675',
        '#1BA084',
        '#904C09',
        '#E67E22',
        '#16A084',
        '#AD0A4B',
        '#B5740B'
    );
}

function et_schemes() {
    return array(
        // Default #1d83d5
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#444c61', // text
            'action_1' => '#1d83d5',
            'paginate'  => '#f1c40f', // paginate button
            'header_modal' => '#5f6e81',
            'header_menu' => '#5f6f81',
            'footer' => '#34495e', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#2d3e50' // Copyright
        ) ,
        // Brown #995c3d
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#995c3d', // text
            'action_1' => '#995c3d',
            'paginate'  => '#995c3d', // paginate button
            'header_modal' => '#584034',
            'header_menu' => '#806659',
            'footer' => '#674b3f', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#584034' // Copyright
        ),
        // Deep Green #20655f
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#20655f', // text
            'action_1' => '#20655f',
            'paginate'  => '#20655f', // paginate button
            'header_modal' => '#1c332f',
            'header_menu' => '#4c9993',
            'footer' => '#2a4d47', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#1c332f' // Copyright
        ),
        // Green #1bbf9d
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#1bbf9d', // text
            'action_1' => '#1bbf9d',
            'paginate'  => '#1bbf9d', // paginate button
            'header_modal' => '#345750',
            'header_menu' => '#5c988c',
            'footer' => '#3d665e', // Footer
            'font-heading-name' => 'Roboto',
            'font-heading' => 'Roboto',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Roboto',
            'font-text' => 'Roboto',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Roboto',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#345750' // Copyright
        ),
        // Light Green #00bcd5
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#00bcd5', // text
            'action_1' => '#00bcd5',
            'paginate'  => '#00bcd5', // paginate button
            'header_modal' => '#245157',
            'header_menu' => '#4c9099',
            'footer' => '#296067', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#245157' // Copyright
        ),
        // Pink #b83b5d
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#b83b5d', // text
            'action_1' => '#b83b5d',
            'paginate'  => '#b83b5d', // paginate button
            'header_modal' => '#572735',
            'header_menu' => '#994c6c',
            'footer' => '#672e3f', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#572735' // Copyright
        ),
        // Purple #9958b2
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#9958b2', // text
            'action_1' => '#9958b2',
            'paginate'  => '#9958b2', // paginate button
            'header_modal' => '#4c3458',
            'header_menu' => '#6b597f',
            'footer' => '#593d66', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#4c3458' // Copyright
        ),
        // Red #b71b1c
        array(
            'background' => '#ecf0f1', // Background
            'header' => '#ffffff', // Header
            'heading' => '#37393a',
            'text' => '#b71b1c', // text
            'action_1' => '#b71b1c',
            'paginate'  => '#b71b1c', // paginate button
            'header_modal' => '#662829',
            'header_menu' => '#9a4c4c',
            'footer' => '#813333', // Footer
            'font-heading-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-heading' => 'Open Sans, sans-serif',
            'font-heading-size' => '15px',
            'font-heading-style' => 'normal',
            'font-heading-weight' => 'normal',
            'font-text-name' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-text' => 'Open Sans, sans-serif',
            'font-text-size' => '15px',
            'font-text-style' => 'normal',
            'font-text-weight' => 'normal',
            'font-action' => 'Open Sans, Arial, Helvetica, sans-serif',
            'font-action-size' => '15px',
            'font-action-style' => 'normal',
            'font-action-weight' => 'normal',
            'layout' => 'content-sidebar',
            'footer_bottom' => '#662829' // Copyright
        ),
        
    );
}

function et_page_color() {
    return array(
        'header' => __("Header Background", ET_DOMAIN) ,
        'background' => __("Body Background", ET_DOMAIN) ,
        'footer' => __("Footer Background", ET_DOMAIN) ,
        'footer_bottom' => __("Copyright Background", ET_DOMAIN) ,
        'action_1' => __("Main color", ET_DOMAIN) 
    );
}

/**
 * Get all font supported by theme
 *
 * @return mixed
 */
function et_get_supported_fonts() {
    $fonts = apply_filters("et_enqueue_gfont", array(
        'raleway' => array(
            'fontface' => 'Raleway, san-serif',
            'name' => 'Raleway',
            'link' => 'Raleway:400,300,500,600,700,800'
        ) ,
        'arial' => array(
            'fontface' => 'Arial, san-serif',
            'name' => 'Arial',
            'link' => 'Arial'
        ) ,
        'quicksand' => array(
            'fontface' => 'Quicksand, sans-serif',
            'link' => 'Quicksand',
            'name' => 'Quicksand'
        ) ,
        'ebgaramond' => array(
            'fontface' => 'EB Garamond, serif',
            'link' => 'EB+Garamond',
            'name' => 'EB Garamond'
        ) ,
        'imprima' => array(
            'fontface' => 'Imprima, sans-serif',
            'link' => 'Imprima',
            'name' => 'Imprima'
        ) ,
        'ubuntu' => array(
            'fontface' => 'Ubuntu, sans-serif',
            'link' => 'Ubuntu',
            'name' => 'Ubuntu'
        ) ,
        'adventpro' => array(
            'fontface' => 'Advent Pro, sans-serif',
            'link' => 'Advent+Pro',
            'name' => 'EB Garamond'
        ) ,
        'mavenpro' => array(
            'fontface' => 'Maven Pro, sans-serif',
            'link' => 'Maven+Pro',
            'name' => 'Maven Pro'
        ) ,
        'times' => array(
            'fontface' => 'Times New Roman, serif',
            'link' => 'Times+New+Roman',
            'name' => 'Times New Roman'
        ) ,
        'georgia' => array(
            'fontface' => 'Georgia, serif',
            'link' => 'Georgia',
            'name' => 'Georgia'
        ) ,
        'helvetica' => array(
            'fontface' => 'Helvetica, san-serif',
            'link' => 'Helvetica',
            'name' => 'Helvetica'
        ) ,
    ));
    return $fonts;
}

/**
 * Get google font
 *
 * @param $font_id
 *
 * @author: nguyenvanduocit
 * @return \WP_Error
 */
function et_get_gfront($font_id) {
    $fonts = et_get_supported_fonts();
    if (array_key_exists($font_id, $fonts)) {
        return $fonts[$font_id];
    }
    return new WP_Error('font_not_found', "Font not found");
}

/**
 * @author: nguyenvanduocit
 */
function et_enqueue_gfont() {
    
    // enqueue google web font
    $fonts = et_get_supported_fonts();    
    foreach ($fonts as $key => $font) {
        echo "<link href='//fonts.googleapis.com/css?family=" . $font['link'] . "' rel='stylesheet' type='text/css'>";
    }
}

/**
 * Enqueue google font
 *
 * @author : Nguyễn Văn Được
 */
function et_enqueue_customize_font() {
    
    $customization_option = et_get_customization();
    $font_heading = $customization_option['font-heading'];
    $font_body = $customization_option['font-text'];
    $fonts = et_get_supported_fonts();
    
    if (array_key_exists($font_heading, $fonts)) {
        $url = "//fonts.googleapis.com/css?family=" . $fonts[$font_heading]['link'];
        wp_enqueue_style('et-customization-font-heading', $url);
    }
    
    if (array_key_exists($font_body, $fonts)) {
        $url = "//fonts.googleapis.com/css?family=" . $fonts[$font_body]['link'];
        wp_enqueue_style('et-customization-text', $url);
    }
}

/**
 * Show off the customizer pannel
 */
function et_customizer_panel() {
    if (current_user_can('manage_options')) {
        $style = et_get_customization();
        $layout = 'content-sidebar';
        $customizer = get_option('ae_theme_customization');    
        
        $schemes = et_get_scheme();
        $page_colors = et_page_color();
        $schemes = array();
?>
        <script type="text/javascript" id="schemes"><?php echo json_encode(et_schemes()); ?></script>
        <div id="customizer" class="customizer-panel">
            <div class="close-panel">
                <a href="<?php echo esc_url(add_query_arg('deactivate', 'customizer')); ?>" class=""><span>*</span></a>
            </div>
            <form action="" id="f_customizer">
                <div class="section">
                    <div class="custom-head">
                        <span class="spacer"></span><h3><?php _e('Color Schemes', ET_DOMAIN) ?></h3><span class="spacer"></span>
                    </div>
                    <div class="section-content">
                        <ul class="blocks-grid">
                            <!-- Default -->
                            <li class="clr-block scheme-item" data="" style="background: #1d83d5"></li>
                            <!-- Lato -->
                            <li class="clr-block scheme-item" data="" style="background: #995c3d"></li>
                            <!-- Deep Green -->
                            <li class="clr-block scheme-item" data="" style="background: #20655f"></li>
                            <!-- Green -->
                            <li class="clr-block scheme-item" data="" style="background: #1bbf9d"></li>
                            <!-- Light Green -->
                            <li class="clr-block scheme-item" data="" style="background: #00bcd5"></li>
                            <!-- Pink -->
                            <li class="clr-block scheme-item" data="" style="background: #b83b5d"></li>
                            <!-- Purple -->
                            <li class="clr-block scheme-item" data="" style="background: #9958b2"></li>
                            <!-- Red -->
                            <li class="clr-block scheme-item" data="" style="background: #b71b1c"></li>
                        </ul>
                    </div>
                </div>
                <div class="section">
                    <div class="custom-head">
                        <span class="spacer"></span><h3><?php _e('Page Options', ET_DOMAIN) ?></h3><span class="spacer"></span>
                    </div>
                    <div class="section-content" style="display: none">                        
                        <h4><?php _e('Colors', ET_DOMAIN) ?></h4>
                        <ul class="blocks-list">
                        <?php foreach ($page_colors as $key => $value) { ?>
                            <li>
                                <div class="picker-trigger clr-block" data-color="<?php echo $key; ?>" style="background: <?php echo $customizer[$key] ?>"></div>
                                <span class="block-label"><?php echo $value; ?></span>
                            </li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="section">
                    <div class="custom-head">
                        <span class="spacer"></span><h3><?php _e('Content Options', ET_DOMAIN) ?></h3><span class="spacer"></span>
                    </div>
                    <div class="section-content" style="display: none">
                        <?php $fonts = et_get_supported_fonts(); ?>
                         <div class="block-select">
                            <label for=""><?php _e('Heading', ET_DOMAIN) ?></label>
                            <div class="select-wrap">
                                <div>
                                    <select class="fontchoose" name="font-heading">
                                        <?php foreach ($fonts as $key => $font) { ?>
                                            <option <?php if (isset($customizer['font-heading']) && $customizer['font-heading'] == $key) echo 'selected="selected"' ?> 
                                                data-fontface="<?php echo $font['fontface'] ?>" 
                                                value="<?php echo $key ?>"><?php echo $font['name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="block-select">
                            <label for=""><?php _e('Content', ET_DOMAIN) ?></label>
                            <div class="select-wrap">
                                <div>
                                    <select class="fontchoose" name="font-text" id="">
                                        <?php foreach ($fonts as $key => $font) { ?>
                                            <option <?php if (isset($customizer['font-text']) && $customizer['font-text'] == $key) echo 'selected="selected"' ?> 
                                                data-fontface="<?php echo $font['fontface'] ?>" 
                                                value="<?php echo $key ?>"><?php echo $font['name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>
                <button type="button" class="btn blue-btn" id="save_customizer" title="<?php _e('Save', ET_DOMAIN) ?>"><span><?php _e('Save', ET_DOMAIN) ?></span></button>
                <button type="button" class="btn none-btn" id="reset_customizer" title="<?php _e('Reset', ET_DOMAIN) ?>"><span class="icon" data-icon="D"></span></span><span><?php _e('Reset', ET_DOMAIN) ?></span></button>
            </form>
        </div> <?php
    }
}

/**
 * Displaying the button that trigger the customizer panel
 */
function et_customizer_trigger() {
    if (current_user_can('administrator')) { ?>
        <style type="text/css">
            #customizer_trigger{
                position: fixed;
                top: 40%;
                left: 0;
                height: 40px;
                width: 40px;
                display: block;
                border-radius: 0px 3px 3px 0px;
                -moz-border-radius: 0px 3px 3px 0px;
                -webkit-border-radius: 0px 3px 3px 0px;
                color: #7b7b7b; 
                border: 1px solid #c4c4c4;
                transition:opacity 0.5s linear;
                z-index: 1000;
                padding: 5px;
            }
            #customizer_trigger:hover{
                opacity: 0.5;
                filter:alpha(opacity:50);
            }

            #customizer_trigger:before {
                font-size: 20px;
                line-height: 23px;
                margin-left: 10px;
                text-shadow: 0 -1px 1px #333333;
                -moz-text-shadow: 0 -1px 1px #333333;
                -webkit-text-shadow: 0 -1px 1px #333333;
            }
            #customizer_trigger i {
                font-size: 30px;
            }
        </style>
        <?php if(!et_load_mobile()):?>
            <a id="customizer_trigger" title="<?php _e('Activate customization mode', ET_DOMAIN) ?>" 
                href="<?php echo esc_url(add_query_arg('activate', 'customizer')) ?>">
                <i class="fa fa-cog"></i>
            </a>
        <?php endif;?>
    <?php
    }
}

define('CUSTOMIZE_DIR', THEME_CONTENT_DIR . '/css');

/**
 * Trigger the customization mode here
 * When administrator decide to customize something,
 * he trigger a link that activate "customization mode".
 *
 * When he finish customizing, he click on the close button
 * on customizer panel to close the "customization mode".
 */
function et_customizer_init() {
    $current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    if (isset($_REQUEST['activate']) && $_REQUEST['activate'] == 'customizer') {
        setcookie('et-customizer', '1', time() + 3600, '/');
        wp_redirect(esc_url(remove_query_arg('activate')). '?et-customizer=1' );
        exit;
    }else if (isset($_REQUEST['deactivate']) && $_REQUEST['deactivate'] == 'customizer') {
        setcookie('et-customizer', '', time() - 3600, '/');
        wp_redirect(esc_url(remove_query_arg(array('deactivate','et-customizer'))));
        exit;
    }

    // If setcookie is false, use session
    if(!isset($_COOKIE['et-customizer'])){
        if (isset($_REQUEST['activate']) && $_REQUEST['activate'] == 'customizer') {
            session_start();
            $_SESSION['et-customizer'] = "1";
            wp_redirect(esc_url(remove_query_arg('activate')));
            exit;
        }elseif (isset($_REQUEST['deactivate']) && $_REQUEST['deactivate'] == 'customizer') {
            session_start();
            $_SESSION['et-customizer'] = "";
            wp_redirect(esc_url(remove_query_arg('deactivate')));
            exit;
        }
    }
    
    /**
     * cookie store customize active
     * render customize bar and script
     */
    if ((isset($_COOKIE['et-customizer']) && (true == $_COOKIE['et-customizer'])) || 
        (isset($_GET['et-customizer']) && (true == $_GET['et-customizer']))  ||
        (isset($_SESSION['et-customizer']) && (true == $_SESSION['et-customizer'])) ) {
        add_action('wp_print_styles', 'et_customizer_print_styles', 100);
        add_action('wp_print_scripts', 'et_customizer_print_scripts');
        add_action('wp_ajax_save-customization', 'et_customizer_save');
        add_action('wp_footer', 'et_customizer_panel');
        add_action('wp_logout', 'et_customizer_destroy_cookie');
    } 
    else {
        add_action('et_after_print_styles', 'et_customization_styles');
        add_action('wp_footer', 'et_customizer_trigger');
        add_action('body_class', 'et_layout_classes');
    }
}

add_action('init', 'et_customizer_init');
function et_customizer_destroy_cookie() {
    setcookie('et-customizer', '', time() + 3600, '/');
    session_destroy();
}

function et_customizer_save() {
    if (!current_user_can('manage_options')) return;
    
    try {
        if (isset($_REQUEST['content']['customization'][0])) {
            unset($_REQUEST['content']['customization'][0]);
        }
        
        $customization = $_REQUEST['content']['customization'];
        
        // save the customization value
        update_option('ae_theme_customization', $customization);
        
        $customzize = et_less2css($customization);
        $customzize = et_mobile_less2css($customization);
        
        $resp = array(
            'success' => true,
            'code' => 200,
            'msg' => __("Changes are saved successfully.", ET_DOMAIN) ,
            'data' => $customization
        );
    }
    catch(Exception $e) {

        $resp = array(
            'success' => false,
            'code' => true,
            'msg' => sprintf(__("Something went wrong! System cause following error <br /> %s", ET_DOMAIN) , $e->getMessage())
        );
    }
    wp_send_json($resp);
}

/**
 * Adds theme layout classes to the array of body classes.
 */
function et_layout_classes($existing_classes) {
    $current_layout = 'content-sidebar';
    
    if (in_array($current_layout, array(
        'content-sidebar',
        'sidebar-content'
    ))) $classes = array(
        'two-column'
    );
    else $classes = array(
        'one-column'
    );
    
    if ('content-sidebar' == $current_layout) $classes[] = 'right-sidebar';
    elseif ('sidebar-content' == $current_layout) $classes[] = 'left-sidebar';
    else $classes[] = $current_layout;
    
    $classes = apply_filters('et_layout_classes', $classes, $current_layout);
    
    return array_merge($existing_classes, $classes);
}

// add_filter( 'body_class', 'et_layout_classes' );

function et_customizer_print_scripts() {
    
    if (current_user_can('manage_options') && !is_admin()) {

        // et_customizer_print_styles();
        // echo '<link rel="stylesheet/less" type="txt/less" href="'. get_template_directory_uri() .'/customizer/admin-define.less">';
        // wp_enqueue_script( 'lessc', get_template_directory_uri() .'/customizer/less.js', array(), true );   
        
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-slider');
        
        // color picker
        wp_register_script('et-colorpicker', TEMPLATEURL . '/customizer/js/colorpicker.js');
        wp_enqueue_script('et-colorpicker', false, array(
            'jquery'
        ) , '1.0', true);
        
        // scrollbar
        wp_register_script('et-tinyscrollbar', TEMPLATEURL . '/customizer/js/jquery.tinyscrollbar.min.js');
        wp_enqueue_script('et-tinyscrollbar', false, array(
            'jquery',
            'underscore',
            'backbone',
            'appengine'
        ) , '1.0', true);
        
        // customizer script
        wp_register_script('et_customizer', TEMPLATEURL . '/customizer/js/customizer.js', array(
            'jquery',
            'et-colorpicker',
            'appengine'
        ) , false, true);
        wp_enqueue_script('et_customizer', false, array(
            'jquery',
            'et-colorpicker',
            'appengine'
        ) , '1.0', true);        
        
        add_action('print_define_less', 'print_define_less');
       

    }
}
function print_define_less() { ?>
    <link rel="stylesheet/less" type="txt/less" href="<?php echo TEMPLATEURL . '/customizer/define.less' ?>">
    <?php
    wp_register_script('less-js', TEMPLATEURL . '/customizer/js/less-1.4.1.min.js', '1.0', true);
    wp_enqueue_script('less-js');
}

