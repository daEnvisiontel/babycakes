<?php

// register the "list_item" post type

function rmc_create_post_types() {

	$labels = array(
		'name' => __('Items', 'templatation'),
		'singular_name' => __('Item', 'templatation'),
		'add_new' => __('Add New', 'templatation'),
		'add_new_item' => __('Add New Item', 'templatation'),
		'edit_item' => __('Edit Item', 'templatation'),
		'new_item' => __('New Item', 'templatation'),
		'view_item' => __('View Item', 'templatation'),
		'search_items' => __('Search Items', 'templatation'),
		'not_found' =>  __('No items found', 'templatation'),
		'not_found_in_trash' => __('No items found in Trash', 'templatation'),
		'parent_item_colon' => '',
		'menu_name' => __('Menu Cards', 'templatation'),
		'all_items' => __('All Items', 'templatation')
	);

 	$args = array(
     	'labels' => $labels,     	
		'public' => true,
		'exclude_from_search' => true,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'query_var'           => false,
	  	'capability_type' => 'post',
     	'hierarchical' => false,
     	'rewrite' => array('slug' => 'menucard-item'),
     	'has_archive' => true,
     	'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
		'menu_position' => 4
	);
	
 	register_post_type('rmc_menu_item', $args);
}

add_action('init', 'rmc_create_post_types');


// flush the permalinks on plugin activation and deactivation

function rmc_activate() {
	rmc_create_post_type();
	flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'rmc_activate');

function rmc_deactivate() {
	flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'rmc_deactivate');

?>