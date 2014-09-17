<?php
/*

Template Name: Media

*/
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post">
						<h2 class="title"><?php _e('Media','harvest'); ?></h2>
						<div class="entry media">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php the_content(); ?>
							
<?php endwhile; endif; wp_reset_query(); ?>

						</div> <!-- .entry.media -->
						
<?php query_posts( array( 
	'post_type' 			=> 'sermon_post', 
	'posts_per_page' 	=> 5, 
	'order' 					=> 'DESC', 
	'paged' 					=> get_query_var('paged') 
) 
); ?>
						
						<div class="sermon_list">
							<ul>
<?php if (have_posts()) : while (have_posts()) : the_post(); 
	$speaker= get_post_meta($post->ID, 'sermon_speaker', true);
	$term = get_the_terms( $post->ID, 'series' );
	$series =false; $term_id=false;
	if($term) {
		$term = array_shift(array_values($term));
		$series = $term->name;
		$term_id = $term->term_taxonomy_id;
	}
?>							
								<li>
<?php harvest_sermonImageThumb($post->ID); ?>
									<div class="sermon_info">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
										<div class="sermon_date"><strong><?php _e('Date:','harvest');?></strong> <?php echo get_the_date('F j, Y'); ?></div> <!-- .sermon_date -->
<?php if($speaker){ ?>
										<div class="sermon_speaker"><strong><?php _e('By:','harvest');?></strong> <?php echo $speaker; ?></div> <!-- .sermon_speaker -->
<?php } ?>
<?php if($series){ ?>
										<div class="sermon_series"><strong><?php _e('Series:','harvest');?></strong> <a href="<?php echo get_term_link($term); ?>"><?php echo $series; ?></a></div> <!-- .sermon_series -->
<?php } ?>											
									</div> <!-- .sermon_info -->

									<p class="excerpt"><?php  $excerpt = get_the_excerpt(); echo string_limit_words($excerpt,40);?></p>
									
								</li> <!-- li -->
								
<?php endwhile; endif; ?>  

							</ul>
							
							<div class="clear"></div>
							
						</div> <!-- .sermon_list -->
						
					</div> <!-- . post -->

<?php if (function_exists("harvest_pagination")) {
	harvest_pagination($additional_loop->max_num_pages);
} ?>

				</div> <!-- .main_feature -->
				
				<div class="feature">
					<div id="feedwidget" class="widget">
						<script type="text/javascript">// <![CDATA[
							function selectText(containerid) {
								if (document.selection) {
									var range = document.body.createTextRange();
									range.moveToElementText(document.getElementById(containerid));
									range.select();
								} else if (window.getSelection) {
									var range = document.createRange();
									range.selectNode(document.getElementById(containerid));
									window.getSelection().addRange(range);
								}
							}
			// ]]></script>
						<h2>Podcast</h2>
						<div class="textwidget">
							<p><?php _e('To subscribe to all of our sermons as a podcast, add the address below to your podcast reader.','harvest');?></p>
							<div class="feedbutton">
								<div id="feed1" class="feedaddr" onclick="selectText(this.id)">
									<a href="<?php echo get_bloginfo_rss('rss2_url');?>"><i class="icon-rss-sign icon-2x"></i></a>
									<p><?php echo get_bloginfo_rss('rss2_url');?></p>
								</div>
							</div> <!-- .feedbutton -->
						</div> <!-- .textwidget -->
					</div> <!-- #feedwidget --> 
					<?php dynamic_sidebar('sidebar-media'); ?>
					<?php get_sidebar(); ?>
				</div> <!-- .feature -->

				<div class="clear"></div>

			</div> <!-- #container -->

			<div class="clear"></div>
			
		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>