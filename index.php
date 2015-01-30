<?php

get_header(); ?>

<?php if( !is_front_page() ): 
echo is_archive();
echo is_single();
echo get_post_type();?>

		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
<?php endif; ?>
		<div class="content_wrap">
<?php 
	if ( is_front_page() ) {
		//if ( harvest_option( 'slider' ) ) echo harvest_option( 'slider' );
	}
?>
			<div class="grid-container content">

<?php 
				if ( is_front_page() ) 
					get_template_part( 'content', 'home' );
				elseif ( is_single() )
					get_template_part( 'single', get_post_type() );
				elseif ( is_archive() )
					get_template_part( 'archive', get_post_type() );
				else
					get_template_part( 'content', get_post_type() );
?>
		
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
get_footer();
	
