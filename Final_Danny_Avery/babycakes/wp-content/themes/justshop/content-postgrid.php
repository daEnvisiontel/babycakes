<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The default template for displaying content for Blog grid page template
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


		<?php $postimg = woo_image( 'return=false&width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] ); ?>
		<div class="post-content <?php if ( empty($postimg) ) echo "NTno-img"; ?>">

			<header class="post-header">

 			<?php if ( !empty($postimg) ) { echo '<figure>'. $postimg. '</figure><div class="fix"></div>'; } ?>

			<h2><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'templatation' ); ?>"><?php the_title(); ?></a></h2>
			</header>
			<?php woo_post_meta(); ?>
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