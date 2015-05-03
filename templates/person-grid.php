<?php
	/* Person grid */
	$post_id = get_the_ID();
	$data = harvest_get_person_data( $post_id );
?>
				<div class="grid-25 mobile-grid-50  tiny-grid-100 ctc-person-grid"> 
					<a href="<?php echo $data[ 'permalink' ]; ?>">
						<div class="ctc-person">
							<div class="ctc-grid-details">
								<h3><?php the_title(); ?></h3>
<?php if( $data[ 'position'] ): ?>
								<div class="ctc-person-title"><?php echo $data[ 'position' ]; ?></div>
<?php endif; ?>								
							</div>
							<img class="ctc-person-img" src="<?php echo $data[ 'img' ]; ?>"/>
						</div> <!-- .ctc-person -->
					</a>
				</div> <!-- .ctc-person-grid -->