<?php

	get_header(); 

?>

		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php _e( 'Page Not found', 'harvest' );?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">
				<div class="grid-80 prefix-10 suffix-10 ctc-content">
				
				<?php _e( 'We\'re sorry. The page you requested could not be found. It\'s a simple mistake, probably on our part. Please <a href="/">click here</a> to return our homepage or use the menu above to visit another section of our site. Have a great day!', 'harvest' );?>
				
				</div>
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	
	get_footer();
	
