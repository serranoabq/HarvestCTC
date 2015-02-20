<?php

/*************************************************************
/ Initial Setup
/************************************************************/
add_action( 'after_setup_theme', 'harvest_setup' );
function harvest_setup(){
	$template_path = trailingslashit( get_template_directory() );
	
	// Load helpers
	include_once( get_template_directory() . '/helpers/class-tgm-plugin-activation.php' );
	include_once( get_template_directory() . '/helpers/ctc-support.php' );
	include_once( get_template_directory() . '/helpers/display.php');
	include_once( get_template_directory() . '/helpers/images.php');
	include_once( get_template_directory() . '/helpers/feeds.php');
	include_once( get_template_directory() . '/helpers/sidebars.php');
	include_once( get_template_directory() . '/helpers/widgets.php');
	
	// Apply theme styles to visual editor
	add_editor_style( 'editor-style.css' );
	
	// Register Menus
	register_nav_menus( array(
		'header-menu'     => __( 'Header Menu' , 'harvest' ),
		'footer-menu'     => __( 'Footer Menu' , 'harvest' )
	));
		
	// Create Admin settins page
	if ( is_admin() && file_exists( $template_path . '/admin/class.theme-options.php' ) ) {
		include_once( get_template_directory() . '/admin/class.theme-options.php' );
		include_once( get_template_directory() . '/admin/harvest-options.php' );
		
		// Options and sections are defined in harvest-options.php
		$theme_options = new Theme_Options( $harvest_theme_options, $harvest_theme_sections );
	}

	// Add Church Theme Content support
	harvest_add_ctc();
	
	// Complete theme setup 
	harvest_thumb_setup();
	
	//harvest_load_custom_types();
	
	// Translation
	load_theme_textdomain( 'harvest', get_template_directory() . '/lang' );
	
}


/*************************************************************
/ Custom Post Types
/************************************************************/
// Load custom post types
function harvest_load_custom_types(){
	//$template_path = trailingslashit( get_template_directory() );
	
	//require_once( get_template_directory()  . '/inc/widget_post-post-type.php');
	//require_once( get_template_directory()  . '/inc/contact_widget.php');
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
	wp_enqueue_script( 'harvest-js', get_stylesheet_directory_uri() . '/js/harvest.js', array( 'jquery' ) );
	
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

function harvest_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) return $title;

	// Add the site name.
	$name = get_bloginfo( 'name' );
 
	// Add the site description for the home/front page.
	//$tagline = get_bloginfo( 'description', 'display' );
	$tagline = '';
	if ( is_page() || is_single() ) 
		$tagline = $title;
		
	// Taxonomies
	if ( is_tax( 'ctc_sermon_series') )
		$tagline = _x( 'Series: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	if ( is_tax( 'ctc_sermon_book') )
		$tagline = _x( 'Book: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	if ( is_tax( 'ctc_sermon_speaker') ) 
		$tagline = _x( 'Speaker: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	if ( is_tax( 'ctc_sermon_topic') ) 
		$tagline = _x( 'Topic: ', 'Page title', 'harvest' ) . single_tag_title( '', false ); 
	
	// Archives 
	if ( is_search() ) 
		$tagline = __( 'Search Results', 'harvest' );
	if ( is_author() ) 
		$tagline = __( 'Author Archive', 'harvest' ); 
	if ( is_category() ) 
		$tagline = __( 'Archive', 'harvest') . ':' . single_cat_title('', false); 
	if ( is_month() ) 
		$tagline =  __( 'Archive', 'harvest' ). '|' . get_the_time('F');
	if ( is_post_type_archive( 'ctc_location' ) ) {
		$tagline =  __( 'Locations', 'harvest' );
		if( harvest_option( 'ctc-location' ) ) 
			$tagline = harvest_option( 'ctc-location' );
	}
	if ( is_post_type_archive( 'ctc_sermon' ) ) {
		$tagline =  __( 'Sermons', 'harvest' );
		if( harvest_option( 'ctc-sermon' ) ) 
			$tagline = harvest_option( 'ctc-sermon' );
	}
	if ( is_post_type_archive( 'ctc_event' ) ) {
		$tagline =  __( 'Events', 'harvest' );
		if( harvest_option( 'ctc-event' ) ) 
			$tagline = harvest_option( 'ctc-event' );
	}
	

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