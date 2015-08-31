 <?php
	/* Single sermon */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$data = harvest_get_sermon_data( $post_id );
		harvest_title_bar( get_the_title() );
?>
		<div class="content_wrap">

			<div class="grid-container content">

				<?php get_template_part( 'templates/sermon', 'details' ); ?>
				
<?php endwhile; endif; //loop ?>
				
<?php 
	// other posts in the series
	if( $data[ 'series' ] ):
		$tax_query[] = array(
				'taxonomy'	=> 'ctc_sermon_series',
				'field'			=> 'slug',
				'terms'			=> $data[ 'series_slug' ],
		);
		if( $data[ 'topic' ] ) {
			$tax_query[] = array( 
				'taxonomy'	=> 'ctc_sermon_topic',
				'field'			=> 'slug',
				'terms'			=> $data[ 'topic_slug' ],	
			);
			$tax_query['relation'] = 'AND';
		}
		$args = array( 
			'post_type' 			=> 'ctc_sermon', 
			'tax_query'				=> $tax_query,
			'order' 					=> 'DESC', 
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) : 

			$i = 1;
			while ( $query->have_posts() ) : $query->the_post();
				$mpost_id = get_the_ID();
				$topic = explode( '/', harvest_option( 'ctc-sermon-topic' , __( 'topic', 'harvest' ) ) );
				$topic = array_pop( $topic );
				$series = explode( '/', harvest_option( 'ctc-sermon-series' , __( 'series', 'harvest' ) ) );
				$series = array_pop( $series );
				if( $mpost_id == $post_id ) continue;
				if( $i == 1 ):
?>
			<div class="grid-100 ctc-sermon-grid-title ctc-sermon-others">
				<h2><?php echo  __( 'Other messages from this ', 'harvest') . strtolower( $series ) . ( $data['topic'] ? _x( ' and ', 'Space before and after', 'harvest' ) . strtolower( $topic ) : '' ); ?></h2>
			</div>
			
<?php		endif; // $i == 1? ?>
			
			<?php get_template_part( 'templates/sermon', 'grid' ); ?>
			
<?php
	$i++; endwhile; endif; endif; 
	wp_reset_postdata();
?>

				<div class="clear"></div>
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	
	
	get_footer();
	

		
