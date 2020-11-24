<?php

/**
* Additional hooks for "Permalink Manager Pro"
*/
class Permalink_Manager_Pro_Functions extends Permalink_Manager_Class {

	public function __construct() {
		define( 'PERMALINK_MANAGER_PRO', true );

		// Stop words
		add_filter( 'permalink_manager_filter_default_post_slug', array($this, 'remove_stop_words'), 9, 3 );
		add_filter( 'permalink_manager_filter_default_term_slug', array($this, 'remove_stop_words'), 9, 3 );

		// Custom fields in permalinks
		add_filter( 'permalink_manager_filter_default_post_uri', array($this, 'replace_custom_field_tags'), 9, 5 );
		add_filter( 'permalink_manager_filter_default_term_uri', array($this, 'replace_custom_field_tags'), 9, 5 );

		// Permalink Manager Pro Alerts
		add_filter( 'permalink-manager-alerts', array($this, 'pro_alerts'), 9, 3 );

		// Save redirects
		add_action( 'permalink-manager-updated-post-uri', array($this, 'save_redirects'), 9, 5 );
		add_action( 'permalink-manager-updated-term-uri', array($this, 'save_redirects'), 9, 5 );

		// Check for updates
		add_action( 'init', array($this, 'check_for_updates'), 9 );
	}

	/**
	 * Update check
	 */
	public function check_for_updates() {
		global $permalink_manager_options;

		// Get the licence key
		$license_key = (!empty($permalink_manager_options['licence']['licence_key'])) ? $permalink_manager_options['licence']['licence_key'] : "";

		// Get expiration date
		// add_filter('puc_request_info_result-permalink-manager-pro', array($this, 'update_pro_info'), 99, 2);

		// Load Plugin Update Checker by YahnisElsts
		require_once PERMALINK_MANAGER_DIR . '/includes/ext/plugin-update-checker/plugin-update-checker.php';

		$UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			"https://updates.permalinkmanager.pro/?action=get_metadata&slug=permalink-manager-pro&license_key={$license_key}",
			PERMALINK_MANAGER_FILE,
			"permalink-manager-pro"
		);

		$file = PERMALINK_MANAGER_BASENAME;
	}

	/**
	 * Stop words
	 */
	static function load_stop_words_languages() {
		return array (
			'ar' => __('Arabic', 'permalink-manager'),
			'zh' => __('Chinese', 'permalink-manager'),
			'da' => __('Danish', 'permalink-manager'),
			'nl' => __('Dutch', 'permalink-manager'),
			'en' => __('English', 'permalink-manager'),
			'fi' => __('Finnish', 'permalink-manager'),
			'fr' => __('French', 'permalink-manager'),
			'de' => __('German', 'permalink-manager'),
			'he' => __('Hebrew', 'permalink-manager'),
			'hi' => __('Hindi', 'permalink-manager'),
			'it' => __('Italian', 'permalink-manager'),
			'ja' => __('Japanese', 'permalink-manager'),
			'ko' => __('Korean', 'permalink-manager'),
			'no' => __('Norwegian', 'permalink-manager'),
			'fa' => __('Persian', 'permalink-manager'),
			'pl' => __('Polish', 'permalink-manager'),
			'pt' => __('Portuguese', 'permalink-manager'),
			'ru' => __('Russian', 'permalink-manager'),
			'es' => __('Spanish', 'permalink-manager'),
			'sv' => __('Swedish', 'permalink-manager'),
			'tr' => __('Turkish', 'permalink-manager')
		);
	}

	/**
	 * Load stop words
	 */
	static function load_stop_words($iso = '') {
		$json_dir = PERMALINK_MANAGER_DIR . "/includes/ext/stopwords-json/dist/{$iso}.json";
		$json_a = array();

		if(file_exists($json_dir)) {
			$string = file_get_contents($json_dir);
			$json_a = json_decode($string, true);
		}

		return $json_a;
	}

	/**
	 * Remove stop words from default URIs
	 */
	public function remove_stop_words($slug, $object, $name) {
		global $permalink_manager_options;

		if(!empty($permalink_manager_options['stop-words']['stop-words-enable']) && !empty($permalink_manager_options['stop-words']['stop-words-list'])) {
			$stop_words = explode(",", strtolower(stripslashes($permalink_manager_options['stop-words']['stop-words-list'])));

			foreach($stop_words as $stop_word) {
				$stop_word = trim($stop_word);
				$slug = preg_replace("/([\/-]|^)({$stop_word})([\/-]|$)/", '$1$3', $slug);
			}

			// Clear the slug
			$slug = preg_replace("/(-+)/", "-", trim($slug, "-"));
			$slug = preg_replace("/(-\/-)|(\/-)|(-\/)/", "/", $slug);
		}

		return $slug;
	}

	/**
	 * Hide "Buy Permalink Manager Pro" alert
	 */
	function pro_alerts($alerts = array()) {
		global $permalink_manager_options;

		if(empty($permalink_manager_options['licence']['licence_key'])) {
			$alerts['licence_key'] = array('txt' => sprintf(__("Please paste the licence key <a href=\"%s\">here</a> to access all Permalink Manager Pro updates!", "permalink-manager"), admin_url('tools.php?page=permalink-manager&section=settings')), 'type' => 'notice-error', 'show' => 1);
		}

		return $alerts;
	}

	/**
	 * Replace custom field tags in default post URIs
	 */
	function replace_custom_field_tags($default_uri, $native_slug, $element, $slug, $native_uri) {
		// Do not affect native URIs
		if($native_uri == true) { return $default_uri; }

		preg_match_all("/%__(.[^\%]+)%/", $default_uri, $custom_fields);

		if(!empty($custom_fields[1])) {
			foreach($custom_fields[1] as $i => $custom_field) {
				if(!empty($element->ID)) {
					$custom_field_value = get_post_meta($element->ID, $custom_field, true);
				} else if(!empty($element->term_id) && function_exists('get_field')) {
					$custom_field_value = get_field($custom_field, "{$element->taxonomy}_{$element->term_id}");
				}

				// Allow to filter the custom field value
				$custom_field_value = apply_filters('permalink_manager_custom_field_value', $custom_field_value, $custom_field, $element);

				// Make sure that custom field is a string
				if(!empty($custom_field_value) && is_string($custom_field_value)) {
					$default_uri = str_replace($custom_fields[0][$i], sanitize_title($custom_field_value), $default_uri);
				}
			}
		}

		return $default_uri;
	}

	public function update_pro_info($raw, $result) {
		if(!empty($result['body'])) {
			$plugin_info = json_decode($result['body']);
			if(!empty($plugin_info['expiration_date'])) {
				// $plugin_info['expiration_date'];
			}
		}
		return $raw;
	}

	/**
	 * Save Redirects
	 */
	public function save_redirects($element_id, $new_uri, $old_uri, $native_uri, $default_uri) {
		global $permalink_manager_options, $permalink_manager_redirects;

		// Terms IDs should be prepended with prefix
		$element_id = (current_filter() == 'permalink-manager-updated-term-uri') ? "tax-{$element_id}" : $element_id;

		// Make sure that $permalink_manager_redirects variable is an array
		$permalink_manager_redirects = (is_array($permalink_manager_redirects)) ? $permalink_manager_redirects : array();

		// AA. Post/term is saved or updated
		if(isset($_POST['permalink-manager-redirects']) && is_array($_POST['permalink-manager-redirects'])) {
			$permalink_manager_redirects[$element_id] = array_filter($_POST['permalink-manager-redirects']);
			$redirects_updated = true;
		}
		// AB. All redirects are removed
		else if(isset($_POST['permalink-manager-redirects'])) {
			$permalink_manager_redirects[$element_id] = array();
			$redirects_updated = true;
		}

		// No longer needed
		unset($_POST['permalink-manager-redirects']);

		// B. Custom URI is updated
		if(get_option('page_on_front') != $element_id && !empty($permalink_manager_options['general']['setup_redirects']) && ($new_uri != $old_uri)) {
			// Make sure that the array with redirects exists
			$permalink_manager_redirects[$element_id] = (!empty($permalink_manager_redirects[$element_id])) ? $permalink_manager_redirects[$element_id] : array();

			// Append the old custom URI
			$permalink_manager_redirects[$element_id][] = $old_uri;
			$redirects_updated = true;
		}

		if(!empty($redirects_updated) && is_array($permalink_manager_redirects[$element_id])) {
			// Remove empty redirects
			$permalink_manager_redirects[$element_id] = array_filter($permalink_manager_redirects[$element_id]);

			if(!empty($permalink_manager_redirects[$element_id])) {
				// Sanitize the array with redirects
				$permalink_manager_redirects[$element_id] = array_map('Permalink_Manager_Helper_Functions::sanitize_title', $permalink_manager_redirects[$element_id]);

				// Reset the keys
				$permalink_manager_redirects[$element_id] = array_values($permalink_manager_redirects[$element_id]);

				// Remove the duplicates
				$permalink_manager_redirects[$element_id] = array_unique($permalink_manager_redirects[$element_id]);
				Permalink_Manager_Actions::clear_single_element_duplicated_redirect($element_id, true);
			}

			// Save the redirects
			update_option('permalink-manager-redirects', $permalink_manager_redirects);
		}
	}

	/**
	 * WooCommerce Coupon URL functions
	 */
	public static function woocommerce_coupon_uris($post_types) {
		$post_types = array_diff($post_types, array('shop_coupon'));
		return $post_types;
	}

	public static function woocommerce_coupon_tabs($tabs = array()) {
		$tabs['coupon-url'] = array(
			'label' => __( 'Coupon Link', 'permalink-manager' ),
			'target' => 'permalink-manager-coupon-url',
			'class' => 'permalink-manager-coupon-url',
		);

		return $tabs;
	}

	public static function woocommerce_coupon_panel() {
		global $permalink_manager_uris, $post;

		$custom_uri = (!empty($permalink_manager_uris[$post->ID])) ? $permalink_manager_uris[$post->ID] : "";

		$html = "<div id=\"permalink-manager-coupon-url\" class=\"panel woocommerce_options_panel custom_uri_container permalink-manager\">";

		// URI field
		ob_start();
			wp_nonce_field('permalink-manager-coupon-uri-box', 'permalink-manager-nonce', true);

			woocommerce_wp_text_input(array(
				'id' => 'custom_uri',
				'label' => __( 'Coupon URI', 'permalink-manager' ),
				'description' => '<span class="duplicated_uri_alert"></span>' . __( 'The URIs are case-insensitive, eg. <strong>BLACKFRIDAY</strong> and <strong>blackfriday</strong> are equivalent.', 'permalink-manager' ),
				'value' => $custom_uri,
				'custom_attributes' => array('data-element-id' => $post->ID),
				//'desc_tip' => true
			));

			$html .= ob_get_contents();
		ob_end_clean();

		// URI preview
		$html .= "<p class=\"form-field coupon-full-url hidden\">";
		$html .= sprintf("<label>%s</label>", __("Coupon Full URL", "permalink-manager"));
 		$html .= sprintf("<code>%s/<span>%s</span></code>", trim(get_option('home'), "/"), $custom_uri);
		$html .= "</p>";

		$html .= "</div>";

		echo $html;
	}

	public static function woocommerce_save_coupon_uri($post_id, $coupon) {
		global $permalink_manager_uris;

		// Verify nonce at first
		if(!isset($_POST['permalink-manager-nonce']) || !wp_verify_nonce($_POST['permalink-manager-nonce'], 'permalink-manager-coupon-uri-box')) { return $post_id; }

		// Do not do anything if post is autosaved
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }

		$old_uri = (!empty($permalink_manager_uris[$post_id])) ? $permalink_manager_uris[$post_id] : "";
		$new_uri = (!empty($_POST['custom_uri'])) ? $_POST['custom_uri'] : "";

		if($old_uri != $new_uri) {
			$permalink_manager_uris[$post_id] = Permalink_Manager_Helper_Functions::sanitize_title($new_uri, true);
			update_option('permalink-manager-uris', $permalink_manager_uris);
		}
	}

	public static function woocommerce_detect_coupon_code($query) {
		global $woocommerce, $pm_query;

		// Check if custom URI with coupon URL is requested
		if(!empty($query['shop_coupon']) && !empty($pm_query['id'])) {
			// Check if cart/shop page is set & redirect to it
			$shop_page_id = wc_get_page_id('shop');
			$cart_page_id = wc_get_page_id('cart');


			if(!empty($cart_page_id) && WC()->cart->get_cart_contents_count() > 0) {
				$redirect_page = $cart_page_id;
			} else if(!empty($shop_page_id)) {
				$redirect_page = $shop_page_id;
			}

			$coupon_code = get_the_title($pm_query['id']);

			// Set-up session
			if(!WC()->session->has_session()) {
				WC()->session->set_customer_session_cookie(true);
			}

			// Add the discount code
			if(!WC()->cart->has_discount($coupon_code)) {
				$woocommerce->cart->add_discount(sanitize_text_field($coupon_code));
			}

			// Do redirect
			if(!empty($redirect_page)) {
				wp_safe_redirect(get_permalink($redirect_page));
				exit();
			}

		}

		return $query;
	}

}

?>
