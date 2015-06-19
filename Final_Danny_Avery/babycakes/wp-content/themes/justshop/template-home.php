<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Template Name: Custom Homepage
 *
 * The blog page template displays the "homepage".
 *
 * @package WooFramework
 * @subpackage Template
 *
 * Here we setup all logic and XHTML that is required for the index template.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options;
 get_header();

	$settings = array(
					'homepage_enable_product_categories' => 'true',
					'homepage_enable_featured_products' => 'true',
					'homepage_enable_recent_products' => 'true',
					'homepage_enable_testimonials' => 'true',
					'homepage_enable_content' => 'false',
					'homepage_product_categories_title' => '',
					'homepage_product_categories_limit' => 3,
					'homepage_featured_products_title' => 'Few of Awesome Products from our Store',
					'homepage_featured_products_limit' => 4,
					'homepage_recent_products_title' => '',
					'homepage_recent_products_limit' => 4,
					'homepage_about_home_section' => 'true',
					'homepage_content_type' => 'posts',
					'homepage_page_id' => '',
					'homepage_posts_sidebar' => 'true'
					);
	$settings = woo_get_dynamic_values( $settings );

	$layout_class = 'col-left';
	if ( 'true' != $settings['homepage_posts_sidebar'] ) { $layout_class = 'full-width'; }
?>

    <div id="content" class="col-full">

    	<?php woo_main_before(); ?>
		
		<!-- product categories block -->
    	<div class="woocommerce woocommerce-wrap <?php if( is_active_sidebar( 'homepage' )) echo "jssidebar"; ?>">
	    	<?php
	    		if ( ! dynamic_sidebar( 'homepage' ) ) {
	    			if ( 'true' == $settings['homepage_enable_product_categories'] && is_woocommerce_activated() ) {
	    				the_widget( 'Woo_Product_Categories', array( 'title' => $settings['homepage_product_categories_title'], 'categories_per_page' => intval( $settings['homepage_product_categories_limit'] ) ) );
	    			}
	    		}
	    	?>
		</div><!--/.woocommerce-->
		
		<!-- Home about us and testimonial section block -->
		<?php
		if ( 'true' == $settings['homepage_about_home_section'] ) { ?>
		<div id="about-home" class="col-full">
		<?php	get_template_part( 'includes/about-home' ); ?>
		</div><!--/#about-home-->
		<?php }	?>
			
		<!-- Featured Products block -->
		<?php  if ( 'true' == $settings['homepage_enable_featured_products'] && is_woocommerce_activated() ) { ?>
			<div class="woocommerce woocommerce-wrap woocommerce-columns-4 home-featured">
			<h2><?php echo $settings['homepage_featured_products_title']; ?></h2>					
		<?php  the_widget( 'Woo_Featured_Products', array( 'title' => $settings['homepage_featured_products_title'], 'products_per_page' => intval( $settings['homepage_featured_products_limit'] ) ) ); ?>
			</div><!--/.woocommerce-->
		<?php } ?>

		<!-- Homepage content area block -->
<?php if ( 'true' == $settings['homepage_enable_content'] ) { ?>
		<div class="home-content fix">
		<section id="main" class="<?php echo esc_attr( $layout_class ); ?>">
		<?php woo_loop_before(); ?>
	<?php
		if ( 'page' == $settings['homepage_content_type'] && 0 < intval( $settings['homepage_page_id'] ) ) {
			global $post;
			if (defined('ICL_LANGUAGE_CODE') && function_exists('icl_object_id')) { // this is to not break code in case WPML is turned off, etc.
			$settings['homepage_page_id'] = icl_object_id($settings['homepage_page_id'], 'page', TRUE); // swith to WPML generated page if applicable.
			}
			$post = get_page( intval( $settings['homepage_page_id'] ) );
			setup_postdata( $post );
			get_template_part( 'content', 'page' );
			wp_reset_postdata();
		} else { // show posts on homepage
	?>
			<?php
				$homesettings = $hpcat = $hpnumposts = $home_posts = '';
				$homesettings = woo_get_dynamic_values( array( 'homepage_content_type' => 'posts', 'homepage_number_of_posts' => get_option( 'posts_per_page' ), 'homepage_posts_category' => '' ) );
				$hpcat = intval( $homesettings['homepage_posts_category'] ); $hpnumposts = intval( $homesettings['homepage_number_of_posts'] );
				$home_posts = new WP_query();
				$home_posts->query('cat='.$hpcat.'&showposts='.$hpnumposts); 
				if ( $home_posts->have_posts() ) : $count = 0;
			?>
	
				<?php /* Start the Loop */ ?>
				<?php while ( $home_posts->have_posts() ) : $home_posts->the_post(); $count++; global $more; $more = 0; ?>
	
					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>
	
				<?php endwhile; ?>
	
			<?php else : ?>
	
				<article <?php post_class(); ?>>
					<p><?php _e( 'Sorry, no posts matched your criteria.', 'templatation' ); ?></p>
				</article><!-- /.post -->
	
			<?php endif; ?>
	<?php } wp_reset_postdata(); ?>
			<?php woo_loop_after(); ?>
	
			<?php
				if ( 'posts' == $settings['homepage_content_type'] ) {
					woo_pagenav();
				}
			?>

		</section><!-- /#main -->
        <?php if ( 'true' == $settings['homepage_posts_sidebar'] ) { get_sidebar(); } ?>
		</div><!-- /.home-content -->
<?php } ?>
		<?php woo_main_after(); ?>

    </div><!-- /#content -->

<?php get_footer(); ?>