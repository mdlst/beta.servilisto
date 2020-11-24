<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
global $post, $ae_post_factory, $current_user, $user_ID;
$place_obj = $ae_post_factory->get('place');
$place = $place_obj->current_post;

$total_count = get_comments(array( 'post_id' => $post->ID, 'type' => 'review', 'count' => true, 'status' => 'approve' ));
$total_comment = get_comments(array('type' => 'review', 'status' => 'approve','post_id' => $post->ID,'count' =>true));
?>
<div class="single-properties-review property-review">
    <div class="tab-info-wrapper">
        <ul class="nav nav-tabs list-info-user-tab" role="tablist">
            <li class="active">
                <a href="#tabs_comment" role="tab" data-toggle="tab"><?php _e('Comment',ET_DOMAIN)?></a>
            </li>
            <li>
                <a href="#tabs_rating" role="tab" data-toggle="tab"><?php _e('Review',ET_DOMAIN)?></a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade body-tab active in" id="tabs_comment">
            <!-- TAB COMMENT -->
            <div class="comments" id="review-list">
                <div class="section-detail-wrapper review-form">
                    <div class="review-place-wrapper review-wrapper">
                        <h3 class="big-title-event title-number-review">
                            <?php
                                $comments = get_comments(array(
                                    'type' => 'review', 
                                    'post_id' => $post->ID,
                                    'meta_query' => array(
                                        'relation' => 'OR',
                                            array(
                                                'key'       => 'et_rate',
                                                'compare' => 'NOT EXISTS',
                                                'value' => 'null',
                                            ),
                                            array(
                                                'key'       => 'et_multi_rate',
                                                'value'     => 'null',
                                                'compare'   => 'NOT EXISTS'
                                            )
                                        )
                                    )
                                );
                                $comment_count = $total_count - $place->reviews_count;
                                if(count($comments) > 0){
                                    if(count($comments) == 1){
                                        printf(__('%s COMMENT',ET_DOMAIN), count($comments));
                                    }else{
                                        printf(__('%s COMMENTS',ET_DOMAIN), count($comments));
                                    }
                                }else{
                                    _e('0 COMMENT');
                                }
                            ?>
                        </h3>
                        <ul class="media-list comment-list">
                            <?php
                            $plugin = "de_multirating/de_multirating.php";
                            if (have_comments()) {
                                wp_list_comments(array('callback' => 'de_list_comment'), $comments );

                                if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                                    <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                                        <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Reviews', ET_DOMAIN)); ?></div>
                                        <div class="nav-next"><?php next_comments_link(__('Newer Reviews &rarr;', ET_DOMAIN)); ?></div>
                                    </nav><!-- #comment-nav-below -->
                                <?php 
                                endif; // Check for comment navigation.
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div style="display:none" >
                    <?php
                        $disable_comment_review = ae_get_option('disable_comment_review');
                        if (!$disable_comment_review) {
                            comment_form ( array(
                                'comment_field'        => '<div class="form-item"><label for="comment">' . __('Comment', ET_DOMAIN) . '</label>
                                                                <div class="input">
                                                                    <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                                                                </div> </div>',
                                'logged_in_as'         => '',
                                'comment_notes_before' => '',
                                'comment_notes_after'  => '',
                                'id_form'              => 'commentform',
                                'id_submit'            => 'submit',
                                'title_reply'          => __("ADD REPLY", ET_DOMAIN),
                                'title_reply_to'       => __('Leave a Reply to %s', ET_DOMAIN),
                                'cancel_reply_link'    => __('CANCEL',ET_DOMAIN),
                                'label_submit'         => __('SUBMIT', ET_DOMAIN)
                            ) );
                        }
                    ?>
                </div>
            </div>
            <!-- TAB COMMENT -->
        </div>
        <div class="tab-pane fade body-tab" id="tabs_rating">
            <!-- TAB REVIEW -->
            <div class="comments" id="review-list">
                <div class="section-detail-wrapper review-form">
                    <div class="review-place-wrapper review-wrapper">
                        <h3 class="big-title-event title-number-review">
                            <?php
                                // query comment have rating
                                $reviews = get_comments(
                                    array('type' => 'review', 
                                        'post_id' => $post->ID,
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key'       => 'et_rate',
                                                'value'     => '0',
                                                'compare'   => '>'
                                            ),
                                            array(
                                                'key'       => 'et_multi_rate',
                                                'value'     => '',
                                                'compare'   => '>'
                                            )
                                        )
                                    )
                                );

                                if(count($reviews) > 0){
                                    if(count($reviews) == 1){
                                        printf(__('%s REVIEW',ET_DOMAIN), count($reviews));
                                    }else{
                                        printf(__('%s REVIEWS',ET_DOMAIN), count($reviews));
                                    }
                                }else{
                                    _e('0 REVIEW');
                                }
                            ?>
                        </h3>
                        <ul class="media-list comment-list">
                            <?php
                                $plugin = "de_multirating/de_multirating.php";
                                if (have_comments()) {
                                    if (!isset($place->multi_overview_score) ||(int)$place->multi_overview_score <= 0 && !is_plugin_active($plugin)) {
                                        wp_list_comments(array('callback' => 'de_list_review'), $reviews );
                                    }else{
                                        wp_list_comments(array('callback' => 'de_multi_list_review'), $reviews );
                                    }

                                    if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                                        <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                                            <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Reviews', ET_DOMAIN)); ?></div>
                                            <div class="nav-next"><?php next_comments_link(__('Newer Reviews &rarr;', ET_DOMAIN)); ?></div>
                                        </nav><!-- #comment-nav-below -->
                                    <?php 
                                    endif; // Check for comment navigation.
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                <div style="display:none" >
                    <?php
                        $disable_comment_review = ae_get_option('disable_comment_review');
                        if (!$disable_comment_review) {
                            comment_form ( array(
                                'comment_field'        => '<div class="form-item"><label for="comment">' . __('Comment', ET_DOMAIN) . '</label>
                                                                <div class="input">
                                                                    <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                                                                </div> </div>',
                                'logged_in_as'         => '',
                                'comment_notes_before' => '',
                                'comment_notes_after'  => '',
                                'id_form'              => 'commentform',
                                'id_submit'            => 'submit',
                                'title_reply'          => __("ADD REPLY", ET_DOMAIN),
                                'title_reply_to'       => __('Leave a Reply to %s', ET_DOMAIN),
                                'cancel_reply_link'    => __('CANCEL',ET_DOMAIN),
                                'label_submit'         => __('SUBMIT', ET_DOMAIN)
                            ) );
                        }
                    ?>
                </div>
            </div>
            <!-- TAB REVIEW -->
        </div>
    </div>
    <?php if ($user_ID) { ?>
        <!-- review form comment without comment parent -->
        <div id="review" class="comment-respond multi-rating-comment-respond">
            <h3 id="reply-title" class="comment-reply-title"><?php  _e('Write Reviews' , ET_DOMAIN);  ?></h3>
            <form action="<?php echo site_url('wp-comments-post.php') ?>" method="post" id="" class="comment-form multi-rating-comment-form">
                <div class="form-item">
                    <label for="comment"><?php _e( 'REVIEW' , ET_DOMAIN ); ?></label>
                    <div class="input">
                        <?php de_multirating_render_review_criterias($place, 'list-rating-criteria', true); ?>
                        <textarea placeholder="<?php _e("description", ET_DOMAIN); ?>" id="de_multirating_comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                    </div>
                    <p class="form-submit">
                        <button class="rating_submit"><?php _e('SUBMIT', ET_DOMAIN); ?></button>
                        <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID ?>" id="comment_post_ID">
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                    </p>
                </div>
            </form>

        </div>
    <?php }else {
        echo '<h3 id="reply-title" class="comment-reply-title ae-comment-reply-title ">' . __( 'YOU MUST <a class="authenticate" href="#login_register">LOGIN</a> TO SUBMIT A REVIEW' , ET_DOMAIN ) . '</h3>';
    }
    ?>
</div>


<?php
/**
 * desktop version review list call back
 * @param $comment
 * @param $args
 * @param $depth
 * @return mixed
 */
function de_list_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    global $ae_post_factory, $user_ID;
    $post = get_post($comment->comment_post_ID);
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->convert($post);
    $disable_comment_review = ae_get_option('disable_comment_review');
    if ($disable_comment_review) {
        $depth = 1;
        $args['max_depth'] = 1;
    }
    if ($comment->comment_approved == 1) {
?>
    <li class="media" id="li-comment-<?php comment_ID();?>">
        <div id="comment-<?php comment_ID(); ?>" class="multi-rating-level1">
            <a class="pull-left avatar-comment" href="#"><?php echo get_avatar($comment->comment_author_email, 60);?></a>
            <div class="media-body">
                <div class="media-body-left">
                    <h4 class="media-heading"><?php comment_author();?></h4>
                    <div class="list-rating-criteria-wrapper">
                        <div class="comment-text"><?php comment_text(); ?></div>
                    </div>
                    <div class="time-reply">
                        <span class="time-review"><i class="fa fa-clock-o"></i><?php echo ae_the_time( strtotime($comment->comment_date)); ?></time></span>
                        <?php comment_reply_link(array_merge($args,array('reply_text' => __( 'Reply', ET_DOMAIN ),'depth' => $depth,'max_depth' => $args['max_depth'] ) ));?>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php
    }elseif(is_user_logged_in()){
        // If this review not yet approve, display status pending
        if(ae_user_can( 'administrator' ) || $comment->user_id == $user_ID || $post->post_author == $user_ID ){
?>
    <li class="media" id="li-comment-<?php comment_ID();?>">
        <div id="comment-<?php comment_ID(); ?>" class="multi-rating-level1">
            <a class="pull-left avatar-comment" href="#"><?php echo get_avatar($comment->comment_author_email, 60);?></a>
            <div class="media-body">
                <div class="media-body-left">
                    <h4 class="media-heading">
                        <?php comment_author();?>
                        <span style="float:right"><?php _e('Moderate', ET_DOMAIN);?></span>
                    </h4>
                    <div class="list-rating-criteria-wrapper">
                        <div class="comment-text"><?php comment_text(); ?></div>
                    </div>
                    <div class="time-reply">
                        <span class="time-review"><i class="fa fa-clock-o"></i><?php echo ae_the_time( strtotime($comment->comment_date)); ?></time></span>
                    </div>
                </div>
            </div>
        </div>
    </li>
<?php
        }
    }
}
?>

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
    $GLOBALS['comment'] = $comment;
    global $ae_post_factory, $user_ID;
    $post = get_post($comment->comment_post_ID);
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->convert($post);
    $disable_comment_review = ae_get_option('disable_comment_review');
    if ($disable_comment_review) {
        $depth = 1;
        $args['max_depth'] = 1;
    }
    if ($comment->comment_approved == 1) {
?>
    <li class="media" id="li-comment-<?php comment_ID();?>">
        <div id="comment-<?php comment_ID(); ?>" class="multi-rating-level1">
            <a class="pull-left avatar-comment" href="#"><?php echo get_avatar($comment->comment_author_email, 60);?></a>
            <div class="media-body">
                <div class="media-body-left">
                    <h4 class="media-heading"><?php comment_author();?></h4>
                    <div class="list-rating-criteria-wrapper">
                        <?php
                            $rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
                            $flag = de_multirating_is_review($rates);
                        
                            if (!$comment->comment_parent && $flag) {
                                de_multirating_render_review_criterias($place, 'list-rating-criteria', null, null, $rates, false);
                            } 
                        ?>
                        <div class="comment-text"><?php comment_text(); ?></div>
                    </div>
                    <div class="time-reply">
                        <span class="time-review"><i class="fa fa-clock-o"></i><?php echo ae_the_time( strtotime($comment->comment_date)); ?></time></span>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php
    }elseif(is_user_logged_in()){
        // If this review not yet approve, display status pending
        if(ae_user_can( 'administrator' ) || $comment->user_id == $user_ID || $post->post_author == $user_ID ){
?>
    <li class="media" id="li-comment-<?php comment_ID();?>">
        <div id="comment-<?php comment_ID(); ?>" class="multi-rating-level1">
            <a class="pull-left avatar-comment" href="#"><?php echo get_avatar($comment->comment_author_email, 60);?></a>
            <div class="media-body">
                <div class="media-body-left">
                    <h4 class="media-heading">
                        <?php comment_author();?>
                        <span style="float:right"><?php _e('Moderate', ET_DOMAIN);?></span>
                    </h4>
                    <div class="list-rating-criteria-wrapper">
                        <?php
                            $rates = get_comment_meta($comment->comment_ID, 'et_multi_rate', true);
                            $flag = de_multirating_is_review($rates);
                        
                            if (!$comment->comment_parent && $flag) {
                                de_multirating_render_review_criterias($place, 'list-rating-criteria', null, null, $rates, false);
                            } 
                        ?>
                        <div class="comment-text"><?php comment_text(); ?></div>
                    </div>
                    <div class="time-reply">
                        <span class="time-review"><i class="fa fa-clock-o"></i><?php echo ae_the_time( strtotime($comment->comment_date)); ?></time></span>
                    </div>
                </div>
            </div>
        </div>
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
function de_list_review( $comment, $args, $depth ){
    global $user_ID, $post;
    $GLOBALS['comment'] = $comment;

    $disable_comment_review = ae_get_option('disable_comment_review');
    if($disable_comment_review) {
        $depth = 1;
        $args['max_depth'] = 1;
    }
    if($comment->comment_approved == 1 ){
    ?>
    <li class="media" id="li-comment-<?php comment_ID();?>">
        <div id="comment-<?php comment_ID(); ?>">
            <a class="pull-left avatar-comment" href="#">
                <?php echo get_avatar( $comment->comment_author_email, 60 );?>
            </a>
            <div class="media-body">
                <h4 class="media-heading">
                    <?php
                    comment_author();
                    $rate = get_comment_meta($comment->comment_ID, 'et_rate' , true);
                    if (!$comment->comment_parent && $rate) {
                        ?>
                        <div class="rate-it" data-score='<?php echo $rate;  ?>'></div>
                    <?php } ?>
                </h4>
                <div class="comment-text"><?php comment_text(); ?></div>
                <span class="time-review"><i class="fa fa-clock-o"></i><?php echo ae_the_time( strtotime($comment->comment_date)); ?></time></span>
                <?php
                comment_reply_link(array_merge($args,   array(
                    'reply_text' => __( 'Reply', ET_DOMAIN ),
                    'depth' => $depth,
                    'max_depth' => $args['max_depth'] ) ));
                ?>
            </div>
        </div>
    </li>
<?php
    }elseif(is_user_logged_in()){
        // If this review not yet approve, display status pending
        if(ae_user_can( 'administrator' ) || $comment->user_id == $user_ID || $post->post_author == $user_ID ){
?>
    <li class="media" id="li-comment-<?php comment_ID();?>">
        <div id="comment-<?php comment_ID(); ?>">
            <a class="pull-left avatar-comment" href="#">
                <?php echo get_avatar( $comment->comment_author_email, 60 );?>
            </a>
            <div class="media-body">
                <h4 class="media-heading">
                    <?php
                    comment_author();
                    $rate = get_comment_meta($comment->comment_ID, 'et_rate' , true);
                    if (!$comment->comment_parent && $rate) {
                        ?>
                        <div class="rate-it" data-score='<?php echo $rate;  ?>'></div>
                    <?php } ?>
                    <span style="float:right"><?php _e('Moderate', ET_DOMAIN);?></span>
                </h4>
                <div class="comment-text"><?php comment_text(); ?></div>
                <span class="time-review"><i class="fa fa-clock-o"></i><?php echo ae_the_time( strtotime($comment->comment_date)); ?></time></span>
            </div>
        </div>
    </li>
<?php            
        }
    }
}