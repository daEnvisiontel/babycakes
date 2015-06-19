<?php
/*
This code is by Adrian Diaconescu, properly permitted and licensed to use in the theme.
Author: Adrian Diaconescu
Author URI: http://adriandiaconescu.com
*/

// plugin folder url


if (!defined('RMC_PLUGIN_URL')) {
	$PLUGIN_URL = trailingslashit(get_bloginfo('template_url')) . 'includes/retail-menu-cards/';
	define('RMC_PLUGIN_URL', $PLUGIN_URL);
}

if (!defined('RMC_PLUGIN_DIR')) {
	$PLUGIN_DIR = trailingslashit(get_bloginfo('template_directory')) . 'includes/retail-menu-cards/';
	define('RMC_PLUGIN_DIR', $PLUGIN_DIR);
}


$rmc_options = get_option('rmc_settings');

// setting up defaults @templatation
if( empty($rmc_options['nothumb']) ) { $rmc_options['nothumb'] = false; }
if( empty($rmc_options['noprice']) ) { $rmc_options['noprice'] = false; }
if( empty($rmc_options['nodesc']) ) { $rmc_options['nodesc'] = false; }
if( empty($rmc_options['nolabels']) ) { $rmc_options['nolabels'] = false; }
if( empty($rmc_options['position']) ) { $rmc_options['position'] = false; }

// includes

require_once('includes/post-types.php');
require_once('includes/taxonomies.php');
require_once('includes/scripts.php');
require_once('includes/shortcodes.php');

if (is_admin()) {
	require_once('includes/settings.php');
	require_once('includes/metabox.php');
	require_once('includes/admin-tweaks.php');
}

// below widget trigger, Moved to theme../includes/widgets for consistency.

/*function rmc_widgets_init() {
	register_widget('RMC_Menu_Items_Widget');
}
add_action('widgets_init', 'rmc_widgets_init');*/