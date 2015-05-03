<?php
	/* Event tag archive */
	
	get_header(); 
	$term = get_queried_object();
	$tags = get_terms( 'ctc_event_category', array( 'hide_empty' => 1 ) );
	foreach ($tags as $option) {
		$a_tags[] = sprintf( '<option value="%s" %s>%s</option>', get_term_link( intval( $option->term_id ), 'ctc_event_category' ), ($option->term_id == $term->term_id ? 'selected' : ''), $option->name );
	}
	array_unshift( $a_tags, '<option value="">Choose a category</option>' );
	$s_tags = implode('', $a_tags);
	
	harvest_title_bar( sprintf( _x( '%s events', 'Event category', 'harvest'), $term->name) );
?>
		<div class="content_wrap">

			<div class="grid-container content">
			
<?php if($a_tags): ?>
				<div class="grid-100 ctc-event-categories" style="text-align: right"><select onChange="window.location = jQuery(this).find('option:selected').val();">
				<?php echo $s_tags; ?>
				</select></div>
<?php endif; ?>
				
<?php
	do_action('__before_loop');
	
	if (have_posts()) :  
		if( $term -> description ): 	?>
				<div class="grid-100 ctc-event-category-desc" >
					<p><?php echo $term->description; ?></p>
				</div>
<?php	
	endif;
	while (have_posts()) : the_post(); 
		
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
	

		
