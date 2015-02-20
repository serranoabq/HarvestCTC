<?php
	/* Sermon topic/location archive */
	
	get_header(); 
	$term = get_queried_object();
?>
		<!-- TITLE BAR -->
		<div class="title_wrap accent-background">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php echo harvest_option( 'ctc-sermons', __( 'Sermon', 'harvest' ) ) . ' ' . harvest_option( 'ctc-sermon-topic' , __( 'Topic', 'harvest' ) ); ?>: <?php echo $term->name; ?>
					</h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php
	$i = 1;
	if( have_posts() ) : while (have_posts()) : the_post(); 
	
		if( $i == 1 ) :
			// The first one is displayed in full
			
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
				$ser_topic = $topics_A[0];
			} else {
				$ser_topic = '';
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
					<h2><?php the_title(); ?></h2>
					<div class="grid-sermon-date"><?php the_date(); ?></div>
					
<?php if( $ser_speakers ): ?>
					<div class="ctc-sermon-speaker"><?php echo __( '<b>Speaker:</b> ', 'harvest' ) . $ser_speakers; ?></div>	
<?php endif; // ser_speakers ?>
					
<?php if( $ser_series ): ?>
					<div class="grid-ctc-sermon-series"><?php _e( '<b>Series:</b>', 'harvest' );?> <a href="<?php echo $ser_series_link; ?>">  <?php echo $ser_series; ?></a></div>				
<?php endif; // ser_series ?>

<?php if( $ser_topic ): ?>
					<div class="grid-ctc-sermon-topic"><b><?php echo ucfirst( harvest_option( 'ctc-sermon-topic' , 'Topic' ) ); ?>:</b> <a href="<?php echo $ser_topic_link; ?>">  <?php echo $ser_topic; ?></a></div>				
<?php endif; // ser_topic ?>

					<div class="ctc-sermon-content"><?php the_content(); ?></div>


				</div> <!-- .ctc-sermon-details -->
				<div class="clear"></div>
				
<?php else: // Others are just displayed as boxes ?>
<?php if( $i == 2 ): ?>
				<div class="grid-100 ctc-sermon-grid-title ctc-sermon-others"><h2><?php echo  __( 'Other messages in this ', 'harvest') . strtolower( harvest_option( 'ctc-sermon-topic' , 'topic' ) ); ?></h2></div>
<?php endif; ?>				
				
				<div class="grid-33 mobile-grid-50 ctc-sermon-grid"> 
					<a href="<?php echo $permalink; ?>">
						<div class="ctc-sermon">
<?php if ( $img ): ?>
							<div class="ctc-grid-details">
								<h3><?php the_title(); ?></h3>
							</div>
							<img class="ctc-sermon-img" src="<?php echo $img; ?>"/>
<?php else: ?>
							<div class="ctc-grid-full accent-background">
								<h1 class="ctc-sermon-name"><?php the_title(); ?></h1>
							</div>
<?php endif; // img ?>
						</div> <!-- .ctc-sermon -->
					</a>
				</div> <!-- .ctc-sermon-grid -->
			
<?php 
	endif; // i=1? 
	$i++; 
	endwhile; endif; 	// loop
?>
				<div class="clear"></div>
				
			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

	harvest_pagination();
	
	get_footer();
	

		
