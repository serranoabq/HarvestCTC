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
		$evt_time = date('g:ia', strtotime( get_post_meta( $post_id, '_ctc_event_start_time' , true ) ) ); 
		$evt_recurrence = get_post_meta( $post_id, '_ctc_event_recurrence' , true ); 
		$evt_recur_period = get_post_meta( $post_id, '_ctc_event_recurrence_period' , true ); 
		$evt_recur_end = get_post_meta( $post_id, '_ctc_event_recurrence_end_date' , true ); 
		$evt_venue = get_post_meta( $post_id, '_ctc_event_venue' , true ); 
		$evt_address = get_post_meta( $post_id, '_ctc_event_address' , true ); 
		
		switch ( $evt_recurrence ) {
		case 'daily' : 
			$recurrence = sprintf( _n( 'Occurs Daily', 'Occurs Every %d Days', (int)$evt_recur_period, 'harvest' ), (int)$evt_recur_period );
			break;
		case 'weekly' :
			if( (int)$evt_recur_period == 1 ) 
				$recurrence = 'Occurs Every ' . date( 'l', strtotime( $evt_start ) );
			else	
				$recurrence = sprintf( __( 'Occurs Every %d Weeks', 'harvest' ), (int)$evt_recur_period);
			break;
		case 'monthly' :
			$recurrence = sprintf( _n( 'Occurs Monthly', 'Occurs Every %d Months', (int)$evt_recur_period, 'harvest' ), (int)$evt_recur_period );
			break;
		case 'yearly' :
			$recurrence = sprintf( _n( 'Occurs Yearly', 'Occurs Every %d Years', (int)$evt_recur_period, 'harvest' ), (int)$evt_recur_period );
			break;
		} // switch
		
?>

		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">
				<div class="grid-40 suffix-10 push-50">

<?php if( $thumbnail ): ?>
					<img src="<?php echo $thumbnail[0]; ?>" class="ctc-event-img"/>
<?php elseif( $evt_address ): ?>
					<a href="http://maps.google.com/maps?q=<?php echo urlencode($evt_address); ?>" target="_blank"><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($evt_address); ?>&zoom=14&size=300x169&scale=2&sensor=false&scale=1&style=saturation:-50|gamma:1&markers=color:orange|<?php echo urlencode($evt_address); ?>" class="ctc-event-map"/></a>
<?php endif; ?>&nbsp;	
				</div> 
				
				<div class="grid-50 pull-50 ctc-event-details">
					<div><h2><?php _e( 'Details', 'harvest' ); ?></h2></div>
					<div class="ctc-date">
						<i class="fa fa-calendar"></i><?php echo date( 'l, F j Y', strtotime( $evt_start ) ); if ( $evt_end != $evt_start) echo '-'. date( 'l, F j Y', strtotime( $evt_end ) ); ?> 
					</div>
<?php if( $evt_time ): ?>
					<div class="ctc-time"><i class="fa fa-clock-o"></i><?php echo $evt_time; ?></div>
<?php endif; // evt_time ?>				

<?php if ( $evt_recurrence && $evt_recurrence != 'none' ) : ?>
					<div class="ctc-recurrence">
						<i class="fa fa-refresh"></i><i><?php echo $recurrence; ?></i>
					</div>
<?php	endif;  // evt_recurrence ?>
				
<?php if( $evt_venue ): ?>
					<div class="ctc-venue"><?php echo $evt_venue; ?></div>
<?php endif; // evt_venue ?>					

<?php if( $evt_address ): ?>
					<div class="ctc-address"><i class="fa fa-map-marker"></i><a href="http://maps.google.com/maps?q=<?php echo urlencode($evt_address); ?>" target="_blank"><?php echo $evt_address; ?></a></div>
<?php endif; // evt_address ?>	
				
<?php if( get_the_content() != ''): ?>
					<div class="ctc-event-content">
						<h2><?php _e( 'Summary', 'harvest' ); ?></h2>
						<?php the_content(); ?>
					</div> <!-- .ctc-event-content -->
<?php endif; // evt_content ?>					
		
				</div> <!-- .ctc-event-details -->
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
