<?php
/**
 * TEMPLATE: SINGLE
 *
 */
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<h2 class="title"><?php the_title(); ?></h2>
						<?php the_post_thumbnail(); ?>
						
						<div class="entry">
							<?php the_content(); ?>
						</div> <!-- //. entry -->
						
<?php endwhile; endif; ?>

					</div> <!-- .post -->
				</div> <!-- .main_feature -->

				<div class="feature">
					<?php get_sidebar(); ?>
				</div> <!-- .feature -->

				<div class="clear"></div>

			</div> <!-- #container -->

			<div class="clear"></div>

		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>