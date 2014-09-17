<?php
	// HELPER: Shortcodes
	
// Insert a YouTube video with responsive design
// USE: [ytvideo id="videoID"]
add_shortcode('ytvideo', 'harvest_ytvideo_shortcode');  
function harvest_ytvideo_shortcode($attr) {

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('ytvideo', '', $attr);
	if ( $output != '' ) return $output;

	extract(shortcode_atts(array(
		'id'         => null,
		'before'    => '',
		'after'     => ''
		), $attr));

	if(!empty($id)){
		$result = '<style>
			.video-container {
				position: relative;
				padding-bottom: 56.25%;
				padding-top: 30px; 
				height: 0; 
				overflow: hidden;
			}
			.video-container iframe,
			.video-container object,
			.video-container embed {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}
		</style>
		<div class="video-container">
			<iframe
			width="440" height="248" src="http://www.youtube.com/embed/'.$id.'?rel=0" 
			frameborder="0" allowfullscreen=""></iframe>
		</div>
		<p>&nbsp;</p>';
			//$result = wpautop( $result, $br = 1 ); 
		return $before . $result . $after;
	} else
		return;
	
}

// Insert another page into a post using 
// USE: [post_insert name="PAGE/POST-NAME"] or [post_insert id="PAGE/POST-ID"]
add_shortcode('post_insert', 'harvest_insert_shortcode');  
function harvest_insert_shortcode($attr) {

	$output = apply_filters( 'post_insert', '', $attr );
	if ( $output != '' ) return $output;

	extract( shortcode_atts( array(
		'name'      => null,
		'id'         => null,
		'before'    => '',
		'after'     => '',
		'noautop'     => null,
		), $attr));

	$id = intval( $id );

	global $wpdb;
	if( empty( $name ) )
		$query = "SELECT post_content FROM $wpdb->posts WHERE id = $id";
	else
		$query = "SELECT post_content FROM $wpdb->posts WHERE post_name = '$name'";   

	$result = $wpdb->get_var($query);

	if( ! empty( $result ) ){
		if( ! isset( $noautop ) ) 
			$result = wpautop( $result, $br = 1 ); 
		return $before . $result . $after;
	} else
		return;
}
?>