<?php

	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php _e( 'Events', 'harvest-ctc' ); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
		$permalink = get_permalink();

		// Location data
		$loc_address = get_post_meta( $post_id, '_ctc_location_address' , true ); 
		$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
		$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 
		
		style = "background: url( 'https://maps.googleapis.com/maps/api/staticmap?size=500x500&zoom=15&scale=2&center=albuquerque&style=saturation:-50|gamma:2&markers=color:orange|albuquerque' );";
		if( $thumbnail ) {
			$img = $thumbnail[0];
			$style = "background: url( '$img' );"
		} elseif ( $loc_address ) {
			style = "background: url( 'https://maps.googleapis.com/maps/api/staticmap?size=500x500&zoom=15&scale=2&center=$loc_address&style=saturation:-50|gamma:2&markers=color:orange|$loc_address' );";
		} 
		
?>

				<div class="grid-50 ctc-loc-grid" <?php echo 'style="' . $style . '"'; ?> >
					<div class="ctc-loc-title">
						<a href="<?php echo $permalink; ?>"><?php echo the_title(); ?></a>
					</div>
				</div> <!-- .ctc-event-grid -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
