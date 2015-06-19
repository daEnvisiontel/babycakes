<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Single Post Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a post ('post' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
	
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
	$settings = array(
					'thumb_single' => 'true', 
					'single_w' => 610, 
					'single_h' => 208, 
					'thumb_single_align' => 'aligncenter'
					);
					
	$settings = woo_get_dynamic_values( $settings );
?>
       
    <div id="content" class="col-full">
    
    	<?php woo_main_before(); ?>
    	
		<section id="main" class="col-left">
		           
        <?php
        	if ( have_posts() ) { $count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>
			<article <?php post_class(); ?>>
			
				<header class="post-header">
					<?php echo woo_embed( 'width=610' ); ?>
					<?php if ( $settings['thumb_single'] == 'true' && ! woo_embed( '' ) ) { $singlepostimg = woo_image( 'return=false&width=' . $settings['single_w'] . '&height=' . $settings['single_h'] . '&class=thumbnail ' . $settings['thumb_single_align'] ); }
				if ( !empty($singlepostimg) ) { echo '<figure>'. $singlepostimg. '</figure><div class="fix"></div>'; } ?>
				
				<?php $titlesettings = "false" ; $titlesettings = get_post_meta(get_the_ID(), '_hide_title_display', true);
				if ( empty($titlesettings) || $titlesettings == 'false' ) { ?>
				<ul>
					<li><?php the_date('M j, Y'); ?></li>
					<li><?php comments_popup_link( __( 'Zero comments', 'templatation' ), __( '1 Comment', 'templatation' ), __( '% Comments', 'templatation' ) ); ?></li>
				</ul>
		        <h1><?php the_title(); ?></h1>
				<?php } // display title if not being hidden in single page/post.?>

				</header>

                <div class="post-content">

	                <section class="entry fix">

	                	<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'templatation' ), 'after' => '</div>' ) ); ?>
					</section>

				</div><!--/.post-content-->
				<div class="fix"></div>
		<footer>
				<ul class="list-f">
					<?php the_tags( '<li class="post-tags f">', ', ', '</li>' ); ?>
					<li class="posted_in"><?php _e( 'Posted in : ', 'templatation' ); ?><?php the_category( ' ') ?></li>
				</ul>
				<div class="sharebox">
							<div><?php _e( 'Share This : ', 'templatation' ); ?></div>
							<?php echo do_shortcode('[fblike style="button_count" float="right"]'); ?>
							<?php echo do_shortcode('[twitter use_post_url="true" float="right"]'); ?>
				</div>
		</footer>							                                
            </article><!-- .post -->

				<?php if ( isset( $woo_options['woo_post_author'] ) && $woo_options['woo_post_author'] == 'true' ) { ?>
				<aside id="post-author" class="fix">
					<div class="profile-image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '120' ); ?></div>
					<div class="profile-content">
						<h2 class="title"><?php printf( esc_attr__( 'About %s', 'templatation' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<div class="profile-link">
							<?php printf( __( 'View all posts by %s ', 'templatation' ), get_the_author() ); ?>
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">( <?php printf( __( ' %s Posts', 'templatation' ), get_the_author_posts() ); ?> )</a>
						</div><!-- #profile-link	-->
					</div><!-- .post-entries -->
				</aside><!-- .post-author-box -->
				<?php } ?>

				<?php woo_subscribe_connect(); ?>

            <?php
            	// Determine wether or not to display comments here, based on "Theme Options".
            	if ( isset( $woo_options['woo_comments'] ) && in_array( $woo_options['woo_comments'], array( 'post', 'both' ) ) ) {
            		comments_template();
            	}

				} // End WHILE Loop
			} else {
		?>
			<article <?php post_class(); ?>>
            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'templatation' ); ?></p>
			</article><!-- .post -->             
       	<?php } ?>  

       	<nav id="post-entries" class="fix">
	            <div class="nav-prev fl"><?php previous_post_link( '%link', '%title' ); ?></div>
	            <div class="nav-next fr"><?php next_post_link( '%link', '%title' ); ?></div>
	        </nav><!-- #post-entries -->
        
		</section><!-- #main -->
		
		<?php woo_main_after(); ?>

        <?php get_sidebar(); ?>

    </div><!-- #content -->
		
<?php get_footer(); ?>