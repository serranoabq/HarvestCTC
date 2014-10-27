<?php
/**
 * TEMPLATE: EVENTS (SINGLE)
 *
 */
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post" id="event-<?php the_ID(); ?>">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<h2 class="title"><?php the_title(); ?></h2>
						<?php the_post_thumbnail(); ?>
						<p>
<?php 
	$is_event = get_post_meta(get_the_ID(), 'event_as_news', true);
	$is_event = empty($is_event);	
	$location = get_post_meta($post->ID, 'event_location', true);
	$map = get_post_meta($post->ID, 'show_map', true); 
	if($is_event){ 
?>
							<div class="event_info">
								<div class="event_date">
									<strong><?php _e('When:','harvest');?></strong>
<?php 
								$start_date = get_post_meta($post->ID, 'event_start_date', true);
								if(!empty($start_date)){
									echo date("D, F j", strtotime($start_date))." - ".get_the_time('D, F j, Y', get_the_ID()); 	
								}else{
									the_time('l, F j, Y'); 
								}
?>
								</div> <!-- .event_date -->
								<div class="event_time">
									<strong><?php _e('Time:','harvest');?></strong> <?php the_time() ?>
								</div> <!-- .event_time-->
<?php if($location) { ?>
								<div class="event_location">
									<strong><?php _e('Where:','harvest');?></strong> <?php echo$location; ?> 
<?php if ($map==1) { ?>
								<a href="http://maps.google.com/maps?q=<?php echo $location; ?>" target="_blank"> <?php _e('[Map]','harvest');?></a>
<?php }?>
								</div> <!-- .event_location -->
<?php }?>							
							</div> <!-- .event_info -->
						</p>
<?php } ?>
						<div class="entry event">
							<?php the_content(); ?>
						</div> <!-- .entry.event -->
						
<?php  if ( function_exists( 'floating_social_bar' ) ) floating_social_bar(); ?>

<?php endwhile; endif; ?>
					</div> <!-- .post -->
					
				</div><!-- .main_feature -->

				<div class="feature">
					<?php dynamic_sidebar('sidebar-events'); ?>
					<?php get_sidebar(); ?>
				</div> <!-- #rightcol -->

				<div class="clear"></div>

			</div> <!-- #container -->

		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>
