<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 * @tt-version  wc 2.1 compatible
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $woo_options;
get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<div id="product-misc" class="col-left product-misc-<?php the_ID(); ?>">
		<div class="product-misc-details">
		<?php 
			/**
			 * calling these functions directly instead of hooking for sake of custom template for single product pages @templatation JS v2.5
			 */		
			if ( function_exists( 'woocommerce_output_product_data_tabs' ) ) { woocommerce_output_product_data_tabs(); }
			if ( function_exists( 'woo_upsell_display' ) ) { woo_upsell_display(); }
			if ( function_exists( 'woocommerce_output_related_products' ) ) { 
					if ( !( isset( $woo_options['woocommerce_related_products'] ) &&  'false' == $woo_options['woocommerce_related_products'] ) ) {
								woocommerce_output_related_products(); 
					}
					
			}
		?>
		</div><!-- .product-misc-details -->
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10 ... hardcoded below @templatation JS v2.5
		 */		
		 if ( $woo_options[ 'woocommerce_products_fullwidth' ] == 'false' ) {
			get_sidebar('shop');
		}
		//do_action('woocommerce_sidebar');
 	?>
	</div><!-- #product-misc -->
	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

<?php get_footer( 'shop' ); ?>