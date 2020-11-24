<div class="container" id="list-picture-wrapper">
	<?php 
	global $user;
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$pending = new WP_Query(array(
		'post_type'   => array('place','post'),
		'post_status' => array('publish'),
		'author' => $user->ID,
		'posts_per_page' => -1,
	));

	$array_id = array();
	foreach ($pending->posts as $key => $value) {
		$array_id[] = $value->ID;
	}
	$number     = get_option( 'posts_per_page', 10 );
	$query_img_args = array(
	    'author'=> $user->ID,
	    'post_type' => 'attachment',
	    'post_mime_type' =>array(
	                    'jpg|jpeg|jpe' => 'image/jpeg',
	                    'gif' => 'image/gif',
	                    'png' => 'image/png',
	                    ),
	    'post_status' => 'inherit',
	    'paged'       => $paged,
	    //'showposts' => 12,
	    'post_parent__in' => $array_id,
	    );
	$backup_query = new WP_Query( $query_img_args);
	?>
	<ul class="list-picture" id="list-picture">
		<?php
		$picture_arr = array();
		if($backup_query->have_posts()){
			while ( $backup_query->have_posts() ) {
				$backup_query->the_post();
				global $post, $ae_post_factory, $user_ID;
				$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
				$src = $src[0];
				$picture_item = $post;
				$post->src = $src;
				array_push($picture_arr, $post);
		?>
			<li class="col-xs-6">
                <span>
                	<a class="fancybox" href="<?php echo $post->guid;?>">
                        <img width="100%" src="<?php echo $src?>" />
                    </a>
                </span>
            </li>
		<?php
			}
		}
    	?>
    </ul>
    <?php 
    	ae_pagination($backup_query, $paged, 'load_more');
    	wp_reset_query();
    ?>
</div>