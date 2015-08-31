<?php 
/*
	Weekly calendar widget
*/
if ( ! class_exists( 'Church_Theme_Content' ) ) return;
class harvest_WeeklyCalendar extends WP_Widget {
		
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'weekly-calendar', 
			'description' => __( 'Harvest Weekly Calendar', 'harvest' ) 
		);
		parent::__construct( 'weekly-calendar', __( 'Harvest Weekly Calendar', 'harvest' ), $widget_ops);
		
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}
	
	function scripts() {
		if( ! wp_script_is( 'responsive-tabs-js', 'registered' ) )
			wp_register_script( 'responsive-tabs-js', get_stylesheet_directory_uri() . '/js/jquery.responsiveTabs.min.js', array('jquery') );
		
	}
	
	function getEvents( $week_start, $week_end, $max_recur = 7, $tag = '' ){
		$query = array(
			'post_type'      => 'ctc_event', 
			'order'          => 'ASC',
			'orderby'        => 'meta_value',
			'meta_key'       => '_ctc_event_start_date_start_time',
			'meta_type'      => 'DATETIME',
			'posts_per_page' => -1,
			'meta_query'     => array(
					array(
						'key'     => '_ctc_event_end_date',
						'value'   => date_i18n( 'Y-m-d' ), // today localized
						'compare' => '>=', // later than today
						'type'    => 'DATE',
					),
				), 
		); 
		
		if( !empty( $tag ) )  {
			$query[ 'tax_query' ] = array( 
				array(
					'taxonomy'  => 'ctc_event_category',
					'field'     => 'slug',
					'terms'     => $tag,
				),
			);
		}
		
		$posts = new WP_Query( $query );
		$events = array();
		if ( $posts -> have_posts() ){
			while ($posts->have_posts()) :
				$posts -> the_post();
				$post_id  = get_the_ID();
				$evt_title	= get_the_title();
				$url  = get_permalink();

				// Get event information
				$start_date  = get_post_meta( $post_id, '_ctc_event_start_date', true );
				$start_time  = get_post_meta( $post_id, '_ctc_event_start_time', true );
				$end_date    = get_post_meta( $post_id, '_ctc_event_end_date', true );
				$end_time    = get_post_meta( $post_id, '_ctc_event_end_time', true );
				$evt_len     = strtotime( $end_date ) - strtotime( $start_date );
				
				// Skip over events outside of the week
				if( isset( $week_start ) && $start_date < $week_start ) continue;
				if( isset( $week_end ) && $start_date > $week_end ) continue;
				
				
				// Add to event array
				$events[] = array(
					'id'          => $post_id,
					'title'       => $evt_title,
					'start_date'  => $start_date,
					'start_time'  => $start_time,
					'end_date'    => $end_date,
					'end_time'    => $end_time,
					'url'         => $url
				);
						
				// Get recurrent information
				$recurrence         = get_post_meta( $post_id, '_ctc_event_recurrence', true );
				$recurrence_end     = get_post_meta( $post_id, '_ctc_event_recurrence_end_date', true );
				$recurrence_period  = get_post_meta( $post_id, '_ctc_event_recurrence_period', true );
				
				// Only daily recurrence matters here all the others
				// would not show up
				if( $recurrence == 'daily' ) {
					$n = $recurrence_period != '' ? (int) $recurrence_period : 1;
					for ( $i = 1 ; $i <= $max_recur ; $i++ ) {
						list( $y, $m, $d ) = explode( '-', $start_date );
						$stDT = new DateTime( $start_date );
						$stDT->modify('+' . $i * $n  . ' days');
						list( $y, $m, $d ) = explode( '-', $stDT->format( 'Y-m-d' ) );
							
						// Fix for a day beyond the month's end
						$t = date( 't', mktime( 0, 0, 0, $m, 1, $y )) ;
						if($d > $t) {
							$recur_date = date( 'Y-m-t', mktime( 0, 0, 0, $m, 1, $y ));
						} else {
							$recur_date = date( 'Y-m-d', mktime( 0, 0, 0, $m, $d, $y ));
						}
						
						// Figure out the shift needed to apply to the end date & time
						$date_shift = strtotime( $recur_date ) - strtotime( $start_date );
						
						// stop if new date is past the recurrence end date
						if( ! empty( $recurrence_end ) && strtotime( $recur_date ) > strtotime( $recurrence_end) ) break;
						if( isset( $week_end ) && strtotime( $recur_date ) > strtotime( $week_end ) ) break;
						
						// shift end dates as well
						$recur_end_date = date('Y-m-d', strtotime( $start_date ) + $evt_len + $date_shift);
						
						// append to event array
						$events[] = array(
							'id'          => $post_id,
							'title'       => $evt_title,
							'start_date'  => $recur_date,
							'start_time'  => $start_time,
							'end_date'    => $recur_end_date,
							'end_time'    => $end_time,
							'url'         => $url
						);
					} 
				} 
			endwhile; 
		}
		wp_reset_query();			
		return $events;
	} 
	
	function widget( $args, $instance ) {
		global $post;
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Weekly Calendar', 'harvest' ) : $instance['title'], $instance, $this->id_base );
		$add_link = $instance['add_link'];
		$custom_link = $instance['custom_link'];
		$link = $instance['link'];
		$tag = $instance['tag'];
		$use_location = $instance['use_location'];
		
		if( $use_location && $post->post_type == 'ctc_location' ) {
			$tag = $post->post_name;
			if( ! $custom_link ){
				$term = get_term_by( 'slug', $tag, 'ctc_event_category' );
				if( $term )
					$link = get_term_link( intval( $term->term_id ), 'ctc_event_category' );
				else
					$link = '';
			}
		}
		
		$week_start = date_i18n( 'Y-m-d' );
		$week_end = date_i18n( 'Y-m-d', strtotime( '+6 days' ) );
		
		$events = $this->getEvents( $week_start, $week_end, 7 , $tag );
		wp_enqueue_script( 'responsive-tabs-js' );
			
		$this->prep_scripts();
		
		echo $before_widget;
		if ( $title ) {
			if( $add_link ) {
				$before_title = $before_title . '<a class="ctc-cal-week-title-link" href="'.$link . '">';
				$after_title = '</a>' . $after_title;
			} 
			echo $before_title . $title . $after_title;
		}
		
		echo '<div id="ctc-cal-week" style="display:none"><ul>';
		
		$week_startDT = new DateTime( $week_start );
		$week_endDT = new DateTime( $week_end );
		
		// Do the day tabs
		$dayN = 1;
		while( $dayN <= 7 ) {
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
		while( $dayN <= 7 ) {
			$day = $week_startDT -> format( 'j' );
			$week_day = date_i18n( 'l', $week_startDT -> getTimestamp() );
			echo '<div id="day-'. $dayN . '">';
			$evt_count = 0;
			foreach( $events as $evt ){
				$evt_date = new DateTime( $evt[ 'start_date' ] );
				if( $evt_date == $week_startDT ) {
					if( $evt[ 'start_time' ] ) {
						$evt_time = new DateTime( $evt[ 'start_time' ] );
						echo sprintf('<p>%s &mdash; <a href="%s">%s</a></p>', $evt_time -> format('g:i A'), $evt[ 'url' ], $evt[ 'title' ] );
					} else
						echo sprintf('<p><a href="%s">%s</a></p>', $evt[ 'url' ], $evt[ 'title' ] );							
					$evt_count ++;
				}
			}
			
			if( $evt_count == 0 ) 
				echo '<p>'. sprintf( __( 'No events listed for %s', 'harvest' ), $week_day ). '</p>';
				
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
		$add_link = esc_attr( $instance['add_link'] );
		$custom_link = esc_attr( $instance['custom_link'] );
		$link = esc_attr( $instance['link'] );
		$use_location = esc_attr( $instance[ 'use_location' ] );
		$tag = esc_attr( $instance[ 'tag' ] );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'harvest' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
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
			<label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e( 'Choose a category to display. If the <code>Use location</code> option is checked AND the widget is in a location page, then the events with a category matching that location will be shown.', 'harvest' ); ?></label>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('use_location'); ?>" name="<?php echo $this->get_field_name('use_location'); ?>" <?php echo $use_location ? 'checked': ''; ?> />
			<label for="<?php echo $this->get_field_id('use_location'); ?>" title="<?php _e( 'If selected, when this widget is displayed on a location single post, it will automatically show the events associated with that particular location if the event is given a category that matches the location. If the option to link the tile is selected, then the title links to the category archive.', 'harvest' ); ?>" ><?php _e( 'Use location', 'harvest' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'tag' ); ?>" id="<?php echo $this->get_field_id( 'tag' ); ?>" class="widefat" <?php echo $use_location ? 'disabled' : ''; ?> >
				<?php
				
				echo sprintf( '<option id="none" value="" %s>- None -</option>', empty( $tag ) ? ' selected=selected"' : '' );
				$tags = get_terms( 'ctc_event_category', array( 'hide_empty' => 0 ) );
				foreach ($tags as $option) {
					echo sprintf( '<option id="%s" value="%s" %s>%s</option>', $option->slug, $option->slug, $tag == $option->slug ? ' selected=selected"' : '', $option->name );
				}
				?>
			</select>		
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

