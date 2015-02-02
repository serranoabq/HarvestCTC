<?php

/*************************************************************
/ Initial Setup
/************************************************************/
add_action( 'after_setup_theme', 'harvest_setup' );
function harvest_setup(){
	$template_path = trailingslashit( get_template_directory() );
	
	// Load helpers
	include_once( $template_path . 'helpers/class-tgm-plugin-activation.php' );
	include_once( $template_path . 'helpers/display.php');
	include_once( $template_path . 'helpers/images.php');
	include_once( $template_path . 'helpers/feeds.php');
	include_once( $template_path . 'helpers/shortcodes.php');
	include_once( $template_path . 'helpers/sidebars.php');
	include_once( $template_path . 'helpers/widgets.php');
	
	// Apply theme styles to visual editor
	add_editor_style( 'editor-style.css' );
	
	// Register Menus
	register_nav_menus( array(
		'header-menu'     => __( 'Header Menu' , 'harvest' ),
		'footer-menu'     => __( 'Footer Menu' , 'harvest' )
	));
		
	// Create Admin settins page
	if ( is_admin() && file_exists( $template_path . 'admin/class.theme-options.php' ) ) {
		include_once( $template_path . 'admin/class.theme-options.php' );
		include_once( $template_path . 'admin/harvest-options.php' );
		
		// Options and sections are defined in harvest-options.php
		$theme_options = new Theme_Options( $harvest_theme_options, $harvest_theme_sections );
	}

	// Add Church Theme Content support
	harvest_add_ctc();
	
	// Complete theme setup 
	harvest_thumb_setup();
	
	harvest_load_custom_types();
	
	// Translation
	load_theme_textdomain( 'harvest', $template_path . 'lang' );
	
}

// Add Church Theme Content support
function harvest_add_ctc(){
	 
	add_theme_support( 'church-theme-content' );
	
	// Events
	add_theme_support( 'ctc-events', array(
			'taxonomies' => array(),
			'fields' => array(
					'_ctc_event_start_date',
					'_ctc_event_end_date',
					'_ctc_event_start_time',
					'_ctc_event_end_time',
					'_ctc_event_recurrence',
					'_ctc_event_recurrence_end_date',
					'_ctc_event_recurrence_period', // Not default in CTC
					'_ctc_event_venue',
					'_ctc_event_address',
					'_ctc_event_show_directions_link',
			),
			'field_overrides' => array()
	) );
	
	// Sermons
	add_theme_support( 'ctc-sermons', array(
			'taxonomies' => array(
					'ctc_sermon_topic',
					'ctc_sermon_book',
					'ctc_sermon_series',
					'ctc_sermon_speaker',
					'ctc_sermon_tag',
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
			),
			'field_overrides' => array()
	) );

}

// Helper function for theme options
function harvest_option( $option, $default = false ) {
	$theme_data = wp_get_theme();
	$theme_safename = sanitize_title( $theme_data );
	$options = get_option( $theme_safename . '-options' );
	if ( isset( $options[ $option ] ) )
		return $options[ $option ];
	else
		return $default;
}

/*************************************************************
/ Custom Post Types
/************************************************************/
// Load custom post types
function harvest_load_custom_types(){
	$template_path = trailingslashit( get_template_directory() );
	
	require_once( $template_path  . '/inc/widget_post-post-type.php');
	require_once( $template_path  . '/inc/contact_widget.php');
	//require_once( $template_path  . '/includes/ministry-post-type.php');
	
}

/************************************************************
/ Require optional & required plugins
/***********************************************************/
add_action( 'tgmpa_register', 'harvest_register_required_plugins' );
function harvest_register_required_plugins() {
	$plugins = array(
		
		array(
			'name' => 'Church Theme Content',
			'slug' => 'church-theme-content',
			'required' => true
		),
		/*
		array(
			'name' => 'S8 Simple Taxonomy Images',
			'slug' => 's8-simple-taxonomy-images',
			'required' => true
		),
		*/
		
		array(
			'name' => 'Meteor Slides',
			'slug' => 'meteor-slides',
			'required' => false
		),
		
		array(
			'name' => 'CTC Full Calendar',
			'slug' => 'ctc-full-calendar',
			'required' => false,
			'source' => 'https://github.com/serranoabq/ctc-full-calendar/archive/master.zip',
			'external_url' => 'https://github.com/serranoabq/ctc-full-calendar',
		),
		
		array(
			'name' => 'CTC Ministries',
			'slug' => 'ctc-ministries',
			'required' => false,
			'source' => 'https://github.com/serranoabq/ctc-ministries/archive/master.zip',
			'external_url' => 'https://github.com/serranoabq/ctc-ministries',
		),
		
		array(
			'name' => 'CTC Shortcodes',
			'slug' => 'ctc-shortcodes',
			'required' => false,
			'source' => 'https://github.com/serranoabq/ctc-shortcodes/archive/master.zip',
			'external_url' => 'https://github.com/serranoabq/ctc-shortcodes',
		),
		
		
	);
	$config = array();
	tgmpa( $plugins, $config );
}

/*************************************************************
/ Script and Styles
/************************************************************/
add_action( 'wp_enqueue_scripts', 'harvest_scripts_styles' );
function harvest_scripts_styles(){
	// sytle.css 	= main theme style
	// custom.css = additional custom styles
	wp_enqueue_style('harvest-grid', get_stylesheet_directory_uri() . '/css/unsemantic-grid-responsive.css', array(), null );
	wp_enqueue_style('harvest-stylesheet', get_stylesheet_uri(), array(), null );
	
	// Add font awesome support
	wp_enqueue_style('font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array(), null );
	wp_enqueue_style('fonts','http://fonts.googleapis.com/css?family=Bitter:400,700|Lato:400,700', array(), null );

	// Registered, but not enqueued
	wp_register_script( 'responsive-tabs-js',get_stylesheet_directory_uri() . '/js/jquery.responsiveTabs.min.js', array('jquery') );
	//wp_register_style( 'weekly_cal' , get_stylesheet_directory_uri() . '/css/cal_widget.css' );
		
	
	// Handle deregistering plugin scripts
	harvest_deregister_scripts();

	// Load custom styles and scripts
	//-------------------------------
	// Custom styles loaded AFTER all other styles to allow overriding
	wp_enqueue_style('harvest-custom-stylesheet', get_stylesheet_directory_uri() . '/custom/custom.css', array(), null );
	
	// Additional color stylesheet
	if ( harvest_option( 'custom_style') ){
		wp_enqueue_style('harvest-color-stylesheet', harvest_option( 'custom_style' ), array(), null );
	}

	// Custom scripts with jQuery support
	wp_enqueue_script('harvest-custom-scripts', get_stylesheet_directory_uri() . '/custom/custom.js', array('jquery'), null);
	
}

// Handle scripts that we dont want loaded all the time
function harvest_deregister_scripts() {
	global $post;
	
	if( is_a( $post, 'WP_Post' ) ) {
		// Meteor Slides
		if ( ! has_shortcode( $post->post_content, 'meteor-slides' ) ){
			wp_deregister_style( 'meteor-slides' );
			wp_deregister_script( 'jquery-cycle' );
			wp_deregister_script( 'jquery-touchwipe' );
			wp_deregister_script( 'jquery-metadata' );
			wp_deregister_script( 'meteorslides-script' );
		}
		
		// Contact Form 7
		if ( ! has_shortcode( $post->post_content, 'contact-form-7' ) ){
			wp_deregister_script( 'contact-form-7' );
			wp_deregister_style( 'contact-form-7' );
		}
		
	}

}

function harvest_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) return $title;

	// Add the site name.
	$name .= get_bloginfo();
 
	// Add the site description for the home/front page.
	$tagline = get_bloginfo( 'description', 'display' );
	
	if ( is_page() || is_single() ) 
		$tagline = $title;
		
	// Taxonomies
	if ( is_tax('ctc_sermon_series') )
		$tagline = _x( 'Sermon Series: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	if ( is_tax('ctc_sermon_book') )
		$tagline = _x( 'Sermon Book: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	if ( is_tax('ctc_sermon_speaker') ) 
		$tagline = _x( 'Sermon Speaker: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	if ( is_tax('ctc_sermon_topic') ) 
		$tagline = _x( 'Sermon Topic: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	
	// Archives 
	if ( is_search() ) 
		$tagline = __( 'Search Results', 'harvest' );
	if ( is_author() ) 
		$tagline = __( 'Author Archive', 'harvest' ); 
	if ( is_category() ) 
		$tagline = __( 'Archive', 'harvest') . ':' . single_cat_title('', false); 
	if ( is_month() ) 
		$tagline =  __( 'Archive', 'harvest' ). '|' . get_the_time('F');

	$title = $name;
	if ( $tagline ) $title = rtrim( "$name $sep $tagline", ' | ');
	
	return $title;
	
} 
add_filter( 'wp_title', 'harvest_wp_title', 10, 2 );

function harvest_series_title( $title, $post_id ) {
	$series = get_the_terms( $post_id, 'ctc_sermon_series' );	
	if( $series && ! is_wp_error( $series) ) {
		$series = array_shift( array_values ( $series ) );
		$ser_series = $series -> name;
	} else {
		$ser_series = '';
	}
	if( $ser_series )
		$title = "$ser_series :: $title";
		
	return $title;
}
add_filter( 'the_title', 'harvest_series_title', 10, 2);