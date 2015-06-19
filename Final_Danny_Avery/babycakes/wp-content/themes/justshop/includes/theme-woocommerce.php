<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$woo_options = get_option( 'woo_options' );
//global $woo_options; @templation v2.0 

/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'templatation_woocommerce_image_dimensions', 1 );

/**
 * Define woocommerce image sizes
 */
function templatation_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '280',	// px
		'height'	=> '180',	// px
		'crop'		=> 1 		// true
	);
 
	$single = array(
		'width' 	=> '350',	// px
		'height'	=> '350',	// px
		'crop'		=> 1 		// true
	);
 
	$thumbnail = array(
		'width' 	=> '80',	// px
		'height'	=> '80',	// px
		'crop'		=> 1 		// false
	);
 
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}


// Disable WooCommerce styles
if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
	define( 'WOOCOMMERCE_USE_CSS', false );
}
// Remove default review stuff - the theme overrides it
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
$settings = woo_get_dynamic_values( array( 'disable_responsive' => 'false' ) );
// Load WooCommerce stylsheet

if ( ! is_admin() ) { add_action( 'wp_enqueue_scripts', 'woo_load_woocommerce_css', 20 ); }
if (  ! is_admin() && isset( $woo_options['woo_disable_responsive'] ) && $woo_options['woo_disable_responsive'] == 'true' ) {
	remove_action( 'wp_enqueue_scripts', 'woo_load_woocommerce_css', 20 ); 
}

if ( ! function_exists( 'woo_load_woocommerce_css' ) ) {
	function woo_load_woocommerce_css () {
		wp_register_style( 'woocommerce', esc_url( get_template_directory_uri() . '/css/woocommerce.css' ) );
		wp_enqueue_style( 'woocommerce' );
	} // End woo_load_woocommerce_css()
}

/*
if ( ! function_exists( 'woo_load_woocommerce_css' ) ) {
	function woo_load_woocommerce_css () {
		$tt_woocommerce_style = '';
		if (is_rtl()) $tt_woocommerce_style = "woocommerce-rtl"; else $tt_woocommerce_style = "woocommerce";
		wp_register_style( 'woocommerce', esc_url( get_template_directory_uri() . '/css/'.$tt_woocommerce_style.'.css' ) );
		wp_enqueue_style( 'woocommerce' );
	} // End woo_load_woocommerce_css()
}
*/

/*-----------------------------------------------------------------------------------*/
/* Products */
/*-----------------------------------------------------------------------------------*/

// Number of columns on product archives
add_filter( 'loop_shop_columns', 'wooframework_loop_columns' );
if ( ! function_exists( 'wooframework_loop_columns' ) ) {
	function wooframework_loop_columns() {
		global $woo_options;
		if ( ! isset( $woo_options['woocommerce_product_columns'] ) ) {
			$cols = 2;
		} else {
			$cols = $woo_options['woocommerce_product_columns'] + 2;
		}
		return $cols;
	} // End wooframework_loop_columns()
}

// Number of products per page
add_filter( 'loop_shop_per_page', 'wooframework_products_per_page' );

if ( ! function_exists( 'wooframework_products_per_page' ) ) {
	function wooframework_products_per_page() {
		global $woo_options;
		if ( isset( $woo_options['woocommerce_products_per_page'] ) ) {
			return $woo_options['woocommerce_products_per_page'];
		}
	} // End wooframework_products_per_page()
}

// move image thumbnail below the title for product categories.
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'woocommerce_after_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
// Add the image wrap
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_product_thumbnail_wrap_open', 5, 2);
add_action( 'woocommerce_after_subcategory_title', 'woocommerce_product_thumbnail_wrap_open', 5, 2);

if (!function_exists('woocommerce_product_thumbnail_wrap_open')) {
	function woocommerce_product_thumbnail_wrap_open() {
		echo '<div class="img-wrap">';
	}
}

// Close image wrap
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_product_thumbnail_wrap_close', 15, 2);
add_action( 'woocommerce_after_subcategory_title', 'woocommerce_product_thumbnail_wrap_close', 15, 2);
if (!function_exists('woocommerce_product_thumbnail_wrap_close')) {
	function woocommerce_product_thumbnail_wrap_close() {
		echo '</div> <!--/.wrap-->';
	}
}

// Move the price inside the img-wrap
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 11 );

// Display product categories in the loop
/*add_action( 'woocommerce_after_shop_loop_item', 'justshop_product_loop_categories', 2 );
*/
if (!function_exists('justshop_product_loop_categories')) {
	function justshop_product_loop_categories() {
		global $post;
		$terms_as_text = get_the_term_list( $post->ID, 'product_cat', '', ', ', '' );
		if ( ! is_product_category() ) {
			echo '<div class="categories">' . $terms_as_text . '</div>';
		}
	}
}

// display out-of-stock on product archive
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_stock', 10);
function woocommerce_template_loop_stock() {
	global $product;
 		if ( ! $product->managing_stock() && ! $product->is_in_stock() )
 		echo '<p class="stock out-of-stock">' . __( 'Out of stock', 'templatation' ) . '</p>';
}

// hook description on product category loop
add_action( 'woocommerce_after_subcategory_title', 'tt_prod_cat_desc', 16);
function tt_prod_cat_desc($category) {
			if ( $category->description !== '' ) {
				echo '<div class="description">' . justshop_truncate($category->description,20) . '</div>';
			}
				echo '<a class="readmore" href="'. get_term_link(  $category->slug, 'product_cat' ) .'"><span class="view-more">' .__('view more', 'templatation') . '</span></a>';

}


/*-----------------------------------------------------------------------------------*/
/* Single Product */
/*-----------------------------------------------------------------------------------*/

// Making the elements sorting as required.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );


function justshop_product_reviews() {
	global $post;

	if ( ! comments_open() )
		return;

	$comments = get_comments(array(
		'post_id' => $post->ID,
		'status' => 'approve'
	));

	//if ( sizeof( $comments ) > 0 ) {

		comments_template();

	//}
}

// Display related products?
add_action( 'wp_head','wooframework_related_products' );
if ( ! function_exists( 'wooframework_related_products' ) ) {
	function wooframework_related_products() {
		global $woo_options;
		if ( isset( $woo_options['woocommerce_related_products'] ) &&  'false' == $woo_options['woocommerce_related_products'] ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
	} // End wooframework_related_products()
}

add_filter( 'woocommerce_output_related_products_args', 'tt_related_products' );
function tt_related_products() {
	global $woo_options, $post;
	$single_layout = get_post_meta( $post->ID, '_layout', true );
	$products_max = $woo_options['woocommerce_related_products_maximum'] + 2;
	if ( $woo_options[ 'woocommerce_products_fullwidth' ] == 'true' && ( $single_layout != 'layout-left-content' && $single_layout != 'layout-right-content' ) ) {
		$products_cols = 4;
	} else {
		$products_cols = 3;
	}
	$args = array(
		'posts_per_page' => $products_max,
		'columns'        => $products_cols,
	);
	return $args;
}


// Upsells
if ( ! function_exists( 'woo_upsell_display' ) ) {
	function woo_upsell_display() {
	    // Display up sells in correct layout.
		global $woo_options;
		if ( isset( $woo_options['woo_layout'] ) && ( $woo_options['woo_layout'] == 'layout-full' ) || $woo_options[ 'woocommerce_products_fullwidth' ] == 'true' ) {
			$products_cols = 4;
		} else {
			$products_cols = 3;
		}
	    woocommerce_upsell_display( -1, $products_cols );
	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
// add_action( 'woocommerce_after_single_product_summary', 'woo_upsell_display', 15 ); // moved in single-product.php


// Custom place holder
add_filter( 'woocommerce_placeholder_img_src', 'wooframework_wc_placeholder_img_src' );

if ( ! function_exists( 'wooframework_wc_placeholder_img_src' ) ) {
	function wooframework_wc_placeholder_img_src( $src ) {
		global $woo_options;
		if ( isset( $woo_options['woo_placeholder_url'] ) && '' != $woo_options['woo_placeholder_url'] ) {
			$src = $woo_options['woo_placeholder_url'];
		}
		else {
			$src = get_template_directory_uri() . '/images/wc-placeholder.gif';
		}
		return esc_url( $src );
	} // End wooframework_wc_placeholder_img_src()
}

// If theme lightbox is enabled, disable the WooCommerce lightbox and make product images prettyPhoto galleries
add_action( 'wp_footer', 'woocommerce_prettyphoto' );
function woocommerce_prettyphoto() {
	global $woo_options;
	if ( $woo_options[ 'woo_enable_lightbox' ] == "true" ) {
		update_option( 'woocommerce_enable_lightbox', false );
		?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.images a').attr('rel', 'prettyPhoto[product-gallery]');
				});
			</script>
		<?php
	}
}

// Display 40 images in galleries on single pages (to remove unnecessary last class)
add_filter( 'woocommerce_product_thumbnails_columns', 'woocommerce_custom_product_thumbnails_columns' );

if (!function_exists('woocommerce_custom_product_thumbnails_columns')) {
	function woocommerce_custom_product_thumbnails_columns() {
		return 40;
	}
}

// Display the ratings in the loop and on the single page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 12 );
/*
 * add_action( 'woocommerce_single_product_summary', 'templatation_product_rating_overview', 12 );

if (!function_exists('templatation_product_rating_overview')) {
	function templatation_product_rating_overview() {
		global $product;
		$review_total = get_comments_number();
		if ( $review_total > 0 && get_option( 'woocommerce_enable_review_rating' ) !== 'no' ) {
			echo '<div class="rating-wrap">';
					echo $product->get_rating_html();
			echo '</div>';
		}
	}
}
*/
// Change the add to cart text
add_filter('add_to_cart_text', 'justshop_custom_cart_button_text');

function justshop_custom_cart_button_text() {
    return __('Add', 'templatation');
}


/*-----------------------------------------------------------------------------------*/
/* Layout */
/*-----------------------------------------------------------------------------------*/

// Adjust markup on all woocommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'woocommerce_theme_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'woocommerce_theme_after_content', 20 );

/*moving product-single page bottom out of main wrapper*/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 ); // occurs on line 211
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


if ( ! function_exists( 'woocommerce_theme_before_content' ) ) {
	function woocommerce_theme_before_content() {
		global $woo_options;
		if ( ! isset( $woo_options['woocommerce_product_columns'] ) ) {
			$columns = 'woocommerce-columns-2';
		} else {
			$columns = 'woocommerce-columns-' . ( $woo_options['woocommerce_product_columns'] + 2 );
		}
		?>
		<!-- #content Starts -->
	    <div id="content" class="col-full <?php echo esc_attr( $columns ); ?>">

	        <!-- #main Starts -->
	        <?php woo_main_before(); ?>
	        <div id="main" class="col-left">

	    <?php
	} // End woocommerce_theme_before_content()
}


if ( ! function_exists( 'woocommerce_theme_after_content' ) ) {
	function woocommerce_theme_after_content() {
		?>

			</div><!-- /#main -->
	        <?php woo_main_after(); ?>

	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	} // End woocommerce_theme_after_content()
}

function templatation_profile() {
	global $current_user;
	$url_myaccount 		= get_permalink( woocommerce_get_page_id( 'myaccount' ) );
	$url_editaddress 	= get_permalink( woocommerce_get_page_id( 'edit_address' ) );
	$url_changepass 	= get_permalink( woocommerce_get_page_id( 'change_password' ) );
	$url_vieworder 		= get_permalink( woocommerce_get_page_id( 'view_order' ) );

	?>
				<?php if ( woocommerce_get_page_id( 'myaccount' ) !== -1 ) { ?>
					<li class="my-account"><a href="<?php echo $url_myaccount; ?>" class="" title="<?php if ( is_user_logged_in() ) {  _e('My Account', 'templatation' ); } else { _e( 'Log In', 'templatation' ); } ?>"><span><?php if ( is_user_logged_in() ) { _e('My Account', 'templatation' ); } else { _e( 'Log In', 'templatation' ); } ?></span></a></li>
				<?php } ?>

				<?php if ( ! is_user_logged_in() && woocommerce_get_page_id( 'myaccount' ) !== -1 && get_option('woocommerce_enable_myaccount_registration')=='yes' ) { ?>
					<li class="register"><a href="<?php echo $url_myaccount; ?>" class="" title="<?php _e( 'Register', 'templatation' ); ?>"><span><?php _e( 'Register', 'templatation' ); ?></span></a></li>
				<?php } ?>

				<?php if ( is_user_logged_in() ) { ?>

					<?php if ( woocommerce_get_page_id( 'edit_address' ) !== -1 ) { ?>
						<li class="edit-address"><a href="<?php echo $url_editaddress; ?>" class="" title="<?php _e( 'Edit Address', 'templatation' ); ?>"><span><?php _e( 'Edit Address', 'templatation' ); ?></span></a></li>
					<?php } ?>

					<?php if ( woocommerce_get_page_id( 'change_password' ) !== -1 ) { ?>
						<li class="edit-password"><a href="<?php echo $url_changepass; ?>" class="" title="<?php _e( 'Change Password', 'templatation' ); ?>"><span><?php _e( 'Change Password', 'templatation' ); ?></span></a></li>
					<?php } ?>

					<?php if ( woocommerce_get_page_id( 'view_order' ) !== -1 ) { ?>
						<li class="logout"><a href="<?php echo wp_logout_url( get_permalink() ); ?>" class="" title="<?php _e( 'Logout', 'templatation' ); ?>"><span><?php _e( 'Logout', 'templatation' ); ?></span></a></li>
					<?php } ?>

				<?php } ?>
	<?php

}


// Remove WC breadcrumb (we're using the WooFramework breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

// Customise the breadcrumb
add_filter( 'woo_breadcrumbs_args', 'woo_custom_breadcrumbs_args', 10 );

if (!function_exists('woo_custom_breadcrumbs_args')) {
	function woo_custom_breadcrumbs_args ( $args ) {
		$textdomain = 'templatation';
		$title = get_bloginfo( 'name' );
		$args = array('separator' => ' ', 'show_home' => sprintf( __( "%s", 'templatation' ), $title ));
		return $args;
	} // End woo_custom_breadcrumbs_args()
}

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Add the WC sidebar in the right place and remove it from shop archives if specified
add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10 );

if ( ! function_exists( 'woocommerce_get_sidebar' ) ) {
	function woocommerce_get_sidebar() {
		global $woo_options;

		if ( is_product() ) {
			return; // do nothing if its single product page, as sidebar is placed in single-product.php @templatation JS v2.5
		}
		// Display the sidebar if full width option is disabled on archives
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( isset( $woo_options['woocommerce_archives_fullwidth'] ) && 'false' == $woo_options['woocommerce_archives_fullwidth'] ) {
				get_sidebar('shop');
			}
		}


	} // End woocommerce_get_sidebar()
}

// Remove pagination (we're using the WooFramework default pagination)
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerceframework_pagination', 10 );

if ( ! function_exists( 'woocommerceframework_pagination' ) ) {
function woocommerceframework_pagination() {
	if ( is_search() && is_post_type_archive() ) {
		add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
		add_filter( 'woo_pagination_args_defaults', 'woocommerceframework_woo_pagination_defaults', 10 );
	}
	woo_pagination();
} // End woocommerceframework_pagination()
}

if ( ! function_exists( 'woocommerceframework_add_search_fragment' ) ) {
function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '&post_type=product';

	return $settings;
} // End woocommerceframework_add_search_fragment()
}

if ( ! function_exists( 'woocommerceframework_woo_pagination_defaults' ) ) {
function woocommerceframework_woo_pagination_defaults ( $settings ) {
	$settings['use_search_permastruct'] = false;

	return $settings;
} // End woocommerceframework_woo_pagination_defaults()
}

// Add a class to the body if full width shop archives are specified or if the nav should be hidden
add_filter( 'body_class','wooframework_layout_body_class', 10 );		// Add layout to body_class output
if ( ! function_exists( 'wooframework_layout_body_class' ) ) {
	function wooframework_layout_body_class( $wc_classes ) {
		global $woo_options;

		$layout = '';
		$nav_visibility = '';

		// Add layout-full class if full width option is enabled
		if ( isset( $woo_options['woocommerce_archives_fullwidth'] ) && 'true' == $woo_options['woocommerce_archives_fullwidth'] && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			$layout = 'layout-full';
		}
		if ( isset( $woo_options['woocommerce_products_fullwidth']) && ($woo_options[ 'woocommerce_products_fullwidth' ] == "true") && ( is_product() ) ) {
			$layout = 'layout-full';
		}

		// Add nav-hidden class if specified in theme options
		if ( isset( $woo_options['woocommerce_hide_nav'] ) && 'true' == $woo_options['woocommerce_hide_nav'] && ( is_checkout() ) ) {
			$nav_visibility = 'nav-hidden';
		}

		// Add classes to body_class() output
		$wc_classes[] = $layout;
		$wc_classes[] = $nav_visibility;

		return $wc_classes;
	} // End woocommerce_layout_body_class()
}

// Enable catalog mode if setup in admin.
if ( isset($woo_options['woo_enable_catalog']) && 'true' == $woo_options['woo_enable_catalog'] ) {
	/* remove add to cart button */
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 11 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
}
/*end of add to cart removal code*/

add_filter('add_to_cart_fragments', 'header_add_to_cart_fragment');

function header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	tt_cart_button();

	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;

}

function tt_cart_button() {
	global $woocommerce;
	?>
	<a class="cart-contents header-cart" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'templatation' ); ?>"><?php echo $woocommerce->cart->get_cart_total(); ?></a>
	<?php
}


function tt_mini_cart() {
	global $woocommerce, $woo_options; $settings16 = '';
	$settings16 = woo_get_dynamic_values( array( 'no_hdrcart_popup' => 'false' ) );
	if ( $settings16['no_hdrcart_popup'] == 'true') tt_cart_button(); // no popup if chosen so in Themeoptions
	else {
		tt_cart_button();
		$tit = $tit1 = "";
		$tit = $woocommerce->cart->cart_contents_count;
		$tit1 = __( ' item(s) in Cart ', 'templatation' );
		$tit = "title=".$tit.$tit1;
		the_widget( 'WC_Widget_Cart', $tit );
	}
}

/* List/Grid view */
/* Author: jameskoster */

/**
 * WC_List_Grid class
 **/
if (!class_exists('WC_List_Grid')) {

	class WC_List_Grid {

		public function __construct() {
			// Hooks
			add_action( 'wp' , array( $this, 'setup_gridlist' ) , 20);

			// Init settings
			$this->settings = array(
				array(
					'name' => __( 'Default catalog view', 'templatation' ),
					'type' => 'title',
					'id' => 'wc_glt_options'
				),
				array(
					'name' 		=> __( 'Default catalog view', 'templatation' ),
					'desc_tip' 	=> __( 'Display products in grid or list view by default', 'templatation' ),
					'id' 		=> 'wc_glt_default',
					'type' 		=> 'select',
					'options' 	=> array(
						'grid'  => __('Grid', 'templatation'),
						'list' 	=> __('List', 'templatation'),
						'disable' 	=> __('Disable', 'templatation')
					)
				),
				array( 'type' => 'sectionend', 'id' => 'wc_glt_options' ),
			);

			// Default options
			add_option( 'wc_glt_default', 'grid' );

			// Admin
			add_action( 'woocommerce_settings_image_options_after', array( $this, 'admin_settings' ), 20);
			add_action( 'woocommerce_update_options_catalog', array( $this, 'save_admin_settings' ) );
			add_action( 'woocommerce_update_options_products', array( $this, 'save_admin_settings' ) );
		}

		/*-----------------------------------------------------------------------------------*/
		/* Class Functions */
		/*-----------------------------------------------------------------------------------*/

		function admin_settings() {
			woocommerce_admin_fields( $this->settings );
		}

		function save_admin_settings() {
			woocommerce_update_options( $this->settings );
		}
		

		// Setup
		function setup_gridlist() {
		$GLoption = ''; $GLoption = get_option( 'wc_glt_default' ); if( $GLoption == 'disable' ) return; // do nothing if disabled.
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'setup_scripts_script' ), 20);
				add_action( 'woocommerce_before_shop_loop', array( $this, 'gridlist_toggle_button' ), 30);
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'gridlist_buttonwrap_open' ), 9);
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'gridlist_buttonwrap_close' ), 11);
				//add_action( 'woocommerce_after_subcategory', array( $this, 'gridlist_cat_desc' ) );
			}
		}

		// Scripts
		function setup_scripts_script() {
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				wp_enqueue_script( 'cookie', get_template_directory_uri() . '/includes/js/jquery.cookie.js', array( 'jquery' ),false,true );
				wp_enqueue_script( 'grid-list-scripts', get_template_directory_uri() . '/includes/js/jquery.gridlistview.js', array( 'jquery' ),false,true );
				add_action( 'wp_footer', array(&$this, 'gridlist_set_default_view'), 99 );
			}
		}

		// Toggle button
		function gridlist_toggle_button() {
			?>
				<nav class="gridlist-toggle">
					<a href="#" id="grid" title="<?php _e('Grid view', 'templatation'); ?>"> <span><?php _e('Grid view', 'templatation'); ?></span></a><a href="#" id="list" title="<?php _e('List view', 'templatation'); ?>"> <span><?php _e('List view', 'templatation'); ?></span></a>
				</nav>
			<?php
		}

		// Button wrap
		function gridlist_buttonwrap_open() {
			?>
				<div class="gridlist-buttonwrap">
			<?php
		}
		function gridlist_buttonwrap_close() {
			?>
				</div>
			<?php
		}

		// hr
		function gridlist_hr() {
			?>
				<hr />
			<?php
		}

		function gridlist_set_default_view() {
			$default = get_option( 'wc_glt_default' );
			?>
				<script>
					if (jQuery.cookie('gridcookie') == null) {
						jQuery('ul.products').addClass('<?php echo $default; ?>');
						jQuery('.gridlist-toggle #<?php echo $default; ?>').addClass('active');
					}
				</script>
			<?php
		}

		function gridlist_cat_desc( $category ) {
			global $woocommerce;
			echo '<div itemprop="description">';
				echo $category->description;
			echo '</div>';

		}
	}
	$WC_List_Grid = new WC_List_Grid();
}

/**
 * The WoocommerceCustomProductTabsLite global object
 * @name $woocommerce_product_tabs_lite
 * @global WoocommerceCustomProductTabsLite $GLOBALS['woocommerce_product_tabs_lite']
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com
 * Copyright: (c) 2012-2013 SkyVerge, Inc. (info@skyverge.com)
 */
$GLOBALS['woocommerce_product_tabs_lite'] = new WoocommerceCustomProductTabsLite();

class WoocommerceCustomProductTabsLite {

	private $tab_data = false;

	/** plugin version number */
	const VERSION = "1.2.3";

	/** plugin version name */
	const VERSION_OPTION_NAME = 'woocommerce_custom_product_tabs_lite_db_version';


	/**
	 * Gets things started by adding an action to initialize this plugin once
	 * WooCommerce is known to be active and initialized
	 */
	public function __construct() {
		// Installation
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) $this->install();

		add_action( 'woocommerce_init', array( $this, 'init' ) );
	}


	/**
	 * Init WooCommerce Product Tabs Lite extension once we know WooCommerce is active
	 */
	public function init() {
		// backend stuff
		add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'product_write_panel_tab' ) );
		add_action( 'woocommerce_product_write_panels',     array( $this, 'product_write_panel' ) );
		add_action( 'woocommerce_process_product_meta',     array( $this, 'product_save_data' ), 10, 2 );

		// frontend stuff
		if ( version_compare( WOOCOMMERCE_VERSION, "2.0" ) >= 0 ) {
			// WC >= 2.0
			add_filter( 'woocommerce_product_tabs', array( $this, 'add_custom_product_tabs' ) );
		} else {
			// WC < 2.0
			add_action( 'woocommerce_product_tabs', array( $this, 'custom_product_tabs' ), 25 );
			// in between the attributes and reviews panels
			add_action( 'woocommerce_product_tab_panels', array( $this, 'custom_product_tabs_panel' ), 25 );
		}

		// allow the use of shortcodes within the tab content
		add_filter( 'woocommerce_custom_product_tabs_lite_content', 'do_shortcode' );
	}


	/** Frontend methods ******************************************************/


	/**
	 * Add the custom product tab
	 *
	 * $tabs structure:
	 * Array(
	 *   id => Array(
	 *     'title'    => (string) Tab title,
	 *     'priority' => (string) Tab priority,
	 *     'callback' => (mixed) callback function,
	 *   )
	 * )
	 *
	 * @since 1.2.0
	 * @param array $tabs array representing the product tabs
	 * @return array representing the product tabs
	 */
	public function add_custom_product_tabs( $tabs ) {
		global $product;

		if ( $this->product_has_custom_tabs( $product ) ) {
			foreach ( $this->tab_data as $tab ) {
				$tabs[ $tab['id'] ] = array(
					'title'    => $tab['title'],
					'priority' => 25,
					'callback' => array( $this, 'custom_product_tabs_panel_content' ),
					'content'  => $tab['content'],  // custom field
				);
			}
		}

		return $tabs;
	}


	/**
	 * Write the custom tab on the product view page.  In WooCommerce these are
	 * handled by templates.
	 *
	 * WC < 2.0
	 */
	public function custom_product_tabs() {
		global $product;

		if ( $this->product_has_custom_tabs( $product ) ) {
			foreach ( $this->tab_data as $tab ) {
				echo "<li><a href=\"#{$tab['id']}\">" . __( $tab['title'] ) . "</a></li>";
			}
		}
	}


	/**
	 * Write the custom tab panel on the product view page.  In WooCommerce these
	 * are handled by templates.
	 *
	 * WC < 2.0
	 */
	public function custom_product_tabs_panel() {
		global $product;

		if ( $this->product_has_custom_tabs( $product ) ) {
			foreach ( $this->tab_data as $tab ) {
				echo '<div class="panel" id="' . $tab['id'] . '">';
				$this->custom_product_tabs_panel_content( $tab['id'], $tab );
				echo '</div>';
			}
		}
	}


	/**
	 * Render the custom product tab panel content for the given $tab
	 *
	 * $tab structure:
	 * Array(
	 *   'title'    => (string) Tab title,
	 *   'priority' => (string) Tab priority,
	 *   'callback' => (mixed) callback function,
	 *   'id'       => (int) tab post identifier,
	 *   'content'  => (sring) tab content,
	 * )
	 *
	 * @param string $key tab key
	 * @param array $tab tab data
	 *
	 * @param array $tab the tab
	 */
	public function custom_product_tabs_panel_content( $key, $tab ) {
		echo apply_filters( 'woocommerce_custom_product_tabs_lite_heading', '<h2>' . $tab['title'] . '</h2>', $tab );
		echo apply_filters( 'woocommerce_custom_product_tabs_lite_content', $tab['content'], $tab );
	}


	/** Admin methods ******************************************************/


	/**
	 * Adds a new tab to the Product Data postbox in the admin product interface
	 */
	public function product_write_panel_tab() {
		echo "<li class=\"product_tabs_lite_tab\"><a href=\"#woocommerce_product_tabs_lite\">" . __( 'Custom Tab' ) . "</a></li>";
	}


	/**
	 * Adds the panel to the Product Data postbox in the product interface
	 */
	public function product_write_panel() {
		global $post;
		// the product

		if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
			$style = 'padding:5px 5px 5px 28px;background-repeat:no-repeat;background-position:5px 7px;';
			$active_style = '';
		} else {
			$style = 'padding:9px 9px 9px 34px;line-height:16px;border-bottom:1px solid #d5d5d5;text-shadow:0 1px 1px #fff;color:#555555;background-repeat:no-repeat;background-position:9px 9px;';
			$active_style = '#woocommerce-product-data ul.product_data_tabs li.product_tabs_lite_tab.active a { border-bottom: 1px solid #F8F8F8; }';
		}
		?>
		<style type="text/css">
			#woocommerce-product-data ul.product_data_tabs li.product_tabs_lite_tab a { <?php echo $style; ?> }
			<?php echo $active_style; ?>
		</style>
		<?php

		// pull the custom tab data out of the database
		$tab_data = maybe_unserialize( get_post_meta( $post->ID, 'frs_woo_product_tabs', true ) );

		if ( empty( $tab_data ) ) {
			$tab_data[] = array( 'title' => '', 'content' => '' );
		}

		foreach ( $tab_data as $tab ) {
			// display the custom tab panel
			echo '<div id="woocommerce_product_tabs_lite" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';
			woocommerce_wp_text_input( array( 'id' => '_wc_custom_product_tabs_lite_tab_title', 'label' => __( 'Tab Title' ), 'description' => __( 'Required for tab to be visible' ), 'value' => $tab['title'] ) );
			$this->woocommerce_wp_textarea_input( array( 'id' => '_wc_custom_product_tabs_lite_tab_content', 'label' => __( 'Content' ), 'placeholder' => __( 'HTML and text to display.' ), 'value' => $tab['content'], 'style' => 'width:70%;height:21.5em;' ) );
			echo '</div>';
		}
	}


	/**
	 * Saves the data inputed into the product boxes, as post meta data
	 * identified by the name 'frs_woo_product_tabs'
	 *
	 * @param int $post_id the post (product) identifier
	 * @param stdClass $post the post (product)
	 */
	public function product_save_data( $post_id, $post ) {

		$tab_title = stripslashes( $_POST['_wc_custom_product_tabs_lite_tab_title'] );
		$tab_content = stripslashes( $_POST['_wc_custom_product_tabs_lite_tab_content'] );

		if ( empty( $tab_title ) && empty( $tab_content ) && get_post_meta( $post_id, 'frs_woo_product_tabs', true ) ) {
			// clean up if the custom tabs are removed
			delete_post_meta( $post_id, 'frs_woo_product_tabs' );
		} elseif ( ! empty( $tab_title ) || ! empty( $tab_content ) ) {
			$tab_data = array();

			$tab_id = '';
			if ( $tab_title ) {
				if ( strlen( $tab_title ) != strlen( utf8_encode( $tab_title ) ) ) {
					// can't have titles with utf8 characters as it breaks the tab-switching javascript
					$tab_id = "tab-custom";
				} else {
					// convert the tab title into an id string
					$tab_id = strtolower( $tab_title );
					$tab_id = preg_replace( "/[^\w\s]/", '', $tab_id );
					// remove non-alphas, numbers, underscores or whitespace
					$tab_id = preg_replace( "/_+/", ' ', $tab_id );
					// replace all underscores with single spaces
					$tab_id = preg_replace( "/\s+/", '-', $tab_id );
					// replace all multiple spaces with single dashes
					$tab_id = 'tab-' . $tab_id;
					// prepend with 'tab-' string
				}
			}

			// save the data to the database
			$tab_data[] = array( 'title' => $tab_title, 'id' => $tab_id, 'content' => $tab_content );
			update_post_meta( $post_id, 'frs_woo_product_tabs', $tab_data );
		}
	}


	private function woocommerce_wp_textarea_input( $field ) {
		global $thepostid, $post;

		if ( ! $thepostid ) $thepostid = $post->ID;
		if ( ! isset( $field['placeholder'] ) ) $field['placeholder'] = '';
		if ( ! isset( $field['class'] ) ) $field['class'] = 'short';
		if ( ! isset( $field['value'] ) ) $field['value'] = get_post_meta( $thepostid, $field['id'], true );

		echo '<p class="form-field ' . $field['id'] . '_field"><label style="display:block;" for="' . $field['id'] . '">' . $field['label'] . '</label><textarea class="' . $field['class'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '" rows="2" cols="20"' . (isset( $field['style'] ) ? ' style="' . $field['style'] . '"' : '') . '>' . esc_textarea( $field['value'] ) . '</textarea> ';

		if ( isset( $field['description'] ) && $field['description'] )
			echo '<span class="description">' . $field['description'] . '</span>';

		echo '</p>';
	}


	/** Helper methods ******************************************************/


	/**
	 * Lazy-load the product_tabs meta data, and return true if it exists,
	 * false otherwise
	 *
	 * @return true if there is custom tab data, false otherwise
	 */
	private function product_has_custom_tabs( $product ) {
		if ( false === $this->tab_data ) {
			$this->tab_data = maybe_unserialize( get_post_meta( $product->id, 'frs_woo_product_tabs', true ) );
		}
		// tab must at least have a title to exist
		return ! empty( $this->tab_data ) && ! empty( $this->tab_data[0] ) && ! empty( $this->tab_data[0]['title'] );
	}



	/** Lifecycle methods ******************************************************/


	/**
	 * Run every time.  Used since the activation hook is not executed when updating a plugin
	 */
	private function install() {

		global $wpdb;

		$installed_version = get_option( self::VERSION_OPTION_NAME );

		// installed version lower than plugin version?
		if ( -1 === version_compare( $installed_version, self::VERSION ) ) {
			// new version number
			update_option( self::VERSION_OPTION_NAME, self::VERSION );
		}
	}

}

// overriding woocommerce function to link images too.
if ( ! function_exists( 'woocommerce_subcategory_thumbnail' ) ) {

	/**
	 * Show subcategory thumbnails.
	 *
	 * @access public
	 * @param mixed $category
	 * @subpackage	Loop
	 * @return void
	 */
	function woocommerce_subcategory_thumbnail( $category ) {
		global $woocommerce, $woo_options;

		$small_thumbnail_size  	= apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' );
		$dimensions    			= wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id  			= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
			$image = $image[0];
		} else {
			$image = woocommerce_placeholder_img_src();
		}
			$tt_imagg = "";
			$tt_imagg = '<a href="' .get_term_link( $category->slug, 'product_cat' ). '"><img src="' . $image . '" alt="' . $category->name . '" width="' . $dimensions['width'] . '" height="' . $dimensions['height'] . '" /></a>';

			$settngg = woo_get_dynamic_values( array('link_prod_thumb' => 'false') ) ;
			if( $settngg['link_prod_thumb'] == 'false' ) 
			$tt_imagg = '<img src="' . $image . '" alt="' . $category->name . '" width="' . $dimensions['width'] . '" height="' . $dimensions['height'] . '" />';
 

		if ( $image )
			echo $tt_imagg;
	}
}

// overriding woocommerce function to link images too.
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {

	/**
	 * Get the product thumbnail, or the placeholder if not set.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @param string $size (default: 'shop_catalog')
	 * @param int $placeholder_width (default: 0)
	 * @param int $placeholder_height (default: 0)
	 * @return string
	 */
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post, $woo_options; $prod_imgg = "";

		if ( has_post_thumbnail() )
			$prod_imgg = get_the_post_thumbnail( $post->ID, $size );
		elseif ( woocommerce_placeholder_img_src() )
			$prod_imgg = woocommerce_placeholder_img( $size );
			
		$settngg = woo_get_dynamic_values( array('link_prod_thumb' => 'false') ) ;
		if( $settngg['link_prod_thumb'] == 'true' ) 
		$prod_imgg = '<a href="' .get_permalink(). '">' .$prod_imgg. '</a>';
		
		return $prod_imgg;
	}
}
