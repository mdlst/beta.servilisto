<?php
global $ae_post_factory;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->current_post;

?>
<li class="post-item place-item">
    <div class="place-wrapper">
        <?php if($post->the_post_thumnail) { ?> 
        <a href="<?php echo the_permalink(); ?>" class="img-place" title="<?php the_title(); ?>">
            <img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
            <?php if(isset($post->ribbon) && $post->ribbon){ ?>
            <div class="cat-<?php echo $post->place_category[0]; ?>">
                <div class="ribbon">
                    <span class="ribbon-content" title="<?php echo $post->ribbon; ?>"><?php echo $post->ribbon; ?></span>
                </div>
            </div>
            <?php } ?>
        </a>
        <?php if($post->et_featured == 1){?>
            <span title="Perfil Destacado" class="tag-featured"><i class="fa fa-flag"></i></span>
        <?php } ?>
        <?php } ?>
        <div class="place-detail-wrapper">
            <h2 class="title-place"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <span class="address-place"><!--<i class="fa fa-map-marker"></i>-->
                <span itemprop="latitude" id="latitude" content="<?php echo $post->et_location_lat;?>"></span> 
                <span itemprop="longitude" id="longitude" content="<?php echo $post->et_location_lng;?>"></span> 
                <span class="distance"></span>
                <?= (isset($post->tax_input["location"][0]->name) && $post->tax_input["location"][0]->name) ? $post->tax_input["location"][0]->name : "Provincia no especificada"; ?>
                <br>
                <?php echo $post->et_full_location; ?>
            </span>
            <div class="rate-it" data-score="<?php echo $post->rating_score; ?>">
            <?php $ads_reserve_price= get_place_calendar_reserveration_price($post->ID);
			  if($ads_reserve_price!=''){
			 ?>
               <div class="price-post v-price-post">   <?php echo $ads_reserve_price; ?> €/H  </div>
              <?php } ?> 
              </div>
            
            
              
        </div>
        <div class="clearfix"></div>
    </div>
</li>