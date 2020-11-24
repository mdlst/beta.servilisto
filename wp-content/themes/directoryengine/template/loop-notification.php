<?php 
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->get_current_post();
/*Get paid status*/
if($post->et_paid==0)
	$paid_status = __("Unpaid", ET_DOMAIN);
elseif ($post->et_paid==1)
	$paid_status = __("Paid", ET_DOMAIN);
else
	$paid_status = __("Free", ET_DOMAIN);
?>
<li class="pending-item">
	<div class="pending-place-wrapper">
		<a href="<?php the_permalink(); ?>" class="img-place" title="<?php the_title();?>">
			<img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title();?>" />
		</a>
		<div class="pending-place-detail">
			<h2 class="title-pending-place">
				<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a>
			</h2>
			<p class="address-pending-place"><i class="fa fa-map-marker"></i><?php echo $post->et_full_location ?></p>
			<p class="desc-pending-place">
				<?php echo $post->trim_post_content; ?>
			</p>
		</div>
		<div class="action-pending-place">
			<span class="status-pending-place action"><?php echo $paid_status; ?></span>
			<span class="enable-pending-place action" data-action="approve"><i class="fa fa-check"></i></span>
            <span class="disable-pending-place action" data-action="reject"><i class="fa fa-times"></i></span>
        </div>
	</div>
</li>