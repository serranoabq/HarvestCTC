<?php
/**
 * TEMPLATE: HOME
 *
 */
?>
<?php get_header(); ?>
<?php if( harvest_option( 'slideshow' ) ) {
	// Front page slider is added in options via the slider shortcode
	do_action( harvest_option( 'slideshow' ) );
} ?>
	<div class="container">
<?php if( harvest_option( 'home_tagline' ) ) { ?>
	<div id="tag_line"><?php echo harvest_option( 'home_tagline' ); ?></div>
<?php } ?>
	<div class="row">
		<?php dynamic_sidebar( 'middle-home' ); ?>
	</div>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<div class="entry-excerpt" id="post-<?php the_ID(); ?>">
								<h4><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s', 'harvest-ctc' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
								
								<span class="date"><?php echo __( 'Published ', 'harvest-ctc' ) . the_time("F j, Y") ?></span>
								
								<?php the_excerpt(); ?>
								
								<a href="<?php the_permalink(); ?>" class="read_more"><strong>read more...</strong></a>
								
								<div class="clear"></div>
								
							</div> <!-- .entry-excerpt -->
							
<?php endwhile; endif; ?>

						</div> <!-- .entry -->
					</div> <!-- .post -->

<?php if( function_exists( 'harvest_pagination' ) ) {
	harvest_pagination( $additional_loop -> max_num_pages );
} ?>
				</div> <!-- #leftcol -->
				
				<div id="rightcol">
					<?php get_sidebar(); ?>
				</div> <!-- #rightcol -->

				<div class="clear"></div>

			</div> <!-- #container -->

			<div class="clear"></div>

		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>