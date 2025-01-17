<?php

/**
* Additional functions used in classes and another subclasses
*/
class Permalink_Manager_URI_Functions_Tax extends Permalink_Manager_Class {

	public function __construct() {
		add_action( 'admin_init', array($this, 'init') );

		add_filter( 'term_link', array($this, 'custom_tax_permalinks'), 999, 2 );
		add_filter( 'category_link', array($this, 'custom_tax_permalinks'), 999, 2 );
		add_filter( 'tag_link', array($this, 'custom_tax_permalinks'), 999, 2 );

		/**
		 * URI Editor
		 */
		add_action( 'quick_edit_custom_box', array($this, 'quick_edit_column_form'), 999, 3);
	}

	/**
	* Allow to edit URIs from "Edit Term" admin pages (register hooks)
	*/
	public function init() {
		$all_taxonomies = Permalink_Manager_Helper_Functions::get_taxonomies_array();

		// Add "URI Editor" to "Quick Edit" for all taxonomies
		foreach($all_taxonomies as $tax => $label) {
			// Check if post type is allowed
			if(Permalink_Manager_Helper_Functions::is_disabled($tax, 'taxonomy')) { continue; }

			add_action( "{$tax}_add_form_fields", array($this, 'edit_uri_box'), 10, 1 );
			add_action( "{$tax}_edit_form_fields", array($this, 'edit_uri_box'), 10, 1 );
			add_action( "edited_{$tax}", array($this, 'update_term_uri'), 10, 2 );
			add_action( "create_{$tax}", array($this, 'update_term_uri'), 10, 2 );
			add_action( "delete_{$tax}", array($this, 'remove_term_uri'), 10, 2 );

			add_filter( "manage_edit-{$tax}_columns", array($this, 'quick_edit_column') );
			add_filter( "manage_{$tax}_custom_column" , array($this, 'quick_edit_column_content'), 10, 3 );
		}
	}

	/**
	* Change permalinks for taxonomies
	*/
	function custom_tax_permalinks($permalink, $term) {
		global $wp_rewrite, $permalink_manager_uris, $permalink_manager_options;

		$term_id = (!empty($term->term_id)) ? $term->term_id : $term;

		// Check if taxonomy is allowed
		if(!empty($term->taxonomy) && Permalink_Manager_Helper_Functions::is_disabled($term->taxonomy, 'taxonomy')) { return $permalink; }

		// Apend the language code as a non-editable prefix (can be used also for another prefixes)
		$prefix = apply_filters('permalink-manager-term-permalink-prefix', '', $term);

		if(isset($permalink_manager_uris["tax-{$term_id}"])) {
			$permalink = get_option('home');

			// Encode URI?
			if(!empty($permalink_manager_options['general']['decode_uris'])) {
				$permalink .= urldecode("/{$prefix}{$permalink_manager_uris["tax-{$term_id}"]}");
			} else {
				$permalink .= Permalink_Manager_Helper_Functions::encode_uri("/{$prefix}{$permalink_manager_uris["tax-{$term_id}"]}");
			}
		} else if(!empty($permalink_manager_options['general']['decode_uris'])) {
			$permalink = urldecode($permalink);
		}

		// 5. Remove trailing slashes from URLs with extensions
		$permalink = preg_replace("/(\.[a-z]{3,4})\/$/i", "$1", $permalink);

		return apply_filters('permalink_manager_filter_final_term_permalink', $permalink, $term);
	}

	/**
	* Check if the provided slug is unique and then update it with SQL query.
	*/
	static function update_slug_by_id($slug, $id) {
		global $wpdb;

		// Update slug and make it unique
		$term = get_term(intval($id));
		$slug = (empty($slug)) ? Permalink_Manager_Helper_Functions::sanitize_title(get_the_title($term->name)) : $slug;
		$new_slug = wp_unique_term_slug($slug, $term);

		$wpdb->query("UPDATE $wpdb->terms SET slug = '$new_slug' WHERE term_id = '$id'");

		return $new_slug;
	}

	/**
	* Get the active URI
	*/
	public static function get_term_uri($term_id, $native_uri = false) {
		global $permalink_manager_uris;

		// Check if input is term object
		$term = (isset($term_id->term_id)) ? $term_id->term_id : get_term($term_id);

		$final_uri = (!empty($permalink_manager_uris["tax-{$term_id}"])) ? $permalink_manager_uris["tax-{$term_id}"] : self::get_default_term_uri($term->term_id, $native_uri);
		return $final_uri;
	}

	/**
	* Get the default (not overwritten by the user) or native URI (unfiltered)
	*/
	public static function get_default_term_uri($term, $native_uri = false) {
		global $permalink_manager_options, $permalink_manager_uris, $permalink_manager_permastructs, $wp_rewrite;

		// 1. Load all bases & term
		$term = is_object($term) ? $term : get_term($term);
		$term_id = $term->term_id;
		$taxonomy_name = $term->taxonomy;
		$taxonomy = get_taxonomy($taxonomy_name);
		$term_slug = $term->slug;

		// 2. Get the permastruct
		$default_permastruct = Permalink_Manager_Helper_Functions::get_default_permastruct($taxonomy_name, true);
		if($native_uri) {
			$permastruct = $default_permastruct;
		} else {
			$permastruct = (isset($permalink_manager_permastructs['taxonomies'][$taxonomy_name])) ? $permalink_manager_permastructs['taxonomies'][$taxonomy_name] : $default_permastruct;
		}
		$default_base = (!empty($permastruct)) ? trim($permastruct, '/') : "";

		// 3A. Check if the taxonomy has custom permastructure set
		if(empty($default_base) && !isset($permalink_manager_permastructs['taxonomies'][$taxonomy_name])) {
			if('category' == $taxonomy_name) {
				$default_uri = "?cat={$term->term_id}";
			} elseif ($taxonomy->query_var) {
				$default_uri = "?{$taxonomy->query_var}={$term_slug}";
			} else {
				$default_uri = "?taxonomy={$taxonomy_name}&term={$slug}";
			}
		}
		// 3B. Use custom permastructure
		else {
			$default_uri = $default_base;

			// Allow to filter the native slug
			if(!$native_uri) {
				$term_slug = Permalink_Manager_Helper_Functions::force_custom_slugs($term_slug, $term);
				$term_slug = apply_filters('permalink_manager_filter_default_term_slug', $term_slug, $term, $term->name);
			}

			// Add ancestors to hierarchical taxonomy
			if(is_taxonomy_hierarchical($taxonomy_name)) {
				$hierarchical_slugs = array();
				$ancestors = (array) get_ancestors($term->term_id, $taxonomy_name, 'taxonomy');

				foreach($ancestors as $ancestor) {
					$ancestor_term = get_term($ancestor, $taxonomy_name);
					$hierarchical_slugs[] = ($native_uri) ? $ancestor_term->slug : Permalink_Manager_Helper_Functions::force_custom_slugs($ancestor_term->slug, $ancestor_term);
				}
				$hierarchical_slugs = array_reverse($hierarchical_slugs);

				$full_slug = implode('/', $hierarchical_slugs);
				$full_slug .= "/{$term_slug}";
			} else {
				$full_slug = $term_slug;
			}

			// Decode URI
			$full_slug = urldecode($full_slug);

			// Check if any term tag is present in custom permastructure
			$do_not_append_slug = apply_filters("permalink_manager_do_not_append_slug", false, $taxonomy, $term);
			if($do_not_append_slug == false) {
				$slug_tags = array("%term_name%", "%term_flat%", "%{$taxonomy_name}%", "%{$taxonomy_name}_flat%");
				$slug_tags_replacement = array($full_slug, $term_slug, $full_slug, $term_slug);
				foreach($slug_tags as $tag) {
					if(strpos($default_uri, $tag) !== false) {
						$do_not_append_slug = true;
						break;
					}
				}
			}

			// Replace the term tags with slugs or rppend the slug if no term tag is defined
			if(!empty($do_not_append_slug)) {
				$default_uri = str_replace($slug_tags, $slug_tags_replacement, $default_uri);
			} else {
				$default_uri .= "/{$full_slug}";
			}
		}

		// Clear the URI
		$default_uri = preg_replace('/\s+/', '', $default_uri);
		$default_uri = str_replace('//', '/', $default_uri);
		$default_uri = trim($default_uri, "/");

		return apply_filters('permalink_manager_filter_default_term_uri', $default_uri, $term->slug, $term, $term_slug, $native_uri);
	}

	/**
	* Find & replace (bulk action)
	*/
	public static function find_and_replace() {
		global $wpdb, $permalink_manager_uris;

		// Check if post types & statuses are not empty
		if(empty($_POST['post_types']) || empty($_POST['post_statuses'])) { return false; }

		// Reset variables
		$updated_slugs_count = 0;
		$updated_array = array();
		$alert_type = $alert_content = $errors = '';

		// Process the variables from $_POST object
		$old_string = esc_sql($_POST['old_string']);
		$new_string = esc_sql($_POST['new_string']);

		$taxonomy_names_array = ($_POST['taxonomies']) ? ($_POST['taxonomies']) : '';
		$taxonomy_names = implode("', '", $taxonomy_names_array);
		$mode = isset($_POST['mode']) ? $_POST['mode'] : 'custom_uris';

		// Filter the terms by IDs
		$where = '';
		if(!empty($_POST['ids'])) {
			// Remove whitespaces and prepare array with IDs and/or ranges
			$ids = esc_sql(preg_replace('/\s*/m', '', $_POST['ids']));
			preg_match_all("/([\d]+(?:-?[\d]+)?)/x", $ids, $groups);

			// Prepare the extra ID filters
			$where .= "AND (";
			foreach($groups[0] as $group) {
				$where .= ($group == reset($groups[0])) ? "" : " OR ";
				// A. Single number
				if(is_numeric($group)) {
					$where .= "(t.term_id = {$group})";
				}
				// B. Range
				else if(substr_count($group, '-')) {
					$range_edges = explode("-", $group);
					$where .= "(t.term_id BETWEEN {$range_edges[0]} AND {$range_edges[1]})";
				}
			}
			$where .= ")";
		}

		// Get the rows before they are altered
		$terms_to_update = $wpdb->get_results("SELECT t.slug, t.name, t.term_id, tt.taxonomy FROM {$wpdb->terms} as t INNER JOIN {$wpdb->term_taxonomy} as tt ON tt.term_id = t.term_id WHERE tt.taxonomy IN ('{$taxonomy_names}') {$where}", ARRAY_A);

		// Now if the array is not empty use IDs from each subarray as a key
		if($terms_to_update && empty($errors)) {
			foreach ($terms_to_update as $row) {
				// Prevent server timeout
				set_time_limit(0);

				// Prepare variables
				$this_term = get_term($row['term_id']);
				$term_permalink_id = "tax-{$row['term_id']}";

				// Get default & native URL
				$native_uri = self::get_default_term_uri($this_term, true);
				$default_uri = self::get_default_term_uri($this_term);
				$old_term_name = $row['slug'];
				$old_uri = (isset($permalink_manager_uris[$term_permalink_id])) ? $permalink_manager_uris[$term_permalink_id] : $native_uri;

				// Do replacement on slugs (non-REGEX)
				if(preg_match("/^\/.+\/[a-z]*$/i", $old_string)) {
					// Use $_POST['old_string'] directly here & fix double slashes problem
					$pattern = "~" . stripslashes(trim(sanitize_text_field($_POST['old_string']), "/")) . "~";

					$new_term_name = ($mode == 'slugs') ? preg_replace($pattern, $new_string, $old_term_name) : $old_term_name;
					$new_uri = ($mode != 'slugs') ? preg_replace($pattern, $new_string, $old_uri) : $old_uri;
				} else {
					$new_term_name = ($mode == 'slugs') ? str_replace($old_string, $new_string, $old_term_name) : $old_term_name; // Term slug is changed only in first mode
					$new_uri = ($mode != 'slugs') ? str_replace($old_string, $new_string, $old_uri) : $old_uri;
				}

				//print_r("{$old_uri} - {$new_uri} - {$native_uri} - {$default_uri} \n");

				// Check if native slug should be changed
				if(($mode == 'slugs') && ($old_term_name != $new_term_name)) {
					self::update_slug_by_id($new_term_name, $row['term_id']);
				}

				if(($old_uri != $new_uri) || ($old_term_name != $new_term_name)) {
					$permalink_manager_uris[$term_permalink_id] = $new_uri;
					$updated_array[] = array('item_title' => $row['name'], 'ID' => $row['term_id'], 'old_uri' => $old_uri, 'new_uri' => $new_uri, 'old_slug' => $old_term_name, 'new_slug' => $new_term_name, 'tax' => $this_term->taxonomy);
					$updated_slugs_count++;
				}

				do_action('permalink-manager-updated-term-uri', $row['term_id'], $new_uri, $old_uri, $native_uri, $default_uri);
			}

			// Filter array before saving
			$permalink_manager_uris = array_filter($permalink_manager_uris);
			update_option('permalink-manager-uris', $permalink_manager_uris);

			$output = array('updated' => $updated_array, 'updated_count' => $updated_slugs_count);
			wp_reset_postdata();
		}

		return (!empty($output)) ? $output : "";
	}

	/**
	* Regenerate slugs & bases (bulk action)
	*/
	static function regenerate_all_permalinks() {
		global $wpdb, $permalink_manager_uris;

		// Check if post types & statuses are not empty
		if(empty($_POST['taxonomies'])) { return false; }

		// Process the variables from $_POST object
		$updated_slugs_count = 0;
		$updated_array = array();
		$alert_type = $alert_content = $errors = '';

		$taxonomy_names_array = ($_POST['taxonomies']) ? ($_POST['taxonomies']) : '';
		$taxonomy_names = implode("', '", $taxonomy_names_array);
		$mode = isset($_POST['mode']) ? $_POST['mode'] : 'custom_uris';

		// Filter the terms by IDs
		$where = '';
		if(!empty($_POST['ids'])) {
			// Remove whitespaces and prepare array with IDs and/or ranges
			$ids = esc_sql(preg_replace('/\s*/m', '', $_POST['ids']));
			preg_match_all("/([\d]+(?:-?[\d]+)?)/x", $ids, $groups);

			// Prepare the extra ID filters
			$where .= "AND (";
			foreach($groups[0] as $group) {
				$where .= ($group == reset($groups[0])) ? "" : " OR ";
				// A. Single number
				if(is_numeric($group)) {
					$where .= "(t.term_id = {$group})";
				}
				// B. Range
				else if(substr_count($group, '-')) {
					$range_edges = explode("-", $group);
					$where .= "(t.term_id BETWEEN {$range_edges[0]} AND {$range_edges[1]})";
				}
			}
			$where .= ")";
		}

		// Get the rows before they are altered
		$terms_to_update = $wpdb->get_results("SELECT t.slug, t.name, t.term_id, tt.taxonomy FROM {$wpdb->terms} as t INNER JOIN {$wpdb->term_taxonomy} as tt ON tt.term_id = t.term_id WHERE tt.taxonomy IN ('{$taxonomy_names}') {$where}", ARRAY_A);

		// Now if the array is not empty use IDs from each subarray as a key
		if($terms_to_update && empty($errors)) {
			foreach ($terms_to_update as $row) {
				// Prevent server timeout
				set_time_limit(0);

				// Prepare variables
				$this_term = get_term($row['term_id']);
				$term_permalink_id = "tax-{$row['term_id']}";

				// Get default & native URL
				$native_uri = self::get_default_term_uri($this_term, true);
				$default_uri = self::get_default_term_uri($this_term);
				$old_term_name = $row['slug'];
				$old_uri = (isset($permalink_manager_uris[$term_permalink_id])) ? $permalink_manager_uris[$term_permalink_id] : $native_uri;
				$correct_slug = Permalink_Manager_Helper_Functions::sanitize_title($row['name']);

				// Process URI & slug
				$new_slug = wp_unique_term_slug($correct_slug, $this_term);
				$new_term_name = ($mode == 'slugs') ? $new_slug : $old_term_name; // Post name is changed only in first mode

				// Prepare the new URI
				if($mode == 'slugs') {
					$new_uri = $old_uri;
				} else if($mode == 'native') {
					$new_uri = $native_uri;
				} else {
					$new_uri = $default_uri;
				}

				//print_r("{$old_uri} - {$new_uri} - {$native_uri} - {$default_uri} \n");

				// Check if native slug should be changed
				if(($mode == 'slugs') && ($old_term_name != $new_term_name)) {
					self::update_slug_by_id($new_term_name, $row['term_id']);
				}

				if(($old_uri != $new_uri) || ($old_term_name != $new_term_name)) {
					$permalink_manager_uris[$term_permalink_id] = $new_uri;
					$updated_array[] = array('item_title' => $row['name'], 'ID' => $row['term_id'], 'old_uri' => $old_uri, 'new_uri' => $new_uri, 'old_slug' => $old_term_name, 'new_slug' => $new_term_name, 'tax' => $this_term->taxonomy);
					$updated_slugs_count++;
				}

				do_action('permalink-manager-updated-term-uri', $row['term_id'], $new_uri, $old_uri, $native_uri, $default_uri);
			}

			// Filter array before saving
			$permalink_manager_uris = array_filter($permalink_manager_uris);
			update_option('permalink-manager-uris', $permalink_manager_uris);

			$output = array('updated' => $updated_array, 'updated_count' => $updated_slugs_count);
			wp_reset_postdata();
		}

		return (!empty($output)) ? $output : "";
	}

	/**
	* Update all slugs & bases (bulk action)
	*/
	static public function update_all_permalinks() {
		global $permalink_manager_uris;

		// Setup needed variables
		$updated_slugs_count = 0;
		$updated_array = array();

		$old_uris = $permalink_manager_uris;
		$new_uris = isset($_POST['uri']) ? $_POST['uri'] : array();

		// Double check if the slugs and ids are stored in arrays
		if (!is_array($new_uris)) $new_uris = explode(',', $new_uris);

		if (!empty($new_uris)) {
			foreach($new_uris as $id => $new_uri) {
				// Remove prefix from field name to obtain term id
				$term_id = filter_var(str_replace('tax-', '', $id), FILTER_SANITIZE_NUMBER_INT);

				// Prepare variables
				$this_term = get_term($term_id);
				$updated = '';

				// Get default & native URL
				$native_uri = self::get_default_term_uri($this_term, true);
				$default_uri = self::get_default_term_uri($this_term);

				$old_uri = isset($old_uris[$id]) ? trim($old_uris[$id], "/") : "";

				// Process new values - empty entries will be treated as default values
				$new_uri = preg_replace('/\s+/', '', $new_uri);
				$new_uri = (!empty($new_uri)) ? trim($new_uri, "/") : $default_uri;
				$new_slug = (strpos($new_uri, '/') !== false) ? substr($new_uri, strrpos($new_uri, '/') + 1) : $new_uri;

				if($new_uri != $old_uri) {
					$old_uris[$id] = $new_uri;
					$updated_array[] = array('item_title' => $this_term->name, 'ID' => $term_id, 'old_uri' => $old_uri, 'new_uri' => $new_uri, 'tax' => $this_term->taxonomy);
					$updated_slugs_count++;
				}

				do_action('permalink-manager-updated-term-uri', $term_id, $new_uri, $old_uri, $native_uri, $default_uri);
			}

			// Filter array before saving & append the global
			$old_uris = $permalink_manager_uris = array_filter($old_uris);
			update_option('permalink-manager-uris', $old_uris);

			//print_r($permalink_manager_uris);

			$output = array('updated' => $updated_array, 'updated_count' => $updated_slugs_count);
		}

		return ($output) ? $output : "";
	}

	/**
	* Allow to edit URIs from "New Term" & "Edit Term" admin pages
	*/
	public function edit_uri_box($term = '') {
		$label = __("Custom URI", "permalink-manager");
		$description = __("Clear/leave the field empty to use the default permalink.", "permalink-manager");

		// A. New term
		if (empty($term->term_id)) {
			$html = "<div class=\"form-field\">";
			$html .= "<label for=\"term_meta[uri]\">{$label}</label>";
			$html .= "<input type=\"text\" name=\"custom_uri\" id=\"custom_uri\" value=\"\">";
			$html .= "<p class=\"description\">{$description}</p>";
			$html .= "</div>";

			// Append nonce field
			$html .= wp_nonce_field( 'permalink-manager-edit-uri-box', 'permalink-manager-nonce', true, false );
		}
		// B. Edit term
		else {
			$uri = self::get_term_uri($term->term_id, true);
			$default_uri = self::get_default_term_uri($term->term_id);
			$native_uri = self::get_default_term_uri($term->term_id, true);

			// Make sure that home URL ends with slash
			$home_url = trim(get_option('home'), "/") . "/";
			$prefix = apply_filters('permalink-manager-term-permalink-prefix', '', $term, true);

			$html = "<tr id=\"permalink-manager\" class=\"form-field permalink-manager-edit-term permalink-manager\">";
			$html .= "<th scope=\"row\" valign=\"top\"><label for=\"custom_uri\">{$label}</label></th>";
			$html .= "<td><div>";
			if(!empty($uri)) {
				$html .= Permalink_Manager_Admin_Functions::display_uri_box($term, $default_uri, $uri, $native_uri, "{$home_url}{$prefix}");
			}
			$html .= "</div></td>";
			$html .= "</tr>";
		}

		echo $html;
	}

	/**
	 * "Quick Edit" form
	 */
	function quick_edit_column($columns) {
		return array_merge($columns, array('permalink-manager-col' => __( 'Current URI', 'permalink-manager')));
	}

	function quick_edit_column_content($content, $column_name, $term_id) {
		global $permalink_manager_uris;

		if($column_name != "permalink-manager-col") { return $content; }
		return (!empty($permalink_manager_uris["tax-{$term_id}"])) ? self::get_term_uri($term_id) : self::get_term_uri($term_id, true);
	}

	function quick_edit_column_form($column_name, $post_type, $taxonomy = '') {
		if($taxonomy && $column_name == 'permalink-manager-col') {
			echo Permalink_Manager_Admin_Functions::quick_edit_column_form();
		}
	}

	/**
	* Update URI from "Edit Term" admin page
	*/
	function update_term_uri($term_id, $taxonomy) {
		global $permalink_manager_uris, $wp_current_filter, $current_screen;

		// Term ID must be defined
		if(empty($term_id)) { return; }

		// Check if term was added via "Edit Post" page
		if(!empty($wp_current_filter[0]) && strpos($wp_current_filter[0], 'wp_ajax_add') !== false && empty($_POST['custom_uri'])) {
			$force_default_uri = true;
		} else if(isset($_POST['custom_uri']) && (!isset($_POST['permalink-manager-nonce']) || !wp_verify_nonce($_POST['permalink-manager-nonce'], 'permalink-manager-edit-uri-box'))) { return; }

		// Get the term object
		$this_term = get_term($term_id);
		$term_permalink_id = "tax-{$term_id}";

		// Check if taxonomy is allowed
		if(Permalink_Manager_Helper_Functions::is_disabled($this_term->taxonomy, 'taxonomy')) { return; }

		// Get default & native & user-submitted URIs
		$native_uri = self::get_default_term_uri($this_term, true);
		$default_uri = self::get_default_term_uri($this_term);
		$old_uri = (isset($permalink_manager_uris[$term_permalink_id])) ? $permalink_manager_uris[$term_permalink_id] : "";

		// A little hack (if user removes whole URI from input) +
		// The terms added via "Edit Post" page should have default URI
		$new_uri = (!empty($_POST['custom_uri']) && empty($force_default_uri) && empty($_POST['post_ID'])) ? Permalink_Manager_Helper_Functions::sanitize_title($_POST['custom_uri']) : $default_uri;

		// Save only changed URIs
		if($new_uri != $old_uri) {
			$permalink_manager_uris[$term_permalink_id] = $new_uri;
		}

		do_action('permalink-manager-updated-term-uri', $term_id, $new_uri, $old_uri, $native_uri, $default_uri);

		update_option('permalink-manager-uris', $permalink_manager_uris);
	}

	/**
	* Remove URI from options array after post is moved to the trash
	*/
	function remove_term_uri($term_id) {
		global $permalink_manager_uris;

		// Check if the custom permalink is assigned to this post
		if(isset($permalink_manager_uris["tax-{$term_id}"])) {
			unset($permalink_manager_uris["tax-{$term_id}"]);
		}

		update_option('permalink-manager-uris', $permalink_manager_uris);
	}

}

?>
