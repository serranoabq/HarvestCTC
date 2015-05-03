<?php
	/* Single location */

	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
	
		harvest_title_bar( get_the_title() );
?>

		<div class="content_wrap">

			<div class="grid-container content">

<?php get_template_part( 'templates/location', 'details' ); ?>
				
				<div class="grid-50 ctc-loc-widgets-left">
					<?php dynamic_sidebar( 'location-sidebar-left' ); ?>
				</div> <!-- .ctc-loc-widgets-left -->
				
				<div class="grid-50 ctc-loc-widgets-right">
					<?php dynamic_sidebar( 'location-sidebar-right' ); ?>
				</div> <!-- .ctc-loc-widgets-right -->
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
