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

			<?php 
		    	woo_image( 'width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] ); 
		    ?>       

			<section class="entry">

				<header class="post-header">
	        
		            <h1><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'templatation' ); ?>"><?php the_title(); ?></a></h1>
		                            	
		        </header>
		        
				<?php the_content( __( 'Continue Reading &rarr;', 'templatation' ) ); ?>
			</section>

		</div><!--/.post-content-->  

		<?php if (!is_front_page()) woo_post_meta(); ?>

	</article><!-- /.post -->