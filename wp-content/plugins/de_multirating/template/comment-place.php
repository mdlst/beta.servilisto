<?php
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
global $post, $ae_post_factory, $current_user, $user_ID;
$place_obj = $ae_post_factory->get('place');
$place = $place_obj->current_post;

if (!session_id())
    session_start();

if (isset($_SESSION['points_added_successfully']) && $_SESSION['points_added_successfully'] == 'yes') {
    global $wpdb;
    $querystr = "SELECT * FROM " . $wpdb->prefix . "tbl_point_settings";
    $review_points = $wpdb->get_results($querystr, OBJECT);
    $points = $review_points[0]->review_points;
    $price = $points * $review_points[0]->points_worth;
    $message = '<h2>Enhorabuena!</h2><p>Has ganado ' . $points . ' Puntos de ' . $price . '€. Recuerda que puedes usar este crédito para reservar cualquier servicio.</p>';
    pointsPopup($message);
    $_SESSION['points_added_successfully'] = '';
}

$user_ID = get_current_user_id();
/*$total_count = get_comments(array('post_id' => $post->ID, 'type' => 'review', 'count' => true, 'status' => 'approve'));
$comments = get_comments(array('type' => 'review', 'post_id' => $post->ID));
$total_comment = get_comments(array('type' => 'review', 'status' => 'approve', 'post_id' => $post->ID, 'count' => true));// query comment have rating
$comment_count = $total_count - $place->reviews_count;*/

?>

    <span style="width: 100%;height: 1px;position: relative;top: -70px;display:block;" id="review-list-ancla"></span>
    <div class="comments" id="review-list">

            <div class="section-detail-wrapper review-form">
                <div class="review-place-wrapper review-wrapper">
                    <p class="title-comments title-number-review">

                        <?php


                        if ($place->reviews_count > 0) {
                            if ($place->reviews_count > 1) {
                                printf(__('%d REVIEWS', ET_DOMAIN), $place->reviews_count);
                            } else {
                                printf(__('%d REVIEW', ET_DOMAIN), $place->reviews_count);
                            }
                        }
                        else{
                            echo "Actualmente no hay opiniones";
                        }

                        ?>
                    </p>
                    <ul class="media-list comment-list">
                        <?php
                        $plugin = "de_multirating/de_multirating.php";
                        if (!isset($place->multi_overview_score) || (int)$place->multi_overview_score <= 0 && !is_plugin_active($plugin)) {
                            wp_list_comments(array('callback' => 'de_list_review'), $comments);
                        } else {
                            wp_list_comments(array('callback' => 'de_multi_list_review'), $comments);
                        }

                        if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                            <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                                <div
                                    class="nav-previous"><?php previous_comments_link(__('&larr; Older Reviews', ET_DOMAIN)); ?></div>
                                <div
                                    class="nav-next"><?php next_comments_link(__('Newer Reviews &rarr;', ET_DOMAIN)); ?></div>
                            </nav><!-- #comment-nav-below -->
                            <?php
                        endif; // Check for comment navigation.
                        ?>
                    </ul>
                </div>
            </div>

        <?php

        // si el usuario:
        //      está registrado
        //      ha reservado
        //      y aún no ha opinado

        // PODRÁ PONER UNA OPINIÓN
        $veces_reservas = check_if_booking_reserved_for_user($post->ID);
        $numero_opiniones_puestas = count_user_reviews_reservation($post->ID);
        //if ($user_ID) {
        if ($user_ID && $veces_reservas > 0 && $numero_opiniones_puestas < $veces_reservas) { ?>
            <!-- review form comment without comment parent -->
            <div id="review" class="comment-respond multi-rating-comment-respond 2">
                <h3 id="reply-title" class="comment-reply-title"><?php _e('ADD REVIEW', ET_DOMAIN); ?></h3>

                <form action="<?php echo site_url('wp-comments-post.php') ?>" method="POST" id=""
                      class="comment-form multi-rating-comment-form">
                    <div class="form-item">
                        <label for="comment"><?php _e('REVIEW', ET_DOMAIN); ?></label>

                        <div class="input">
                            <?php de_multirating_render_review_criterias($place, 'list-rating-criteria', true); ?>

                            <textarea placeholder="<?php _e("description", ET_DOMAIN); ?>" id="de_multirating_comment"
                                      name="comment" cols="45" rows="8" aria-required="true"></textarea>
                        </div>
                        <p class="form-submit">
                            <button class="rating_submit"><?php _e('SUBMIT REVIEW', ET_DOMAIN); ?></button>
                            <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID ?>"
                                   id="comment_post_ID">
                            <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                        </p>
                    </div>
                </form>

            </div>
        <?php } ?>
    </div>
<?php


/**
 * desktop version review list call back
 * @param $comment
 * @param $args
 * @param $depth
 * @return mixed
 */
function de_multi_list_review($comment, $args, $depth)
{
    global $user_ID;
    $GLOBALS['comment'] = $comment;
    global $ae_post_factory;
    $post = get_post($comment->comment_post_ID);
    $place_obj = $ae_post_factory->get('place');

    $place = $place_obj->convert($post);
    $disable_comment_review = ae_get_option('disable_comment_review');
    if ($disable_comment_review) {
        $depth = 1;
        $args['max_depth'] = 1;
    }
    if ($comment->comment_approved == 1) {
        $rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
        $flag = de_multirating_is_review($rates);

        ?>
        <li class="media <?php if (!(!$comment->comment_parent && $flag)) echo 'fullwidth'; ?>"
            id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="multi-rating-level1">
                <span class="pull-left avatar-comment" >
                    <?php echo get_avatar($comment->comment_author_email, 60); ?>
                </span>
                <?php
                //$rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
                //$flag = de_multirating_is_review($rates);
                ?>
                <div class="media-body">
                    <div class="media-body-left">
                        <div
                            class="list-rating-criteria-wrapper <?php if (!(!$comment->comment_parent && $flag)) echo 'fullwidth'; ?> ">
                            <h4 class="media-heading">
                                <?php
                                comment_author();
                                //$rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
                                ?>
                            </h4>

                            <div class="comment-text">
                                <?php comment_text();
                                if (et_load_mobile() && !$comment->comment_parent && $flag) {

                                    //de_multirating_render_review_criterias( $place, 'list-rating-criteria' ,null, null, $rates);
                                    de_multirating_render_review_criterias($place, 'list-rating-criteria', null, null, $rates, false);
                                } ?>
                            </div>
                            <div class="time-reply">
                                <span class="time-review"><i
                                        class="fa fa-clock-o"></i><?php echo ae_the_time(strtotime($comment->comment_date)); ?></time></span>
                                <?php
                                comment_reply_link(array_merge($args, array(
                                    'reply_text' => __('&nbsp;&nbsp;|&nbsp;&nbsp; Reply', ET_DOMAIN),
                                    'depth' => $depth,
                                    'max_depth' => $args['max_depth'])));
                                ?>
                            </div>
                        </div>
                        <?php
                        //$flag = de_multirating_is_review($rates);
                        ?>
                        <?php if (!et_load_mobile() && !$comment->comment_parent && $flag) {
                            //de_multirating_render_review_criterias( $place, 'list-rating-criteria' ,null, null, $rates);
                            de_multirating_render_review_criterias($place, 'list-rating-criteria', null, null, $rates, false);
                        } ?>
                    </div>
                </div>
            </div>
            <?php if (!$comment->comment_parent && $flag) { ?>
                <div class="criteria-line"></div>
            <?php } ?>
        </li>
        <?php
    } elseif (is_user_logged_in()) {
        // If this review not yet approve, display status pending
        if (ae_user_can('administrator') || $comment->user_id == $user_ID || $post->post_author == $user_ID) {
            ?>
            <?php
            $rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
            $flag = de_multirating_is_review($rates);
            ?>
            <li class="media <?php if (!(!$comment->comment_parent && $flag)) echo 'fullwidth'; ?>"
                id="li-comment-<?php comment_ID(); ?>">
                <div id="comment-<?php comment_ID(); ?>" class="multi-rating-level1">
                    <a class="pull-left avatar-comment" href="#">
                        <?php echo get_avatar($comment->comment_author_email, 60); ?>
                    </a>

                    <div class="media-body">
                        <div class="media-body-left">
                            <div
                                class="list-rating-criteria-wrapper <?php if (!(!$comment->comment_parent && $flag)) echo 'fullwidth'; ?>">
                                <h4 class="media-heading">
                                    <?php
                                    comment_author();
                                    //$rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
                                    ?>
                                    <span style="float:right"><?php _e('Moderate', ET_DOMAIN); ?></span>
                                </h4>

                                <div class="comment-text">
                                    <?php comment_text();
                                    if (et_load_mobile() && !$comment->comment_parent && $flag) {
                                        //de_multirating_render_review_criterias( $place, 'list-rating-criteria' ,null, null, $rates);
                                        de_multirating_render_review_criterias($place, 'list-rating-criteria', null, null, $rates, false);
                                    } ?>
                                </div>
                                <div class="time-reply">
                                    <span class="time-review"><i
                                            class="fa fa-clock-o"></i><?php echo ae_the_time(strtotime($comment->comment_date)); ?></time></span>
                                </div>
                            </div>
                            <?php
                            //$flag = de_multirating_is_review($rates);
                            ?>
                            <?php if (!et_load_mobile() && !$comment->comment_parent && $flag) {
                                //de_multirating_render_review_criterias( $place, 'list-rating-criteria' ,null, null, $rates);
                                de_multirating_render_review_criterias($place, 'list-rating-criteria', null, null, $rates, false);
                            } ?>
                        </div>
                    </div>
                </div>
                <?php if (!$comment->comment_parent && $flag) { ?>
                    <div class="criteria-line"></div>
                <?php } ?>
            </li>
            <?php
        }
    }
}

/**
 * desktop version review list call back
 * @param $comment
 * @param $args
 * @param $depth
 */
function de_list_review($comment, $args, $depth)
{
    global $user_ID;
    $GLOBALS['comment'] = $comment;

    $disable_comment_review = ae_get_option('disable_comment_review');
    if ($disable_comment_review) {
        $depth = 1;
        $args['max_depth'] = 1;
    }
    if ($comment->comment_approved == 1) {
        ?>
        <li class="media" id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>">
                <a class="pull-left avatar-comment" href="#">
                    <?php echo get_avatar($comment->comment_author_email, 60); ?>
                </a>

                <div class="media-body">
                    <h4 class="media-heading">
                        <?php
                        comment_author();
                        $rate = get_comment_meta($comment->comment_ID, 'et_rate', true);
                        if (!$comment->comment_parent && $rate) {
                            ?>
                            <div class="rate-it" data-score='<?php echo $rate; ?>'></div>
                        <?php } ?>
                    </h4>
                    <div class="comment-text"><?php comment_text(); ?></div>
                    <span class="time-review"><i
                            class="fa fa-clock-o"></i><?php echo ae_the_time(strtotime($comment->comment_date)); ?></time></span>
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'reply_text' => __('&nbsp;&nbsp;|&nbsp;&nbsp; Reply', ET_DOMAIN),
                        'depth' => $depth,
                        'max_depth' => $args['max_depth'])));
                    ?>
                </div>
            </div>
        </li>
        <?php
    } elseif (is_user_logged_in()) {
        // If this review not yet approve, display status pending
        if (ae_user_can('administrator') || $comment->user_id == $user_ID || $post->post_author == $user_ID) {
            ?>
            <li class="media" id="li-comment-<?php comment_ID(); ?>">
                <div id="comment-<?php comment_ID(); ?>">
                    <a class="pull-left avatar-comment" href="#">
                        <?php echo get_avatar($comment->comment_author_email, 60); ?>
                    </a>

                    <div class="media-body">
                        <h4 class="media-heading">
                            <?php
                            comment_author();
                            $rate = get_comment_meta($comment->comment_ID, 'et_rate', true);
                            if (!$comment->comment_parent && $rate) {
                                ?>
                                <div class="rate-it" data-score='<?php echo $rate; ?>'></div>
                            <?php } ?>
                            <span style="float:right"><?php _e('Moderate', ET_DOMAIN); ?></span>
                        </h4>
                        <div class="comment-text"><?php comment_text(); ?></div>
                        <span class="time-review"><i
                                class="fa fa-clock-o"></i><?php echo ae_the_time(strtotime($comment->comment_date)); ?></time></span>
                    </div>
                </div>
            </li>
            <?php
        }
    }
}