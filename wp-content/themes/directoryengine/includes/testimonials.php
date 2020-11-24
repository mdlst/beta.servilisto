<?php

/**
 * this file contain all function related to testimonials
 */
add_action('init', 'de_init_testimonial');
function de_init_testimonial() {
    
    /**
     * register post type testimonial to store testimonial details
     */
    register_post_type('testimonial', array(
        'labels' => array(
            'name'               => __('Testimonial', ET_DOMAIN) ,
            'singular_name'      => __('Testimonial', ET_DOMAIN) ,
            'add_new'            => __('Add New', ET_DOMAIN) ,
            'add_new_item'       => __('Add New Testimonial', ET_DOMAIN) ,
            'edit_item'          => __('Edit Testimonial', ET_DOMAIN) ,
            'new_item'           => __('New Testimonial', ET_DOMAIN) ,
            'all_items'          => __('All Testimonials', ET_DOMAIN) ,
            'view_item'          => __('View Testimonial', ET_DOMAIN) ,
            'search_items'       => __('Search Testimonials', ET_DOMAIN) ,
            'not_found'          => __('No testimon found', ET_DOMAIN) ,
            'not_found_in_trash' => __('No Testimonials found in Trash', ET_DOMAIN) ,
            'parent_item_colon'  => '',
            'menu_name'          => __('Testimonials', ET_DOMAIN)
        ) ,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => 'testimonials',
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports' => array(
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'comments',
            'custom-fields'
        )
    ));
}

