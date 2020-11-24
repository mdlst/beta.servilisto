<?php

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/widgets/widget-sidebars.php';

/* Theme Widgets*/
require get_template_directory() . '/inc/widgets/tab-posts.php';
require get_template_directory() . '/inc/widgets/wb-info.php';
require get_template_directory() . '/inc/widgets/social-menu.php';

/* Register site widgets */
if ( ! function_exists( 'write_blog_widgets' ) ) :
    /**
     * Load widgets.
     *
     * @since 1.0
     */
    function write_blog_widgets() {
        register_widget( 'Write_Blog_Tab_Posts' );
        register_widget( 'Write_Blog_Social_Menu' );
        register_widget( 'Write_Blog_Author_Info' );
    }
endif;
add_action( 'widgets_init', 'write_blog_widgets' );