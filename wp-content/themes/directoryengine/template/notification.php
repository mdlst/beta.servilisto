<?php 
/**
 * NOTIFICATION
 */
?>

<div class="notification-places">
	<div class="container">
		<p>
			<i class="fa fa-exclamation-circle"></i>
			<?php 
				$place_pending = new WP_Query(array(
						'post_type'   => 'place',
						'post_status' => array('pending'),
						'showposts'=> -1
				));
				if($place_pending->found_posts > 1){
					$count_pending = sprintf(__("Hay <span><number>%s</number> anuncios pendientes</span>, esperando su aprobación. <span class='btn-pending-places'>Verlos</span>", ET_DOMAIN), $place_pending->found_posts);
				}else{
					$count_pending = sprintf(__("Hay <span><number>%s</number> anuncio pendiente</span>, esperando su aprobación. <span class='btn-pending-places'>Verlo</span>", ET_DOMAIN), $place_pending->found_posts);
				}
				echo $count_pending;
				wp_reset_query();
			?>
		</p>
		<span class="notification-hide"><i class="fa fa-times"></i></span>
	</div>
	
</div>
<div  class="noti-pending-places-wrap" style="display:none;">
	<div class="noti-pending-places">
		<ul class="list-pending-places" id="list-pending-places" data-load='1'>
			<?php 
				$pending = new WP_Query(array(
					'post_type' 		=> 'place',
					'post_status' 		=> array('pending'),
					'paginate'			=> 'load_more',
					'showposts'			=> 6,
					'paged' => get_query_var( 'paged' )
				));
				if($pending->have_posts()){
					global $post, $ae_post_factory;
					while ($pending->have_posts()) {
						$pending->the_post();
						/**
						 * convert
						 */
						$ae_post    =   $ae_post_factory->get('place');
						$convert    =   $ae_post->convert($post, 'medium_post_thumbnail');
						$post_arr[] =   $convert;

						// get template template/author-loop-place.php
						get_template_part( 'template/loop' , 'notification' );
					}
					echo '<script type="json/data" class="ae_query"  id="ae-pending-notification"> ' . json_encode($pending->query) . '</script>';
					echo '<script type="json/data" class="postdata"  id="ae-pending-notification"> ' . json_encode($post_arr) . '</script>';
				}
			?>
		</ul>
	</div>
</div>
<div class="noti-marsk-black" style="display:none;"></div>
