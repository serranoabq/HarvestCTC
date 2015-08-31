<?php
	/* Template Name: Sermon Series */
	
	get_header(); 
	$title = explode( '/', harvest_option( 'ctc-sermon-series' , __( 'Sermons', 'harvest' ) ) );
	$title = array_shift( $title );
	harvest_title_bar( $title );
?>
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	$all_series = get_terms( 'ctc_sermon_series', array( 'order_by' => 'id', 'order' => 'DESC') );
	foreach( $all_series as $single_series ) :
		$img = '';
		$term_id = $single_series -> term_id ; 
		$term_link = get_term_link( intval( $single_series->term_id ), 'ctc_sermon_series' );
		$term_name = $single_series -> name;
		if( get_option( 'ctc_tax_img_' . $term_id ) )
			$img = get_option( 'ctc_tax_img_' . $term_id );
?>

				<div class="grid-33 mobile-grid-50 ctc-sermon-grid">
					<a href="<?php echo $term_link; ?>">
						<div class="ctc-sermon">
<?php if( $img ): ?>
							<!-- div class="ctc-grid-details">
								<h3><?php echo $term_name; ?></h3>
							</div -->
							<img src="<?php echo $img; ?>" class="ctc-sermon-img" alt="<?php echo $term_name; ?>" title="<?php echo $term_name; ?>"/>
<?php	else: ?>
							<div class="ctc-grid-full accent-background">
								<h1 class="ctc-sermon-name"><?php echo $term_name; ?></h1>
							</div>
<?php	endif; ?>

						</div> <!-- ctc-sermon -->
					</a>
				</div>
<?php endforeach; ?>	

				<div class="clear"></div>
			
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	get_footer();
	

		
