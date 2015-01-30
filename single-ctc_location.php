<?php

	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full-width' );
		$permalink = get_permalink();

		// Location data
		$loc_address = get_post_meta( $post_id, '_ctc_location_address' , true ); 
		$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
		$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 

?>

		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<!-- IMAGE -->
<?php if( $thumbnail ): ?>
		<div class="image_wrap">
				<img src="<?php echo $thumbnail[0]; ?>" class="header-img full-width"/>
		</div>
<?php endif; ?>

		<div class="content_wrap">

			<div class="grid-container content">

				<div class="grid-50 ctc-loc-details">

<?php if( $loc_times ): ?>
					<div class="ctc-time"><i class="fa-time icon-time"></i><?php echo $loc_times; ?></div>
<?php endif; ?>				

<?php if( $loc_address ): ?>
					<div class="ctc-address"><i class="fa-map-marker icon-map-marker"></i><?php echo $loc_address; ?></div>
					<div class="ctc-map">
						<a href="http://maps.google.com/maps?q=<?php echo urlencode($evt_address); ?>" target="_blank">
							<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($evt_address); ?>&zoom=15&size=200x200&sensor=false&scale=2&style=saturation:-50|gamma:0.5&markers=color:orange|<?php echo urlencode($evt_address); ?>" />
						</a>
					</div>
<?php endif; ?>					

				</div> <!-- .ctc-loc-details -->
				
				<div class="grid-50 ctc-loc-widgets-right">
					<?php dynamic_sidebar( 'location-sidebar-right' ); ?>
				</div> <!-- .ctc-loc-widgets-right -->
				
				<div class="grid-100 ctc-loc-content">
				
					<?php echo the_content(); ?>
					
				</div> <!-- .ctc-loc-content -->
				
				<div class="grid-100 ctc-loc-widgets-bottom">
				
					<?php dynamic_sidebar( 'location-sidebar-bottom' ); ?>
					
				</div> <!-- .ctc-loc-widgets-bottom -->
				
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
