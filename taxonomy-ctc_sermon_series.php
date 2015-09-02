<?php
	/* Sermon series archive */
	
	get_header(); 
	$term = get_queried_object();
	$title = explode( '/', harvest_option( 'ctc-sermon-series' , __( 'Series', 'harvest' ) ) );
	$title = array_pop( $title );
	harvest_title_bar( sprintf( '%s: %s', $title, $term->name	) );
	
?>
		<div class="content_wrap">

			<div class="grid-container content">
			
<?php
	if( have_posts() ) : 
		if( $term -> description ): 	?>
				<div class="grid-100 ctc-sermon-series-desc" >
					<p><?php echo do_shortcode( $term->description ); ?></p>
				</div>
<?php	
	endif;
	while (have_posts()) : the_post(); 
		
		get_template_part( 'templates/sermon', 'grid' );
		
	endwhile; endif; 	// loop
?>
				
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

		<div class="grid-container">
<?php harvest_pagination_new(); ?>
		</div>

<?php

	//harvest_pagination();
	
	get_footer();
	

		
