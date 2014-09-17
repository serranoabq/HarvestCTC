<?php
/**
 * CUSTOM POST TYPE: Staff
 * 
 * Custom post type for showing staff member profiles
 * 
 */
?>
<?php

// Create Staff post type
add_action( 'init', 'harvest_createStaffPostType' );
function harvest_createStaffPostType() {
	register_post_type( 'staff',
		array(
			'labels' => array(
				'name' 								=> __( 'Staff' ),
				'singular_name' 			=> __( 'Staff Member' ),
				'add_new' 						=> __( 'Add New Staff Member' ),
				'add_new_item' 				=> __( 'Add New Staff Member' ),
				'edit' 								=> __( 'Edit Staff Member' ),
				'edit_item' 					=> __( 'Edit Staff Member' ),
				'new_item' 						=> __( 'Add New Staff Member' ),
				'view' 								=> __( 'View Staff Member' ),
				'view_item' 					=> __( 'View Staff Member' ),
				'search_items' 				=> __( 'Search Staff Members' ),
				'not_found' 					=> __( 'No Staff Members found' ),
				'not_found_in_trash' 	=> __( 'No Staff Members found in Trash' ),
				'menu_name'						=> __( 'Staff' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/staff-icon.png',  // Icon Path
			'supports' 							=> array('title', 'editor', 'thumbnail' ),
			'rewrite' 							=> true,
			'query_var' 						=> true,
			'exclude_from_search' 	=> false,
			'show_ui' 							=> true,
			'show_in_menu'					=> true,
			'show_in_nav_menus'			=> true,
			'capability_type' 			=> 'post'
		)
	);
}

// Create Metaboxes 
add_action("admin_init", "harvest_add_staff_metaboxes");
function harvest_add_staff_metaboxes(){
	add_meta_box("staff_designation", "Staff Position, Title, or Designation", "harvest_staff_metabox", "staff", "normal", "high");
	add_meta_box("staff_email", "Staff Email", "harvest_staff_email_metabox", "staff", "normal", "high");
}

//// Staff Desigation
function harvest_staff_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$staff_designation = $custom["staff_designation"][0];
?>
	<label>Designation:</label>
	<input name="staff_designation" value="<?php echo $staff_designation; ?>" style="width: 50%;" />
<?php
}
// Save Link
function harvest_save_staff_designation($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "staff_designation", $_POST["staff_designation"]);
}
add_action('save_post', 'harvest_save_staff_designation');
//// End Staff Designation

//// Staff Email
function harvest_staff_email_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$staff_email = $custom["staff_email"][0];
?>
	<label>Staff Email:</label>
	<input name="staff_email" value="<?php echo $staff_email; ?>" style="width: 50%;" />
<?php
}
// Save Link
function harvest_save_staff_email($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "staff_email", $_POST["staff_email"]);
}
add_action('save_post', 'harvest_save_staff_email');
//// End Staff Designation


// Register Widget Post widget
add_action( 'widgets_init', 'harvest_registerStaffWidget' );
function harvest_registerStaffWidget() {
	register_widget( 'harvest_Staff_Widget' );
}

class harvest_Staff_Widget extends WP_Widget {
	function harvest_Staff_Widget() {
		$widget_ops = array(
			'classname' => 'widget_staff', 
			'description' => __( 'Church Staff') 
		);
		$this->WP_Widget('widget_staff', __('Church Staff'), $widget_ops);
	}
	function widget( $args, $instance ) {
		
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Our Staff' ) : $instance['title'], $instance, $this->id_base);
		
		$feat_query = array(
			'post_type' => 'staff', 
			'order' => 'DESC') ; 
		$feat_posts = new WP_Query();
		$feat_posts->query($feat_query); $i=1;
		if ($feat_posts->have_posts()) { 
			echo $before_widget;
			if ( $title)
				echo $before_title .$title . $after_title;
			echo '<ul>';
			while ($feat_posts->have_posts()) : $feat_posts->the_post();
				echo '<li>'; $i++;
				echo get_the_post_thumbnail(get_the_ID(),array(75,75), array('class' => 'staff_widget_img left'));
				$email=get_post_meta(get_the_ID(), 'staff_email', true);
				$position=get_post_meta(get_the_ID(), 'staff_designation', true);
				echo '<h4>';
				if($email)
						echo '<a class="icon-envelope" href="mailto:'.$email.'" title="Email '.get_the_title().'"></a>&nbsp; ';
				echo get_the_title().'</h4>';
				echo '<div class="staff_info">';
				if($position)
					echo '<div class="staff_position"><i class="icon-user"></i>'.$position.'</div>';
				
				echo '</div></li>';
			
			endwhile; wp_reset_query(); echo '</ul>';
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