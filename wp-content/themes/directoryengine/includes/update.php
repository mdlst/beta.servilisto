<?php
/**
 * Plugin updater for Engine Themes
 */
class ET_Update
{
	/**
	 * Product version
	 * @var string
	 */
	public $current_version;
	/**
	 * Product update path
	 * @var string
	 */
	public $update_path;
	/**
	 * Product info url
	 * @var string
	 */
	public $product_url;
	/**
	 * User license key
	 * @var string
	 */
	public $license_key;

	/**
	 * Initialize a new instance of the Engine Theme Auto-Update class
	 *
	 * @param string $current_version
	 * @param string $update_path
	 * @param string $product_slug
	 * @internal param string $plugin_slug
	 */
	function __construct($current_version, $update_path, $product_slug){
		$this->current_version 	= $current_version;
		$this->update_path 		= $update_path;
		$this->product_slug 	= $product_slug;
		$this->product_url 		= $update_path;
		$this->license_key 		= get_option('et_license_key');

	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 * @param $update_info
	 * @return object $update_info
	 */
	public function check_update($update_info)
	{
		global $wp_version;
		
		if ( empty($update_info->checked) )
			return $update_info;

		// get remote version
		$remote_version = $this->get_remote_version();
		// if a new version is alvaiable, add the update
		if ( version_compare( $this->current_version, $remote_version, '<')){
			$obj 				= new stdClass();
			$obj->slug 			= $this->product_slug;
			$obj->new_version 	= $remote_version;
			$obj->url 			= $this->product_url;
			$obj->package 		= add_query_arg('key', $this->license_key ,$this->update_path);
			$update_info->response[$this->product_slug] = $obj;
		}
		return $update_info;
	}


	/**
	 * Return the remote version 
	 * @return string $remote_version
	 */
	public function get_remote_version()
	{
		// send version request
		$request = wp_remote_post($this->update_path, array(
			'body' => array(
				'action' 		=> 'version',
				'product' 		=> $this->product_slug,
				'key' 			=> $this->license_key
			)));
		// check request if it is valid
		if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {  
			return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $request['body']);//$request['body']; 
		}  
		return false;
	}
	
	/**
	 * Return the status of the plugin licensing
	 * @return boolean $remote_license
	 */
	public function getRemote_license()
	{
	}
}

/**
 * Handle updating themes for engine themes
 */
class ET_Theme_Updater extends ET_Update{
	/**
	 * construct ET_Theme_Updater
	 * @param string $current_version
	 * @param string $update_path
	 * @param string $product_slug
	 * @param string $product_url
     */
	public function __construct($current_version, $update_path, $product_slug, $product_url = ''){
		parent::__construct($current_version, $update_path, $product_slug);
		$this->product_url = $product_url;

		// define the alternative API for updating checking  
		add_filter('pre_set_site_transient_update_themes', array(&$this, 'check_update'));
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 * @param $update_info
	 * @return object $ transient
	 * @internal param $transient
	 */
	public function check_update($update_info)
	{
		global $wp_version;
		
		if ( empty($update_info->checked) )
			return $update_info;

		// get remote version
		$remote_version = $this->get_remote_version();
		// if a new version is alvaiable, add the update
		if ( version_compare( $this->current_version, $remote_version, '<')){
			$obj 				= new stdClass();
			$obj->slug 			= $this->product_slug;
			$obj->new_version 	= $remote_version;
			$obj->url 			= $this->product_url;
			$obj->package 		= add_query_arg( array(
				'key' 	=> $this->license_key,
				'type' 	=> 'theme'
				), $this->update_path); //$this->update_path; 
			$update_info->response[$this->product_slug] = (array)$obj;
		}
		return $update_info;
	}
}

// initialize theme update
add_action('init', 'et_check_update');
function et_check_update(){

	// install themes updater
	$update_path = ET_UPDATE_PATH . '&product='.THEME_NAME.'&type=theme';
	new ET_Theme_Updater(ET_VERSION, $update_path, THEME_NAME, 'http://enginethemes.com');
}
