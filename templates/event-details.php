<?php
	/* Event details */
	$post_id = get_the_ID();
	$data = harvest_get_event_data( $post_id );
?>

				<div class="grid-60 push-40 ctc-event-img-div">
<?php if( $data[ 'img' ] ): ?>	
				
<?php	if( $data[ 'map_used' ] ): ?><a href="<?php echo $data[ 'map_url' ];?>" target="_blank"><?php endif; ?>	
					<img src="<?php echo $data[ 'img' ]; ?>" class="ctc-event-img"/>
<?php	if( $data[ 'map_used' ] ): ?></a><?php endif; ?>	

<?php endif; // img ?>
				</div>
				
				<div class="grid-40 pull-60 ctc-event-details">
					<h2><?php _e( 'Details', 'harvest' ); ?></h2>

<?php if( $data[ 'start' ] ): ?>					
					<div class="ctc-event-details-date">
						<i class="fa fa-calendar"></i><?php echo date_i18n( 'l, F j Y', strtotime( $data[ 'start' ] ) ); if ( $data[ 'start' ] != $data[ 'end' ]) echo '-'. date_i18n( 'l, F j Y', strtotime( $data[ 'end' ] ) ); ?> 
					</div>
<?php endif; // date ?>
				
<?php if( $data[ 'time' ] ): ?>
					<div class="ctc-event-details-time"><i class="fa fa-clock-o"></i><?php echo $data[ 'time' ]; if( $data[ 'endtime' ] ) echo ' - ' . $data[ 'endtime' ]; ?></div>
<?php endif; // time ?>				

<?php if ( $data[ 'recurrence_note' ] && $data[ 'recurrence' ] != 'none' ) : ?>
					<div class="ctc-event-details-recurrence">
						<i class="fa fa-refresh"></i><i><?php echo $data[ 'recurrence_note' ]; ?></i>
					</div>
<?php	endif;  // recurrence ?>

<?php if( $data[ 'venue' ] ): ?>
					<div class="ctc-event-details-venue"><i class="fa fa-building-o"></i><?php echo $data[ 'venue' ]; ?></div>
<?php endif; // venue ?>					

<?php if( $data[ 'address' ] ): ?>
					<div class="ctc-event-details-address"><i class="fa fa-map-marker"></i><?php echo $data[ 'address' ]; ?> (<a href="<?php echo $data[ 'map_url' ]; ?>" target="_blank"><?php _e( 'get directions', 'harvest' ); ?></a>)</div>
<?php endif; // address ?>	

<?php if( $data[ 'categories' ] ): ?>
					<div class="ctc-event-details-tag"><i class="fa fa-tag"></i><?php echo $data[ 'categories' ]; ?></div>
<?php endif; // categories ?>	
								
				</div> <!-- .ctc-event-details -->
				
				<div class="grid-100 ctc-event-content">
<?php if( get_the_content() != ''): ?>
					<h2><?php _e( 'Summary', 'harvest' ); ?></h2>
<?php endif; // content ?>					
					<?php the_content(); ?>
				</div> <!-- .ctc-event-content -->
		
				
				