<?php
	/* Event archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
<?php if( harvest_option( 'ctc-events' ) ): ?>
					<h2><?php echo harvest_option ( 'ctc-events' , __( 'Events', 'harvest' ) ); ?></h2>
<?php endif; ?>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'ctc-wide' );
		$permalink = get_permalink();
		
		
		// Event data
		$evt_start = get_post_meta( $post_id, '_ctc_event_start_date' , true ); 
		$evt_end = get_post_meta( $post_id, '_ctc_event_end_date' , true ); 
		$evt_time = date('g:ia', strtotime( get_post_meta( $post_id, '_ctc_event_start_time' , true ) ) ); 
		$evt_recurrence = get_post_meta( $post_id, '_ctc_event_recurrence' , true ); 
		$evt_recur_period = get_post_meta( $post_id, '_ctc_event_recurrence_period' , true ); 
		$evt_recur_end = get_post_meta( $post_id, '_ctc_event_recurrence_end_date' , true ); 
		$evt_venue = get_post_meta( $post_id, '_ctc_event_venue' , true ); 
		$evt_address = get_post_meta( $post_id, '_ctc_event_address' , true ); 
		
		if( strtotime( $evt_start ) < strtotime( date('Y-m-d') ) ) continue;
		
		$img = " https://maps.googleapis.com/maps/api/staticmap?size=500x280&zoom=15&scale=2&center=albuquerque&style=saturation:-25&markers=color:orange|albuquerque";
		if( $thumbnail ) {
			$img = $thumbnail[0];
		} elseif ( $evt_address ) {
			$url = urlencode( $evt_address );
			$img = " https://maps.googleapis.com/maps/api/staticmap?size=500x280&zoom=15&scale=2&center=$url&style=saturation:-25&markers=color:orange|$evt_address";
		} 
		
?>
				<div class="grid-33 ctc-event-grid">
					<a href="<?php echo $permalink; ?>">
						<div class="ctc-event">
							<div class="ctc-grid-details">
								<h3><?php echo the_title(); ?></h3>
								<div class="ctc-date">
									<?php echo date( 'D, M j Y', strtotime( $evt_start ) ); if ( $evt_end != $evt_start) echo '-'. date( 'D, M j Y', strtotime( $evt_end ) ); if( $evt_time ) echo ' @ ' . $evt_time; ?>
								</div>
							</div> <!-- .ctc-grid-details -->
							<img src="<?php echo $img; ?>" class="ctc-event-img"/>
						</div> <!-- .ctc-event -->
					</a>
				</div> <!-- .ctc-event-grid -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
