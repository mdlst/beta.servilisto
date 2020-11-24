<?php
add_filter('ae_admin_menu_pages', 'de_multirating_menu');
/**
 *Add a setting page
 * @param $pages
 * @return array
 */
function de_multirating_menu($pages) {
  new AE_CategoryAjax(new AE_Category(array(
        'taxonomy' => 'review_criteria'
    )));
    $sections = array();
    $options = AE_Options::get_instance();
     /**
     * DE MultiRating settings section
     */
    $sections[] = array(
        'args' => array(
            'title' => __("General setting", ET_DOMAIN) ,
            'id' => 'de-multirating-settings',
            'icon' => 'y',
            'class' => ''
        ) ,
        'groups' => array(
            
            array(
                'args' => array(
                    'title' => __("Maximum number of criterias", ET_DOMAIN) ,
                    'id' => 'de-multirating-max-num',
                    'class' => '',
                    'desc' => __("Set up how many criterias a place can have. (Recommend < 5)", ET_DOMAIN)
                ) ,
                
                'fields' => array(
                    array(
                        'id' => 'de_multirating_max_num',
                        'type' => 'text',
                        'title' => __("Maximum number of criterias", ET_DOMAIN) ,
                        'name' => 'de_multirating_max_num',
                        'placeholder' => __("eg:5", ET_DOMAIN) ,
                        'class' => 'gt_zero',
                        'default'=> 5
                    )
                )
            ),
            array(
                    'type' => 'cat',
                    'args' => array(
                        'title'     => __("Review criterias", ET_DOMAIN) ,
                        'taxonomy'  => 'review_criteria',
                        'id'        => 'review_criteria',
                        'class'     => '',
                        'name'      => 'review_criteria',
                        'desc'      => __("Review criterias.", ET_DOMAIN) ,
                        'use_icon'  => 0,
                        'use_color' => 0,
                        'hierarchical'=> 0,
                    ) ,
                    'fields' => array(),
                ),
            array(
                'args' => array(
                    'title' => __("Order options", ET_DOMAIN) ,
                    'id' => 'de_multirating_order_by',
                    'class' => '',
                    'desc' => __("Select the argument option to order review criterias.", ET_DOMAIN)
                ) ,
                'fields' => array(
                    array(
                        'id' => 'de_multirating_orderby',
                        'type' => 'select',
                        'data' => array(
                            'name' => __("name", ET_DOMAIN),
                            'count' => __("count", ET_DOMAIN),
                            'slug' => __("slug", ET_DOMAIN),
                            'term_group' => __("term_group", ET_DOMAIN),
                            'term_order' => __("term_order", ET_DOMAIN),
                            'term_id' => __("term_id", ET_DOMAIN),
                            'none' => __("none", ET_DOMAIN)
                        ),
                        'title' => __("Select orderby", ET_DOMAIN) ,
                        'name' => 'de_multirating_orderby',
                        'class' => 'option-item bg-grey-input '
                    ),
                    array(
                        'id' => 'de_multirating_order',
                        'type' => 'select',
                        'data' => array(
                            'ASC' => __("ASC", ET_DOMAIN),
                            'DESC' => __("DESC", ET_DOMAIN)
                        ),
                        'title' => __("Select order", ET_DOMAIN) ,
                        'name' => 'de_multirating_order',
                        'class' => 'option-item bg-grey-input ',
                        'placeholder' => __("Select order", ET_DOMAIN),
                        'label' => __("Select order", ET_DOMAIN)
                    )
                )
            ),
        )
    );
    $sections[] = array(
        'args' => array(
            'title' => __("Critical Setting", ET_DOMAIN) ,
            'id' => 'multirating-category-settings',
            'icon' => 'i',
            'class' => ''
        ) ,
        'groups' => array(
            array(
                'args' => array(
                    'title' => __("Set up criterions based on categories", ET_DOMAIN) ,
                    'id' => 'critical_categories',
                    'class' => 'wrapper-critical',
                    'desc' => __("If you enable this option, each category will have it own criterions", ET_DOMAIN)
                ) ,
                
                'fields' => array(
                    array(
                        'id' => 'enable_critical',
                        'type' => 'switch',
                        'label' => __("Enable will display multiple map marker icons in category of current place.", ET_DOMAIN) ,
                        'name' => 'enable_critical',
                        'class' => 'option-item bg-grey-input'
                    )
                )
            ) ,
            array(
                'type' => 'critical',
                'args' => array(
                    'title' => __("Add criterions based on categories", ET_DOMAIN) ,
                    'id' => 'de-multirating-max-num',
                    'class' => '',
                    'desc' => __("Set up different criterions for different categories", ET_DOMAIN)
                ),
                'fields' =>array()
            ),
        )
    );
    $temp = array();
    foreach ($sections as $key => $section) {
        $temp[] = new AE_section($section['args'], $section['groups'], $options);
    }
    
    $orderlist = new AE_container(array(
        'class' => 'field-settings',
        'id' => 'settings',
    ) , $temp, $options);
    
    $pages[] = array(
        'args' => array(
            'parent_slug' => 'et-overview',
            'page_title' => __('DE MultiRating', ET_DOMAIN) ,
            'menu_title' => __('DE MultiRating', ET_DOMAIN) ,
            'cap' => 'administrator',
            'slug' => 'de-multirating',
            'icon' => 'x',
            'desc' => __("MultiRating", ET_DOMAIN)
        ) ,
        'container' => $orderlist
    );
    
    return $pages;
}