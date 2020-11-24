<?php
/**
 * Loop place with status is publish on page author
 */
?>
<div class="">
	<ul class="list-posts list-places" id="publish-places" data-list="publish" data-thumb="big_post_thumbnail">
		<?php
			global $wp_query;
			$post_arr       =   array();
			add_filter( 'posts_orderby', 'order_by_post_status' );
			$publish = new WP_Query(array(
					'post_type'   => 'place',
					'post_status' => array('publish'),
					'author' => get_query_var( 'author' ),
					'paged' => get_query_var( 'paged' ),
					// 'orderby' => 'post_status',
					// 'order' => 'ASC'
				));

			remove_filter( 'posts_orderby', 'order_by_post_status' );

			if($publish->have_posts()){
				while ($publish->have_posts()) {
					$publish->the_post();
					global $post, $ae_post_factory;

					$ae_post    =   $ae_post_factory->get('place');
					$convert    =   $ae_post->convert($post, 'big_post_thumbnail');
					$post_arr[] =   $convert;

					get_template_part( 'template/loop' , 'place' );
				}
				echo '<script type="json/data" class="postdata"  id="ae-publish-posts"> ' . json_encode($post_arr) . '</script>';
			} else {
				//  notfound text
				get_template_part( 'template/place', 'notfound' );
			}
		?>
	</ul>
	<div class="paginations-wrapper">
	<?php
		ae_pagination($publish);
		wp_reset_query();
	?>
	</div>
</div>
