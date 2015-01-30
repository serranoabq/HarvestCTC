<?php
	// HELPER: Images
	
	// Removes default thumbnail width/height attributes
	add_filter( 'post_thumbnail_html', 'harvest_remove_thumbnail_dimensions', 10 );  
	add_filter( 'image_send_to_editor', 'harvest_remove_thumbnail_dimensions', 10 ); 
	function harvest_remove_thumbnail_dimensions( $html ) {     
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );   
		return $html; 
	} 
	
	// Setup image dimensions
	function harvest_thumb_setup(){
		add_theme_support( 'post-thumbnails' );
		
		set_post_thumbnail_size(900, 9999 );
		
		add_image_size( 'slide-background', 800, 450 );
		
		//add_image_size( 'widget-thumb', 85, 85 );
		
		// Thumbnail for CTC (events, sermons, series?)
		add_image_size( 'ctc-thumb', 75, 75, true );
		
		// Featured image size for CTC posts
		add_image_size( 'ctc-post', 300 );
		
		
		// Recommend a thumbnail flush after installing theme to regenerate
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

?>