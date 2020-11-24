<ul class="list-places fullwidth" id="place-pending-list">
<?php 
global $wp_query, $post, $ae_post_factory, $user_ID;/**
 * generate nearby center
*/
if(isset($_REQUEST['center']) && $_REQUEST['center'] != '') {
	$args = array(
	    'post_type' => 'place', 
	    'paged' => get_query_var( 'paged' ) , 
	    'meta_key' => 'rating_score',
	    'post_status' => array('pending'),
        'orderby' => 'post__in'
	);

    $center = explode(',', $_REQUEST['center']);
    $args['near_lat'] = $center[0];
    $args['near_lng'] = $center[1];
    unset($_REQUEST['center']);
    $args['radius'] = (ae_get_option('nearby_distance'))? ae_get_option('nearby_distance') : 10;

    $place_obj = $ae_post_factory->get('place');
	// $search_query    =   $place_obj->nearbyPost($args);
    $search_query    =   new WP_Query( $args );

}else{
	$search_query = $wp_query;
}
$data_arr = array();
if($search_query->have_posts()){
while($search_query->have_posts()) { $search_query->the_post(); 
     
	$place_obj = $ae_post_factory->get('place');
    // covert post
    $convert = $place_obj->convert($post, 'thumbnail');
    $data_arr[] = $convert;
    get_template_part( 'mobile/template/loop', 'place' );

}}
else
{
    ?><div class="event-active-wrapper">
    <div class="col-md-12">
        <div class="event-wrapper tab-style-event">
            <h2 class="title-envent"><?php _e("Currently, there are not event yet.", ET_DOMAIN); ?></h2>
        </div>
    </div>
    </div><?php
}?>
        
</ul>
<?php
echo '<script type="json/data" class="postdata" > ' . json_encode($data_arr) . '</script>';
echo '<div class="paginations-wrapper">';
ae_pagination($search_query, 1, 'load_more');
echo '</div>';
wp_reset_postdata();