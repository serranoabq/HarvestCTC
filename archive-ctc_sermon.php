<?php
	/* Sermon archive */
	
	get_header(); 
	
	// Create a dropdown for the locations
	$tags = get_terms( 'ctc_sermon_topic', array( 'hide_empty' => 1 ) );
	foreach ($tags as $option) {
		$a_tags[] = sprintf( '<option value="%s">%s</option>', get_term_link( intval( $option->term_id ), 'ctc_sermon_topic' ), $option->name );
	}
	$title = array_pop( explode( '/', harvest_option( 'ctc-sermon-topic' , __( 'Topic', 'harvest' ) ) ) );
	array_unshift( $a_tags, sprintf( '<option value="">' . __( 'Choose a %s', 'harvest' ) . '</option>', $title ) );
	$s_tags = implode('', $a_tags);
	
	global $paged; 
	if( empty($paged) ) $paged = 1;
	
	$title = array_shift( explode( '/', harvest_option( 'ctc-sermons' , __( 'Sermons', 'harvest' ) ) ) );
	harvest_title_bar( $title );
?>
		
		<div class="content_wrap">

			<div class="grid-container content">
			
<?php if($a_tags): ?>
				<div class="grid-100 ctc-sermon-topics" style="text-align: right"><select onChange="window.location = jQuery(this).find('option:selected').val();">
				<?php echo $s_tags; ?>
				</select></div>
<?php endif; ?>

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
	

		
