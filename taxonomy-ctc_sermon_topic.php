<?php
	/* Sermon topic/location archive */
	global $paged; 
	if( empty($paged) ) $paged = 1;
	
	get_header(); 
	$term = get_queried_object();
	$title = explode( '/', harvest_option( 'ctc-sermon-topic' , __( 'Topic', 'harvest' ) ) );
	$title = array_pop( $title );
	harvest_title_bar( sprintf( '%s: %s', $title, $term->name )	);
	
?>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php harvest_get_tax_dropdown( 'ctc_sermon_topic' ); ?>

<?php
	$i = 1;
	if( have_posts() ) : while (have_posts()) : the_post(); 
		
		if( $paged == 1 && $i == 1 ) :
			// The first one is displayed with full details
?>
			<div class="grid-100" style="padding-bottom: 20px; text-decoration: underline"><h1><?php _e( 'Latest message', 'harvest' ); ?></h1></div>
<?php			
			get_template_part( 'templates/sermon', 'details' );
				
		else: 
			// Others are just displayed as boxes 
			if( $paged == 1 && $i == 2 ): ?>
				<div class="grid-100 ctc-sermon-grid-title ctc-sermon-others">
					<h2><?php echo  __( 'Other messages from this ', 'harvest') . strtolower( $title ); ?></h2>
				</div>
<?php endif; // $i == 2

				get_template_part( 'templates/sermon', 'grid' );

		endif; // $i==1? 
		$i++; 
	endwhile; endif; 	// loop
?>
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

		<div class="grid-container">
<?php harvest_pagination_new(); ?>
		</div>
		
<?php

	get_footer();
	

		
