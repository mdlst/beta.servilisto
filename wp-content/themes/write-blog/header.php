<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Write_Blog
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <?php wp_head(); ?>
    </head>
<body <?php body_class(); ?>>
<?php if (function_exists('wp_body_open')) {
    wp_body_open();
}
?>
<?php
$enable_preloader = write_blog_get_option('enable_preloader', true);
$style = 'style="display:none"';
if ($enable_preloader) {
    $style = '';
}
?>
    <div class="preloader" <?php echo $style; ?>>
        <div class="loader-wrapper">
            <div class="blobs">
                <div class="blob"></div>
                <div class="blob"></div>
                <div class="blob"></div>
                <div class="blob"></div>
                <div class="blob"></div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                <defs>
                    <filter id="goo">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"/>
                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"/>
                        <feBlend in="SourceGraphic" in2="goo"/>
                    </filter>
                </defs>
            </svg>
        </div>
    </div>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'write-blog'); ?></a>
    <header id="thememattic-header" class="site-header">
        <?php
        $enable_header_image_class = '';
        if (has_header_image()) {
            $enable_header_image_class = "header-image-enabled";
        } else {
            $enable_header_image_class = "header-image-disabled";
        }
        ?>

        <?php $header_image = write_blog_get_option('enable_header_overlay', false);
        $header_image_class = "";
        if ($header_image = false) {
            $header_image_class = "header-overlay-disabled";
        } else {
            $header_image_class = "header-overlay-enabled";
        }
        ?>
        <div class="thememattic-midnav data-bg <?php echo esc_attr($enable_header_image_class); ?> <?php echo esc_attr($header_image_class); ?>"
             data-background="<?php echo(get_header_image()); ?>">
            <div class="container-fluid">
                <div class="site-branding">
                    <?php
                    the_custom_logo();
                    if (is_front_page() && is_home()) : ?>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                  rel="home"><?php bloginfo('name'); ?></a></h1>
                    <?php else : ?>
                        <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                 rel="home"><?php bloginfo('name'); ?></a></p>
                    <?php
                    endif;

                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) : ?>
                        <p class="site-description primary-font">
                            <?php echo $description; ?>
                        </p>
                    <?php
                    endif;
                    ?>
                </div>
                <div class="thememattic-navigation">
                    <nav id="site-navigation" class="main-navigation">
                            <span class="toggle-menu" aria-controls="primary-menu" aria-expanded="false" tabindex="0">
                                 <span class="screen-reader-text">
                                    <?php esc_html_e('Primary Menu', 'write-blog'); ?>
                                </span>
                                <i class="ham"></i>
                            </span>
                        <?php
                        if (has_nav_menu('menu-1')) {
                            wp_nav_menu(array(
                                'theme_location' => 'menu-1',
                                'menu_id' => 'primary-menu',
                                'container' => 'div',
                                'container_class' => 'menu',
                                'depth' => 3,
                            ));
                        } else {
                            wp_nav_menu(array(
                                'menu_id' => 'primary-menu',
                                'container' => 'div',
                                'container_class' => 'menu',
                                'depth' => 3,
                            ));
                        } ?>


                        <?php if (has_nav_menu('social-nav')) { ?>
                            <div class="header-social-icon hidden-xs">
                                <div class="social-icons">
                                    <?php
                                    wp_nav_menu(
                                        array('theme_location' => 'social-nav',
                                            'link_before' => '<span>',
                                            'link_after' => '</span>',
                                            'menu_id' => 'social-menu',
                                            'fallback_cb' => false,
                                            'menu_class' => false
                                        )); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="icon-search">
                            <i class="thememattic-icon ion-ios-search"></i>
                        </div>
                    </nav><!-- #site-navigation -->
                </div>
            </div>

            <?php $enable_header_images = write_blog_get_option('enable_header_overlay', false);
            if ($enable_header_images == true) { ?>
                <div class="header-image-overlay"></div>
            <?php }
            ?>
        </div>

    </header><!-- #masthead -->

    <div class="popup-search">
        <div class="table-align">
            <div class="table-align-cell">
                <?php get_search_form(); ?>
            </div>
        </div>
        <div class="close-popup"></div>
    </div>

<?php
if (is_front_page()) {
    /**
     * Hook - write_blog_home_section.
     *
     * @hooked write_blog_banner_content - 10
     * @hooked write_blog_featured_categories - 20
     * @hooked write_blog_home_full_grid_cat - 30
     * @hooked write_blog_home_panel_grid_cat - 40
     */
    do_action('write_blog_home_section');
} else {
    /**
     * Hook - write_blog_inner_header.
     *
     * @hooked write_blog_display_inner_header - 10
     */
    do_action('write_blog_inner_header');
    ?>
    <div id="content" class="site-content">
    <?php
}