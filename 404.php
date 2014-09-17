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
						<h2 class="title"><?php _e('Page Not found','harvest');?></h2>
						<p><?php echo __("We're sorry. The page you requested could not be found. It's a simple mistake, probably on our part. Please ",'harvest') . '<a href="/">' . __('click here','harvest') . '</a> ' . __('to return our homepage or use the menu above to visit another section of our site. Have a blessed day!','harvest');?></p>
					</div> <!-- .post -->
				</div> <!-- .main_feature -->

				<div class="feature">
					
				</div> <!-- .feature -->

				<div class="clear"></div>

			</div> <!-- #container -->

			<div class="clear"></div>

		</div> <!-- .box -->

	</div> <!-- #wrap -->

<?php get_footer(); ?>