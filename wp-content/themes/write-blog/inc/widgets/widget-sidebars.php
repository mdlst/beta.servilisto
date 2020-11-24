<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function write_blog_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'write-blog'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Displays items on sidebar.', 'write-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="thememattic-title-wrapper"><h2 class="widget-title thememattic-title">',
        'after_title' => '</h2></div>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Column One', 'write-blog'),
        'id' => 'footer-col-one',
        'description' => esc_html__('Displays items on footer first column.', 'write-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Column Two', 'write-blog'),
        'id' => 'footer-col-two',
        'description' => esc_html__('Displays items on footer second column.', 'write-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Column Three', 'write-blog'),
        'id' => 'footer-col-three',
        'description' => esc_html__('Displays items on footer third column.', 'write-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'write_blog_widgets_init');