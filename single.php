<?php
	/* Single post */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		
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
				<img src="<?php echo $thumbnail[0]; ?>" class="featured-image grid-50 prefix-25 suffix-25"/>
<?php endif; ?>

				<?php echo the_content(); ?>
					
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	
