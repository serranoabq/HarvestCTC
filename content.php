<?php // content
	
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
				
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
		$permalink = get_permalink();

		// Event data
		$evt_start = get_post_meta( $post_id, '_ctc_event_start_date' , true ); 
		$evt_end = get_post_meta( $post_id, '_ctc_event_end_date' , true ); 
		$evt_time = get_post_meta( $post_id, '_ctc_event_time' , true ); 
		$evt_recurrence = get_post_meta( $post_id, '_ctc_event_recurrence' , true ); 
		$evt_recur_period = get_post_meta( $post_id, '_ctc_event_recurrence_period' , true ); 
		$evt_recur_end = get_post_meta( $post_id, '_ctc_event_recurrence_end_date' , true ); 
		$evt_venue = get_post_meta( $post_id, '_ctc_event_venue' , true ); 
		$evt_addr = get_post_meta( $post_id, '_ctc_event_address' , true ); 
		$evt_show_loc = get_post_meta( $post_id, '_ctc_event_show_directions_link' , true ); 
?>
<div class="ctc-block ctc-event">
<?php if( $thumbnail ): ?>
	<img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/>
<?php endif; ?>				

	<div class="ctc-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>

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
				printf(_n('Recurs Daily','Recurs Every %d Days',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
				break;
			case 'weekly' :
					printf(_n('Recurs Weekly','Recurs Every %d Weeks',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
				break;
			case 'monthly' :
				printf(_n('Recurs Monthly','Recurs Every %d Months',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
				break;
			case 'yearly' :
				printf(_n('Recurs Yearly','Recurs Every %d Years',(int)$evt_recur_period, 'ctc-shortcodes'), (int)$evt_recur_period);
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

</div>
<?php 
						
					
		endwhile;
	endif;
	
	harvest_pagination();
?>