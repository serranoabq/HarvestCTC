<?php
/*

	Template Name: Staff

*/
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post">
						<h2 class="title"><?php _e('Meet our staff','harvest');?></h2>
						
<?php query_posts(array('post_type'=>'staff', 'posts_per_page' => -1)); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<div class="entry staff">
							<?php the_post_thumbnail('staff-thumb', array('class'=>'staff-img')); ?>
<?php 
	$email=get_post_meta($post->ID, 'staff_email', true); 
	$designation=get_post_meta($post->ID, 'staff_designation', true); 
?>
							<h3><?php the_title(); ?></h3>
							<div class="staff_info">
<?php if($designation): ?>							
								<i class="icon-user"></i> <span><?php echo $designation; ?></span><br/>
<?php endif; ?>
<?php if($email): ?>
								<i class="icon-envelope"></i> <span><a href="mailto:<?php echo $email; ?>" class="mail_link"><?php echo $email; ?></a></span>
<?php endif; ?>
							</div> <!-- .stadd_info -->
							<?php the_content(); ?>

						</div> <!-- .entry.staff -->
						
						<div class="clear">&nbsp;</div>
						
<?php endwhile; endif; wp_reset_query(); ?>
						
					</div> <!-- .post -->

				</div> <!-- .main_feature -->

				<div class="feature">
					<?php dynamic_sidebar('sidebar-staff'); ?>
					<?php get_sidebar(); ?>
				</div> <!-- .feature -->
				
				<div class="clear"></div>

			</div> <!-- #container -->

			<div class="clear"></div>

		</div> <!-- .box -->
	</div>	<!-- #wrap -->

<?php get_footer(); ?>