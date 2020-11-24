<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Write_Blog
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (is_single()) {
        $archive_div_class = "single-post";
    } else {
        $archive_div_class = "archive-bg-all article-bg article-bg-1";
    } ?>
    <div class="<?php echo esc_attr($archive_div_class); ?>">
        <?php
        $image_option = write_blog_get_image_option();
        if ( 'no-image' != $image_option ){
            if (has_post_thumbnail()) { ?>
                <div class="thememattic-featured-image post-thumb">
                    <?php echo (get_the_post_thumbnail(get_the_ID(), $image_option)); ?> 
                <?php $pic_caption = get_the_post_thumbnail_caption(); 
                if ($pic_caption) { ?>
                    <div class="img-copyright-info">
                        <p><?php echo esc_html($pic_caption); ?></p>
                    </div>
                <?php
                } ?>
                </div>
            <?php }
        }
        ?>
    	<div class="entry-summary">
            <?php if (!is_singular()): ?>
                <header class="entry-header">
                    <?php if( true == write_blog_get_archive_meta_option() ){
                        write_blog_entry_category();
                    } ?>
                    <!-- posted coment -->
                    <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                </header>
                <?php if( true == write_blog_get_archive_meta_option() ){
                    write_blog_posted_date_only();
                    write_blog_posted_comment();
                } ?>
            <?php endif;
            if( true == write_blog_get_archive_desc_option() ){
                the_excerpt();
            }
            ?>
    	</div><!-- .entry-summary -->
    </div>
</article>