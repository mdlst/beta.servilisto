<?php
    global $ae_post_factory;
    $review_object = $ae_post_factory->get('de_review'); // get review object

    $de_review = $review_object->current_comment;
    /**
     * review author details
    */
    $author = isset($de_review->author_data) ? $de_review->author_data : '';
    $post_data = $de_review->post_data;
    $comments = wp_count_comments($post_data->ID);

    $term = $post_data->place_category;
    $categoy = new AE_Category();
    $color = $categoy->get_term_color($term[0],'place_category');
    $color = ($color) ? $color : '#eb5256';
?>
<li class="col-md-12 review-item">
    <div class="place-review">
        <div class="place-image-wrapper vertical <?php if(isset($post_data->tax_input['place_category'][0])) { echo $post_data->tax_input['place_category'][0]->slug; } ?>">
            <span class="number-comment"><i class="fa fa-comment"></i>&nbsp;<?php echo $post_data->reviews_count;//(int)wp_count_comments($post_data->ID) + (int)$post_data->reviews_count; ?></span>
            <span class="img"><img src="<?php echo $post_data->small_post_thumbnail; ?>" alt="<?php echo $post_data->post_title ?>"/></span>
            <div class="place-review-top">
                <h2><a href="<?php echo get_comment_link($de_review->comment_ID); ?>"><?php echo $post_data->post_title; ?></a></h2>
                <span class="address-place"><i class="fa fa-map-marker"></i>
                    <span itemprop="latitude" id="latitude" content="<?php echo $post_data->et_location_lat;?>"></span>
                    <span itemprop="longitude" id="longitude" content="<?php echo $post_data->et_location_lng;?>"></span>
                    <span class="distance"></span> 
                    <?php echo $post_data->et_full_location; ?>
                </span>
            </div>
            <div class="clearfix"></div>
            <div class="border-review" style="background: <?php echo $color;?>;"></div>
        </div>
        <div class="place-review-bottom-wrapper">
            <div class="place-review-bottom">
                <?php if($author) { ?> 
                    <a href="<?php echo $author->author_url; ?>" title="<?php echo $author->display_name; ?>" class="name-author"><?php echo $author->display_name; ?></a>
                <?php }else { ?>
                    <a href="#" title="<?php echo $de_review->comment_author; ?>" class="name-author"><?php echo $de_review->comment_author; ?></a>
                <?php } ?>
                <span class="quote">
                    <img src="<?php echo get_template_directory_uri() ?>/img/quote.png" alt="quote">
                    <?php echo $de_review->comment_content;  ?>
                </span>
                <div class="time">
                    <span style="display:inline-block;">
                        <i class="fa fa-clock-o"></i><?php echo et_the_time(strtotime($de_review->comment_date)); ?>
                    </span>
                    <div style="display: inline-block; margin-left: 5px;" class="rate-it" data-score="<?php echo $de_review->et_rate; ?>"></div>
                </div>
            </div>
        </div>
    </div>
</li>
