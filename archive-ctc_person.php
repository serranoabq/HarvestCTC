<?php
	/* People archive */
	
	get_header(); 
	$title = array_shift( explode( '/', harvest_option( 'ctc-people' , __( 'People', 'harvest' ) ) ) );
	harvest_title_bar( $title );
?>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	query_posts($query_string . '&orderby=menu_order&order=ASC&posts_per_page=-1');
	if (have_posts()) : while (have_posts()) : the_post(); 
	
		get_template_part( 'templates/person', 'grid' );

	endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	get_footer();
	

		
