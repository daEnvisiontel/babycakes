<?php
/**
 * Carousel products Panel
 */
 	
	/**
 	* The Variables
 	*
 	* Template for product carausel. As of version 2.3 it doesnt slider, will add Carousel slider in future versions.
 	*/
	
	global $woocommerce, $woocommerce_loop, $post; $atts ='';
	
	if ( ! defined( 'WOO_SHORTCODE_JS' ) ) { define( 'WOO_SHORTCODE_JS', 'load' ); }
	
	extract(shortcode_atts(array(
		'number_of_products' 	=> '4',
		'columns' 	=> '4',
		'orderby' => 'date',
		'order' => 'desc'
	), $atts));
?>


			<section id="home-carousel" class="products-carousel woocommerce woocommerce-wrap woocommerce-columns-<?php echo $columns; ?> fix">

				<div class="tabs-a shortcode-tabs">
					<ul>
						<li class="nav-tab"><a href="#tab-a"><?php _e( 'Latest', 'templatation' ); ?></a></li>
						<li class="nav-tab"><a href="#tab-b"><?php _e( 'Featured', 'templatation' ); ?></a></li>
						<li class="nav-tab"><a href="#tab-c"><?php _e( 'Bestsellers', 'templatation' ); ?></a></li>
					</ul>
					<div>
						<div id="tab-a" class="blog-g">
							<?php echo do_shortcode( '[recent_products per_page="' . $number_of_products . '" columns="' . $columns .'" orderby="date" order="desc"]' ); ?>
						</div>
						<div id="tab-b" class="blog-g">
							<?php echo do_shortcode( '[featured_products per_page="' . $number_of_products . '" columns="' . $columns .'" orderby="date" order="desc"]' ); ?>						</div>
						<div id="tab-c" class="blog-g">
							<?php echo do_shortcode( '[best_selling_products per_page="' . $number_of_products . '" columns="' . $columns .'" orderby="date" order="desc"]' ); ?>  					</div>
					</div>
				</div>

    		</section>
