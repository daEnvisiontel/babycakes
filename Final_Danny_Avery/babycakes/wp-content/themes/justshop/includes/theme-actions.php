<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$woo_options = get_option( 'woo_options' );

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Theme Setup
- Load style.css in the <head>
- Load responsive <meta> tags in the <head>
- Add custom styling to HEAD
- Add custom typograhpy to HEAD
- Add layout to body_class output
- woo_feedburner_link
- Optionally load top ad section into the header.
- Optionally load top navigation.
- Optionally load custom logo.
- Add custom CSS class to the <body> tag if the lightbox option is enabled.
- Load PrettyPhoto JavaScript and CSS if the lightbox option is enabled.
- Customise the default search form
- Load responsive IE scripts
- Control homepage content

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Theme Setup */
/*-----------------------------------------------------------------------------------*/
/**
 * Theme Setup
 *
 * This is the general theme setup, where we add_theme_support(), create global variables
 * and setup default generic filters and actions to be used across our theme.
 *
 * @package WooFramework
 * @subpackage Logic
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */

if ( ! isset( $content_width ) ) $content_width = 640;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support for post thumbnails.
 *
 * To override templatation_setup() in a child theme, add your own templatation_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for automatic feed links.
 * @uses add_editor_style() To style the visual editor.
 */

add_action( 'after_setup_theme', 'templatation_setup' );

if ( ! function_exists( 'templatation_setup' ) ) {
	function templatation_setup () {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		if ( locate_template( 'editor-style.css' ) != '' ) { add_editor_style(); }

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		if ( is_child_theme() ) {
			$theme_data = wp_get_theme();
			define( 'CHILD_THEME_URL', trailingslashit( get_stylesheet_directory_uri() ) );
			define( 'CHILD_THEME_NAME', $theme_data->name );
		}
		
		// @justshop function hooking.
		add_action( 'woo_content_before', 'templatation_headline_area', 10 ); // adding custom style for headline area
		add_action( 'wp_head', 'justshop_slider_cont', 10 ); // hooking function for slider setup.
		// Register js_contactwidget shortcode
		add_shortcode('contactwidget', 'js_contactwidget');
		add_action( 'wp_head', 'templatation_logo_offset', 10 ); // adding custom style in body tag
		add_action( 'wp_head', 'templatation_header_height', 10 ); // adding custom header height value from theme options
		add_action( 'woo_header_inside', 'templatation_topnav_content', 9 ); // adding custom style in body tag
		add_shortcode('rmc-menu', 'rmc_display_menu'); // Menucard shortcode
		add_shortcode('sidenav', 'templatation_sidenav'); // Sidebar nav
		add_shortcode('sidenav_content', 'templatation_sidenav_content'); // Sidebar content SC
		add_shortcode('TT-teaser', 'templatation_teaser'); // Teaser text.
		add_shortcode('TT-latestproducts', 'templatation_latestproducts'); // Latest products shortcode.
		add_shortcode('TT-featuredproducts', 'templatation_featuredproducts'); // Featured products shortcode.
		//add_shortcode('TT-team', 'templatation_team'); // Team text.
		add_shortcode('TT-headline', 'templatation_headline');
		add_shortcode('TT-productcategories', 'templatation_productcategories'); // extending woocommerce's [product_categories] SC
		add_shortcode('TT-productcategory', 'templatation_productcategory'); // extending woocommerce's [product_category] SC
		add_shortcode('TT-carouselproducts', 'templatation_carouselproducts'); 
		add_shortcode('TT-fa', 'fa_shortcode_icon');
	}
}

/*-----------------------------------------------------------------------------------*/
/* Visual Composer Stuff */
/*-----------------------------------------------------------------------------------*/
define('ULTIMATE_USE_BUILTIN', 'true');
add_action( 'vc_before_init', 'tt_vcSetAsTheme' );
function tt_vcSetAsTheme() {
	if (function_exists('vc_set_as_theme')) vc_set_as_theme();
}

function templatation_vc_row_and_vc_column($class_string, $tag) { return $class_string; }

// Filter to Replace default css class for vc_row shortcode and vc_column
add_filter('vc_shortcodes_css_class', 'templatation_vc_row_and_vc_column', 10, 2);

// Ext VC
if (class_exists('WPBakeryVisualComposerAbstract')) {
	require_once locate_template('/includes/tt-vc-extend/tt-vc-extend.php');
}



/**
 * Set the default Google Fonts used in theme.
 *
 * Used to set the default Google Fonts used in the theme, when Custom Typography is disabled.
 */

global $default_google_fonts;
$default_google_fonts = array( 'Droid Serif', 'Droid Sans' );


/*-----------------------------------------------------------------------------------*/
/* Load style.css in the <head> */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() ) { 
	add_action( 'wp_enqueue_scripts', 'woo_load_frontend_css', 20 );
	add_action( 'wp_enqueue_scripts', 'tt_load_responsive_css', 21 ); // made another call for it because need to load woocommerce.css btwn these two
}

if ( ! function_exists( 'woo_load_frontend_css' ) ) {
	function woo_load_frontend_css () {
		global $woo_options; $tt_layout_style = '';
		wp_register_style( 'theme-stylesheet', get_stylesheet_uri() );

		if (is_rtl()) $tt_layout_style = "layout-rtl"; else $tt_layout_style = "layout";
		wp_register_style( 'woo-layout', get_template_directory_uri() . '/css/'.$tt_layout_style.'.css' );

		wp_enqueue_style( 'theme-stylesheet' );
		wp_enqueue_style( 'woo-layout' );

	} // End woo_load_frontend_css()
}

if ( ! function_exists( 'tt_load_responsive_css' ) ) {
	function tt_load_responsive_css () {
		global $woo_options; $tt_responsive_style = '';
		
		if (is_rtl()) $tt_responsive_style = "responsive-rtl"; else $tt_responsive_style = "responsive";
		wp_register_style( 'temp-responsive', get_template_directory_uri() . '/css/'.$tt_responsive_style.'.css' );

		wp_enqueue_style( 'temp-responsive' );

	} // End woo_load_frontend_css()
}


/*-----------------------------------------------------------------------------------*/
/* Load responsive <meta> tags in the <head> */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_head', 'woo_load_responsive_meta_tags', 10 );

if ( ! function_exists( 'woo_load_responsive_meta_tags' ) ) {
	function woo_load_responsive_meta_tags () {
		$html = '';

		$html .= "\n" . '<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->' . "\n";
		$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />' . "\n";

		/* Remove this if not responsive design */
		$html .= "\n" . '<!--  Mobile viewport scale | Disable user zooming as the layout is optimised -->' . "\n";
		$html .= '<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>' . "\n";

		echo $html;
	} // End woo_load_responsive_meta_tags()
}

// Remove responsive design
if (  ! is_admin() && isset( $woo_options['woo_disable_responsive'] ) && $woo_options['woo_disable_responsive'] == 'true' ) {
	// if user do not want responstive layout , we will remove layout.css and woocommerce.css(removed in theme-woocommerce.php) and load non-responsive.css which has layout.css + woocommerce.css content without media queries.
	remove_action( 'wp_enqueue_scripts', 'woo_load_frontend_css', 20 ); // remove default styles
	remove_action( 'wp_enqueue_scripts', 'tt_load_responsive_css', 21 ); // remove responsive.css styles
	add_action( 'wp_enqueue_scripts', 'tt_remove_responsive_design', 20 ); // add non-responsive styles

	// Remove mobile viewport scale meta tag
	remove_action( 'wp_head', 'woo_load_responsive_meta_tags', 10 );
}

/*-----------------------------------------------------------------------------------*/
/* Function to optionally remove responsive design and load in fallback CSS styling. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'tt_remove_responsive_design' ) ) {
/**
 * Trigger items for removing responsive design.
 * @since  5.0.13
 * @return void
 */
function tt_remove_responsive_design () {
	//remove_action( 'wp_head', 'woo_load_site_width_css', 10 );
	$non_responsive_style = '';
	wp_register_style( 'theme-stylesheet', get_stylesheet_uri() );
	if (is_rtl()) $non_responsive_style = "non-responsive-rtl"; else $non_responsive_style = "non-responsive";
	wp_register_style( 'non-responsive', get_template_directory_uri() . '/css/'.$non_responsive_style.'.css' );

	// Load in CSS file for non-responsive layouts.
	wp_enqueue_style( 'theme-stylesheet' );
	wp_enqueue_style( 'non-responsive' );
	// Load non-responsive site width CSS.
	//add_action( 'wp_print_scripts', 'woo_load_site_width_css_nomedia', 10 );
} // End woo_remove_responsive_design()
}

/*-----------------------------------------------------------------------------------*/
/* Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head', 'woo_custom_styling', 10 ); // Add custom styling to HEAD

if ( ! function_exists( 'woo_custom_styling' ) ) {
	function woo_custom_styling() {
		global $woo_options;
		$output = $tt_hdr_class = $body_image = '';
		// Get options
		$settings = array(
						'body_color' => '',
						'body_img' => '',
						'bodybg_img' => '',
						'body_repeat' => '',
						'body_pos' => '',
						'body_attachment' => '',
						'header_color' => '',
						'header_img' => '',
						'header_repeat' => '',
						'header_pos' => '',
						'link_color' => '',
						'link_hover_color' => '',
						'button_color' => '',
						'top_nav_color' => '',
						'sc_icons_right' => 'false',
						'no_white_bg' => 'false',
						'no_bdcmp_shadow' => 'false'
						);
		$settings = woo_get_dynamic_values( $settings );

		if ( !isset( $woo_options['woo_header_section_layout'] ) || $woo_options['woo_header_section_layout'] == 'layout2' ) {
		$tt_hdr_class = "b";
		}
		elseif( $woo_options['woo_header_section_layout'] == 'layout3' ) $tt_hdr_class = "c";
		elseif( $woo_options['woo_header_section_layout'] == 'layout4' ) $tt_hdr_class = "d";
		elseif( $woo_options['woo_header_section_layout'] == 'layout5' ) $tt_hdr_class = "e";
		elseif( $woo_options['woo_header_section_layout'] == 'layout6' ) $tt_hdr_class = "f";
		elseif( $woo_options['woo_header_section_layout'] == 'layout7' ) $tt_hdr_class = "f";
		//elseif( $woo_options['woo_header_section_layout'] == 'layout8' ) $tt_hdr_class = "e";
		else  $tt_hdr_class = "d";

		// Type Check for Array
		if ( is_array($settings) ) {

			// Add CSS to output
			if ( $settings['body_color'] != '' ) {
				$output .= 'body { background: ' . $settings['body_color'] . ' !important; }' . "\n";
			}

			if ( $settings['bodybg_img'] != '' && $settings['bodybg_img'] != 'default' ) {
				$body_image = get_template_directory_uri() . '/images/bodybg/' . $settings['bodybg_img'] . '.png';
			}

			if ( $settings['body_img'] != '' ) {
				$body_image = $settings['body_img'];  // if custom image is uploaded, get rid of previous.
			}

			if ( $body_image != '' ) {
				if ( is_ssl() ) { $body_image = str_replace( 'http://', 'https://', $body_image ); }
				$output .= 'body { background-image: url( ' . esc_url( $body_image ) . ' ) !important; }' . "\n";
			}

			if ( ( $settings['body_img'] != '' ) && ( $settings['body_repeat'] != '' ) && ( $settings['body_pos'] != '' ) ) {
				$output .= 'body { background-repeat: ' . $settings['body_repeat'] . ' !important; }' . "\n";
			}

			if ( ( $settings['body_img'] != '' ) && ( $settings['body_pos'] != '' ) ) {
				$output .= 'body { background-position: ' . $settings['body_pos'] . ' !important; }' . "\n";
			}

			if ( ( $settings['body_img'] != '' ) && ( $settings['body_attachment'] != '' ) ) {
				$output .= 'body { background-attachment: ' . $settings['body_attachment'] . ' !important; }' . "\n";
			}

			if ( ( $settings['body_img'] != '' ) || ( $settings['body_color'] != '' ) ) {
				$output .= '.widescreen #content:before { background: transparent; }
							.widescreen.page-template-template-contact-php #content { margin-top: 53px;}
							@media
									only screen and (-webkit-min-device-pixel-ratio: 1.5),
									only screen and (   min--moz-device-pixel-ratio: 1.5),
									only screen and (     -o-min-device-pixel-ratio: 3/2),
									only screen and (        min-device-pixel-ratio: 1.5),
									only screen and (                min-resolution: 192dpi),
									only screen and (                min-resolution: 2dppx) { background-size: 20px 20px; }'
				           . "\n";
			}

			if ( $settings['header_color'] != '' ) {
				$output .= '#header.'.$tt_hdr_class.' { background: ' . $settings['header_color'] . ' !important; }' . "\n";
			}

			if ( $settings['header_img'] != '' ) {
				$header_image = $settings['header_img'];
				if ( is_ssl() ) { $header_image = str_replace( 'http://', 'https://', $header_image ); }
				$output .= '#header.'.$tt_hdr_class.' { background-image: url( ' . esc_url( $header_image ) . ' ) !important; }' . "\n";
			}

			if ( ( $settings['header_img'] != '' ) && ( $settings['header_repeat'] != '' ) && ( $settings['header_pos'] != '' ) ) {
				$output .= '#header.'.$tt_hdr_class.' { background-repeat: ' . $settings['header_repeat'] . ' !important; }' . "\n";
			}

			if ( ( $settings['header_img'] != '' ) && ( $settings['header_pos'] != '' ) ) {
				$output .= '#header.'.$tt_hdr_class.' { background-position: ' . $settings['header_pos'] . ' !important; }' . "\n";
			}

			if ( $settings['link_color'] != '' ) {
				$output .= 'a { color: ' . $settings['link_color'] . ' !important; }' . "\n";
			}

			if ( $settings['sc_icons_right'] == 'true' ) {
				$output .= '#tools ul { float: right; }' . "\n";
			}

			if ( $settings['no_bdcmp_shadow'] == 'true' ) {
				$output .= '#headline { background: none repeat scroll 0 0 transparent;}' . "\n";
			}
/*
			if ( $settings['no_white_bg'] == 'true' ) {
				$output .= '.widescreen #content:before, #content { background: transparent; }' . "\n";
			}
*/
			if ( $settings['link_hover_color'] != '' ) {
				$output .= 'a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover { color: ' . $settings['link_hover_color'] . ' !important; }' . "\n";
			}

			if ( $settings['button_color'] != '' ) {
				$output .= 'a.button, a.comment-reply-link, #commentform #submit, #contact-page .submit { background: ' . $settings['button_color'] . ' !important; border-color: ' . $settings['button_color'] . ' !important; }' . "\n";
				$output .= 'a.button:hover, a.button.hover, a.button.active, a.comment-reply-link:hover, #commentform #submit:hover, #contact-page .submit:hover { background: ' . $settings['button_color'] . ' !important; opacity: 0.9; }' . "\n";
			}
			if ( $settings['top_nav_color'] != '' ) {
				$output .= '#tools, #tools > .fit-a { background: ' . $settings['top_nav_color'] . ' }' . "\n";
			}

		} // End If Statement

		// Output styles
		if ( isset( $output ) && $output != '' ) {
			$output = strip_tags( $output );
			$output = "\n" . "<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}

	} // End woo_custom_styling()
}

/*-----------------------------------------------------------------------------------*/
/* Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head','woo_custom_typography', 10 ); // Add custom typography to HEAD

if ( ! function_exists( 'woo_custom_typography' ) ) {
	function woo_custom_typography() {

		// Get options
		global $woo_options;

		// Reset
		$output = '';
		$default_google_font = false;

		// Type Check for Array
		if ( is_array($woo_options) ) {

			// Add Text title and tagline if text title option is enabled
			if ( isset( $woo_options['woo_texttitle'] ) && $woo_options['woo_texttitle'] == 'true' ) {

				if ( $woo_options['woo_font_site_title'] )
					$output .= '#header .site-title a {'.woo_generate_font_css($woo_options['woo_font_site_title']).'}' . "\n";
				if ( $woo_options['woo_tagline'] == "true" AND $woo_options['woo_font_tagline'] )
					$output .= '#header .site-description {'.woo_generate_font_css($woo_options[ 'woo_font_tagline']).'}' . "\n";
			}

			if ( isset( $woo_options['woo_typography'] ) && $woo_options['woo_typography'] == 'true' ) {

				if ( isset( $woo_options['woo_font_body'] ) && $woo_options['woo_font_body'] )
					$output .= 'body { '.woo_generate_font_css($woo_options['woo_font_body'], '1.5').' }' . "\n";
					
				/* specific to justshop only */
				if ( isset( $woo_options['woo_font_body_alt'] ) && $woo_options['woo_font_body_alt'] )
					$output .= '.cols-b, .double-a p, .double-b p, .breadcrumbs-wrap .breadcrumb .breadcrumb-trail, .jssidebar .widget_tag_cloud .tagcloud a ,
#sidebar .widget_tag_cloud .tagcloud a, #header #navigation ul#main-nav > li.megamenu > ul.sub-menu > li > a, 
  #header #navigation ul#main-nav > li.megamenu.parent:hover > ul.sub-menu > li > a, article.hentry, article.hentry p, .search #main article header p, ul.products li.product .prod-excerpt, ul.products li.product .excerpt, .single-product .product { '.woo_generate_font_css($woo_options['woo_font_body_alt'], '1.5').' }' . "\n";

				if ( isset( $woo_options['woo_font_nav'] ) && $woo_options['woo_font_nav'] )
					$output .= '.nav a { '.woo_generate_font_css($woo_options['woo_font_nav'], '1.4').' }' . "\n";

				if ( isset( $woo_options['woo_font_page_title'] ) && $woo_options['woo_font_page_title'] )
					$output .= '.page header h1 { '.woo_generate_font_css($woo_options[ 'woo_font_page_title' ]).' }' . "\n";

				if ( isset( $woo_options['woo_font_post_title'] ) && $woo_options['woo_font_post_title'] )
					$output .= '.post header h1, .post header h1 a:link, .post header h1 a:visited { '.woo_generate_font_css($woo_options[ 'woo_font_post_title' ]).' }' . "\n";

/*				if ( isset( $woo_options['woo_font_post_meta'] ) && $woo_options['woo_font_post_meta'] )
					$output .= '.post-meta { '.woo_generate_font_css($woo_options[ 'woo_font_post_meta' ]).' }' . "\n";
*/
				if ( isset( $woo_options['woo_font_post_entry'] ) && $woo_options['woo_font_post_entry'] )
					$output .= '.entry, .entry p { '.woo_generate_font_css($woo_options[ 'woo_font_post_entry' ], '1.5').' } h1, h2, h3, h4, h5, h6 { font-family: '.stripslashes($woo_options[ 'woo_font_page_title' ]['face']).', Tahoma, sans-serif; }'  . "\n";

				if ( isset( $woo_options['woo_font_widget_titles'] ) && $woo_options['woo_font_widget_titles'] )
					$output .= '.widget h3 { '.woo_generate_font_css($woo_options[ 'woo_font_widget_titles' ]).' }'  . "\n";

				// Component titles
				if ( isset( $woo_options['woo_font_component_titles'] ) && $woo_options['woo_font_component_titles'] )
					$output .= '.component h2.component-title { '.woo_generate_font_css($woo_options[ 'woo_font_component_titles' ]).' }'  . "\n";

			// Add default typography Google Font
			} else {

				// Load default Google Fonts
				global $default_google_fonts;
				if ( is_array( $default_google_fonts) and count( $default_google_fonts ) > 0 ) :

					$count = 0;
					foreach ( $default_google_fonts as $font ) {
						$count++;
						$woo_options[ 'woo_default_google_font_'.$count ] = array( 'face' => $font );
					}
					$default_google_font = true;

				endif;

			}

		} // End If Statement

		// Output styles
		if (isset($output) && $output != '') {

			// Load Google Fonts stylesheet in HEAD
			if (function_exists( 'woo_google_webfonts')) woo_google_webfonts();

			$output = "\n" . "<!-- Woo Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;

		// Check if default google font is set and load Google Fonts stylesheet in HEAD
		} elseif ( $default_google_font ) {

			// Enable Google Fonts stylesheet in HEAD
			if (function_exists( 'woo_google_webfonts')) woo_google_webfonts();

		}

	} // End woo_custom_typography()
}

// Returns proper font css output
if (!function_exists( 'woo_generate_font_css')) {
	function woo_generate_font_css($option, $em = '1') {

		// Test if font-face is a Google font
		global $google_fonts;

		// Type Check for Array
		if ( is_array($google_fonts) ) {

			foreach ( $google_fonts as $google_font ) {

				// Add single quotation marks to font name and default arial sans-serif ending
				if ( $option[ 'face' ] == $google_font[ 'name' ] )
					$option[ 'face' ] = "'" . $option[ 'face' ] . "', arial, sans-serif";

			} // END foreach

		} // End If Statement

		if ( (!@$option["style"] && !@$option["size"] && !@$option["unit"] && !@$option["color"]) || ($option["size"] == 'default') )
			return 'font-family: '.stripslashes($option["face"]).';';
		else
			return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}

// Output stylesheet and custom.css after custom styling
remove_action( 'wp_head', 'templatation_wp_head' );
add_action( 'woo_head', 'templatation_wp_head' );


/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class','woo_layout_body_class', 10 );		// Add layout to body_class output

if ( ! function_exists( 'woo_layout_body_class' ) ) {
	function woo_layout_body_class( $classes ) {

		global $woo_options;

		$layout = 'two-col-left';

		if ( isset( $woo_options['woo_site_layout'] ) && ( $woo_options['woo_site_layout'] != '' ) ) {
			$layout = $woo_options['woo_site_layout'];
		}

		// Set main layout on post or page
		if ( is_singular() ) {
			global $post;
			$single = get_post_meta($post->ID, '_layout', true);
			if ( $single != "" AND $single != "layout-default" )
				$layout = $single;
		}

		// Add layout to $woo_options array for use in theme
		$woo_options['woo_layout'] = $layout;

		// Add classes to body_class() output
		$classes[] = $layout;
		return $classes;

	} // End woo_layout_body_class()
}


/*-----------------------------------------------------------------------------------*/
/* woo_feedburner_link() */
/*-----------------------------------------------------------------------------------*/
/**
 * woo_feedburner_link()
 *
 * Replace the default RSS feed link with the Feedburner URL, if one
 * has been provided by the user.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'feed_link', 'woo_feedburner_link', 10 );

function woo_feedburner_link ( $output, $feed = null ) {

	global $woo_options;

	$default = get_default_feed();

	if ( ! $feed ) $feed = $default;

	if ( isset($woo_options[ 'woo_feed_url']) && $woo_options[ 'woo_feed_url' ] && ( $feed == $default ) && ( ! stristr( $output, 'comments' ) ) ) $output = esc_url( $woo_options[ 'woo_feed_url' ] );

	return $output;

} // End woo_feedburner_link()

/*-----------------------------------------------------------------------------------*/
/* Optionally load custom logo. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_logo' ) ) {
function woo_logo () {
	global $woo_options;
	$settingsl = array(
					'retina_favicon' => 'false'
				);
	$settingsl = woo_get_dynamic_values( $settingsl );
	if ( isset( $woo_options['woo_texttitle'] ) && $woo_options['woo_texttitle'] == 'true' ) return; // Get out if we're not displaying the logo.

	$logo = esc_url( get_template_directory_uri() . '/images/logo.png' );
	if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
	if ( is_ssl() ) { $logo = str_replace( 'http://', 'https://', $logo ); }
?>

	<a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
	 <?php if ( 'false' == $settingsl['retina_favicon'] ) { ?>
		<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	 <?php } else { ?>

		<img src="<?php echo esc_url( $logo ); ?>" style="width:<?php echo $woo_options["woo_retina_logo_w"]; ?>px;height:<?php echo $woo_options["woo_retina_logo_h"]; ?>px;" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	 <?php } ?>
	</a>
<?php
} // End woo_logo()
}

add_action( 'woo_header_inside', 'woo_logo', 10 );

/*-----------------------------------------------------------------------------------*/
/* Add search box on right side of the header.                                       */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_header_inside', 'tt_hdr_search', 11 );
if ( ! function_exists( 'tt_hdr_search' ) ) {
	function tt_hdr_search() {
		global $woo_options;
		$settingsl5 = array(
			'header_section_layout' => 'layout2',
			'enable_hdr_search'     => 'false',
		);
		$settingsl5 = woo_get_dynamic_values( $settingsl5 );
		if ( ( $settingsl5['enable_hdr_search'] == 'true' ) && ( $settingsl5['header_section_layout'] == 'layout6' || $settingsl5['header_section_layout'] == 'layout7' ) ) {
			if ( is_woocommerce_activated() ) {
				get_product_search_form();
			} else {
				get_search_form();
			}
		}
	} // End tt_hdr_search()
}

/*-----------------------------------------------------------------------------------*/
/* Add custom CSS class to the <body> tag if the lightbox option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'woo_add_lightbox_body_class', 10 );

function woo_add_lightbox_body_class ( $classes ) {
	global $woo_options;

	if ( isset( $woo_options['woo_enable_lightbox'] ) && $woo_options['woo_enable_lightbox'] == 'true' ) {
		$classes[] = 'has-lightbox';
	}

	return $classes;
} // End woo_add_lightbox_body_class()

/*-----------------------------------------------------------------------------------*/
/* Load PrettyPhoto JavaScript and CSS if the lightbox option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_action( 'templatation_add_javascript', 'woo_load_prettyphoto', 10 );
add_action( 'templatation_add_css', 'woo_load_prettyphoto', 10 );

function woo_load_prettyphoto () {
	global $woo_options;

	if ( (! isset( $woo_options['woo_enable_lightbox'] ) || $woo_options['woo_enable_lightbox'] == 'false')  || ( is_singular() && get_post_type() == 'product' ) ) { return; }

	$filter = current_filter();

	switch ( $filter ) {
		case 'templatation_add_javascript':
			wp_enqueue_script( 'enable-lightbox' );
		break;

		case 'templatation_add_css':
			wp_enqueue_style( 'prettyPhoto' );
		break;

		default:
		break;
	}
} // End woo_load_prettyphoto()

/*-----------------------------------------------------------------------------------*/
/* Customise the default search form */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_customise_search_form' ) ) {
function woo_customise_search_form ( $html ) {
  // Add the "search_main" and "fix" classes to the wrapping DIV tag.
  $html = str_replace( '<form', '<div class="search_main fix"><form', $html );
  // Add the "searchform" class to the form.
  $html = str_replace( ' method=', ' class="searchform" method=', $html );
  // Add the placeholder attribute and CSS classes to the input field.
  $html = str_replace( ' name="s"', ' name="s" class="field s" placeholder="' . esc_attr( __( 'Search...', 'templatation' ) ) . '"', $html );
  // Wrap the end of the form in a closing DIV tag.
  $html = str_replace( '</form>', '</form></div>', $html );
  // Add the "search-submit" class to the button.
  $html = str_replace( ' id="searchsubmit"', ' class="search-submit" id="searchsubmit"', $html );

  return $html;
} // End woo_customise_search_form()
}

add_filter( 'get_search_form', 'woo_customise_search_form' );

/*-----------------------------------------------------------------------------------*/
/* Load responsive IE scripts */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_footer', 'woo_load_responsive_IE_footer', 10 );

if ( ! function_exists( 'woo_load_responsive_IE_footer' ) ) {
	function woo_load_responsive_IE_footer () {
		$html = '';
		echo '<!--[if lt IE 9]>'. "\n";
		echo '<script src="' . get_template_directory_uri() . '/includes/js/respond.js"></script>'. "\n";
		echo '<![endif]-->'. "\n";

		echo $html;
	} // End ()
}

/*-----------------------------------------------------------------------------------*/
/* Customise the display of the testmonials */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_customise_testimonials_template' ) ) {
function woo_customise_testimonials_template ( $template ) {
	return '<div class="%%CLASS%% quote-%%ID%%"><blockquote class="testimonials-text">%%AUTHOR%% %%AVATAR%% <p>%%TEXT%%</p></blockquote><div class="fix"></div></div>';
} // End woo_customise_testimonials_template()
}

add_filter( 'templatation_testimonials_item_template', 'woo_customise_testimonials_template' );

/*-----------------------------------------------------------------------------------*/
/* Control homepage content */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_customise_homepage_query' ) ) {
/**
 * Modify the homepage query, if set to display blog posts, based on the theme options.
 * @since  1.0.0
 * @param  object $q The query object.
 * @return object    The modified query object.
 */
function woo_customise_homepage_query ( $q ) {
	if ( $q->is_admin ) return $q; // We don't want to act in the admin.

	if ( $q->is_home && $q->is_main_query() ) {
		$settings = woo_get_dynamic_values( array( 'homepage_content_type' => 'posts', 'homepage_number_of_posts' => get_option( 'posts_per_page' ), 'homepage_posts_category' => '' ) );
		if ( 'posts' != $settings['homepage_content_type'] ) return $q; // If we're not displaying blog posts, don't modify the query.

		$q->set( 'posts_per_page', intval( $settings['homepage_number_of_posts'] ) );

		if ( 0 < intval( $settings['homepage_posts_category'] ) ) {
			$q->set( 'cat', intval( $settings['homepage_posts_category'] ) );
		}

		$q->parse_query();
	}

	return $q;
} // End woo_customise_homepage_query()
}

//add_filter( 'pre_get_posts', 'woo_customise_homepage_query' );

// fire customizer if enabled from themeoptions/styling
if ( ( isset( $woo_options['woo_tt_live_cust'] ) && $woo_options['woo_tt_live_cust'] == 'true') ) {
	// Adding live customizer.
	if ( function_exists('templatation_customize_register') ) add_action( 'customize_register', 'templatation_customize_register' );
	if ( function_exists('tt_customizer_css') ) add_action( 'wp_head', 'tt_customizer_css' );
}

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>