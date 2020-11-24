<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Write_Blog
 */
?>
<?php
if( is_front_page() ) {
    /**
     * Hook - write_blog_home_section.
     */
    do_action('write_blog_home_footer_section');
}
if( !is_front_page() ) {
    ?>
    </div><!-- #content -->
    <?php
}
?>
<footer id="colophon" class="site-footer">
    <?php if (is_active_sidebar('footer-col-one') || is_active_sidebar('footer-col-two') || is_active_sidebar('footer-col-three')): ?>
        <div class="footer-widget-area">
            <div class="container-fluid">
                <div class="row">
                    <?php if (is_active_sidebar('footer-col-one')) : ?>
                        <div class="col-md-4">
                            <?php dynamic_sidebar('footer-col-one'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-col-two')) : ?>
                        <div class="col-md-4">
                            <?php dynamic_sidebar('footer-col-two'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-col-three')) : ?>
                        <div class="col-md-4">
                            <?php dynamic_sidebar('footer-col-three'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $copyright_text = write_blog_get_option('copyright_text', true);
    if ($copyright_text):
    ?>
        <div class="site-copyright">
            <div class="container-fluid">
                <span><?php echo wp_kses_post($copyright_text);?></span>
                <?php
                $enable_footer_credit = write_blog_get_option('enable_footer_credit', true);
                if ($enable_footer_credit) {
                    printf(esc_html__('Theme: %1$s by %2$s', 'write-blog'), 'Write Blog', '<a href="http://thememattic.com/" target = "_blank" rel="designer">Thememattic</a>');
                }
                ?>
            </div>
        </div>
    <?php endif;?>
</footer>
</div>
<a id="scroll-up" class="secondary-background"><i class="ion-ios-arrow-up"></i></a>
<?php wp_footer(); ?>

</body>
</html>
