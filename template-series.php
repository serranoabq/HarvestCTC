<?php
	/* Template Name: Sermon Series */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php echo harvest_option( 'ctc-sermon-series' , __( 'Sermon Series', 'harvest' ) ); ?>
					</h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	$all_series = get_terms( 'ctc_sermon_series' );
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
							<div class="ctc-grid-details">
								<h3><?php echo $term_name; ?></h3>
							</div>
							<img src="<?php echo $img; ?>" class="ctc-sermon-img" />
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
	

		
