<?php
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->current_post;
$review_object = $ae_post_factory->get('de_review'); // get review object

$de_review = $review_object->current_comment;

/**
 * review author details
*/
$author = $de_review->author_data;
$post_data = $de_review->post_data;
?>
<li <?php post_class( 'post-item' ); ?> >
    <div class="place-wrapper">
        <a href="<?php echo $post_data->permalink; ?>" class="img-place">
            <img src="<?php echo $post_data->the_post_thumnail; ?>" />
            <?php if(isset($post_data->ribbon) && $post_data->ribbon){ ?>
            <div class="cat-<?php echo $post_data->place_category[0]; ?>">
                <div class="ribbon">
                    <span class="ribbon-content"><?php echo $post_data->ribbon; ?></span>
                </div>
            </div>
            <?php } ?>
        </a>
        <div class="place-detail-wrapper">
            <h2 class="title-place"><a href="<?php echo $post_data->permalink; ?>" title="<?php echo $post_data->post_title; ?>" ><?php echo $post_data->post_title; ?></a></h2>
            <span class="address-place"><i class="fa fa-map-marker"></i>
                <span itemprop="latitude" id="latitude" content="<?php echo $post_data->et_location_lat;?>"></span>
                <span itemprop="longitude" id="longitude" content="<?php echo $post_data->et_location_lng;?>"></span>
                <span class="distance"></span>
                <?php echo $post_data->et_full_location; ?></span>
        </div>
    </div>
    <div class="content-review">
        <h5><?php echo $author->display_name;?></h5>
        <p><img src="<?php echo get_template_directory_uri();?>/img/quote.png"> <?php echo $de_review->comment_content;?> </p>
        <div class="row">
            <div class="col-sm-7 col-xs-7 no-padding-right"><i class="fa fa-clock-o"></i><?php echo $de_review->date_ago;?></div>
            <div class="col-sm-1 col-xs-1 no-padding"><i class="fa fa-comment"></i><?php echo $post_data->reviews_count; ?></div>
            <div class="col-sm-4 col-xs-4 no-padding-left"><div class="rate-it" data-score="<?php echo $post_data->rating_score; ?>"></div></div>
        </div>
    </div>
</li>