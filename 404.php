<?php

	get_header(); 
	harvest_title_bar( __( 'Page Not Found', 'harvest' ) );
?>
		
		<div class="content_wrap">

			<div class="grid-container content">
				<div class="grid-100 ctc-content error-404">
				
				<?php _e( 'We\'re sorry. The page you requested could not be found. It\'s a simple mistake, probably on our part. Please <a href="/">click here</a> to return our homepage or use the menu above to visit another section of our site. Have a great day!', 'harvest' );?>
				
				</div>
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	
	get_footer();
	
