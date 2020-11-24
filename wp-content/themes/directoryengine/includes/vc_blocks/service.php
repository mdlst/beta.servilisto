<?php

/**
 * Class WPBakeryShortCode_de_service_block
 */
class WPBakeryShortCode_de_service_block extends WPBakeryShortCode {

    /**
     * @param array $atts
     * @param null $content
     * @return string
     */
    protected function content($atts, $content = null) {
        
        $custom_css = $el_class = $title = $icon = $output = $s_content = $m_link = '';

        extract(shortcode_atts(array(
            'el_class'      => '',
            'title'         => __('SERVICE BLOCK',ET_DOMAIN),
            's_content'     => '',
            'm_link'        => '',
            'icon'          => ''
        ), $atts));

        $m_link = vc_build_link($m_link);
        $html_more = !empty($m_link) ? '<a title="'.$m_link['title'].'" target="'.$m_link['target'].'" href="'.$m_link['url'].'" class="btn-more">'.__('More', ET_DOMAIN).'</a>' : '';
        /* ================  Render Shortcodes ================ */
        ob_start();
        echo    '<div class="services-wrapper '.$el_class.'" style="'.$custom_css.'">
                    <span class="icon-services"><i class="fa '.$icon.'"></i></span>
                    <div class="content-services">
                        <h2>'.$title.'</h2>
                        <p>'.$s_content.'</p>
                        '.$html_more.'
                    </div>
                </div>';
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}

vc_map( array(
    "base"      => "de_service_block",
    "name"      => __("DE Service", ET_DOMAIN),
    "class"     => "",
    "icon"      => "icon-wpb-de_service",
    "category" => __('DirectoryEngine', ET_DOMAIN),
    "params"    => array(
        array(
            "type" => "textfield",
            "heading" => __("Icon", ET_DOMAIN),
            "class" => "input-icon",
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
            "heading" => __("Service Content", ET_DOMAIN),
            "param_name" => "s_content",
            "value"     => '',
        ), 
        array(
            "type" => "vc_link",
            "class" => "",
            "heading" => __("More Link", ET_DOMAIN),
            "param_name" => "m_link",
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