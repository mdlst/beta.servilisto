<?php

/**
 * Class WPBakeryShortCode_de_category_block
 */
class WPBakeryShortCode_de_category_block extends WPBakeryShortCode {

    /**
     * @param array $atts
     * @param null $content
     * @return string
     */
    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $count = $style = $hide_empty = $orderby = '';

        extract(shortcode_atts(array(
            'el_class'   => '',
            'title'      => __('CATEGORY BLOCK',ET_DOMAIN),
            'style'      => 'vertical' , 
            'count'      => false, 
            'hide_empty' => false,
            'orderby'    => 'name',
            'custom_css' => ''
        ), $atts));

        //unset($atts['title']);
        unset($atts['el_class']);
        /* ================  Render Shortcodes ================ */
        ob_start();

        the_widget('DE_Categories_Widget', $atts);

        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}

vc_map( array(
    "base"     => "de_category_block",
    "name"     => __("DE Category", ET_DOMAIN),
    "class"    => "",
    "icon"     => "icon-wpb-de_category",
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
            "holder"     => "h3",
            "class"      => "",
            "heading"    => __("Number of category", ET_DOMAIN),
            "param_name" => "number",
            "value"      => 10
        ),  
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Style", ET_DOMAIN),
            "param_name" => "style",
            "value"      => array('Vertical' => 'vertical', 'Horizontal' => 'horizontal'),
        ), 
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Show posts count", ET_DOMAIN),
            "param_name" => "count",
            "value"      => array('Yes' => 1, 'No' => 0),
        ), 
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Hide empty", ET_DOMAIN),
            "param_name" => "hide_empty",
            "value"      => array('Yes' => 1, 'No' => 0),
        ), 
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Order by", ET_DOMAIN),
            "param_name" => "orderby",
            "value"      => array('Name' => 'name', 'Slug' => 'slug', 'Count' => 'count', 'ID' => 'id'),
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