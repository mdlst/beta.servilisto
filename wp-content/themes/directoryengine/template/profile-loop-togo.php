<?php
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->current_post;

?>
<li class="togo-item col-lg-3 col-md-4 col-sm-4">
	<div class="wrap-place-publishing">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="place-publishing-img">
			<img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>"/>
		</a>
		<h2 class="place-publishing-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<span class="place-publishing-map"><i class="fa fa-map-marker"></i>
			<span itemprop="latitude" id="latitude" content="<?php echo $post->et_location_lat;?>"></span>
			<span itemprop="longitude" id="longitude" content="<?php echo $post->et_location_lng;?>"></span>
			<span class="distance"></span>
			<?php echo $post->et_full_location ?>
		</span>
		<div class="rate-it" data-score="<?php echo $post->rating_score ?>" data-id="<?php echo $post->ID; ?>"></div>
	</div>
</li>