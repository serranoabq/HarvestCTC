<?php
	/* Sermon archive */
	
	get_header(); 
	
	global $paged; 
	if( empty($paged) ) $paged = 1;
	$title = explode( '/', harvest_option( 'ctc-sermons' , __( 'Sermons', 'harvest' ) ) );
	$title = array_shift( $title );
	harvest_title_bar( $title );
?>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php harvest_get_tax_dropdown( 'ctc_sermon_topic' ); ?>			

				<div class="grid-100 ctc-content-sidebar">
					<?php dynamic_sidebar( 'sermon-sidebar' ); ?>
				</div>

<?php
	$i = 1;
	do_action('__before_loop');
	if( have_posts() ) : while (have_posts()) : the_post(); 
		
		if( $paged == 1 && $i == 1 ) :
			// The first one is displayed in full
?>
			<div class="grid-100" style="padding-bottom: 20px; text-decoration: underline"><h1><?php _e( 'Latest message', 'harvest' ); ?></h1></div>
<?php
			get_template_part( 'templates/sermon', 'details' );
			
		else:
			if( $paged == 1 && $i == 2 ): ?>
				<div class="grid-100 ctc-sermon-grid-title ctc-sermon-others">
					<h2><?php echo  __( 'Other messages', 'harvest'); ?></h2>
				</div>
<?php endif; // $i == 2
			get_template_part( 'templates/sermon', 'grid' );
			 
		endif; // paged == 1? 
		
		$i++; 
	endwhile; endif; 	
	
?>
				
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

		<div class="grid-container">
<?php harvest_pagination_new(); ?>
		</div>
		
<?php
		
	get_footer();
	

		
