<?php
/**
 * Template Name: Page Static
*/

global $post;
get_header();

if(have_posts()) { the_post();

?>

<!-- Breadcrumb Blog -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb Blog / End -->

<!-- Page Blog -->
<section id="blog-page">
	<div class="container">
    	<div class="row">
        	<!-- Column left -->
        	<div class="col-md-9 col-xs-12">
            	<div class="blog-wrapper static-detail-wrapper">
                    <!-- post title -->
                	<div class="section-detail-wrapper padding-top-bottom-20">
                		<h1 class="media-heading title-blog"><?php the_title(); ?></h1>
                    </div>
                    <!--// post title -->
                    
                    <div class="section-detail-wrapper static-content-wrapper padding-top-bottom-20">
                		<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
                		<h1>HEADING 1 OPEN SANS, Semi Bold, Font size: 24px, CS: 0</h1>
						<h2>HEADING 2 OPEN SANS, Bold, Font size: 18px, CS: 0</h2>
						<h3>HEADING 3 OPEN SANS, Semi Bold, Font size: 18px, CS: 0</h3>
						<h4>HEADING 4 OPEN SANS, Semi Bold, Font size: 16px, CS: 0</h4>
						<h5>HEADING 5 OPEN SANS, Bold, Font size: 14px, CS: 0</h5>
						<h6>HEADING 6 OPEN SANS, Semi Bold, Font size: 14px, CS: 0</h6>
						<br/>
						<br/>
						<label for="">Bullet list:</label>
	                    <ul>
	                    	<li>At vero eos et accusamus et iusto odio dignissimos.</li>
	                    	<li>Sed ut perspiciatis unde omnis iste natus error.</li>
	                    	<li>Nemo enim ipsam voluptatem quia.</li>
	                    </ul>
	                    <br/>
	                    <label for="">Number list:</label>
	                    <ol>
	                    	<li>At vero eos et accusamus et iusto odio dignissimos.</li>
	                    	<li>Sed ut perspiciatis unde omnis iste natus error.</li>
	                    	<li>Nemo enim ipsam voluptatem quia.</li>
	                    </ol>
	                    <br/>
	                    <p><b>FREE BUFFET FOR COUPLEs on wednesday nights</b></p>
	                    <p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
                    </div>
                </div>
            </div>
            <!-- Column left / End --> 
            
            <!-- Column right -->
        	<?php get_sidebar( 'single' ); ?>
            <!-- Column right / End -->
		</div>
    </div>
</section>
<!-- Page Blog / End -->   

<?php
	//the_content();
}
get_footer();

