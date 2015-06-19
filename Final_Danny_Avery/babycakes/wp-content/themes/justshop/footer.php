<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
	global $woo_options;

	$total = 4;
	if ( isset( $woo_options['woo_footer_sidebars'] ) && ( $woo_options['woo_footer_sidebars'] != '' ) ) {
		$total = $woo_options['woo_footer_sidebars'];
	}

	if ( ( woo_active_sidebar( 'footer-1' ) ||
		   woo_active_sidebar( 'footer-2' ) ||
		   woo_active_sidebar( 'footer-3' ) ||
		   woo_active_sidebar( 'footer-4' ) ) && $total > 0 ) {

?>

	<?php woo_footer_before(); ?>

	<?php // grabbing the theme settings
		$lefticon = get_template_directory_uri() . '/images/icons/a.png';
		$righticon = get_template_directory_uri() . '/images/icons/b.png';

		$footsettings = array(
					'ft_enable_hero' => 'true',
					'ft_icon_bg_left' => 'true', 
					'ft_icon_left' => $lefticon, 
					'ft_title_left' => 'Title (Left side)', 
					'ft_content_left' => 'Edit this text and title in Theme-options -> Footer Customization -> Footer showcase content section. Lorem ipsum dolor sit amet, consectetuer adipisc in elit, sed diam non ummy nibh in euismod tincidunt ut liber tempor laoreet.', 
					'ft_icon_bg_right' => 'true', 
					'ft_icon_right' => $righticon, 
					'ft_title_right' => 'Title (Right side)', 
					'ft_content_right' => 'Edit this text and title in Theme-options -> Footer Customization -> Footer showcase content section. Lorem ipsum dolor sit amet, consectetuer adipisc in elit, sed diam non ummy nibh in euismod tincidunt ut liber tempor laoreet.',
					'jsanim_no' => 'true'
					);
	$footsettings = woo_get_dynamic_values( $footsettings ); ?>
					
	<footer id="footer-wrap" class="col-full <?php if ( $footsettings['ft_enable_hero'] == 'false' ) echo "no-showcase"; ?>">
	
				<?php if ( $footsettings['ft_enable_hero'] == 'true' ) { // render footer showcase area ?>
					<article class="double-a <?php if ( 'false' == $footsettings['jsanim_no'] ) echo "js_animate";?>">
						<div>
							<h3><?php echo $footsettings['ft_title_left']; ?></h3>
							<figure <?php if ( 'true' == $footsettings['ft_icon_bg_left'] ) echo 'class="icon-bg"'; ?>><img width="59" height="59" alt="" src="<?php echo  $footsettings['ft_icon_left']; ?>"></figure>
							<p><?php echo do_shortcode($footsettings['ft_content_left']); ?></p>
						</div>
						<div>
							<h3><?php echo $footsettings['ft_title_right']; ?></h3>
							<figure <?php if ( 'true' == $footsettings['ft_icon_bg_right'] ) echo 'class="icon-bg"'; ?>><img width="59" height="59" alt="" src="<?php echo $footsettings['ft_icon_right']; ?>"></figure>
							<p><?php echo do_shortcode($footsettings['ft_content_right']); ?></p>
						</div>
					</article>
					<div class="jsfooter-hr"></div>
				<?php } ?>
				
				
		<section id="footer-widgets" class="col-full col-<?php echo $total; ?> fix">

			<?php $i = 0; while ( $i < $total ) { $i++; ?>
				<?php if ( woo_active_sidebar( 'footer-' . $i ) ) { ?>

			<div class="block footer-widget-<?php echo $i; ?>">
	        	<?php woo_sidebar( 'footer-' . $i ); ?>
			</div>

		        <?php } ?>
			<?php } // End WHILE Loop ?>

		</section><!-- /#footer-widgets  -->
	<?php } // End IF Statement ?>
		<div id="footer" class="col-full">

			<div id="credit" class="fix">
				<div id="copyright" class="col-left">
				<?php if( isset( $woo_options['woo_footer_left'] ) && $woo_options['woo_footer_left'] == 'true' ) {
						echo stripslashes( $woo_options['woo_footer_left_text'] );
				} else { ?>
					<p><?php bloginfo(); ?> &copy; <?php echo date( 'Y' ); ?>. <?php _e( 'All Rights Reserved.', 'templatation' ); ?></p>
				<?php } ?>
				</div>
				<div class="col-right">
				<?php if( isset( $woo_options['woo_footer_right'] ) && $woo_options['woo_footer_right'] == 'true' ) {
					echo stripslashes( $woo_options['woo_footer_right_text'] );
				} else { ?>
					<p><?php _e( 'Designed by', 'templatation' ); ?> <a href="http://www.templatation.com">templatation</a></p>
				<?php } ?>
				</div>
			</div><!-- /#credit  -->

		</div><!-- /#footer  -->

	</footer><!--/.footer-wrap-->

</div><!-- /#wrapper -->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>