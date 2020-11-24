<?php 
	global $ae_post_factory;
    $review_object = $ae_post_factory->get('de_review'); // get review object
    $de_review = $review_object->current_comment;
    /**
     * review author details
    */
    $author = isset($de_review->author_data) ? $de_review->author_data : '';
    $post_data = $de_review->post_data;
?>
<li class="review-item col-lg-3 col-md-4 col-sm-4">
	<div class="wrap-place-publishing">
		<a href="<?php echo get_comment_link($de_review->comment_ID); ?>" class="place-publishing-img">
			<img src="<?php echo $post_data->the_post_thumnail; ?>" title="<?php echo $post_data->post_title; ?>" />
		</a>
		<h2 class="place-publishing-title"><a href="<?php echo get_comment_link($de_review->comment_ID); ?>" title="<?php echo $post_data->post_title; ?>"><?php echo $post_data->post_title; ?></a></h2>
		<span class="place-publishing-map"><i class="fa fa-map-marker"></i>
			<span itemprop="latitude" id="latitude" content="<?php echo $post_data->et_location_lat;?>"></span>
            <span itemprop="longitude" id="longitude" content="<?php echo $post_data->et_location_lng;?>"></span>
            <span class="distance"></span>
			<?php echo $post_data->et_full_location; ?>
		</span>
		<div class="reviews">
			<p class="username">
				<?php if($author) { ?> 
                    <a href="<?php echo $author->author_url; ?>" title="<?php echo $author->display_name; ?>" class="name-author"><?php echo $author->display_name; ?></a>
                <?php }else { ?>
                    <a href="#" title="<?php echo $de_review->comment_author; ?>" class="name-author"><?php echo $de_review->comment_author; ?></a>
                <?php } ?>
			</p>
			<p class="text"><img src="<?php echo get_template_directory_uri();?>/img/quote.png"><?php echo wp_trim_words($de_review->comment_content,'4'); ?></p>
			<span class="time pull-left"><i class="fa fa-clock-o"></i><?php echo et_the_time(strtotime($de_review->comment_date)); ?></span>
			<div class="rate-it pull-right" data-score="<?php echo $de_review->et_rate; ?>"></div>
		</div>
	</div>
</li>