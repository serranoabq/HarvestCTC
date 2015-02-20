<?php
	/* Sermon archive */
	
	get_header(); 
?>
		<!-- TITLE BAR -->
		<div class="title_wrap accent-background" style="<?php echo $accent_color; ?>">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php echo harvest_option( 'ctc-sermons' , __( 'Sermons', 'harvest' ) ); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	$i = 1;
	if( have_posts() ) : while (have_posts()) : the_post(); 
	
		if( $i == 1 ) :
			// The first one is displayed
			
			$post_id = get_the_ID();
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sermon-thumb' ); // sermon is 16:9 image (HD video)
			$permalink = get_permalink();
			$img = '';
			if( $thumbnail ) $img = $thumbnail[0];
			
			
			// Sermon data
			$ser_video = get_post_meta( $post_id, '_ctc_sermon_video' , true ); 
			$ser_audio = get_post_meta( $post_id, '_ctc_sermon_audio' , true ); 
			
			$series = get_the_terms( $post_id, 'ctc_sermon_series');
			if( $series && ! is_wp_error( $series) ) {
				$series = array_shift( array_values ( $series ) );
				$ser_series = $series -> name;
				$ser_series_slug = $series -> slug;
				$ser_series_link = get_term_link( intval( $series->term_id ), 'ctc_sermon_series' );
				if ( get_option( 'ctc_tax_img_' . $series->term_id ) )
					$img = get_option( 'ctc_tax_img_' . $series->term_id );
			} else {
				$ser_series = '';
			}
			
			/* Not used
			$books = get_the_terms( $post_id, 'ctc_sermon_book');
			if( $books && ! is_wp_error( $books ) ) {
				$books_A = array();
				foreach ( $books as $book ) { $books_A[] = $book -> name; }
				$ser_books = join( ', ', $books_A );
			} else {
				$ser_books = '';
			}
			*/
			/* Not used
			$tags = get_the_terms( $post_id, 'ctc_sermon_tag');
			if( $tags && ! is_wp_error( $tags ) ) {
				$tags_A = array();
				foreach ( $tags as $tag ) { $tags_A[] = $tag -> name; }
				$ser_tags = join( ', ', $tags_A );
			} else {
				$ser_tags = ''; 
			}
			*/
			
			$speakers = get_the_terms( $post_id, 'ctc_sermon_speaker');
			if( $speakers && ! is_wp_error( $speakers ) ) {
				$speakers_A = array();
				foreach ( $speakers as $speaker ) { $speakers_A[] = $speaker -> name; }
				$ser_speakers = join( ', ', $speakers_A );
			} else {
				$ser_speakers = '';
			}
			
			$topics = get_the_terms( $post_id, 'ctc_sermon_topic');
			if( $topics && ! is_wp_error( $topics ) ) {
				$topics_A = array();
				foreach ( $topics as $topic ) { $topics_A[] = $topic -> name; }
				$ser_topics = join( ', ', $topics_A );
			} else {
				$ser_topics = '';
			}
			
?>
		
				<div class="grid-60 push-40 ctc-sermon-media">
				
<?php if( $ser_video ): ?>
					<div class="ctc-sermon-video"><?php echo wp_video_shortcode( array( 'src' => $ser_video ) ); ?></div>
<?php elseif ( $img ): ?>
					<img class="ctc-sermon-img" src="<?php echo $img; ?>"/>
<?php else: ?>
					<div class="ctc-sermon">
						<div class="ctc-grid-full accent-background">
							<h1 class="ctc-sermon-name"><?php the_title(); ?></h1>
						</div>
					</div>
<?php endif; // ser_video || img ?>

<?php if( $ser_audio && ! $ser_video ): ?>
					<div class="ctc-sermon-audio"><?php echo wp_audio_shortcode( array( 'src' => $ser_audio ) ); ?></div>
<?php endif;  // ser_audio || !ser_audio ?>

				</div> <!-- .ctc-sermon-media -->

				<div class="grid-40 pull-60 ctc-sermon-details"> 
					<h2><b><?php echo __( 'Latest message:', 'harvest'); ?></b><br/><?php the_title(); ?></h2>
					<div class="grid-sermon-date"><?php the_date(); ?></div>
					
<?php if( $ser_speakers ): ?>
					<div class="ctc-sermon-speaker"><?php echo __( '<b>Speaker:</b> ', 'harvest' ) . $ser_speakers; ?></div>	
<?php endif; // ser_speakers ?>
					
<?php if( $ser_series ): ?>
					<div class="grid-ctc-sermon-series"><?php _e( '<b>Series:</b>', 'harvest' );?> <a href="<?php echo $ser_series_link; ?>">  <?php echo $ser_series; ?></a></div>				
<?php endif; // ser_series ?>

					<div class="ctc-sermon-content"><?php the_content(); ?></div>

				</div> <!-- .ctc-sermon-details -->
				<div class="clear"></div>
				
<?php else: /* ?>
				
				<div class="grid-33 ctc-sermon-grid"> 
					<a href="<?php echo $permalink; ?>">
						<div class="ctc-sermon">
<?php if ( $img ): ?>
							<img class="ctc-sermon-img" src="<?php echo $img; ?>"/>
<?php else: ?>
							<span class="ctc-sermon-img logo"><?php bloginfo('name'); ?></span>
<?php endif; // img ?>
						</div> <!-- .ctc-sermon -->
					</a>
			</div> <!-- .ctc-sermon-grid -->
			
<?php */ break; endif; // i=1? ?>

<?php 
	$i++; 
	endwhile; endif; 	
	wp_reset_postdata(); // loop 
?>
<?php
		$i = 1;
		$all_series = get_terms( 'ctc_sermon_series' );
		foreach( $all_series as $single_series ) :
			$img = '';
			$term_id = $single_series -> term_id ; 
			$term_link = get_term_link( intval( $single_series->term_id ), 'ctc_sermon_series' );
			$term_name = $single_series -> name;
			if( get_option( 'ctc_tax_img_' . $term_id ) )
				$img = get_option( 'ctc_tax_img_' . $term_id );
			
			if( $i == 1 ): ?>
			
			<div class="grid-100 ctc-sermon-grid-title ctc-sermon-others"><h2><?php _e( 'Other series', 'harvest' ); ?></h2></div>
<?php endif; ?>
			<div class="grid-33 mobile-grid-50 ctc-sermon-grid">
			
<?php if( $i < 6 ): ?>					
				<a href="<?php echo $term_link; ?>">
					<div class="ctc-sermon">
<?php 	if( $img ): ?>
						<div class="ctc-grid-details">
							<h3><?php echo $term_name; ?></h3>
						</div>
						<img src="<?php echo $img; ?>" class="ctc-sermon-img" />
<?php		else: ?>
						<div class="ctc-grid-full accent-background">
							<h1 class="ctc-sermon-name"><?php echo $term_name; ?></h1>
						</div>
<?php		endif; ?>

<?php else: 
			$tax = get_taxonomy( 'ctc_sermon_series' );
			$tax_link = $tax->rewrite['slug'];
			// This will create a link to an archive page called /taxonomy_slug/. This page is not generated directly be must be created, and it must use the Series template
?>
				<a href="<?php echo home_url( $tax_link ); ?>">
					<div class="ctc-sermon">
						<div class="ctc-grid-full ctc-grid-viewall accent-background">
							<h1><?php _e( 'All', 'harvest' ); ?> <i class="fa fa-chevron-right"></i></h1>
						</div>
<?php endif; ?>
					</div> <!-- ctc-sermon -->
				</a>
			</div>
<?php 
		if( $i == 6 ) break;
		$i++;  
		endforeach; 
?>	

				<div class="clear"></div>
				
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
