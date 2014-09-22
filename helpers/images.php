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
		set_post_thumbnail_size(958, 9999 );
		
		add_image_size( 'slide-background', 600, 300 );
		
		add_image_size( 'media-small', 85, 55 );
		add_image_size( 'media-medium', 180, 130 );
		add_image_size( 'media-large', 980, 9999 );
		add_image_size( 'event-small', 85, 55 );
		add_image_size( 'event-medium', 150, 100 );
		add_image_size( 'event-large', 450, 300 );
		
		add_image_size( 'cpt-thumb', 150, 150, true );
		
		add_image_size( 'ministry-thumb', 200, 200, true );
		add_image_size( 'ministry-full', 200, 200 );
	}

?>