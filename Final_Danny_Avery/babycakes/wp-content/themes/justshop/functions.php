<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------*/
/* Start templatation Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php'			// Theme widgets
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );

foreach ( $includes as $i ) {
	locate_template( $i, true );
}

require_once( 'includes/testimonials/templatation-testimonials.php' );
require_once( 'includes/retail-menu-cards/retail-menu-cards.php' );
require_once( 'includes/tt-plugins/tt-plugins.php' );
if (function_exists('tt_init_import')) tt_init_import();


if ( is_woocommerce_activated() ) {
	if ( !defined('YITH_WCMG') ) { require_once( 'includes/wc-zm/init.php' ); }
	// removed ajax nav, install from wp repo directly.
	locate_template( 'includes/theme-woocommerce.php', true );
}


/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below. */
/*-----------------------------------------------------------------------------------*/










/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here. */
/*-----------------------------------------------------------------------------------*/
?>