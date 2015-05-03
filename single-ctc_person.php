<?php
	/* Single person */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		
		harvest_title_bar( get_the_title() );
?>

		<div class="content_wrap">

			<div class="grid-container content">
			
<?php get_template_part( 'templates/person', 'details' ); ?>
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
