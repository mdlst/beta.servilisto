<!-- List News -->
<section class="section-wrapper" id="list-news"> 
	<div class="container">
    	<div class="row">
        	<div class="col-xs-12 blog" id="list-blog">
                <?php 
                global $wp_query, $post , $ae_post_factory;
                $post_arr = array();
                $post_object = $ae_post_factory->get('post');
                while(have_posts()) { the_post(); 
                    $post_arr[] = $post_object->convert($post, 'thumbnail');
                    get_template_part( 'mobile/template/loop' , 'post' );
                } 
                ?>
            </div>
            <div class="paginations-wrapper">
            <?php
                echo '<script type="json/data" class="postdata" > ' . json_encode($post_arr) . '</script>';
                ae_pagination($wp_query, 1, 'load_more'); 
            ?>
            </div>
        </div>
    </div>
</section>
<!-- List News / End -->