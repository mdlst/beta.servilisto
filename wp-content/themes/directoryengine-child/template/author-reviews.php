<ul class="list-place-review">
    <?php
        global $wp_query, $wp_rewrite, $ae_post_factory;
        $review_object = $ae_post_factory->get('de_review'); // get review object

        $number     = get_option( 'posts_per_page', 10 );
        $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;  
        $offset     = ($paged - 1) * $number;  

        $all_cmts   = get_comments( array(
                'post_author' => get_query_var( 'author' ),
                'type'        => 'review',
                'meta_key'    => 'et_rate', 
                'status'      => 'approve'
            ) );
        $reviews = get_comments( array(
                'post_author' => get_query_var( 'author' ),
                'type'        => 'review',
                'meta_key'    => 'et_rate', 
                'number'      => $number, 
                'status'      => 'approve',
                'offset'      => $offset
            ) );
        $comment_pages  =   ceil( count($all_cmts)/$number );
        if(!empty($reviews)){
            foreach ($reviews as $comment) {
                $de_review = $review_object->convert($comment, 'review_post_thumbnail');
                get_template_part( 'template/loop', 'review' );
            }
        } else {
            get_template_part( 'template/place', 'notfound' );
        }
    ?>
</ul>
<?php ae_comments_pagination($comment_pages,$paged) ?>