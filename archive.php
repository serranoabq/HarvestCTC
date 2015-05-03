<?php
	/* Std archive */
	
	get_header(); 
	harvest_title_bar( __( 'Archive', 'harvest' ) );
?>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
		$permalink = get_permalink();
?>

				<div class="grid-100">
<?php if( $thumbnail ): ?><img src="<?php echo $thumbnail[0]; ?>" class="left" /><?php endif; ?>					
					<div class="ctc-loc-title">
						<a href="<?php echo $permalink; ?>"><?php echo the_title(); ?></a>
					</div>
				</div> <!-- .grid-100 -->

<?php endwhile; endif; ?>

			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
