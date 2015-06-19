<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! is_admin() ) { add_action( 'wp_enqueue_scripts', 'templatation_add_javascript' ); }

if ( ! function_exists( 'templatation_add_javascript' ) ) {
	function templatation_add_javascript() {
		global $woo_options;

		wp_register_script( 'prettyPhoto', get_template_directory_uri() . '/includes/js/jquery.prettyPhoto.js', array( 'jquery' ),false,true );
		wp_register_script( 'enable-lightbox', get_template_directory_uri() . '/includes/js/enable-lightbox.js', array( 'jquery', 'prettyPhoto' ),false,true );
		wp_register_script( 'google-maps', 'http://maps.google.com/maps/api/js?sensor=false','',false,true );
		wp_register_script( 'google-maps-markers', get_template_directory_uri() . '/includes/js/markers.js','',false,true );
		wp_register_script( 'infinite-scroll', get_template_directory_uri() . '/includes/js/jquery.infinitescroll.min.js', array( 'jquery' ),false,true );
		wp_register_script( 'masonry', get_template_directory_uri() . '/includes/js/jquery.masonry.min.js', array( 'jquery' ),false,true );
		wp_register_script( 'number-polyfill', get_template_directory_uri() . '/includes/js/number-polyfill.min.js', array( 'jquery' ),false,true );
		wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/includes/js/waypoints.min.js', array( 'jquery' ),false,true );

		wp_enqueue_script( 'third party', get_template_directory_uri() . '/includes/js/third-party.js', array( 'jquery' ),false,true );
		wp_register_script( 'portfolio', get_template_directory_uri() . '/includes/js/portfolio.js', array( 'jquery', 'prettyPhoto' ),false,true );
		wp_enqueue_script( 'tiptip', get_template_directory_uri() . '/includes/js/jquery.tiptip.min.js', array( 'jquery' ),false,true );

		// Load Google Script on Contact Form Page Template
		if ( is_page_template( 'template-contact.php' ) ) {
			wp_enqueue_script( 'google-maps','','','',true );
			wp_enqueue_script( 'google-maps-markers','','','',true );
		} // End If Statement
		if ( version_compare( WOOCOMMERCE_VERSION, "2.3" ) || version_compare( WOOCOMMERCE_VERSION, "2.3-rc1" ) >= 0 ) {
			wp_enqueue_script( 'number-polyfill','','','',true );
		}

		// Load infinite scroll on shop page / product cats
		if ( is_woocommerce_activated() ) {
			if ( !isset($woo_options['woocommerce_archives_infinite_scroll'] )  ) $woo_options['woocommerce_archives_infinite_scroll'] = 'true'; 
			if ( ( $woo_options['woocommerce_archives_infinite_scroll'] == 'true' ) && ( is_shop() || is_product_category() ) ) {
				wp_enqueue_script( 'infinite-scroll','','','',true );
			}
		}

		// Load Portfolio JS for homepage, page template, single page, post type archive, and taxonomy archive
		if ( is_page_template( 'template-home.php' ) || is_page_template( 'template-portfolio.php' ) || is_page_template( 'template-portfolio-one.php' ) || is_page_template( 'template-portfolio-hex.php' ) || is_page_template( 'template-portfolio-two.php' ) || is_page_template( 'template-portfolio-three.php' ) ||  is_page_template( 'template-portfolio-four.php' ) || ( is_singular() && get_post_type() == 'portfolio' ) || is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {			
			wp_enqueue_script( 'portfolio','','','',true );
		}		
		
		// Load Masonry on the blog grid layout
		if ( is_page_template( 'template-blog-masonry.php' ) ) {
			wp_enqueue_script( 'masonry','','','',true );
			wp_enqueue_script( 'isotope','','','',true );
			add_action( 'wp_head', 'woo_fire_masonry' );
		}

		do_action( 'templatation_add_javascript' );
		wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery' ),false,true );
	} // End templatation_add_javascript()
}

add_action ( 'templatation_add_javascript', 'tt_add_bxslider' );
if ( ! function_exists( 'tt_add_bxslider' ) ) {
	function tt_add_bxslider() {
		$tt_bxslider = $child_inc_file = '';
		if ( is_rtl() ) {
			$tt_bxslider = "bxslider-rtl";
		} else {
			$tt_bxslider = "bxslider";
		}

		if ( is_child_theme() ) { $child_inc_file = get_stylesheet_directory(). '/includes/js/'. $tt_bxslider .'.min.js'; }
		if ( file_exists( $child_inc_file ) ) { // if using supplied child theme, fetch that js file instead.
			wp_register_script( 'tt-bxslider', get_stylesheet_directory_uri() . '/includes/js/' . $tt_bxslider . '.min.js', array( 'jquery' ),false,true );
		}
		else
		wp_register_script( 'tt-bxslider', get_template_directory_uri() . '/includes/js/' . $tt_bxslider . '.min.js', array( 'jquery' ),false,true );
		wp_enqueue_script( 'tt-bxslider','','','',true );
	} // End tt_add_bxslider()
}


if ( ! is_admin() ) { add_action( 'wp_print_styles', 'templatation_add_css' ); }

if ( ! function_exists( 'templatation_add_css' ) ) {
	function templatation_add_css () {
		wp_register_style( 'prettyPhoto', get_template_directory_uri().'/includes/css/prettyPhoto.css' );
		/*if ( ! wp_style_is( 'js_composer_front', 'enqueued' ) ) {*/
			wp_enqueue_style( 'css3animations', get_template_directory_uri().'/includes/css/css3_animations.css' );
		/*}*/
		do_action( 'templatation_add_css' );
	} // End templatation_add_css()
}

if ( ! function_exists( 'woo_fire_masonry' ) ) {
	function woo_fire_masonry () { ?>
		<script>
		jQuery(document).ready(function($){
			if (jQuery(window).width() > 767) {
				jQuery('.blog-masonry .masonryblock').isotope({
					itemSelector : '.post'
				});
			}
		});
		</script>
	<?php }
}

// Add an HTML5 Shim

add_action( 'wp_head', 'html5_shim' );

if ( ! function_exists( 'html5_shim' ) ) {
	function html5_shim() {
		?>
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
		<?php
	} // End html5_shim()
}

?>