<?php
/**
 * Loop place with status is pending on page author
 */
?>
<ul class="list-posts list-places fullwidth" id="publish-places" data-list="pending" data-thumb="medium_post_thumbnail">
	<?php 
		global $wp_query;
		$post_arr       =   array();
		
		add_filter( 'posts_orderby', 'order_by_post_status' );
		$pending = new WP_Query(array(
				'post_type'   => 'place',
				'post_status' => array('reject' ,'pending','publish', 'archive', 'draft'),
				'author' => get_query_var( 'author' ),
				'paged' => get_query_var( 'paged' ), 
				// 'orderby' => 'post_status', 
				// 'order' => 'ASC'
			));
		
		remove_filter( 'posts_orderby', 'order_by_post_status' );

		if($pending->have_posts()){
			while ($pending->have_posts()) {
				$pending->the_post();
				global $post, $ae_post_factory;
				/**
				 * convert
				*/
				$ae_post    =   $ae_post_factory->get('place');
				$convert    =   $ae_post->convert($post, 'medium_post_thumbnail');
				$post_arr[] =   $convert;

				// get template template/author-loop-place.php
				get_template_part( 'template/author-loop' , 'place' );
			}

			echo '<script type="json/data" class="postdata"  id="ae-pending-posts"> ' . json_encode($post_arr) . '</script>';
		} else {
			//  notfound text
			get_template_part( 'template/place', 'notfound' );
		}
	?>
</ul> 
<?php 
	ae_pagination($pending);
	wp_reset_query();
?>