<?php
	/* Template Name: Sidebar Left */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		harvest_title_bar( get_the_title() );
?>

		<div class="content_wrap">

			<div class="grid-container content">
			
				<div class="grid-33 sidebar">
					<?php get_sidebar(); ?>
				</div>
				
				<div class="grid-66 ctc-content">
				
<?php if( $thumbnail ): ?>
					<img src="<?php echo $thumbnail[0]; ?>" class="featured-image"/>
<?php endif; ?>
				
					<?php echo the_content(); ?>
				</div>
				
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap --> 
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	
