<?php
	/* Single location */

	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full-width' );
		$permalink = get_permalink();

		// Location data
		$loc_address = get_post_meta( $post_id, '_ctc_location_address' , true ); 
		$loc_phone = get_post_meta( $post_id, '_ctc_location_phone' , true ); 
		$loc_times = get_post_meta( $post_id, '_ctc_location_times' , true ); 
		
		$img = ''; $map_url = $img;
		$map_used = false;
		if( $thumbnail )
			$img = $thumbnail[0];
		elseif( $loc_address ) {
			$url = urlencode( $loc_address );
			$map_url = "https://maps.googleapis.com/maps/api/staticmap?size=640x360&zoom=15&scale=2&center=$url&style=saturation:-25&markers=color:orange|$url";
			$img = $map_url;
			$map_used = true;
		}
?>

		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

				<div class="grid-60 push-40 ctc-loc-image">
<?php if( $img ): ?>
<?php if( $map_used ): ?><a href="http://maps.google.com/maps?q=<?php echo urlencode($loc_address); ?>" target="_blank"><?php endif; ?>
					
					<img src="<?php echo $img; ?>" class="ctc-loc-img"/>
<?php if( $map_used ): ?></a><?php endif; ?>					
<?php else: ?>
					<div class="ctc-location">
						<div class="ctc-grid-full accent-background">
							<h1 class="ctc-location-name"><?php the_title(); ?></h1>
						</div>
					</div>
<?php endif; ?>
				</div>
				<div class="grid-40 pull-60 ctc-loc-details">
					<div class="ctc-loc-content">
					
						<?php echo the_content(); ?>
						
					</div> <!-- .ctc-loc-content -->					
				
<?php if( $loc_times || $loc_address ): ?>
					<h2><?php _e( 'Times &amp; Location', 'harvest' ); ?></h2>
<?php endif; ?>
<?php if( $loc_times ): ?>
					<div class="ctc-loc-details-time">
						<i class="fa fa-clock-o icon-time"></i><?php echo nl2br( $loc_times ); ?>
					</div>
<?php endif; ?>				

<?php if( $loc_address ): ?>
					<div class="ctc-loc-details-address">
						<i class="fa fa-map-marker icon-map-marker"></i><?php echo $loc_address; ?>
					</div>
<?php endif; ?>
<?php if( $loc_phone ): ?>
					<div class="ctc-loc-details-phone">
						<i class="fa fa-mobile icon-mobile"></i><?php echo $loc_phone; ?>
					</div>
<?php endif; ?>

<?php if( $loc_address && ! $map_used ): ?>
					<div class="ctc-loc-details-map">
						<a href="http://maps.google.com/maps?q=<?php echo urlencode($loc_address); ?>" target="_blank">
							<img src="<?php echo $map_url; ?>" class="ctc-map"/>
						</a>
					</div>
<?php endif; ?>					

				</div> <!-- .ctc-loc-details -->
				
				<div class="grid-50 ctc-loc-widgets-left">
					<?php dynamic_sidebar( 'location-sidebar-left' ); ?>
				</div> <!-- .ctc-loc-widgets-left -->
				
				<div class="grid-50 ctc-loc-widgets-right">
					<?php dynamic_sidebar( 'location-sidebar-right' ); ?>
				</div> <!-- .ctc-loc-widgets-right -->
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
