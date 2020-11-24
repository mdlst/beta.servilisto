<?php

if (!function_exists('write_blog_banner_content')) :
    /**
     * Banner Slider Content.
     *
     * @since 1.0.0
     */
    function write_blog_banner_content(){

        $enable_banner_slider = write_blog_get_option('enable_slider_posts', true);
        if ($enable_banner_slider) {

            $slider_cat = write_blog_get_option('slider_cat', true);
            if (!empty($slider_cat)) {

                $enable_nav = write_blog_get_option('enable_slider_below_nav', true);
                $slider_style = write_blog_get_option('slider_style_option', 'main-slider-default');
                $no_of_slider_posts = write_blog_get_option('no_of_slider_posts', true);
                $enable_slider_meta_info = write_blog_get_option('enable_slider_meta_info', true);

                $post_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => $no_of_slider_posts,
                    'post_status' => 'publish',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'term_id',
                            'terms' => $slider_cat,
                        ),
                    ),
                );
                $slider_post = new WP_Query($post_args);
                if ($slider_post->have_posts()):
                    $slider_nav = '';
                    ?>
                    <?php if($enable_nav){ 
                        $nav_banner = "enabled-nav";
                        } else {
                        $nav_banner = "disabled-nav";
                        }
                    ?>
                    <?php
                    if ($slider_style == 'main-slider-default') {
                        $slider_style = "main-slider-default";
                    } else {
                        $slider_style = "main-slider-default-1";
                    }
                    ?>
                    <section class="section-block main-slider-block  <?php echo esc_html($slider_style); ?> <?php echo esc_html($nav_banner); ?>">
                        <div class="main-slider-area">
                            <?php $rtl_class_c = 'false';
                            if(is_rtl()){ 
                                $rtl_class_c = 'true';
                            }?>
                            <div class="banner-slider" data-slick='{"rtl": <?php echo($rtl_class_c); ?>}'>
                                    <?php
                                    while ($slider_post->have_posts()):$slider_post->the_post();
                                        $slider_img = $slider_img_class = '';
                                        ?>
                                            <div class="slide item">
                                                <div class="slider-wrapper">
                                                    <?php
                                                    if(has_post_thumbnail()){
                                                        $slider_img_class = 'bg-image ';
                                                        $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                                        $slider_img = '<img src="' . esc_url($image) . '">';
                                                    }
                                                    ?>
                                                    <div class="<?php echo esc_attr($slider_img_class);?> slide-bg">
                                                        <?php echo $slider_img; ?>
                                                    </div>
                                                    <div class="slide-text">
                                                        <div class="slide-text-wrapper">
                                                            <?php if($enable_slider_meta_info):?>
                                                                <div class="slide-categories visible hidden-xs">
                                                                    <?php the_category(' ');?>
                                                                </div>
                                                            <?php endif;?>
                                                            <h2 class="slide-title">
                                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if($enable_nav){
                                                /*Slider nav*/
                                                $slider_nav .= '<div class="slider-nav-item"><figure class="slider-article clearfix">';
                                                $slider_nav .= '<span class="slider-nav-image">'.$slider_img.'</span>';
                                                $slider_nav .= '<figcaption class="navtitle-wrapper"><h4 class="slide-nav-title">';
                                                $slider_nav .=  esc_html(wp_trim_words( get_the_title(), 16, ' ...' ));
                                                $slider_nav .= '</h4></figcaption>';
                                                $slider_nav .= '</figure></div>';
                                                /**/
                                            }
                                            ?>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                        </div>
                        <?php if($enable_nav){ ?>
                            <div class="slidenav-area hidden-xs hidden-sm">
                                <div class="slider-nav">
                                    <?php echo $slider_nav;?>
                                </div>
                            </div>
                        <?php
                        } ?>
                    </section>
                <?php endif;
            }
        }
    }
endif;
add_action('write_blog_home_section', 'write_blog_banner_content', 10);
