<?php

get_header(); ?>

		<div class="content_wrap">
<?php if ( harvest_option( 'slider' ) ) 
		echo harvest_option( 'slider' );
?>
			<div class="grid-container content">

				<div class="boxes">
					<?php dynamic_sidebar( 'home-boxes' ); ?>
				</div><!-- .boxes -->
				
				<div class="home-widgets">
<?php if( harvest_option( 'layout' ) == '66' ): ?>
					<div class="grid-66 home-widget home-widget-left">
<?php else: ?>
					<div class="grid-33 home-widget home-widget-left"> 
<?php endif; ?>
						<?php dynamic_sidebar( 'home-left' ); ?>
					</div><!-- .home-widget-left -->
<?php if( harvest_option( 'layout' ) == '33' ): ?>
					<div class="grid-33 home-widget home-widget-middle">
						<?php dynamic_sidebar( 'home-middle' ); ?>
					</div><!-- .home-widget-middle -->
<?php endif; ?>
					<div class="grid-33 home-widget home-widget-right">
						<?php dynamic_sidebar( 'home-right' ); ?>
					</div><!-- .home-widget-right -->
				
				</div><!-- .home-widgets -->
		
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
get_footer();
	
