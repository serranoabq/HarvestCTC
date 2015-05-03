<?php
	/* Location grid */
	$post_id = get_the_ID();
	$data = harvest_get_location_data( $post_id );
?>
				<div class="grid-50 ctc-loc-grid"> 
					<a href="<?php echo $data[ 'permalink' ]; ?>">
						<div class="ctc-loc">
							<div class="ctc-grid-details">
								<h3><?php the_title(); ?></h3>
<?php if( $data[ 'times'] ): ?>
								<div class="loc-times"><?php echo nl2br( $data[ 'times' ] ); ?></div>
<?php endif; ?>								
							</div> <!-- .ctc-loc-details -->
							<img class="ctc-loc-img" src="<?php echo $data[ 'img' ]; ?>"/>
						</div> <!-- .ctc-loc -->
					</a>
				</div> <!-- .ctc-loc-grid --> 
