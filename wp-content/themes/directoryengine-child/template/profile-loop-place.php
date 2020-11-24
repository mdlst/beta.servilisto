<?php
/**
 * Loop Place Item (Status: Publish, Pending, Draft, Archive, Reject)
 */
global $ae_post_factory, $user_ID;
$place_obj = $ae_post_factory->get('place');
$post = $place_obj->current_post;
$status = $post->post_status;
$status_translate = traduce_estados_anuncio($status);
$time_to_expired = $post->time_to_expired;
$col = "col-lg-3 col-md-4 col-sm-4";


?>
<li id="post-<?php the_ID(); ?>">
    <div <?php post_class($col . ' place-item'); ?>>
        <div class="wrap-place-publishing">
            <?php if ($post->et_payment_package=="001") : ?>
                <span class="tipo_anuncio gratis">Anuncio Gratis</span>
            <?php endif; ?>
            <?php if ($post->et_payment_package=="003") : ?>
                <span class="tipo_anuncio premium">Anuncio Premium</span>
            <?php endif; ?>
            <?php if ($post->et_payment_package=="004") : ?>
                <span class="tipo_anuncio multiple">Anuncio Múltiple</span>
            <?php endif; ?>
            <?php if ($post->et_payment_package=="005") : ?>
                <span class="tipo_anuncio multiple_premium">Anuncio Múltiple Premium</span>
            <?php endif; ?>
            <?php if (ae_user_can('edit_others_posts') || $post->post_author == $user_ID) { ?>
                <ol class="box-edit-place">
                    <li>
                        <a href="#edit_place" class="action edit" data-target="#" data-action="edit"><i
                                    class="fa fa-pencil"></i></a>
                    </li>
                    <li style="display:inline-block">
                        <a href="#" title="<?php _e("Delete Permanently", ET_DOMAIN); ?>" class="action delete"
                           data-action="delete">
                            <i class="fa fa-times" style="color:red"></i>
                        </a>
                    </li>
                </ol>
            <?php } ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="place-publishing-img">
                <img src="<?php echo $post->the_post_thumnail; ?>" alt="<?php the_title(); ?>"/>
            </a>
            <?php if ($post->et_featured == 1) { ?>
                <span class="tag-featured"><i class="fa fa-flag"></i><?php _e('Featured', ET_DOMAIN) ?></span>
            <?php } ?>
            <?php if ($time_to_expired) { ?>
                <span class="tag-remaining oculto"><i class="fa fa-clock-o"></i> <?= $time_to_expired; ?></span>
            <?php } ?>

            <!--LE PONEMOS UN RIBBON DE ESTADO 2-->
            <?php if ($status == "publish") { ?>
                <div class="ribbon-esquinero verde"><span><?= $status_translate ?></span></div>
            <?php } else if ($status == "pending") { ?>
                <div class="ribbon-esquinero amarillo"><span><?= $status_translate ?></span></div>
            <?php } else { ?>
                <div class="ribbon-esquinero naranja"><span><?= $status_translate ?></span></div>
            <?php } ?>


            <h2 class="place-publishing-title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h2>
            <span class="place-publishing-map"><i class="fa fa-map-marker"></i>
                <span itemprop="latitude" id="latitude" content="<?php echo $post->et_location_lat; ?>"></span>
                <span itemprop="longitude" id="longitude" content="<?php echo $post->et_location_lng; ?>"></span>
                <span class="distance"></span>
                <span class="location"><?php echo $post->et_full_location ?></span>
            </span>
            <div class="rate-it" data-score="<?php echo $post->rating_score ?>"
                 data-id="<?php echo $post->ID; ?>"></div>
        </div>
    </div>
</li>
