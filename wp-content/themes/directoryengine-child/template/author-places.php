<ul class="list-posts list-places" id="publish-places" data-list="publish" data-thumb="big_post_thumbnail">
	<?php 
		global $wp_query;	
		$post_arr       =   array();
		
		if(have_posts()){
			while (have_posts()) {
				the_post();
				global $post, $ae_post_factory;

				$ae_post    =   $ae_post_factory->get('place');
				$convert    =   $ae_post->convert($post, 'big_post_thumbnail');
				$post_arr[] =   $convert;

				get_template_part( 'template/loop' , 'place' );
			}

			echo '<script type="json/data" class="postdata"  id="ae-publish-posts"> ' . json_encode($post_arr) . '
			</script>';
		} else {
			//  notfound text
			get_template_part( 'template/place', 'notfound' );
		}
	?>
</ul> 
<?php 
	ae_pagination($wp_query);
	wp_reset_query();
?>