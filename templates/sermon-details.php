<?php
	/* Sermon details */
	$post_id = get_the_ID();
	$data = harvest_get_sermon_data( $post_id );
?>
		
				<div class="grid-60 push-40 ctc-sermon-media">
				
<?php if( $data[ 'video' ] ): ?>
	<?php if ( $data[ 'img' ] ): ?>
		<script>
			// Add lazy loading of videos, using the image as the cover art
			jQuery(document).ready( function($) {
				$( '.ctc-sermon-media' ).css( 'position', 'relative' );
				
				var vid_src = '<?php echo strripos( $data[ 'video' ], 'iframe' ) ? '<div class="ctc-sermon-video video-container">' . $data[ 'video' ] . '</div>': '<div class="ctc-sermon-video">' . wp_video_shortcode( array( 'src' => $data[ 'video' ] ) ) . '</div>'; ?>';
				
				vid_src = vid_src.replace( 'autoPlay=false', 'autoPlay=true' );
				$( '.play-overlay' ).click( function(){
					$( this ).hide();
					$( '.overlay' ).fadeOut( 'fast', function() {
						$( this ).replaceWith( vid_src );
					});
				} );
			})
			
		</script>
		<img class="ctc-sermon-img overlay" src="<?php echo $data[ 'img' ]; ?>"/><div class="play-overlay"><i class="fa fa-play-circle-o fa-5x"></i></div>
	<?php else: ?>

		<?php if( strripos( $data[ 'video' ], 'iframe' ) ): ?>
						<div class="ctc-sermon-video video-container"><?php echo $data[ 'video' ]; ?></div>
		<?php else: ?>
						<div class="ctc-sermon-video"><?php echo wp_video_shortcode( array( 'src' => $data[ 'video' ] ) ); ?></div>
		<?php endif; ?>
		
	<?php endif; ?>
	
<?php elseif ( $data[ 'img' ] ): ?>
<?php /* if ( $data[ 'default_used' ] ): ?><div class="ctc-sermon-logo accent-background"><?php endif; */ ?>
							<img class="ctc-sermon-img" src="<?php echo $data[ 'img' ]; ?>"/>
<?php /* if ( $data[ 'default_used' ] ): ?></div><?php endif;  */ ?>
<?php else: ?>
					<div class="ctc-sermon">
						<div class="ctc-grid-full accent-background">
							<h1 class="ctc-sermon-name"><?php the_title(); ?></h1>
						</div>
					</div>
<?php endif; // video || img ?>

<?php if( $data[ 'audio' ] && ! $data[ 'video' ] ): ?>
					<div class="ctc-sermon-audio"><?php echo wp_audio_shortcode( array( 'src' => $data[ 'audio' ] ) ); ?></div>
<?php endif;  // audio || !video ?>

				</div> <!-- .ctc-sermon-media -->

				<div class="grid-40 pull-60 ctc-sermon-details"> 
					<h2><?php the_title(); ?></h2>
					<div class="grid-sermon-date"><?php echo __( '<b>Date:</b> ', 'harvest' ) . get_the_date(); ?></div>
					
<?php if( $data[ 'speakers' ] ): ?>
					<div class="ctc-sermon-speaker"><?php echo __( '<b>Speaker:</b> ', 'harvest' ) . $data[ 'speakers' ]; ?></div>	
<?php endif; // ser_speakers ?>
					
<?php if( $data[ 'series' ] ): ?>
					<div class="grid-ctc-sermon-series"><?php _ex( '<b>Series:</b>', 'Label for detail template', 'harvest' );?> <a href="<?php echo $data[ 'series_link' ]; ?>">  <?php echo $data[ 'series' ]; ?></a></div>				
<?php endif; // ser_series ?>

<?php if( $data[ 'topic' ] ): ?>
					<div class="grid-ctc-sermon-topic"><b><?php echo ucfirst( array_pop( explode( '/', harvest_option( 'ctc-sermon-topic' , __( 'Topic', 'harvest') ) ) ) ); ?>:</b> <a href="<?php echo $data[ 'topic_link' ]; ?>">  <?php echo $data[ 'topic' ]; ?></a></div>				
<?php endif; // topic ?>

<?php if( $data[ 'audio' ] && $data[ 'video' ] ): ?>
					<div class="grid-ctc-sermon-audio"><?php _e( '<b>Audio:</b>', 'harvest' );?> <a href="<?php echo $data[ 'audio' ]; ?>">  <?php echo __( 'Download audio', 'harvest' ); ?></a></div>				
<?php endif; // audio ?>

					<div class="ctc-sermon-content"><?php the_content(); ?></div>

				</div> <!-- .ctc-sermon-details -->
				<div class="clear"></div>