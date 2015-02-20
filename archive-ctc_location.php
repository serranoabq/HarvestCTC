<?php
	/* Location archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap  accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
<?php if( harvest_option( 'ctc-locations' ) ): ?>
					<h2><?php echo harvest_option ( 'ctc-locations' ); ?></h2>
<?php else: ?>					
					<h2><?php _e( 'Locations', 'harvest' ); ?></h2>
<?php endif; ?>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

				<div class="grid-100 ctc-loc-content">
				
					<?php dynamic_sidebar( 'location-archive-sidebar' ); ?>
					
				</div> <!-- .ctc-loc-content -->
				
<?php
	query_posts($query_string . '&orderby=menu_order&order=ASC');
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'ctc-wide' );
		$permalink = get_permalink();

		// Location data
		$loc_address = get_post_meta( $post_id, '_ctc_location_address' , true ); 
		$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
		$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 
		
		$img = "https://maps.googleapis.com/maps/api/staticmap?size=500x280&zoom=15&scale=2&center=albuquerque&style=saturation:-25&markers=color:orange|albuquerque";
		if( $thumbnail ) {
			$img = $thumbnail[0];
		} elseif ( $loc_address ) {
			$url = urlencode( $loc_address );
			$img = "https://maps.googleapis.com/maps/api/staticmap?size=500x280&zoom=15&scale=2&center=$url&style=saturation:-25&markers=color:orange|$url";
		} 
		
?>

				<div class="grid-50 ctc-loc-grid">
					<a href="<?php echo $permalink; ?>">
						<div class="ctc-loc">
							<div class="ctc-grid-details">
								<h3><?php echo the_title(); ?></h3>
<?php if( $loc_times ): ?>
								<div class="loc-times"><?php echo nl2br($loc_times); ?></div>
<?php endif; ?>
							</div> <!-- .ctc-grid-details -->
							<img src="<?php echo $img; ?>" class="ctc-loc-img"/>
						</div> <!-- .ctc-loc -->
					</a>
				</div> <!-- .ctc-loc-grid -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
