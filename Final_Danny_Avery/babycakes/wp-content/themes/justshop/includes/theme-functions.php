<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Exclude categories from displaying on the "Blog" page template.
- Exclude categories from displaying on the homepage.
- Register WP Menus
- Breadcrumb display
- Page navigation
- Post Meta
- Subscribe & Connect
- Comment Form Fields
- Comment Form Settings
- Archive Description
- WooPagination markup
- Google maps (for contact template)
- Featured Slider: Post Type
- Featured Slider: Hook Into Content
- Featured Slider: Get Slides
- Is IE
- Check if WooCommerce is activated
- Contact list
- Woo Portfolio Navigation
- Woo Portfolio Item Settings
- Woo Portfolio, show portfolio galleries in portfolio item breadcrumbs

- @ Justshop Function templatation_headline_area  // adding custom style for headline area
- @ Justshop Function justshop_slider_cont // hooking function for slider setup.
//Register contactwidget shortcode
- @ Justshop Function templatation_logo_offset // adding custom style in body tag

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the "Blog" page template.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the "Blog" page template.
add_filter( 'woo_blog_template_query_args', 'woo_exclude_categories_blogtemplate' );

function woo_exclude_categories_blogtemplate ( $args ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $args; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_blog' );

	// Homepage logic.
	if ( count( $excluded_cats ) > 0 ) {

		// Setup the categories as a string, because "category__not_in" doesn't seem to work
		// when using query_posts().

		foreach ( $excluded_cats as $k => $v ) { $excluded_cats[$k] = '-' . $v; }
		$cats = join( ',', $excluded_cats );

		$args['cat'] = $cats;
	}

	return $args;

} // End woo_exclude_categories_blogtemplate()

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the homepage.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the homepage.
add_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' );

function woo_exclude_categories_homepage ( $query ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $query; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_home' );

	// Homepage logic.
	if ( is_home() && ( count( $excluded_cats ) > 0 ) ) {
		$query->set( 'category__not_in', $excluded_cats );
	}

	$query->parse_query();

	return $query;

} // End woo_exclude_categories_homepage()

/*-----------------------------------------------------------------------------------*/
/* Register WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'templatation' ) ) );
}

/*-----------------------------------------------------------------------------------*/
/* Breadcrumb display */
/*-----------------------------------------------------------------------------------*/

/*add_action('woo_main_before','woo_display_breadcrumbs',10);
*/
if (!function_exists( 'woo_display_breadcrumbs')) {
	function woo_display_breadcrumbs() {
		global $woo_options, $wp_query ;
		$tt_post_id = '';
		if ( !is_404() && !is_search() ) {
		if ( ! empty( $wp_query->post->ID ) ) {
			$tt_post_id = $wp_query->post->ID;
				}
			}
		$single_disable_breadcrumbs = get_post_meta($tt_post_id, '_single_disable_breadcrumbs', true);
		if ( !isset($single_disable_breadcrumbs) || empty($single_disable_breadcrumbs) ) $single_disable_breadcrumbs = 'false';
		if ( $single_disable_breadcrumbs == 'true') return; // do nothing if breadcrumb is disabled on page editors.

		if ( function_exists('yoast_breadcrumb') ) { // if Yoast seo is enabled, output that breadcrumb.
			yoast_breadcrumb( '<section class="breadcrumbs-wrap yoast"><div class="breadcrumb breadcrumbs woo-breadcrumbs">', '</div></section>' );
		}

		elseif ( function_exists('bcn_display') ) { ?>
			<section class="breadcrumbs-wrap navxt">
				<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
					<?php bcn_display(); ?>
				</div>
			</section><!--/#breadcrumbs-wrap --> <?php
		}
		else {
			if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' && ! is_home() ) {
			echo '<section class="breadcrumbs-wrap">';
				woo_breadcrumbs();
			echo '</section><!--/#breadcrumbs-wrap -->';
			}
		}

	} // End woo_display_breadcrumbs()
} // End IF Statement


/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_pagenav')) {
	function woo_pagenav() {

		global $woo_options;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>
            <nav class="nav-entries fix">
                <?php next_posts_link( '<span class="nav-prev fl">'. __( 'Older posts', 'templatation' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts', 'templatation' ) . '</span>' ); ?>
            </nav>
		<?php
			}
		} else {
			woo_pagination();

		} // End IF Statement

	} // End woo_pagenav()
} // End IF Statement


/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_post_meta')) {
	function woo_post_meta( ) {
?>
<div class="post-meta">
	<ul class="list-f">
		<li class="post-author a">
			<?php the_author_posts_link(); ?>
		</li>
		<li class="post-comments b">
			<?php comments_popup_link( __( 'Leave a comment', 'templatation' ), __( '1 Comment', 'templatation' ), __( '% Comments', 'templatation' ) ); ?>
		</li>
		<?php $categorri = get_the_category();
		if($categorri[0]) { ?>
		<li class="post-category">
			<?php the_category( ' '); ?>
		</li>
		<?php } ?>
	</ul>
</div>
<?php
	}
}


/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '', $contact_template = 'false') {

		//Setup default variables, overriding them if the "Theme Options" have been saved.
		$settings = array(
						'connect' => 'false',
						'connect_title' => __('Subscribe' , 'templatation'),
						'connect_related' => 'true',
						'connect_content' => __( 'Subscribe to our profiles on the following social networks.', 'templatation' ),
						'connect_newsletter_id' => '',
						'connect_mailchimp_list_url' => '',
						'feed_url' => '',
						'connect_rss' => '',
						'connect_twitter' => '',
						'connect_facebook' => '',
						'connect_youtube' => '',
						'connect_flickr' => '',
						'connect_linkedin' => '',
						'connect_pinterest' => '',
						'connect_instagram' => '',
						'connect_rss' => '',
						'connect_googleplus' => ''
						);
		$settings = woo_get_dynamic_values( $settings );

		// Setup title
		if ( $widget != 'true' )
			$title = $settings[ 'connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $settings[ 'connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $settings[ 'connect' ] == "true" OR $widget == 'true' ) : ?>
	<aside id="connect" class="fix">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','templatation'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<?php if ($settings[ 'connect_content' ] != '' AND $contact_template == 'false') echo '<p>' . stripslashes($settings[ 'connect_content' ]) . '</p>'; ?>

			<?php if ( $settings[ 'connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $settings[ 'connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'templatation' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'templatation' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'templatation' ); ?>';}" />
				<input type="hidden" value="<?php echo $settings[ 'connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit email-submit" type="submit" name="submit" value="<?php _e( 'Submit', 'templatation' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $settings['connect_mailchimp_list_url'] != "" AND $form != 'on' AND $settings['connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form" action="<?php echo $settings['connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $settings['connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','templatation'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','templatation'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','templatation'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'templatation'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $settings['connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $settings['connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $settings['feed_url'] ) { echo esc_url( $settings['feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="RSS"></a>

		   		<?php } if ( $settings['connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_twitter'] ); ?>" class="twitter" title="Twitter"></a>

		   		<?php } if ( $settings['connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_facebook'] ); ?>" class="facebook" title="Facebook"></a>

		   		<?php } if ( $settings['connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_youtube'] ); ?>" class="youtube" title="YouTube"></a>

		   		<?php } if ( $settings['connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_flickr'] ); ?>" class="flickr" title="Flickr"></a>

		   		<?php } if ( $settings['connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_linkedin'] ); ?>" class="linkedin" title="LinkedIn"></a>

		   		<?php } if ( $settings['connect_pinterest' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_pinterest'] ); ?>" class="pinterest" title="Pinterest"></a>

		   		<?php } if ( $settings['connect_instagram' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_instagram'] ); ?>" class="instagram" title="Instagram"></a>

		   		<?php } if ( $settings['connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_googleplus'] ); ?>" class="googleplus" title="Google+"></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $settings['connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'templatation' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

	</aside>
	<?php endif; ?>
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Fields */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_default_fields', 'woo_comment_form_fields' );

	if ( ! function_exists( 'woo_comment_form_fields' ) ) {
		function woo_comment_form_fields ( $fields ) {

			$commenter = wp_get_current_commenter();

			$required_text = ' <span class="required">(' . __( 'Required', 'templatation' ) . ')</span>';

			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$fields =  array(
				'author' => '<p class="comment-form-author">' .
							'<input id="author" class="txt" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'<label for="author">' . __( 'Name', 'templatation'  ) . ( $req ? $required_text : '' ) . '</label> ' .
							'</p>',
				'email'  => '<p class="comment-form-email">' .
				            '<input id="email" class="txt" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
				            '<label for="email">' . __( 'Email', 'templatation'  ) . ( $req ? $required_text : '' ) . '</label> ' .
				            '</p>',
				'url'    => '<p class="comment-form-url">' .
				            '<input id="url" class="txt" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
				            '<label for="url">' . __( 'Website', 'templatation'  ) . '</label>' .
				            '</p>',
			);

			return $fields;

		} // End woo_comment_form_fields()
	}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Settings */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_defaults', 'woo_comment_form_settings' );

	if ( ! function_exists( 'woo_comment_form_settings' ) ) {
		function woo_comment_form_settings ( $settings ) {

			$settings['comment_notes_before'] = '';
			$settings['comment_notes_after'] = '';
			$settings['label_submit'] = __( 'Submit Comment', 'templatation' );
			$settings['cancel_reply_link'] = __( 'Click here to cancel reply.', 'templatation' );

			return $settings;

		} // End woo_comment_form_settings()
	}

	/*-----------------------------------------------------------------------------------*/
	/* Misc back compat */
	/*-----------------------------------------------------------------------------------*/

	// array_fill_keys doesn't exist in PHP < 5.2
	// Can remove this after PHP <  5.2 support is dropped
	if ( !function_exists( 'array_fill_keys' ) ) {
		function array_fill_keys( $keys, $value ) {
			return array_combine( $keys, array_fill( 0, count( $keys ), $value ) );
		}
	}

/*-----------------------------------------------------------------------------------*/
/**
 * woo_archive_description()
 *
 * Display a description, if available, for the archive being viewed (category, tag, other taxonomy).
 *
 * @since V1.0.0
 * @uses do_atomic(), get_queried_object(), term_description()
 * @echo string
 * @filter woo_archive_description
 */

if ( ! function_exists( 'woo_archive_description' ) ) {
	function woo_archive_description ( $echo = true ) {
		do_action( 'woo_archive_description' );

		// Archive Description, if one is available.
		$term_obj = get_queried_object();
		$description = term_description( $term_obj->term_id, $term_obj->taxonomy );

		if ( $description != '' ) {
			// Allow child themes/plugins to filter here ( 1: text in DIV and paragraph, 2: term object )
			$description = apply_filters( 'woo_archive_description', '<div class="archive-description">' . $description . '</div><!--/.archive-description-->', $term_obj );
		}

		if ( $echo != true ) { return $description; }

		echo $description;
	} // End woo_archive_description()
}

/*-----------------------------------------------------------------------------------*/
/* WooPagination Markup */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_pagination_args', 'woo_pagination_html5_markup', 2 );

function woo_pagination_html5_markup ( $args ) {
	$args['before'] = '<nav class="pagination woo-pagination">';
	$args['after'] = '</nav>';

	return $args;
} // End woo_pagination_html5_markup()


/*-----------------------------------------------------------------------------------*/
/* Google Maps */
/*-----------------------------------------------------------------------------------*/

function woo_maps_contact_output($args){

	$key = get_option('woo_maps_apikey');

	// No More API Key needed

	if ( !is_array($args) )
		parse_str( $args, $args );

	extract($args);
	$mode = '';
	$streetview = 'off';
	$map_height = get_option('woo_maps_single_height');
	$featured_w = get_option('woo_home_featured_w');
	$featured_h = get_option('woo_home_featured_h');
	$zoom = get_option('woo_maps_default_mapzoom');
	$type = get_option('woo_maps_default_maptype');
	$marker_title = get_option('woo_contact_title');
	if ( $zoom == '' ) { $zoom = 6; }
	$lang = get_option('woo_maps_directions_locale');
	$locale = '';
	if(!empty($lang)){
		$locale = ',locale :"'.$lang.'"';
	}
	$extra_params = ',{travelMode:G_TRAVEL_MODE_WALKING,avoidHighways:true '.$locale.'}';

	if(empty($map_height)) { $map_height = 250;}

	if(is_home() && !empty($featured_h) && !empty($featured_w)){
	?>
    <div id="single_map_canvas" style="width:<?php echo $featured_w; ?>px; height: <?php echo $featured_h; ?>px"></div>
    <?php } else { ?>
    <div id="single_map_canvas" style="width:100%; height: <?php echo $map_height; ?>px"></div>
    <?php } ?>
    <script type="text/javascript">
		jQuery(document).ready(function(){
			function initialize() {


			<?php if($streetview == 'on'){ ?>


			<?php } else { ?>

			  	<?php switch ($type) {
			  			case 'G_NORMAL_MAP':
			  				$type = 'ROADMAP';
			  				break;
			  			case 'G_SATELLITE_MAP':
			  				$type = 'SATELLITE';
			  				break;
			  			case 'G_HYBRID_MAP':
			  				$type = 'HYBRID';
			  				break;
			  			case 'G_PHYSICAL_MAP':
			  				$type = 'TERRAIN';
			  				break;
			  			default:
			  				$type = 'ROADMAP';
			  				break;
			  	} ?>

			  	var myLatlng = new google.maps.LatLng(<?php echo $geocoords; ?>);
				var myOptions = {
				  zoom: <?php echo $zoom; ?>,
				  center: myLatlng,
				  mapTypeId: google.maps.MapTypeId.<?php echo $type; ?>
				};
				<?php if(get_option('woo_maps_scroll') == 'true'){ ?>
			  	myOptions.scrollwheel = false;
			  	<?php } ?>
			  	var map = new google.maps.Map(document.getElementById("single_map_canvas"),  myOptions);

				<?php if($mode == 'directions'){ ?>
			  	directionsPanel = document.getElementById("featured-route");
 				directions = new GDirections(map, directionsPanel);
  				directions.load("from: <?php echo $from; ?> to: <?php echo $to; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);
			  	<?php
			 	} else { ?>

			  		var point = new google.maps.LatLng(<?php echo $geocoords; ?>);
	  				var root = "<?php echo esc_url( get_template_directory_uri() ); ?>";
	  				var callout = '<?php echo preg_replace("/[\n\r]/","<br/>",get_option('woo_maps_callout_text')); ?>';
	  				var the_link = '<?php echo get_permalink(get_the_id()); ?>';
	  				<?php $title = str_replace(array('&#8220;','&#8221;'),'"', $marker_title); ?>
	  				<?php $title = str_replace('&#8211;','-',$title); ?>
	  				<?php $title = str_replace('&#8217;',"`",$title); ?>
	  				<?php $title = str_replace('&#038;','&',$title); ?>
	  				var the_title = '<?php echo html_entity_decode($title) ?>';

	  			<?php
			 	if(is_page()){
			 		$custom = get_option('woo_cat_custom_marker_pages');
					if(!empty($custom)){
						$color = $custom;
					}
					else {
						$color = get_option('woo_cat_colors_pages');
						if (empty($color)) {
							$color = 'red';
						}
					}
			 	?>
			 		var color = '<?php echo $color; ?>';
			 		createMarker(map,point,root,the_link,the_title,color,callout);
			 	<?php } else { ?>
			 		var color = '<?php echo get_option('woo_cat_colors_pages'); ?>';
	  				createMarker(map,point,root,the_link,the_title,color,callout);
				<?php
				}
					if(isset($_POST['woo_maps_directions_search'])){ ?>

					directionsPanel = document.getElementById("featured-route");
 					directions = new GDirections(map, directionsPanel);
  					directions.load("from: <?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?> to: <?php echo $address; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);



					directionsDisplay = new google.maps.DirectionsRenderer();
					directionsDisplay.setMap(map);
    				directionsDisplay.setPanel(document.getElementById("featured-route"));

					<?php if($walking == 'on'){ ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.WALKING;
					<?php } else { ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.DRIVING;
					<?php } ?>
					var start = '<?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?>';
					var end = '<?php echo $address; ?>';
					var request = {
       					origin:start,
        				destination:end,
        				travelMode: travelmodesetting
    				};
    				directionsService.route(request, function(response, status) {
      					if (status == google.maps.DirectionsStatus.OK) {
        					directionsDisplay.setDirections(response);
      					}
      				});

  					<?php } ?>
				<?php } ?>
			<?php } ?>


			  }
			  function handleNoFlash(errorCode) {
				  if (errorCode == FLASH_UNAVAILABLE) {
					alert("Error: Flash doesn't appear to be supported by your browser");
					return;
				  }
				 }



		initialize();

		});
	jQuery(window).load(function(){

		var newHeight = jQuery('#featured-content').height();
		newHeight = newHeight - 5;
		if(newHeight > 300){
			jQuery('#single_map_canvas').height(newHeight);
		}

	});

	</script>

<?php
}
/*-----------------------------------------------------------------------------------*/
/* Featured Slider: Post Type */
/*-----------------------------------------------------------------------------------*/

// removed 

/*-----------------------------------------------------------------------------------*/
/* Featured Slider: Hook Into Content */
/*-----------------------------------------------------------------------------------*/

// removed 

/*-----------------------------------------------------------------------------------*/
/* Featured Slider: Get Slides */
/*-----------------------------------------------------------------------------------*/

// removed 

/*-----------------------------------------------------------------------------------*/
/* Is IE */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'is_ie' ) ) {
	function is_ie ( $version = '6.0' ) {
		$supported_versions = array( '6.0', '7.0', '8.0', '9.0' );
		$agent = substr( $_SERVER['HTTP_USER_AGENT'], 25, 4 );
		$current_version = substr( $_SERVER['HTTP_USER_AGENT'], 30, 3 );
		$response = false;
		if ( in_array( $version, $supported_versions ) && 'MSIE' == $agent && ( $version == $current_version ) ) {
			$response = true;
		}

		return $response;
	} // End is_ie()
}

/*-----------------------------------------------------------------------------------*/
/* Check if WooCommerce is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Truncate */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'justshop_truncate' ) ) {
	function justshop_truncate($text, $limit) {
		if (str_word_count($text, 0) > $limit) {
			$words = str_word_count($text, 2);
			$pos = array_keys($words);
			$text = strip_tags( $text );
			$text = substr($text, 0, $pos[$limit]) . '...';
		}
		return $text;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Infinite Scroll */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'justshop_infinite_scroll_js' ) ) {
	function justshop_infinite_scroll_js() {
		global $woo_options;
		if ( !isset( $woo_options['woocommerce_archives_infinite_scroll'] ))  $woo_options['woocommerce_archives_infinite_scroll'] = 'true';
	    if ( ( $woo_options['woocommerce_archives_infinite_scroll'] == 'true' ) && ( is_shop() || is_product_category() ) ) { ?>
	    <script>
	    if ( ! navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {
		    var infinite_scroll = {
		        loading: {
		            img: "<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif",
		            msgText: "<?php _e( 'Loading the next set of products...', 'templatation' ); ?>",
		            finishedMsg: "<?php _e( 'All products loaded.', 'templatation' ); ?>"
		        },
		        "nextSelector":".pagination a.next",
		        "navSelector":".pagination",
		        "itemSelector":"#main .product",
		        "contentSelector":"#main ul.products"
		    };
		    jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );
		}
	    </script>
	    <?php
	    }
	}
}
if (is_woocommerce_activated()) {
	add_action( 'wp_footer', 'justshop_infinite_scroll_js',100 );
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Portfolio Item (Portfolio Component) */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_add_portfolio' ) ) {
	function woo_add_portfolio() {
	
		global $woo_options;
	
		// "Portfolio Item" Custom Post Type
		$labels = array(
			'name' => _x( 'Portfolio', 'post type general name', 'templatation' ),
			'singular_name' => _x( 'Portfolio Item', 'post type singular name', 'templatation' ),
			'menu_name' => __( 'Cakes/Portfolio', 'templatation' ),
			'add_new' => _x( 'Add New', 'slide', 'templatation' ),
			'add_new_item' => __( 'Add New Portfolio Item', 'templatation' ),
			'edit_item' => __( 'Edit Portfolio Item', 'templatation' ),
			'new_item' => __( 'New Portfolio Item', 'templatation' ),
			'view_item' => __( 'View Portfolio Item', 'templatation' ),
			'search_items' => __( 'Search Portfolio Items', 'templatation' ),
			'not_found' =>  __( 'No portfolio items found', 'templatation' ),
			'not_found_in_trash' => __( 'No portfolio items found in Trash', 'templatation' ), 
			'parent_item_colon' => ''
		);
		
		$portfolioitems_rewrite = get_option( 'woo_portfolioitems_rewrite' );
 		if( empty( $portfolioitems_rewrite ) ) { $portfolioitems_rewrite = 'portfolio-items'; }
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => $portfolioitems_rewrite ),
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_icon' => get_template_directory_uri() .'/includes/images/portfolio.png',
			'menu_position' => null, 
			'has_archive' => true, 
			'taxonomies' => array( 'portfolio-gallery','post_tag' ), 
			'supports' => array( 'title','editor','thumbnail', 'comments')
		);
		
		if ( isset( $woo_options['woo_portfolio_excludesearch'] ) && ( $woo_options['woo_portfolio_excludesearch'] == 'true' ) ) {
			$args['exclude_from_search'] = true;
		}
		
		register_post_type( 'portfolio', $args );
		
		// "Portfolio Galleries" Custom Taxonomy
		$labels = array(
			'name' => _x( 'Portfolio Galleries', 'taxonomy general name', 'templatation' ),
			'singular_name' => _x( 'Portfolio Gallery', 'taxonomy singular name', 'templatation' ),
			'search_items' =>  __( 'Search Portfolio Galleries', 'templatation' ),
			'all_items' => __( 'All Portfolio Galleries', 'templatation' ),
			'parent_item' => __( 'Parent Portfolio Gallery', 'templatation' ),
			'parent_item_colon' => __( 'Parent Portfolio Gallery:', 'templatation' ),
			'edit_item' => __( 'Edit Portfolio Gallery', 'templatation' ), 
			'update_item' => __( 'Update Portfolio Gallery', 'templatation' ),
			'add_new_item' => __( 'Add New Portfolio Gallery', 'templatation' ),
			'new_item_name' => __( 'New Portfolio Gallery Name', 'templatation' ),
			'menu_name' => __( 'Portfolio Galleries', 'templatation' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'portfolio-gallery' )
		);
		
		register_taxonomy( 'portfolio-gallery', array( 'portfolio' ), $args );
	}
	
	add_action( 'init', 'woo_add_portfolio' );
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Navigation */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_portfolio_navigation' ) ) {
	function woo_portfolio_navigation ( $galleries, $settings = array(), $toggle_pagination = false ) {

		// Sanity check.
		if ( ! is_array( $galleries ) || ( count( $galleries ) <= 0 ) ) { return; }
		
		global $woo_options, $wp_query;
		
		$defaults = array(
						'id' => 'port-tags', 
						'label' => '', 
						'display_all' => true, 
						'current' => 'all'
						 );
		
		$settings = wp_parse_args( $settings, $defaults );
					 
		$settings = apply_filters( 'woo_portfolio_navigation_args', $settings );
		
		// Prepare the anchor tags of the various gallery items.
		$gallery_anchors = '';
		foreach ( $galleries as $g ) {
			$current_class = '';

			if ( $settings['current'] == $g->term_id ) {
				$current_class = ' current';
			}

$permalink = '#' . $g->slug;
if ( is_tax() || $toggle_pagination == true ) {
	$permalink = get_term_link( $g, 'portfolio-gallery' );
}

$gallery_anchors .= '<li><a href="' . $permalink . '" rel="' . $g->slug . '" data-option-value=".' . $g->slug . '" class="navigation-slug-' . $g->slug . ' navigation-id-' . $g->term_id . $current_class . '">' . $g->name . '</a></li>' . "\n";
		}
		
		$html = '<div id="' . $settings['id'] . '" class="port-tags">' . "\n";
				$html .= '<ul class="port-cat">' . "\n";
				
				// Display label, if one is set.
				if ( $settings['label'] != '' ) { $html .= $settings['label'] . ' '; }
				
				// Display "All", if set to "true".
				if ( $settings['display_all'] == 'all' ) {
					$all_permalink = '#';
					if ( is_tax() || $toggle_pagination == true ) {
						$all_permalink = get_post_type_archive_link( 'portfolio' );
					}
					
					$all_current = '';
					if ( $settings['current'] == 'all' ) {
						$all_current = ' class="current"';
					}
					$html .= '<li ' . $all_current . '><a href="' . $all_permalink . '" data-option-value="*" rel="all">' . __( 'All','templatation' ) . '</a></li> ';
				}
				
				// Add the gallery anchors in.
				$html .= $gallery_anchors;
				
				$html .= '</ul>' . "\n";
			$html .= '<div class="fix"></div>' . "\n";
		$html .= '</div><!--/#' . $settings['id'] . ' .port-tags-->' . "\n";
		
		
		$html = apply_filters( 'woo_portfolio_navigation', $html );
		
		echo $html;
	
	} // End woo_portfolio_navigation()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Item Settings */
/* @uses woo_portfolio_image_dimensions() */
/*-----------------------------------------------------------------------------------*/
 
if ( !function_exists( 'woo_portfolio_item_settings' ) ) {
	function woo_portfolio_item_settings ( $id ) {
		
		global $woo_options;
		
		// Sanity check.
		if ( ! is_numeric( $id ) ) { return; }
		
		$website_layout = 'two-col-left';
		$website_width = '900px';
		
		if ( isset( $woo_options['woo_layout'] ) ) { $website_layout = $woo_options['woo_layout']; }
		if ( isset( $woo_options['woo_layout_width'] ) ) { $website_width = $woo_options['woo_layout_width']; }
		
		$dimensions = woo_portfolio_image_dimensions( $website_layout, $website_width );
		
		$width = $dimensions['width'];
		$height = $dimensions['height'];
		
		
		$settings = array(
							'large' => '', 
							'caption' => '', 
							'rel' => '', 
							'gallery' => array(), 
							'css_classes' => 'group post portfolio-img', 
							'embed' => '', 
							'testimonial' => '', 
							'testimonial_author' => '', 
							'display_url' => '', 
							'width' => $width, 
							'height' => $height
						 );
		
		$meta = get_post_custom( $id );
		
		// Check if there is a gallery in post.
		// woo_get_post_images is offset by 1 by default. Setting to offset by 0 to show all images.
		
		$large = '';
		if ( isset( $meta['portfolio-image'][0] ) )
			$large = $meta['portfolio-image'][0];
			
		$caption = '';
			    
		$rel = 'rel="lightbox['. $id .']"';

		// Check if there are more than 1 image
    	$gallery = woo_get_post_images( '0' );
    	
	    // If we only have one image, disable the gallery functionality.
	    if ( isset( $gallery ) && is_array( $gallery ) && ( count( $gallery ) <= 1 ) ) {
			$rel = 'data-rel="lightbox"';
	    }
	    
	    // Check for a post thumbnail, if support for it is enabled.
	    if ( isset( $woo_options['woo_post_image_support'] ) && ( $woo_options['woo_post_image_support'] == 'true' ) && current_theme_supports( 'post-thumbnails' ) ) {
	    	$image_id = get_post_thumbnail_id( $id );
	    	if ( intval( $image_id ) > 0 ) {
	    		$large_data = wp_get_attachment_image_src( $image_id, 'large' );
	    		if ( is_array( $large_data ) ) {
	    			$large = $large_data[0];
	    		}
	    	}
	    }
	    
	    // See if lightbox-url custom field has a value
	    if ( isset( $meta['lightbox-url'] ) && ( $meta['lightbox-url'][0] != '' ) ) {
	    	$large = $meta['lightbox-url'][0];
	    }
	    		
		// Create CSS classes string.
		$css = '';
		$galleries = array();
		$terms = get_the_terms( $id, 'portfolio-gallery' );
		if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) { foreach ( $terms as $t ) { $galleries[] = $t->slug; } }				
		$css = join( ' ', $galleries );
		
		// If on the single item screen, check for a video.
		if ( is_singular() ) { $settings['embed'] = woo_embed( 'width=540' ); }
		
		// Add testimonial information.
		if ( isset( $meta['testimonial'] ) && ( $meta['testimonial'][0] != '' ) ) {
			$settings['testimonial'] = $meta['testimonial'][0];
		}
		
		if ( isset( $meta['testimonial_author'] ) && ( $meta['testimonial_author'][0] != '' ) ) {
			$settings['testimonial_author'] = $meta['testimonial_author'][0];
		}
		
		// Look for a custom display URL of the portfolio item (used if it's a website, for example)
		if ( isset( $meta['url'] ) && ( $meta['url'][0] != '' ) ) {
			$settings['display_url'] = $meta['url'][0];
		}
		
		// Assign the values we have to our array.
		$settings['large'] = $large;
		$settings['caption'] = $caption;
		$settings['rel'] = $rel;
		if (isset( $gallery )) { $settings['gallery'] = $gallery; } else { $settings['gallery'] = array(); }
		$settings['css_classes'] .= ' ' . $css;
				
		// Check for a custom description.
		$description = get_post_meta( $id, 'lightbox-description', true );
		if ( $description != ''  ) { $settings['caption'] = $description; }
		
		// Allow child themes/plugins to filter these settings.
		$settings = apply_filters( 'woo_portfolio_item_settings', $settings, $id );
		
		return $settings;
	} // End woo_portfolio_item_settings()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, show portfolio galleries in portfolio item breadcrumbs */
/* Modify woo_breadcrumbs() Arguments Specific to this Theme */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_breadcrumbs_args', 'woo_portfolio_filter_breadcrumbs_args', 10 );

if ( !function_exists( 'woo_portfolio_filter_breadcrumbs_args' ) ) {
	function woo_portfolio_filter_breadcrumbs_args( $args ) {
	
		$args['singular_portfolio_taxonomy'] = 'portfolio-gallery';
	
		return $args;
	
	} // End woo_portfolio_filter_breadcrumbs_args()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, get image dimensions based on layout and website width settings. */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'woo_portfolio_image_dimensions' ) ) {
	function woo_portfolio_image_dimensions ( $layout = 'one-col', $width = '960' ) {
		
		$dimensions = array( 'width' => 575, 'height' => 0, 'thumb_width' => 175, 'thumb_height' => 175 );
		
		// Allow child themes/plugins to filter these dimensions.
		$dimensinos = apply_filters( 'woo_portfolio_gallery_dimensions', $dimensions );
	
		return $dimensions;
	
	} // End woo_post_gallery_dimensions()
}

/*-----------------------------------------------------------------------------------*/
/* Get Post image attachments */
/*-----------------------------------------------------------------------------------*/
/* 
Description:

This function will get all the attached post images that have been uploaded via the 
WP post image upload and return them in an array. 

*/
if ( !function_exists( 'woo_get_post_images' ) ) {
	function woo_get_post_images( $offset = 1, $size = 'large' ) {
		
		// Arguments
		$repeat = 100; 				// Number of maximum attachments to get 
		$photo_size = 'large';		// The WP "size" to use for the large image
	
		global $post;
	
		$output = array();
	
		$id = get_the_id();
		$attachments = get_children( array(
		'post_parent' => $id,
		'numberposts' => $repeat,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'order' => 'ASC', 
		'orderby' => 'menu_order date' )
		);
		if ( !empty($attachments) ) :
			$output = array();
			$count = 0;
			foreach ( $attachments as $att_id => $attachment ) {
				$count++;  
				if ($count <= $offset) continue;
				$url = wp_get_attachment_image_src($att_id, $photo_size, true);	
					$output[] = array( 'url' => $url[0], 'caption' => $attachment->post_excerpt, 'id' => $att_id );
			}  
		endif; 
		return $output;
	} // End woo_get_post_images()
}

/**
 * woo_portfolio_add_post_classes function.
 * 
 * @access public
 * @param array $classes
 * @return array $classes
 */

add_filter( 'post_class', 'woo_portfolio_add_post_classes', 10 );
 
function woo_portfolio_add_post_classes ( $classes ) {
	if ( in_array( 'portfolio', $classes ) ) {
		global $post;
		
		$terms = get_the_terms( $post->ID, 'portfolio-gallery' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$classes[] = $t->slug;
			}
		}
		
		if ( ! is_singular() ) {
			foreach ( $classes as $k => $v ) {
				if ( in_array( $v, array( 'hentry', 'portfolio' ) ) ) {
					unset( $classes[$k] );
				}
			}
		}
	}
	return $classes;
} // End woo_portfolio_add_post_classes()

/*-----------------------------------------------------------------------------------*/
/* Portfolio Meta */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_portfolio_meta')) {
	function woo_portfolio_meta( ) {
?>
<aside class="portfolio-meta">
	<ul>
		<li class="portfolio-date">
			<?php the_time( get_option( 'date_format' ) ); ?>
		</li>
		<li class="portfolio-comments">
			<?php comments_popup_link( __( 'Leave a comment', 'templatation' ), __( '1 Comment', 'templatation' ), __( '% Comments', 'templatation' ), 'button' ); ?>
		</li>
		<li><?php edit_post_link( __( '{ Edit }', 'templatation' ), '<li class="edit">', '</li>' ); ?></li>
	</ul>
</aside>
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* @ Justshop custom function from below ... */
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Fixing the font size for the tag cloud widget.                                    */
/*-----------------------------------------------------------------------------------*/
add_filter( 'widget_tag_cloud_args', 'my_tag_cloud_args' );
if (!function_exists( 'my_tag_cloud_args')) {
	function my_tag_cloud_args($in) {
	return 'smallest=12&largest=12&number=25&orderby=name&unit=px';
}
}

/*-----------------------------------------------------------------------------------*/
/* Hooking class to <header to build the required header layout.                     */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'jc_hdr_class')) {
	function jc_hdr_class() {
		global $woo_options;
		if ( !isset( $woo_options['woo_header_section_layout'] ) || $woo_options['woo_header_section_layout'] == 'layout2' ) { 
		$hdrclass = "b";
		}
		elseif( $woo_options['woo_header_section_layout'] == 'layout3' ) $hdrclass = "c";
		elseif( $woo_options['woo_header_section_layout'] == 'layout4' ) $hdrclass = "d";
		elseif( $woo_options['woo_header_section_layout'] == 'layout5' ) $hdrclass = "e";
		elseif( $woo_options['woo_header_section_layout'] == 'layout6' ) $hdrclass = "f";
		elseif( $woo_options['woo_header_section_layout'] == 'layout7' ) $hdrclass = "f h";
		//elseif( $woo_options['woo_header_section_layout'] == 'layout8' ) $hdrclass = "e g";
		else  $hdrclass = "d";
		return $hdrclass;
}
}

/*-----------------------------------------------------------------------------------*/
/* Add a class to body_class if fullwidth slider to appear. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class','justshop_body_class', 10 );// Add layout to body_class output

if ( ! function_exists( 'justshop_body_class' ) ) {
function justshop_body_class( $classes ) {

	global $woo_options, $wp_query;
	$tt_post_id = $current_page_template = $hdr_layout = '';
	//setting up defaults.
	$settings6 = woo_get_dynamic_values( array( 'sticky_menu' => 'false',
												'widescreen' => 'false',
												'headline_gmap' => 'true',
												'jsanim_no' => 'true',
												'trans_lay4' => 'false',
												'no_cont_shadow' => 'false'
												) );
	if ( !is_404() && !is_search() ) {
		if ( ! empty( $wp_query->post->ID ) ) {
			$tt_post_id = $wp_query->post->ID;
		}
	}
	// fetching which page template is being used to render current post/page.
	if ( !empty($tt_post_id) ) { $current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true); }
	if ( !is_search() && ($current_page_template == "template-fullwidth.php" )) { $classes[] = 'layout-full'; }
	if ( is_page() && ! is_front_page() && ($current_page_template == "template-home.php" )) { $classes[] = 'home'; } // force .home class in body if custom-home template is used (even if its not set as homepage), to trigger the styles written for homepage only.
	if ( $settings6['widescreen'] == "true" ) $classes[] = 'widescreen'; else $classes[] = 'boxed';
	if ( $settings6['sticky_menu'] == "true" ) { $classes[] = 'sticky-menu'; }
	if ( $settings6['jsanim_no'] == "true" ) { $classes[] = 'jsanim_no'; }
	if ( $settings6['trans_lay4'] == "true" ) { $classes[] = 'trans_layout4'; }
	if ( $settings6['no_cont_shadow'] == "true" ) { $classes[] = 'no_shadow'; }


	if ( isset( $woo_options['woo_header_section_layout'] ) ) { 
		$hdr_layout = $woo_options['woo_header_section_layout'];
		if ( $hdr_layout == 'layout5' || $hdr_layout == 'layout6' )  $hdr_layout = 'layout2';
		// Add classes to body_class() output 
		$classes[] = 'structure-'.$hdr_layout;
	}
	else $classes[] = 'structure-layout2'; // if layout not set from admin, trigger default as layout2
	return $classes;
  }
}

/*-----------------------------------------------------------------------------------*/
/* Function for setting up slider container									.        */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'justshop_slider_cont')) {
	function justshop_slider_cont() {
		global $woo_options;
		// Enable slider
		// Show slider only on homepage
		// Which slider layout to choose out of Full width(Layout 2), Fixed width(Layout 3), Full width 2(Layout 4)...etc
		
		if ( !isset( $woo_options['woo_enable_slider'] ) || 'false' == $woo_options['woo_enable_slider'] ) { return; } // return if not enabled.

		if ( !isset( $woo_options['woo_header_section_layout'] ) || $woo_options['woo_header_section_layout'] == 'layout2' || $woo_options['woo_header_section_layout'] == 'layout3' || $woo_options['woo_header_section_layout'] == 'layout5' || $woo_options['woo_header_section_layout'] == 'layout6' ) { 
		add_action( 'woo_content_before', 'justshop_slider', 0 ); // hook slider after header container
		}
		else { 
		add_action( 'woo_nav_after', 'justshop_slider', 0 ); // hook slider inside header container
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/*  function for rendering slider 													 */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'justshop_slider' ) ) {
function justshop_slider() {
	global $woo_options, $wp_query; $tt_post_id = $single_page_slider = $main_slider_area = '';
	if ( is_404() || is_search() ) return;
	if ( !empty($wp_query->post->ID) ) $tt_post_id = $wp_query->post->ID;
	$fullyes = $hdr_section_layout = '';
	
	// term homepage refers to template-home.php page template in this function, not index page.
	
	// fetching which page template is being used to render current post/page.
	if ( empty($tt_post_id) ) $current_page_template = 'no-home'; else $current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true);

	// fetching which layout is set in admin , if not setup , layout2 is default.
	if ( isset( $woo_options['woo_header_section_layout'] ) ) $hdr_section_layout = $woo_options['woo_header_section_layout']; else $hdr_section_layout = 'layout2';
	if ( $hdr_section_layout == 'layout5' || $hdr_section_layout == 'layout6' )  $hdr_section_layout = 'layout2';
	
	if( !isset($woo_options['woo_slider_whole_site']) ) $slider_whole_site = 'false'; else  $slider_whole_site = $woo_options['woo_slider_whole_site'];// setup default, show slider only on homepage.

	// fetching value from single posts .
	$single_page_slider = get_post_meta($tt_post_id, '_single_page_slider', true);

	if ( ! empty( $single_page_slider ) ) { $main_slider_area = $single_page_slider ; } // slider content from single page meta, if exists.
	if ( empty( $single_page_slider ) && (($slider_whole_site == 'true') || ($current_page_template == "template-home.php")) ) { $main_slider_area = $woo_options['woo_slider_area_content']; } // if single post meta is blank, show default slider content from admin if enabled for whole website, or if its homepage.
	if ( empty( $main_slider_area ) )  return; // show nothing if disabled for whole website, nothing entered in single page and its not homepage
	
	echo '<section class="'.$tt_post_id.' col-full slider-'.$hdr_section_layout.'" id="main-slider">' . stripslashes( do_shortcode( $main_slider_area ) ) . "</section>\n";
}
}

/*-----------------------------------------------------------------------------------*/
/* Function for featured headline area inside main container.                        */
/*-----------------------------------------------------------------------------------*/
 if (!function_exists('templatation_headline_area')) {
	function templatation_headline_area() {
	global $woo_options, $wp_query;
	$tt_post_id = '';
	if ( is_404() || is_search() ) return;
	if( isset($wp_query->post->ID)) $tt_post_id = $wp_query->post->ID; // saving current object id so that we can fetch its own single heading section details via custom fields.

	// if the current page template is contact page.
	$current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true);
	if ( !empty($current_page_template) && $current_page_template == "template-contact.php" ) { // hook gmap in contact page
		$settings7 = woo_get_dynamic_values( array( 'headline_gmap' => 'true' ) );
		if ( $settings7['headline_gmap'] == 'true' ) { // hook gmap in headline section if set in admin
			add_action( 'woo_content_before', 'tt_headline_gmap', 21 );
			return; 
		}
	}

	// fetching value from theme options settings .
	if ( isset( $woo_options['woo_show_headline_global'] ) )  $show_headline_global = $woo_options['woo_show_headline_global']; else  $show_headline_global = 'true';
	if ( isset( $woo_options['woo_enable_default_headline'] ) )  $enable_default_headline = $woo_options['woo_enable_default_headline']; else  $enable_default_headline = 'true';
	if ( isset( $woo_options['woo_headline_default_title'] ) )  $headline_default_title = $woo_options['woo_headline_default_title']; else  $headline_default_title = 'Taste our yummy Cakes';
	if ( isset( $woo_options['woo_headline_default_message'] ) )  $headline_default_message = $woo_options['woo_headline_default_message']; else  $headline_default_message = 'All kind of cakes and pastries ready within minutes. Try once. Call xxx-x-xxx Today !';
	if ( isset( $woo_options['woo_header_section_layout'] ) ) $hdr_section_layout = $woo_options['woo_header_section_layout']; else  $hdr_section_layout = 'layout2';

	// fetching value from single posts .
	$single_disable_headline = get_post_meta($tt_post_id, '_single_disable_headline', true);
	$single_headline_heading = esc_attr(get_post_meta($tt_post_id, '_single_headline_heading', true));
	$single_headline_message = esc_textarea(get_post_meta($tt_post_id, '_single_headline_message', true));
	
	// setting defaults
	if ( !isset($single_disable_headline) || empty($single_disable_headline) ) $single_disable_headline = 'false';
	

	// return if globally disabled in admin and single page respectively but still show breadcrumb., no other setting make difference.
	if ( $show_headline_global == 'false') {
		if(isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true')  { ?>
		<?php woo_display_breadcrumbs(); ?>
	<?php } return; }

	if ( $single_disable_headline == 'true' ) { 
		if(isset( $woo_options['woo_breadcrumbs_show'] ) && ($woo_options['woo_breadcrumbs_show'] == 'true'))  { ?>
		<?php woo_display_breadcrumbs(); ?>
	<?php } return; } 


	ob_start(); ?>
	<section class="headline-<?php echo $tt_post_id; ?> headline-<?php echo $hdr_section_layout; ?> col-full" id="headline">
			<div class="headline-left-side">
				<?php if( !empty($single_headline_heading) ) echo "<h2>". stripslashes( $single_headline_heading ) ."</h2>"; 
				elseif( 'true' == $enable_default_headline ) echo "<h2>". stripslashes( $headline_default_title ) ."</h2>"; ?>

				<?php if( !empty($single_headline_message) ) echo '<div class="headline-message">'. stripslashes( $single_headline_message ) .'</div>'; 
				elseif( 'true' == $enable_default_headline ) echo '<div class="headline-message">'. stripslashes( $headline_default_message ) .'</div>'; ?>
				
				<?php if( ('false' == $enable_default_headline) && empty($single_headline_heading) ) $bcmp_class="no-headline-bcmp"; else $bcmp_class="headline-bcmp"; ?>
				<div class="<?php echo $bcmp_class; ?>"><?php woo_display_breadcrumbs(); ?></div>
			</div>
						
				<?php if ( is_woocommerce_activated() && ( isset($woo_options['woo_enable_catalog']) && 'false' == $woo_options['woo_enable_catalog'] ) ) {
						if( isset( $woo_options['woocommerce_header_cart_link'] ) && 'true' == $woo_options['woocommerce_header_cart_link'] ) {
							global $woocommerce;  ?>
							<div class="headline-right-side"><?php tt_mini_cart(); ?></div>
					<?php	}
					  } ?>

	</section>
	<?php
	$headline_content = ob_get_clean();
	echo $headline_content;
}  
}


/*-----------------------------------------------------------------------------------*/
/* Function for adding google map in headline section      .                        */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('tt_headline_gmap')) {
	function tt_headline_gmap() {
 	global $woo_options, $wp_query;
	$settings7 = $gmap = "";
	
	$settings7 = woo_get_dynamic_values( array( 'headline_gmap' => 'true' ) );
	if ( $settings7['headline_gmap'] == 'false' ) return ; // do nothing if we are not showing map in the headline section.
	ob_start(); 

	   if ( isset($woo_options['woo_contactform_map_coords']) && $woo_options['woo_contactform_map_coords'] != '' ) { $geocoords = $woo_options['woo_contactform_map_coords']; }  else { $geocoords = ''; } ?>
		<?php if ($geocoords != '') { ?>
		<?php woo_maps_contact_output("geocoords=$geocoords"); ?>
		<?php }
	
	$gmap = ob_get_clean();

	echo '<section class="col-full fix gmap" id="headline">' . $gmap . "</section>\n";

}
}

/*-----------------------------------------------------------------------------------*/
/* Function for showing google map inside container.                                 */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('templatation_gmap')) {
	function templatation_gmap() {
 	global $woo_options, $wp_query;
	$settings7 = $gmap = "";
	
	$settings7 = woo_get_dynamic_values( array( 'headline_gmap' => 'true' ) );
	if ( $settings7['headline_gmap'] == 'true' ) return ; // do nothing if we are showing map in the headline section.
	ob_start(); 

	   if ( isset($woo_options['woo_contactform_map_coords']) && $woo_options['woo_contactform_map_coords'] != '' ) { $geocoords = $woo_options['woo_contactform_map_coords']; }  else { $geocoords = ''; } ?>
		<?php if ($geocoords != '') { ?>
		<?php woo_maps_contact_output("geocoords=$geocoords"); ?>
		<div class="woo-sc-hr"></div>
		<?php }
	
	$gmap =  ob_get_clean();
	echo $gmap;
	
}
}

/*-----------------------------------------------------------------------------------*/
/* Callback function for contactwidget shortcode */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'js_contactwidget' ) ) {
function js_contactwidget($atts) {
		 $defaults = array(
		'name' => 'Just Shop', // name of the firm
		'telephone' => '000-000-000',
		'email' => 'email@someemail.com', 
		'address' => 'blah street, blah country' 
		);

		extract(shortcode_atts( $defaults, $atts));
		// building the widget details.
		ob_start(); ?>
		<div class="vcard">
			<h4 class="fn org"><?php echo $name; ?></h4>
			<p class="tel"><?php echo $telephone; ?></p>
			<p><a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
			<p class="adr"><?php echo $address; ?></p>
		</div>
		<?php $contactwidget = ob_get_clean();
		return $contactwidget;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Logo offset fuction. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_logo_offset' ) ) {
/**
 * Output CSS for logo adjustments
 */
function templatation_logo_offset() {
	global $woo_options;
    $outputlogostyle = $top = $topactive = $marginleft = '';
	//setting up defaults.
	$settings10 = array(
					'header_section_layout' => 'layout2',
					'logo_left_offset' => '0',
					'logo_top_offset' => '0'
					);

	$settings10 = woo_get_dynamic_values( $settings10 );
	$logo_right_offset = $logo_top_offset = "0"; // setting up default
	if( !empty($settings10['logo_left_offset']) ) $logo_right_offset = $settings10['logo_left_offset'];
	if( !empty($settings10['logo_top_offset']) ) $logo_top_offset = $settings10['logo_top_offset'];

	if( ( $logo_right_offset == "0" ) && ( $logo_top_offset == "0" ) ) return; // do nothing if 0,0 is entered == reset.

	if( $settings10['header_section_layout'] == 'layout5' ) {
		$marginleft = -110-(-$logo_right_offset);
		$top = 10-(-$logo_top_offset);
		$topactive = 50-(-$logo_top_offset);
		if( $logo_right_offset <> "0" ) $outputlogostyle .= '#header.e #logo { margin-left: '. $marginleft .'px; }' . "\n"; // setting up left offset
		if( $logo_top_offset <> "0" ) $outputlogostyle .= '#header.e #logo { top: '. $top .'px; }' . ' #header.e.active #logo { top: '. $topactive .'px; }' . '\n';  // setting up top offset
	}
	elseif ( $settings10['header_section_layout'] == 'layout6' ) {
		$marginleft = -483-(-$logo_right_offset);
		$top = $logo_top_offset;
		//$topactive = 60-(-$logo_top_offset);
		if( $logo_right_offset <> "0" ) $outputlogostyle .= '#header.f #logo { margin-left: '. $marginleft .'px; }' . "\n"; // setting up left offset
//		if( $logo_top_offset <> "0" ) $outputlogostyle .= '#header.f #logo { top: '. $top .'px; }' . ' #header.f.active #logo { top: '. $topactive .'px; }' . '\n';  // setting up top offset @v3.5, removed .active class
		if( $logo_top_offset <> "0" ) $outputlogostyle .= '#header.f #logo { top: '. $top .'px; }' . '\n';  // setting up top offset
	}
	else {
		$marginleft = -483-(-$logo_right_offset);
		$top = 21-(-$logo_top_offset);
		$topactive = 60-(-$logo_top_offset);
		if( $logo_right_offset <> "0" ) $outputlogostyle .= '#header #logo { margin-left: '. $marginleft .'px; }' . "\n"; // setting up left offset
		if( $logo_top_offset <> "0" ) $outputlogostyle .= '#header #logo, #header.d #logo { top: '. $top .'px; }' . ' #header.active #logo { top: '. $topactive .'px; }' . '\n';  // setting up top offset
	}
	

	// Output styles
	if ( $outputlogostyle != '' ) {
		$outputlogostyle = strip_tags($outputlogostyle);
		echo '<!-- Logo offset Custom CSS -->' . "\n";
		$outputlogostyle = "<style type=\"text/css\">\n" . $outputlogostyle . "</style>\n\n";
		echo stripslashes( $outputlogostyle );
	}
} // End templatation_logo_offset()
}

/*-----------------------------------------------------------------------------------*/
/* Header Height fuction. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_header_height' ) ) {
function templatation_header_height() {
	global $woo_options, $wp_query;
	$tt_post_id = $current_page_template = '';
	if( !is_404() && !is_search() ) {
		if ( isset( $wp_query->post->ID ) ) {
			$tt_post_id = $wp_query->post->ID;
		} // saving current object id so that we can fetch its own single heading section details via custom fields.
	}
    $outputheaderstyle = $headerstyle = $maps_single_height = '';

	//setting up defaults.
	$settings10 = array(
					'headline_gmap' => 'true',
					'maps_single_height' => '350',
					'header_height' => '',
					);
	$settings10 = woo_get_dynamic_values( $settings10 );
	if ( !empty($tt_post_id) ) { $current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true); }

	if ( !empty($current_page_template) ) { 
		if( $current_page_template == "template-contact.php" && $settings10['headline_gmap'] == "true" ) {
			 $outputheaderstyle .= '.page-template-template-contact-php #headline { padding: 0px; }' . "\n"; // Headline heading top padding
			 $outputheaderstyle .= '.widescreen.page-template-template-contact-php #content { margin-top: 0px; }' . "\n"; // Headline heading top padding
			 $outputheaderstyle .= '.boxed.page-template-template-contact-php #headline.gmap { border-bottom: 4px solid #FFFFFF;  box-shadow: 0 1px 3px #cdcdcd; -moz-box-shadow: 0 1px 3px #cdcdcd; -webkit-box-shadow: 0 1px 3px #cdcdcd; }' . "\n"; // Headline heading top padding
		}
	} // add styles for contact us page if map is set to be shown in headline section.

	$headerstyle = $settings10['header_height'];
	if ( !empty($headerstyle) ) 
	$outputheaderstyle .= '#header.TThdr { min-height: '. $headerstyle .'px !important; }' . "\n"; // setting up header height overriding everything else
	
	// Output styles
	if ( $outputheaderstyle != '' ) {
		$outputheaderstyle = strip_tags($outputheaderstyle);
		echo '<!-- Header offset Custom CSS -->' . "\n";
		$outputheaderstyle = "<style type=\"text/css\">\n" . $outputheaderstyle . "</style>\n\n";
		echo stripslashes( $outputheaderstyle );
	}
} // End templatation_logo_offset()
}

/*-----------------------------------------------------------------------------------*/
/* Sidenav function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_sidenav' ) ) {
function templatation_sidenav ( $atts, $content = null ) {
	$defaults = array( 'style' => 'default' );

	extract( shortcode_atts( $defaults, $atts ) );

	return '<aside class="cols-c"><nav class="nav-a ' . esc_attr( $style ) . '">' . do_shortcode( $content ) . '</nav></aside>' . "\n";
} // End templatation_sidenav()
}

/*-----------------------------------------------------------------------------------*/
/* Sidenav function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_sidenav_content' ) ) {
function templatation_sidenav_content ( $atts, $content = null ) {
	$defaults = array( 'style' => 'default' );

	extract( shortcode_atts( $defaults, $atts ) );

	return '<div class="sidenav-right ' . esc_attr( $style ) . '">' . do_shortcode( $content ) . '</div>';
} // End templatation_sidenav()
}

/*-----------------------------------------------------------------------------------*/
/* Allowed tags for html sanitizing */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'tt_custom_allowedtags' ) ) {
	function tt_custom_allowedtags() {
		$tt_allowedtags = '';
		$tt_allowedtags = array(
			'a'      => array(
				'href'  => array(),
				'title' => array()
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
		);

		return $tt_allowedtags;
	} // End tt_custom_allowedtags()
}

/*-----------------------------------------------------------------------------------*/
/* Topnav function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_topnav_content' ) ) {
function templatation_topnav_content ( $atts, $content = null ) {
	global $woo_options, $current_user;
	if ( function_exists( 'woocommerce_get_page_id' ) ) { $url_myaccount = get_permalink( woocommerce_get_page_id( 'myaccount' ) ); }
	$output =  "";
	$topsettings = array(
					'enable_topbar' => 'true',
					'enable_showhide' => 'true',
					'enable_ttext' => 'true',
					'ttext_icon' => '',
					'ttext_text' => '',
					'welcome_text_icon' => '',
					'enable_top_social' => 'true',
					'enable_lang_dropdown' => 'false',
					'enable_top_search' => 'true',
					'connect_rss' => '',
					'connect_twitter' => '',
					'connect_facebook' => '',
					'connect_youtube' => '',
					'connect_flickr' => '',
					'connect_linkedin' => '',
					'connect_pinterest' => '',
					'connect_instagram' => '',
					'connect_rss' => '',
					'connect_googleplus' => '',
					'feed_url' => ''
					);

	$topsettings = woo_get_dynamic_values( $topsettings );
	if ( $topsettings['enable_topbar'] == "false" ) return; // do nothing if nav bar is disabled.
	ob_start();
?>
		<nav id="tools" class="<?php if ( $topsettings['enable_showhide'] == "true" ) echo "oc"; else echo "no-oc"; ?>">
			<div id="connect" class="col-inner">
				<ul>
					<!-- Start teaser text block. -->
					<?php if ( $topsettings['enable_ttext'] == "true" ) { ?>
						<li class="c">
						<?php if ( !empty($topsettings['ttext_icon'] )) { ?>
						<img src="<?php echo $topsettings['ttext_icon']; ?>" alt="" />
						<?php } ?>

						<?php if ( $topsettings['ttext_text'] != '' ) {
						 echo stripslashes( $topsettings['ttext_text'] );
						 } ?>
						</li>
					<?php } ?>

					<!-- Start login/welcome text block. -->
					<?php if ( is_woocommerce_activated() ) { ?>
					<?php if ( woocommerce_get_page_id( 'myaccount' ) !== -1 ) { ?>
						<?php if ( !empty($topsettings['welcome_text_icon'] )) { ?>
							<li class="b">
							<?php if ( is_user_logged_in() ) { 
									echo get_avatar( get_current_user_id() );
								  } else { ?>
									<img src="<?php echo $topsettings['welcome_text_icon']; ?>" alt="" />
							<?php  } ?>
							</li>
						<?php } ?>
						<?php } ?>
						<?php if ( ! is_user_logged_in() && woocommerce_get_page_id( 'myaccount' ) !== -1 ) { ?>
							<li class="a"><a href="<?php echo $url_myaccount; ?>"><?php _e( 'Login / Register', 'templatation' ); ?></a></li>
						<?php } if ( is_user_logged_in() ) { ?>
							<li class="a"><?php _e( 'Welcome', 'templatation' ); echo " ".ucfirst($current_user->display_name)." | "; ?> <a href="<?php echo $url_myaccount; ?>"><?php _e( 'My Account', 'templatation' ); ?></a> | <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Logout"><?php _e( 'Logout ', 'templatation' ); ?></a></li>
						<?php } ?>
					<?php } ?>
				</ul>

				<!-- Start login/welcome text block. -->
				<?php if ( $topsettings['enable_top_social'] == "true" ) { ?>
					<ul class="social">
						<?php if ( !empty( $topsettings['connect_twitter'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_twitter'] ); ?>" class="twitter" title="Twitter"></a></li>
						<?php } if ( !empty( $topsettings['connect_facebook'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_facebook'] ); ?>" class="facebook" title="Facebook"></a></li>
						<?php } if ( !empty( $topsettings['connect_youtube'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_youtube'] ); ?>" class="youtube" title="YouTube"></a></li>
						<?php } if ( $topsettings['connect_rss' ] == "true" ) { ?>
						<li><a href="<?php if ( $topsettings['feed_url'] ) { echo esc_url( $topsettings['feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="RSS"></a></li>
						<?php } if ( !empty( $topsettings['connect_flickr'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_flickr'] ); ?>" class="flickr" title="Flickr"></a></li>
						<?php } if ( !empty( $topsettings['connect_linkedin'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_linkedin'] ); ?>" class="linkedin" title="LinkedIn"></a></li>
						<?php } if ( !empty( $topsettings['connect_pinterest'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_pinterest'] ); ?>" class="pinterest" title="Pinterest"></a></li>
						<?php } if ( !empty( $topsettings['connect_instagram'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_instagram'] ); ?>" class="instagram" title="Instagram"></a></li>
						<?php } if ( !empty( $topsettings['connect_googleplus'] ) ) { ?>
						<li><a href="<?php echo esc_url( $topsettings['connect_googleplus'] ); ?>" class="googleplus" title="Google+"></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
 
				<!-- Start language block. -->
				<?php if ( $topsettings['enable_lang_dropdown'] == "true" ) { ?>
					<div id="language">
						<?php do_action('icl_language_selector'); ?>
					</div>
				<?php } ?>

				<!-- Start top search block. -->
				<?php if ( $topsettings['enable_top_search'] == "true" ) { ?>
					<?php if ( is_woocommerce_activated() ) { get_product_search_form(); } else { get_search_form(); } ?>
				<?php } ?>
			</div>
		</nav>
<?php
	$output = ob_get_clean();
	echo $output;
} // End templatation_topnav_content()
}

/*-----------------------------------------------------------------------------------*/
/* Function to disable loading icon                                                  */
/*-----------------------------------------------------------------------------------*/
add_action('woo_header_before', 'templatation_loadicon');
if ( ! function_exists( 'templatation_loadicon' ) ) {
function templatation_loadicon ( ) {
	$settings11 = '';
	$settings11 = woo_get_dynamic_values( array( 'disable_loadicon' => 'false' ) );
	if ( $settings11['disable_loadicon'] == 'false' ) {
	echo '
		<div id="loader-wrapper">
			<div id="loader"></div>
			<div class="loader-section"></div>
		</div>';
	}
} // End templatation_loadicon()
}


/*-----------------------------------------------------------------------------------*/
/* Headline shortcode */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_headline' ) ) {
function templatation_headline ( $atts, $content = null ) {
	$defaults = array( 'title' => '', 'headstyle' => 'HDborder' );
	$title = $headstyle = "";
	extract( shortcode_atts( $defaults, $atts ) );
	return '<h3 class="'. $headstyle .'">' .$title. '</h3>';
} // End templatation_headline()
}

/*-----------------------------------------------------------------------------------*/
/* Latest products [TT-latestproducts] Shortcode function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_latestproducts' ) ) {
function templatation_latestproducts ( $atts, $content = null ) {
	$defaults = array( 'title' => 'Latest Products' );
	// we do not need title here as included part file already include title defined in theme options
	extract( shortcode_atts( $defaults, $atts ) );
	$latestprods = "";
	if ( ! is_woocommerce_activated() ) return;

	ob_start(); ?>
		<div class="woocommerce woocommerce-wrap woocommerce-columns-4 home-featured">
		<div class="widget">
		<h2><?php echo $title; ?></h2>					
		<?php  echo do_shortcode( '[recent_products per_page="4" columns="4" orderby="date" order="desc"]' );
		 ?>
		</div><!--/.widget-->
		</div><!--/.woocommerce-->
<?php
	$latestprods = ob_get_clean();
	return $latestprods;
} // End templatation_latestproducts()
}

/*-----------------------------------------------------------------------------------*/
/* Featured products [TT-featuredproducts] Shortcode function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_featuredproducts' ) ) {
function templatation_featuredproducts ( $atts, $content = null ) {
	$defaults = array( 'title' => 'Featured Products' );
	// we do not need title here as included part file already include title defined in theme options
	extract( shortcode_atts( $defaults, $atts ) );
	if ( ! is_woocommerce_activated() ) return;

	$featuredprods = "";
	ob_start(); ?>
		<div class="woocommerce woocommerce-wrap woocommerce-columns-4 home-featured">
			<h2><?php echo $title; ?></h2>					
		<?php  the_widget( 'Woo_Featured_Products' ); ?>
		</div><!--/.woocommerce-->
<?php
	$featuredprods = ob_get_clean();
	return $featuredprods;
} // End templatation_featuredproducts()
}

/*-----------------------------------------------------------------------------------*/
/* Carousel products [TT-carouselproducts] Shortcode function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_carouselproducts' ) ) {
function templatation_carouselproducts ( $atts, $content = null ) {
	$defaults = array( 'title' => '' );
	// we do not need title here as included part file already include title defined in theme options
	extract( shortcode_atts( $defaults, $atts ) );
	if ( ! is_woocommerce_activated() ) return;
	$carouselproducts = "";
	ob_start();
	get_template_part( 'includes/products-carousel' );
	$carouselproducts = ob_get_clean();
	return $carouselproducts;
} // End templatation_carouselproducts()
}

/*-----------------------------------------------------------------------------------*/
/* extending woocommerce's [product_categories] SC [TT-productcategories] Shortcode function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_productcategories' ) ) {
function templatation_productcategories ( $atts, $content = null ) {
	$defaults = array( 'number' => '3',
					   'orderby' => 'name',
					   'order' => 'ASC',
					   'columns' => '3',
					   'hide_empty' => '1',
					   'parent' => '',
					   'ids' => ''
					    );
	// we do not need title here as user can put it via visual composer 
	extract( shortcode_atts( $defaults, $atts ) );
	$output = "";
	if ( ! is_woocommerce_activated() ) return;
	$output = ""; $columnss = $columns;
	if( !empty($number) ) $number = 'number='. $number;
	if( !empty($parent) ) $parent = 'parent='. $parent;
	if( !empty($orderby) ) $orderby = 'orderby='. $orderby;
	if( !empty($order) ) $order = 'order='. $order;
	if( !empty($columns) ) $columns = 'columns='. $columns;
	if( !empty($hide_empty) ) $hide_empty = 'hide_empty='. $hide_empty;
	if( !empty($parent) ) $parent = 'parent='. $parent;
	if( !empty($ids) ) $ids = 'ids='. $ids;

	$sc = '[product_categories ' . $number .' '. $parent .' '. $orderby .' '. $order .' '. $columns .' '. $hide_empty .' '. $parent .' '. $ids .' ]';

	$output = '<div class="fix woocommerce-columns-'. $columnss .'">'. do_shortcode( $sc ) .'</div>';
	
	return $output;
} // End templatation_productcategories()
}


/*-----------------------------------------------------------------------------------*/
/* extending woocommerce's [product_category] SC [TT-productcategory] Shortcode function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'templatation_productcategory' ) ) {
function templatation_productcategory ( $atts, $content = null ) {
	$defaults = array( 'per_page' => '12',
					   'orderby' => 'title',
					   'order' => 'ASC',
					   'columns' => '3',
					   'category' => '',
					    );
	// we do not need title here as user can put it via visual composer 
	extract( shortcode_atts( $defaults, $atts ) );
	$output = "";
	if ( ! is_woocommerce_activated() ) return;

	$output = ""; $columnss = $columns;
	if( !empty($per_page) ) $per_page = 'per_page='. $per_page;
	if( !empty($orderby) ) $orderby = 'orderby='. $orderby;
	if( !empty($order) ) $order = 'order='. $order;
	if( !empty($columns) ) $columns = 'columns='. $columns;
	if( !empty($category) ) $category = 'category='. $category;

	$sc = '[product_category ' . $per_page .' '. $orderby .' '. $order .' '. $columns .' '. $category .' ]';

	$output = '<div class="fix woocommerce-columns-'. $columnss .'">'. do_shortcode( $sc ) .'</div>';
	
	return $output;
} // End templatation_productcategory()
}

/*-----------------------------------------------------------------------------------*/
/* Function for Plugging styles for wrapper/body image. */
/*-----------------------------------------------------------------------------------*/
/* not being used for now , might use in future.

if ( ! function_exists( 'templatation_wp_head' ) ) {
function templatation_wp_head () {
	global $woo_options, $wp_query;
	$output = $outputstyle = $single_page_bg_image = $single_page_bg_image_repeat = $tt_post_id = $current_page_template = '';
	if( isset($wp_query->post->ID) ) $tt_post_id = $wp_query->post->ID; // saving current object id so that we can fetch its own single heading section details via custom fields.

	if ( !empty($tt_post_id) ) { $current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true); }

	// fetching value from single posts.
	$single_page_bg_image = get_post_meta($tt_post_id, 'singbgimage', true);
	$single_page_bg_image_repeat = get_post_meta($tt_post_id, '_single_page_bg_image_repeat', true);

	// Wrapper setting.
	if ( !empty($single_page_bg_image) ) {
		$outputstyle .= '#wrapper { background-image: url('. $single_page_bg_image .'); }' . "\n"; // show background image from single post, regardless of theme-options value.
		$outputstyle .= '#wrapper { background-position: center top;   background-repeat: '. $single_page_bg_image_repeat .'; }' . "\n";
	}

	echo '<!-- Headings Custom CSS -->' . "\n";
	$output = "<style type=\"text/css\">\n" . $outputstyle . "</style>\n\n";
	echo stripslashes( $output );
	}
} // End templatation_wp_head()
*/

/*-----------------------------------------------------------------------------------*/
/* Adding fontawesome icons to menubar
/* Version: 4.0.3.0
/* Author: New Nine Media
/* Author URI: http://www.newnine.com
/* License: GPLv2 or later
/*-----------------------------------------------------------------------------------*/
add_filter( 'wp_nav_menu' , 'templatatio_menu', 10, 2 );
if ( ! function_exists( 'templatatio_menu' ) ) {
function templatatio_menu( $nav ){
	$menu_item = preg_replace_callback(
		'/(<li[^>]+class=")([^"]+)("?[^>]+>[^>]+>)([^<]+)<\/a>/',
		'templatation_replace',
		$nav
	);
	return $menu_item;
}
}
if ( ! function_exists( 'templatation_replace' ) ) {
function templatation_replace( $a ){
	$start = $a[ 1 ];
	$classes = $a[ 2 ];
	$rest = $a[ 3 ];
	$text = $a[ 4 ];
	$before = true;
	
	$class_array = explode( ' ', $classes );
	$fontawesome_classes = array();
	foreach( $class_array as $key => $val ){
		if( 'fa' == substr( $val, 0, 2 ) ){
			if( 'fa' == $val ){
				unset( $class_array[ $key ] );
			} elseif( 'fa-after' == $val ){
				$before = false;
				unset( $class_array[ $key ] );
			} else {
				$fontawesome_classes[] = $val;
				unset( $class_array[ $key ] );
			}
		}
	}
	
	if( !empty( $fontawesome_classes ) ){
		$fontawesome_classes[] = 'fa';
		if( $before ){
			$newtext = '<i class="fa-fw '.implode( ' ', $fontawesome_classes ).'"></i><span class="fontawesome-text"> '.$text.'</span>';
		} else {
			$newtext = '<span class="fontawesome-text">'.$text.' </span><i class="fa-fw '.implode( ' ', $fontawesome_classes ).'"></i>';
		}
	} else {
		$newtext = $text;
	}
	
	$item = $start.implode( ' ', $class_array ).$rest.$newtext.'</a>';
	return $item;
}
}
/* TT-fa shortcode. */
if ( ! function_exists( 'fa_shortcode_icon' ) ) {
function fa_shortcode_icon( $atts ){
	extract( shortcode_atts( array(
		'class' => '',
	), $atts ) );
	if( !empty( $class ) ){
		$fa_exists = false;
		$class_array = explode( ' ', $class );
		foreach( $class_array as $c ){
			if( 'fa' == $c ){
				$fa_exists = true;
			}
		}
		if( !$fa_exists ){
			array_unshift( $class_array, 'fa' );
		}
		return '<i class="'.implode( ' ', $class_array ).'"></i>';
	}
}
}

/*-----------------------------------------------------------------------------------*/
/* Function for Adding Retina support, thanks to C.bavota                            */
/*-----------------------------------------------------------------------------------*/
add_filter( 'wp_generate_attachment_metadata', 'retina_support_attachment_meta', 10, 2 );
/**
 * Retina images
 *
 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
 */
if ( ! function_exists( 'retina_support_attachment_meta' ) ) {
function retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
 
    return $metadata;
}
}
/**
 * Create retina-ready images
 *
 * Referenced via retina_support_attachment_meta().
 */
if ( ! function_exists( 'retina_support_create_images' ) ) {
function retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}
}
add_filter( 'delete_attachment', 'delete_retina_support_images' );
/**
 * Delete retina-ready images
 *
 * This function is attached to the 'delete_attachment' filter hook.
 */
if ( ! function_exists( 'delete_retina_support_images' ) ) {
function delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
	if (isset($meta["file"])) {
		$path = pathinfo( $meta["file"] );
		foreach ( $meta as $key => $value ) {
			if ( "sizes" === $key ) {
				foreach ( $value as $sizes => $size ) {
					$original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
					$retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
					if ( file_exists( $retina_filename ) )
						unlink( $retina_filename );
				}
			}
		}
	}
}
}
// Function to initialize demo setup if first time install.
if ( ! function_exists( 'tt_init_import' ) ) {
function tt_init_import() {
    $woo_options = get_option( 'woo_options' );
	if( !isset( $woo_options['woo_rmve_import'] ) || ( 'false' == $woo_options['woo_rmve_import'] ) ) {
		require get_template_directory() .'/includes/demo-install/init.php';
	}
}
}// end of tt_init_import

// Function hook on demo installer to setup rest of the stuff.
add_action( 'tt_after_import_hook', 'tt_demo_import' );
function tt_demo_import() {
	// Set reading options
	$homepge = get_page_by_title( 'Homepage' );
	$posts_pge = get_page_by_title( 'Blog' );
	if( isset( $homepge ) && $homepge->ID ) {
		update_option('show_on_front', 'page');
		update_option('page_on_front', $homepge->ID); // setting up homepage
	}
	if( isset( $posts_pge ) && $posts_pge->ID ) {
		update_option('page_for_posts', $posts_pge->ID); // setting up blog
	}
	if (function_exists('tt_import_rev')) tt_import_rev(); // import revslider
	if (function_exists('tt_set_demo_menus')) tt_set_demo_menus(); // setup menu location
	if (function_exists('templatation_woocommerce_image_dimensions')) templatation_woocommerce_image_dimensions(); // setup wc img size

	// setting up widgets
	if (function_exists('process_widget_import_file')) process_widget_import_file();
	//wp_redirect( admin_url( 'admin.php?page=templatation' ) );
} // end of tt_demo_import

// Adding a class to VC so that we can customize vc styles without !important
add_filter('vc_shortcodes_css_class', 'tt_vc_class_name', 10, 2);
function tt_vc_class_name($class_string, $tag) {
   return $class_string.' templatation';
}
// WC update changed class name on cart table, adding old to keep the desired look.
add_filter('woocommerce_cart_item_class', 'tt_add_cart_class', 10, 2);
function tt_add_cart_class( $cart_item ) {
   return $cart_item.' tt_cart_table_item';
}
add_filter( 'get_product_search_form' , 'tt_custom_product_searchform' );
function tt_custom_product_searchform( $form ) {

	$form = '<form role="search" id="searchform" method="get" class="woocommerce-product-search" action="'. esc_url( home_url( '/'  ) ).'">
	<label class="screen-reader-text" for="s">'. __( 'Search for:', 'templatation' ) .'</label>
	<input id="s" type="search" class="search-field" placeholder="'. __( 'Search Products&hellip;', 'templatation' ) .'" value="'. get_search_query().'" name="s" title="'. esc_attr_x( 'Search for:', 'templatation' ).'" />
	<input id="searchsubmit" type="submit" value="'. __( 'Search', 'templatation' ).'" />
	<input type="hidden" name="post_type" value="product" />
</form>
';

	return $form;

}
/*-----------------------------------------------------------------------------------*/
/* Function for live customizer
/*-----------------------------------------------------------------------------------*/

if (! function_exists('templatation_customize_register') ) {
	function templatation_customize_register( $wp_customize ) {

		// Setting of settings for default color section.
		$colors   = array();
		$colors[] = array(
			'slug'    => 'tt_primary_color',
			'default' => '#e75a39',
			'label'   => __( 'Primary Theme Color', 'templatation' )
		);
		$colors[] = array(
			'slug'    => 'tt_secondary_color',
			'default' => '#917460',
			'label'   => __( 'Secondary Theme Color', 'templatation' )
		);
		$colors[] = array(
			'slug'    => 'tt_ligher_color',
			'default' => '#faded8',
			'label'   => __( 'Light Theme Color', 'templatation' )
		);
		foreach ( $colors as $color ) {
			// SETTINGS
			$wp_customize->add_setting(
				$color['slug'], array(
					'default'    => $color['default'],
					'capability' => 'edit_theme_options'
				)
			);
			// CONTROLS
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$color['slug'],
					array(
						'label'    => $color['label'],
						'section'  => 'colors',
						'settings' => $color['slug']
					)
				)
			);
		}
		$wp_customize->remove_section( 'title_tagline');
		$wp_customize->get_section( 'colors' )->description             = __( 'Modify main colors of the theme. For body and header styling, please go to Themeoptions/stying. Here you can choose main 3 colors for the theme with live preview on right. Note that live preview is not instant, it takes 3 seconds to auto-refresh, also to change color of few icons/images please recolor sprite image as well. Its PSD is included in your download/resources. Note: This customizer works only if you have selected default.css in Themeoptions/Quick Start.', 'templatation' );
		$wp_customize->get_control( 'tt_ligher_color' )->description    = __( 'This is lighter version of primary color, used on background of Post meta. Recommended: Extreme lighter version of primary color.', 'templatation' );
		$wp_customize->get_control( 'tt_primary_color' )->description   = __( 'Primary color of the theme.', 'templatation' );
		$wp_customize->get_control( 'tt_secondary_color' )->description = __( 'Secondary color of the theme. Used on button shadow and few hovers and buttons. Recommended : Darker version of primary color.', 'templatation' );

	}
}

if (! function_exists('tt_customizer_css') ) {
function tt_customizer_css() {
    ?>
    <style type="text/css">

	@-webkit-keyframes glowbutton {
	from {
	background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	50% {
	background-color: <?php echo get_theme_mod( 'tt_ligher_color' ); ?>;
	}
	to {
	background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	}
	}
	span.onsale,span.onsale:after{background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>}

/*-------------------------------------------------------------------------------------------*/
/* 2. Defaults and Heading tags */
/*-------------------------------------------------------------------------------------------*/

	::selection {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	::-moz-selection {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	a {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	h1,
	h2,
	h3,
	h4,
	h5,
	h6 {
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	h1 a:hover,
	h2 a:hover,
	h3 a:hover,
	h4 a:hover,
	h5 a:hover,
	h6 a:hover {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	a.remove {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

/*-------------------------------------------------------------------------------------------*/
/* 3. Page layout */
/*-------------------------------------------------------------------------------------------*/
	#header.d {
	  background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#navigation .nav a,
	#yith-wcwl-popup-message {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.slider-layout2, .slider-layout3 {
	  background-color: <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	#header .nav-toggle span {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#reviews #comments li.comment .comment_container .verified:before {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#respond h3 #cancel-comment-reply-link {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	input[type="checkbox"]:checked {
	  background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	/* Buttons (Includes WF chortcode buttons) */
	a.button,
	#commentform #submit,
	.submit,
	input[type=submit],
	input.button,
	button.button,
	#wrapper .woo-sc-button,
	.added_to_cart,
	.ns_button.wpb_button,
	.tt_cta_button.wpb_button,
	.yith-wcwl-add-button a.add_to_wishlist,
	.yith-wcwl-add-button .add_to_wishlist,
	.yith-wcwl-wishlistexistsbrowse a,
	.yith-wcwl-wishlistaddedbrowse a {
	  background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	  /* Box shadow */
	  box-shadow: 0 2px 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	  -moz-box-shadow: 0 2px 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	  -webkit-box-shadow: 0 2px 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	ul.products li.product.product-category span.view-more {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	  /* Box shadow */
	  box-shadow: 0 2px 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	  -moz-box-shadow: 0 2px 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	  -webkit-box-shadow: 0 2px 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	a.comment-reply-link{
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	a.button:hover,
	a.comment-reply-link:hover,
	#commentform #submit:hover,
	.submit:hover,
	input[type=submit]:hover,
	input.button:hover,
	button.button:hover,
	#wrapper .woo-sc-button:hover,
	.added_to_cart:hover,
	.ns_button.wpb_button:hover,
	.tt_cta_button.wpb_button:hover,
	.yith-wcwl-add-button.show .add_to_wishlist:hover,
	.yith-wcwl-wishlistexistsbrowse a,
	.yith-wcwl-wishlistaddedbrowse a {
	  background-color: <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}


/*-------------------------------------------------------------------------------------------*/
/* 4. Post/page structure */
/*-------------------------------------------------------------------------------------------*/
	.breadcrumbs-wrap .breadcrumbs {
	  border-left-color: <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	#post-entries a:hover {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.single article.post > footer .list-f li.post-tags:before {
	color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.single article.post > footer .list-f li.posted_in:before {
	color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.post-meta ul li:before {
	color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#comments li.comment .comment_container .comment-text .name{
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	.jssidebar .widget h3,
	#sidebar .widget h3,
	.jssidebar.templatation .widgettitle {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#sidebar .widget_rss .rsswidget a { color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>; }
	#sidebar .widget_rss ul li a { display: inline; color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>; }
	#sidebar .woocommerce .product_list_widget li:hover {
	  background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#sidebar .woocommerce ul li .amount {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.price .amount,
	del .amount {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	article.hentry header h1, article.hentry header h2, article.type-post header h1, article.type-post header h2, article.type-page header h1, article.type-page header h2, .search #main article header h1, .search #main article header h2, h1.page-title, h2.page-title{
	  background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	  border-color: <?php echo get_theme_mod( 'tt_ligher_color' ); ?>;
	}

	article.hentry header ul, .search #main article header ul {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	article.type-page header h1, article.type-page header h2, h1.page-title, h2.page-title {
	border: none;
	padding: 8px 10px;
	}
	article.hentry header ul li,
	article.hentry header ul li a,
	.search #main article header ul li,
	.search #main article header ul li a {
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	.nav-a > ul > li > a:hover > .shade-a, .nav-a > ul > li > a:focus > .shade-a, .nav-a > ul > li > a:active > .shade-a, .nav-a > ul > li.active > a > .shade-a, .nav-a > ul > li:hover > a > .shade-a,
	.wpb_tour.templatation ul.wpb_tabs_nav li.ui-tabs-active a .shade-a, .wpb_tour.templatation ul.wpb_tabs_nav li a:hover .shade-a, .wpb_tour.templatation ul.wpb_tabs_nav li a:focus .shade-a, .wpb_tour.templatation ul.wpb_tabs_nav li a:active .shade-a {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.nav-a > ul > li > a:hover, .nav-a > ul > li > a:focus, .nav-a > ul > li > a:active, .nav-a > ul > li.active > a, .nav-a > ul > li:hover > a,
	.wpb_tour.templatation.wpb_content_element .wpb_tabs_nav li.ui-tabs-active, .wpb_tour.templatation.wpb_content_element .wpb_tabs_nav li:hover {
		border-left-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.nav-a > ul > li > ul > li a:hover, .nav-a > ul > li > ul > li a:focus, .nav-a > ul > li > ul > li a:active,
	.wpb_tour.templatation.wpb_content_element .wpb_tabs_nav li.ui-tabs-active a, .wpb_tour.templatation.wpb_content_element .wpb_tabs_nav li:hover a { color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>; }

	.widget_templatation_testimonials .bx-prev, .widget_templatation_testimonials .bx-next,
	.home-featured .widget .bx-prev, .home-featured .widget .bx-next,
	.wpb_carousel .prev, .wpb_carousel .next,
	.templatation.wpb_carousel .prev, .templatation.wpb_carousel .next { background-color: <?php echo get_theme_mod( 'tt_ligher_color' ); ?>; }

	.widget_templatation_testimonials .bx-prev:hover, .widget_templatation_testimonials .bx-next:hover,
	.home-featured .widget .bx-prev:hover, .home-featured .widget .bx-next:hover { background-color: <?php echo get_theme_mod( 'tt_secondary_color' ); ?>; }

	.products-carousel ul.products .bx-wrapper .bx-controls .bx-next:hover,
	.home-showcase ul.products .bx-wrapper .bx-controls .bx-next:hover,
	.templatation.wpb_carousel .next:hover {
		background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.products-carousel ul.products .bx-wrapper .bx-controls .bx-prev:hover,
	.home-showcase ul.products .bx-wrapper .bx-controls .bx-prev:hover,
	.templatation.wpb_carousel .prev:hover {
		background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	.widget .product_list_widget li .quantity .amount,
	.widget_templatation_testimonials .testimonials .quote .testimonials-text cite.author,
	.templatation.wpb_teaser_grid .categories_filter li a, .templatation.wpb_categories_filter li a,
	.single-product .summary .yith-wcwl-add-button .add_to_wishlist,
	.single-product .summary .yith-wcwl-wishlistexistsbrowse a,
	.single-product .summary .yith-wcwl-wishlistaddedbrowse a {
	  color:<?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.widget_price_filter .ui-slider .ui-slider-handle {
	  border: 1px solid <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.widget_price_filter .ui-slider .ui-slider-range {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.templatation.vc_progress_bar .vc_single_bar.bar_orange .vc_bar {background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;}
	article.hentry header ul, .search #main article header ul { background: <?php echo get_theme_mod( 'tt_ligher_color' ); ?>; }
/*-------------------------------------------------------------------------------------------*/
/* 5. Footer structure */
/*-------------------------------------------------------------------------------------------*/
	#footer-wrap {
	  background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#footer {
	  background: <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	#footer-wrap.contact {
	  border-top: 4px solid <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#footer-widgets {
	  border-top: 1px solid <?php echo get_theme_mod( 'tt_secondary_color' ); ?>;
	}
	.jsfooter-hr {
		border-bottom: 1px solid <?php echo get_theme_mod( 'tt_ligher_color' ); ?>;
		}
	#footer-widgets #searchform {
	  border:1px solid <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#footer-widgets .button {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#footer-widgets .widget .product_list_widget li {
		border-bottom: 1px solid #FFF;
	}

	#footer-widgets .widget .product_list_widget li img {
		border: 1px solid #FFF;
	}
	#footer-widgets .widget .product_list_widget li .quantity, #footer-widgets .widget .product_list_widget li .amount {
		color: #444444;
	}

/*-------------------------------------------------------------------------------------------*/
/* 6. Portfolio structure */
/*-------------------------------------------------------------------------------------------*/
	#portfolio-gallery #port-tags li:hover {
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#portfolio-gallery #port-tags li.current a{
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}


/*-------------------------------------------------------------------------------------------*/
/* 7. Woocommerce Styles */
/*-------------------------------------------------------------------------------------------*/
	.woo-pagination a {
	  background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	  border: 3px solid #F3D1C3;
	}
	.woo-pagination a:hover {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	.woo-pagination .page-numbers.current {
	  border: 3px solid #EEB4A6;
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.woocommerce-message,
	.woocommerce_message {
	  border-top-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.woocommerce-message:before,
	.woocommerce_message:before {
	  content: "\f05d";
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.star-rating span:before {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	p.stars a.star-1:hover:after,
	p.stars a.star-2:hover:after,
	p.stars a.star-3:hover:after,
	p.stars a.star-4:hover:after,
	p.stars a.star-5:hover:after,
	p.stars a.star-1.active:after,
	p.stars a.star-2.active:after,
	p.stars a.star-3.active:after,
	p.stars a.star-4.active:after,
	p.stars a.star-5.active:after {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.woocommerce-info,
	.woocommerce_info,
	.noreviews,
	.create-account p:first-child,
	.nocomments {
	  border-top-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.woocommerce-info,
	.woocommerce_info,
	.noreviews,
	.create-account p:first-child,
	.nocomments {
	  border-top-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.woocommerce-info:before,
	.woocommerce_info:before,
	.noreviews:before,
	.create-account p:first-child:before,
	.nocomments:before {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#infscr-loading {
	  border-top-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.stock:before {
	  color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	span.onsale {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	span.onsale:after {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	ul.products li.product.sale .img-wrap .price {
	  background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

	.sliderh2 {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	.slidertext {
		border-bottom: 3px solid <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}

/*-------------------------------------------------------------------------------------------*/
/* 9. Media queries */
/*-------------------------------------------------------------------------------------------*/
/*----------------------*/
@media only screen and (max-width: 768px) {
	#navigation ul.nav li a:hover,
	#navigation ul.nav li a:focus,
	#navigation ul.nav li a:active {
	 background: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	 color: #FFFFFF;
	}
}
@media only screen and (min-width: 769px) {
  /* 1. GLOBAL DROPDOWN STYLES (these are purely for the dropdown layout and you should only edit the width of the dropdowns) */

	ul.nav ul.sub-menu li a {
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#navigation ul.nav ul.sub-menu li a{
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#navigation ul.nav li.parent:hover ul.sub-menu a  {
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>!important;
	}
	#navigation ul.nav ul.sub-menu li a:hover {
		background:<?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF;
	}
	#navigation ul.nav li.parent:hover ul.sub-menu a:hover  {
		background:<?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF!important;
	}
	#header.b #navigation > ul > li > a:hover,#header.b #navigation > ul > li > a:focus,#header.b #navigation > ul > li > a:active,
	#header.e #navigation > ul > li > a:hover,#header.e #navigation > ul > li > a:focus,#header.e #navigation > ul > li > a:active,
	#header.f #navigation > ul > li > a:hover,#header.f #navigation > ul > li > a:focus,#header.f #navigation > ul > li > a:active,
	#header.b #navigation > ul > li:active > a,
	#header.e #navigation > ul > li:active > a,
	#header.f #navigation > ul > li:active > a,
	#header.b #navigation > ul > li:hover > a,
	#header.e #navigation > ul > li:hover > a,
	#header.f #navigation > ul > li:hover > a  {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF;
	}
	#header.f.h #navigation {
		background-color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.f.h #navigation > ul > li > a:hover,
	#header.f.h #navigation > ul > li > a:focus,
	#header.f.h #navigation > ul > li > a:active,
	#header.f.h #navigation > ul > li:active > a,
	#header.f.h #navigation > ul > li:hover > a,
	#header.f.h #navigation ul.nav > li.parent > a:hover,
	#header.f.h #navigation ul.nav > li.parent:hover a,
	#header.f.h #navigation ul.nav > li.current_page_item a,
	#header.f.h #navigation ul.nav > li.current_page_parent > a,
	#header.f.h #navigation ul.nav > li.current-cat a,
	#header.f.h #navigation ul.nav > li.current-menu-ancestor a,
	#header.f.h #navigation ul.nav > li.li.current-menu-item > a{
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.b #navigation ul.nav > li.current_page_item a,
	#header.b #navigation ul.nav li.current_page_parent > a,
	#header.b #navigation ul.nav li.current-cat a,
	#header.b #navigation ul.nav li.current-menu-ancestor a,
	#header.b #navigation ul.nav li.li.current-menu-item > a,
	#header.e #navigation ul.nav > li.current_page_item a,
	#header.e #navigation ul.nav li.current_page_parent > a,
	#header.e #navigation ul.nav li.current-cat a,
	#header.e #navigation ul.nav li.current-menu-ancestor a,
	#header.e #navigation ul.nav li.li.current-menu-item > a,
	#header.f #navigation ul.nav > li.current_page_item a,
	#header.f #navigation ul.nav li.current_page_parent > a,
	#header.f #navigation ul.nav li.current-cat a,
	#header.f #navigation ul.nav li.current-menu-ancestor a,
	#header.f #navigation ul.nav li.li.current-menu-item > a {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF;
	}
	#header.b #navigation ul.nav > li.parent > a:hover,
	#header.e #navigation ul.nav > li.parent > a:hover,
	#header.f #navigation ul.nav > li.parent > a:hover {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF;
	}
	#header.b #navigation ul.nav > li.parent:hover a,
	#header.e #navigation ul.nav > li.parent:hover a,
	#header.f #navigation ul.nav > li.parent:hover a {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF;
	}
	#header.b #navigation ul.nav > li.parent:hover li a,
	#header.e #navigation ul.nav > li.parent:hover li a,
	#header.f #navigation ul.nav > li.parent:hover li a {
		background: none repeat scroll 0 0 #FFFFFF;
	}
	#header.b #navigation ul.nav > li.parent:hover li a:hover,
	#header.e #navigation ul.nav > li.parent:hover li a:hover,
	#header.f #navigation ul.nav > li.parent:hover li a:hover {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		color: #FFF;
	}
	#header.c #navigation > ul > li > a:hover,
	#header.c #navigation > ul > li > a:focus,
	#header.c #navigation > ul > li > a:active,
	#header.c #navigation > ul > li:active > a,
	#header.c #navigation > ul > li:hover > a {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.c #navigation > ul > li > a:hover {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.c #navigation ul.nav > li.current_page_item a,
	#header.c #navigation ul.nav li.current_page_parent > a,
	#header.c #navigation ul.nav li.current-cat a,
	#header.c #navigation ul.nav li.current-menu-ancestor a,
	#header.c #navigation ul.nav li.li.current-menu-item > a {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.c #navigation ul.nav > li.parent > a:hover {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.c #navigation ul.nav > li.parent:hover a {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header.c #navigation ul.nav > li.parent:hover li a {
		background: none repeat scroll 0 0 #FFFFFF;
	}
	#header.c #navigation ul.nav > li.parent:hover li a:hover {
		background: none repeat scroll 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header .header-top .row .account .account-links ul {
		box-shadow: 0.618em 0 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
		-webkit-box-shadow: 0.618em 0 0 0 <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	table.cart .tt_cart_table_item .product-price,
	table.cart .tt_cart_table_item .product-subtotal
	{
		color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	}
	#header #navigation ul#main-nav > li.megamenu > ul.sub-menu { border-top: 3px solid <?php echo get_theme_mod( 'tt_primary_color' ); ?>; }
	#header #navigation ul#main-nav > li.megamenu > ul.sub-menu > li a { border-right: 1px dotted <?php echo get_theme_mod( 'tt_primary_color' ); ?>; }
	#header.f #navigation ul.nav > li.parent:hover li a { color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>; }
}

@media only screen and (min-width: 767px) {
  .cart-collaterals .cart_totals table tbody .total { color:<?php echo get_theme_mod( 'tt_primary_color' ); ?>; }
  table.cart .tt_cart_table_item .product-price,
  table.cart .tt_cart_table_item .product-subtotal
   {
    font-size: 1.1em;
	color: <?php echo get_theme_mod( 'tt_primary_color' ); ?>;
	font-weight: 700;
  }

}
    </style>
    <?php
}
}
/*
function tt_customizer_live_preview()
{
	wp_enqueue_script(
		  'mytheme-themecustomizer',			//Give the script an ID
		  get_template_directory_uri().'/includes/js/theme-customizer.js',//Point to file
		  array( 'jquery','customize-preview' ),	//Define dependencies
		  '',						//Define a version (optional)
		  true						//Put script in footer?
	);
}
add_action( 'customize_preview_init', 'tt_customizer_live_preview' );
*/



/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/