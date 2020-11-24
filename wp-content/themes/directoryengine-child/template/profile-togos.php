<?php 
global $wp_query, $wp_rewrite, $ae_post_factory,$user_ID, $user;

$inact = "";
?>
<div class="content-togo tab-pane fade<?php echo $inact; ?>" id="tab-togo">
    <ul class="list-place-publishing">
    <?php
        $post_object = $ae_post_factory->get('place');

        $number     = get_option( 'posts_per_page', 10 );
        $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;  
        $offset     = ($paged - 1) * $number;  

        $all_cmts   = get_comments( array(
                'user_id' => $user->ID,
                'type'        => 'favorite',
                'status'      => 'approve',
                'order'       => 'comment_date',
                'orderby'     => 'DESC'
            ) );
        $args = array(
                'user_id' => $user->ID,
                'type'        => 'favorite',
                'number'      => $number,
                'status'      => 'approve',
                'order'       => 'comment_date',
                'orderby'     => 'DESC'
            );
        $reviews = get_comments( $args );
        $comment_pages  =   ceil( count($all_cmts)/$number );

        if(!empty($reviews)){
            $post_arr   =   array();
            foreach ($reviews as $comment) {
                $post = get_post($comment->comment_post_ID);
                $convert = $post_object->convert($post);
                $post_arr[] =   $convert;
                get_template_part( 'template/profile', 'loop-togo' );
            }
            echo '<script type="json/data" class="postdata" > ' . json_encode($post_arr) . '</script>';   
        } else { ?>
            <li class="col-md-12">
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-envent no-title-envent "><?php _e( "Currently, there are not favorite yet.", ET_DOMAIN ); ?></h2>
                        </div>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
    <?php 
        if(!empty($reviews)){
            echo '<div class="paginations-wrapper">';
                ae_comments_pagination($comment_pages,$paged,array(
                        'user_id' => $user->ID,
                        'type'        => 'favorite',
                        'status'      => 'approve', 
                        'number' => $number, 
                        'total' => $comment_pages, 
                        'post_type' => 'place',
                        'page' => $paged,
                        'paginate' => 'page'
                        )
                    ) ;
            echo '</div>';
        }
    ?>
</div>
