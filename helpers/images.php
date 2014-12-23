<?php
// HELPER: Images

// Removes default thumbnail width/height attributes
add_filter( 'post_thumbnail_html', 'harvest_remove_thumbnail_dimensions', 10 );  
add_filter( 'image_send_to_editor', 'harvest_remove_thumbnail_dimensions', 10 ); 
function harvest_remove_thumbnail_dimensions( $html ) {     
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );   
	return $html; 
} 

// Setup image dimensions
function harvest_thumb_setup(){
	add_theme_support( 'post-thumbnails' );
	
	set_post_thumbnail_size(900, 9999 );
	
	//add_image_size( 'slide-background', 800, 450 );
	
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
function harvest_sermonImageThumb( $postID, $use_default = true ){
	return harvest_sermonImage( $postID, 'ctc-thumb', $use_default );
}

function harvest_sermonImageFull( $postID, $use_default=true ){
	return harvest_sermonImage($postID, 'ctc-post', $use_default);
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
function harvest_sermonImage($postID, $size, $use_default = true ){
	$term = get_the_terms( $postID, 'series' );
	$series =false; $term_id=false;
	if( $term ) {
		$term = array_shift(array_values($term));
	}
	echo harvest_getSermonImage( $postID, $size, $use_default);
	return $term;
}

/**
 * Get image associated with a sermon post. If the post has no image, then 
 * it will look for an image associated with a taxonomy
 *
 * @param integer $postID ID of the post to output the image for. 
 * @param mixed $size Optional. String keyword or 2-item array (width, height) representing the size of the image 
 * @return mixed URL of image associated with a taxonomy term
*/
function harvest_getSermonImage( $postID, $size = 'ctc-post', $use_default = true, $display = false ){
	if( ! 'ctc-sermon' == get_post_type( $postID ) ) return '';
		
	// Check if the post has an image
	if( has_post_thumbnail( $postID ) ) {
		$img_src =  wp_get_attachment_image_src( get_post_thumbnail_id( $postID), $size ); 
		// Image found
		harvest_doImage( $img_src, $display );
	} else {
		// Check by taxonomy
		harvest_getSermonImageByTax(  $postID, $size, $use_default, $display );
	}
}

function harvest_doImage( $img_src, $display = false ){
	if( $display ) 
		echo '<img src="' . $img_src . '" class="ctc-sermon-img">';
	else
		return $img_src;
}

// Find a taxonomy image
function harvest_sermonImageByTax( $postID, $size = 'ctc-post', $use_default = true, $display = false ){
	if( ! 'ctc-sermon' == get_post_type( $postID ) ) return '';
	$post = get_post( $postID );
	$img_src = '';
	// Check taxonomies and terms for an image
	$taxes = get_object_taxonomies( $post, 'objects' );
	foreach( $taxes as $tax ) {
		$terms = get_the_terms( $postID, $tax->name );
		foreach( $terms as $term ){
			if( function_exists( 'ctc_tax_img_url' ) {
				$img_src = ctc_tax_img_url( $term->term_id );				
				break;
			}
		}
	}
	if( empty( $img_src ) )
		$img_src = get_stylesheet_directory() . '/images/default-sermon.png'; 
	
	harvest_doImage( $img_src, $display );
}
	
	
}	
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

