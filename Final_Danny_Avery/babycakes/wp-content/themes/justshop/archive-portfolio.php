<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>
    
    <div id="content" class="page col-full">
    	
    	<?php woo_main_before(); ?>
    	
		<section id="portfolio-gallery" class="col-full"> 

        <?php if ( have_posts() ) : $count = 0; ?>                                                           
            <article <?php post_class(); ?>>
                
                <?php get_template_part( 'loop', 'portfolio' ); ?>
        
                <?php edit_post_link( __( '{ Edit }', 'templatation' ), '<span class="small">', '</span>' ); ?>
                
            </article><!-- /.post -->
            
        <?php else : ?>
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'templatation' ); ?></p>
            </article><!-- /.post -->
        <?php endif; ?>  
        
        </section><!-- /#portfolio-gallery -->
        
        <?php woo_main_after(); ?>
        
    </div><!-- /#content -->
			
<?php get_footer(); ?>