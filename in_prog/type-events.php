<?php
/*

Template Name: Events

*/
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post">
						<h2 class="title"><?php esc_html_e('News & Events','harvest');?></h2>
						<!-- Events Intro -->
						<div class="entry events">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php the_content(); ?>

<?php endwhile; endif; wp_reset_query(); ?>

						</div> <!-- .entry.events -->
						
<?php query_posts( array(
	'post_type' 			=> 'events', 
	'post_status' 		=> 'future', 
	'posts_per_page' 	=> 10, 
	'order' 					=> 'ASC', 
	'paged' 					=> get_query_var('paged') 
	) 
); ?>
						<div class="events_list">
							<ul>
<?php if (have_posts()) : while (have_posts()) : the_post(); 
	// Check if the event should be treated as an undated news item
	$is_event = get_post_meta($post->ID, 'event_as_news', true);
	$is_event = empty($is_event);
	$start_date = get_post_meta($post->ID, 'events_start_date', true);
	$wd = get_the_time('D, M j', $post->ID);
	if(!empty($start_date))
		$wd = date("M j", strtotime($start_date))." - ".get_the_time('M j', get_the_ID());
	$tm = get_the_time('g:ia', $post->ID);
?>			
							<li>
<?php if(has_post_thumbnail($post->ID)){
					echo get_the_post_thumbnail($post->ID,array(75,75), array('class' => 'event_widget_img left'));
} else { if($is_event) { ?>
										<img src="<?php bloginfo('template_directory'); ?>/images/default-event.png" class="event_widget_img left wp-post-image" /> 
<?php } else { ?>
										<img src="<?php bloginfo('template_directory'); ?>/images/default-news.png" class="event_widget_img left wp-post-image" />
<?php } } ?>										
									<div class="event_info">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
<?php if($is_event) { ?>
										<div class="event_datetime"><strong><?php _e('When:','harvest');?></strong> <?php echo $wd. ' @ '.$tm; ?></div> <!-- .event_datetime -->
									</div> <!-- .event_info -->
<?php } ?>
								</li> <!-- li-->
								
<?php endwhile; endif; ?>  

							</ul>
							
							<div class="clear"></div>
							
						</div> <!-- .events_list -->
			
				</div> <!-- .post -->
						
<?php if (function_exists("harvest_pagination")) {
	harvest_pagination($additional_loop->max_num_pages);
} ?>
				
				</div> <!-- .main_feature -->
			
				<div class="feature">
					<?php dynamic_sidebar('sidebar-events'); ?>
					<?php get_sidebar(); ?>
				</div> <!-- .feature -->
			
				<div class="clear"></div>
			
			</div> <!-- #container -->
			
			<div class="clear"></div>
		
		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>