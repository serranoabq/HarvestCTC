<?php 
/* Recent Sermon Widget */
/* Uses ctc_sermon_topic and ctc_location to display the latest sermon associated with a location, as long as the topic slug matches the slug of the location page it is displayed */

class harvest_RecentSermon extends WP_Widget {
		
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'recent-sermon', 
			'description' => __( 'Harvest Recent Sermon', 'harvest' ) 
		);
		parent::__construct( 'recent-sermon', __( 'Harvest Recent Sermon', 'harvest' ), $widget_ops);
	}
		
	function widget( $args, $instance ) {
		global $post;
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Sermon', 'harvest' ) : $instance['title'], $instance, $this->id_base );
		$add_link = $instance['add_link'];
		$custom_link = $instance['custom_link'];
		$link = $instance['link'];
		$tag = $instance['tag'];
		$use_location = $instance['use_location'];
		
		$query = array(
			'post_type' 			=> 'ctc_sermon', 
			'order' 					=> 'DESC',
			'orderby' 				=> 'date',
			'posts_per_page'		=> 1,
		); 
		
		// Automatic override using the location as a tag
		if( !empty( $tag ) && $add_link ){
			$term = get_term_by( 'slug', $tag, 'ctc_sermon_topic' );
			$link = get_term_link( intval( $term->term_id ), 'ctc_sermon_topic' );
		}
		if( $use_location && $post->post_type == 'ctc_location' ) {
			$tag = $post->post_name;
			if( ! $custom_link ){
				$term = get_term_by( 'slug', $tag, 'ctc_sermon_topic' );
				$link = get_term_link( intval( $term->term_id ), 'ctc_sermon_topic' );
			}
		}
		if( !empty( $tag ) ) {
			$query[ 'tax_query' ] = array( 
				array(
					'taxonomy' 	=> 'ctc_sermon_topic',
					'field'			=> 'slug',
					'terms'			=> $tag,
				),
			);
		}
		$posts = new WP_Query( $query ); 
		if ( $posts -> have_posts() ){
			echo $before_widget;
			if ( $title ) {
				if( $add_link ) {
					$before_title = $before_title . '<a class="ctc-cal-week-title-link" href="'.$link . '">';
					$after_title = '</a>' . $after_title ;
				} 
				echo $before_title . $title . $after_title;
			}
			
			echo '<div id="ctc-recent-sermon" class="ctc-sermon-grid">';
			
			while ($posts->have_posts()): 
				$posts -> the_post();
				$post_id = get_the_ID();			
				$data = harvest_get_sermon_data( $post_id );
?>
			<div class="ctc-sermon">
<?php if( $data[ 'video' ] ): ?>
	<?php if ( $data[ 'img' ] ): ?>
		<script>
			// Add lazy loading of videos, using the image as the cover art
			jQuery(document).ready( function($) {
				$( '.ctc-sermon' ).css( {position: 'relative', 'padding-bottom': 'calc(56.25% + 5px)' } );
				
				var vid_src = '<?php echo strripos( $data[ 'video' ], 'iframe' ) ? '<div class="ctc-sermon-video video-container">' . $data[ 'video' ] . '</div>': '<div class="ctc-sermon-video">' . wp_video_shortcode( array( 'src' => $data[ 'video' ] ) ) . '</div>'; ?>';
				
				vid_src = vid_src.replace( 'autoPlay=false', 'autoPlay=true' );
				$( '.play-overlay' ).click( function(){
					$( this ).hide();
					$( '.overlay' ).fadeOut( 'fast', function() {
						$( this ).replaceWith( vid_src );
						$('.video-container').css( 'padding-top', 0 );
						$('.video-container iframe').css( 'border', 'none' );
				
					});
				} );
			})
			
		</script>
		<img class="ctc-sermon-img overlay" src="<?php echo $data[ 'img' ]; ?>"/><div class="play-overlay"><i class="fa fa-play-circle-o fa-5x"></i></div>
	<?php else: ?>
		<?php if( strripos( $data[ 'video' ], 'iframe' ) ): ?>
						<div class="ctc-sermon-video video-container"><?php echo $data[ 'video' ]; ?></div>
		<?php else: ?>
						<div class="ctc-sermon-video"><?php echo wp_video_shortcode( array( 'src' => $data[ 'video'], 'width' => 768 ) ); ?></div>
		<?php endif; ?>
	<?php endif; ?>
<?php elseif ( $data[ 'img' ] ): ?>
					<a href="<?php echo $data[ 'permalink' ] ?>">
						<div class="ctc-grid-details">
							<h3><?php the_title(); ?></h3>
						</div>
<?php if ( $data[ 'logo_used' ] ): ?><div class="ctc-sermon-logo accent-background"><?php endif; ?>
						<img class="ctc-sermon-img" src="<?php echo $data[ 'img' ]; ?>"/>
<?php if ( $data[ 'logo_used' ] ): ?></div><?php endif;  ?>
					</a>
<?php else: ?>
					<a href="<?php echo $data[ 'permalink' ]; ?>">
						<div class="ctc-grid-full accent-background">
							<h1 class="ctc-sermon-name"><?php the_title(); ?></h1>
						</div>
					</a>
<?php endif; ?>
				</div> <!-- .ctc-sermon -->
<?php if( $data[ 'video' ] ): ?>
						<h3><a href="<?php echo $data[ 'permalink' ]; ?>"><?php the_title(); ?></a></h3>	
<?php elseif( $data[ 'audio' ] && ! $data[ 'video' ] ): ?>
					<div class="ctc-sermon-audio"><?php echo wp_audio_shortcode( array( 'src' => $data[ 'audio' ] ) ); ?></div>
<?php endif; ?>

<?php				
			endwhile;

		echo '</div>';
		echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tag'] = strip_tags( $new_instance['tag'] );
		$instance['add_link'] = strip_tags( $new_instance['add_link'] );
		if( strip_tags( $new_instance['link'] ) != '' && $new_instance['custom_link'] ){
			$instance['link'] = strip_tags( $new_instance['link'] );
			$instance['custom_link'] = strip_tags( $new_instance['custom_link'] );
		} else {
			$instance['link'] = '';
			$instance['custom_link'] = '';
		}
		if( $new_instance['use_location'] ){
			$instance['tag'] = '';
			$instance['use_location'] = strip_tags( $new_instance['use_location'] );
		}
		return $instance;
	}
	
	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
		$tag = esc_attr( $instance['tag'] );
		$add_link = esc_attr( $instance['add_link'] );
		$custom_link = esc_attr( $instance['custom_link'] );
		$link = esc_attr( $instance['link'] );
		$use_location = esc_attr( $instance['use_location'] );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'harvest' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('add_link'); ?>" name="<?php echo $this->get_field_name('add_link'); ?>" <?php echo $add_link ? 'checked': ''; ?> />
			<label for="<?php echo $this->get_field_id('add_link'); ?>" ><?php _e( 'Title Link?', 'harvest' ); ?></label> 
			&nbsp; &nbsp;
			<input type="checkbox" id="<?php echo $this->get_field_id('custom_link'); ?>" name="<?php echo $this->get_field_name('custom_link'); ?>" <?php echo $custom_link ? 'checked': ''; ?> />
			<label for="<?php echo $this->get_field_id('custom_link'); ?>"><?php _e( 'Custom Link?', 'harvest' ); ?></label> 
			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e( 'URL', 'harvest' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" <?php echo $add_link ? '' : 'disabled'; ?> value="<?php echo $link; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e( 'Choose a topic to display. If the <code>Use location</code> option is checked AND the widget is in a location page, then the most recent message with a topic matching that location is shown.', 'harvest' ); ?></label>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('use_location'); ?>" name="<?php echo $this->get_field_name('use_location'); ?>" <?php echo $use_location ? 'checked': ''; ?> />
			<label for="<?php echo $this->get_field_id('use_location'); ?>" title="<?php _e( 'If selected, when this widget is displayed on a location single post, it will automatically show the most recent sermon associated with that particular location if the sermon is given a topic that matches the location. If the option to link the tile is selected, then the title links to the sermon archive for that location.', 'harvest' ); ?>" ><?php _e( 'Use location', 'harvest' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'tag' ); ?>" id="<?php echo $this->get_field_id( 'tag' ); ?>" class="widefat" <?php echo $use_location ? 'disabled' : ''; ?> >
				<?php
				
				echo sprintf( '<option id="none" value="" %s>- None -</option>', empty( $tag ) ? ' selected=selected"' : '' );
				$tags = get_terms( 'ctc_sermon_topic', array( 'hide_empty' => 0 ) );
				foreach ($tags as $option) {
					echo sprintf( '<option id="%s" value="%s" %s>%s</option>', $option->slug, $option->slug, $tag == $option->slug ? ' selected=selected"' : '', $option->name );
				}
				?>
			</select>		
		</p>
		<script>
		
			jQuery(document).ready( function($) {
				$('#<?php echo $this->get_field_id('custom_link'); ?>').change( function (){
						$('#<?php echo $this->get_field_id('link'); ?>').prop('disabled', ! $( this ).is(':checked') );
				});
				$('#<?php echo $this->get_field_id('use_location'); ?>').change( function (){
						$('#<?php echo $this->get_field_id('tag'); ?>').prop('disabled', $( this ).is(':checked') );
				});
			});
		</script>
	<?php 
	} 
} 

