<div class="tab-pane fade body-tabs" id="user_review">
    <div class="container" id="reviews-list-wrapper">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-places" id="list-reviews">
                    <?php
                    global $ae_post_factory, $user;
                    $review_object = $ae_post_factory->get('de_review');
                    $number = get_option('posts_per_page', 10);
                    $all_cmts = get_comments(array(
                        'user_id' => get_query_var('author'),
                        'type' => 'review',
                        'meta_key' => 'et_rate',
                        'status' => 'approve',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'et_rate',
                                'value' => '0',
                                'compare' => '>'
                            )
                        )
                    ));
                    $query_args = array(
                        'user_id' => $user->ID,
                        'type' => 'review',
                        'meta_key' => 'et_rate',
                        'number' => $number,
                        'status' => 'approve',
                        'paginate' => 'load_more',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'et_rate',
                                'value' => '0',
                                'compare' => '>'
                            )
                        )
                    );
                    $reviews = get_comments($query_args);
                    $comment_pages = ceil(count($all_cmts) / $number);
                    if (!empty($reviews)) {

                        foreach ($reviews as $comment) {
                            $de_review = $review_object->convert($comment);
                            get_template_part('mobile/template/loop', 'place-review');
                        }
                    } else {
                        ?>
                        <div class="event-active-wrapper">
                            <div class="col-md-12">
                                <div class="event-wrapper tab-style-event">
                                    <h2 class="title-envent"><?php _e("Currently, there are not review yet.", ET_DOMAIN); ?></h2>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
            </div>
            <?php
            if (!empty($reviews)) {
                ae_comments_pagination($comment_pages, 1, array(
                        'user_id' => $user->ID,
                        'type' => 'review',
                        'post_type' => 'place',
                        'total' => $comment_pages,
                        'number' => $number,
                        'status' => 'approve',
                        'paginate' => 'load_more',
                        'text' => 'Load More',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'et_rate',
                                'value' => '0',
                                'compare' => '>'
                            )
                        )
                    )
                );
            }
            wp_reset_query();
            ?>
        </div>
    </div>
</div>
