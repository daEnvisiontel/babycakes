<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'templatation_get_testimonials' ) ) {
/**
 * Wrapper function to get the testimonials from the WooDojo_Testimonials class.
 * @param  string/array $args  Arguments.
 * @since  1.0.0
 * @return array/boolean       Array if true, boolean if false.
 */
function templatation_get_testimonials ( $args = '' ) {
	global $templatation_testimonials;
	return $templatation_testimonials->get_testimonials( $args );
} // End templatation_get_testimonials()
}

/**
 * Enable the usage of do_action( 'templatation_testimonials' ) to display testimonials within a theme/plugin.
 *
 * @since  1.0.0
 */
add_action( 'templatation_testimonials', 'templatation_testimonials' );

if ( ! function_exists( 'templatation_testimonials' ) ) {
/**
 * Display or return HTML-formatted testimonials.
 * @param  string/array $args  Arguments.
 * @since  1.0.0
 * @return string
 */
function templatation_testimonials ( $args = '' ) {
	global $post;

	$defaults = array(
		'limit' => 5, 
		'orderby' => 'menu_order', 
		'order' => 'DESC', 
		'id' => 0, 
		'display_author' => true, 
		'display_avatar' => true, 
		'display_url' => true, 
		'effect' => 'fade', // Options: 'fade', 'none'
		'pagination' => false, 
		'echo' => true, 
		'size' => 50, 
		'title' => '', 
		'before' => 'false',
		'after' => '</div>', 
		'before_title' => '<h2>', 
		'after_title' => '</h2>'
	);
	
	$args = wp_parse_args( $args, $defaults );

	// manipulation of before, to make list/slider option feature.
	($args['before'] == 'false') ? $args['before'] = '<div class="widget testi_slide widget_templatation_testimonials">' : $args['before'] = '<div class="widget testi_slide widget_templatation_testimonials">'; // .inner class is being hooked to .testi_slide. these are same for now.
	// Allow child themes/plugins to filter here. check widget.
	$args = apply_filters( 'templatation_testimonials_args', $args );
	$html = '';

	do_action( 'templatation_testimonials_before', $args );
		
		// The Query.
		$query = templatation_get_testimonials( $args );

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			
			if ( $args['effect'] != 'none' ) {
				$effect = ' ' . $args['effect'];
			}
			
			$html .= $args['before'] . "\n";
			if ( '' != $args['title'] ) {
				$html .= $args['before_title'] . esc_html( $args['title'] ) . $args['after_title'] . "\n";
			}
			$html .= '<div class="testimonials component' . $effect . '">' . "\n";

			$html .= '<div class="testimonials-list">' . "\n";
		
			// Begin templating logic.
			$tpl = '<div id="quote-%%ID%%" class="%%CLASS%%"><blockquote class="testimonials-text">%%TEXT%%</blockquote>%%AVATAR%% %%AUTHOR%%<div class="fix"></div></div>';
			$tpl = apply_filters( 'templatation_testimonials_item_template', $tpl, $args );

			$count = 0;
			foreach ( $query as $post ) { $count++;
				$template = $tpl;

				$css_class = 'quote';
				if ( 1 == $count ) { $css_class .= ' first'; }
				if ( count( $query ) == $count ) { $css_class .= ' last'; }

				setup_postdata( $post );
				
				$author = '';
				$author_text = '';
				
				// If we need to display the author, get the data.
				if ( ( get_the_title( $post ) != '' ) && true == $args['display_author'] ) {
					$author .= '<cite class="author">';

					$author_name = get_the_title( $post );

					$author .= $author_name;

					if ( isset( $post->byline ) && '' != $post->byline ) {
						$author .= ' <span class="excerpt">' . $post->byline . '</span><!--/.excerpt-->' . "\n";
					}

					if ( true == $args['display_url'] && '' != $post->url ) {
						$author .= ' <span class="url"><a href="' . esc_url( $post->url ) . '">' . $post->url . '</a></span><!--/.excerpt-->' . "\n";
					}
					
					$author .= '</cite><!--/.author-->' . "\n";

					// Templating engine replacement.
					$template = str_replace( '%%AUTHOR%%', $author, $template );
				} else {
					$template = str_replace( '%%AUTHOR%%', '', $template );
				}

				// Templating logic replacement.
				$template = str_replace( '%%ID%%', get_the_ID(), $template );
				$template = str_replace( '%%CLASS%%', esc_attr( $css_class ), $template );

				if ( isset( $post->image ) && ( '' != $post->image ) && true == $args['display_avatar'] ) {
					$template = str_replace( '%%AVATAR%%', '<a href="' . esc_url( $post->url ) . '" class="avatar-link">' . $post->image . '</a>', $template );
				} else {
					$template = str_replace( '%%AVATAR%%', '', $template );
				}

				// Remove any remaining %%AVATAR%% template tags.
				$template = str_replace( '%%AVATAR%%', '', $template );

				$template = str_replace( '%%TEXT%%', get_the_content(), $template );

				// Assign for output.
				$html .= $template;
			}

			wp_reset_postdata();
			
			$html .= '</div><!--/.testimonials-list-->' . "\n";
			
			if ( $args['pagination'] == true && count( $query ) > 1 && $args['effect'] != 'none' ) {
				$html .= '<div class="pagination">' . "\n";
				$html .= '<a href="#" class="btn-prev">' . apply_filters( 'templatation_testimonials_prev_btn', '&larr; ' . __( 'Previous', 'templatation' ) ) . '</a>' . "\n";
		        $html .= '<a href="#" class="btn-next">' . apply_filters( 'templatation_testimonials_next_btn', __( 'Next', 'templatation' ) . ' &rarr;' ) . '</a>' . "\n";
		        $html .= '</div><!--/.pagination-->' . "\n";
			}
				$html .= '<div class="fix"></div>' . "\n";
			$html .= '</div><!--/.testimonials-->' . "\n";
			$html .= $args['after'] . "\n";
		}
		
		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'templatation_testimonials_html', $html, $query, $args );
		
		if ( $args['echo'] != true ) { return $html; }
		
		// Should only run is "echo" is set to true.
		echo $html;
		
		do_action( 'templatation_testimonials_after', $args ); // Only if "echo" is set to true.
} // End templatation_testimonials()
}

if ( ! function_exists( 'templatation_testimonials_shortcode' ) ) {
function templatation_testimonials_shortcode ( $atts, $content = null ) {
	$args = (array)$atts;

	$defaults = array(
		'limit' => 5, 
		'orderby' => 'menu_order', 
		'order' => 'DESC', 
		'id' => 0, 
		'display_author' => true, 
		'display_avatar' => true, 
		'display_url' => true, 
		'effect' => 'fade', // Options: 'fade', 'none'
		'pagination' => false, 
		'echo' => true, 
		'size' => 50
	);

	$args = shortcode_atts( $defaults, $atts );

	// Make sure we return and don't echo.
	$args['echo'] = false;

	// Fix integers.
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );
	if ( isset( $args['id'] ) ) $args['id'] = intval( $args['id'] );
	if ( isset( $args['size'] ) &&  ( 0 < intval( $args['size'] ) ) ) $args['size'] = intval( $args['size'] );

	// Fix booleans.
	foreach ( array( 'display_author', 'display_url', 'pagination', 'display_avatar' ) as $k => $v ) {
		if ( isset( $args[$v] ) && ( 'true' == $args[$v] ) ) {
			$args[$v] = true;
		} else {
			$args[$v] = false;
		}
	}

	return templatation_testimonials( $args );
} // End templatation_testimonials_shortcode()
}

add_shortcode( 'templatation_testimonials', 'templatation_testimonials_shortcode' );
?>