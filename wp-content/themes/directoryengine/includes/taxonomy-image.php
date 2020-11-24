<?php
//var_dump(ae_get_option('default_location_img'));
$default_location_img = ae_get_option('default_location_img');
if($default_location_img['medium_large'][0])
	define('ET_IMAGE_PLACEHOLDER',$default_location_img['medium_large'][0]); 
else
	define('ET_IMAGE_PLACEHOLDER','');
function et_active_taxonomy_image($taxonomy){
	add_action( $taxonomy.'_add_form_fields', 'et_add_texonomy_field', 10, 2 );
	add_action( $taxonomy.'_edit_form_fields', 'et_edit_texonomy_field', 10, 2 );
	add_action( 'edited_'.$taxonomy, 'et_save_taxonomy_field', 10, 2 );  
	add_action( 'create_'.$taxonomy, 'et_save_taxonomy_field', 10, 2 );
	add_filter( 'manage_edit-'.$taxonomy.'_columns', 'et_taxonomy_columns' );
	add_filter( 'manage_'.$taxonomy.'_custom_column', 'et_taxonomy_column', 10, 3 );
	// Style the image in category list
	if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
		add_action( 'admin_head', 'et_add_style' );
		add_action('quick_edit_custom_box', 'et_quick_edit_custom_box', 10, 3);
		add_filter("attribute_escape", "et_change_insert_button_text", 10, 2);
	}
}

// add image field in add form
function et_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}	
	echo '<div class="form-field">
		<label for="taxonomy_image">' . __('Image', 'ET_DOMAIN') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="et_upload_image_button button">' . __('Upload/Add image', 'ET_DOMAIN') . '</button>
	</div>'.et_script();
}

// add image field in edit form
function et_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}	
	if (et_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == ET_IMAGE_PLACEHOLDER) 
		$image_url = "";
	else
		$image_url = et_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('Image', 'ET_DOMAIN') . '</label></th>
		<td><img class="taxonomy-image" src="' . et_taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_url.'" /><br />
		<button class="et_upload_image_button button">' . __('Upload/Add image', 'ET_DOMAIN') . '</button>
		<button class="et_remove_image_button button">' . __('Remove image', 'ET_DOMAIN') . '</button>
		</td>
	</tr>'.et_script();
}
// upload using wordpress upload
function et_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".et_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".et_remove_image_button").click(function() {
				$(".taxonomy-image").attr("src", "'.ET_IMAGE_PLACEHOLDER.'");
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . ET_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").click(function() {	
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

				if (thumb != "' . ET_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				
				$(".inline-edit-col .title img").attr("src",thumb);
			});
	    });
	</script>';
}
// save our taxonomy image while edit or save term
function et_save_taxonomy_field( $term_id ) {
	if(isset($_POST['taxonomy_image']))
        update_option('et_taxonomy_image'.$term_id, $_POST['taxonomy_image'], NULL);
}
// get taxonomy image
function et_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}	
    $taxonomy_image_url = get_option('et_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = et_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id)) {
	    	$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		    $taxonomy_image_url = $taxonomy_image_url[0];
	    }
	}

    if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : ET_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_image_url;
}
// get attachment ID by image url
function et_get_attachment_id_by_url($image_src) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}
function et_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __('Image', 'ET_DOMAIN');

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}
/**
 * Thumbnail column value added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function et_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . et_taxonomy_image_url($id, 'thumbnail', TRUE) . '" alt="' . __('Thumbnail', 'ET_DOMAIN') . '" class="wp-post-image" /></span>';	
	return $columns;
}
function et_add_style() {
	echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image {border:1px solid #eee;max-width:300px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb span {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:48px;height:48px;}
	</style>';
}
function et_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="et_upload_image_button button">' . __('Upload/Add image', 'ET_DOMAIN') . '</button>
					<button class="et_remove_image_button button">' . __('Remove image', 'ET_DOMAIN') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}
// Change 'insert into post' to 'use this image'
function et_change_insert_button_text($safe_text, $text) {
    return str_replace("Insert into Post", "Use this image", $text);
}