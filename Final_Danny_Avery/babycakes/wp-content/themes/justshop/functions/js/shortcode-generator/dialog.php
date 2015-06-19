<?php
if ( ! isset( $_GET['woo-shortcodes-nonce'] ) || ( $_GET['woo-shortcodes-nonce'] == '' ) ) die( 'Security check' );

// Get the path to the root.
$full_path = __FILE__;

$path_bits = explode( 'wp-content', $full_path );

$url = $path_bits[0];

// Require WordPress bootstrap.
require_once( $url . '/wp-load.php' );

// Nonce security check.    
$nonce = $_GET['woo-shortcodes-nonce'];
if ( ! wp_verify_nonce( $nonce, 'wooframework-shortcode-generator' ) ) die( 'Security check' );

$woo_framework_version = get_option( 'woo_framework_version' );

$MIN_VERSION = '2.9';

$meetsMinVersion = version_compare($woo_framework_version, $MIN_VERSION) >= 0;

$woo_framework_path = dirname(__FILE__) .  '/../../';

$woo_framework_url = get_template_directory_uri() . '/functions/';

$woo_shortcode_css = $woo_framework_path . 'css/shortcodes.css';
                                  
$isWooTheme = file_exists($woo_shortcode_css);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<div id="woo-dialog">

<?php if ( $meetsMinVersion && $isWooTheme ) { ?>
<div id="woo-options-buttons" class="clear">
	<div class="alignleft">
	
	    <input type="button" id="woo-btn-cancel" class="button" name="cancel" value="Cancel" accesskey="C" />
	    
	</div>
	<div class="alignright">
	    <input type="button" id="woo-btn-insert" class="button-primary" name="insert" value="Insert" accesskey="I" />  
	</div>
	<div class="clear"></div><!--/.clear-->
</div><!--/#woo-options-buttons .clear-->

<div id="woo-options" class="alignleft">
    <h3><?php echo __( 'Customize the Shortcode', 'templatation' ); ?></h3>
    
	<table id="woo-options-table">
	</table>

</div>
<div class="clear"></div>


<script type="text/javascript" src="<?php echo esc_url( $woo_framework_url . 'js/shortcode-generator/js/column-control.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo esc_url( $woo_framework_url . 'js/shortcode-generator/js/tab-control.js' ); ?>"></script>
<?php  }  else { ?>

<div id="woo-options-error">

    <h3><?php echo __( 'Ninja Trouble', 'templatation' ); ?></h3>
    
    <?php if ( $isWooTheme && ( ! $meetsMinVersion ) ) { ?>
    <p><?php echo sprinf ( __( 'Your version of the WooFramework (%s) does not yet support shortcodes. Shortcodes were introduced with version %s of the framework.', 'templatation' ), $woo_framework_version, $MIN_VERSION ); ?></p>
    
    <h4><?php echo __( 'What to do now?', 'templatation' ); ?></h4>
    
    <p><?php echo __( 'Upgrading your theme, or rather the WooFramework portion of it, will do the trick.', 'templatation' ); ?></p>

	<p><?php echo sprintf( __( 'The framework is a collection of functionality that all templatation have in common. In most cases you can update the framework even if you have modified your theme, because the framework resides in a separate location (under %s).', 'templatation' ), '<code>/functions/</code>' ); ?></p>
	
	<p><?php echo sprintf ( __( 'There\'s a tutorial on how to do this on templatation.com: %sHow to upgradeyour theme%s.', 'templatation' ), '<a title="templatation Tutorial" target="_blank" href="http://www.templatation.com/2009/08/how-to-upgrade-your-theme/">', '</a>' ); ?></p>
	
	<p><?php echo __( '<strong>Remember:</strong> Every Ninja has a backup plan. Safe or not, always backup your theme before you update it or make changes to it.', 'templatation' ); ?></p>

<?php } else { ?>

    <p><?php echo __( 'Looks like your active theme is not from templatation. The shortcode generator only works with themes from templatation.', 'templatation' ); ?></p>
    
    <h4><?php echo __( 'What to do now?', 'templatation' ); ?></h4>

	<p><?php echo __( 'Pick a fight: (1) If you already have a theme from templatation, install and activate it or (2) if you don\'t yet have one of the awesome templatation head over to the <a href="http://www.templatation.com/themes/" target="_blank" title="templatation Gallery">templatation Gallery</a> and get one.', 'templatation' ); ?></p>

<?php } ?>

<div style="float: right"><input type="button" id="woo-btn-cancel"
	class="button" name="cancel" value="Cancel" accesskey="C" /></div>
</div>

<?php  } ?>

<script type="text/javascript" src="<?php echo esc_url( $woo_framework_url . 'js/shortcode-generator/js/dialog-js.php' ); ?>"></script>
</div>
</body>
</html>