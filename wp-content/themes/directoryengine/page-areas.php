<?php
/**
 * Template Name: Area Page
*/
get_header();
?>
<!-- Breadcrumb List users -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb List users / End -->
<section id="blog-page">
    <div class="container">
        <div class="row">
            <!-- Column left -->
            <div class="col-md-9 col-xs-12">
                <div class="areas-place">
					<div class="filter-wrapper">
						<h2 class="widgettitle">PLACE BY AREAS</h2>
					</div>
					<div class="row list-wrapper">
						<ul class="list-areas">	
							<li class="col-md-6 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href=""><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
							<li class="col-md-6 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href=""><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
							<li class="col-md-6 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href=""><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="paginations-wrapper">
						<div class="paginations">
							<a class="inview load-more-post">Load more</a>
						</div>
					</div>
				</div>
            </div>
            <div class="col-md-3 col-xs-12">
            	<div class="areas-place">
            		<div class="filter-wrapper">
						<h2 class="widgettitle">PLACE BY AREAS</h2>
					</div>
            		<div class="row list-wrapper">
		            	<ul class="list-areas vertical">
		            		<li class="col-md-12">
		            			<a href="" title="">
		            				<span class="area-name">Hiep Thanh City</span>
		            				<span class="area-number">23</span>
		            			</a>
		            		</li>
		            		<li class="col-md-12">
		            			<a href="" title="">
		            				<span class="area-name">Hiep Thanh City</span>
		            				<span class="area-number">23</span>
		            			</a>
		            		</li>
		            		<li class="col-md-12">
		            			<a href="" title="">
		            				<span class="area-name">Hiep Thanh City</span>
		            				<span class="area-number">23</span>
		            			</a>
		            		</li>
		            		<li class="col-md-12">
		            			<a href="" title="">
		            				<span class="area-name">Hiep Thanh City</span>
		            				<span class="area-number">23</span>
		            			</a>
		            		</li>
		            	</ul>
	            	</div>
            	</div>
            </div>
			
			<div class="col-md-12 col-xs-12">
                <div class="areas-place">
					<div class="filter-wrapper">
						<h2 class="widgettitle">PLACE BY AREAS</h2>
					</div>
					<div class="row list-wrapper">
						<ul class="list-areas">	
							<li class="col-md-4 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href="#"><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
							<li class="col-md-4 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href=""><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
							<li class="col-md-4 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href=""><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
							<li class="col-md-4 col-sm-6 col-xs-6">
								<div class="area-wrapper">
									<a href=""><img src="http://lab.enginethemes.com/de/wp-content/uploads/sites/18/2016/01/1039833_947862388615518_6207504858793413836_o.jpg" alt=""></a>
									<div class="area-info">
										<h2>Hiep Thanh City</h2>
										<span class="place-number">20 Places</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="paginations-wrapper">
						<div class="paginations">
							<a class="inview load-more-post">Load more</a>
						</div>
					</div>
				</div>

            </div>

            <!-- Column right -->
            <?php //get_sidebar( 'single' ); ?>
            <!-- Column right / End -->
        </div>
    </div>
</section>       
<?php
get_footer();