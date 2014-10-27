<?php
/**
 * CUSTOM POST TYPE: Widget_Post
 * 
 * Allows the use of the post editing tools in a widget
 * 
 */
?>
<?php

// Create Widget Post post type
add_action( 'init', 'harvest_createWidgetPostType' );
function harvest_createWidgetPostType() {
	register_post_type( 'widget_post',
		array(
			'labels' => array(
				'name' 								=> __( 'Widget Posts' ),
				'singular_name' 			=> __( 'Widget Post' ),
				'add_new' 						=> __( 'Add New' ),
				'add_new_item' 				=> __( 'Add New' ),
				'edit' 								=> __( 'Edit' ),
				'edit_item' 					=> __( 'Edit Widget Post' ),
				'new_item' 						=> __( 'New Widget Post' ),
				'view' 								=> __( 'View Widget Post' ),
				'view_item' 					=> __( 'View Widget Post' ),
				'search_items' 				=> __( 'Search widget posts' ),
				'not_found' 					=> __( 'No widget posts found' ),
				'not_found_in_trash' 	=> __( 'No widget posts found in Trash' ),
				'parent' 							=> __( 'Parent Widget Post' ),
				'menu_name'						=> __( 'Widget Posts' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/notebook-icon.png',  // Icon Path
			'supports' 							=> array('title', 'editor' ),
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

// Register Widget Post widget
add_action( 'widgets_init', 'harvest_registerWidgetPostWidget' );
function harvest_registerWidgetPostWidget() {
	register_widget( 'harvest_Widget_Post' );
}

class harvest_Widget_Post extends WP_Widget {
	function harvest_Widget_Post() {
		$widget_ops = array(
			'classname' => 'widget_post', 
			'description' => __( 'Display widget posts') 
		);
		$this->WP_Widget('widget_post', __('Widget Post'), $widget_ops);
	}
	function widget( $args, $instance ) {
		
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$number = $instance['number'];
		
		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;
?>
			<div class="textwidget">
<?php
			$pid= $number;
			$widget_post_id = get_post($pid);
			$content = $widget_post_id->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]>', $content);
			
			echo $content;
?>
			</div> <!-- .textwidget -->
<?php 
			echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	
	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
		$number = esc_attr($instance['number']);
?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
    <p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Select Widget Post to display:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>">
<?php 
	$nposts = get_posts('numberposts=10000&post_type=widget_post');
	foreach($nposts as $entry) :
		$val = $entry->ID;
			echo '<option';
			if ($number == $val){
				echo ' selected="selected"';
			}
			echo ' value="', $val , '">', $entry->post_title , '</option>';					
	endforeach;	
?>
			</select>
		</p>
<?php 
	} 
} 



?>