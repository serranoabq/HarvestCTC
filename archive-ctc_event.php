<?php
	/* Event archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
<?php if( harvest_option( 'ctc-events' ) ): ?>
					<h2><?php echo harvest_option ( 'ctc-events' ); ?></h2>
<?php else: ?>					
					<h2><?php _e( 'Events', 'harvest' ); ?></h2>
<?php endif; ?>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
		$permalink = get_permalink();

		// Event data
		$evt_start = get_post_meta( $post_id, '_ctc_event_start_date' , true ); 
		$evt_end = get_post_meta( $post_id, '_ctc_event_end_date' , true ); 
		$evt_time = get_post_meta( $post_id, '_ctc_event_time' , true ); 
		$evt_recurrence = get_post_meta( $post_id, '_ctc_event_recurrence' , true ); 
		$evt_recur_period = get_post_meta( $post_id, '_ctc_event_recurrence_period' , true ); 
		$evt_recur_end = get_post_meta( $post_id, '_ctc_event_recurrence_end_date' , true ); 
		$evt_venue = get_post_meta( $post_id, '_ctc_event_venue' , true ); 
		$evt_address = get_post_meta( $post_id, '_ctc_event_address' , true ); 
		
		$style = "background: url( 'https://maps.googleapis.com/maps/api/staticmap?size=500x500&zoom=15&scale=2&center=albuquerque&style=saturation:-50|gamma:2&markers=color:orange|albuquerque' );";
		if( $thumbnail ) {
			$img = $thumbnail[0];
			$style = "background: url( '$img' );"
		} elseif ( $evt_address ) {
			style = "background: url( 'https://maps.googleapis.com/maps/api/staticmap?size=500x500&zoom=15&scale=2&center=$evt_address&style=saturation:-50|gamma:2&markers=color:orange|$evt_address' );";
		} 
		
?>

				<div class="grid-30 ctc-event-grid" <?php echo 'style="' . $style . '"'; ?> >
					<div class="ctc-event-title">
						<a href="<?php echo $permalink; ?>"><?php echo the_title(); ?></a>
					</div>
					<div class="ctc-event-grid-details">
					
<?php if( $evt_start ): ?>
						<div class="ctc-date"><i class="fa-calendar icon-calendar"></i><?php echo date( 'D, M d Y', strtotime( $evt_start ) ); if ( $evt_end != $evt_start) echo '-'. date( 'D, M d Y', strtotime( $evt_end ) ); ?></div>
<?php endif; ?>

<?php if( $evt_time ): ?>
						<div class="ctc-time"><i class="fa-time icon-time"></i><?php echo $evt_time; ?></div>
<?php endif; ?>				

<?php if( $evt_venue ): ?>
						<div class="ctc-venue"><i class="fa-building icon-building"></i><?php echo $evt_venue; ?></div>
<?php endif; ?>					

					</div> <!-- .ctc-event-grid-details -->
				</div> <!-- .ctc-event-grid -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
