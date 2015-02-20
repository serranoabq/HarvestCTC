<?php
/**
 * TEMPLATE: 404
 *
 */
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post">
						<h2 class="title"><?php _e( 'Page Not found', 'harvest-ctc' );?></h2>
						<div class="entry">
							<p><?php _e( 'We\'re sorry. The page you requested could not be found. It\'s a simple mistake, probably on our part. Please <a href="/">click here</a> to return our homepage or use the menu above to visit another section of our site. Have a great day!', 'harvest-ctc' );?></p>
						</div> <!-- .entry -->
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