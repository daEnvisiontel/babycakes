<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The default template for displaying content
 */

	global $woo_options;

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */

 	$settings = array(
					'thumb_w' => 610,
					'thumb_h' => 208,
					'thumb_align' => 'aligncenter'
					);

	$settings = woo_get_dynamic_values( $settings );

?>

	<article <?php post_class(); ?>>



		<div class="post-content">

			<header class="post-header">

			<?php
		    	$postimg = woo_image( 'return=false&width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] );
				if ( !empty($postimg) ) { echo '<figure>'. $postimg. '</figure><div class="fix"></div>'; } ?>
				
				<ul class="jc-meta">
					<li><?php the_time('M j, Y'); ?></li>
					<li><?php comments_popup_link( __( 'Zero comments', 'templatation' ), __( '1 Comment', 'templatation' ), __( '% Comments', 'templatation' ) ); ?></li>
				</ul>
				<h2><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'templatation' ); ?>"><?php the_title(); ?></a></h2>

			</header>
			<section class="entry">

				<?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'content' ) { the_content( __( 'Continue Reading &rarr;', 'templatation' ) ); } else { the_excerpt(); } ?>
				<footer class="post-more">
				<?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'excerpt' ) { ?>
					<span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'templatation' ); ?>"><?php _e( 'Continue Reading &rarr;', 'templatation' ); ?></a></span>
				<?php } ?>
				</footer>
			</section>

		</div><!--/.post-content-->


	</article><!-- /.post -->