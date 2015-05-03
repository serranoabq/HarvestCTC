<?php
	/* Event grid */
	$post_id = get_the_ID();
	$data = harvest_get_event_data( $post_id );
?>
				<div class="grid-33 mobile-grid-50 tiny-grid-100 ctc-event-grid">
					<a href="<?php echo $data[ 'permalink' ]; ?>">
						<div class="ctc-event">
							<div class="ctc-grid-details">
								<h3><?php echo the_title(); ?></h3>
<?php if( $data[ 'start' ] ): ?>									
								<div class="ctc-date">
									<?php echo date_i18n( 'D, M j Y', strtotime( $data[ 'start' ] ) ); if ( $data[ 'end' ] != $data[ 'start' ]) echo '-'. date_i18n( 'D, M j Y', strtotime( $data[ 'end' ] ) ); if( $data[ 'time' ] ) echo ' @ ' . $data[ 'time' ]; ?>
								</div>
<?php endif; // date ?>	
							</div> <!-- .ctc-grid-details -->
							<img src="<?php echo $data[ 'img' ]; ?>" class="ctc-event-img"/>
						</div> <!-- .ctc-event -->
					</a>
				</div> <!-- .ctc-event-grid -->
				