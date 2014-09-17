<?php
add_action( 'widgets_init', 'harvest_registerContactWidget' );
function harvest_registerContactWidget() {
	register_widget( 'harvest_Contact_Widget' );
}
class harvest_Contact_Widget extends WP_Widget {
	function harvest_Contact_Widget() {
		$widget_ops = array(
			'classname' => 'widget_contact_info', 
			'description' => __( 'Contact Info') 
		);
		$this->WP_Widget('widget_contact_info', __('Contact Info'), $widget_ops);
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Contact Info' ) : $instance['title'], $instance, $this->id_base);
		
		$address = harvest_option( 'address' );
		$phone = harvest_option( 'phone' );
		$addl_info = harvest_option( 'addl_info' );
		
		echo $before_widget;
		if ( $title)
			echo $before_title . $title . $after_title;
			
		if($address){
?>
			<div id="contact_map">
				<a href="http://maps.google.com/maps?q=<?php echo urlencode($address); ?>" target="_blank">
					<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&zoom=15&size=300x200&sensor=false&scale=2&markers=color:red|<?php echo urlencode($address); ?>" />
				</a>
			</div>
			<div id="contact_address">
				<strong>Address:</strong>
				<div class="contact_info">
					<?php echo wpautop($address); ?>
				</div>
			</div>
<?php } ?>
<?php if($phone){ ?>
			<div id="contact_phone">
				<strong>Phone: </strong>
				<span class="contact_info"><?php echo wpautop($phone); ?></span>
			</div>
<?php } ?>	
<?php if($addl_info){ ?>	
			<div class="contact_addl_info">
				<?php echo wpautop($addl_info); ?>
			</div>
<?php } 

		echo $after_widget;
		
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