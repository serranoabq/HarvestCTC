<?php
	/* People archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
<?php if( harvest_option( 'ctc-people' ) ): ?>
					<h2><?php echo harvest_option ( 'ctc-people' ); ?></h2>
<?php else: ?>					
					<h2><?php _e( 'People', 'harvest' ); ?></h2>
<?php endif; ?>
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

		// Person data
		$per_position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
		$per_email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
		$per_phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
		
		$img = trailingslashit( get_stylesheet_directory_uri() ) . 'images/user.png';
		if( $thumbnail ) $img = $thumbnail[0];
		$style = "background: url( '$img' );"
		
?>

				<div class="grid-30 ctc-person-grid" <?php echo 'style="' . $style . '"'; ?> >
					<div class="ctc-person-details">
						<div class="ctc-person-name"><a href="<?php echo $permalink; ?>"><?php echo the_title(); ?></a></div>
<?php if( $per_position ): ?>
						<div class="ctc-person-title"><?php echo $per_position; ?></div>
<?php endif; ?>

					</div> <!-- .ctc-person-grid-details -->
				</div> <!-- .ctc-person-grid -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
