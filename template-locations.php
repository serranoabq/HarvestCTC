<?php
	/*
	Template Name: Location Archive
	*/
	
	get_header(); 
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		$color = get_post_meta( $post_id, '_post_accent_color', true );
		$title = explode( '/', harvest_option( 'ctc-locations' , __( 'Locations', 'harvest' ) ) );
		$title = array_shift( $title );
		harvest_title_bar( $title, $color );
?>
		<div class="content_wrap">

			<div class="grid-container content">

<?php if( $thumbnail ): ?>
				<div class="grid-100 featured-image-div">
					<img src="<?php echo $thumbnail[0]; ?>" class="featured-image"/>
				</div>
<?php endif; ?>

				<div class="grid-100 ctc-content">
					<?php echo the_content(); ?>
				</div>
					
			</div> <!-- .content.grid-container -->

<?php
	
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
		$permalink = get_permalink();

		// Location data
		$loc_address = get_post_meta( $post_id, '_ctc_location_address' , true ); 
		$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
		$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 
		
		$style = "background-image: url( 'https://maps.googleapis.com/maps/api/staticmap?size=500x250&zoom=15&scale=2&center=albuquerque&style=saturation:-50|gamma:2&markers=color:orange|albuquerque' );";
		if( $thumbnail ) {
			$img = $thumbnail[0];
			$style = "background-image: url( '$img' );";
		} elseif ( $loc_address ) {
			$url = urlencode( $loc_address );
			$style = "background-image: url( 'https://maps.googleapis.com/maps/api/staticmap?size=500x250&zoom=15&scale=2&center=$url&style=saturation:-50|gamma:2&markers=color:orange|$url' );";
		} 
		
?>

				<a href="<?php echo $permalink; ?>">
					<div class="grid-50 ctc-loc-grid" <?php echo 'style="' . $style . '"'; ?> >
						<div class="ctc-loc-title">
							<h3><?php echo the_title(); ?></h3>
						</div>
<?php if( $loc_times ): ?>						
						<div class="ctc-loc-grid-details">
							<?php echo nl2br($loc_times); ?>
						</div> <!-- .ctc-loc-grid-details -->
<?php endif; ?>
					</div> <!-- .ctc-event-grid -->
				</a>
<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
