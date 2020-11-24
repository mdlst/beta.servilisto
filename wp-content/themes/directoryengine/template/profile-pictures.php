<?php 
/**
 * Loop Picture Author 
 * @author ThanhTu
 */
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
    'showposts' => get_option('posts_per_page'),
    //'showposts' => 12,
    'post_parent__in' => $array_id,
    );
$backup_query = new WP_Query( $query_img_args);

?>
<div class="content-picture tab-pane fade" id="tab-picture">
	<ul class="list-picture">
		<?php
		$picture_arr = array();
		if($backup_query->have_posts()){
			while ( $backup_query->have_posts() ) {
				$backup_query->the_post();
				global $post, $ae_post_factory, $user_ID;
				$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium_post_thumbnail' );
				$src = $src[0];
				$picture_item = $post;
				$post->src = $src;
				array_push($picture_arr, $post);
		?>
			<li class="picture-item col-md-3 col-sm-4 col-xs-6">
				<span class="hidden-img">
					<a class="fancybox" href="<?php echo $post->guid;?>">
						<img src="<?php echo $src;?>" alt="<?php echo $post->post_title?>">
					</a>
				</span>
			</li>
		<?php	
			}
		}else{ ?>
			<li class="col-md-12">
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-envent no-title-envent "><?php _e( "Currently, there are not picture yet.", ET_DOMAIN ); ?></h2>
                        </div>
                    </div>
                </div>
            </li>
		<?php }?>
	</ul>
	<?php   ?>
	<?php 
		if($backup_query->have_posts()){
			echo '<div class="paginations-wrapper">';
			ae_pagination($backup_query);
            wp_reset_query();
            echo '<script type="json/data" class="postdata" > ' . json_encode($picture_arr) . '</script>'; 
            echo '</div>';
        }
	?>
	
</div>


