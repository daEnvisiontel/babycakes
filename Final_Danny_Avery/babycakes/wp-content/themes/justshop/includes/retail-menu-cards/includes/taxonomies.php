<?php

// Create two taxonomies, "rmc_menu_card" and "rmc_menu_label" for the post type "rmc_menu_item"

function rmc_create_taxonomies() {
	
	// Add new taxonomy, make it hierarchical (like categories)
	
	$labels = array(
		'name' => __('Cards', 'templatation'),
		'singular_name' => __('Card', 'templatation'),
		'search_items' =>  __('Search Cards'),
		'all_items' => __('All Cards'),
		'parent_item' => __('Parent Card'),
		'parent_item_colon' => __('Parent Card:'),
		'edit_item' => __('Edit Card'), 
		'update_item' => __('Update Card'),
		'add_new_item' => __('Add New Card'),
		'new_item_name' => __('New Card Name'),
		'menu_name' => __('Cards'),
	); 	
	
	register_taxonomy('rmc_menu_card', 'rmc_menu_item', array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'menu-card')
	));
	
	// Add new taxonomy, NOT hierarchical (like tags)
	
	$labels = array(
		'name' => __('Labels', 'templatation'),
		'singular_name' => __('Label', 'templatation'),
		'search_items' =>  __('Search Labels', 'templatation'),
		'popular_items' => __('Popular Labels', 'templatation'),
		'all_items' => __('All Labels', 'templatation'),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __('Edit Label', 'templatation'), 
		'update_item' => __('Update Label', 'templatation'),
		'add_new_item' => __('Add New Label', 'templatation'),
		'new_item_name' => __('New Label Name', 'templatation'),
		'separate_items_with_commas' => __('Separate labels with commas', 'templatation'),
		'add_or_remove_items' => __('Add or remove labels', 'templatation'),
		'choose_from_most_used' => __('Choose from the most used labels', 'templatation'),
		'menu_name' => __('Labels', 'templatation'),
	); 
	
	register_taxonomy('rmc_menu_label', 'rmc_menu_item', array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array('slug' => 'menu-label'),
	));
}

add_action('init', 'rmc_create_taxonomies');

?>