<li>
	<?php if(has_post_thumbnail()) { ?><span class="img-news"><?php echo the_post_thumbnail( 'thumbnail' ); ?></span> <?php	} ?>	
    <div class="content-news">
    	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_excerpt(); ?></p>
    </div>
</li>