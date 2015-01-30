<?php
	/* Single event */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full-width' );
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

?>

		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<!-- IMAGE -->
<?php if( $thumbnail ): ?>
		<div class="image_wrap">
				<img src="<?php echo $thumbnail[0]; ?>" class="header-img full-width"/>
		</div>
<?php endif; ?>

		<div class="content_wrap">

			<div class="grid-container content">

				<div class="grid-50 ctc-event-details">

				<?php if( $evt_start ): ?>
					<div class="ctc-date"><i class="fa-calendar icon-calendar"></i><?php echo date( 'D, M d Y', strtotime( $evt_start ) ); if ( $evt_end != $evt_start) echo '-'. date( 'D, M d Y', strtotime( $evt_end ) ); ?></div>
<?php endif; ?>

<?php if( $evt_time ): ?>
					<div class="ctc-time"><i class="fa-time icon-time"></i><?php echo $evt_time; ?></div>
<?php endif; ?>				

<?php if ( $evt_recurrence && $evt_recurrence != 'none' ) : ?>
					<div class="ctc-recurrence"><i><?php 
							
						switch ( $evt_recurrence ) {
							case 'daily' : 
								printf( _n( 'Recurs Daily', 'Recurs Every %d Days', (int)$evt_recur_period, 'harvest-ctc' ), (int)$evt_recur_period );
								break;
							case 'weekly' :
									printf( _n( 'Recurs Weekly', 'Recurs Every %d Weeks', (int)$evt_recur_period, 'harvest-ctc' ), (int)$evt_recur_period );
								break;
							case 'monthly' :
								printf( _n( 'Recurs Monthly', 'Recurs Every %d Months', (int)$evt_recur_period, 'harvest-ctc' ), (int)$evt_recur_period );
								break;
							case 'yearly' :
								printf( _n( 'Recurs Yearly', 'Recurs Every %d Years', (int)$evt_recur_period, 'harvest-ctc' ), (int)$evt_recur_period );
								break;
							} // switch

						?></i></div>
<?php	endif;  ?>

<?php if( $evt_venue ): ?>
					<div class="ctc-venue"><i class="fa-building icon-building"></i><?php echo $evt_venue; ?></div>
<?php endif; ?>					

<?php if( $evt_address ): ?>
					<div class="ctc-address"><i class="fa-map-marker icon-map-marker"></i><?php echo $evt_address; ?></div>
<?php endif; ?>					

				</div> <!-- .ctc-event-details -->
				
				<div class="grid-50 ctc-event-map">
<?php if( $evt_address ): ?>
					<a href="http://maps.google.com/maps?q=<?php echo urlencode($evt_address); ?>" target="_blank">
						<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($evt_address); ?>&zoom=15&size=480x480&sensor=false&scale=2&style=saturation:-50|gamma:0.5&markers=color:orange|<?php echo urlencode($evt_address); ?>" />
					</a>
<?php endif; ?>				
				</div> <!-- .ctc-event-map -->
				
				<div class="grid-100 ctc-event-content">
				
					<?php echo the_content(); ?>
					
				</div> <!-- .ctc-event-content -->
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
