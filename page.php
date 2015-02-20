<?php
	/* Single page */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		$color = get_post_meta( $post_id, '_post_accent_color', true );
		
?>

		<!-- TITLE BAR -->
		<div class="title_wrap accent-background" style="<?php echo $color ? "background: $color " : $accent_color ; ?>">
			<div class="grid-container title-bar" >
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">
		
<?php if( $thumbnail ): ?>
				<div class="grid-70 prefix-15 suffix-15">
					<img src="<?php echo $thumbnail[0]; ?>" class="featured-image"/>
				</div>
<?php endif; ?>
	
				<div class="grid-80 prefix-10 suffix-10 ctc-content">
					<?php echo the_content(); ?>
				</div>
				
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif;

	get_footer();
	
