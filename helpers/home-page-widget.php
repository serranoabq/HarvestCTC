<?php 
	class harvest_HomeWidget extends WP_Widget {
		function harvest_HomeWidget() {
			global $pagenow;
			
			$widget_ops = array(
				'classname' => 'front-box-widget', 
				'description' => 'Harvest Home Box' 
			);
			
			$this->WP_Widget('front_box_widget', __('harvest Home Page Box'), $widget_ops);
			
			if ( false || 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {  
					// Now we'll replace the 'Insert into Post Button' inside Thickbox  
					add_filter( 'gettext', array(&$this, 'replace_thickbox_text'), 1, 3 ); 
			}
			
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
		
		public function replace_thickbox_text($translated_text, $text, $domain) { 
			if ('Insert into Post' == $text) { 
					$referer = strpos( wp_get_referer(), $this->theme_safename . '-options' ); 
					if ( $referer != '' ) { 
							return __('Use this image');  
					}  
			}  
			return $translated_text;  
		}
		
		function prep_scripts(){
			echo '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					var _custom_media = true
					var _orig_send_attachment = wp.media.editor.send.attachment;
					$(".remove_button").click(function(e) {
						targetfield = $(this).prev().prev(".upload-url");
						targetfield.val("");
					});
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
			$caption = esc_attr($instance['caption']);
			$image_url = esc_attr($instance['image_url']);
			$link = esc_attr($instance['link']);
			add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

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
				<input class="widefat upload-url" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo $image_url; ?>" /> <input id="image_upload_button" class="upload_button button-secondary" type="button" name="image_upload_button" value="Upload" /><input id="image_remove_button" class="remove_button button-secondary" type="button" name="image_remove_button" value="Remove" /> 
			</p>

	<?php 
		} 
	} 
