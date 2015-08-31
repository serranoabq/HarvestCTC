<?php
	/* Location archive */
	
	get_header(); 
	$title = explode( '/', harvest_option( 'ctc-locations' , __( 'Locations', 'harvest' ) ) );
	$title = array_pop( $title );
	harvest_title_bar( $title );
?>
		
		<div class="content_wrap">

			<div class="grid-container content">
				
<?php
	query_posts($query_string . '&orderby=menu_order&order=ASC');
	if (have_posts()) : while (have_posts()) : the_post(); 
		
		get_template_part( 'templates/location', 'grid' );

	endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	get_footer();
	

		
