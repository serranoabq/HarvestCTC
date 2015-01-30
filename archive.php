<?php
	/* Std archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php _e( 'Archive', 'harvest' ); ?></h2>
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
?>

				<div class="grid-100">
<?php if( $thumbnail ): ?><img src="<?php echo $thumbnail[0]; ?>" class="left" /><?phpendif; ?>					
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
	

		
