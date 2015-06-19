<?php

// enqueue front-end scripts and plugin base css

function rmc_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-flexslider', RMC_PLUGIN_URL . 'assets/js/jquery.flexslider.js', array('jquery'));
	wp_enqueue_script('theme', RMC_PLUGIN_URL . 'assets/js/theme.js', array('jquery-flexslider'));
	wp_enqueue_style('flexslider', RMC_PLUGIN_URL . 'assets/css/flexslider.css');
	wp_enqueue_style('retail-menu-cards', RMC_PLUGIN_URL . 'assets/css/retail-menu-cards.css');
}
// @templataion , we dont need flexslider as we dont need widget, also the styles moved to main style file. so we dont need to hook this function at all
// add_action('wp_enqueue_scripts', 'rmc_enqueue_scripts', 12, 1);

// add custom css (if any)

function rmc_custom_css() {
	global $rmc_options;
	if (!is_admin()) { 
		if (!empty($rmc_options['css'])) { ?>	
			<style type="text/css">
				<?php echo $rmc_options['css']; ?>
			</style>
		<?php
		}
	}
}
add_action('wp_head', 'rmc_custom_css');

?>