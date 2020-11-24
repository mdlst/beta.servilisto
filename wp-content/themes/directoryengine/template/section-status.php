<?php

$status = false;
global $wp_query;
if( is_post_type_archive( 'place' ) )  {
    if ($wp_query->post_count > 1) {
        $status = sprintf(__('%d places', ET_DOMAIN) , $wp_query->found_posts);
    } else {
        $status = sprintf(__('%d place', ET_DOMAIN) , $wp_query->found_posts);
    }
    echo '<script type="data/json"  id="total_place">'. json_encode(array('number' => $wp_query->found_posts ) ) .'</script>'; 

}else{
    $queried_object = get_queried_object();
    // place tag status
    if(is_tax('place_tag')) {
        $status = sprintf( __( 'Tag: %s', 'twentyfourteen' ), single_tag_title( '', false ) );
    }

    //  category, location status
    if(!$status) {
        if ($wp_query->found_posts > 1) {
            $status = sprintf(__('%d places in "%s"', ET_DOMAIN) , $wp_query->found_posts, $queried_object->name);
        } else {
            $status = sprintf(__('%d place in "%s"', ET_DOMAIN) , $wp_query->found_posts, $queried_object->name);
        }    
    }    
    echo '<script type="data/json"  id="total_place">'. json_encode(array('number' => $wp_query->found_posts ) ) .'</script>';  
}

?>
<!-- Bar Post Place -->
<section id="bar-post-place-wrapper">
  <div class="container">
      <div class="row">
            <div class="col-md-9 col-xs-8">
                <h1 class="top-title-post-place" id="place-status">
                    <?php echo $status; ?>
                </h1>
            </div>
            <div class="col-md-3 col-xs-4">
                <div class="top-btn-post-place">
                    <a href="<?php echo et_get_page_link('post-place'); ?>" class="btn btn-post-place">
                        <i class="fa fa-map-marker"></i>
                        <?php _e("Submit a place", ET_DOMAIN); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Bar Post Place / End -->