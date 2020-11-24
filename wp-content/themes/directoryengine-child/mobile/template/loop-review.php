<?php
global $ae_post_factory;
$review_object = $ae_post_factory->get('de_review'); // get review object
$users = AE_Users::get_instance();
$de_review = $review_object->current_comment;
/**
 * review author details
*/
$author = isset($de_review->author_data) ? $de_review->author_data : '';
$post_data = $de_review->post_data;
?>
<li class="col-xs-12">
    <div class="place-review review-item">
        <div class="place-review-top-wrapper">
            <div class="place-review-top">
                <h2>
                    <a href="<?php echo $post_data->permalink; ?>"><?php echo $post_data->post_title; ?></a>
                </h2>
                <span class="address-place">
                    <i class="fa fa-map-marker"></i>
                    <span itemprop="latitude" id="latitude" content="<?php echo $post_data->et_location_lat;?>"></span>
                    <span itemprop="longitude" id="longitude" content="<?php echo $post_data->et_location_lng;?>"></span>
                    <span class="distance"></span>
                    <?php echo $post_data->et_full_location; ?>
                </span>
                <span class="number-comment"><i class="fa fa-comment"></i><?php echo $post_data->reviews_count; ?></span>
            </div>
        </div>
        <div class="place-image-wrapper">
            <img src="<?php echo $post_data->the_post_thumnail; ?>" alt="<?php echo $post_data->post_title ?>">
        </div>
        <div class="place-review-bottom-wrapper">
            <div class="place-review-bottom">
                <?php if($author) { ?> 
                    <a href="<?php echo $author->author_url; ?>" title="<?php echo $author->display_name; ?>" class="name-author"><?php echo $author->display_name; ?></a>
                <?php }else { ?>
                    <a href="#" title="<?php echo $de_review->comment_author; ?>" class="name-author"><?php echo $de_review->comment_author; ?></a>
                <?php } ?>
                <span class="quote">

                    <?php comment_text(); ?>
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