<?php

require_once dirname(__FILE__) . '/aecore/index.php';

if(!class_exists('AE_Base')) return;

require_once dirname(__FILE__) . '/class-tgm-plugin-activation.php';

require_once dirname(__FILE__) . '/admin.php';
require_once dirname(__FILE__) . '/blogpost.php';
require_once dirname(__FILE__) . '/mailing.php';
require_once dirname(__FILE__) . '/members.php';
require_once dirname(__FILE__) . '/places.php';
require_once dirname(__FILE__) . '/pictures.php';
require_once dirname(__FILE__) . '/events.php';
require_once dirname(__FILE__) . '/testimonials.php';
require_once dirname(__FILE__) . '/post-meta-box.php';
require_once dirname(__FILE__) . '/reviews.php';
// require_once dirname(__FILE__) . '/schedule.php';
require_once dirname(__FILE__) . '/packages.php';
require_once dirname(__FILE__) . '/template.php';
require_once dirname(__FILE__) . '/theme.php';
// require_once dirname(__FILE__) . '/category.php';
require_once dirname(__FILE__) . '/widgets.php';

require_once dirname(__FILE__) . '/upgrade-db.php';

require_once dirname(__FILE__) . '/taxonomy-image.php';

if ( et_is_plugin_active( 'js_composer/js_composer.php' ) ) {

	$de_block_dir = get_template_directory().'/includes/vc_blocks/';
	require_once( $de_block_dir . 'feature.php' );
	require_once( $de_block_dir . 'service.php' );
	require_once( $de_block_dir . 'place.php' );
	require_once( $de_block_dir . 'review.php' );
	require_once( $de_block_dir . 'testimonial.php' );
	require_once( $de_block_dir . 'blog.php' );
	require_once( $de_block_dir . 'category.php' );
	require_once( $de_block_dir . 'areas.php' );
	add_action('admin_enqueue_scripts', 'print_vc_scripts');
	function print_vc_scripts(){
		wp_enqueue_script('vc_extend_script', get_template_directory_uri().'/includes/vc_blocks/js/vc_extend_script.js' , array('jquery') );
		wp_enqueue_style('vc_extend_style', get_template_directory_uri().'/includes/vc_blocks/css/vc_extend_script.css' );		
	}
}