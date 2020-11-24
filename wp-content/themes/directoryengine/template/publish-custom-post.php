<div class="blog-wrapper" id="blogwrapper">
<?php if(have_posts()) { ?>
    <ul class="list-blog" id="listblog">
    <?php 
        global $wp_query, $ae_post_factory, $post;
            while(have_posts()) { the_post(); 
                
                $ae_post    =   $ae_post_factory->get('post');
                $convert    =   $ae_post->convert($post);
                $post_arr[] =   $convert;
                get_template_part( 'template/loop', 'post' );
        }
        ?>
    </ul>
    <div class="paginations-wrapper" >
    <?php
        ae_pagination($wp_query);
        wp_reset_postdata();
        /*echo '<script type="json/data" class="postdata" id="ae-posts-data"> ' . json_encode($post_arr) . '
        </script>';*/
        get_template_part( 'template-js/loop', 'post' );
    ?>
    </div>
    <?php } ?>
</div>