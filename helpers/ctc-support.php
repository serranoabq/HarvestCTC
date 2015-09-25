<?php 
// Add Church Theme Content support

function harvest_ctc_notice(){
	echo '<div class="error"><p><a href="http://localhost/wp-admin/plugin-install.php?tab=plugin-information&plugin=church-theme-content&TB_iframe=true&width=600&height=550">'. __( 'Church Theme Content Plugin is required!', 'harvest' ).'</a></p></div>';
}
function harvest_ctcex_notice(){
	echo '<div class="error"><p>'. __( 'CTC_Extender Plugin is required!', 'harvest' ).'</p></div>';
}
	
function harvest_add_ctc(){
	 
	if( ! class_exists( 'Church_Theme_Content' ) ) {
		add_action( 'admin_notices', 'harvest_ctc_notice' );
		return;
	}
	if( ! class_exists( 'CTC_Extender' ) ) {
		add_action( 'admin_notices', 'harvest_ctcex_notice' );
	}
	
	add_theme_support( 'church-theme-content' );
	
	// Events
	add_theme_support( 'ctc-events', array(
			'taxonomies' => array(
				'ctc_event_category',
			),
			'fields' => array(
				'_ctc_event_start_date',
				'_ctc_event_end_date',
				'_ctc_event_start_time',
				'_ctc_event_end_time',
				'_ctc_event_recurrence',
				'_ctc_event_recurrence_end_date',
				'_ctc_event_recurrence_period',       // Not default in CTC
				'_ctc_event_recurrence_monthly_type', // Not default in CTC
				'_ctc_event_recurrence_monthly_week', // Not default in CTC 
				'_ctc_event_venue',
				'_ctc_event_address',
			),
			'field_overrides' => array()
	) );
	
	// Sermons
	add_theme_support( 'ctc-sermons', array(
			'taxonomies' => array(
					'ctc_sermon_topic',
					'ctc_sermon_series',
					'ctc_sermon_speaker',
			),
			'fields' => array(
					'_ctc_sermon_video',
					'_ctc_sermon_audio',
			),
			'field_overrides' => array()
	) );
	 
	// People
	add_theme_support( 'ctc-people', array(
			'taxonomies' => array(
					'ctc_person_group',
			),
			'fields' => array(
					'_ctc_person_position',
					'_ctc_person_phone',
					'_ctc_person_email',
					'_ctc_person_urls',
					'_ctc_person_gender',	// Not default in CTC
			),
			'field_overrides' => array()
	) );

	// Locations
	add_theme_support( 'ctc-locations', array(
			'taxonomies' => array(),
			'fields' => array(
					'_ctc_location_address',
					'_ctc_location_phone',
					'_ctc_location_times',
					'_ctc_location_slider',
					'_ctc_location_pastor',
			),
			'field_overrides' => array()
	) );
	
}

// Add default image into the sermon
add_filter( 'ctc_sermon_image', 'harvest_sermon_image' );
add_filter( 'ctc_event_image', 'harvest_sermon_image' );
function harvest_sermon_image( $img ){
	if( empty( $img ) )
		$img = harvest_option( 'feed_logo', '' );
	
	// Fall back to the site logo
	if( empty( $img ) )
		$img = harvest_option( 'logo', '' );
	
	return $img; 
	
}

// Add a default gender-specific person image defined by the theme
add_filter( 'ctc_person_image', 'harvest_person_image', 10, 2 );
function harvest_person_image( $img, $gender ){
	// Check if the gender meta is available (added by CTC Extender)
	if( $gender ){
		// Allow a gender-specific default image
		if( file_exists( get_stylesheet_directory() . '/images/user_' . strtolower( $gender ) . '.png' ) )
			$img = get_stylesheet_directory_uri() . '/images/user_' . strtolower( $gender ) . '.png';
	} elseif( file_exists( get_stylesheet_directory() . '/images/user.png' ) ) {
		// Get the default user image
		$img = get_stylesheet_directory_uri() . '/images/user.png';
	}
	
	return $img; 
}

// This helper is used to get an expression for recurrence
function harvest_get_recurrence_note( $post_obj ) {
	if( class_exists( 'CTC_Extender' ) )
		return ctcex_get_recurrence_note ( $post_obj );
	else
		return '';
}

function harvest_get_default_data( $post_id ) {
	$data = array(
		'permalink'   => get_permalink( $post_id ),
		'name'        => get_the_title( $post_id ),
	);
	return $data;
}

// Get sermon data for use in templates
function harvest_get_sermon_data( $post_id ){
	$default_img = harvest_option( 'feed_logo', '');
	if( empty( $default_img ) ) $default_img = harvest_option( 'logo', '' );
	if( class_exists( 'CTC_Extender' ) )		
		return ctcex_get_sermon_data( $post_id, $default_img );
	else
		return harvest_get_default_data( $post_id ); 
}

// Get event data for use in templates
function harvest_get_event_data( $post_id ){
	if( class_exists( 'CTC_Extender' ) )
		return ctcex_get_event_data( $post_id );
	else
		return harvest_get_default_data( $post_id ); 
}

// Get location data for use in templates
function harvest_get_location_data( $post_id ){
	if( class_exists( 'CTC_Extender' ) )
		return ctcex_get_location_data( $post_id );
	else
		return harvest_get_default_data( $post_id ); 
}

// Get person data for use in templates
function harvest_get_person_data( $post_id ){
	if( class_exists( 'CTC_Extender' ) )
		return ctcex_get_person_data( $post_id );
	else
		return harvest_get_default_data( $post_id ); 
}

add_action( 'admin_init', 'harvest_metabox_location_slider' , 11);
add_action( 'admin_enqueue_scripts', 'harvest_metabox_location_slider', 11 );
function harvest_metabox_location_slider() {
	$meta_box = array(

		// Meta Box
		'id'        => 'ctc_location_slider', // unique ID
		'title'     => __( 'Slider ', 'harvest' ),
		'post_type' => 'ctc_location',
		'context'   => 'side', 
		'priority'  => 'low', 

		// Fields
		'fields' => array(
			'_ctc_location_slider' => array(
				'name'       => __( 'Location slider', 'harvest' ),
				'desc'       => __( 'Enter the shortcode for the slider to use instead of the image (e.g., <code>[metaslider id=1]</code>).', 'harvest' ), 
				'type'       => 'text', 
				'default'    => '', 
				'no_empty'   => false, 
				'class'      => 'ctmb-medium', // class(es) to add to input (try ctmb-medium, ctmb-small, ctmb-tiny)
				'field_class'   => '', // class(es) to add to field container
			),
		),
	);
	
	// Add Meta Box
	if( class_exists( 'CT_Meta_Box' ) )
		new CT_Meta_Box( $meta_box );
}

