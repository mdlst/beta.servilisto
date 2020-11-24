<?php

/*Get default values to set while building customizer elements*/
$default_options = write_blog_get_default_customizer_values();

/*Get image sizes*/
$image_sizes = write_blog_get_all_image_sizes(true);

/* ========== Site title text size option added to default Site Identity section ========== */

$wp_customize->add_setting(
    'theme_options[enable_header_overlay]',
    array(
        'default'           => $default_options['enable_header_overlay'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_header_overlay]',
    array(
        'label'    => __( 'Enable Header Overlay', 'write-blog' ),
        'section'  => 'header_image',
        'type'     => 'checkbox',
    )
);
/*Site title text size*/
$wp_customize->add_setting(
    'theme_options[site_title_text_size]',
    array(
        'default' => $default_options['site_title_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[site_title_text_size]',
    array(
        'label' => __('Site Title Text Size', 'write-blog'),
        'section' => 'title_tagline',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);
/**/

/* ========== Color Options added to default color section ========== */

/*Primary Color*/
$wp_customize->add_setting(
    'theme_options[primary_color]',
    array(
        'default' => $default_options['primary_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(  new WP_Customize_Color_Control( 
    $wp_customize, 'theme_options[primary_color]',
        array(
            'label' => __('Secondary Color', 'write-blog'),
            'section' => 'colors',
            'type' => 'color',
        )
    )
);

/*Secondary Color*/
$wp_customize->add_setting(
    'theme_options[secondary_color]',
    array(
        'default' => $default_options['secondary_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(  new WP_Customize_Color_Control( 
    $wp_customize, 'theme_options[secondary_color]',
        array(
            'label' => __('Secondary Color', 'write-blog'),
            'section' => 'colors',
            'type' => 'color',
        )
    )
);

/* ========== Color Options Close ========== */

/*Add Home Page Options Panel.*/
$wp_customize->add_panel(
    'theme_home_option_panel',
    array(
        'title' => __( 'Front Page Options', 'write-blog' ),
        'description' => __( 'Contains all front page settings', 'write-blog')
    )
);
/**/

/* ========== Home Page Slider Section ========== */
$wp_customize->add_section(
    'home_banner_options' ,
    array(
        'title' => __( 'Slider Options', 'write-blog' ),
        'panel' => 'theme_home_option_panel',
    )
);

/*Enable Slider Section*/
$wp_customize->add_setting(
    'theme_options[enable_slider_posts]',
    array(
        'default'           => $default_options['enable_slider_posts'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_slider_posts]',
    array(
        'label'    => __( 'Enable Banner Slider', 'write-blog' ),
        'section'  => 'home_banner_options',
        'type'     => 'checkbox',
    )
);
/**/

$wp_customize->add_setting(
    'theme_options[slider_style_option]',
    array(
        'default'           => $default_options['slider_style_option'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[slider_style_option]',
    array(
        'label'       => __( 'Select Banner Slider Style', 'write-blog' ),
        'section'     => 'home_banner_options',
        'type'        => 'select',
        'choices'     => array(
            'main-slider-default' => __( 'Default Slider', 'write-blog' ),
            'main-slider-default-1' => __( 'Slider Style 2', 'write-blog' )
        ),
    )
);
/*Slider Category.*/
$wp_customize->add_setting(
    'theme_options[slider_cat]',
    array(
        'default'           => $default_options['slider_cat'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    new Write_Blog_Dropdown_Taxonomies_Control(
        $wp_customize,
        'theme_options[slider_cat]',
        array(
            'label'    => __( 'Choose Slider category', 'write-blog' ),
            'section'  => 'home_banner_options',
            'active_callback'  => 'write_blog_is_banner_slider_enabled',
        )
    )
);
/**/
$wp_customize->add_setting(
    'theme_options[enable_slider_below_nav]',
    array(
        'default'           => $default_options['enable_slider_below_nav'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_slider_below_nav]',
    array(
        'label'    => __( 'Enable Slider Navigation Section', 'write-blog' ),
        'section'  => 'home_banner_options',
        'type'     => 'checkbox',
    )
);

/*Number of Slider Posts.*/
$wp_customize->add_setting(
    'theme_options[no_of_slider_posts]',
    array(
        'default'           => $default_options['no_of_slider_posts'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    'theme_options[no_of_slider_posts]',
    array(
        'label'    => __( 'No of Slider Posts', 'write-blog' ),
        'section'  => 'home_banner_options',
        'type'     => 'number',
        'active_callback'  => 'write_blog_is_banner_slider_enabled',
    )
);
/**/

/*Enable Slider Meta Info*/
$wp_customize->add_setting(
    'theme_options[enable_slider_meta_info]',
    array(
        'default'           => $default_options['enable_slider_meta_info'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_slider_meta_info]',
    array(
        'label'    => __( 'Enable Category Info', 'write-blog' ),
        'section'  => 'home_banner_options',
        'type'     => 'checkbox',
        'active_callback'  => 'write_blog_is_banner_slider_enabled',
    )
);
/**/

/*Enable Slider Loop*/
$wp_customize->add_setting(
    'theme_options[enable_slider_loop]',
    array(
        'default'           => $default_options['enable_slider_loop'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_slider_loop]',
    array(
        'label'    => __( 'Loop slider after last slide', 'write-blog' ),
        'section'  => 'home_banner_options',
        'type'     => 'checkbox',
        'active_callback'  => 'write_blog_is_banner_slider_enabled',
    )
);
/**/

/* ========== Home Page Slider Section Close ========== */

/* ========== Home Page Featured Categories Section ========== */
$wp_customize->add_section(
    'home_featured_categories_options' ,
    array(
        'title' => __( 'Featured Categories Options', 'write-blog' ),
        'panel' => 'theme_home_option_panel',
    )
);

/*Enable Featured Categories Section*/
$wp_customize->add_setting(
    'theme_options[enable_ft_categories]',
    array(
        'default'           => $default_options['enable_ft_categories'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_ft_categories]',
    array(
        'label'    => __( 'Enable Featured Categories', 'write-blog' ),
        'section'  => 'home_featured_categories_options',
        'type'     => 'checkbox',
    )
);

/*1st Featured Category*/
$wp_customize->add_setting(
    'theme_options[first_ft_cat]',
    array(
        'default'           => $default_options['first_ft_cat'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    new Write_Blog_Dropdown_Taxonomies_Control(
        $wp_customize,
        'theme_options[first_ft_cat]',
        array(
            'label'    => __( 'Choose First Category', 'write-blog' ),
            'section'  => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*1st Featured Category Image*/
$wp_customize->add_setting(
    'theme_options[first_ft_cat_image]',
    array(
        'default'           => $default_options['first_ft_cat_image'],
        'sanitize_callback' => 'write_blog_sanitize_image',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'theme_options[first_ft_cat_image]',
        array(
            'label'           => __( 'First Category Image', 'write-blog' ),
            'description'	  => sprintf( esc_html__( 'Recommended Size %1$s px X %2$s px', 'write-blog' ), 750, 90 ),
            'section'         => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*2nd Featured Category*/
$wp_customize->add_setting(
    'theme_options[second_ft_cat]',
    array(
        'default'           => $default_options['second_ft_cat'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    new Write_Blog_Dropdown_Taxonomies_Control(
        $wp_customize,
        'theme_options[second_ft_cat]',
        array(
            'label'    => __( 'Choose Second Category', 'write-blog' ),
            'section'  => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*2nd Featured Category Image*/
$wp_customize->add_setting(
    'theme_options[second_ft_cat_image]',
    array(
        'default'           => $default_options['second_ft_cat_image'],
        'sanitize_callback' => 'write_blog_sanitize_image',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'theme_options[second_ft_cat_image]',
        array(
            'label'           => __( 'Second Category Image', 'write-blog' ),
            'description'	  => sprintf( esc_html__( 'Recommended Size %1$s px X %2$s px', 'write-blog' ), 750, 90 ),
            'section'         => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*3rd Featured Category*/
$wp_customize->add_setting(
    'theme_options[third_ft_cat]',
    array(
        'default'           => $default_options['third_ft_cat'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    new Write_Blog_Dropdown_Taxonomies_Control(
        $wp_customize,
        'theme_options[third_ft_cat]',
        array(
            'label'    => __( 'Choose Third Category', 'write-blog' ),
            'section'  => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*3rd Featured Category Image*/
$wp_customize->add_setting(
    'theme_options[third_ft_cat_image]',
    array(
        'default'           => $default_options['third_ft_cat_image'],
        'sanitize_callback' => 'write_blog_sanitize_image',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'theme_options[third_ft_cat_image]',
        array(
            'label'           => __( 'Third Category Image', 'write-blog' ),
            'description'	  => sprintf( esc_html__( 'Recommended Size %1$s px X %2$s px', 'write-blog' ), 750, 90 ),
            'section'         => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*4th Featured Category*/
$wp_customize->add_setting(
    'theme_options[fourth_ft_cat]',
    array(
        'default'           => $default_options['fourth_ft_cat'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    new Write_Blog_Dropdown_Taxonomies_Control(
        $wp_customize,
        'theme_options[fourth_ft_cat]',
        array(
            'label'    => __( 'Choose Fourth Category', 'write-blog' ),
            'section'  => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/*4th Featured Category Image*/
$wp_customize->add_setting(
    'theme_options[fourth_ft_cat_image]',
    array(
        'default'           => $default_options['fourth_ft_cat_image'],
        'sanitize_callback' => 'write_blog_sanitize_image',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'theme_options[fourth_ft_cat_image]',
        array(
            'label'           => __( 'Fourth Category Image', 'write-blog' ),
            'description'	  => sprintf( esc_html__( 'Recommended Size %1$s px X %2$s px', 'write-blog' ), 750, 90 ),
            'section'         => 'home_featured_categories_options',
            'active_callback'  => 'write_blog_is_ft_cats_enabled',
        )
    )
);

/* ========== Home Page Featured Categories Section Close ========== */

/* ========== Home Page Full Width Grid Section ========== */
$wp_customize->add_section(
    'home_footer_recommend_cat_options' ,
    array(
        'title' => __( 'Footer Recommendation Options', 'write-blog' ),
        'panel' => 'theme_home_option_panel',
    )
);

/*Enable Full Width Grid Category Section*/
$wp_customize->add_setting(
    'theme_options[enable_footer_recommend_cat]',
    array(
        'default'           => $default_options['enable_footer_recommend_cat'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_footer_recommend_cat]',
    array(
        'label'    => __( 'Enable Recommended Post', 'write-blog' ),
        'section'  => 'home_footer_recommend_cat_options',
        'type'     => 'checkbox',
    )
);
$wp_customize->add_setting(
    'theme_options[footer_recommend_cat_title]',
    array(
        'default'           => $default_options['footer_recommend_cat_title'],
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    )
);
$wp_customize->add_control(
    'theme_options[footer_recommend_cat_title]',
    array(
        'label'       => __( 'Related Posts title', 'write-blog' ),
        'section'     => 'home_footer_recommend_cat_options',
        'type'        => 'text',
        'active_callback'  => 'write_blog_is_full_grid_enabled',
    )
);

/*Full Width Grid Category.*/
$wp_customize->add_setting(
    'theme_options[full_width_grid_cat]',
    array(
        'default'           => $default_options['full_width_grid_cat'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    new Write_Blog_Dropdown_Taxonomies_Control(
        $wp_customize,
        'theme_options[full_width_grid_cat]',
        array(
            'label'    => __( 'Choose Recommended Post', 'write-blog' ),
            'section'  => 'home_footer_recommend_cat_options',
            'active_callback'  => 'write_blog_is_full_grid_enabled',
        )
    )
);

/*Number of Category Posts.*/
$wp_customize->add_setting(
    'theme_options[no_of_full_width_cat_posts]',
    array(
        'default'           => $default_options['no_of_full_width_cat_posts'],
        'sanitize_callback' => 'absint'
    )
);
$wp_customize->add_control(
    'theme_options[no_of_full_width_cat_posts]',
    array(
        'label'    => __( 'No of Posts', 'write-blog' ),
        'section'  => 'home_footer_recommend_cat_options',
        'type'     => 'number',
        'active_callback'  => 'write_blog_is_full_grid_enabled',
    )
);
/**/

/*Enable Full Width Grid Meta Info*/
$wp_customize->add_setting(
    'theme_options[enable_full_grid_meta_info]',
    array(
        'default'           => $default_options['enable_full_grid_meta_info'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_full_grid_meta_info]',
    array(
        'label'    => __( 'Enable Meta Info', 'write-blog' ),
        'section'  => 'home_footer_recommend_cat_options',
        'type'     => 'checkbox',
        'active_callback'  => 'write_blog_is_full_grid_enabled',
    )
);
/**/

/*Full Width Cat Section Background Color */
$wp_customize->add_setting(
    'theme_options[full_width_grid_cat_bg_color]',
    array(
        'default'           => $default_options['full_width_grid_cat_bg_color'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
    'theme_options[full_width_grid_cat_bg_color]',
    array(
        'label'    => __( 'Background Color', 'write-blog' ),
        'section'  => 'home_footer_recommend_cat_options',
        'type'     => 'color',
        'active_callback'  => 'write_blog_is_full_grid_enabled',
    )
);
/**/

/*Full Width Cat Section Text Color */
$wp_customize->add_setting(
    'theme_options[full_width_grid_cat_text_color]',
    array(
        'default'           => $default_options['full_width_grid_cat_text_color'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
    'theme_options[full_width_grid_cat_text_color]',
    array(
        'label'    => __( 'Text Color', 'write-blog' ),
        'section'  => 'home_footer_recommend_cat_options',
        'type'     => 'color',
        'active_callback'  => 'write_blog_is_full_grid_enabled',
    )
);
/**/

/* ========== Home Page Full Width Grid Close ========== */

/* ========== Home Page Layout Section ========== */
$wp_customize->add_section(
    'home_page_layout_options',
    array(
        'title'      => __( 'Front Page Layout Options', 'write-blog' ),
        'panel'      => 'theme_home_option_panel',
    )
);

/* Home Page Layout */
$wp_customize->add_setting(
    'theme_options[home_page_layout]',
    array(
        'default'           => $default_options['home_page_layout'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[home_page_layout]',
    array(
        'label'       => __( 'Front Page Layout', 'write-blog' ),
        'section'     => 'home_page_layout_options',
        'type'        => 'select',
        'choices'     => array(
            'right-sidebar' => __( 'Content - Primary Sidebar', 'write-blog' ),
            'left-sidebar' => __( 'Primary Sidebar - Content', 'write-blog' ),
            'no-sidebar' => __( 'No Sidebar', 'write-blog' )
        ),
    )
);

/* ========== Home Page Layout Section Close ========== */

/*Add Theme Options Panel.*/
$wp_customize->add_panel(
    'theme_option_panel',
    array(
        'title' => __( 'Theme Options', 'write-blog' ),
        'description' => __( 'Contains all theme settings', 'write-blog')
    )
);
/**/

/* ========== Preloader Section  ========== */
$wp_customize->add_section(
    'preloader_options',
    array(
        'title'      => __( 'Preloader Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);
/*Enable Preloader*/
$wp_customize->add_setting(
    'theme_options[enable_preloader]',
    array(
        'default'           => $default_options['enable_preloader'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[enable_preloader]',
    array(
        'label'    => __( 'Enable Preloader', 'write-blog' ),
        'section'  => 'preloader_options',
        'type'     => 'checkbox',
    )
);

/* ========== Preloader Section Close ========== */

/* ==========  Typography Section ========== */
/*google fonts*/
global $write_blog_google_fonts;
$wp_customize->add_section(
    'typography_options',
    array(
        'title' => esc_html__('Typography', 'write-blog'),
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

/*Primary Font*/
$wp_customize->add_setting(
    'theme_options[primary_font]',
    array(
        'default' => $default_options['primary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[primary_font]',
    array(
        'label' => __('Primary Font', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'select',
        'choices' => $write_blog_google_fonts,
    )
);

/*Secondary Font*/
$wp_customize->add_setting(
    'theme_options[secondary_font]',
    array(
        'default' => $default_options['secondary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[secondary_font]',
    array(
        'label' => __('Secondary Font', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'select',
        'choices' => $write_blog_google_fonts,
    )
);

/*Paragraph text sie*/
$wp_customize->add_setting(
    'theme_options[p_text_size]',
    array(
        'default' => $default_options['p_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[p_text_size]',
    array(
        'label' => __('Text Size For Paragraph', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);

/*h1 text sie*/
$wp_customize->add_setting(
    'theme_options[h1_text_size]',
    array(
        'default' => $default_options['h1_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[h1_text_size]',
    array(
        'label' => __('Text Size For H1', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);

/*h2 text sie*/
$wp_customize->add_setting(
    'theme_options[h2_text_size]',
    array(
        'default' => $default_options['h2_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[h2_text_size]',
    array(
        'label' => __('Text Size For H2', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);

/*h3 text sie*/
$wp_customize->add_setting(
    'theme_options[h3_text_size]',
    array(
        'default' => $default_options['h3_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[h3_text_size]',
    array(
        'label' => __('Text Size For H3', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);

/*h4 text sie*/
$wp_customize->add_setting(
    'theme_options[h4_text_size]',
    array(
        'default' => $default_options['h4_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[h4_text_size]',
    array(
        'label' => __('Text Size For H4', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);

/*h5 text sie*/
$wp_customize->add_setting(
    'theme_options[h5_text_size]',
    array(
        'default' => $default_options['h5_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[h5_text_size]',
    array(
        'label' => __('Text Size For H5', 'write-blog'),
        'section' => 'typography_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);

/* ========== Typography Section Close ========== */

/* ========== Layout Section ========== */
$wp_customize->add_section(
    'layout_options',
    array(
        'title'      => __( 'Layout Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);

/**/

/*Masonry Animation*/
$wp_customize->add_setting(
    'theme_options[masonry_animation]',
    array(
        'default'           => $default_options['masonry_animation'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[masonry_animation]',
    array(
        'label'       => __( 'Masonry Animation', 'write-blog' ),
        'section'     => 'layout_options',
        'type'        => 'select',
        'choices'     => array(
            'none' => __( 'None', 'write-blog' ),
            'default' => __( 'Default', 'write-blog' ),
            'slide-up' => __( 'Slide Up', 'write-blog' ),
            'slide-down' => __( 'Slide Down', 'write-blog' ),
            'zoom-out' => __( 'Zoom Out', 'write-blog' )
        ),
    )
);
/**/

/* Site Layout*/
$wp_customize->add_setting(
    'theme_options[site_layout]',
    array(
        'default'           => $default_options['site_layout'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[site_layout]',
    array(
        'label'       => __( 'Site Layout', 'write-blog' ),
        'section'     => 'layout_options',
        'type'        => 'select',
        'choices'     => array(
            'fullwidth' => __( 'Fullwidth', 'write-blog' ),
            'boxed' => __( 'Boxed', 'write-blog' )
        ),
    )
);

/* Global Layout*/
$wp_customize->add_setting(
    'theme_options[global_layout]',
    array(
        'default'           => $default_options['global_layout'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[global_layout]',
    array(
        'label'       => __( 'Global Layout', 'write-blog' ),
        'section'     => 'layout_options',
        'type'        => 'select',
        'choices'     => array(
            'right-sidebar' => __( 'Content - Primary Sidebar', 'write-blog' ),
            'left-sidebar' => __( 'Primary Sidebar - Content', 'write-blog' ),
            'no-sidebar' => __( 'No Sidebar', 'write-blog' )
        ),
    )
);

/* Image in Archive Page */
$wp_customize->add_setting(
    'theme_options[archive_image]',
    array(
        'default'           => $default_options['archive_image'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[archive_image]',
    array(
        'label'       => __( 'Image in Archive Page', 'write-blog' ),
        'section'     => 'layout_options',
        'type'        => 'select',
        'choices'     => $image_sizes,
    )
);

/* Image in Single Post*/
$wp_customize->add_setting(
    'theme_options[single_post_image]',
    array(
        'default'           => $default_options['single_post_image'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[single_post_image]',
    array(
        'label'       => __( 'Image in Single Posts', 'write-blog' ),
        'section'     => 'layout_options',
        'type'        => 'select',
        'choices'     => $image_sizes,
    )
);

/* Image in Single Page*/
$wp_customize->add_setting(
    'theme_options[single_page_image]',
    array(
        'default'           => $default_options['single_page_image'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[single_page_image]',
    array(
        'label'       => __( 'Image in Single Page', 'write-blog' ),
        'section'     => 'layout_options',
        'type'        => 'select',
        'choices'     => $image_sizes,
    )
);

/* ========== Layout Section Close ========== */

/* ========== Pagination Section ========== */
$wp_customize->add_section(
    'pagination_options',
    array(
        'title'      => __( 'Pagination Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);

/*Pagination Type*/
$wp_customize->add_setting( 
    'theme_options[pagination_type]',
    array(
        'default'           => $default_options['pagination_type'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control( 
    'theme_options[pagination_type]',
    array(
        'label'       => __( 'Pagination Type', 'write-blog' ),
        'section'     => 'pagination_options',
        'type'        => 'select',
        'choices'     => array(
            'default' => esc_html__( 'Default (Older / Newer Post)', 'write-blog' ),
            'numeric' => esc_html__( 'Numeric', 'write-blog' ),
            'button_click_load' => esc_html__( 'Button Click Ajax Load', 'write-blog' ),
            'infinite_scroll_load' => esc_html__( 'Infinite Scroll Ajax Load', 'write-blog' ),
        ),
    )
);
/* ========== Pagination Section Close========== */

/* ========== Breadcrumb Section ========== */
$wp_customize->add_section(
    'breadcrumb_options',
    array(
        'title'      => __( 'Breadcrumb Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);

/* Breadcrumb Type*/
$wp_customize->add_setting(
    'theme_options[breadcrumb_type]',
    array(
        'default'           => $default_options['breadcrumb_type'],
        'sanitize_callback' => 'write_blog_sanitize_select',
    )
);
$wp_customize->add_control(
    'theme_options[breadcrumb_type]',
    array(
        'label'       => __( 'Breadcrumb Type', 'write-blog' ),
        'description' => sprintf( esc_html__( 'Advanced: Requires %1$sBreadcrumb NavXT%2$s plugin', 'write-blog' ), '<a href="https://wordpress.org/plugins/breadcrumb-navxt/" target="_blank">','</a>' ),
        'section'     => 'breadcrumb_options',
        'type'        => 'select',
        'choices'     => array(
            'disabled' => __( 'Disabled', 'write-blog' ),
            'simple' => __( 'Simple', 'write-blog' ),
            'advanced' => __( 'Advanced', 'write-blog' ),
        ),
    )
);
/* ========== Breadcrumb Section Close ========== */

/* ========== Single Posts Section ========== */
$wp_customize->add_section(
    'single_posts_options',
    array(
        'title'      => __( 'Single Post Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);

/*Show Related Posts*/
$wp_customize->add_setting(
    'theme_options[show_related_posts]',
    array(
        'default'           => $default_options['show_related_posts'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[show_related_posts]',
    array(
        'label'    => __( 'Show related Posts', 'write-blog' ),
        'section'  => 'single_posts_options',
        'type'     => 'checkbox',
    )
);
/**/

/* Related Post Title */
$wp_customize->add_setting(
    'theme_options[related_posts_title]',
    array(
        'default'           => $default_options['related_posts_title'],
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    )
);
$wp_customize->add_control(
    'theme_options[related_posts_title]',
    array(
        'label'       => __( 'Related Posts title', 'write-blog' ),
        'section'     => 'single_posts_options',
        'type'        => 'text',
        'active_callback'  => 'write_blog_is_related_posts_enabled',
    )
);
/**/
/* ========== Single Posts Section Close ========== */

/* ========== Archive Section ========== */
$wp_customize->add_section(
    'archive_options',
    array(
        'title'      => __( 'Archive Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);

/*Show Description on archive pages*/
$wp_customize->add_setting(
    'theme_options[show_desc_archive_pages]',
    array(
        'default'           => $default_options['show_desc_archive_pages'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[show_desc_archive_pages]',
    array(
        'label'    => __( 'Show Description on Archive Pages', 'write-blog' ),
        'section'  => 'archive_options',
        'type'     => 'checkbox',
    )
);
/**/

/*Show Meta Info on archive pages*/
$wp_customize->add_setting(
    'theme_options[show_meta_archive_pages]',
    array(
        'default'           => $default_options['show_meta_archive_pages'],
        'sanitize_callback' => 'write_blog_sanitize_checkbox',
    )
);
$wp_customize->add_control(
    'theme_options[show_meta_archive_pages]',
    array(
        'label'    => __( 'Show Meta Info on Archive Pages', 'write-blog' ),
        'section'  => 'archive_options',
        'type'     => 'checkbox',
    )
);
/**/

/* ========== Archive Section Close ========== */

/* ========== Excerpt Section ========== */
$wp_customize->add_section(
    'excerpt_options',
    array(
        'title'      => __( 'Excerpt Options', 'write-blog' ),
        'panel'      => 'theme_option_panel',
    )
);

/* Excerpt Length */
$wp_customize->add_setting(
    'theme_options[excerpt_length]',
    array(
        'default'           => $default_options['excerpt_length'],
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[excerpt_length]',
    array(
        'label'       => __( 'Excerpt Length', 'write-blog' ),
        'section'     => 'excerpt_options',
        'type'        => 'number',
    )
);
/**/

/*Excerpt text sie*/
$wp_customize->add_setting(
    'theme_options[excerpt_text_size]',
    array(
        'default' => $default_options['excerpt_text_size'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control(
    'theme_options[excerpt_text_size]',
    array(
        'label' => __('Excerpt Font Size', 'write-blog'),
        'section' => 'excerpt_options',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 100, 'style' => 'width: 150px;'),
    )
);
/**/

/* ========== Excerpt Section Close ========== */

/* ========== Footer Section ========== */
$wp_customize->add_section(
    'footer_options' ,
    array(
        'title' => __( 'Footer Options', 'write-blog' ),
        'panel' => 'theme_option_panel',
    )
);
/*Copyright Text.*/
$wp_customize->add_setting(
    'theme_options[copyright_text]',
    array(
        'default'           => $default_options['copyright_text'],
        'sanitize_callback' => 'sanitize_text_field',
        'transport'           => 'postMessage',
    )
);
$wp_customize->add_control(
    'theme_options[copyright_text]',
    array(
        'label'    => __( 'Copyright Text', 'write-blog' ),
        'section'  => 'footer_options',
        'type'     => 'text',
    )
);

/*Footer Background Color*/
$wp_customize->add_setting(
    'theme_options[footer_bg_color]',
    array(
        'default' => $default_options['footer_bg_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
    'theme_options[footer_bg_color]',
    array(
        'label' => __('Footer Background Color', 'write-blog'),
        'section' => 'footer_options',
        'type' => 'color',
    )
);

/*Footer Text Color*/
$wp_customize->add_setting(
    'theme_options[footer_text_color]',
    array(
        'default' => $default_options['footer_text_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
    'theme_options[footer_text_color]',
    array(
        'label' => esc_html__('Footer Text Color', 'write-blog'),
        'section' => 'footer_options',
        'type' => 'color',
    )
);
/* ========== Footer Section Close========== */


