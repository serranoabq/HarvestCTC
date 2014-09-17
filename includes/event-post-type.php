<?php
/**
 * CUSTOM POST TYPE: Events
 * 
 * Custom post type for displaying news and events
 * 
 */
?>
<?php

// Create Events post type
add_action( 'init', 'harvest_createEventsPostType' );
function harvest_createEventsPostType() {
	register_post_type( 'events',
		array(
			'labels' => array(
				'name' 								=> __( 'Events' ),
				'singular_name' 			=> __( 'Event' ),
				'add_new' 						=> __( 'Add New Event' ),
				'add_new_item' 				=> __( 'Add New Event' ),
				'edit' 								=> __( 'Edit Event' ),
				'edit_item' 					=> __( 'Edit Event' ),
				'new_item' 						=> __( 'Add New Event' ),
				'view' 								=> __( 'View Event' ),
				'view_item' 					=> __( 'View Event' ),
				'search_items' 				=> __( 'Search Events' ),
				'not_found' 					=> __( 'No Events Found' ),
				'not_found_in_trash' 	=> __( 'No Events Found in Trash' ),
				'menu_name'						=> __( 'Events' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/calendar-icon.png',  // Icon Path
			'supports' 							=> array('title', 'editor', 'thumbnail' ),
			'rewrite' 							=> true,
			'query_var' 						=> true,
			'exclude_from_search' 	=> false,
			'menu_position' 				=> '5',
			'show_ui' 							=> true,
			'show_in_menu'					=> true,
			'show_in_nav_menus'			=> true,
			'capability_type' 			=> 'post'
		)
	);
	
}

// Change Scheduled for to "Event End Date"
add_filter('gettext', 'harvest_translation_mangler', 10, 4);
function harvest_translation_mangler($translation, $text, $domain) {
		global $post;
		if ( is_object($post) && $post->post_type == 'events') {

		$translations = &get_translations_for_domain( $domain);
		if ( $text == 'Scheduled for: <b>%1$s</b>') {
			return $translations->translate( 'Event End Date: <b>%1$s</b>' );
		}
		if ( $text == 'Published on: <b>%1$s</b>') {
			return $translations->translate( 'Event End Date: <b>%1$s</b>' );
		}
		if ( $text == 'Publish <b>immediately</b>') {
			return $translations->translate( 'Event End Date: <b>%1$s</b>' );
		}
	}

	return $translation;
}

// Show Scheduled Posts
add_filter('the_posts', 'harvest_showScheduledPosts');
function harvest_showScheduledPosts($posts) {
   global $wp_query, $wpdb;
   if(is_single() && $wp_query->post_count == 0) {
      $posts = $wpdb->get_results($wp_query->request);
   }
   return $posts;
}

// Add metaboxes for events
add_action("admin_init", "harvest_add_events_metaboxes");
function harvest_add_events_metaboxes(){
	add_meta_box("event_location", "Event Location", "harvest_event_location_metabox", "events", "normal", "high");
	add_meta_box("event_start", "Event Start Date", "harvest_event_start_metabox", "events", "side", "high");
	add_meta_box("event_asnews", "Treat as Annoucement", "harvest_event_asnews_metabox", "events", "side", "high");
}

//// Event Location 
function harvest_event_location_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$event_location = $custom["event_location"][0];
	$map = $custom["show_map"][0];
?>
    <label>Event Location:</label>
	<input name="event_location" value="<?php echo $event_location; ?>" style="width: 50%;" /> <br/>
	<input type="checkbox" name="show_map" id="show_map" <?php echo  $map ? "checked" : "" ?> />
	<label for="show_map" title="Show Link to Google Maps">Show Link to Map (Ensure the Event Venue can be located in Google Maps by name alone)</label>
<?php
}
// Save Location
add_action('save_post', 'harvest_save_event_location');
function harvest_save_event_location($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "event_location", $_POST["event_location"]);
	update_post_meta($post->ID, "show_map", isset($_POST["show_map"]));
}
//// End Event Location


//// Event Start Date  
function harvest_event_start_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$event_start_date = $custom["event_start_date"][0];
		
	$start_date=strtotime($event_start_date);
	if(!empty($start_date)){
		$start_mm=date("m",$start_date);
		$start_yy=date("Y",$start_date);
		$start_jj=date("d",$start_date);
	}else{
		$start_mm="";
		$start_yy="";
		$start_jj="";
	}
	
	// Handle the validation errors. This will display a message in the metabox
	$is_error = isset($_GET["start_date_error"]);
	switch (absint($_GET["start_date_error"])) {
	case 1:
		$msg = "All parts of the date must be completed. ";
		break;
	case 2:
		$msg = "The event start date must occurr before the event end date. ";
		break;
	default:
		$msg="";
	}
	$error_msg = "Invalid Start  Date. ".$msg."Start date cleared.";
	
?>
<?php if($is_error){ ?>
	<style>
		.is-error{
			background-color: #FFEBE8;
    	border: 1px solid #c00;
    	border-radius: 3px;
    	padding: 7px;
    }
	</style>
	<p class="is-error"><?php echo $error_msg; ?></p>
<?php } ?>
	<input type="checkbox" name="use_start" id="use_start" onclick="jQuery('#start_date').toggle(this.checked);" <?php echo (empty($start_mm)?'':'checked'); ?>/>
	<label for="use_start" title="Use a separate start date">Use separate start date </label>
	<div class="timestamp-wrap" id="start_date" style="display: <?php echo (empty($start_mm) && !$is_error?'none':'block'); ?>">
		<p>Enter the start date for events that span more than one day. The Event Date above should be the end date of the event. Leave blank for single-day events.</p>
		<select id="start_mm" name="start_mm">
			<option value="" disabled selected="selected" style="display:none">Month</option>
			<option value="01" <?php echo ($start_mm=="01"?'selected="selected"':''); ?> >01-Jan</option>
			<option value="02" <?php echo ($start_mm=="02"?'selected="selected"':''); ?> >02-Feb</option>
			<option value="03" <?php echo ($start_mm=="03"?'selected="selected"':''); ?> >03-Mar</option>
			<option value="04" <?php echo ($start_mm=="04"?'selected="selected"':''); ?> >04-Apr</option>
			<option value="05" <?php echo ($start_mm=="05"?'selected="selected"':''); ?> >05-May</option>
			<option value="06" <?php echo ($start_mm=="06"?'selected="selected"':''); ?> >06-Jun</option>
			<option value="07" <?php echo ($start_mm=="07"?'selected="selected"':''); ?> >07-Jul</option>
			<option value="08" <?php echo ($start_mm=="08"?'selected="selected"':''); ?> >08-Aug</option>
			<option value="09" <?php echo ($start_mm=="09"?'selected="selected"':''); ?> >09-Sep</option>
			<option value="10" <?php echo ($start_mm=="10"?'selected="selected"':''); ?> >10-Oct</option>
			<option value="11" <?php echo ($start_mm=="11"?'selected="selected"':''); ?> >11-Nov</option>
			<option value="12" <?php echo ($start_mm=="12"?'selected="selected"':''); ?> >12-Dec</option>
    </select>
    <input type="text" id="start_jj" name="start_jj" size="2" maxlength="2" title="day" placeholder="Day" value="<?php echo esc_attr($start_jj); ?>" />, 
    <input type="text" id="start_yy" name="start_yy" size="4" maxlength="4" title="year" placeholder="Year" value="<?php echo esc_attr($start_yy); ?>" />
    
  	</div> <!-- .timestamp-wrap -->
<?php
}

// Save Start Date
add_action('save_post', 'harvest_save_event_start');
function harvest_save_event_start($post_id) {
	global $post, $error_msg;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	// check if the date elements are set
	$start_mm = trim(isset($_POST["start_mm"])?$_POST["start_mm"]:"");
	$start_yy = trim(isset($_POST["start_yy"])?$_POST["start_yy"]:"");
	$start_jj = trim(isset($_POST["start_jj"])?$_POST["start_jj"]:"");
	$use_start = isset($_POST["use_start"]);
	
	// if any is not set we clear
	$start_date = $start_yy."-".$start_mm."-".$start_jj;
	$start_time = strtotime($start_date);
	$post_date = strtotime($post->post_date);
	
	// invalid conditions are: invalid month, day or year, end time greater 
	// than the event time or the use_end checkbox unchecked
	if(!$use_start){
		// checkbox is empty, start date not needed
		$start_date = "";
	}elseif(empty($start_mm)||empty($start_yy)||empty($start_jj)){
		// one of the date parts was not set, clear and notify
		$start_date = "";
		$error_msg = 1;
		add_filter('redirect_post_location', 'harvest_date_error_redirect_filter', '99');
	}elseif($post_date<$start_time){
		// the start date is after the end, clear and notify
		$start_date = "";
		$error_msg = 2;
		add_filter('redirect_post_location', 'harvest_date_error_redirect_filter', '99');
	}
	update_post_meta( 
		$post->ID, 
		"events_start_date", 
		(empty($start_date) ? "" : date("Y-m-d",strtotime($start_date)))
	);
		
}
//// End Event Start Date

// Redirection filter to show errors
function harvest_date_error_redirect_filter($location) {
	global $error_msg;
  remove_filter('redirect_post_location', __FUNCTION__, '99');
  return add_query_arg('start_date_error', $error_msg, $location);
}

//// Event as News 
function harvest_event_asnews_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$events_as_news = $custom["event_as_news"][0];
?>
  <input type="checkbox" name="event_as_news" id="event_as_news" <?php echo  $event_as_news ? "checked" : "" ?> />  
	<label for="event_as_news" title="Treat this event as an announcement">Check this box to show this event as an undated announcement. The announcement is mixed in with the other events, but no date label is included. The scheduled date is the date up to which the announcement is displayed. </label>
<?php
}

// Save Event as News flag
add_action('save_post', 'harvest_save_event_asnews');
function harvest_save_event_asnews($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "event_as_news", $_POST["event_as_news"]);
}
//// End Event as News


// Edit Columns 
add_action("manage_posts_custom_column",  "harvest_events_custom_columns");
add_filter("manage_events_posts_columns", "harvest_events_edit_columns");
function harvest_events_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Event Title",    
    "location" => "Event Location",
    "map"	=> "Show Map",
    "ann"	=> "Announcement",
    "start"  => "Event Start Date",
    "date" => "Event End Date"
  );
 
  return $columns;
}
function harvest_events_custom_columns($column){
  global $post;
  switch ($column) {   
    case "location":
			$custom = get_post_custom();
			echo $custom["event_location"][0];
			break;
    case "map":
    	$custom = get_post_custom();
			echo $custom["show_map"][0];
			break;
		case "ann":
    	$custom = get_post_custom();
			echo $custom["event_as_news"][0];
			break;
		case "start":
			$custom = get_post_custom();
			$start_date = $custom["event_start_date"][0];
			if(!empty($start_date))
				echo date('Y/m/d',strtotime($start_date));
			else
				echo '';
			break;
  }
}

// Register Upcoming Events widget
add_action( 'widgets_init', 'harvest_registerEventsWidget' );
function harvest_registerEventsWidget() {
	register_widget( 'harvest_Events_Widget' );
}
class harvest_Events_Widget extends WP_Widget {
	function harvest_Events_Widget() {
		$widget_ops = array(
			'classname' => 'widget_upcoming_events', 
			'description' => __( 'Upcoming Events') 
		);
		$this->WP_Widget('widget_upcoming_events', __('Upcoming Events'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		// Number of posts to show in widget
		$widget_posts = 3;
		
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'News &amp; Events' ) : $instance['title'], $instance, $this->id_base);
		
		$feat_query = array(
			'post_type' => 'events', 
			'post_status' => 'future', 
			'posts_per_page' => $widget_posts, 
			'order' => 'ASC'
		) ; 
		$feat_posts = new WP_Query();
		$feat_posts->query($feat_query); $i=1;
		if ($feat_posts->have_posts()) { 
			echo $before_widget;
			if ($title)
				echo $before_title . $title . $after_title;
			
			echo '<ul>';
			while ($feat_posts->have_posts()) : $feat_posts->the_post();
				// Check both start_date meta and the post date
				$start_date = get_post_meta(get_the_ID(), 'event_start_date', true);
				$wd = get_the_time('D, M j', get_the_ID());
				
				if(!empty($start_date))
					$wd = date("M j", strtotime($start_date))." - ".get_the_time('M j', get_the_ID());
					
				$tm = get_the_time('g:ia', get_the_ID());
				$is_event = get_post_meta(get_the_ID(), 'event_as_news', true);
				$is_event = empty($is_event);
				
				echo '<li>'; $i++;
				if(has_post_thumbnail(get_the_ID())){
					echo get_the_post_thumbnail(get_the_ID(),array(75,75), array('class' => 'event_widget_img left'));
				} else { 
						if($is_event) { ?>
					 <img src="<?php bloginfo('template_directory'); ?>/images/default-event.png" class="event_widget_img left wp-post-image" /> 
						<?php } else { ?>
					 <img src="<?php bloginfo('template_directory'); ?>/images/default-news.png" class="event_widget_img left wp-post-image" />
						<?php }
				}
				echo '<div class="event_info">';
				echo '<h4><a href="'. get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a></h4>';
				if($is_event)
					echo '<div class="event_datetime">'.$wd.' @ '.$tm .'</div>';
				echo '</div></li>';
			endwhile; wp_reset_query(); 
			echo '</ul>';
			$after_widget .=  '<div class="biglink">';
			$after_widget .=  '<a href="'.get_bloginfo('home').'/events/" title="view all news &amp; events" class="green">';
			$after_widget .=  '<img src="'.get_bloginfo('template_directory').'/images/ico_calender.png" alt="Calendar" />';		
			$after_widget .=  '<span class="small">view all</span><strong>news &amp; events</strong></a></div>';
			echo $after_widget;
		}
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
	?>
    <p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
<?php 
	} 
} 


?>