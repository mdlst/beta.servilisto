<?php
/**
* The Header template for our theme
*
* Displays all of the <head> section and everything up till <div id="main">
*
* @since DirectoryEngine 1.0
*/
?><!DOCTYPE html>
<!--[if IE 7]>
	<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
	<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1 ,user-scalable=no">
	<?php if(is_singular('place')){
		global $post;
		if(isset($post)){
			$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
			?>
			<meta property="og:title" content="<?php the_title();?>" />
			<meta property="og:image" content="<?php echo $url; ?>" >
			<meta property="og:url" content="<?php the_permalink();?>">
			<meta property="og:type" content="place"/>
	<?php }

	} ?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!-- <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,300,400,600,700&subset=latin,vietnamese,cyrillic,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'> -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,300,400,600,700' rel='stylesheet' type='text/css'>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.min.js"></script>
	<?php
		ae_favicon();
		wp_head();
		de_category_style();
		/**
		* render less custom css
		*/
		if(function_exists('et_render_less_style')) {
			et_render_less_style();
		}
		$re = false;
    	$re = de_check_register();
		if(!$re && !is_user_logged_in()){
?>
			<style type="text/css">
				.btn-post-place{
					display: none!important;
				}
			</style>
<?php   } ?>
</head>
<body <?php body_class(); ?>  >
	<?php if( ae_get_option('use_pre_loading', 0) ) {ae_pre_loading();} ?>
    	<div id="sticky-holder"></div>
	<!-- Header -->
	<header id="header-wrapper" class="sticky-scroll">
		<?php 
			/* NOTIFICATION */
			if(is_user_logged_in() && ae_user_can('edit_others_posts') ) { 
				if(isset($_COOKIE['view-notification']) && $_COOKIE['view-notification'] == 1) {
					$pending_place = new WP_Query(array('post_type' => 'place', 'post_status' => 'pending', 'showposts'=> -1));
					if($pending_place->found_posts > 0){
						get_template_part('template/notification');
					}
				} 
			}
			/* NOTIFICATION */
		?>
		<?php
		global $user_ID;
		$confirm = get_user_meta( $user_ID, 'register_status', true ); 
		if( $confirm ){ ?>
		<section class="activation-notification">
			<div class="activation-notification-content">
				<p>
					<span>
						<i class="fa fa-exclamation-circle"></i>
						<?php _e('Your account has not been confirmed yet! Please check your mailbox to activate your account.', ET_DOMAIN); ?>
					</span>
					<a href="#" class="activation-notification-close">
						<i class="fa fa-times pull-right"></i>
					</a>
				</p>
				<a href="#" class="resend-activation-code"><?php _e('Resend an activation code', ET_DOMAIN); ?></a>
			</div>
		</section>
		<?php } ?>
		<section id="menu-top">
			<ul class="top-menu gn-menu-main" id="gn-menu">
				
                <li class="gn-trigger">
                	<?php if(has_nav_menu('et_header')) { ?>
                    <a href="#" class="menu-btn gn-icon-menu"><i class="fa fa-bars"></i></a>
                    
                    <nav class="gn-menu-wrapper" id="gmenu-main">
                        <div class="gn-scroller">
                            <?php
                            	wp_nav_menu( array(	
	                            				'theme_location' => 'et_header' , 
	                            				'menu_class' => 'gn-menu' , 
	                            				'walker' => new DE_Menu_Walker() 
                            				));
                            ?>
                            <!-- Nexus Menu / End -->
                        </div>
                    </nav><!-- /gn-scroller -->
                    <?php } ?>
                </li>
				<li>
					<a href="<?php echo home_url(); ?>" class="logo">
						<?php ae_logo(); ?>
					</a>
				</li>
				<?php de_support_info(); ?>
			</ul>
			<ul class="top-menu-right">
				<?php de_support_min_info(); ?>
				<li class="top-active top-search" data-name="search"><a href="javascript:void(0)" class="search-btn"><i class="fa fa-search"></i></a></li>
			<?php if($re){
					if(is_user_logged_in()) {
						global $current_user;
						if(ae_user_can('edit_others_posts')) {
							$pending_post = new WP_Query(array('post_type' => 'place', 'post_status' => 'pending', 'showposts'=> -1));
						}
				?>
				<li class="top-user dropdown visible-sm visible-md visible-lg">
					<a class="dropdown-toggle display-name" data-toggle="dropdown" href="#">
						<?php 
							echo $current_user->display_name;
							if(ae_user_can('edit_others_posts') && $pending_post->found_posts > 0) {
								echo '<span style="color:#EE671B;"> ('.$pending_post->found_posts.')</span>';
							}
						 ?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo et_get_page_link('profile'); ?>">
								<i class="fa fa-user"></i><?php _e("Profile", ET_DOMAIN) ?>
							</a>
						</li>
						<?php 
						if(ae_user_can('edit_others_posts')) {
							if($pending_post->have_posts()) {								
						?>
						<li>
							<a href="<?php echo get_post_type_archive_link( 'place' ); ?>">
								<i class="fa fa-flash"></i><?php printf( __("%s Pending", ET_DOMAIN), $pending_post->found_posts); ?>
							</a>
						</li>
						<?php 
							}
						} ?>
						<li>
							<a href="<?php echo wp_logout_url( home_url() ); ?>">
								<i class="fa fa-power-off"></i><?php _e("Log Out", ET_DOMAIN) ?>
							</a>
						</li>
					</ul>
				</li>
				<li class="top-avatar">
					<a href="<?php echo get_author_posts_url( $current_user->ID ); ?>">
						<?php echo get_avatar( $current_user->ID, 60 ); ?>
					</a>
				</li>
				<?php } else { ?>
				<li class="non-login">
					<a id="authenticate" class="<?php if(!is_page_template('page-login.php')){ echo 'authenticate'; } ?> " href="#" >
						<?php _e("SIGN IN", ET_DOMAIN); ?>
					</a>
				</li>
			<?php 
			}
				} ?>
			</ul>
			<div class="top-menu-center">
				<?php de_header_top_menu(); ?>
			</div>
			
			<script type="text/template" id="header_login_template">
				<li class="top-user dropdown">
					<a class="dropdown-toggle display-name" data-toggle="dropdown" href="#">
						{{= display_name }} <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo et_get_page_link('profile'); ?>">
								<i class="fa fa-user"></i><?php _e("Profile", ET_DOMAIN) ?>
							</a>
						</li>
						<li>
							<a href="<?php echo wp_logout_url( home_url() ); ?>">
								<i class="fa fa-power-off"></i><?php _e("Log Out", ET_DOMAIN) ?>
							</a>
						</li>
					</ul>
				</li>
				<li class="top-avatar">
					<a href="{{= author_url }}">
					<# if(et_avatar_url !== '') {#>
						<img alt="" src="{{= et_avatar_url }}"  class="avatar avatar-60 photo avatar-default" height="60" width="60">
					<# }else{ #>
						{{=avatar }}
					<# } #>
					</a>
				</li>
			</script>
			<script type="text/template" id="header_signin_template">
				<li class="non-login">
					<a id="authenticate" class="authenticate" href="#">
						<?php _e("SIGN IN", ET_DOMAIN); ?>
					</a>
				</li>
			</script>
			<div class="clearfix"></div>
		</section>
		
		<!-- Opition Search Form -->
		<section id="option-search-form" class="option-search-form-wrapper option-contact-search">
			<div class="container">
				<div class="row">
					<form action="<?php echo home_url(); ?>" method="get">
						<div class="col-md-6">
							<ul class="option-search left">
								<li>
									<span><?php _e("KEYWORD:", ET_DOMAIN); ?></span>
									<input value="<?php echo (isset($_REQUEST['s']) ? $_REQUEST['s'] : '') ?>" type="text" name="s" class="option-search-textfield" placeholder="<?php _e("Enter your keyword", ET_DOMAIN); ?>" />
								</li>	
								<li>
									<span><?php _e("LOCATION:", ET_DOMAIN); ?></span>
									<?php ae_tax_dropdown( 'location' , 
														array(  'class' => 'chosen-single tax-item', 
																'hide_empty' => true, 
																'hierarchical' => true , 
																// 'id' => 'location' , 
																'show_option_all' => __("Select your location", ET_DOMAIN) ,
																'value' => 'slug',
																'name' => 'l',
																'name_option' => 'l',
																'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
															) 
														) ;?> 
								</li>
								<li>
									<span><?php _e("CATEGORIES:", ET_DOMAIN); ?></span>
									<?php ae_tax_dropdown( 'place_category' , 
														array(  'class' => 'chosen-single tax-item', 
																'show_option_all' => __("Select your category", ET_DOMAIN) ,
																'hide_empty' => true, 
																'hierarchical' => true , 
																// 'id' => 'place_category' , 
																'value' => 'slug', 
																'name' => 'c',
																'name_option' => 'c',
																'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
															) 
													) ;?> 
									
								</li>	
							</ul>
						</div>
						<div class="col-md-6">
							<ul class="option-search right">
								<li>
									<span><?php _e("WITH IN:", ET_DOMAIN); ?></span>
									<input  data-name="radius" type="text" class="slider-value-default slider-ranger nearby" value="0" 
											data-slider-min="0" data-slider-max="50" 
											data-slider-step="1" data-slider-value="0" 
											data-slider-orientation="horizontal" data-slider-selection="before" 
											data-slider-tooltip="show"
										/>
									<span class="text-desc">&#60; <?php echo "<em class='radius'>50</em> ".de_unit_text() ; ?></span>
								</li>	
								<li>
									<span><?php _e("FROM DAY:", ET_DOMAIN); ?></span>
									<input data-name="days" type="text" class="slider-value-default slider-ranger" value="0" data-slider-enabled="false" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="0" data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" />
									<span class="text-desc">&#60; <?php printf(__("%s days", ET_DOMAIN) , "<em class='days'>100</em>"); ?></span>
								</li>
								<li>
									<input type="hidden" name="radius" id="radius" />
									<input type="hidden" name="days" id="days" />
									<input type="hidden" name="price" id="price" />
									<input type="hidden" name="center" id="center" />
									<input class="btn-search" type="submit" value="<?php _e("Search", ET_DOMAIN); ?>" />
								</li>
							</ul>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- Opition Search Form / End -->
		<div class="option-contact-phone">
			<p><span class="txt-phone"><?php _e('Contact phone number',ET_DOMAIN);?>:</span><span class="val-phone"><?php echo de_support_phone_info(); ?></span></p>
		</div>
		<div class="option-contact-email">
			<p><span class="txt-email"><?php _e('Contact email',ET_DOMAIN);?>:</span><span class="val-email"><?php echo de_support_email_info(); ?></span></p>
		</div>
	</header>
	<!-- Header / End -->
	
	<!-- Marsk -->
	<div class="marsk-black"></div>
	<!-- Marsk / End -->
    
	<div id="page">

<?php
	if( is_show_top_fullwidth_map() ) {
		get_sidebar( 'fullwidth-top' );	
	}

	
