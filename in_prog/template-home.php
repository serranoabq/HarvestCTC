<?php
/**
 * TEMPLATE: HOME
 *
 */
?>
<?php get_header(); ?>

<?php if ( function_exists( 'meteor_slideshow' ) ) { ?>
			<div class="slides">
<?php		meteor_slideshow( 'home', 'height: 300, width:600' ); ?>
			</div>
<?php } ?>

<?php if(harvest_option( 'layout' )) $lo = harvest_option( 'layout' ); ?>

			<div id="container" class="homepage <?php echo $lo; ?>">
				<div class="main_feature">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<h2><?php _e( 'Welcome', 'harvest-ctc' );?></h2>
<?php 	if ( ! function_exists( 'meteor_slideshow' ) ) { ?>
					<div class="main_feature_img">
					<?php the_post_thumbnail(); ?>
					</div> <!-- #main_feature_img -->
<?php } ?>
					<?php the_content(); ?>
					
<?php endwhile; endif; ?>
				</div> <!-- #main_feature -->
				
				<div class="feature first">
<?php dynamic_sidebar( 'sidebar-home-left' ); ?>
				</div>  <!-- .feature.first -->

				<div class="clear"></div> 
				
				<div class="feature second">
<?php dynamic_sidebar( 'sidebar-home-mid' ); ?>
				</div> <!-- .feature.second -->

				<div class="feature third">
<?php dynamic_sidebar( 'sidebar-home-right' ); ?>
				</div> <!-- .feature.third -->
				
				<div class="clear"></div>
			
			</div>	<!-- #container -->

			<div class="clear"></div>

		</div> <!-- .box -->
	</div> <!-- #wrap -->	

<?php get_footer(); ?>