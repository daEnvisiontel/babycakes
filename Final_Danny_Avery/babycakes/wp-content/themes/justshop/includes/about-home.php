<?php 
$settings = array(
				'homepage_about_title' => '',
				'homepage_about_message' => '',
				'homepage_show_testi' => 'true',
				'homepage_testimonials_area_title' => 'People love us: ',
				'jsanim_no' => 'true'
			);
					
$settings = woo_get_dynamic_values( $settings );
?>
				<div class="module-b">
					<div class="cols-b  <?php if ( 'false' == $settings['jsanim_no'] ) echo " js_animate "; if ( 'false' == $settings['homepage_show_testi'] ) echo " cols-b-full "; ?>">
						<article>
							<h2><?php echo stripslashes( $settings['homepage_about_title'] ); ?></h2>
							<p><?php echo stripslashes( nl2br( do_shortcode( $settings['homepage_about_message'] ) ) ); ?></p>
						</article>
					<?php
	    			if ( 'true' == $settings['homepage_show_testi'] ) { ?>
						<aside>
						<h3><?php echo stripslashes( $settings['homepage_testimonials_area_title'] ); ?></h3>
	    			<?php	echo do_shortcode( '[templatation_testimonials limit="40" size="100" display_avatar="true" title="'. $settings['homepage_testimonials_area_title'] .'" ]' ); ?>
						</aside>
	    			<?php }	?>
					</div>
				</div>