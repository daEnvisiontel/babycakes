<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if (!function_exists( 'woo_options')) {
function woo_options() {

// THEME VARIABLES
$themename = 'Theme Options';
$themeslug = 'justshop';

// STANDARD VARIABLES. DO NOT TOUCH!
$shortname = 'woo';
$manualurl = 'http://www.templatation.com/support/theme-documentation/'.$themeslug.'/';

//Stylesheets Reader
$alt_stylesheet_path = get_template_directory() . '/styles/';
$alt_stylesheets = array();
if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, '.css') !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }
    }
}

// Setup an array of category terms for a dropdown.
$args = array( 'echo' => 0, 'hierarchical' => 1, 'taxonomy' => 'category' );
$cats_dropdown = wp_dropdown_categories( $args );
$cats = array();

// Quick string hack to make sure we get the pages with the indents.
$cats_dropdown = str_replace( "<select name='cat' id='cat' class='postform' >", '', $cats_dropdown );
$cats_dropdown = str_replace( '</select>', '', $cats_dropdown );
$cats_split = explode( '</option>', $cats_dropdown );

$cats[] = __( 'Select a Category:', 'templatation' );

foreach ( $cats_split as $k => $v ) {
    $id = '';
    // Get the ID value.
    preg_match( '/value="(.*?)"/i', $v, $matches );

    if ( isset( $matches[1] ) ) {
        $id = $matches[1];
        $cats[$id] = trim( strip_tags( $v ) );
    }
}

$woo_categories = $cats;

// Setup an array of post_tag terms for a dropdown.
$args = array( 'echo' => 0, 'hierarchical' => 1, 'taxonomy' => 'post_tag' );
$cats_dropdown = wp_dropdown_categories( $args );
$cats = array();


// Quick string hack to make sure we get the pages with the indents.
$cats_dropdown = str_replace( "<select name='cat' id='cat' class='postform' >", '', $cats_dropdown );
$cats_dropdown = str_replace( '</select>', '', $cats_dropdown );
$cats_split = explode( '</option>', $cats_dropdown );

$cats[] = __( 'Select a Post Tag:', 'templatation' );

foreach ( $cats_split as $k => $v ) {
    $id = '';
    // Get the ID value.
    preg_match( '/value="(.*?)"/i', $v, $matches );

    if ( isset( $matches[1] ) ) {
        $id = $matches[1];
        $cats[$id] = trim( strip_tags( $v ) );
    }
}

$woo_post_tags = $cats;

// Setup an array of numbers.
$woo_numbers = array();
for ( $i = 1; $i <= 20; $i++ ) {
    $woo_numbers[$i] = $i;
}


// Setup an array of portfolio gallery terms for a dropdown.
$args = array( 'echo' => 0, 'hierarchical' => 1, 'taxonomy' => 'portfolio-gallery' );
$cats_dropdown = wp_dropdown_categories( $args );
$cats = array();

// Quick string hack to make sure we get the pages with the indents.
$cats_dropdown = str_replace( "<select name='cat' id='cat' class='postform' >", '', $cats_dropdown );
$cats_dropdown = str_replace( '</select>', '', $cats_dropdown );
$cats_split = explode( '</option>', $cats_dropdown );

$cats[] = __( 'Select a Portfolio Gallery:', 'templatation' );

foreach ( $cats_split as $k => $v ) {   
    $id = '';   
    // Get the ID value.
    preg_match( '/value="(.*?)"/i', $v, $matches );
    
    if ( isset( $matches[1] ) ) {   
        $id = $matches[1];
        $cats[$id] = trim( strip_tags( $v ) );
    }
}

$portfolio_groups = $cats;

// Setup an array of pages for a dropdown.
$args = array( 'echo' => 0 );
$pages_dropdown = wp_dropdown_pages( $args );
$pages = array();

// Quick string hack to make sure we get the pages with the indents.
$pages_dropdown = str_replace( '<select name="page_id" id="page_id">', '', $pages_dropdown );
$pages_dropdown = str_replace( '</select>', '', $pages_dropdown );
$pages_split = explode( '</option>', $pages_dropdown );

$pages[] = __( 'Select a Page:', 'templatation' );

foreach ( $pages_split as $k => $v ) {
    $id = '';
    // Get the ID value.
    preg_match( '/value="(.*?)"/i', $v, $matches );

    if ( isset( $matches[1] ) ) {
        $id = $matches[1];
        $pages[$id] = trim( strip_tags( $v ) );
    }
}

$woo_pages = $pages;

// THIS IS THE DIFFERENT FIELDS
$options = array();
$other_entries = array( '0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19' );

/* General */

$options[] = array( 'name' => __( 'General Settings', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'general' );

$settings13 = ''; $settings13 = woo_get_dynamic_values( array( 'rmve_import' => 'false' ) );
if ( $settings13['rmve_import'] == 'false' ) {
	$options[] = array(
		'name' => __( 'Demo Import', 'templatation' ),
		'type' => 'subheading'
	);

	$options[] = array(
		'name' => __( 'Welcome to JustShop ThemeOptions', 'templatation' ),
		'desc' => '',
		'id'   => $shortname . '_demo_import',
		'std'  => __( 'Thanks for purchasing and activating JustShop, lets proceed with Demo Importer...', 'templatation' ),
		'type' => 'info',
	);

	$options[] = array(
		'name' => __( 'Is this fresh wordpress installation? (Please read)', 'templatation' ),
		'desc' => __( 'Running demo importer can change current website massively, its not recommended to run demo importer if its not a totally new wordpress installation. Please only proceed if its blank website or there is no data that you care about. Check this to proceed with demo importer, or check box below to hide this page.<br>There are 3 easy step to import one of the demos. (You will be required to navigate away from this page to complete those steps, to come back here, just click Theme Options on left menu.) Check this box to see steps.', 'templatation' ),
		'id'   => $shortname . '_import_cnf',
		'std'  => 'false',
		'class' => 'collapsed',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Step 1 (install recommended/bundled plugins)', 'templatation' ),
		'desc' => '',
		'id'   => $shortname . '_demo_import1',
		'std'  => sprintf( __( 'Please %1$s (or go to Appearance->Install plugins) and install and activate all plugins listed. If you face problems with this step, please contact support.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/themes.php?page=install-required-plugins">' . __( 'click here', 'templatation' ) . '</a>' ),
		'type' => 'info',
		'class' => 'hidden',
	);

	$options[] = array(
		'name' => __( 'Step 2 (import content.xml)', 'templatation' ),
		'desc' => '',
		'id'   => $shortname . '_demo_import2',
		'std'  => sprintf( __( 'Please %1$s (or go to Tools->Import) & click WordPress (install importer plugin if prompted) and import Your-themeforest-download/resources/import-data/content.xml file( Note that content is same in all demos, only images differs). (Check box to import attachments during process). This process can take upto 3 mins.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/import.php">' . __( 'click here', 'templatation' ) . '</a>' ),
		'type' => 'info',
		'class' => 'hidden',
	);

	$options[] = array(
		'name' => __( 'Step 3 (import configurations)', 'templatation' ),
		'desc' => '',
		'id'   => $shortname . '_demo_import3',
		'std'  => sprintf( __( 'Please %1$s (or go to Appearance->Import Demo Data) & Follow on-page instructions please.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/themes.php?page=radium_demo_installer">' . __( 'click here', 'templatation' ) . '</a>' ),
		'type' => 'info',
		'class' => 'hidden',
	);

	$options[] = array(
		'name' => __( 'Important Final Step', 'templatation' ),
		'desc' => '',
		'id'   => $shortname . '_demo_importi',
		'std'  => sprintf( __( 'Please click Save All Changes button on bottom right. Now check your front website. If all looks good, please check below checkbox and save to hide this demo import page and disable import scripts. Also go to Users on left bar and change password/email of imported users. Its very important for security reasons.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/themes.php?page=radium_demo_installer">' . __( 'go here', 'templatation' ) . '</a>' ),
		'type' => 'info',
		'class' => 'hidden last',
	);

	$options[] = array(
		'name' => __( 'Remove this welcome page/demo import section(Important)', 'templatation' ),
		'desc' => __( 'Once you are done importing(or if you are only updating theme), you might want to remove this whole page. Check this box to remove this. Make sure to confirm importing success before removing this page. You will need to contact support in case you want to undo this action.', 'templatation' ),
		'id'   => $shortname . '_rmve_import',
		'std'  => 'false',
		'type' => 'checkbox'
	);
}
$options[] = array( 'name' => __( 'Quick Start', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Theme Stylesheet/Theme Color', 'templatation' ),
    				'desc' => __( 'Select your themes alternative color scheme.', 'templatation' ),
    				'id' => $shortname . '_alt_stylesheet',
    				'std' => 'default.css',
    				'type' => 'select',
    				'options' => $alt_stylesheets );

$options[] = array( 'name' => __( 'Custom Logo', 'templatation' ),
    				'desc' => __( 'Upload a logo for your theme, or specify an image URL directly. If you are worried about retina friendliness you can upload bigger logo and set desired dimension in retina ready graphics option below.', 'templatation' ),
    				'id' => $shortname . '_logo',
    				'std' => '',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Horizontal Logo Offset', 'templatation' ),
    				'desc' => __( 'If the logo you uploaded is misplaced, you can move logo horizontally by providing the values below. If you put 10 in left offset, logo will move to right side by 10px, -10 in left offset will move the logo to left by 10px. Enter values without px eg: 10, 20, -15 etc.', 'templatation' ),
    				'id' => $shortname . '_logo_left_offset',
    				'std' => 0,
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Vertical Logo Offset', 'templatation' ),
    				'desc' => __( 'If the logo you uploaded is misplaced, you can move logo vertically by providing the values below. If you put 10 in top offset, logo will move to top side by 10px, -10 in top offset will move the logo down 10px. Enter values without px eg: 10, 20, -15 etc.', 'templatation' ),
    				'id' => $shortname . '_logo_top_offset',
    				'std' => 0,
    				'type' => 'text' );

// changed below to remove php Notice array to string conversion @ pk v1.0
/*$options[] = array( 'name' => __( 'Logo Offset', 'templatation' ),
    				'desc' => __( 'If the logo you uploaded is misplaced, you can move logo vertically or horizontally by providing the values below. If you put 10 in left offset, logo will move to right side by 10px, -10 in left offset will move the logo to left by 10px. Enter values without px eg: 10, 20, -15 etc.', 'templatation' ),
    				'id' => $shortname . '_logo_offset',
    				'std' => '',
    				'type' => array(
    					array(  'id' => $shortname . '_logo_left_offset',
    						'type' => 'text',
    						'std' => 0,
    						'meta' => __( 'Left Offset', 'templatation' ) ),
    					array(  'id' => $shortname . '_logo_top_offset',
    						'type' => 'text',
    						'std' => 0,
    						'meta' => __( 'Top Offset', 'templatation' ) )
    				) );

*/

$options[] = array( 'name' => __( 'Text Title', 'templatation' ),
    				'desc' => sprintf( __( 'Enable text-based Site Title and Tagline. Setup title & tagline in %1$s.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/options-general.php">' . __( 'General Settings', 'templatation' ) . '</a>' ),
    				'id' => $shortname . '_texttitle',
    				'std' => 'false',
    				'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Site Title', 'templatation' ),
    				'desc' => __( 'Change the site title typography.', 'templatation' ),
    				'id' => $shortname . '_font_site_title',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'Helvetica', 'style' => '', 'color' => '#333333' ),
    				'class' => 'hidden',
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Site Description', 'templatation' ),
    				'desc' => __( 'Enable the site description/tagline under site title.', 'templatation' ),
    				'id' => $shortname . '_tagline',
    				'class' => 'hidden',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Site Description', 'templatation' ),
    				'desc' => __( 'Change the site description typography.', 'templatation' ),
    				'id' => $shortname . '_font_tagline',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'Helvetica', 'style' => '', 'color' => '#999999' ),
    				'class' => 'hidden last',
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Custom Favicon', 'templatation' ),
    				'desc' => sprintf( __( 'Upload a 16px x 16px %1$s that will represent your website\'s favicon.', 'templatation' ), '<a href="http://www.faviconr.com/">'.__( 'ico image', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_custom_favicon',
    				'std' => '',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Retina Ready Graphics ?', 'templatation' ),
    				'desc' => __( 'If you worry about Retina ready devices, please check this and you will find options to upload favicon for high resolution devices. If you are not sure what this means leave it unchecked, its not that important.', 'templatation' ),
    				'id' => $shortname . '_retina_favicon',
    				'std' => 'false',
    				'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Retina Logo Width', 'templatation' ),
    				'desc' => __( 'Enter the width for your retina logo. If you want logo to be retina ready , please upload double size version of logo above. And enter the width you want it to appear. Normally it will be half of actual retina logo image width. Enter values without px eg: 180, 200 etc.', 'templatation' ),
    				'id' => $shortname . '_retina_logo_w',
    				'class' => 'hidden',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Retina Logo Height', 'templatation' ),
    				'desc' => __( 'Enter the height for your retina logo. If you want logo to be retina ready , please upload double size version of logo above. And enter the height you want it to appear. Normally it will be half of actual retina logo image height. Enter values without px eg: 50, 70 etc.', 'templatation' ),
    				'id' => $shortname . '_retina_logo_h',
    				'class' => 'hidden',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Apple iPhone Icon', 'templatation' ),
    				'desc' => __( 'Icon for Apple iPhone (57px x 57px)', 'templatation' ),
    				'id' => $shortname . '_fvcn_57x57',
    				'std' => '',
    				'class' => 'hidden',
    				'type' => 'upload' );
					
$options[] = array( 'name' => __( 'Apple iPhone Retina ', 'templatation' ),
    				'desc' => __( 'Icon for Apple iPhone Retina Version (114px x 114px) and other high-resolution Retina display.', 'templatation' ),
    				'id' => $shortname . '_fvcn_114x114',
    				'std' => '',
    				'class' => 'hidden',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Apple iPad Icon', 'templatation' ),
    				'desc' => __( 'Icon for Apple iPad (72px x 72px)', 'templatation' ),
    				'id' => $shortname . '_fvcn_72x72',
    				'std' => '',
    				'class' => 'hidden',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Apple iPad Retina Icon', 'templatation' ),
    				'desc' => __( 'Icon for Apple iPad Retina Version (144px x 144px) and other high-resolution Retina display.', 'templatation' ),
    				'id' => $shortname . '_fvcn_144x144',
    				'std' => '',
    				'class' => 'hidden last',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Enable Widescreen Mode ?', 'templatation' ),
    				'desc' => __( 'If you check this , the main container with white background will flow to full availble width horizontally(by default its 950px fixed width). Note that content will still be inside 950px width, only background will be full width. If you enable it, also see bottom option of Styling -> Body Background. If you are not sure, try it :).', 'templatation' ),
    				'id' => $shortname . '_widescreen',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Enable Sticky Menu ?', 'templatation' ),
    				'desc' => __( 'If you check this , navigation bar sticks to top even if you scroll down on any page.', 'templatation' ),
    				'id' => $shortname . '_sticky_menu',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Google analytics', 'templatation' ),
    				'desc' => __( 'Paste your Google Analytics Tracking ID here. This will be added into the footer template of your theme. eg : UA-00000000-0 . NOTE: ONLY ENTER TRACKING ID, NOT FULL CODE.', 'templatation' ),
    				'id' => $shortname . '_google_analytics',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Disable Default Animations ?', 'templatation' ),
    				'desc' => __( 'Check this to disable Default animations. Note that Animations triggered by Page builder will not be turned off, they can be managed from Page Builder itself. Recommended: Unchecked.', 'templatation' ),
    				'id' => $shortname . '_jsanim_no',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Disable Loading icon ?', 'templatation' ),
    				'desc' => __( 'When page loads, theme displays a page loading icon. Please Check this box to disable this.', 'templatation' ),
    				'id' => $shortname . '_disable_loadicon',
    				'std' => 'false',
    				'type' => 'checkbox' );

	$options[] = array( 'name' => __( 'Disable Responsiveness ?', 'templatation' ),
    				'desc' => __( 'The theme adjust itself automatically to the width of the device/browser. Do you want to disable it and open desktop version on all devices instead ? Check to disable.', 'templatation' ),
    				'id' => $shortname . '_disable_responsive',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Subscription Settings', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'RSS URL', 'templatation' ),
    				'desc' => __( 'Enter your preferred RSS URL. (Feedburner or other)', 'templatation' ),
    				'id' => $shortname . '_feed_url',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'E-Mail Subscription URL', 'templatation' ),
    				'desc' => __( 'Enter your preferred E-mail subscription URL. (Feedburner or other)', 'templatation' ),
    				'id' => $shortname . '_subscribe_email',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Display Options', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Custom CSS', 'templatation' ),
    				'desc' => __( 'Quickly add some CSS to your theme by adding it to this block.', 'templatation' ),
    				'id' => $shortname . '_custom_css',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Post/Page Comments', 'templatation' ),
    				'desc' => __( 'Select if you want to enable/disable comments on posts and/or pages.', 'templatation' ),
    				'id' => $shortname . '_comments',
    				'std' => 'both',
    				'type' => 'select2',
    				'options' => array( 'post' => __( 'Posts Only', 'templatation' ), 'page' => __( 'Pages Only', 'templatation' ), 'both' => __( 'Pages / Posts', 'templatation' ), 'none' => __( 'None', 'templatation' ) ) );

$options[] = array( 'name' => __( 'Post Content', 'templatation' ),
    				'desc' => __( 'Select if you want to show the full content or the excerpt on posts.', 'templatation' ),
    				'id' => $shortname . '_post_content',
    				'type' => 'select2',
    				'options' => array( 'excerpt' => __( 'The Excerpt', 'templatation' ), 'content' => __( 'Full Content', 'templatation' ) ) );

$options[] = array( 'name' => __( 'Post Author Box', 'templatation' ),
    				'desc' => sprintf( __( 'This will enable the post author box on the single posts page. Edit description in %1$s.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/profile.php">' . __( 'Profile', 'templatation' ) . '</a>' ),
    				'id' => $shortname . '_post_author',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Display Breadcrumbs', 'templatation' ),
    				'desc' => __( 'Display dynamic breadcrumbs on each page of your website.', 'templatation' ),
    				'id' => $shortname . '_breadcrumbs_show',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Display Pagination', 'templatation' ),
    				'desc' => __( 'Display pagination on the blog.', 'templatation' ),
    				'id' => $shortname . '_pagenav_show',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Pagination Style', 'templatation' ),
    				'desc' => __( 'Select the style of pagination you would like to use on the blog.', 'templatation' ),
    				'id' => $shortname . '_pagination_type',
    				'type' => 'select2',
    				'options' => array( 'paginated_links' => __( 'Numbers', 'templatation' ), 'simple' => __( 'Next/Previous', 'templatation' ) ) );

					

/* Styling */

$options[] = array( 'name' => __( 'Styling', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'styling' );

$options[] = array( 'name' => __( 'Body Background', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Enable live customizer ?', 'templatation' ),
    				'desc' => sprintf( __( 'Enable live customizer to change colors of the theme with live preview. Not recommended unless you really need it. Once checked and saved using above button, %1$s. (Yes you can disable it later, so nothing wrong in giving it a try.)', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/customize.php">' . __( 'Access by clicking here', 'templatation' ) . '</a>' ),
    				'id' => $shortname . '_tt_live_cust',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Body Background Color', 'templatation' ),
    				'desc' => __( 'Pick a custom color for background color of the theme e.g. #697e09', 'templatation' ),
    				'id' => $shortname . '_body_color',
    				'std' => '',
    				'type' => 'color' );

$bodyimgurl =  get_template_directory_uri() . '/images/bodybg/admin/';
$options[] = array( 'name' => __( 'Choose Body background image', 'templatation' ),
    				'desc' => __( 'Select builtin body background images. ( Find more cool BGs on http://backgroundlabs.com included those used in demo ).', 'templatation' ),
    				'id' => $shortname . '_bodybg_img',
    				'std' => '',
    				'type' => 'images',
    				'options' => array(
    					'default' => $bodyimgurl . 'off.png',
    					'giftly' => $bodyimgurl . 'giftly.png',
    					'restaurant_icons' => $bodyimgurl . 'restaurant_icons.png',
    					'retina_wood' => $bodyimgurl . 'retina_wood.png',
    					'shattered' => $bodyimgurl . 'shattered.png',
    					'white_wall_hash' => $bodyimgurl . 'white_wall_hash.png',
    					'grunge_wall' => $bodyimgurl . 'grunge_wall.png',
    					'04' => $bodyimgurl . '04.png',
    					'06' => $bodyimgurl . '06.png',
    					'08' => $bodyimgurl . '08.png',
    					'009' => $bodyimgurl . '009.png',
    				));

$options[] = array( 'name' => __( 'Upload Body background image', 'templatation' ),
    				'desc' => __( 'Upload an image for the theme\'s background', 'templatation' ),
    				'id' => $shortname . '_body_img',
    				'std' => '',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Background image repeat', 'templatation' ),
    				'desc' => __( 'Select how you would like to repeat the background-image', 'templatation' ),
    				'id' => $shortname . '_body_repeat',
    				'std' => 'no-repeat',
    				'type' => 'select',
    				'options' => array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) );

$options[] = array( 'name' => __( 'Background image position', 'templatation' ),
    				'desc' => __( 'Select how you would like to position the background', 'templatation' ),
    				'id' => $shortname . '_body_pos',
    				'std' => 'top',
    				'type' => 'select',
    				'options' => array( 'top left', 'top center', 'top right', 'center left', 'center center', 'center right', 'bottom left', 'bottom center', 'bottom right' ) );

$options[] = array( 'name' => __( 'Background Attachment', 'templatation' ),
    				'desc' => __( 'Select whether the background should be fixed or move when the user scrolls', 'templatation' ),
    				'id' => $shortname.'_body_attachment',
    				'std' => 'scroll',
    				'type' => 'select',
    				'options' => array( 'scroll', 'fixed' ) );
/*
$options[] = array( 'name' => __( 'Apply body color/image everywhere?', 'templatation' ) ,
                'desc' => __( 'By default, content area has white background for consistency. If you prefer, check this box to override default white background with above setting globally.', 'templatation' ) ,
                'id' => $shortname . '_no_white_bg',
                'std' => 'false',
                'type' => 'checkbox' );
*/
$options[] = array( 'name' => __( 'Disable content area Shadow?', 'templatation' ) ,
                'desc' => __( 'Incase you want single tone body and content area backgrounds, check to disable shadows that appear on start and end of content area on widescreen mode. Recommended: unchecked.', 'templatation' ) ,
                'id' => $shortname . '_no_cont_shadow',
                'std' => 'false',
                'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Disable headline background Shadow?', 'templatation' ) ,
                'desc' => __( 'Again, incase you want single tone body and content area backgrounds, check to disable breadcrumb shadow. Recommended: unchecked.', 'templatation' ) ,
                'id' => $shortname . '_no_bdcmp_shadow',
                'std' => 'false',
                'type' => 'checkbox' );


$options[] = array( 'name' => __( 'Header Background', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Header Settings', 'templatation' ),
	                'desc' => '',
	                'id' => $shortname . '_js_hdr_settings',
	                'std' => __( 'You can customize header area here. Note : This customization will not reflect on homepage of layout 4, because slider overlaps header area in layout4 homepage.', 'templatation' ),
	                'type' => 'info' );


$options[] = array( 'name' => __( 'Header Background Color', 'templatation' ),
    				'desc' => __( 'Pick a custom color for header background. e.g. #697e09', 'templatation' ),
    				'id' => $shortname . '_header_color',
    				'std' => '',
    				'type' => 'color' );

$options[] = array( 'name' => __( 'Header background image', 'templatation' ),
    				'desc' => __( 'Upload an image for the Header background.', 'templatation' ),
    				'id' => $shortname . '_header_img',
    				'std' => '',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Header image repeat', 'templatation' ),
    				'desc' => __( 'Select how you would like to repeat the Header background-image.', 'templatation' ),
    				'id' => $shortname . '_header_repeat',
    				'std' => 'no-repeat',
    				'type' => 'select',
    				'options' => array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) );

$options[] = array( 'name' => __( 'Header image position', 'templatation' ),
    				'desc' => __( 'Select how you would like to position the Header background.', 'templatation' ),
    				'id' => $shortname . '_header_pos',
    				'std' => 'top',
    				'type' => 'select',
    				'options' => array( 'top left', 'top center', 'top right', 'center left', 'center center', 'center right', 'bottom left', 'bottom center', 'bottom right' ) );


$options[] = array( 'name' => __( 'Header Background Attachment', 'templatation' ),
    				'desc' => __( 'Select whether the background should be fixed or move when the user scrolls.', 'templatation' ),
    				'id' => $shortname.'_body_attachment',
    				'std' => 'scroll',
    				'type' => 'select',
    				'options' => array( 'scroll', 'fixed' ) );


$options[] = array( 'name' => __( 'Links', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Link Color', 'templatation' ),
    				'desc' => __( 'Pick a custom color for links or add a hex color code e.g. #697e09', 'templatation' ),
    				'id' => $shortname . '_link_color',
    				'std' => '',
    				'type' => 'color' );

$options[] = array( 'name' => __( 'Link Hover Color', 'templatation' ),
    				'desc' => __( 'Pick a custom color for links hover or add a hex color code e.g. #697e09', 'templatation' ),
    				'id' => $shortname . '_link_hover_color',
    				'std' => '',
    				'type' => 'color' );

$options[] = array( 'name' => __( 'Button Color', 'templatation' ),
    				'desc' => __( 'Pick a custom color for buttons or add a hex color code e.g. #697e09', 'templatation' ),
    				'id' => $shortname . '_button_color',
    				'std' => '',
    				'type' => 'color' );

/* Typography */

$options[] = array( 'name' => __( 'Typography', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'typography' );

$options[] = array( 'name' => __( 'Typography Settings', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_js_typography_notice',
    				'std' => __( 'Theme is already designed very well, but in-case you want to change some fonts , you can do it here. Please be careful with this as it might messup the fonts. You can however disable below changes completely by first checkbox anytime you want. Note: If for certain element you only want to change font family but let the font size, color etc come from main style.css file, then select Default.px. If you are unsure, you can skip this section totally. :)', 'templatation' ),
    				'type' => 'info' );

$options[] = array( 'name' => __( 'Enable Custom Typography', 'templatation' ) ,
    				'desc' => __( 'Enable the use of custom typography for your site. Custom styling will be output in your sites HEAD.', 'templatation' ) ,
    				'id' => $shortname . '_typography',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'General Typography', 'templatation' ) ,
    				'desc' => __( 'Change the general font. Default font-size: 13px.', 'templatation' ) ,
    				'id' => $shortname . '_font_body',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'Roboto', 'style' => '', 'color' => '' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'General Typography Alternate', 'templatation' ) ,
    				'desc' => __( 'Change the alternate general font. Used on some places to differ the element from normal element eg buttons. Default font-size: 13px.', 'templatation' ) ,
    				'id' => $shortname . '_font_body_alt',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'tahoma', 'style' => '', 'color' => '' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Navigation', 'templatation' ) ,
    				'desc' => __( 'Change the navigation font. Default font-size: 13px.', 'templatation' ),
    				'id' => $shortname . '_font_nav',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'Philosopher', 'style' => '', 'color' => '' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Page Title / H tags', 'templatation' ) ,
    				'desc' => __( 'Change the page title. H tags will fetch font-family from here, font size is managed by css file for heading tags. Default font-size: 13px.', 'templatation' ) ,
    				'id' => $shortname . '_font_page_title',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'Philosopher', 'style' => 'bold/italic', 'color' => '' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Post Title', 'templatation' ) ,
    				'desc' => __( 'Change the post title. Default font-size: 13px.', 'templatation' ) ,
    				'id' => $shortname . '_font_post_title',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'Philosopher', 'style' => 'bold/italic', 'color' => '' ),
    				'type' => 'typography' );

/*$options[] = array( 'name' => __( 'Post Meta', 'templatation' ),
    				'desc' => __( 'Change the post meta.', 'templatation' ) ,
    				'id' => $shortname . '_font_post_meta',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'BergamoStd', 'style' => '', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );
*/
$options[] = array( 'name' => __( 'Post Entry', 'templatation' ) ,
    				'desc' => __( 'Change the post entry. Default font-size: 13px.', 'templatation' ) ,
    				'id' => $shortname . '_font_post_entry',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'tahoma', 'style' => '', 'color' => '' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Widget Titles', 'templatation' ) ,
    				'desc' => __( 'Change the widget titles. Default font-size: 13px.', 'templatation' ) ,
    				'id' => $shortname . '_font_widget_titles',
    				'std' => array( 'size' => 'Default', 'unit' => 'px', 'face' => 'Philosopher', 'style' => 'bold', 'color' => '' ),
    				'type' => 'typography' );

/* Layout */

$options[] = array( 'name' => __( 'Layout (Sidebar / Header)', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'layout' );

$url =  get_template_directory_uri() . '/functions/images/';
$options[] = array( 'name' => __( 'Main Layout', 'templatation' ),
    				'desc' => __( 'Select which layout you want for your site.', 'templatation' ),
    				'id' => $shortname . '_site_layout',
    				'std' => 'layout-right-content',
    				'type' => 'images',
    				'options' => array(
    					'layout-left-content' => $url . '2cl.png',
    					'layout-right-content' => $url . '2cr.png' )
    				);

$options[] = array( 'name' => __( 'Header layout', 'templatation' ),
                    'desc' => __( 'Select which header layout you wish for header/menu items area. Please check demo website for example of layouts or you can try changing it. You can always revert back the change easily.', 'templatation' ),
                    'id' => $shortname . '_header_section_layout',
                    'std' => 'layout2',
                    'type' => 'select2',
					'options' => array( 'layout2' => __( 'Header Layout 2', 'templatation' ),
						 'layout3' => __( 'Header Layout 3', 'templatation' ),
						 'layout4' => __( 'Header Layout 4', 'templatation' ),
						 'layout5' => __( 'Header Layout 5', 'templatation' ),
						 'layout6' => __( 'Header Layout 6', 'templatation' ),
						 'layout7' => __( 'Header Layout 7', 'templatation' )
						 //'layout8' => __( 'Header Layout 8', 'templatation' )
						 )
					 );

$options[] = array( 'name' => __( 'Transparent Layout4 header', 'templatation' ),
                    'desc' => __( 'Enable transparent header and menus. This only applies if you are using layout 4.', 'templatation' ),
                    'id' => $shortname . '_trans_lay4',
                    'std' => 'false',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Header Height', 'templatation' ),
    				'desc' => __( 'If you want to set your own header height, please enter value. IMPORTANT NOTE: Please leave it blank if you are not sure what it means. The value you enter here over-rides all other header height values. It only increases header height, you will still need to take care of placement of logo(by logo offset in general settings) and navigation(manually). (Enter 0 or leave blank to return to default). Enter value without px, eg : 100, 150 etc. px automatically added by script. ', 'templatation' ),
    				'id' => $shortname . '_header_height',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Show Header Search', 'templatation' ),
                    'desc' => __( 'Enable/Disable the search bar on right side of Header. It does not fit to all header layouts. It will only show up if you are using layout 6 or layout7.', 'templatation' ),
                    'id' => $shortname . '_enable_hdr_search',
                    'std' => 'false',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Toppest Bar Settings', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_js_topbar_notice',
    				'std' => __( 'Below settings are for the top most navigation bar which is optional. It adds some usual user interface items. There is limited space on this bar , so be careful in choosing what part you really need. You can try it out and disable things later if bar goes out of space.', 'templatation' ),
    				'type' => 'info' );


$options[] = array( 'name' => __( 'Enable Top Nav Bar', 'templatation' ),
                    'desc' => __( 'Enable/Disable the Top most nav bar globally.', 'templatation' ),
                    'id' => $shortname . '_enable_topbar',
                    'std' => 'true',
    				'class' => 'collapsed',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Show Hide/Show icon', 'templatation' ),
                    'desc' => __( 'This icon appears on right corner of the top bar and it enables user to show/hide the bar when clicked. If disabled, top bar will always show.', 'templatation' ),
                    'id' => $shortname . '_enable_showhide',
                    'std' => 'true',
    				'class' => 'hidden',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Top Nav Bar Color', 'templatation' ),
    				'desc' => __( 'Pick a custom color for Top nav bar or add a hex color code e.g. #697e09', 'templatation' ),
    				'id' => $shortname . '_top_nav_color',
    				'std' => '',
    				'type' => 'color' );

	$options[] = array( 'name' => __( 'Enable Teaser Text', 'templatation' ),
                    'desc' => __( 'Teaser text is short sentence that you want to highlight. It comes on left most area of top navigation. If you turn it off below 2 settings makes no difference. Default is checked.', 'templatation' ),
                    'id' => $shortname . '_enable_ttext',
                    'std' => 'true',
    				'class' => 'hidden',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Teaser Text Icon', 'templatation' ),
                    'desc' => __( 'This icon appears on left side of teaser text. Recommended size : 21 x 21 px.', 'templatation' ),
                    'id' => $shortname . '_ttext_icon',
                    'std' => '',
    				'class' => 'hidden',
                    'type' => 'upload' );

$options[] = array( 'name' => __( 'Teaser Text', 'templatation' ),
                    'desc' => __( 'Teaser text is short sentence that you want to highlight. eg : Our helpline number : xyz. This text appears on left most side of top bar.', 'templatation' ),
                    'id' => $shortname . '_ttext_text',
                    'std' => '',
    				'class' => 'hidden',
                    'type' => 'text' );

$options[] = array( 'name' => __( 'Welcome Text Icon', 'templatation' ),
                    'desc' => __( 'This icon appears on left side of Login/Register or Welcome. Recommended size : 21 x 21 px.', 'templatation' ),
                    'id' => $shortname . '_welcome_text_icon',
                    'std' => '',
    				'class' => 'hidden',
                    'type' => 'upload' );

$options[] = array( 'name' => __( 'Show Social icons', 'templatation' ),
                    'desc' => __( 'Show social icons on top bar, you can control value of these from Subscribe -> Connect below.', 'templatation' ),
                    'id' => $shortname . '_enable_top_social',
                    'std' => 'true',
    				'class' => 'hidden',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Show Language Dropdown', 'templatation' ),
                    'desc' => __( 'Show lang dropdown, at this time it supports WPML only. It will only work if you have WPML plugin installed and langauges setup in it.', 'templatation' ),
                    'id' => $shortname . '_enable_lang_dropdown',
                    'std' => 'false',
    				'class' => 'hidden',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Show Search', 'templatation' ),
                    'desc' => __( 'Enable/Disable the search bar on right side of Top nav bar.', 'templatation' ),
                    'id' => $shortname . '_enable_top_search',
                    'std' => 'true',
    				'class' => 'hidden',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Stick social icons to right', 'templatation' ),
                'desc' => __( 'If you disable search box above , you might want to move social icons to right to avoid blank space on right side of the bar. Leave it unchecked if you are not sure.', 'templatation' ),
                'id' => $shortname . '_sc_icons_right',
                'std' => 'false',
                'class' => 'hidden',
                'type' => 'checkbox' );


/* Slider and Header */


$options[] = array( 'name' => __( 'Slider & Headline', 'templatation' ),
                    'icon' => 'slider',
                    'type' => 'heading' );

$options[] = array( 'name' => __( 'Slider Settings', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'Slider & Headline Settings', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_js_slider_notice',
    				'std' => __( 'The slider area (or HERO area) is the main highlighted area in the header. You can place slider or any html in this. You can also define separate slider area for each page/post/product from bottom of its editor page.', 'templatation' ),
    				'type' => 'info' );


$options[] = array( 'name' => __( 'Enable Slider', 'templatation' ),
                    'desc' => __( 'Enable/Disable the slider globally.', 'templatation' ),
                    'id' => $shortname . '_enable_slider',
                    'std' => 'false',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Slider area content', 'templatation' ),
    				'desc' => __( 'Please enter the content you want to show on slider area. Usually you will enter shortcode given by revolution slider here. eg: [rev_slider home]. However, (for advanced users/developers) if you want, you can use any kind of data here and it will be shown on the slider section (Hero Area) instead.', 'templatation' ),
    				'id' => $shortname . '_slider_area_content',
    				'std' => '<p class="no-slider">No slides has been setup, Please Create a slider using the revolution slider packaged with the theme and enter the provided shortcode in Theme-options -> Slider & Header -> Slider Settings -> Slider area content.</p>',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Show above slider on whole website ?', 'templatation' ),
                    'desc' => __( 'Do you want to show above slider/Hero area code only on homepage, or on whole website instead. By default this appears only on page with page-template set as "Custom Homepage". You can define separate slider/Hero area for particular page from bottom of page editor. Check to show on whole website.', 'templatation' ),
                    'id' => $shortname . '_slider_whole_site',
                    'std' => 'false',
                    'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Headline Options', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Headline Setup', 'templatation' ),
                    'desc' => '',
                    'id' => $shortname . '_headline_notice',
                    'std' => __( 'Headline is the area after header block and before content block. Below are the defaults for Headline area for whole website, these settings can be override by particular page or post editor page settings. ( Feel free to play with it , you can always revert back. ) ' , 'templatation' ),
                    'type' => 'info' );

$options[] = array( 'name' => __( 'Show Headline Section Globally ?', 'templatation' ),
    				'desc' => __( 'Default: checked, Uncheck to disable. If globally disabled the headline area will not appear anywhere. The settings below and the Headline and heading message setting in single post pages will not make any appearance. If disabled here, it will not show in any case, but if enabled here, you can disable it on single page basis on single post pages too.', 'templatation' ),
    				'id' => $shortname . '_show_headline_global',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Enable Default Headline title and message ?', 'templatation' ),
    				'desc' => __( 'If you enable it you will see boxes to enter default title and message for Headline area. These Title and Message will be show on whole website where specific Title and Message is not defined from single page editor. If you disable it , Breadcrumb will be shown on headline area instead. ( False is recommended if you are not sure about it.) .', 'templatation' ),
    				'id' => $shortname . '_enable_default_headline',
    				'std' => 'true',
					'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Default Headline Title', 'templatation' ),
    				'desc' => __( 'If you enable Headline and enter title here, it will be shown if no other title is specified in single post/pages.', 'templatation' ),
    				'id' => $shortname . '_headline_default_title',
    				'std' => 'Taste our yummy Cakes',
					'class' => 'hidden',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Default Headline Message', 'templatation' ),
    				'desc' => __( 'If you enable Headline and enter Message here, it will be shown if no other Message is specified in single post/pages.', 'templatation' ),
    				'id' => $shortname . '_headline_default_message',
    				'std' => 'All kind of cakes and pastries ready within minutes. Try once. Call xxx-x-xxx Today !',
					'class' => 'hidden last',
    				'type' => 'textarea' );


/* Homepage */

$options[] = array( 'name' => __( 'Homepage', 'templatation' ),
                    'icon' => 'homepage',
                    'type' => 'heading' );

$options[] = array( 'name' => __( 'Homepage Setup', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'Homepage Setup', 'templatation' ),
                    'desc' => '',
                    'id' => $shortname . '_homepage_notice',
                    'std' => sprintf( __( 'Note: Below settings only works if you create a page with page template "Custom Homepage" and set it as front page from Settings->Reading. You can optionally customise the homepage product categories section by adding widgets to the "Homepage" widgetized area on the "%sWidgets%s" screen. If you add widgets to "Homepage" widgetized area, it will override "product categories" section of homepage.', 'templatation' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>' ) ,
                    'type' => 'info' );

if ( is_woocommerce_activated() ) {
$options[] = array( 'name' => __( 'Enable Product Categories', 'templatation' ),
                    'desc' => __( 'Display product categories on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_enable_product_categories',
                    'std' => 'true',
                    'type' => 'checkbox');

}

/*$options[] = array( 'name' => __( 'Enable Recent Products', 'templatation' ),
                    'desc' => __( 'Display recent products on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_enable_recent_products',
                    'std' => 'true',
                    'type' => 'checkbox');
*/

$options[] = array( 'name' => __( 'Enable About / Testimonial section', 'templatation' ),
                    'desc' => __( 'Display About / Testimonial section.', 'templatation' ),
                    'id' => $shortname . '_homepage_about_home_section',
                    'std' => 'true',
                    'type' => 'checkbox');

if ( is_woocommerce_activated() ) {
$options[] = array( 'name' => __( 'Enable Featured Products', 'templatation' ),
                    'desc' => __( 'Display featured products on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_enable_featured_products',
                    'std' => 'true',
                    'type' => 'checkbox');

}
$options[] = array( 'name' => __( 'Enable Content Area', 'templatation' ),
                    'desc' => __( 'Display the content area with either page content or a list of blog posts.', 'templatation' ),
                    'id' => $shortname . '_homepage_enable_content',
                    'std' => 'false',
                    'type' => 'checkbox');

if ( is_woocommerce_activated() ) {
$options[] = array( 'name' => __( 'Product Categories', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'Number of Product Categories', 'templatation' ),
                    'desc' => __( 'Select the number of product categories to display on the homepage. Multiples of 3 recommended, eg: 3,6,9', 'templatation' ),
                    'id' => $shortname . '_homepage_product_categories_limit',
                    'std' => '4',
                    'type' => 'select2',
                    'options' => $woo_numbers
                  );

$options[] = array( 'name' => __( 'Featured Products', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'Title', 'templatation' ),
                    'desc' => __( 'Enter the title to display above the Featured products on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_featured_products_title',
                    'std' => '',
                    'type' => 'text' );

$options[] = array( 'name' => __( 'Number of Products', 'templatation' ),
                    'desc' => __( 'Select the number of featured products to display on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_featured_products_limit',
                    'std' => '4',
                    'type' => 'select2',
                    'options' => $woo_numbers
                  );

/*$options[] = array( 'name' => __( 'Recent Products', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'Title', 'templatation' ),
                    'desc' => __( 'Enter the title to display above the recent products on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_recent_products_title',
                    'std' => '',
                    'type' => 'text' );

$options[] = array( 'name' => __( 'Number of Products', 'templatation' ),
                    'desc' => __( 'Select the number of recent products to display on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_recent_products_limit',
                    'std' => '4',
                    'type' => 'select2',
                    'options' => $woo_numbers
                  );
*/
}

$options[] = array( 'name' => __( 'About us / Testimonials', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'About Title', 'templatation' ),
                    'desc' => __( 'Enter the title to display in the about us section on homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_about_title',
                    'std' => sprintf( __( 'About %s', 'templatation' ), get_bloginfo( 'name' ) ),
                    'type' => 'text' );

$options[] = array( 'name' => __( 'About box content', 'templatation' ),
    				'desc' => __( 'Enter some information about your business here.', 'templatation' ),
    				'id' => $shortname . '_homepage_about_message',
    				'std' => 'About some information about your business here. You can edit this content from Theme-options -> Homepage settings. <br>Sed diam non ummy nibh in euismod tincidunt ut liber tempor laoreet. Lorem ipsum dolor sit amet, consect etuer adipisc in elit, samo em ipsum dolor sit amet, con in hendrerit in vulputate velit at sectetuer adipisc in elit. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea comm odo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. ittera gothica, quam nunc putamus parumclar am, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima.',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Show Testimonials', 'templatation' ),
                        'desc' => __( 'Display the Testimonials on the right side of this, if disabled about section flows to full container.', 'templatation' ),
                        'id' => $shortname.'_homepage_show_testi',
                        'std' => 'true',
                        'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Testimonials Title', 'templatation' ),
                    'desc' => __( 'Enter the title to display above the testimonials on the homepage.', 'templatation' ),
                    'id' => $shortname . '_homepage_testimonials_area_title',
                    'std' => sprintf( __( 'What people say about %s', 'templatation' ), get_bloginfo( 'name' ) ),
                    'type' => 'text' );

$options[] = array( 'name' => __( 'Content Area', 'templatation' ),
                    'type' => 'subheading' );

$options[] = array( 'name' => __( 'Content Type', 'templatation' ),
                    'desc' => __( 'Determine whether to display the content of a specified page, or your recent blog posts.', 'templatation' ),
                    'id' => $shortname . '_homepage_content_type',
                    'std' => 'posts',
                    'type' => 'select2',
                    'options' => array( 'posts' => __( 'Blog Posts', 'templatation' ), 'page' => __( 'Page Content', 'templatation' ) )
                  );

$options[] = array( 'name' => __( 'Page Content', 'templatation' ),
                    'desc' => __( 'Select the page to display content from if the homepage content area is enabled.', 'templatation' ),
                    'id' => $shortname . '_homepage_page_id',
                    'std' => '',
                    'type' => 'select2',
                    'options' => $woo_pages
                  );

$options[] = array( 'name' => __( 'Number of Blog Posts', 'templatation' ),
                    'desc' => __( 'Select the number of posts to display if the content type is set to "Blog Posts".', 'templatation' ),
                    'id' => $shortname . '_homepage_number_of_posts',
                    'std' => '5',
                    'type' => 'select2',
                    'options' => $woo_numbers
                  );

$options[] = array( 'name' => __( 'Posts Category', 'templatation' ),
                    'desc' => __( 'Optionally select a category of posts to display if the content type is set to "Blog Posts".', 'templatation' ),
                    'id' => $shortname . '_homepage_posts_category',
                    'std' => '',
                    'type' => 'select2',
                    'options' => $woo_categories
                    );

$options[] = array( 'name' => __( 'Show Sidebar', 'templatation' ),
                        'desc' => __( 'Display the sidebar on the homepage.', 'templatation' ),
                        'id' => $shortname.'_homepage_posts_sidebar',
                        'std' => 'true',
                        'type' => 'checkbox' );


$options[] = array( 'name' => __( 'Category Exclude - Homepage', 'templatation' ),
    				'desc' => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your homepage (eg: uncategorized).', 'templatation' ),
    				'id' => $shortname . '_exclude_cats_home',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Category Exclude - Blog Page Template', 'templatation' ),
    				'desc' => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your \'Blog\' page template (eg: uncategorized).', 'templatation' ),
    				'id' => $shortname . '_exclude_cats_blog',
    				'std' => '',
    				'type' => 'text' );

/* Portfolio */

$options[] = array( 'name' => __( 'Portfolio/Cakes', 'templatation' ),
                    'icon' => 'portfolio',
                    'type' => 'heading');

$options[] = array( 'name' => __( 'Portfolio Page Title', 'templatation' ),
                    'desc' => __('The title of your Portfolio Page.', 'templatation'),
                    'id' => $shortname.'_portfolio_title',
                    'std' => __( 'Portfolio', 'templatation' ),
                    'type' => 'text');

$options[] = array( 'name' => __( 'Portfolio Items URL Base', 'templatation' ),
                    'desc' => sprintf( __( 'The base of all portfolio item URLs (re-save the %s after changing this setting).', 'templatation' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . __( 'Permalinks', 'templatation' ) . '</a>' ),
                    'id' => $shortname.'_portfolioitems_rewrite',
                    'std' => 'portfolio-items',
                    'type' => 'text');

/*$options[] = array( 'name' => __( 'Featured Portfolio Gallery', 'templatation' ),
                    'desc' => __( 'Optionally choose a Gallery to be featured in your Portfolio template.', 'templatation' ),
                    'id' => $shortname . '_portfolio_area_gallery_term',
                    'std' => '0',
                    'type' => 'select2',
                    'options' => $portfolio_groups );
*/                        
$options[] = array( 'name' => __( 'Exclude Galleries from the Portfolio Navigation', 'templatation' ),
                    'desc' => __( 'Optionally exclude portfolio galleries from the portfolio gallery navigation switcher. Place the gallery slugs here, separated by commas <br />(eg: one, two, three)', 'templatation' ),
                    'id' => $shortname.'_portfolio_excludenav',
                    'std' => '',
                    'type' => 'text');

$options[] = array( 'name' => __( 'Exclude Portfolio Items from Search Results', 'templatation' ),
                    'desc' => __( 'Exclude portfolio items from results when searching your website.', 'templatation' ),
                    'id' => $shortname.'_portfolio_excludesearch',
                    'std' => 'false',
                    'type' => 'checkbox');

$options[] = array( 'name' => __( 'Portfolio Items Link To', 'templatation' ),
                    'desc' => __( 'Do the portfolio items link to the lightbox, or to the single portfolio item screen?', 'templatation' ),
                    'id' => $shortname.'_portfolio_linkto',
                    'std' => 'lightbox',
                    'type' => 'select2',
                    'options' => array( 'lightbox' => __( 'Lightbox', 'templatation' ), 'post' => __( 'Portfolio Item', 'templatation' ) ) ); 

/*$options[] = array( 'name' => __( 'Enable Pagination in Portfolio', 'templatation' ),
                    'desc' => __( 'Enable pagination in the portfolio section (disables JavaScript filtering by category)', 'templatation' ),
                    'id' => $shortname.'_portfolio_enable_pagination',
                    'std' => 'false', 
                    'class' => 'collapsed', 
                    'type' => 'checkbox');

*/                    
$options[] = array( 'name' => __( 'Number of posts to display on "Portfolio" page template', 'templatation' ),
                    'desc' => __( 'The number of posts to display per page, when pagination is enabled, in the "Portfolio" page template.', 'templatation' ),
                    'id' => $shortname.'_portfolio_posts_per_page',
                    'std' => get_option( 'posts_per_page' ), 
                    'class' => 'hidden last', 
                    'type' => 'text');

/* WooCommerce */

if ( is_woocommerce_activated() ) {
    $options[] = array( 'name' => __( 'WooCommerce', 'templatation' ),
    					'type' => 'heading',
    					'icon' => 'woocommerce' );

    $options[] = array( 'name' => __( 'General', 'templatation' ),
    					'type' => 'subheading' );

    $options[] = array( 'name' => __( 'Enable Catalog Mode', 'templatation' ),
                        'desc' => __( 'If enabled, price and cart buttons will not show up. Website will behave like a catalog only with no purchase functionality.', 'templatation' ),
                        'id' => $shortname.'_enable_catalog',
                        'std' => 'false',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Custom Placeholder', 'templatation' ),
                        'desc' => __( 'Upload a custom placeholder to be displayed when there is no product image.', 'templatation' ),
                        'id' => $shortname . '_placeholder_url',
                        'std' => '',
                        'type' => 'upload' );

    $options[] = array( 'name' => __( 'Header Cart Link', 'templatation' ),
                        'desc' => __( 'Display a link to the cart in the Header Area. Note that if you disable headline, it will not show up, as this is connected with headline section.', 'templatation' ),
                        'id' => $shortname.'commerce_header_cart_link',
                        'std' => 'true',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Disable Header Cart Popup', 'templatation' ),
                        'desc' => __( 'By default, on mouse on, cart icon shows popup of cart items in a popup, check to disable popup.', 'templatation' ),
                        'id' => $shortname.'_no_hdrcart_popup',
                        'std' => 'false',
                        'type' => 'checkbox' );
/*
    $options[] = array( 'name' => __( 'Header Product Search', 'templatation' ),
                        'desc' => __( 'Display a product search form in the header', 'templatation' ),
                        'id' => $shortname.'commerce_header_search_form',
                        'std' => 'true',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Distraction free checkout', 'templatation' ),
                        'desc' => __( 'Hiding distracting elements like the navigation and footer can increase conversions at the checkout. If you enable this option it is recommended that your checkout use the Full Width page template to remove the sidebar as well.', 'templatation' ),
                        'id' => $shortname.'commerce_hide_nav',
                        'std' => 'false',
                        'type' => 'checkbox' );
*/
    $options[] = array( 'name' => __( 'Product Archives', 'templatation' ),
                        'type' => 'subheading' );

    $options[] = array( 'name' => __( 'Shop archives full width?', 'templatation' ),
                        'desc' => __( 'Display the product archive in a full-width single column format? (The sidebar is removed).', 'templatation' ),
                        'id' => $shortname.'commerce_archives_fullwidth',
                        'std' => 'false',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Product columns', 'templatation' ),
                        'desc' => __( 'Select how many columns of products you want on product archive pages. Default is 2 and styled well. If you change it, some styling issues  might arise.', 'templatation' ),
                        'id' => $shortname . 'commerce_product_columns',
                        'std' => '2',
                        'type' => 'select2',
                        'options' => array( '2', '3', '4', '5' ) );

    $options[] = array( 'name' => __( 'Products per page', 'templatation' ),
    					'desc' => __( 'How many products do you want to display on product archive pages?', 'templatation' ),
    					'id' => $shortname.'commerce_products_per_page',
    					'std' => '12',
    					'type' => 'text' );

    $options[] = array( 'name' => __( 'Enable infinite scroll?', 'templatation' ),
                        'desc' => __( 'Automatically loads the next set of products via AJAX when the user scrolls to the bottom of the page', 'templatation' ),
                        'id' => $shortname.'commerce_archives_infinite_scroll',
                        'std' => 'true',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Product Details', 'templatation' ),
                        'type' => 'subheading' );

    $options[] = array( 'name' => __( 'Display related products', 'templatation' ),
    					'desc' => __( 'Display related products on the product details page', 'templatation' ),
    					'id' => $shortname.'commerce_related_products',
    					'std' => 'true',
                        'class' => 'collapsed',
    					'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Maximum related products', 'templatation' ),
                        'desc' => __( 'The maximum number of related products to display.', 'templatation' ),
                        'id' => $shortname . 'commerce_related_products_maximum',
                        'std' => '3',
                        'type' => 'select2',
                        'class' => 'hidden last',
                        'options' => array( '2', '3', '4', '5', '6', '7', '8' ) );

    $options[] = array( 'name' => __( 'Product details pages full width?', 'templatation' ),
                        'desc' => __( 'Display the product details in a full-width single column format? (The sidebar is removed)', 'templatation' ),
                        'id' => $shortname.'commerce_products_fullwidth',
                        'std' => 'false',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Link product thumbnail to product details page too', 'templatation' ),
                        'desc' => __( 'By default product thumbnail is not linked to product details page, do you want to link it ? Check to link.', 'templatation' ),
                        'id' => $shortname.'_link_prod_thumb',
                        'std' => 'false',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Show default sharing button?', 'templatation' ),
                        'desc' => __( 'Show default Twitter and facebook sharing buttons on single product display page.', 'templatation' ),
                        'id' => $shortname.'_default_sharing_button',
                        'std' => 'false',
                        'type' => 'checkbox' );

}

/* Dynamic Images */

$options[] = array( 'name' => __( 'Dynamic Images', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'image' );

$options[] = array( 'name' => __( 'Resizer Settings', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'WP Post Thumbnail', 'templatation' ),
    				'desc' => __( 'Use WordPress post thumbnail to assign a post thumbnail. Will enable the <strong>Featured Image panel</strong> in your post sidebar where you can assign a post thumbnail.', 'templatation' ),
    				'id' => $shortname . '_post_image_support',
    				'std' => 'true',
    				'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'WP Post Thumbnail - Dynamic Image Resizing', 'templatation' ),
    				'desc' => __( 'The post thumbnail will be dynamically resized using native WP resize functionality. <em>(Requires PHP 5.2+)</em>', 'templatation' ),
    				'id' => $shortname . '_pis_resize',
    				'std' => 'true',
    				'class' => 'hidden',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'WP Post Thumbnail - Hard Crop', 'templatation' ),
    				'desc' => __( 'The post thumbnail will be cropped to match the target aspect ratio (only used if "Dynamic Image Resizing" is enabled).', 'templatation' ),
    				'id' => $shortname . '_pis_hard_crop',
    				'std' => 'true',
    				'class' => 'hidden last',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Automatic Image Thumbnail', 'templatation' ),
    				'desc' => __( 'If no thumbnail is specifified then the first uploaded image in the post is used. (If you have used full size images as first attachment some styling issues may arise.)', 'templatation' ),
    				'id' => $shortname . '_auto_img',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Thumbnail Settings', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Thumbnail Image Width', 'templatation' ),
    				'desc' => __( 'Enter an integer value i.e. 602 for the desired image width which will be used when dynamically creating the images. Recommended : keep it default', 'templatation' ),
    				'id' => $shortname . '_thumb_w',
    				'std' => '610',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Thumbnail Image Height', 'templatation' ),
    				'desc' => __( 'Enter an integer value i.e. 602 for the desired image height which will be used when dynamically creating the images. Recommended : keep it default', 'templatation' ),
    				'id' => $shortname . '_thumb_h',
    				'std' => '208',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Thumbnail Alignment', 'templatation' ),
    				'desc' => __( 'Select how to align your thumbnails with posts.', 'templatation' ),
    				'id' => $shortname . '_thumb_align',
    				'std' => 'aligncenter',
    				'type' => 'select2',
    				'options' => array( 'alignleft' => __( 'Left', 'templatation' ), 'alignright' => __( 'Right', 'templatation' ), 'aligncenter' => __( 'Center', 'templatation' ) ) );

$options[] = array( 'name' => __( 'Single Post - Show Thumbnail', 'templatation' ),
    				'desc' => __( 'Show the thumbnail in the single post page.', 'templatation' ),
    				'id' => $shortname . '_thumb_single',
    				'class' => 'collapsed',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Single Post - Thumbnail Width', 'templatation' ),
    				'desc' => __( 'Enter an integer value for single post image width. Recommended : keep it default', 'templatation' ),
    				'id' => $shortname . '_single_w',
    				'std' => '610',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Single Post - Thumbnail Height', 'templatation' ),
    				'desc' => __( 'Enter an integer value for single post image height. Recommended : keep it default', 'templatation' ),
    				'id' => $shortname . '_single_h',
    				'std' => '208',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Single Post - Thumbnail Alignment', 'templatation' ),
    				'desc' => __( 'Select how to align your thumbnail with single posts.', 'templatation' ),
    				'id' => $shortname . '_thumb_single_align',
    				'std' => 'alignright',
    				'type' => 'select2',
    				'class' => 'hidden',
    				'options' => array( 'alignleft' => __( 'Left', 'templatation' ), 'alignright' => __( 'Right', 'templatation' ), 'aligncenter' => __( 'Center', 'templatation' ) ) );

$options[] = array( 'name' => __( 'Add thumbnail to RSS feed', 'templatation' ),
    				'desc' => __( 'Add the the image uploaded via your Custom Settings panel to your RSS feed', 'templatation' ),
    				'id' => $shortname . '_rss_thumb',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Enable Lightbox', 'templatation' ),
    				'desc' => __( 'Enable the PrettyPhoto lighbox script on images within your website\'s content.', 'templatation' ),
    				'id' => $shortname . '_enable_lightbox',
    				'std' => 'false',
    				'type' => 'checkbox' );

/* Footer */

$options[] = array( 'name' => __( 'Footer Customization', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'footer' );

$url =  get_template_directory_uri() . '/functions/images/';
$options[] = array( 'name' => __( 'Footer Widget Areas', 'templatation' ),
    				'desc' => __( 'Select how many footer widget areas you want to display.', 'templatation' ),
    				'id' => $shortname . '_footer_sidebars',
    				'std' => '4',
    				'type' => 'images',
    				'options' => array(
    					'0' => $url . 'layout-off.png',
    					'1' => $url . 'footer-widgets-1.png',
    					'2' => $url . 'footer-widgets-2.png',
    					'3' => $url . 'footer-widgets-3.png',
    					'4' => $url . 'footer-widgets-4.png' )
    				);

$options[] = array( 'name' => __( 'Footer showcase content', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_extreme_footer_notice',
    				'std' => __( 'Below settings are for the showcase area of the footer. Which appears half in the content section and half in the footer section. Separate settings are there for left and right side box. Note: Footer showcase area only appears if footer widgets are present.', 'templatation' ),
    				'type' => 'info' );

$options[] = array( 'name' => __( 'Enable footer showcase area', 'templatation' ),
    				'desc' => __( 'Uncheck this if you do not want the footer showcase area (round corner area just above footer widgets area).', 'templatation' ),
    				'id' => $shortname . '_ft_enable_hero',
    				'std' => 'true',
					'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Title (Left side)', 'templatation' ),
    				'desc' => __( 'Enter the title to show in Left footer showcase area.', 'templatation' ),
    				'id' => $shortname . '_ft_title_left',
    				'std' => 'Title (Left side)',
					'class' => 'hidden',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Show icon background (Left side)', 'templatation' ),
    				'desc' => __( 'Do you want to show the icon background ? You might need to disable it if the background doesnt fit well with the image you use below.', 'templatation' ),
    				'id' => $shortname . '_ft_icon_bg_left',
    				'std' => 'true',
					'class' => 'hidden',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Upload icon (Left side)', 'templatation' ),
    				'desc' => __( 'Upload a logo for your theme, or specify an image URL directly. 59x59 size recommended.', 'templatation' ),
    				'id' => $shortname . '_ft_icon_left',
    				'std' => '',
					'class' => 'hidden',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Text (Left side)', 'templatation' ),
    				'desc' => __( 'Enter the content for Left footer showcase area.', 'templatation' ),
    				'id' => $shortname . '_ft_content_left',
    				'std' => 'Edit this text and title in Theme-options -> Footer Customization -> Footer showcase content section. Lorem ipsum dolor sit amet, consectetuer adipisc in elit, sed diam non ummy nibh in euismod tincidunt ut liber tempor laoreet.',
					'class' => 'hidden',
    				'type' => 'textarea' );


$options[] = array( 'name' => __( 'Title (Right side)', 'templatation' ),
    				'desc' => __( 'Enter the title to show in Right footer showcase area.', 'templatation' ),
    				'id' => $shortname . '_ft_title_right',
    				'std' => '',
					'class' => 'hidden',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Show icon background (Right side)', 'templatation' ),
    				'desc' => __( 'Do you want to show the icon background ? You might need to disable it if the background doesnt fit well with the image you use below.', 'templatation' ),
    				'id' => $shortname . '_ft_icon_bg_right',
    				'std' => 'true',
					'class' => 'hidden',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Upload icon (Right side)', 'templatation' ),
    				'desc' => __( 'Upload a logo for your theme, or specify an image URL directly. 59x59 size recommended.', 'templatation' ),
    				'id' => $shortname . '_ft_icon_right',
    				'std' => '',
					'class' => 'hidden',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Text (Right side)', 'templatation' ),
    				'desc' => __( 'Enter the content for Right footer showcase area.', 'templatation' ),
    				'id' => $shortname . '_ft_content_right',
    				'std' => 'Edit this text and title in Theme-options -> Footer Customization -> Footer showcase content section. Lorem ipsum dolor sit amet, consectetuer adipisc in elit, sed diam non ummy nibh in euismod tincidunt ut liber tempor laoreet.',
					'class' => 'hidden last',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Extreme footer content', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_extreme_footer_notice',
    				'std' => __( 'Below settings are for the extreme bottom bar content.', 'templatation' ),
    				'type' => 'info' );


$options[] = array( 'name' => __( 'Enable Custom Footer (Left)', 'templatation' ),
    				'desc' => __( 'Activate to add the custom text below to the theme footer.', 'templatation' ),
    				'id' => $shortname . '_footer_left',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Custom Text (Left)', 'templatation' ),
    				'desc' => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'templatation' ),
    				'id' => $shortname . '_footer_left_text',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Enable Custom Footer (Right)', 'templatation' ),
    				'desc' => __( 'Activate to add the custom text below to the theme footer.', 'templatation' ),
    				'id' => $shortname . '_footer_right',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Custom Text (Right)', 'templatation' ),
    				'desc' => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'templatation' ),
    				'id' => $shortname . '_footer_right_text',
    				'std' => '',
    				'type' => 'textarea' );

/* Subscribe & Connect */

$options[] = array( 'name' => __( 'Subscribe & Connect', 'templatation' ),
    				'type' => 'heading',
    				'icon' => 'connect' );

$options[] = array( 'name' => __( 'Setup', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Enable Subscribe & Connect - Single Post', 'templatation' ),
    				'desc' => sprintf( __( 'Enable the subscribe & connect area on single posts. You can also add this as a %1$s in your sidebar.', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/widgets.php">widget</a>' ),
    				'id' => $shortname . '_connect',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Subscribe Title', 'templatation' ),
    				'desc' => __( 'Enter the title to show in your subscribe & connect area.', 'templatation' ),
    				'id' => $shortname . '_connect_title',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Text', 'templatation' ),
    				'desc' => __( 'Change the default text in this area.', 'templatation' ),
    				'id' => $shortname . '_connect_content',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Enable Related Posts', 'templatation' ),
    				'desc' => __( 'Enable related posts in the subscribe area. Uses posts with the same <strong>tags</strong> to find related posts. Note: Will not show in the Subscribe widget.', 'templatation' ),
    				'id' => $shortname . '_connect_related',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Subscribe Settings', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Subscribe By E-mail ID (Feedburner)', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s for the e-mail subscription form.', 'templatation' ), '<a href="http://www.templatation.com/tutorials/how-to-find-your-feedburner-id-for-email-subscription/">'.__( 'Feedburner ID', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_newsletter_id',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Subscribe By E-mail to MailChimp', 'templatation', 'templatation' ),
    				'desc' => sprintf( __( 'If you have a MailChimp account you can enter the %1$s to allow your users to subscribe to a MailChimp List.', 'templatation' ), '<a href="http://woochimp.heroku.com" target="_blank">'.__( 'MailChimp List Subscribe URL', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_mailchimp_list_url',
    				'std' => '',
    				'type' => 'text' );

/*$options[] = array( 'name' => __( 'Social Profile Settings', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_social_profile_notice',
    				'std' => __( 'Below settings are for social profiles. If you dont want to use one , simply leave it blank.', 'templatation' ),
    				'type' => 'info' );
*/
$options[] = array( 'name' => __( 'Connect Settings', 'templatation' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Enable RSS', 'templatation' ),
    				'desc' => __( 'Enable the subscribe and RSS icon.', 'templatation' ),
    				'id' => $shortname . '_connect_rss',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Twitter URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.twitter.com/templatation', 'templatation' ), '<a href="http://www.twitter.com/">'.__( 'Twitter', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_twitter',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Facebook URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.facebook.com/templatation', 'templatation' ), '<a href="http://www.facebook.com/">'.__( 'Facebook', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_facebook',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'YouTube URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.youtube.com/templatation', 'templatation' ), '<a href="http://www.youtube.com/">'.__( 'YouTube', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_youtube',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Flickr URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.flickr.com/templatation', 'templatation' ), '<a href="http://www.flickr.com/">'.__( 'Flickr', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_flickr',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'LinkedIn URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.linkedin.com/in/templatation', 'templatation' ), '<a href="http://www.www.linkedin.com.com/">'.__( 'LinkedIn', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_linkedin',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Pinterest URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.pinterest.com/templatation', 'templatation' ), '<a href="http://www.pinterest.com/">'.__( 'Pinterest', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_pinterest',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Instagram URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.instagram.com/templatation', 'templatation' ), '<a href="http://www.instagram.com/">'.__( 'Instagram', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_instagram',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Google+ URL', 'templatation' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. https://plus.google.com/104560124403688998123/', 'templatation' ), '<a href="http://plus.google.com/">'.__( 'Google+', 'templatation' ).'</a>' ),
    				'id' => $shortname . '_connect_googleplus',
    				'std' => '',
    				'type' => 'text' );

/* Contact Template Settings */

$options[] = array( 'name' => __( 'Contact Page', 'templatation' ),
					'icon' => 'maps',
				    'type' => 'heading');

$options[] = array( 'name' => __( 'Contact Information', 'templatation' ),
					'type' => 'subheading');

$options[] = array( 'name' => __( 'Enable Contact Information Panel', 'templatation' ),
					'desc' => __( 'Enable the contact informal panel', 'templatation' ),
					'id' => $shortname.'_contact_panel',
					'std' => 'false',
					'class' => 'collapsed',
					'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Location Name', 'templatation' ),
					'desc' => __( 'Enter the location name. Example: London Office', 'templatation' ),
					'id' => $shortname . '_contact_title',
					'std' => '',
					'class' => 'hidden',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Location Address', 'templatation' ),
					'desc' => __( "Enter your company's address", 'templatation' ),
					'id' => $shortname . '_contact_address',
					'std' => '',
					'class' => 'hidden',
					'type' => 'textarea' );

$options[] = array( 'name' => __( 'Telephone', 'templatation' ),
					'desc' => __( 'Enter your telephone number', 'templatation' ),
					'id' => $shortname . '_contact_number',
					'std' => '',
					'class' => 'hidden',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Fax', 'templatation' ),
					'desc' => __( 'Enter your fax number', 'templatation' ),
					'id' => $shortname . '_contact_fax',
					'std' => '',
					'class' => 'hidden last',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Contact Form E-Mail', 'templatation' ),
					'desc' => __( "Enter your E-mail address to use on the 'Contact Form' page Template.", 'templatation' ),
					'id' => $shortname.'_contactform_email',
					'std' => '',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Your Twitter Shortcode(since version 5.3)', 'templatation' ),
					'desc' => __( 'The inbuilt twitter facility has been discountinued due to security reasons. You can use any plugin for twitter needs and enter the supplied shortcode to display tweets here.(recommended plugin: Twitter Widget Pro) enter shortcode eg : [twitter-widget username="yourTwitterUsername"]. Note: This will not work if shortcode is not valid. Contact support for more info.', 'templatation' ),
					'id' => $shortname . '_contact_twitter',
					'std' => '',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Enable Subscribe and Connect', 'templatation' ),
					'desc' => __( 'Enable the subscribe and connect functionality on the contact page template', 'templatation' ),
					'id' => $shortname.'_contact_subscribe_and_connect',
					'std' => 'false',
					'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Maps', 'templatation' ),
					'type' => 'subheading');

$options[] = array( 'name' => __( 'Show map in headline area.', 'templatation' ),
					'desc' => __( 'If checked, google map will show up in headline area behind the header. If unchecked google map will show up in the middle of the page. Note google map shows up only if you enter coordinates below.', 'templatation' ),
					'id' => $shortname.'_headline_gmap',
					'std' => 'true',
					'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Contact Form Google Maps Coordinates', 'templatation' ),
					'desc' => sprintf( __( 'Enter your Google Map coordinates to display a map on the Contact Form page template and a link to it on the Contact Us widget. You can get these details from %1$s', 'templatation' ), '<a href="http://www.dbsgeo.com/latlon/" target="_blank">'.__( 'Google Maps', 'templatation' ).'</a>' ),
					'id' => $shortname . '_contactform_map_coords',
					'std' => '',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Disable Mousescroll', 'templatation' ),
					'desc' => __( 'Turn off the mouse scroll action for all the Google Maps on the site. This could improve usability on your site.', 'templatation' ),
					'id' => $shortname . '_maps_scroll',
					'std' => '',
					'type' => 'checkbox');

$options[] = array( 'name' => __( 'Map Height', 'templatation' ),
					'desc' => __( 'Height in pixels for the maps displayed on Single.php pages.', 'templatation' ),
					'id' => $shortname . '_maps_single_height',
					'std' => '250',
					'type' => 'text');

$options[] = array( 'name' => __( 'Default Map Zoom Level', 'templatation' ),
					'desc' => __( 'Set this to adjust the default in the post & page edit backend.', 'templatation' ),
					'id' => $shortname . '_maps_default_mapzoom',
					'std' => '9',
					'type' => 'select2',
					'options' => $other_entries);

$options[] = array( 'name' => __( 'Default Map Type', 'templatation' ),
					'desc' => __( 'Set this to the default rendered in the post backend.', 'templatation' ),
					'id' => $shortname . '_maps_default_maptype',
					'std' => 'G_NORMAL_MAP',
					'type' => 'select2',
					'options' => array( 'G_NORMAL_MAP' => __( 'Normal', 'templatation' ), 'G_SATELLITE_MAP' => __( 'Satellite', 'templatation' ),'G_HYBRID_MAP' => __( 'Hybrid', 'templatation' ), 'G_PHYSICAL_MAP' => __( 'Terrain', 'templatation' ) ) );

$options[] = array( 'name' => __( 'Map Callout Text', 'templatation' ),
					'desc' => __( 'Text or HTML that will be output when you click on the map marker for your location.', 'templatation' ),
					'id' => $shortname . '_maps_callout_text',
					'std' => '',
					'type' => 'textarea');
					
/* Plugins */
$options[] = array( 'name' => __( 'Included Plugins', 'templatation' ),
					'icon' => 'general',
				    'type' => 'heading');

$options[] = array( 'name' => __( 'Included Plugins', 'templatation' ),
    				'desc' => '',
    				'id' => $shortname . '_inc_plugins',
    				'std' => sprintf( __( 'The theme requires/recommends some plugin to function properly. Theme also includes some premium plugins with your purchase, to manage plugins, please %1$s. (or find them in Appearance -> Install plugins ).', 'templatation' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/themes.php?page=install-required-plugins">'.__( 'Click Here', 'templatation' ).'</a>' ),
    				'type' => 'info' );


// Add extra options through function
if ( function_exists( 'woo_options_add') )
	$options = woo_options_add($options);

if ( get_option( 'woo_template') != $options) update_option( 'woo_template',$options);
if ( get_option( 'woo_themename') != $themename) update_option( 'woo_themename',$themename);
if ( get_option( 'woo_shortname') != $shortname) update_option( 'woo_shortname',$shortname);
if ( get_option( 'woo_manual') != $manualurl) update_option( 'woo_manual',$manualurl);

// Woo Metabox Options
// Start name with underscore to hide custom key from the user
global $post;
$woo_metaboxes = array();

// Shown on both posts and pages


// Show only on specific post types or page
if ( get_post_type() == 'portfolio' || !get_post_type() ) {

    $woo_metaboxes[] = array (  
                                'name'  => 'embed',
                                'std'  => '',
                                'label' => __(  'Embed Code',  'templatation' ),
                                'type' => 'textarea',
                                'desc' => __(  'Enter the video embed code for your video (YouTube, Vimeo or similar)',  'templatation' )
                                );


    $woo_metaboxes[] = array (  
                                'name' => '_portfolio_url',
                                'std' => '',
                                'label' => __( 'Portfolio URL', 'templatation'),
                                'type' => 'text',
                                'desc' => __( 'Enter an alternative URL for your Portfolio item. By default it will link to your portfolio post or lightbox. Its useful if you want to link portfolio item to external link. It links to View Project button in one column portfolio page template.', 'templatation')
                                );
    
    $woo_metaboxes['lightbox-url'] = array (    
                                'name' => 'lightbox-url',
                                'label' => __( 'Lightbox URL', 'templatation' ),
                                'type' => 'text',
                                'desc' => sprintf( __( 'Enter an optional URL to show in the %s for this portfolio item.', 'templatation' ), '<a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/">' . __( 'PrettyPhoto lightbox', 'templatation' ) . '</a>' ) 
                                );

} // End portfolio

if ( ( get_post_type() == 'post') || ( !get_post_type() ) ) {

	// TimThumb is enabled in options
	if ( get_option( 'woo_resize') == 'true' ) {

		$woo_metaboxes[] = array (	'name' => 'image',
									'label' => __( 'Image', 'templatation' ),
									'type' => 'upload',
									'desc' => __( 'Upload an image or enter an URL.', 'templatation' ) );

		$woo_metaboxes[] = array (	'name' => '_image_alignment',
									'std' => __( 'Center', 'templatation' ),
									'label' => __( 'Image Crop Alignment', 'templatation' ),
									'type' => 'select2',
									'desc' => __( 'Select crop alignment for resized image', 'templatation' ),
									'options' => array(	'c' => 'Center',
														't' => 'Top',
														'b' => 'Bottom',
														'l' => 'Left',
														'r' => 'Right'));
	// TimThumb disabled in the options
	} else {

		$woo_metaboxes[] = array (	'name' => '_t-i-m-t-humb-info',
									'label' => __( 'Image', 'templatation' ),
									'type' => 'info',
									'desc' => __( 'Please use the Featured image just right side of this message to upload featured image. It will be resized using native wordpress image resizing script.', 'templatation') ) ;

	}

	$woo_metaboxes[] = array (  'name'  => 'embed',
					            'std'  => '',
					            'label' => __( 'Embed Code', 'templatation' ),
					            'type' => 'textarea',
					            'desc' => __( 'Enter the video embed code for your video (YouTube, Vimeo or similar)', 'templatation' ) );

} // End post

if ( ( get_post_type() == 'post') || ( get_post_type() == 'page') || ( get_post_type() == 'product') ) {
/*
$woo_metaboxes[] = array (	'name' => 'singbgimage',
							'label' => __( 'Whole Background Image', 'templatation' ),
							'type' => 'upload',
							'desc' => __( 'Upload an image for the page background area, this image aligns center top in background of whole page. It will override the Body background image you set in Themeoptions -> Styling -> Body settings.', 'templatation' ) );

$woo_metaboxes[] = array( 'name' => '_single_page_bg_image_repeat',
					'desc' => __( 'Select how you would like to repeat the background-image you chose above', 'templatation' ),
					'label' => __( 'Background Image Repeat', 'templatation' ),
					'std' => 'no-repeat',
					'type' => 'select',
					'options' => array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) );
*/
$woo_metaboxes[] = array(   'name' => '_single_disable_headline',
							'desc' => __( 'If disabled the headline area will not appear on this post/page. The settings below will not make any difference if this is globally disabled from theme options panel. Check to disable. If disabled , breadcrumb will be shown on headline area instead.', 'templatation' ),
							'label' => __( 'Disable headline area from this page', 'templatation' ),
							'std' => 'false',
							'type' => 'checkbox' );

$woo_metaboxes[] = array (
							'name' => '_single_headline_heading',
							'label' => __( 'Heading Title.', 'templatation' ),
							'type' => 'text',
							'desc' => __( 'Enter the headline which appears on the top in headline area as heading. Leave blank to show default that has been entered in Theme-options.', 'templatation' )
							);

$woo_metaboxes[] = array (
							'name'  => '_single_headline_message',
							'std'  => '',
							'label' => __( 'Headline text', 'templatation' ),
							'type' => 'textarea',
							'desc' => __( 'This text appears in headline area below heading. Leave blank to show default that has been entered in Theme-options.(If you dont want any headline subtext particularly for this page, enter blank space.)', 'templatation' )
							);

$woo_metaboxes[] = array(   'name' => '_hide_title_display',
							'desc' => __( 'In some case, you might want to hide the default title display. Check this to hide title. If you are not sure about it , leave it unchecked. N/A for woocommerce products.', 'templatation' ),
							'label' => __( 'Hide default title display in middle content area.', 'templatation' ),
							'std' => 'false',
							'type' => 'checkbox' );

$woo_metaboxes[] = array( 'name' => '_single_disable_breadcrumbs',
    				'desc' => __( 'If checked, Breadcrumbs will not show up on this page. This can be globally turned off from admin too.', 'templatation' ),
					'label' => __( 'Disable Breadcrumbs', 'templatation' ),
    				'std' => 'false',
    				'type' => 'checkbox' );

$woo_metaboxes[] = array (	'name' => '_single_page_slider',
							'label' => __( 'Hero/Slider Area Content.', 'templatation' ),
							'type' => 'textarea',
							'desc' => __( 'This content appears on the header area, you can use it to place slider particular for this page/post/product or any other image or content. Shortcodes allowed. e.g. [rev_slider portfolio-slider]', 'templatation' )
							);


}  // End general post/page metabox.

$woo_metaboxes[] = array (	'name' => '_layout',
							'std' => 'normal',
							'label' => __( 'Layout', 'templatation' ),
							'type' => 'images',
							'desc' => __( 'Select the layout you want on this specific post/page.', 'templatation' ),
							'options' => array(
										'layout-default' => $url . 'layout-off.png',
										'layout-full' => get_template_directory_uri() . '/functions/images/' . '1c.png',
										'layout-left-content' => get_template_directory_uri() . '/functions/images/' . '2cl.png',
										'layout-right-content' => get_template_directory_uri() . '/functions/images/' . '2cr.png'));

if ( get_post_type() == 'slide' || ! get_post_type() ) {
        $woo_metaboxes[] = array (
                                    'name' => 'url',
                                    'label' => __( 'Slide URL', 'templatation' ),
                                    'type' => 'text',
                                    'desc' => sprintf( __( 'Enter an URL to link the slider title to a page e.g. %s (optional)', 'templatation' ), 'http://yoursite.com/pagename/' )
                                    );

        $woo_metaboxes[] = array (
                                    'name'  => 'embed',
                                    'std'  => '',
                                    'label' => __( 'Embed Code', 'templatation' ),
                                    'type' => 'textarea',
                                    'desc' => __( 'Enter the video embed code for your video (YouTube, Vimeo or similar)', 'templatation' )
                                    );
} // End Slide


// Add extra metaboxes through function
if ( function_exists( 'woo_metaboxes_add' ) )
	$woo_metaboxes = woo_metaboxes_add( $woo_metaboxes );

if ( get_option( 'woo_custom_template' ) != $woo_metaboxes) update_option( 'woo_custom_template', $woo_metaboxes );

} // END woo_options()
} // END function_exists()

// Add options to admin_head
add_action( 'admin_head', 'woo_options' );

//Enable WooSEO on these Post types
$seo_post_types = array( 'post', 'page' );
define( 'SEOPOSTTYPES', serialize( $seo_post_types ));

//Global options setup
add_action( 'init', 'woo_global_options' );
function woo_global_options(){
	// Populate templatation option in array for use in theme and add some defaults
	global $woo_options;
	$woo_options = get_option( 'woo_options' );
	 $woo_options['woo_resize'] = false;
	 $woo_options['framework_woo_theme_version_checker'] = false;
	 $woo_options['framework_woo_framework_version_checker'] = false;
	 $woo_options['framework_woo_admin_bar_enhancements'] = false;
}