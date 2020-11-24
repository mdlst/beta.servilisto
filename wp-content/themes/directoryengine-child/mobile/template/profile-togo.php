<div class="tab-pane fade body-tabs" id="user_togo">
    <div class="container" id="list-favorite-wrapper">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-places fullwidth" id="list-favorite">
                    <?php
                    global $wp_query, $wp_rewrite, $ae_post_factory, $user;
                    $post_object = $ae_post_factory->get('place');

                    $number = get_option('posts_per_page', 10);
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    // $offset     = ($paged - 1) * $number;

                    $all_cmts = get_comments(array(
                        'user_id' => $user->ID,
                        'type' => 'favorite',
                        'status' => 'approve',
                        'order' => 'comment_date',
                        'orderby' => 'DESC'
                    ));

                    $query_args = array(
                        'user_id' => $user->ID,
                        'type' => 'favorite',
                        'number' => $number,
                        'status' => 'approve',
                        'paginate' => 'load_more',
                        'text' => 'Load More',
                        'order' => 'comment_date',
                        'orderby' => 'DESC',
                        'showposts' => get_option('posts_per_page')
                    );

                    $reviews = get_comments($query_args);
                    if (!empty($reviews)) {
                        $comment_pages = ceil(count($all_cmts) / $number);
                        $place_obj = $ae_post_factory->get('place');
                        foreach ($reviews as $comment) {
                            $post = get_post($comment->comment_post_ID);
                            $de_favorite = $place_obj->convert($post, 'thumbnail');
                            get_template_part('mobile/template/loop', 'place');
                        }
                    } else {
                        ?>
                        <div class="event-active-wrapper">
                        <div class="col-md-12">
                            <div class="event-wrapper tab-style-event">
                                <h2 class="title-envent"><?php _e("Currently, there are not favorite yet.", ET_DOMAIN); ?></h2>
                            </div>
                        </div>
                        </div><?php
                    }
                    ?>
                </ul>
                <?php
                if (!empty($reviews)) {
                    echo '<div class="paginations-wrapper">';
                    ae_comments_pagination($comment_pages, $paged, array(
                        'user_id' => $user->ID,
                        'type' => 'favorite',
                        'status' => 'approve',
                        'number' => $number,
                        'total' => $comment_pages,
                        'post_type' => 'place',
                        'page' => $paged,
                        'paginate' => 'load_more',
                        'text' => 'Load More',
                    ));
                    echo "</div>";
                }
                wp_reset_query();
                ?>
            </div>
        </div>
    </div>
</div>