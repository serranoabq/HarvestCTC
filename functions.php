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
	//include_once( get_template_directory() . '/kirki/kirki.php' );
	
	// Apply theme styles to visual editor
	harvest_editor_styles();
	
	// Register Menus
	register_nav_menus( array(
		'header-menu'     => __( 'Header Menu' , 'harvest' ),
		'footer-menu'     => __( 'Footer Menu' , 'harvest' )
	));
		
		
	//add_filter( 'kirki/config', 'kirki_demo_configuration_sample' );
	
	// Create Admin settins page
	if ( is_admin() && file_exists( $template_path . '/admin/class.theme-options.php' ) ) {
		include_once( get_template_directory() . '/admin/class.theme-options.php' );
		include_once( get_template_directory() . '/admin/harvest-options.php' );
		
		// Options and sections are defined in harvest-options.php
		$theme_options = new Theme_Options( $harvest_theme_options, $harvest_theme_sections );
	}

	// Add Church Theme Content support
	harvest_add_ctc();

	//add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	
	// Complete theme setup 
	harvest_thumb_setup();
	
	// Translation
	load_theme_textdomain( 'harvest', get_template_directory() . '/lang' );
	
	if( is_user_logged_in() ){
		if( function_exists( 'ctcex_update_recurring_events' ) ) 
			ctcex_update_recurring_events();
	} 

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
		
		array(
			'name' => 'CTC Extender',
			'slug' => 'ctc-extender',
			'required' => true,
			'source' => 'https://github.com/serranoabq/ctc-extender/releases/download/v1.0.1/ctc-extender.zip',
			'external_url' => 'https://github.com/serranoabq/ctc-extender',
		),
		
	);
	$config = array();
	tgmpa( $plugins, $config );
}

/*************************************************************
/ Script and Styles
/************************************************************/
function harvest_editor_styles(){
	add_editor_style( str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Lato:400,700' ) );
	add_editor_style( str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Bitter:400,700' ) );
	
	add_editor_style( 'editor-style.css' );	
}

add_action( 'wp_enqueue_scripts', 'harvest_scripts_styles' );
function harvest_scripts_styles(){
	// sytle.css 	= main theme style
	// custom.css = additional custom styles
	wp_enqueue_style( 'harvest-grid', get_stylesheet_directory_uri() . '/css/unsemantic-grid-responsive.css' );
	wp_enqueue_style( 'harvest-stylesheet', get_stylesheet_uri() );
	
	// Add font awesome support
	wp_enqueue_style( 'font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), null );
	wp_enqueue_style( 'font-Bitter','//fonts.googleapis.com/css?family=Bitter:400,700', array(), null );
	wp_enqueue_style( 'font-Lato','//fonts.googleapis.com/css?family=Lato:400,700', array(), null );
	
	// Registered, but not enqueued
	wp_register_script( 'responsive-tabs-js', get_stylesheet_directory_uri() . '/js/jquery.responsiveTabs.min.js', array( 'jquery' ) );
	//wp_enqueue_script( 'color-thief', get_stylesheet_directory_uri() . '/js/color-thief.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'harvest-js', get_stylesheet_directory_uri() . '/js/harvest.js', array( 'jquery' ) );
	
	// Handle deregistering plugin scripts
	harvest_deregister_scripts();

	// Load custom styles and scripts
	//-------------------------------
	// Custom styles loaded AFTER all other styles to allow overriding
	wp_enqueue_style( 'harvest-custom-stylesheet', get_stylesheet_directory_uri() . '/custom/custom.css', array(), null );
	
	// Additional color stylesheet
	if ( harvest_option( 'custom_style') ){
		wp_enqueue_style( 'harvest-color-stylesheet', harvest_option( 'custom_style' ), array(), null );
	}

	// Custom scripts with jQuery support
	wp_enqueue_script( 'harvest-custom-scripts', get_stylesheet_directory_uri() . '/custom/custom.js', array( 'jquery' ), null );
	
	// Override styles
	harvest_style_override();
	
}

// Override styles based on theme options
function harvest_style_override(){
	$primary =  harvest_option( 'accent', '#006f7c' );
	$secondary = harvest_option( 'secondary_accent', '#b4b2b1' );
	$logo_css = harvest_option( 'logo_name_css', '');
	$style = ".logo_name { $logo_css }";
	wp_add_inline_style( 'harvest-stylesheet', $style );	
	
	$style = ".accent-background, 
		.r-tabs .r-tabs-state-active {
			background: $primary;
		}
		.secondary-accent-background,
		.recent-sermon h2, 
		.weekly-calendar h2 {
			background: $secondary;
		}
		.r-tabs a,
		.r-tabs .r-tabs-anchor {
			color: $primary;
		}
		.r-tabs .r-tabs-tab {
			border-bottom-color: $secondary;
			border-top-color: $secondary;
		}
		.r-tabs .r-tabs-accordion-title{
			border-bottom-color: $secondary;
		}
		.content a:not(.button),
		.content a:hover {
			color: $primary;
		}
		.content a.button:hover {
			color: $primary;
			border-color: $primary;
		}";
		wp_add_inline_style( 'harvest-stylesheet', $style );	
}

// Handle scripts that we dont want loaded all the time
function harvest_deregister_scripts() {
	global $wp_query, $post;
	
	if( ! ( strpos( json_encode( $wp_query ), '[contact-form-7' ) || strpos( json_encode( $post ), '[contact-form-7' ) ) )  {
			wp_deregister_script( 'contact-form-7' );
			wp_deregister_style( 'contact-form-7' );
	}

}

// Helper function for theme options
function harvest_option( $option, $default = false ) {
	if( class_exists( 'CTC_Extender' )  && ctcex_has_option( $option ) )
		return ctcex_get_option( $option, $default );
	
	$theme_data = wp_get_theme();
	$theme_safename = sanitize_title( $theme_data );
	$options = get_option( $theme_safename . '-options' );
	if( isset( $options[ $option ] ) ) 
		return $options[ $option ];
	else
		return $default;
}

function harvest_wp_title( $title, $sep ) {
	
	global $paged, $page;
	global $wp_filter;

	// Add the site name.
	$name = get_bloginfo( 'name' );
	if ( is_feed() ) return $name;
 
	// Add the site description for the home/front page.
	$tagline = '';
	if ( ( is_page() || is_single() ) && !is_front_page() ) 
		$tagline = $title;

	// Taxonomies
	if ( is_tax( 'ctc_sermon_series') )
		$tagline = sprintf( '%s: %s',
			harvest_option( 'ctc-sermon-series', _x( 'Series', 'Page title', 'harvest' ) ),
			single_tag_title( '', false ) ); 
	if ( is_tax( 'ctc_sermon_speaker') ) 
		$tagline = sprintf( _x( 'Speaker: %s', 'Page title', 'harvest' ), single_tag_title( '', false ) ); 
	if ( is_tax( 'ctc_event_category') ) 
		$tagline = sprintf( _x( '%s Events', 'Page title', 'harvest' ), single_tag_title( '', false ) ); 
	if ( is_tax( 'ctc_sermon_topic') ) {
		$tagline = sprintf( '%s: %s',
			harvest_option( 'ctc-sermon-topic', _x( 'Topic', 'Page title', 'harvest' ) ),
			single_tag_title( '', false ) ); 
			
	}
	// Archives 
	if ( is_search() ) 
		$tagline = __( 'Search Results', 'harvest' );
	if ( is_author() ) 
		$tagline = __( 'Author Archive', 'harvest' ); 
	if ( is_category() ) 
		$tagline = sprintf( __( 'Archive: %s', 'harvest'), single_cat_title('', false) ); 
	if ( is_month() ) 
		$tagline =  sprintf( __( 'Archive | %s', 'harvest' ), get_the_time('F') ); 
	if ( is_post_type_archive( 'ctc_location' ) ) {
		$tagline =  harvest_option ( 'ctc-locations', __( 'Locations', 'harvest') );
	}
	if ( is_post_type_archive( 'ctc_sermon' ) ) {
		$tagline =  harvest_option( 'ctc-sermons' , __( 'Sermons', 'harvest' ) );
	}
	if ( is_post_type_archive( 'ctc_event' ) ) {
		$tagline =  harvest_option ( 'ctc-events' , __( 'Events', 'harvest' ) );
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
//add_filter( 'the_title', 'harvest_series_title', 10, 2);

function harvest_debug( $log ) {
	add_action( 'admin_notices', function( $log ){
		echo '<div class="error"><p><code>'. $log .'</code></p></div>';
	} );
}

function harvest_sermon_save_audio_enclosure( $post_id, $post ) {

	// Stop if no post, auto-save (meta not submitted) or user lacks permission
	$post_type = get_post_type_object( $post->post_type );
	if ( empty( $_POST ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return false;
	}

	// Stop if PowerPress plugin is active
	// Solves conflict regarding enclosure field: http://wordpress.org/support/topic/breaks-blubrry-powerpress-plugin?replies=6
	if ( defined( 'POWERPRESS_VERSION' ) ) {
		return false;
	}

	// Get audio URL
	$audio = get_post_meta( $post_id , '_ctc_sermon_audio' , true );

	// The built-in do_enclose method goes a roundabout way of getting the file 
	// length, which involves an http fetch to get the right length on some server 
	// configurations if the fetch fails the enclosure isn't added. 
	// This isn't a big deal if the file is remote, but it's frustrating if the file 
	// is on the same server, where WP can get all the information without the http fetch. 
	// This method now does the handling of the enclosure data using WP methods if the 
	// file is local and then falls back to the normal do_enclose method if it's 
	// a remote file
	
	// Populate enclosure field with URL, length and format, if valid URL found
	$uploads = wp_upload_dir();
	$is_local = stripos( $audio, $uploads[ 'baseurl' ] ); // check if the link is to a local file
	if( ! ( false === $is_local)  ) {
		// Get the path to the file
		$audio_src = str_replace( $uploads['baseurl'], $uploads['basedir'], $audio );
		// Get meta data
		$metadata =  wp_read_audio_metadata( $audio_src );
		if( $metadata ){
			// Make sure we got metadata and read the mime_type 
			// and filesize values which are needed for the enclosure
			$mime = $metadata[ 'mime_type' ];
			$length = $metadata[ 'filesize' ];
			if( $mime ) {
				// We've got data, add enclosure meta
				update_post_meta( $post_id, 'enclosure', "$audio\n$length\n$mime\n" );
			}
		}
	} else {
		// Leave do_enclose for remote files
		do_enclose( $audio, $post_id ); 
	}
}
remove_action( 'save_post', 'ctc_sermon_save_audio_enclosure', 11 ); // Replace the built in CTC enclosure function which is failing on my server
add_action( 'save_post', 'harvest_sermon_save_audio_enclosure', 11, 2 ); // after 'save_post' saves meta fields on 10
