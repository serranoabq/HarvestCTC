<?php
	/* Location details */
	$post_id = get_the_ID();
	$data = harvest_get_location_data( $post_id );
?>
				<div class="grid-60 push-40 ctc-loc-image">
<?php if( $data[ 'slider' ] ) : // Slider specified ?>
					
					<div class="ctc-loc-img"><?php echo do_shortcode( $data[ 'slider' ] ); ?></div>
					
<?php elseif( $data[ 'img' ] ) : // Image specified ?>

<?php	if( $data[ 'map_used' ] ): ?><a href="<?php echo $data[ 'map_url' ];?>" target="_blank"><?php endif; ?>			
					<img src="<?php echo $data[ 'img' ]; ?>" class="ctc-loc-img"/>
<?php	if( $data[ 'map_used' ] ): ?></a><?php endif; ?>					

<?php endif; ?>
				</div> <!-- ctc-loc-image -->

				<div class="grid-40 pull-60 ctc-loc-details">

<?php if( $data[ 'times' ] || $data[ 'address' ] ): ?>
					<h2><?php _e( 'Times &amp; Location', 'harvest' ); ?></h2>
<?php endif; ?>
<?php if( $data[ 'times' ] ): ?>
					<div class="ctc-loc-details-time">
						<i class="fa fa-clock-o icon-time"></i><?php echo nl2br( $data[ 'times' ] ); ?>
					</div>
<?php endif; ?>				

<?php if( $data[ 'address' ] ): ?>
					<div class="ctc-loc-details-address">
						<i class="fa fa-map-marker icon-map-marker"></i><?php echo $data[ 'address' ]; ?>
					</div>
<?php endif; ?>

<?php if( $data[ 'phone' ] ): ?>
					<div class="ctc-loc-details-phone">
						<i class="fa fa-mobile icon-mobile"></i><?php echo $data[ 'phone' ]; ?>
					</div>
<?php endif; ?>

<?php if( $data[ 'address' ] && ! $data[ 'map_used' ] ): ?>
					<div class="ctc-loc-details-map">
						<a href="<?php echo $data[ 'map_url' ]; ?>" target="_blank">
							<img src="<?php echo $data[ 'map_img_url' ]; ?>" class="ctc-map"/>
						</a>
					</div>
<?php endif; ?>					

				</div> <!-- .ctc-loc-details -->
				
				<div class="grid-100 ctc-loc-content">
					
						<?php the_content(); ?>
						
				</div> <!-- .ctc-loc-content -->	

				