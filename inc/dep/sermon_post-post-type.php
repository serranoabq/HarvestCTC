<?php
/**
 * CUSTOM POST TYPE: Sermon Post 
 * 
 * Custom post type for showing sermons and sermon audio
 * 
 */
?>
<?php

// Create Sermon Post post type
add_action( 'init', 'harvest_createSermonPostType' );
function harvest_createSermonPostType() {
	register_post_type( 'sermon_post',
		array(
			'labels' => array(
				'name' 								=> __( 'Sermons' ),
				'singular_name' 			=> __( 'Sermon' ),
				'add_new' 						=> __( 'Add New Sermon' ),
				'add_new_item' 				=> __( 'Add New Sermon' ),
				'edit' 								=> __( 'Edit Sermon' ),
				'edit_item' 					=> __( 'Edit Sermon' ),
				'new_item' 						=> __( 'New Sermon' ),
				'view' 								=> __( 'View Sermon' ),
				'view_item' 					=> __( 'View Sermon' ),
				'search_items' 				=> __( 'Search Sermons' ),
				'not_found' 					=> __( 'No Sermons found' ),
				'not_found_in_trash' 	=> __( 'No Sermons found in Trash' ),
				'menu_name'						=> __( 'Sermons' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/sermon-icon.png',  // Icon Path
			'supports' 							=> array('title', 'editor', 'thumbnail'),
			'rewrite' 							=> true,
			'taxonomies' 						=> array( 'series'),
			'query_var' 						=> true,
			'exclude_from_search' 	=> false,
			'show_ui' 							=> true,
			'show_in_menu'					=> true,
			'show_in_nav_menus'			=> true,
			'capability_type' 			=> 'post'
		)
	);
	
	register_taxonomy( 'series' , 'sermon_post', 
		array( 
			'hierarchical'	=> false,
			'label' 				=> __( 'Sermon Series' ) 
		) 
	);
}

// Create Metaboxes 
add_action("admin_init", "harvest_add_sermon_metaboxes");
function harvest_add_sermon_metaboxes(){
	add_meta_box("sermon_speaker", "Sermon Speaker", "harvest_sermon_speaker_metabox", "sermon_post", "side", "high");
	add_meta_box("sermon_filelink", "Sermon Audio", "harvest_sermon_link_metabox", "sermon_post", "side", "high");
	add_meta_box("sermon_verse", "Sermon Verse", "harvest_sermon_verse_metabox", "sermon_post", "side", "low");
	
	// Media uploader script
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}


//// Sermon Speaker
function harvest_sermon_speaker_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$sermon_speaker = $custom["sermon_speaker"][0];
?>
		<label>Name of the speaker:</label>
		<input name="sermon_speaker" value="<?php echo $sermon_speaker; ?>" style="width: 95%;" />
<?php
}
// Save Speaker
function harvest_save_sermon_speaker($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 

	update_post_meta($post->ID, "sermon_speaker", $_POST["sermon_speaker"]);
}
add_action('save_post', 'harvest_save_sermon_speaker');
//// END Sermon Speaker

//// Sermon Link
function harvest_sermon_link_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$sermon_filelink = $custom["sermon_filelink"][0];
	
?>
	
	<label>Sermon Audio File:</label>
	<input id="sermon_filelink" name="sermon_filelink" value="<?php echo $sermon_filelink; ?>" class="upload-url" style="width: 180px;" />
	<input id="sermon_filelink-upload_button" class="upload_button button-secondary" type="button" name="sermon_filelink-upload_button-upload_button" value="Upload" />
	<p class="howto">Click <strong>Upload</strong> and upload a sermon audio file (MP3 format preferred). After choosing your file, click <strong>Insert into Post</strong></p>
	<script>
	// Uploader stuff
	jQuery(document).ready(function($) {
			$(".upload_button").click(function() {
         targetfield = $(this).prev(".upload-url");
         tb_show("Upload sermon audio", "media-upload.php?type=audio&TB_iframe=true");
         return false;
			});
			window.send_to_editor = function(html) {
				console.log(html);
        url = $(html).attr("href");
				console.log(url);
        $(targetfield).val(url);
				 tb_remove();
			}
		});
	</script>
<?php
}
// Save Link
function harvest_save_sermon_link($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	// Add enclosure to enable podcasting of sermon
	update_post_meta($post->ID, "enclosure", harvest_make_enclosure($post->ID, $_POST["sermon_filelink"]));
	update_post_meta($post->ID, "sermon_filelink", $_POST["sermon_filelink"]);
}
add_action('save_post', 'harvest_save_sermon_link');
//// END Sermon Link	
	
// Make a podcast-acceptable enclosure for the sermon link
function harvest_make_enclosure($post_id, $file_link){
	// convert link url to a server path
	$uploads 	= wp_upload_dir();
	$real_path 	= $uploads['basedir'];
	$web_path 	= $uploads['baseurl'];
	$file_real_path = str_replace( $web_path, $real_path, $file_link );
	// get the file size 
	$file_size = filesize($file_real_path);
	// put the necessary data into an array and implode it
	$enclosure_array = array($file_link,$file_size,'audio/mpeg');
	$enclosure = implode("\n", $enclosure_array);
	return $enclosure;
}


//// Sermon Verse
function harvest_sermon_verse_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$sermon_verse = $custom["sermon_verse"][0];
?>
		<label>Sermon Verse:</label>
		<input name="sermon_verse" value="<?php echo $sermon_verse; ?>" style="width: 95%;" />
<?php
}
// Save Verse
function harvest_save_sermon_verse($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "sermon_verse", $_POST["sermon_verse"]);
}
add_action('save_post', 'harvest_save_sermon_verse');
//// END Sermon Verse

// Edit columns display
add_action("manage_posts_custom_column",  "harvest_sermon_custom_columns");
add_filter("manage_sermon_post_posts_columns", "harvest_sermon_edit_columns");
function harvest_sermon_edit_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Sermon Title",    
		"verse" => "Sermon Verse",
		"speaker"	=> "Sermon Speaker",
		"date" => "Sermon Date",
	);
 
	return $columns;
}
function harvest_sermon_custom_columns($column){
	global $post;
	switch ($column) {   
		case "verse":
			$custom = get_post_custom();
			echo $custom["sermon_verse"][0];
			break;
		case "speaker":
			$custom = get_post_custom();
			echo $custom["sermon_speaker"][0];
			break;
	}
}


// Pull sermon image for display using either a taxonomy image or 
// the attached image or the theme default image.

// $postID			: ID of sermon post 
// $use_default : (Optional) Show default theme image if post has no image attached.
//							  Default = True
function harvest_sermonImageThumb($postID, $use_default=true){
	return harvest_sermonImage($postID,'thumbnail',$use_default);
}

function harvest_sermonImageFull($postID,$use_default=true){
	return harvest_sermonImage($postID,'post-thumbnail',$use_default);
}

/* Function to display an image with the sermon_post. If the sermon is part of a
 series AND the series has an image associated with it 
 (using Simple Taxonomy Images from S8; http://wordpress.org/plugins/s8-simple-taxonomy-images/)
ARGUMENTS
 @param $postID 					- post ID
 @param $size							-	image size 
 @param true $use_default - Show default theme image if post has no image attached.
 @return mixed
							  	Default = True
*/
function harvest_sermonImage($postID,$size,$use_default=true){
	$term = get_the_terms( $postID, 'series' );
	$series =false; $term_id=false;
	if($term) {
		$term = array_shift(array_values($term));
	}
	echo harvest_getSermonImage($postID,$size,$use_default);
	return $term;
}
function harvest_getSermonImage($postID,$size,$use_default=true){
	$term = get_the_terms( $postID, 'series' );
	$series =false; $term_id=false;
	if($term) {
		$term = array_shift(array_values($term));
		$series = $term->name;
		$term_id = $term->term_taxonomy_id;
	}
	$series_images_avail = $series && function_exists("s8_get_taxonomy_image_src");
	if ( $series_images_avail ) {
		$series_image = s8_get_taxonomy_image_src(get_term($term_id,'series'),$size);
		$series_images_avail = $series_image[src];
	}
	if( $series_images_avail ) {
		return '<img src="' . $series_image[src] .'" class="'. ('thumbnail'==$size?'sermon_widget_img left ':'') .'wp-post-image" />';
	} elseif(has_post_thumbnail($postID)){
		if('thumbnail'==$size)
			return get_the_post_thumbnail($postID, array(75,75), array('class' => 'sermon_widget_img left'));
		else
			return get_the_post_thumbnail($postID);
		
	} elseif($use_default) {  
			return '<img src="' . get_bloginfo('template_directory') . '/images/default-sermon.png" class="' . ('thumbnail'==$size?'sermon_widget_img left ':'') . 'wp-post-image" />';
	}
	return '';
}



// Register Sermon Post widget
add_action( 'widgets_init', 'harvest_registerSermonWidget' );
function harvest_registerSermonWidget() {
	register_widget( 'harvest_Sermon_Widget' );
}

class harvest_Sermon_Widget extends WP_Widget {
	function harvest_Sermon_Widget() {
		$widget_ops = array(
			'classname' => 'widget_recent_sermons', 
			'description' => __( 'Recent Sermons') 
		);
		$this->WP_Widget('widget_recent_sermons', __('Recent Sermons'), $widget_ops);
	}
	function widget( $args, $instance ) {
		// Number of posts to show in widget
		$widget_posts = 3;
		
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Recent Sermons' ) : $instance['title'], $instance, $this->id_base);
		
		$feat_query = array(
			'post_type' => 'sermon_post', 
			'posts_per_page' => $widget_posts, 
			'order' => 'DESC'
		) ; 
		$feat_posts = new WP_Query();
		$feat_posts->query($feat_query); $i=1;
		if ($feat_posts->have_posts()) { 
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
			
			echo '<ul>';
			while ($feat_posts->have_posts()) : $feat_posts->the_post();
				$wd = get_the_time('D, M jS', get_the_ID());
				echo '<li>'; $i++;
				if(has_post_thumbnail(get_the_ID())){
					echo get_the_post_thumbnail(get_the_ID(),array(75,75), array('class' => 'sermon_widget_img left'));
				} else {  ?>
					<img src="<?php bloginfo('template_directory'); ?>/images/default-sermon.png" class="sermon_widget_img left wp-post-image" />
				<?php  }
				echo '<div class="sermon_info">';
				echo '<h4><a href="'. get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a></h4>';
				echo '<div class="sermon_date">'. $wd .'</div>';
				echo '</div></li>';
			
			endwhile; wp_reset_query(); 
			echo '</ul>';
			$after_widget .= '<div class="biglink">';
			$after_widget .= '<a href="'.get_bloginfo('home').'/media/" title="browse the sermon archive" class="green">';
			$after_widget .= '<img src="'.get_bloginfo('template_directory').'/images/ico_headphone.png" alt="Listen" />';		
			$after_widget .= '<span class="small">browse the</span><strong>sermon archive</strong></a></div>';
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