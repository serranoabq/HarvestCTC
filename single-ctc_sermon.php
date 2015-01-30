<?php
	/* Single sermon */
	
	get_header(); 

	if (have_posts()) : while (have_posts()) : the_post(); 
		$post_id = get_the_ID();
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sermon' ); // sermon is 16:9 image (HD video)
		$permalink = get_permalink();

		// Sermon data
		$ser_video = get_post_meta( $post_id, '_ctc_sermon_video' , true ); 
		$ser_audio = get_post_meta( $post_id, '_ctc_sermon_audio' , true ); 
		
		$series = get_the_terms( $post_id, 'ctc_sermon_series');
		if( $series && ! is_wp_error( $series) ) {
			$series = array_shift( array_values ( $series ) );
			$ser_series = $series -> name;
		} else {
			$ser_series = '';
		}
		
		$books = get_the_terms( $post_id, 'ctc_sermon_book');
		if( $books && ! is_wp_error( $books ) ) {
			$books_A = array();
			foreach ( $books as $book ) { $books_A[] = $book -> name; }
			$ser_books = join( ', ', $books_A );
		} else {
			$ser_books = '';
		}
		
		$speakers = get_the_terms( $post_id, 'ctc_sermon_speaker');
		if( $speakers && ! is_wp_error( $speakers ) ) {
			$speakers_A = array();
			foreach ( $speakers as $speaker ) { $speakers_A[] = $speaker -> name; }
			$ser_speakers = join( ', ', $speakers_A );
		} else {
			$ser_speakers = '';
		}
		
		$tags = get_the_terms( $post_id, 'ctc_sermon_tag');
		if( $tags && ! is_wp_error( $tags ) ) {
			$tags_A = array();
			foreach ( $tags as $tag ) { $tags_A[] = $tag -> name; }
			$ser_tags = join( ', ', $tags_A );
		} else {
			$ser_tags = '';
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
		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php the_title(); ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>
		
		<div class="content_wrap">

			<div class="grid-container content">

<?php if( $ser_video ): ?>
				<div class="grid-50 prefix-25 suffix-25 ctc-sermon-video">
					<div class="ctc-video"><?php echo wp_video_shortcode( array( 'src' => $ser_video ) ); ?></div>
				</div> <!-- .ctc-sermon-video -->
<?php elseif ( $thumbnail ): ?>
				<div class="grid-50 prefix-25 suffix-25 ctc-sermon-img">
					<img class="ctc-img" src="<?php echo $thumbnail[0]; ?>"/>
				</div> <!-- .ctc-sermon-img -->
<?php elseif ( harvest_option( 'logo' ) <> "" ): ?>
				<div class="grid-50 prefix-25 suffix-25 ctc-sermon-img">
					<img src="<?php echo harvest_option( 'logo' ); ?>" alt="ctc-img logo" />
				</div> <!-- .ctc-sermon-img -->
<?php else: ?>
				<div class="grid-50 prefix-25 suffix-25 ctc-sermon-img">
					<span class="ctc-img logo"><?php bloginfo('name'); ?></span>
				</div> <!-- .ctc-sermon-img -->
<?php endif; ?>
<?php if( $ser_audio ): ?>
				<div class="grid-50 prefix-25 suffix-25 ctc-sermon-audio">
					<div class="ctc-audio"><?php echo wp_audio_shortcode( array( 'src' => $ser_audio ) ); ?></div>
				</div> <!-- .ctc-sermon-audio -->
<?php endif; ?>

				<div class="grid-50 prefix-25 suffix-25 grid-parent ctc-sermon-details"> 

<?php if( $ser_speakers ): ?>
					<div class="grid-50"><?php echo $ser_speakers; ?></div>				
<?php endif; ?>

<?php if( $ser_tags ): ?>
					<div class="grid-50"><?php echo $ser_tags; ?></div>				
<?php endif; ?>

<?php if( $ser_topics ): ?>
					<div class="grid-50"><?php echo $ser_topics; ?></div>				
<?php endif; ?>

				</div> <!-- .ctc-sermon-details -->
				<div class="clear"></div>


			</div> <!-- .content.grid-container -->

		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php
	endwhile; endif; 
	
	get_footer();
	

		
