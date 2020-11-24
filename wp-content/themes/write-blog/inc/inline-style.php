<?php
/**
 * CSS related hooks.
 *
 * This file contains hook functions which are related to CSS.
 *
 * @package Write_Blog
 */
if (!function_exists('write_blog_inline_css')) :
    /**
     * Output theme custom CSS.
     *
     * @since 1.0.0
     */
    function write_blog_inline_css()
    {
        global $write_blog_google_fonts;
        $write_blog_primary_color = write_blog_get_option('primary_color', true);
        $write_blog_secondary_color = write_blog_get_option('secondary_color', true);

        $write_blog_cat_grid_bgcolor = write_blog_get_option('full_width_grid_cat_bg_color', true);
        $write_blog_cat_grid_textcolor = write_blog_get_option('full_width_grid_cat_text_color', true);

        $write_blog_primary_font = $write_blog_google_fonts[write_blog_get_option('primary_font', true)];
        $write_blog_secondary_font = $write_blog_google_fonts[write_blog_get_option('secondary_font', true)];

        $write_blog_sitetitle_size = write_blog_get_option('site_title_text_size', true);
        $write_blog_font_size_p = write_blog_get_option('p_text_size', true);
        $write_blog_font_size_h1 = write_blog_get_option('h1_text_size', true);
        $write_blog_font_size_h2 = write_blog_get_option('h2_text_size', true);
        $write_blog_font_size_h3 = write_blog_get_option('h3_text_size', true);
        $write_blog_font_size_h4 = write_blog_get_option('h4_text_size', true);
        $write_blog_font_size_h5 = write_blog_get_option('h5_text_size', true);


        $write_blog_font_excerpt_size = write_blog_get_option('excerpt_text_size', true);

        $mailchimp_bg_color = write_blog_get_option('mailchimp_bg_color', true);

        $write_blog_footer_bg_color = write_blog_get_option('footer_bg_color', true);
        $write_blog_footer_text_color = write_blog_get_option('footer_text_color', true);
        ?>
        <style type="text/css">
            <?php
            if (!empty($write_blog_primary_color) ){
                ?>
            body .primary-background,
            body button:hover,
            body button:focus,
            body input[type="button"]:hover,
            body input[type="reset"]:hover,
            body input[type="reset"]:focus,
            body input[type="submit"]:hover,
            body input[type="submit"]:focus,
            body .widget .social-widget-menu ul li,
            body .comments-area .comment-list .reply,
            body .slide-categories a:hover,
            body .slide-categories a:focus,
            body .widget .social-widget-menu ul li:hover a:before,
            body .widget .social-widget-menu ul li:focus a:before,
            body .ham,
            body .ham:before,
            body .ham:after,
            body .btn-load-more{
                background: <?php echo esc_html($write_blog_primary_color); ?>;
            }

            <?php
            }

            if (!empty($write_blog_secondary_color) ){
                ?>
            body .secondary-background,
            body button,
            body input[type="button"],
            body input[type="reset"],
            body input[type="submit"],
            body .widget.widget_write_blog_tab_posts_widget ul.nav-tabs li.active a,
            body .widget.widget_write_blog_tab_posts_widget ul.nav-tabs > li > a:focus,
            body .widget.widget_write_blog_tab_posts_widget ul.nav-tabs > li > a:hover,
            body .author-info .author-social > a:hover,
            body .author-info .author-social > a:focus,
            body .widget .social-widget-menu ul li a:before,
            body .widget .social-widget-menu ul li:hover,
            body .widget .social-widget-menu ul li:focus,
            body .moretag,
            body .moretag,
            body .thememattic-search-icon:before,
            body .slide-categories a,
            body .search-button.active .thememattic-search-icon:before,
            body .search-button.active .thememattic-search-icon:after,
            body .btn-load-more:hover,
            body .btn-load-more:focus{
                background: <?php echo esc_html($write_blog_secondary_color); ?>;
            }

            body .sticky header:before,
            body a:hover,
            body a:focus,
            body a:active,
            body .main-navigation .menu > ul > li.current-menu-item > a,
            body .main-navigation .menu > ul > li:hover > a,
            body .main-navigation .menu > ul > li:focus > a,
            body .sidr a:hover,
            body .sidr a:focus,
            body .page-numbers.current {
                color: <?php echo esc_html($write_blog_secondary_color); ?>;
            }

            body .ajax-loader,
            body .thememattic-search-icon:after{
                border-color: <?php echo esc_html($write_blog_secondary_color); ?> !important;
            }

            <?php
        }

        if (!empty($mailchimp_bg_color) ){
            ?>
            body .mailchimp-bgcolor  {
                background: <?php echo esc_html($mailchimp_bg_color); ?>;
            }

            <?php
        }

        if (!empty($write_blog_cat_grid_bgcolor) ){
            ?>
            body .section-recommended.section-bg  {
                background: <?php echo esc_html($write_blog_cat_grid_bgcolor); ?>;
            }

            <?php
        }

        if (!empty($write_blog_cat_grid_textcolor) ){
            ?>
            body .section-recommended.section-bg .home-full-grid-cat-section,
            body .section-recommended.section-bg .home-full-grid-cat-section a{
                color: <?php echo esc_html($write_blog_cat_grid_textcolor); ?>;
            }

            <?php
        }

        if (!empty($write_blog_primary_font) ){
            ?>
            body,
            body .primary-font,
            body .site .site-title,
            body .section-title,
            body .site .widget-title,
            body .main-navigation .menu ul li a,
            body .comment-reply-title {
                font-family: <?php echo esc_html($write_blog_primary_font); ?>!important;
            }

            <?php
        }

        if (!empty($write_blog_secondary_font) ){
            ?>
            body h1, body h2, body h3, body h4, body h5, body h6,
            body .secondary-font,
            body .prime-excerpt,
            body blockquote,
            body.single .entry-content:before, .page .entry-content:before{
                font-family: <?php echo esc_html($write_blog_secondary_font); ?> !important;
            }



            <?php
           }

           if (!empty($write_blog_sitetitle_size) ){
           ?>
            body .site-title {
                font-size: <?php echo esc_html($write_blog_sitetitle_size); ?>px !important;
            }

            <?php
            }

            if (!empty($write_blog_font_size_p) ){
                ?>
            body, body button, body input, body select, body textarea, body p {
                font-size: <?php echo absint($write_blog_font_size_p); ?>px !important;
            }

            <?php
        }

        if (!empty($write_blog_font_size_h1) ){
            ?>
            body h1 {
                font-size: <?php echo absint($write_blog_font_size_h1); ?>px;
            }

            <?php
        }

        if (!empty($write_blog_font_size_h2) ){
            ?>
            body h2,
            h2.entry-title{
                font-size: <?php echo absint($write_blog_font_size_h2); ?>px;
            }

            <?php
        }

        if (!empty($write_blog_font_size_h3) ){
            ?>
            body h3 {
                font-size: <?php echo absint($write_blog_font_size_h3); ?>px;
            }

            <?php
        }

        if (!empty($write_blog_font_size_h4) ){
            ?>
            body h4 {
                font-size: <?php echo absint($write_blog_font_size_h4); ?>px;
            }

            <?php
        }

        if (!empty($write_blog_font_size_h5) ){
            ?>
            body h5 {
                font-size: <?php echo absint($write_blog_font_size_h5); ?>px;
            }

            <?php
        }

        if (!empty($write_blog_font_excerpt_size) ){
            ?>
            body .masonry-grid.masonry-col article .entry-content,
            body .masonry-grid.masonry-col article .entry-content p{
                font-size: <?php echo absint($write_blog_font_excerpt_size); ?>px !important;
            }

            <?php
        }

        if (!empty($mailchimp_bg_color) ){
            ?>
            body .mailchimp-bgcolor {
                background: <?php echo esc_html($mailchimp_bg_color); ?>;
            }

            <?php
        }

        if (!empty($write_blog_footer_bg_color) ){
            ?>
            body .footer-widget-area {
                background: <?php echo esc_html($write_blog_footer_bg_color); ?>;
            }

            <?php
        }

        if (!empty($write_blog_footer_text_color) ){
        ?>
            body .footer-widget-area,
            body .site-footer .widget-title,
            body .site-footer,
            body .site-footer a,
            body .site-footer a:visited{
                color: <?php echo esc_html($write_blog_footer_text_color); ?>;
            }

            <?php
        }
        ?>
        </style>
        <?php
    }
endif;