<?php
	/* Single person */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
		$permalink = get_permalink();

		// Person data
		$per_position = get_post_meta( $post_id, '_ctc_person_position' , true ); 
		$per_email = get_post_meta( $post_id, '_ctc_person_email' , true ); 
		$per_phone = get_post_meta( $post_id, '_ctc_person_phone' , true ); 
		
?>

		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php if( $thumbnail ): ?>

				<div class="grid-50 ctc-person-photo">
				
					<div class="image_wrap"><img src="<?php echo $thumbnail[0]; ?>" class="ctc-img"/></div>
					
				</div>
				
				<div class="grid-50 ctc-person-details">		
				
<?php else; ?>

				<div class="grid-100 ctc-person-details">		

<?php endif; ?>

<?php if( $per_position ): ?>
					<div class="ctc-person-title"><h3><?php echo $per_position; ?></h3></div>
<?php endif; ?>

<?php if( $per_email ): ?>
					<div class="ctc-person-title"><i class="fa fa-envelope"></i><?php echo $per_email; ?></div>
<?php endif; ?>
				
					<div class="ctc-person-content">

						<?php echo the_content(); ?>

					</div> <!-- .ctc-person-content -->
				
				</div> <!-- .ctc-person-details -->
				
			</div> <!-- .content.grid-container -->
			
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	

		
