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
		
		add_image_size( 'ctc-wide', 640, 360 );
		add_image_size( 'ctc-tall', 360, 1138 );
		
		// Recommend a thumbnail flush after installing theme to regenerate
	}
	
	// Get image on post
	function harvest_getImage( $post_id = null, $size = 'large' ){

		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );  
		if( $thumbnail ) $img = $thumbnail[0];
		
		$post_type = get_post_type( $post_id );
		switch( $post_type ){
			case 'ctc_sermon':
			case 'ctc_event':
			case 'ctc_person':
			case 'ctc_location':
				$img = get_post_meta( $post_id, '_ctc_image' , true ); 
		}
		
		// Fall back to the site logo
		if( empty( $img ) )
			$img = harvest_option( 'logo', '' );
		
		return $img;
		
	}

?>