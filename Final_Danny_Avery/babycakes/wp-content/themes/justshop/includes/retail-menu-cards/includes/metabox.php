<?php

// Add the Meta Box

function rmc_add_meta_box() {
    add_meta_box(
		'rmc_meta_box', // $id
		'Item Details', // $title 
		'rmc_show_meta_box', // $callback
		'rmc_menu_item', // $page
		'normal', // $context
		'high'); // $priority
}

add_action('add_meta_boxes', 'rmc_add_meta_box');

// Field Array

$prefix = 'rmc_';
$rmc_meta_fields = array(
	array(
		'label'	=> 'Description',
		'desc'	=> 'Short description for the item.',
		'id'	=> $prefix . 'description',
		'type'	=> 'textarea'
	),
	array(
		'label'	=> 'Price',
		'desc'	=> 'Price for the item.',
		'id'	=> $prefix . 'price',
		'type'	=> 'text'
	)	
);

function rmc_show_meta_box() {
	global $rmc_meta_fields, $post;
	
	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce( 'templatation-save-menucard' ) .'" />';
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($rmc_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
					// text
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
								<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
								<br /><span class="description">'.$field['desc'].'</span>';
					break;			
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data

function rmc_save_post($post_id) {
    global $rmc_meta_fields;
	
	// @templatation 
	if (empty($_POST['custom_meta_box_nonce'])) return;
	// verify nonce
	if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], 'templatation-save-menucard')) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	
	// loop through fields and save the data
	foreach ($rmc_meta_fields as $field) {
		if($field['type'] == 'tax_select') continue;
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	} // enf foreach
	
	// save taxonomies
	$post = get_post($post_id);
	$category =""; if ( !empty($_POST['category']) ) $category = $_POST['category'];
	wp_set_object_terms( $post_id, $category, 'category' );
}
add_action('save_post', 'rmc_save_post');

?>