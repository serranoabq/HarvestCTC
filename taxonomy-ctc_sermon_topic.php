<?php
	/* Sermon topic/location archive */
	global $paged; 
	if( empty($paged) ) $paged = 1;
	
	get_header(); 
	$term = get_queried_object();
	
	// Create a dropdown for the locations
	$tags = get_terms( 'ctc_sermon_topic', array( 'hide_empty' => 1 ) );
	foreach ($tags as $option) {
		$a_tags[] = sprintf( '<option value="%s" %s>%s</option>', get_term_link( intval( $option->term_id ), 'ctc_sermon_topic' ), ($option->term_id == $term->term_id ? 'selected': '' ),$option->name );
	}
	array_unshift( $a_tags, sprintf( '<option value="">Choose a %s</option>', harvest_option( 'ctc-sermon-topic', 'Topic' ) ) );
	$s_tags = implode('', $a_tags);
	
	$select = "<select onChange=\"window.location = jQuery(this).find('option:selected').val();\">$s_tags;</select>";
	
	$title = array_pop( explode( '/', ctcex_get_option( 'ctc-sermon-topic' , __( 'Topic', 'harvest' ) ) ) );
	harvest_title_bar( sprintf( '%s: %s', $title, $term->name )	);
	
?>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php if($a_tags): ?>
				<div class="grid-100 ctc-sermon-topics" style="text-align: right"><select onChange="window.location = jQuery(this).find('option:selected').val();">
				<?php echo $s_tags; ?>
				</select></div>
<?php endif; ?>

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
	

		
