<ul class="list-place-review list-posts list-places" data-thumb="big_post_thumbnail">
    <?php
        global $wp_query, $wp_rewrite, $ae_post_factory;
        $post_object = $ae_post_factory->get('place');

        $number     = get_option( 'posts_per_page', 10 );
        $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;  
        $offset     = ($paged - 1) * $number;  

        $all_cmts   = get_comments( array(
                'user_id' => get_query_var( 'author' ),
                'type'        => 'favorite',
                'status'      => 'approve'
            ) );
        $reviews = get_comments( array(
                'user_id' => get_query_var( 'author' ),
                'type'        => 'favorite',
                'number'      => $number, 
                'status'      => 'approve'
            ) );
        $comment_pages  =   ceil( count($all_cmts)/$number );

        if(!empty($reviews)){
            foreach ($reviews as $comment) {
                $post = get_post($comment->comment_post_ID);
                $convert = $post_object->convert($post);
                get_template_part( 'template/loop', 'place-togo' );
            }
        } else {
            get_template_part( 'template/place', 'notfound' );
        }            
    ?>
</ul>
<?php ae_comments_pagination($comment_pages,$paged) ?>