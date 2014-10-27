<?php
/*

Template Name: Sermon Series

*/
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post">
						<h2 class="title"><?php _e('Sermon Series','harvest'); ?></h2>
						<div class="entry media">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php the_content(); ?>
							
<?php endwhile; endif; wp_reset_query(); ?>

						</div> <!-- .entry.media -->
						
<?php 
	$sermon_series = get_terms('series');
	foreach($sermon_series as $series){
		query_posts( array( 
			'post_type' 			=> 'sermon_post', 
			'posts_per_page' 	=> 10, 
			'order' 					=> 'DESC', 
			'taxonomy'				=> 'series',
			'term'						=> $series->slug,
			'paged' 					=> get_query_var('paged') 
		) ); ?>
						
						<div class="sermon_list">
							<ul>
<?php 
	$term_link = get_term_link(intval($series->term_id),'series');
	$term_description = term_description(intval($series->term_id),'series');
	$term_feed = get_term_feed_link($series->term_id,'series');
	$series_name = $series->name;
	
	if (have_posts()) : while (have_posts()) : the_post(); 
?>
								<li>
<?php harvest_sermonImageThumb($post->ID); ?>
									<div class="sermon_info">
										<h3>
											<a href="<?php echo $term_link; ?>" title="<?php echo $series_name; ?>" rel="bookmark"><?php echo $series_name; ?></a>
<?php if($term_feed) { ?>																				
											<a href="<?php echo $term_feed;?>"><i class="icon-rss-sign pull-right"></i></a>
<?php } ?>										
										</h3>
<?php if($term_description) { ?>										
										<div class="series_info"><?php  echo $term_description;?></div>
<?php } ?>	
									</div>  <!-- .sermon_info -->

								</li> <!-- li -->
																
<?php break; endwhile; endif; ?>  

							</ul>
							
							<div class="clear"></div>
							
						</div> <!-- .sermon_list -->
<?php } ?>						
					</div> <!-- . post -->

<?php if (function_exists("harvest_pagination")) {
	harvest_pagination($additional_loop->max_num_pages);
} ?>

				</div> <!-- .main_feature -->
				
				<div class="feature">
					<?php dynamic_sidebar('sidebar-media'); ?>
					<?php get_sidebar(); ?>
				</div> <!-- .feature -->

				<div class="clear"></div>

			</div> <!-- #container -->

			<div class="clear"></div>
			
		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>