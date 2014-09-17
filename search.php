<?php
/**
 * TEMPLATE: SINGLE
 *
 */
?>
<?php get_header(); ?>

			<div id="container">
				<div id="leftcol">
					<div class="post">
						<h2 class="title"><?php _e('Search Results','harvest');?></h2>
<?php if (have_posts()) : ?>
						<p><?php _e('Your search for','harvest');?> <strong><?php the_search_query(); ?></strong> <?php _e('returned the following results:','harvest');?> </p>
<?php while (have_posts()) : the_post(); ?>
						<div class="entry-excerpt" id="post-<?php the_ID(); ?>">
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
						
            		<?php the_excerpt(); ?>

								<a href="<?php the_permalink(); ?>" class="read_more"><strong><?php _e('read more...','harvest');?></strong></a>
								
            		<div class="clear"></div>
						</div> <!-- .entry-excerpt -->
<?php endwhile; ?>
<?php else: ?>
						<div class="entry-excerpt">
						<?php _e('Sorry','harvest');?> <strong><?php the_search_query(); ?></strong> <?php _e('was not found in the site.','harvest');?> 
						</div> <!-- .entry-excerpt -->
<?php endif; ?>
					</div> <!-- .post -->
			
<?php if (function_exists("harvest_pagination")) {
	harvest_pagination($additional_loop->max_num_pages);
} ?>
				</div> <!-- #leftcol -->
	
				<div id="rightcol">
					<?php get_sidebar(); ?>
				</div> <!-- #rightcol -->
		
				<div class="clear"></div>
			
			</div> <!-- #container -->

			<div class="clear"></div>
			
		</div> <!-- .box -->
	</div> <!-- #wrap -->

<?php get_footer(); ?>