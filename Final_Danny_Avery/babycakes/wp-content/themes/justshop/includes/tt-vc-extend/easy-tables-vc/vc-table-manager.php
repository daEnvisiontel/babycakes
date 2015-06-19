<?php
/* THIS PLUGIN IS INCLUDED IN TEMPLATATION.COM'S WORDPRESS THEME WITH EXPLICIT PERMISSION FROM PLUGIN AUTHOR. */
/*
Plugin Name: Easy Tables (vc)
Plugin URI: http://vc.wpbakery.com/
Description: Table Manager for Visual Composer on Steroids
Version: 1.0.2
Author: WPBakery
Author URI: http://wpbakery.com
License: http://codecanyon.net/licenses
*/


// don't load directly
if (!defined('ABSPATH')) die('-1');
define('WPB_VC_TABLE_REQUIRED_VERSION', '3.6.8');

function vc_table_manager_notice() {
    $plugin_data = get_plugin_data(__FILE__);
    echo '
  <div class="updated">
    <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_template'), $plugin_data['Name']) . '</p>
  </div>';
}
function vc_table_manager_notice_version() {
    $plugin_data = get_plugin_data(__FILE__);
    echo '
  <div class="updated">
    <p>' . sprintf(__('<strong>%s</strong> requires <strong>%s</strong> version of <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site. Current version is %s.', 'vc_template'), $plugin_data['Name'], WPB_VC_TABLE_REQUIRED_VERSION, WPB_VC_VERSION) . '</p>
  </div>';
}


// Get directory path of this plugin.
/*$dir = dirname(__FILE__);*/
$dir = trailingslashit(get_template_directory()) . 'includes/tt-vc-extend/easy-tables-vc';
$vcttdir = trailingslashit(get_template_directory()) . 'includes/tt-vc-extend/easy-tables-vc'; // added by tt for global access.
$vctturl = trailingslashit(get_bloginfo('template_url')) . 'includes/tt-vc-extend/easy-tables-vc'; // added by tt for global access.
// Template manager main class is required.
require_once($dir . '/lib/vc_table_manager.php');

/**
 * Registry hooks
 */

//register_activation_hook(__FILE__, array('VcTableManager', 'install'));

add_action('init', 'vc_table_manager_init');
/**
 * Initialize Templatera with init action.
 */
function vc_table_manager_init() {
    global $vc_table_manager;
    $dir = trailingslashit(get_template_directory()) . 'includes/tt-vc-extend/easy-tables-vc';
    /*
        Display notice if Visual Composer is not installed or activated.
    */
    if (!defined('WPB_VC_VERSION')) {
        add_action('admin_notices', 'vc_table_manager_notice');
        return;
    } elseif(version_compare(WPB_VC_VERSION, WPB_VC_TABLE_REQUIRED_VERSION) < 0) {
        add_action('admin_notices', 'vc_table_manager_notice_version');
        return;
    }
    // Init or use instance of the manager.
    $vc_table_manager = new VcTableManager($dir);
    $vc_table_manager->init();
}