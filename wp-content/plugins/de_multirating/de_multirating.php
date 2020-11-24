<?php 
/**
 * @package Directoryengine Multirating
 */
/*
Plugin Name: DE MultiRating
Plugin URI: http://enginethemes.com/
Description: Allow visitors to rate a post based on multiple criterias and titles
Version: 1.4.2
Author: EngineThemes
Author URI: http://enginethemes.com/
License: GPLv2 or later
Text Domain: enginetheme
*/

define('DE_MULTIRATING_PATH',  dirname(__FILE__));
add_action('after_setup_theme', 'de_multirating_require_lib');
function de_multirating_require_lib() {
    require_once(DE_MULTIRATING_PATH . '/settings.php');
    require_once(DE_MULTIRATING_PATH . '/functions.php');
    //require_once(DE_MULTIRATING_PATH . '/reviews.php');
    require_once(DE_MULTIRATING_PATH . '/template.php');
    require_once(DE_MULTIRATING_PATH . '/update.php');
}
add_action('wp_enqueue_scripts', 'print_style_script');
/**
 * Register css / js
 *
 *@since version 1.0
 */
function print_style_script(){
  
    $max_rc = (int)ae_get_option('de_multirating_max_num', 5);
    if ((int)$max_rc < 1) {
        $max_rc = 1;
    }
	wp_enqueue_style('multirating-style', plugins_url('',__FILE__).'/css/de_multirating.css');
    wp_enqueue_script('multirating-script', plugins_url('',__FILE__) .'/js/de_multirating.js', array('jquery','backbone','appengine', 'magnific-raty'), true, true);
    wp_localize_script('multirating-script', 'de_multirating_globals', array(
        'invalid_content' => "El contenido de la opinión es obligatorio.",
        'invalid_rating' => "Debes valorar todas las secciones",
        'max_review_criterias'=> $max_rc
        ) );
}
/**
 * Hook chosen js in to page DE Multi Rating admin
 * @param $hook
 * @since 1.4
 */
function my_enqueue($hook){
    if ('engine-settings_page_de-multirating' != $hook) {
        return;
    }
     $max_rc = (int)ae_get_option('de_multirating_max_num', 5);
    if ((int)$max_rc < 1) {
        $max_rc = 1;
    }

    wp_enqueue_style('multirating-style', plugins_url('',__FILE__) . '/css/chosen_css.css');
    wp_enqueue_script('chosen_script', plugin_dir_url(__FILE__) . 'js/chosen.js', array('jquery','backbone','appengine'));
    wp_enqueue_script('critical_script', plugin_dir_url(__FILE__) . 'js/critical_category.js', array('jquery','backbone','appengine'));
    wp_localize_script('critical_script', 'de_multirating_globals_critical', array(
        'max_review_criterias'=> $max_rc
        )
    );
}
add_action('admin_enqueue_scripts', 'my_enqueue');

add_filter("comments_template", "ae_multirating_comment_template");
/**
 *Rewrite comment template
 *
 *@since version 1.0
 */
function ae_multirating_comment_template(){
    global $post ;
    if ($post->post_type == 'place') {
        if(THEME_NAME == 'directoryengine')
        {
            return dirname(__FILE__) .'/template/comment-place.php';
        }
        if(THEME_NAME == 'estateengine')
        {
            return dirname(__FILE__) .'/template/comment-property.php';
        }
    }
    return false;
}

add_action('init', 'register_new_taxonomy', 10);
/**
 *Register taxonomy review_criteria
 *
 *@since version 1.0
 */
function register_new_taxonomy(){
 /**
     * Create a taxonomy
     *
     * @uses   Inserts new taxonomy object into the list
     * @uses   Adds query vars
     * @param  string        Name of taxonomy object
     * @param  array|string  Name of the object type for the taxonomy object.
     * @param  array|string  Taxonomy arguments
     * @return null|WP_Error WP_Error if errors, otherwise null.
     */
    
    $labels = array(
        'name' => _x('Review criteias', 'Taxonomy plural name', ET_DOMAIN) ,
        'singular_name' => _x('Review criteias', 'Taxonomy singular name', ET_DOMAIN) ,
        'search_items' => __('Search review criteias', ET_DOMAIN) ,
        'popular_items' => __('Popular review criteias', ET_DOMAIN) ,
        'all_items' => __('All review criteias', ET_DOMAIN) ,
        'edit_item' => __('Edit review criteias', ET_DOMAIN) ,
        'update_item' => __('Update review criteias', ET_DOMAIN) ,
        'add_new_item' => __('Add New review criteias', ET_DOMAIN) ,
        'new_item_name' => __('New review criteia Name', ET_DOMAIN) ,
        'add_or_remove_items' => __('Add or remove review criteia', ET_DOMAIN) ,
        'choose_from_most_used' => __('Choose from most used enginetheme ', ET_DOMAIN) ,
        'menu_name' => __('Review criteia', ET_DOMAIN) ,
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => false,
        'hierarchical' => false,
        'show_tagcloud' => true,
        'show_ui' => true,
        'rewrite' => array(
            'slug' => ae_get_option('review_criteria_slug', 'review_criteria') ,
            'hierarchical' => ae_get_option('review_criteria_hierarchical', false)
        ) ,
        'query_var' => true,
        'capabilities' => array(
            'manage_terms',
            'edit_terms',
            'assign_terms'
        )
    );
    
    register_taxonomy('review_criteria', array(
        'place'
    ), $args);
}
add_filter('et_get_translate_string', 'de_multirating_add_translate_string');
/**
 * hook to add translate string to plugins 
 * @param Array $entries Array of translate entries
 * @since 1.0
 * @author Tambh
 * @return array
 */
function de_multirating_add_translate_string ($entries) {
    $lang_path = dirname(__FILE__).'/lang/default.po';
    if (file_exists($lang_path)) {
        $pot        =   new PO();
        $pot->import_from_file($lang_path, true);
        
        return  array_merge($entries, $pot->entries);    
    }
    return $entries;
}

