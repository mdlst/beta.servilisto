<?php
/**
 * Class WPBakeryShortCode_de_blog_block
*/
class WPBakeryShortCode_de_blog_block extends WPBakeryShortCode {

    /**
     * @param array $atts
     * @param null $content
     * @return string
     */
    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $showposts = $category = $style = $paginate = '';

        extract(shortcode_atts(array(
            'el_class'  => '',
            'title'     => __('BLOG BLOCK',ET_DOMAIN),
            'showposts' => 6, 
            'style'     => 'vertical',
            'category'  => '',
            'paginate'  => 'load_more',
            'custom_css' => ''
        ), $atts));

        ob_start();
        the_widget( 'DE_Blog_Widget', $atts );
        $output = ob_get_clean();

        /* ================  Render Shortcodes ================ */
        return $output;
    }
}
global $wpdb;
// places
$query = "SELECT *
                        FROM
                            {$wpdb->terms} as t 
                        INNER JOIN 
                            {$wpdb->term_taxonomy} as tax 
                        ON 
                            tax.term_id = t.term_id
                        WHERE 
                            tax.taxonomy = 'category' AND tax.count > 0";                     
$categories     =  $wpdb->get_results($query);
$categories_arr = array(__('All', ET_DOMAIN) => '');
foreach ($categories as $category) {
    $categories_arr[$category->name] = $category->term_id;
}

vc_map( array(
    "base"     => "de_blog_block",
    "name"     => __("DE BLOG", ET_DOMAIN),
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
            "param_name" => "showposts",
            "value"      => 6,
        ), 
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Category", ET_DOMAIN),
            "param_name" => "category",
            "value"      => $categories_arr,
        ),           
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Paginate", ET_DOMAIN),
            "param_name" => "paginate",
            "value"      => array('None' => '0', 'Load more' => 'load_more', 'Page paginate' => 'page'),
        ),  
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Style", ET_DOMAIN),
            "param_name" => "style",
            "value"      => array('Vertical' => 'vertical', 'Horizontal' => 'horizontal'),
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