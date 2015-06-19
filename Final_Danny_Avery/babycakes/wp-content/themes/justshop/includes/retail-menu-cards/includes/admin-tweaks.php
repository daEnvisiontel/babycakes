<?php

// add custom columns to the menu item list
function rmc_columns_add($columns) {
	unset($columns['date']);
	unset($columns['post_type']);
    return $columns 
         + array('thumbnail' => __('Thumbnail', 'templatation'), 
                 'description' => __('Description', 'templatation'));
}
add_filter('manage_edit-rmc_menu_item_columns', 'rmc_columns_add');

// display the custom columns content
function rmc_columns_content($column) {
	global $post;
	switch ($column) {
		case 'thumbnail':
			echo get_the_post_thumbnail($post->ID, 'thumbnail');
			break;
		case 'description':
			echo get_post_meta($post->ID, 'rmc_description', true);
			break;
	}
}
add_action('manage_posts_custom_column', 'rmc_columns_content');

// add taxonomy filters for the custom post
function rmc_taxonomy_filters() {
	global $typenow;
	
	// an array of our two taxonomies
	$taxonomies = array('rmc_menu_card', 'rmc_menu_label');
 
	// must set this to the post type you want the filter(s) displayed on
	if ($typenow == 'rmc_menu_item') {
 
		foreach ($taxonomies as $tax_slug) {
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$tax_obj = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if ( count( $terms ) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>$tax_name</option>";
				foreach ( $terms as $term ) {
					echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}
}
add_action('restrict_manage_posts', 'rmc_taxonomy_filters');

// give the new admin menu item a unique icon
function rmc_admin_icon() { ?>
    <style type="text/css" media="screen">
        #menu-posts-rmc_menu_item .wp-menu-image {
            background: url(<?php echo RMC_PLUGIN_URL; ?>assets/images/rmc_icon_16.png) no-repeat 6px 6px !important;
        }
		#menu-posts-rmc_menu_item:hover .wp-menu-image, #menu-posts-rmc_menu_item.wp-has-current-submenu .wp-menu-image {
            background-position: 6px -18px !important;
        }
		#icon-edit.icon32-posts-rmc_menu_item {background: url(<?php echo RMC_PLUGIN_URL; ?>assets/images/rmc_icon_32.png) no-repeat;}
    </style>
<?php }
add_action('admin_head', 'rmc_admin_icon');

?>