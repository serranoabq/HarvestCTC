<?php
	// HELPER: Widgets
	
	add_action( 'widgets_init', 'harvest_registerWidgets' );
	function harvest_registerWidgets() {
		register_widget( 'harvest_HomeWidget' );
		register_widget( 'harvest_WeekWidget' );
	}
	
class harvest_HomeWidget extends WP_Widget {
	function harvest_HomeWidget() {
		global $pagenow;
		
		$widget_ops = array(
			'classname' => 'front-box-widget', 
			'description' => 'Harvest Home Box' 
		);
		$this->WP_Widget('front_box_widget', __('Harvest Home Page Box'), $widget_ops);
	
	}
	
	function widget( $args, $instance ) {
		
		extract( $args );
		$number = $instance['number'];
		$caption = esc_attr($instance['caption']);
		$image_url = esc_attr($instance['image_url']);
		$link = esc_attr($instance['link']);
		
		echo $before_widget;
		?>
		<figure>
		<?php if ($link): ?>
			<a href="<?php echo $link; ?>">
		<?php endif; ?>
				<img src="<?php echo $image_url; ?>" />
				<figcaption><?php echo $caption; ?></figcaption>
		<?php if ($link): ?>
			</a>
		<?php endif; ?>	
		</figure>
	<?php
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['caption'] = strip_tags($new_instance['caption']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['image_url'] = strip_tags($new_instance['image_url']);		
		return $instance;
	}
	
	function prep_scripts(){
		echo '
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				var _custom_media = true
				var _orig_send_attachment = wp.media.editor.send.attachment;
	 
				$(".upload_button").click(function(e) {
					var send_attachment_bkp = wp.media.editor.send.attachment;
					var button = $(this);
					targetfield = $(this).prev(".upload-url");
					wp.media.editor.send.attachment = function(props, attachment) {
						if ( _custom_media ) {
							targetfield.val(attachment.url);
						} else {
							return _orig_send_attachment.apply( this, [props, attachment] );
						};
					};
			 
					wp.media.editor.open(button);
					return false;
				});
			 
				$(".add_media").click( function(){ _custom_media = false; });
			});
		</script>';
	}
	
	function form( $instance ) {
		$caption = esc_attr( $instance['caption'] );
		$image_url = esc_attr( $instance['image_url'] );
		$link = esc_attr( $instance['link'] );
		//add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
		wp_enqueue_media();
		$this->prep_scripts();
?>
    <p>
			<label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e('Caption'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo $caption; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link URL'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image'); ?></label> 
			<input class="widefat upload-url" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo $image_url; ?>" /> <input id="image_upload_button" class="upload_button button-secondary" type="button" name="image_upload_button" value="Upload" /> 
		</p>

<?php 
	} 
} 

class harvest_WeekWidget extends WP_Widget {
		
	function harvest_WeekWidget() {
		$widget_ops = array(
			'classname' 	=> 'weekly-calendar', 
			'description' => __( 'Harvest Weekly Calendar', 'harvest' ) 
		);
		$this->WP_Widget( 'weekly-calendar', __( 'Harvest Weekly Calendar', 'harvest' ), $widget_ops);
	}
	
	function getEvents( $week_start, $week_end, $max_recur ){
		if ( ! isset( $max_recur ) ) $max_recur = 12;
		$query = array(
			'post_type' 			=> 'ctc_event', 
			'order' 					=> 'ASC',
			'orderby' 				=> 'meta_value',
			'meta_key' 				=> '_ctc_event_start_date',
			'meta_type' 			=> 'DATETIME',
		); 
		$posts = new WP_Query();
		$posts -> query( $query ); 
		if ( $posts -> have_posts() ){
			$events = array();
			while ($posts->have_posts()) :
				$posts		 	-> the_post();
				$post_id 	 	= get_the_ID();
				$evt_title	= get_the_title();
				$url 			 	= get_permalink();
				
				// Get event information
				$start_date 	= get_post_meta( $post_id, '_ctc_event_start_date', true );
				$start_time 	= get_post_meta( $post_id, '_ctc_event_start_time', true );
				$end_date   	= get_post_meta( $post_id, '_ctc_event_end_date', true );
				$end_time   	= get_post_meta( $post_id, '_ctc_event_end_time', true );
				$evt_len 			= strtotime( $end_date ) - strtotime( $start_date );
				
				// Skip over events outside of the week
				if( isset( $week_start ) && $start_date < $week_start ) continue;
				if( isset( $week_end ) && $start_date > $week_end ) continue;
				
				// Add to event array
				$events[] = array(
					'id' 		 			=> $post_id,
					'title'  			=> $evt_title,
					'start_date'  => $start_date,
					'start_time'  => $start_time,
					'end_date' 	 	=> $end_date,
					'end_time' 	 	=> $end_time,
					'url'    => $url
				);
				
				// Get recurrent information
				$recurrence 		  = get_post_meta( $post_id, '_ctc_event_recurrence', true );
				$recurrence_end 	= get_post_meta( $post_id, '_ctc_event_recurrence_end_date', true );
				$recurrence_period 	= get_post_meta( $post_id, '_ctc_event_recurrence_period', true );
						
				if ($recurrence != 'none') {
					$n = $recurrence_period != '' ? (int) $recurrence_period : 1;
					//$stDT = new DateTime( $start_date );
					for ( $i = 1 ; $i <= $max_recur ; $i++ ) {
						list( $y, $m, $d ) = explode( '-', $start_date );
						switch ($recurrence) {
							// NOTE: Daily is not an option in the CTC plugin 
							case 'daily':
								$stDT = new DateTime( $start_date );
								$stDT->modify('+' . $i * $n  . ' days');
								list( $y, $m, $d ) = explode( '-', $stDT->format( 'Y-m-d' ) );
								break;
							case 'weekly':
								// same day of the week (eg, Sun-Sat)
								$stDT = new DateTime( $start_date );
								$stDT -> modify('+' , $i * $n  . ' weeks');
								list( $y, $m, $d ) = explode( '-', $stDT->format( 'Y-m-d' ) );
								break;
							case 'monthly':
								// same day of the month (eg., the 13th)
								$m += $i * $n;							
								$ny = floor(($m-1)/12);
								$m -= 12*$ny;
								$y += $ny;
								break;
							case 'yearly':
								// same day of the year (eg., the 27th of October)
								$y += $i * $n;
								break;
						} // switch
						
						// Fix for a day beyond the month's end
						$t = date( 't', mktime( 0, 0, 0, $m, 1, $y )) ;
						if($d > $t) {
							$recur_date = date( 'Y-m-t', mktime( 0, 0, 0, $m, 1, $y ));
						} else {
							$recur_date = date( 'Y-m-d', mktime( 0, 0, 0, $m, $d, $y ));
						}
						//echo "<div>$i $n $recur_date $recurrence_end</div>";
						// Figure out the shift needed to apply to the end date & time
						$date_shift = strtotime( $recur_date ) - strtotime( $start_date );
						
						// stop if new date is past the recurrence end date
						if( strtotime( $recur_date ) > strtotime( $recurrence_end) ) break;
						if( isset( $week_end ) && strtotime( $recur_date ) > strtotime( $week_end ) ) break;
						
						// shift end dates as well
						$recur_end_date = date('Y-m-d', strtotime( $start_date ) + $evt_len + $date_shift);
						
						// append to event array
						$events[] = array(
							'id' 		 			=> $post_id,
							'title'  			=> $evt_title,
							'start_date'  => $recur_date,
							'start_time'  => $start_time,
							'end_date' 	 	=> $recur_end_date,
							'end_time' 	 	=> $end_time,
							'url'    			=> $url
						);
					} 
				} 
			endwhile; 
		}
		wp_reset_query();			

		return $events;
	} 
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Weekly Calendar', 'harvest' ) : $instance['title'], $instance, id_base);
		$add_link = $instance['add_link'];
		$link = $instance['link'];
		
		$week_start = date( 'Y-m-d' );
		$week_end = date( 'Y-m-d', strtotime( '+6 days' ) );
		
		$events = $this->getEvents( $week_start, $week_end );
		
		wp_enqueue_script( 'responsive-tabs-js' );
		//wp_enqueue_style( 'weekly_cal' );
		
		$this->prep_scripts();
		
		echo $before_widget;
		if ( $title ) {
			if( $add_link ) {
				$after_title = '<a class="ctc-cal-week-title-link" href="'.$link . '"><i class="fa fa-chevron-right pull-right"></i></a>' . $after_title;
			} 
			echo $before_title . $title . $after_title;
		}
		
		echo '<div id="ctc-cal-week" style="display:none"><ul>';
		
		$week_startDT = new DateTime( $week_start );
		$week_endDT = new DateTime( $week_end );
		
		// Do the day tabs
		$dayN = 1;
		while( $week_startDT <= $week_endDT ) {
			$day = $week_startDT -> format( 'j' );
			$week_day = date_i18n( 'l', $week_startDT -> getTimestamp() );
			echo '<li><a href="#day-'. $dayN .'">'. $day . "<span>$week_day</span>" .'</a></li>';
			$week_startDT -> modify( '+1 day' );
			$dayN++;
		}
		echo '</ul>';
		
		// Do the daily content
		$week_startDT = new DateTime( $week_start );
		$dayN = 1;
		while( $week_startDT <= $week_endDT ) {
			$day = $week_startDT -> format( 'j' );
			$week_day = date_i18n( 'l', $week_startDT -> getTimestamp() );
			echo '<div id="day-'. $dayN . '">';
			$evt_count = 0;
			foreach( $events as $evt ){
				$evt_date = new DateTime( $evt[ 'start_date' ] );
				if( $evt_date == $week_startDT ) {
					if( $evt[ 'start_time' ] )
						echo sprintf('<p>%s &mdash; <a href="%s">%s</a></p>', $evt[ 'start_time' ], $evt[ 'url' ], $evt[ 'title' ] );
					else
						echo sprintf('<p><a href="%s">%s</a></p>', $evt[ 'url' ], $evt[ 'title' ] );							
					$evt_count ++;
				}
			}
			
			if( $evt_count == 0 ) 
				echo "<p>No events listed for $week_day</p>";
				
			echo '</div>';
			$week_startDT -> modify( '+1 day' );
			$dayN++;
		}
		/**/
		echo '</div>';
		echo $after_widget;
	}

	function prep_scripts(){
		echo '
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$( ".weekly-calendar" ).show();
				$( "#ctc-cal-week" ).each( function(){ $( this ).responsiveTabs(); } );
				ctc_accordion();
				$( window ).resize( function(){ ctc_accordion(); } );
	
				function ctc_accordion(){
					jQuery( "#ctc-cal-week" ).each( function(){ 
						cww = jQuery( this ).width();
						if ( cww < 512 ) 
							jQuery(  this ).addClass( "accordion" );
						else
							jQuery( this ).removeClass( "accordion" );
					} );
				}
				
			});
		</script>';
	}
	
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		if( strip_tags( $new_instance['link'] ) != '' && $new_instance['add_link'] ){
			$instance['link'] = strip_tags( $new_instance['link'] );
			$instance['add_link'] = strip_tags( $new_instance['add_link'] );
		} else {
			$instance['link'] = '';
			$instance['add_link'] = '';
		}
		return $instance;
	}
	
	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
		$add_link = esc_attr( $instance['add_link'] );
		$link = esc_attr( $instance['link'] );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('add_link'); ?>" name="<?php echo $this->get_field_name('add_link'); ?>" <?php echo $add_link ? 'checked': ''; ?> />
			<label for="<?php echo $this->get_field_id('add_link'); ?>"><?php _e( 'Show Title Link', 'harvest' ); ?></label> 
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" <?php echo $add_link ? '' : 'disabled'; ?> value="<?php echo $link; ?>" />
		</p>
		<script>
		<?php 
		// There's no need to have two of these on the same sidebar, so this code below
		// prevents adding a second instance to the same sidebar. This won't prevent 
		// adding it to the same page (via separate widget areas), but it's a start
		?>
			jQuery(document).ready( function($) {
				var sidebars = $('.widgets-sortables');
				sidebars.each( function() {
					var id_base = $(this).find("input.id_base");
					var me = $( this );
					if( me.attr('id').indexOf('inactive') != -1 ) return;
					var found = false;
					id_base.each( function(){
						if( $(this).val() != 'widget_weekcal') return;
						if(found) 
							$(this).closest('div.widget').remove();
						else
							found = true;
					});
				});
				$('#<?php echo $this->get_field_id('add_link'); ?>').change( function (){
						$('#<?php echo $this->get_field_id('link'); ?>').prop('disabled', ! $( this ).is(':checked') );
					
				});
			});
		</script>
	<?php 
	} 
} 


