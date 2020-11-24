<?php
/**
 * review block
*/
class WPBakeryShortCode_de_testimonial_block extends WPBakeryShortCode {

    /**
     * @param array $atts
     * @param null $content
     * @return string
     */
    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $number = $paginate = '';

        extract(shortcode_atts(array(
            'el_class' => '',
            'title'    => __('TESTIMONIAL BLOCK',ET_DOMAIN),
            'number'   => 4,
            'custom_css' => ''
        ), $atts));
        ob_start();
        the_widget( 'DE_Testimonial_Widget', $atts );
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}

vc_map( array(
    "base"     => "de_testimonial_block",
    "name"     => __("DE TESTIMONIAL", ET_DOMAIN),
    "class"    => "",
    "icon"     => "icon-wpb-de_place",
    "category" => __('DirectoryEngine', ET_DOMAIN),
    "params"   => array(       
        array(
            "type"       => "textfield",
            "holder"     => "h3",
            "class"      => "",
            "heading"    => __("Title", ET_DOMAIN),
            "param_name" => "title",
            "value"      => __("Default Title", ET_DOMAIN),
        ),        
        array(
            "type"       => "textfield",
            "class"      => "",
            "heading"    => __("Number Posts", ET_DOMAIN),
            "param_name" => "number",
            "value"      => 4,
        ),          
        array(
            "type"        => "textfield",
            "heading"     => __("Extra class name", ET_DOMAIN),
            "param_name"  => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", ET_DOMAIN)
        ),
        array(
            "type"        => "textfield",
            "heading"     => __("Custom CSS", ET_DOMAIN),
            "param_name"  => "custom_css",
            "description" => __("If you wish to style particular content element differently, then use this field to add a custom CSS here.", ET_DOMAIN)
        )                                         
    )
) );