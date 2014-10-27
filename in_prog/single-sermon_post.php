<?php
/**
 * TEMPLATE: SERMON (SINGLE)
 *
 */
?>
<?php get_header(); ?>

			<div id="container">
				<div class="main_feature">
					<div class="post" id="sermon-<?php the_ID(); ?>">
<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
						<h2 class="title"><?php the_title(); ?></h2>
<?php 
	$speaker= get_post_meta($post->ID, 'sermon_speaker', true);
	$verse= get_post_meta($post->ID, 'sermon_verse', true);
	$filelink= get_post_meta($post->ID, 'sermon_filelink', true);
	$term=harvest_sermonImageFull($post->ID,false);
	$series = $term->name;
?>
						<p>
	
							<div class="sermon_info">
								<div class="sermon_date"><strong><?php _e('Date:','harvest');?></strong> <?php echo get_the_date('F j, Y'); ?></div> <!-- .sermon_date -->
<?php	if($speaker) { ?>
								<div class="sermon_speaker"><strong><?php _e('By:','harvest');?></strong> <?php echo $speaker; ?></div> <!-- .sermon_speaker -->
<?php } ?>
<?php	if($verse) { ?>
								<div class="sermon_verse"><strong><?php _e('Verse:','harvest');?></strong> <?php echo $verse; ?></div> <!-- .sermon_verse -->
<?php } ?>
<?php	if($series) { ?>
								<div class="sermon_series"><strong><?php _e('Series:','harvest');?></strong> <a href="<?php echo get_term_link($term); ?>"><?php echo $series; ?></a></div> <!-- .sermon_series -->
<?php } ?>
							</div> <!-- .sermon_info -->
						</p>
						
						<div class="entry sermon">
							<?php the_content(); ?>
						</div> <!-- .entry.sermon -->
						
<?php if ($filelink) { ?>

						<?php /*  <audio class="audio-player" src="<?php echo $sermon_filelink; ?>" type="audio/mp3" controls="controls" width="280"></audio> <?php */ ?>
						<div class="sermon_audio">
<?php echo do_shortcode('[audio src="'.$filelink.'" width="280"]'); ?>
						</div>
						<a class="button" href="<?php echo $filelink; ?>" title="<?php esc_attr_e('Download','harvest');?>"><?php _e('Download','harvest');?></a>
<?php } ?>
						
						<div class="clear"></div>   
						
<?php endwhile; endif; wp_reset_query(); ?>
					</div> <!-- .post -->
					
					<div class="clear"></div>
					
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

		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>