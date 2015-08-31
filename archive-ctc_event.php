<?php
	/* Event archive */
	
	get_header(); 
	$title = explode( '/', harvest_option( 'ctc-events' , __( 'Events', 'harvest' ) ) );
	$title = array_shift( $title );
	harvest_title_bar( $title );
?>
		
		<div class="content_wrap">
			<div class="grid-container content">
			
<?php harvest_get_tax_dropdown( 'ctc_event_category' ); ?>

				<div class="grid-100 ctc-content-sidebar">
					<?php dynamic_sidebar( 'event-sidebar' ); ?>
				</div>
<?php
	do_action('__before_loop');
	if (have_posts()) : while (have_posts()) : the_post(); 

	get_template_part( 'templates/event', 'grid' );

	endwhile; endif; 
	do_action('__after_loop');  
?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->
		
		<div class="grid-container">
<?php harvest_pagination_new(); ?>
		</div>
		
<?php

	get_footer();
	

		
