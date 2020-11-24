<?php
/**
 * Default customizer values.
 *
 * @package Write_Blog
 */
if ( ! function_exists( 'write_blog_get_default_customizer_values' ) ) :
	/**
	 * Get default customizer values.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default customizer values.
	 */
	function write_blog_get_default_customizer_values() {

		$defaults = array();

        $defaults['enable_header_overlay'] = false;
        $defaults['enable_slider_posts'] = true;
        $defaults['slider_style_option'] = 'main-slider-default';
        $defaults['slider_cat'] = 1;
        $defaults['enable_slider_meta_info'] = true;
        $defaults['no_of_slider_posts'] = 6;
        $defaults['enable_slider_below_nav'] = true;
        $defaults['enable_slider_loop'] = true;

        $defaults['enable_ft_categories'] = false;
        $defaults['first_ft_cat'] = 1;
        $defaults['first_ft_cat_image'] = '';
        $defaults['second_ft_cat'] = 1;
        $defaults['second_ft_cat_image'] = '';
        $defaults['third_ft_cat'] = 1;
        $defaults['third_ft_cat_image'] = '';
        $defaults['fourth_ft_cat'] = 1;
        $defaults['fourth_ft_cat_image'] = '';

        $defaults['enable_footer_recommend_cat'] = false;
        $defaults['footer_recommend_cat_title'] = __( 'You May Have Missed' , 'write-blog');
        $defaults['full_width_grid_cat'] = 1;
        $defaults['no_of_full_width_cat_posts'] = 4;
        $defaults['enable_full_grid_meta_info'] = true;
        $defaults['full_width_grid_cat_bg_color'] = '#f9e3d2';
        $defaults['full_width_grid_cat_text_color'] = '#333';

        // Front Page Layout
        $defaults['home_page_layout'] = 'no-sidebar';

        /* Preloader */
        $defaults['enable_preloader'] = true;

        /* Font and Colors */
        $defaults['primary_color'] = '#33363b';
        $defaults['secondary_color'] = '#fd5b66';
        $defaults['primary_font'] = 'Source+Sans+Pro:300,400,400i,700,700i';
        $defaults['secondary_font'] = 'Playfair+Display:400,400i,700,700i';
        $defaults['site_title_text_size'] = 38;
        $defaults['p_text_size'] = 16;
        $defaults['h1_text_size'] = 32;
        $defaults['h2_text_size'] = 26;
        $defaults['h3_text_size'] = 24;
        $defaults['h4_text_size'] = 18;
        $defaults['h5_text_size'] = 14;

        // Global Layout
        $defaults['enable_masonry_post_archive'] = true;
        $defaults['masonry_animation'] = 'default';
        $defaults['relayout_masonry'] = true;
        $defaults['site_layout'] = 'fullwidth';
        $defaults['global_layout'] = 'right-sidebar';
        $defaults['archive_image'] = 'full';
        $defaults['single_post_image'] = 'full';
        $defaults['single_page_image'] = 'full';

        //Pagination
        $defaults['pagination_type'] = 'infinite_scroll_load';

        //BreadCrumbs
        $defaults['breadcrumb_type'] = 'simple';

        //Single Posts Section
        $defaults['show_related_posts'] = true;
        $defaults['related_posts_title'] = __( 'Related Articles' , 'write-blog');

        //Archive Section
        $defaults['show_desc_archive_pages'] = true;
        $defaults['show_meta_archive_pages'] = true;

        //Excerpt
        $defaults['excerpt_length'] = 40;
        $defaults['excerpt_text_size'] = 16;

	// Footer.
	$defaults['copyright_text'] = esc_html__( 'Copyright &copy; All rights reserved.', 'write-blog' );
        $defaults['enable_footer_credit'] = true;
        $defaults['footer_bg_color'] = '#fafafa';
        $defaults['footer_text_color'] = '#4a4a4a';

		$defaults = apply_filters( 'write_blog_default_customizer_values', $defaults );
		return $defaults;
	}
endif;
