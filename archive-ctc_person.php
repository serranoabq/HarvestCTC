<?php
	/* People archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
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
	query_posts($query_string . '&orderby=menu_order&order=ASC');
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'ctc-tall' );
		$permalink = get_permalink();

		// Person data
		$per_position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
		$per_email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
		$per_phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
		$per_url = get_post_meta( $post_id, '_ctc_person_url' , true ); 
		
		$img = get_stylesheet_directory_uri() . '/images/user.png';
		if( $thumbnail ) $img = $thumbnail[0];
		
?>

				<div class="grid-25 mobile-grid-50 ctc-person-grid">
					<a href="<?php echo $permalink; ?>">
						<div class="ctc-person">
							<div class="ctc-grid-details">
								<h3><?php echo the_title(); ?></h3>
<?php if( $per_position ): ?>
								<div class="ctc-person-title"><?php echo $per_position; ?></div>
<?php endif; ?>
<?php /* 
<?php if( $per_email || $per_phone || $per_url ): ?>
								<div class="ctc-person-contact">
<?php if( $per_email ): ?>
									<a href="mailto:<?php echo $per_email; ?>"><i class="fa fa-envelope"></i></a>
<?php endif; ?>
<?php if( $per_phone ): ?>
									<a href="tel:<?php echo $per_phone; ?>"><i class="fa fa-phone"></i></a>
<?php endif; ?>
<?php if( $per_url ): ?>
									<a href="<?php echo $per_url; ?>"><i class="fa fa-globe"></i></a>
<?php endif; ?>
						</div>
<?php endif; ?>
*/ ?>
							</div> <!-- .ctc-grid-details -->
							<img src="<?php echo $img; ?>" class="ctc-person-img"/>
						</div> <!-- .ctc-person -->
					</a>
				</div> <!-- .ctc-person-grid -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
