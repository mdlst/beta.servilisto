<?php
if (!function_exists('write_blog_display_inner_header')) :
    /**
     * Inner Pages Header.
     *
     * @since 1.0.0
     */
    function write_blog_display_inner_header()
    {
        if(is_singular()){
            ?>
            <div class="inner-banner">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="primary-font thememattic-bredcrumb">
                                <?php
                                /**
                                 * Hook - write_blog_display_breadcrumb.
                                 *
                                 * @hooked write_blog_breadcrumb_content - 10
                                 */
                                do_action('write_blog_display_breadcrumb');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                            <?php if (!is_page()) { ?>
                                <header class="entry-header">
                                    <div class="entry-meta entry-inner primary-font small-font">
                                        <?php write_blog_posted_on(); ?>
                                    </div>
                                </header>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }else{
                $breadcrumb_type = write_blog_get_option( 'breadcrumb_type', true );
                if ( 'disabled' === $breadcrumb_type ) {
                    return;
                }
                // Bail if Home Page.
                if ( is_front_page() || is_home() ) {
                    return;
                }
            ?>
            <div class="inner-banner">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="primary-font thememattic-bredcrumb">
                                <?php
                                /**
                                 * Hook - write_blog_display_breadcrumb.
                                 *
                                 * @hooked write_blog_breadcrumb_content - 10
                                 */
                                do_action('write_blog_display_breadcrumb');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <?php if (is_404()) { ?>
                                <h1 class="entry-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'write-blog'); ?></h1>
                            <?php } elseif (is_archive()) {
                                the_archive_title('<h1 class="entry-title">', '</h1>');
                                the_archive_description('<div class="taxonomy-description">', '</div>');
                            } elseif (is_search()) { ?>
                                <h1 class="entry-title"><?php printf(esc_html__('Search Results for: %s', 'write-blog'), '<span>' . get_search_query() . '</span>'); ?></h1>
                            <?php } else { ?>
                                <h1 class="entry-title"></h1>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
endif;
add_action('write_blog_inner_header', 'write_blog_display_inner_header');