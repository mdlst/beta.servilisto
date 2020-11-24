<?php
    global $ae_post_factory;
    $review_object = $ae_post_factory->get('de_review'); // get review object
    $de_review = $review_object->current_comment;
    /**
     * review author details
    */
    $author = isset($de_review->author_data) ? $de_review->author_data : '';
    $post_data = $de_review->post_data;
    // $comments = wp_count_comments($post_data->ID);
    $col = 'col-md-4';
    if(is_author()) $col = 'col-md-3';

?>
<li class="<?php echo $col; ?> col-sm-6 review-item" id="review-<?php echo $de_review->id; ?>">
    <div class="place-review">
        <div class="place-review-top-wrapper">
            <div class="place-review-top">
                <h2>
                    <a href="<?php echo get_comment_link($de_review->comment_ID); ?>"><?php echo $post_data->post_title; ?></a>
                </h2>
                <span class="address-place">
                    <i class="fa fa-map-marker"></i>
                    <span itemprop="latitude" id="latitude" content="<?php echo $post_data->et_location_lat;?>"></span>
                    <span itemprop="longitude" id="longitude" content="<?php echo $post_data->et_location_lng;?>"></span>
                    <span class="distance"></span>
                    <?php echo $post_data->et_full_location; ?>
                </span>
                <span class="number-comment"><i class="fa fa-comment"></i>&nbsp;<?php echo $post_data->reviews_count; //(int)wp_count_comments($post_data->ID) + (int)$post_data->reviews_count; ?></span>
            </div>
        </div>
        <div class="place-image-wrapper">
            <!-- button event for admin control  -->
        <?php /* if( (is_author() && $user_ID == get_query_var( 'author' ) ) ) { ?>
        <ol class="edit-review-option">
            <li style="display:inline-block"><a href="#edit_place" class="action edit" data-target="#" data-action="editReview"><i class="fa fa-pencil"></i></a></li>
            <li style="display:inline-block"><a href="#" class="action delete" data-action="delete"><i class="fa fa-trash-o"></i></a></li>                
        </ol>
        <?php } */ ?>
            <div class="">
                <img class="lazy" data-original="<?php echo $post_data->the_post_thumnail; ?>" alt="<?php echo $post_data->post_title ?>" title="<?php echo $post_data->post_title ?>">
            </div>
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
                        <i class="fa fa-clock-o"></i> <?php echo et_the_time(strtotime($de_review->comment_date)); ?>
                    </span>
                    <!-- rating -->
                    <div class="rate-it" style="display: inline-block; margin-left: 5px;" data-score="<?php echo $de_review->et_rate; ?>" ></div>
                </div>
            </div>
        </div>
    </div>
</li>