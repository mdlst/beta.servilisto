<?php
global $wp_query;
$wp_query_default = $wp_query;
$query = $wp_query->query_vars;
// Order by Places Featured
$query_args = array(
                'orderby' => 'meta_value_num date',
                'meta_key' => 'et_featured'
            );
$query_args = wp_parse_args($query_args, $query);
$result = new WP_QUERY($query_args);
?>
<div class="col-md-9 publish_place_wrapper" id="publish_place_wrapper" >
    <div class="filter-wrapper">
        <?php get_template_part( 'template/place', 'filter' ); ?>
    </div>

    <div class="clearfix"></div>
    <div class="row">
        <?php if($result->have_posts()) { ?>
            <ul class="list-places list-posts" id="publish-places" data-list="publish" data-thumb="medium_post_thumbnail">
            <?php 
            $post_arr   =   array();
            while ($result->have_posts()) {
                    $result->the_post();
                    global $post, $ae_post_factory;
                    $ae_post    =   $ae_post_factory->get('place');
                    $convert    =   $ae_post->convert($post, 'big_post_thumbnail');
                    $post_arr[] =   $convert;
                    get_template_part( 'template/loop' , 'place' );
                }
                echo '<script type="json/data" class="postdata" id="ae-publish-posts"> ' . json_encode($post_arr) . '</script>';
            ?>
            </ul>
            <div class="paginations-wrapper main-pagination" >
            <?php
                ae_pagination($wp_query);
                wp_reset_postdata();
            ?>
            </div>
            <?php   
        }else {
            get_template_part('template/place', 'notfound' );
        }
        
        if( is_tax() ){
            echo '<script type="data/json"  id="place_cat_slug">'. json_encode(array('slug' => get_query_var( 'term' )) ) .'</script>';     
        }
    ?>
    </div>
</div>