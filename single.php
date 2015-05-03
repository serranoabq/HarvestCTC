<?php
	/* Single post */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		harvest_title_bar( get_the_title() );
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
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	
