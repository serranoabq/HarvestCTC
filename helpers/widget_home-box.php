<?php

class harvest_HomeBox extends WP_Widget {
	function __construct() {
		global $pagenow;
		
		$widget_ops = array(
			'classname' 	=> 'front-box-widget', 
			'description' => 'Harvest Home Box' 
		);
		parent::__construct( 'front_box_widget', __('Harvest Home Page Box', 'harvest'), $widget_ops );
	
	}
	
	function widget( $args, $instance ) {
		
		extract( $args );
		$caption = esc_attr($instance['caption']);
		$image_url = esc_attr($instance['image_url']);
		$link = esc_attr($instance['link']);
		
		echo $before_widget; ?>
<?php if ($link): ?>
			<a href="<?php echo $link; ?>">
<?php endif; ?>
				<div class="box">
					<div class="box-title">
						<?php echo $caption; ?>
					</div>
					<img src="<?php echo $image_url; ?>" class="box-img" />
				</div>
<?php if ($link): ?>
			</a>
<?php endif; ?>	
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
		wp_enqueue_media();
		$this->prep_scripts();
?>
    <p>
			<label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e('Caption', 'harvest'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo $caption; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link URL', 'harvest'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image', 'harvest'); ?></label> 
			<input class="widefat upload-url" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo $image_url; ?>" /> <input id="image_upload_button" class="upload_button button-secondary" type="button" name="image_upload_button" value="Upload" /> 
		</p>

<?php 
	} 
} 


