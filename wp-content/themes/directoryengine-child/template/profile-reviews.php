<div class="content-reviews tab-pane fade" id="tab-review">
    <ul class="list-place-publishing">
        <?php
        global $wp_query, $wp_rewrite, $ae_post_factory,$user_ID, $user;
        $review_object = $ae_post_factory->get('de_review'); // get review object

        $number     = get_option( 'posts_per_page', 10 );
        $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $offset     = ($paged - 1) * $number;

        $all_cmts   = get_comments( array(
            'user_id' => $user->ID,
            'type'        => 'review',
            'status'      => 'approve',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' 		=> 'et_rate',
                    'value' 	=> '0',
                    'compare' 	=> '>'
                )
            )
        ) );
        $reviews = get_comments( array(
            'user_id' => $user->ID,
            'type'        => 'review',
            'number'      => $number,
            'status'      => 'approve',
            'offset'      => $offset,
            'post_type' => 'place' ,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' 		=> 'et_rate',
                    'value' 	=> '0',
                    'compare' 	=> '>'
                )
            )
        ) );
        $comment_pages  =   ceil( count($all_cmts)/$number );
        $comment_arr = array();

        if(!empty($reviews)){
            foreach ($reviews as $comment) {
                $de_review = $review_object->convert($comment, 'review_post_thumbnail');
                $de_review->id = $de_review->ID;
                $comment_arr[] = $de_review;
                get_template_part( 'template/profile', 'loop-review' );
            }
        } else { ?>
            <li class="col-md-12">
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-envent no-title-envent "><?php _e( "Currently, there are not review yet.", ET_DOMAIN ); ?></h2>
                        </div>
                    </div>
                </div>
            </li>
        <?php }
        $review_object->reset();
        ?>
    </ul>
    <?php
    if(!empty($reviews)){
        echo '<div class="paginations-wrapper">';
        ae_comments_pagination($comment_pages,$paged, array(
            'user_id' => $user->ID,
            'type'        => 'review',
            'status'      => 'approve',
            'number' => $number,
            'total' => $comment_pages,
            'post_type' => 'place',
            'page' => $paged,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' 		=> 'et_rate',
                    'value' 	=> '0',
                    'compare' 	=> '>'
                )
            ),
            'paginate' => 'page'
        ));
        echo "</div>";
        // render js data for use
        echo '<script type="json/data" class="postdata" > ' . json_encode($comment_arr) . '</script>';
    }
    ?>
</div>