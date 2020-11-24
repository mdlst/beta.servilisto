<?php
/**
 * class Mailing control mail options
 */
class WPBakeryShortCode_de_feature_block extends WPBakeryShortCode {

    /**
     * @param array $atts
     * @param null $content
     * @return string
     */
    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $icon = $number_post = $output = $f_content = '';

        extract(shortcode_atts(array(
            'el_class'      => '',
            'icon'          => '',
            'title'         => __('CONTACT FORM',ET_DOMAIN),
            'number_post'   => 2 ,
            'f_content'     => ''
        ), $atts));
        
        /* ================  Render Shortcodes ================ */
        ob_start();
        echo    '<div class="features-wrapper '.$el_class.'" style="'.$custom_css.'">
                    <span class="icon-features"><i class="fa '.$icon.'"></i></span>
                    <div class="content-features">
                        <h2>'.$title.'</h2>
                        <p>'.$f_content.'</p>
                    </div>
                </div>';
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}

vc_map( array(
    "base"      => "de_feature_block",
    "name"      => __("DE Feature", ET_DOMAIN),
    "class"     => "",
    "icon"      => "icon-wpb-de_feature",
    "category" => __('DirectoryEngine', ET_DOMAIN),
    "params"    => array(
        array(
            "type" => "textfield",
            "class" => "input-icon",
            "heading" => __("Icon", ET_DOMAIN),
            "param_name" => "icon",
            "value"     => ''
        ),        
        array(
            "type" => "textfield",
            "holder" => "h3",
            "class" => "",
            "heading" => __("Title", ET_DOMAIN),
            "param_name" => "title",
            "value"     => __("Default Title", ET_DOMAIN),
        ),
        array(
            "type" => "textarea",
            "class" => "",
            "heading" => __("Feature Content", ET_DOMAIN),
            "param_name" => "f_content",
            "value"     => '',
        ), 
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", ET_DOMAIN),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", ET_DOMAIN)
        ),
        array(
            "type" => "textfield",
            "heading" => __("Custom CSS", ET_DOMAIN),
            "param_name" => "custom_css",
            "description" => __("If you wish to style particular content element differently, then use this field to add a custom CSS here.", ET_DOMAIN)
        )                           
    )
) );