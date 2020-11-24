
<?php
et_get_mobile_header();

$all_cmts   = get_comments( array(	'type'        => 'review',
		'meta_key'    => 'et_rate',
		'status'      => 'approve'
) );
$total_comment  = count($all_cmts);
?>
<!-- Top bar -->
<section id="top-bar" class="section-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-centered">
				<h1 class="title-page sss7"><?php printf(__("%d Reviews", ET_DOMAIN), $total_comment ); ?></h1>
			</div>
		</div>
	</div>
</section>
<!-- Top bar / End -->

<!-- Top bar -->
<section class="section-wrapper">
	<div class="container" id="reviews-list-wrapper">
		<div class="row">
			<ul class="list-place-review" id="list-reviews">
				<?php

				global $ae_post_factory;
				$review_object = $ae_post_factory->get('de_review');
				$number = get_option( 'posts_per_page', 10 );

				$query_args = array(
						'type'        => 'review',
						'meta_key'    => 'et_rate',
						'number'      => $number,
						'status'      => 'approve',
						'paginate' => 'load_more'
				);
				$reviews = get_comments( $query_args );
				$comment_pages  =   ceil( $total_comment/$number );
				$query_args['total'] = $comment_pages;
				$query_args['text'] = 'Load more';
				foreach ($reviews as $comment) {
					$de_review = $review_object->convert( $comment, 'review_post_thumbnail');
					get_template_part( 'mobile/template/loop', 'review' );
				}

				?>
			</ul>
			<div class="paginations-wrapper">
				<?php
				ae_comments_pagination( $comment_pages, 1, $query_args );
				?>
			</div>
		</div>
	</div>
</section>
<?php  et_get_mobile_footer();
?>