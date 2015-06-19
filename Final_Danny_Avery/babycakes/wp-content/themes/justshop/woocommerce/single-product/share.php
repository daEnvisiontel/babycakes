<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $woo_options;
 
				if ( !isset( $woo_options['woo_default_sharing_button'] ) || ('true' == $woo_options['woo_default_sharing_button']) ) { ?>

				<div class="shop-sharebox">
							<?php echo do_shortcode('[twitter use_post_url="true" float="left"]'); ?>
							<?php echo do_shortcode('[fblike style="button_count" float="left"]'); ?>
				</div>
				<?php } ?>

<?php do_action( 'woocommerce_share' ); // Sharing plugins can hook into here ?>