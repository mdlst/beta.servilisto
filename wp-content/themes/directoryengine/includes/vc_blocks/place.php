<?php

/**
 * Class WPBakeryShortCode_de_place_block
 */
class WPBakeryShortCode_de_place_block extends WPBakeryShortCode {

    /**
     * @param $atts
     * @param null $content
     * @return string
     */
    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $showposts = $style = $query = $order = '' ;
        extract(shortcode_atts(array(
            'el_class'          => '',
            'title'             => __('PLACE BLOCK',ET_DOMAIN),
            'style'             => 'vertical',
            'showposts'         => 10,
            'place_category'    => '',
            'location'          => '',
            'query'             => '',
            'order'            => '',
            'paginate'          => '',
            'filterbar'         => '1',
            'defaultdisplay'    => '0',
            'custom_css'        => ''
        ), $atts));

        ob_start();
        the_widget( 'DE_ListPlace_Widget', $atts );
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}

// get all places & locations
global $wpdb;
// places
$query_places = "SELECT *
                        FROM
                            {$wpdb->terms} as t 
                        INNER JOIN 
                            {$wpdb->term_taxonomy} as tax 
                        ON 
                            tax.term_id = t.term_id
                        WHERE 
                            tax.taxonomy = 'place_category' AND tax.count > 0";                     
$places =  $wpdb->get_results($query_places);
// locations
$query_locations = "SELECT * 
                            FROM
                                {$wpdb->terms} as t 
                            INNER JOIN 
                                {$wpdb->term_taxonomy} as tax 
                            ON 
                                tax.term_id = t.term_id
                            WHERE 
                                tax.taxonomy = 'location' AND tax.count > 0";                     
$locations =  $wpdb->get_results($query_locations);
// $places    = get_terms('place_category', array('hide_empty'=>false) );
// $locations = get_terms('location', array('hide_empty'=>false) );

$places_arr    = array(__('All', ET_DOMAIN) => '');
$locations_arr = array(__('All', ET_DOMAIN) => '');

foreach ($places as $place) {
    $places_arr[$place->name] = $place->slug;
}
foreach ($locations as $location) {
    $locations_arr[$location->name] = $location->slug;
}

vc_map( array(
    "base"     => "de_place_block",
    "name"     => __("DE Place", ET_DOMAIN),
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
            "value"      => ""
        ), 
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Query", ET_DOMAIN),
            "param_name" => "query",
            "value"      => array('Featured Posts' => 'featured', 'Recent Posts' => 'recent', 'Has Event' => 'event',),
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Order", ET_DOMAIN),
            "param_name" => "order",
            "value"      => array('Latest' => 'DESC', 'Oldest' => 'ASC', 'Random' => 'rand',),
        ),       
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Category", ET_DOMAIN),
            "param_name" => "place_category",
            "value"      => $places_arr,
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Location", ET_DOMAIN),
            "param_name" => "location",
            "value"      => $locations_arr,
        ),                
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Style", ET_DOMAIN),
            "param_name" => "style",
            "value"      => array('Vertical' => 'vertical', 'Horizontal' => 'horizontal'),
        ),         
        array(
            "type"       => "textfield",
            "class"      => "",
            "heading"    => __("Number Posts", ET_DOMAIN),
            "param_name" => "showposts",
            "value"      => '10',
        ),   
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Paginate", ET_DOMAIN),
            "param_name" => "paginate",
            "value"      => array('none' => '0', 'Page paginate' => 'page', 'Load More' => 'load_more'),
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
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Filter bar", ET_DOMAIN),
            "param_name" => "filterbar",
            "value"      => array('Enable' => '1', 'Disable' => '0'),
        ),
         array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Default display", ET_DOMAIN),
            "param_name" => "defaultdisplay",
            "value"      => array('Grid view' => '0', 'List view' => '1'),
        )
                       
    )
) );