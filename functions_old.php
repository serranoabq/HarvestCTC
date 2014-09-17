<?php
/* 
JRS: Potential imprevements for this theme:
1. Consolidate some of the functions into a single file. Right now there is this file, 
   a functions folder with more files and an include folder with more files
2. At the same time, this file has stuff that should probably be in its own file (i.e. custom post types)
3. The Admin interface and related functions need to be cleaned up to make sense of what's not being used
4. 'churchthemes' is used arbitrarily here. Given the number of changes, it might make sense to change that to something else
5. There are things in the file structure that are not necessary (i.e. ads) or can be cleaned up
*/
// Start  Functions
$functions_path = TEMPLATEPATH . '/functions/';
$includes_path = TEMPLATEPATH . '/includes/';

// Apply theme styles to visual editor
add_editor_style('editor-style.css');

// Options panel variables and functions
require_once ($functions_path . 'admin-setup.php');

// Custom functions and plugins
require_once ($functions_path . 'admin-functions.php');

// Thumbnails
add_theme_support('post-thumbnails');
set_post_thumbnail_size(958, 9999);
add_image_size('slide-background', 600, 300);
add_image_size('media-small', 85, 55, true);
add_image_size('media-medium', 180, 130, true);
add_image_size('media-large', 980, 9999);
add_image_size('event-small', 85, 55, true);
add_image_size('event-medium', 150, 100, true);
add_image_size('event-large', 450, 300, true);
add_image_size('staff-thumb', 300, 300, true);

// allow special tags in editor
function fb_change_mce_options($initArray) {
	$ext = 'pre[*],iframe[*],script[*],style';
	if ( isset( $initArray['extended_valid_elements'] ) ) {
		$initArray['extended_valid_elements'] .= ',' . $ext;
	} else {
		$initArray['extended_valid_elements'] = $ext;
	}
	return $initArray;
}
add_filter('tiny_mce_before_init', 'fb_change_mce_options');

// Custom fields 
// require_once ($functions_path . 'admin-custom.php');

// More churchThemes Page
require_once ($functions_path . 'admin-theme-page.php');

// Admin Interface!
require_once ($functions_path . 'admin-interface.php');

// Options panel settings
require_once ($includes_path . 'theme-options.php'); // What we do!

// Custom Theme Fucntions
// JRS: This only has a pagination function, that might/should be moved to another file
require_once ($includes_path . 'theme-functions.php'); 

// Custom Comments
// JRS: We don't support comments yet, and if we did it would probably be a Disqus, FB, or G+ system
//require_once ($includes_path . 'theme-comments.php'); 

// Load Javascript in wp_head
// This just enqueues jquery, which isn't necessary, so maybe remove
require_once ($includes_path . 'theme-js.php');

// Widgets  & Sidebars
require_once ($includes_path . 'sidebar-init.php');
require_once ($includes_path . 'theme-widgets.php');

add_action('wp_head', 'churchthemes_wp_head');
add_action('admin_menu', 'churchthemes_add_admin');
add_action('admin_head', 'churchthemes_admin_head');    

function page_slug(){
	global $post;
	$page_slug = 'page-'.$post->post_name; 
	return $page_slug;
}

function add_stylesheet(){
	wp_enqueue_style('stylesheet', get_template_directory_uri() . '/style.css', array(), null);
	
	// JRS: css3 only loads a font that is not used
	//wp_enqueue_style('stylesheet', get_template_directory_uri() . '/css/css3.css', array(), null);

//	wp_enqueue_style('nivo-style', get_template_directory_uri() . '/css/slider/nivo-slider.css');
//	wp_enqueue_style('nivo-default', get_template_directory_uri() . '/css/slider/default/default.css');

	// Other stylesheets should be loaded where needed
	// MediaElementJS is attached to the theme, so load it where needed
	// For other plugin-related styles, we disable them everywhere, EXCEPT where we need them

	// ADDED MediaElementJS: Only on media, sermon_post, and series taxonomy
	if( is_singular('sermon_post') || is_page('media') || is_tax('series') ) {
		wp_enqueue_style('mediaelements', get_template_directory_uri() . '/mediaelement/mediaelementplayer.css', array(), null);
	}
	
	// Leave MeteorSlides for the homepage only--for now. To use in different pages will require a different conditional
	if(!is_front_page()){
		wp_deregister_style( 'meteor-slides' );
	}

	// Restrict the contact form to the contact-us page--for now. To use in different pages will require a different conditional
	if(!is_page('contact-us')){
		wp_deregister_style( 'contact-form-7' );
	}
}

function theme_scripts_method() {
	wp_enqueue_script('main',get_template_directory_uri() . '/js/main.js',array('jquery'),null);
	
	// ADDED MediaElementJS: Only on media, sermon_post, and series taxonomy
	if( is_singular('sermon_post') || is_page('media') || is_tax('series') ) {
		wp_enqueue_script('mediaelements', get_template_directory_uri() . '/mediaelement/mediaelement-and-player.min.js',array('jquery'),null);
	}
	
	// Leave MeteorSlides for the homepage only--for now. To use in different pages will require a different conditional
	if(!is_front_page()){
		wp_deregister_script( 'jquery-cycle' );
		wp_deregister_script( 'jquery-touchwipe' );
		wp_deregister_script( 'jquery-metadata' );
		wp_deregister_script( 'meteorslides-script' );
	}
	
	// Restrict the contact form to the contact-us page--for now. To use in different pages will require a different conditional
	if(!is_page('contact-us')){
		wp_deregister_script( 'contact-form-7' );
	}
}
add_action('wp_enqueue_scripts', 'theme_scripts_method');
add_action('wp_enqueue_scripts', 'add_stylesheet');

function new_excerpt_length($length) {
	return 100;
}
add_filter('excerpt_length', 'new_excerpt_length');

function string_limit_words($string, $word_limit){

  $words = explode(' ', $string, ($word_limit+ 1));
	if(count($words) > $word_limit) {
		array_pop($words);
		//add a ... at last article when more than limitword count
		echo implode(' ', $words)."..."; } 
	else {
		//otherwise
		echo implode(' ', $words); 
	}
}

// Registering Menus For Theme
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
	register_nav_menus(
		array(
			'main-nav-menu' => __( 'Main Navigation Menu' ),
			'footer-menu' => __( 'Footer Menu' )
		)
	);
}

add_action( 'init', 'create_my_post_types' );
function create_my_post_types() {
/* 
//JRS: 11/23/2012: disable this slide post to allow the MeteorSlides post type to show through
	////// Register the slide post type
	register_post_type( 'slide',
		array(
			'labels' => array(
				'name' => __( 'Slides' ),
				'singular_name' => __( 'Slide' ),
				'add_new' => __( 'Add New' ),
				'add_new' => 'Add New Slide',
				'add_new_item' => __( 'Add New Slide' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Slide' ),
				'new_item' => __( 'New Slide' ),
				'view' => __( 'View Slide' ),
				'view_item' => __( 'View Slides' ),
				'search_items' => __( 'Search Slides' ),
				'not_found' => __( 'No Slides found' ),
				'not_found_in_trash' => __( 'No Slides found in Trash' ),
				'parent' => __( 'Parent Slide' )
			),
			'public' => true,
			'supports' => array('thumbnail','title'),
			'rewrite' => true,
			'query_var' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'capability_type' => 'post'
		)
	);

	// Add Metaboxes for slider
	function add_slide_metaboxes(){
		add_meta_box("slide_link", "Slide Link", "slide_metabox", "slide", "normal", "high");
	}
	add_action("admin_init", "add_slide_metaboxes");
	
	//// Slide Link
	function slide_metabox(){
		global $post;
		$custom = get_post_custom($post->ID);
		$slide_link = $custom["slide_link"][0];
?>
		<label>Slide link:</label>
		<input name="slide_link" value="<?php echo $slide_link; ?>" style="width: 50%;" />
<?php

	}
	// Save Slide Link
	function save_link($post_id) {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} 
		update_post_meta($post->ID, "slide_link", $_POST["slide_link"]);
	}	
	add_action('save_post', 'save_link');
	//// END Slide post
*/	

	////// Register Sermon post type
	register_post_type( 'sermon_post',
		array(
			'labels' => array(
				'name' 								=> __( 'Sermons' ),
				'singular_name' 			=> __( 'Sermon' ),
				'add_new'			 				=> __( 'Add New Sermon' ),
				'add_new_item' 				=> __( 'Add New Sermon' ),
				'edit_item' 					=> __( 'Edit Sermon Post' ),
				'new_item' 						=> __( 'Add New Sermon' ),
				'view_item' 					=> __( 'View Sermon' ),
				'search_items' 				=> __( 'Search Sermons' ),
				'not_found' 					=> __( 'No sermons found' ),
				'not_found_in_trash' 	=> __( 'No sermons found in trash' ),
				'menu_name' 					=> __( 'Sermons' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/sermon-icon.png',  // Icon Path
			'supports' 							=> array( 'title', 'editor', 'comments', 'thumbnail' ),
			'capability_type' 			=> 'post',
			'query_var' 						=> true,
			'exclude_from_search' 	=> false,
			'taxonomies' 						=> array( 'series','post_tag' ),
			'show_ui' 							=> true,
			'show_in_menu' 					=> true,
			'show_in_nav_menus'			=> true,
		//	'has_archive'						=> true
		)
	);
	register_taxonomy( 'series' , 'sermon_post', 
		array( 
			'hierarchical'	=> false,
			'label' 				=> __( 'Sermon Series' ) 
		) 
	);
	
	// Create Metaboxes for media post type
	function add_sermon_metaboxes(){
		add_meta_box("sermon_speaker", "Sermon Speaker", "sermon_speaker_metabox", "sermon_post", "side", "low");
		add_meta_box("sermon_filelink", "Sermon Link", "sermon_link_metabox", "sermon_post", "side", "low");
		add_meta_box("sermon_verse", "Sermon Verse", "sermon_verse_metabox", "sermon_post", "side", "low");
		
	}
	add_action("admin_init", "add_sermon_metaboxes");

	//// Sermon Speaker
	function sermon_speaker_metabox(){
		global $post;
		$custom = get_post_custom($post->ID);
		$sermon_speaker = $custom["sermon_speaker"][0];
	?>
			<label>Name of the speaker:</label>
			<input name="sermon_speaker" value="<?php echo $sermon_speaker; ?>" style="width: 95%;" />
	<?php
	}
	// Save Speaker
	function save_speaker($post_id) {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} 

		update_post_meta($post->ID, "sermon_speaker", $_POST["sermon_speaker"]);
	}
	add_action('save_post', 'save_speaker');
	//// END Sermon Speaker

	//// Sermon Link
	function sermon_link_metabox(){
		global $post;
		
		$custom = get_post_custom($post->ID);
		
		$sermon_filelink = $custom["sermon_filelink"][0];
		
	?>
		<label>Link to the MP3 File:</label>
		<input name="sermon_filelink" value="<?php echo $sermon_filelink; ?>" style="width: 95%;" />
	<?php
	}
	// Save Link
	function save_filelink($post_id) {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} 
		// Add enclosure to enable podcasting of sermon
		// JRS: 11/20/12
		update_post_meta($post->ID, "enclosure", make_enclosure($post->ID, $_POST["sermon_filelink"]));
		update_post_meta($post->ID, "sermon_filelink", $_POST["sermon_filelink"]);
	}
	add_action('save_post', 'save_filelink');
	//// END Sermon Link
	
	// Make a podcast-acceptable enclosure for the sermon link
	function make_enclosure($post_id, $file_link){
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

	//// Sermon Verse: JRS 12/2/1012
	function sermon_verse_metabox(){
		global $post;
		$custom = get_post_custom($post->ID);
		$sermon_verse = $custom["sermon_verse"][0];
	?>
			<label>Sermon Verse:</label>
			<input name="sermon_verse" value="<?php echo $sermon_verse; ?>" style="width: 95%;" />
	<?php
	}
	// Save Verse
	function save_verse($post_id) {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} 

		update_post_meta($post->ID, "sermon_verse", $_POST["sermon_verse"]);
	}
	add_action('save_post', 'save_verse');
	
	add_action("manage_posts_custom_column",  "sermon_custom_columns");
	add_filter("manage_sermon_post_posts_columns", "sermon_edit_columns");
	function sermon_edit_columns($columns){
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Sermon Title",    
			"verse" => "Sermon Verse",
			"speaker"	=> "Sermon Speaker",
			"date" => "Sermon Date",
		);
	 
		return $columns;
	}
	function sermon_custom_columns($column){
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
	//// END Sermon Verse
	
	////// Register Staff post type
	register_post_type( 'staff',
		array(
			'labels' => array(
				'name' 								=> __( 'Staff' ),
				'singular_name' 			=> __( 'Staff Member' ),
				'add_new' 						=> __( 'Add New Staff Member' ),
				'add_new_item' 				=> __( 'Add New Staff Member' ),
				'edit_item' 					=> __( 'Edit Staff Member' ),
				'new_item' 						=> __( 'Add New Staff Member' ),
				'view_item' 					=> __( 'View Staff Member' ),
				'search_items' 				=> __( 'Search Staff Members' ),
				'not_found' 					=> __( 'No Staff Members found' ),
				'not_found_in_trash' 	=> __( 'No Staff Members found in Trash' ),
				'menu_name'						=> __( 'Staff' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/staff-icon.png',  // Icon Path
			'capability_type' 			=> 'post',
			'supports' 							=> array( 'thumbnail', 'title', 'editor' ),
			'rewrite' 							=> true,
			'query_var' 						=> true,
			'exclude_from_search' 	=> false,
			'show_ui' 							=> true,
			'show_in_menu'					=> true,
			'show_in_nav_menus'			=> true
		)

	);
	// Add metaboxes for staff pages
	function add_staff_metaboxes(){
		add_meta_box("staff_designation", "Staff Designation", "staff_metabox", "staff", "normal", "high");
		add_meta_box("staff_email", "Staff Email", "staff_email_metabox", "staff", "normal", "high");
	}
	add_action("admin_init", "add_staff_metaboxes");

	//// Staff Desigation
	function staff_metabox(){
		global $post;
		$custom = get_post_custom($post->ID);
		$staff_designation = $custom["staff_designation"][0];
	?>
		<label>Staff Designation:</label>
		<input name="staff_designation" value="<?php echo $staff_designation; ?>" style="width: 50%;" />
	<?php
	}
	// Save Link
	function save_designation($post_id) {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} 
		update_post_meta($post->ID, "staff_designation", $_POST["staff_designation"]);
	}
	add_action('save_post', 'save_designation');
	//// End Staff Designation
	
	//// Staff Email
	// JRS: 12/21/12
	function staff_email_metabox(){
		global $post;
		$custom = get_post_custom($post->ID);
		$staff_email = $custom["staff_email"][0];
	?>
		<label>Staff Email:</label>
		<input name="staff_email" value="<?php echo $staff_email; ?>" style="width: 50%;" />
	<?php
	}
	// Save Link
	function save_staff_email($post_id) {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} 
		update_post_meta($post->ID, "staff_email", $_POST["staff_email"]);
	}
	add_action('save_post', 'save_staff_email');
	//// End Staff Designation
}


/**
* Enables the Event custom post type
**/
require_once(STYLESHEETPATH . '/extensions/event-post-type.php');

/**
* Enables the Widget Content custom post type
**/ 
require_once(STYLESHEETPATH . '/widget_content_type.php');

/**
* Enables the Event Widget
* DISABLED: JRS-replaced with new one on the theme-widgets.php
**/  
// require_once(STYLESHEETPATH . '/extensions/event-widget.php');

///////////////////////////////////////////////////////////////////////////////
//

// Removes default thumbnail width/height attr
// JRS
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );  
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 ); 
function remove_thumbnail_dimensions( $html ) {     
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );   
	return $html; 
} 


// Breadcrumb function
// JRS
function wp_do_breadcrumb() {
	global $post;
	// min_parents is the number of ancestors that a page must have to show the breadcrumbs
	// 0 = always show breadcrumbs
	$min_parents = 1;
	// breadcrumb delimiter >>
	$delimiter = '<span class="delimiter"> &raquo; </span>';
	// only act upon pages as I don't care for anything else right now
	if( is_page() ) { 
		// get the post parents in reverse order
		$parents = array_reverse(get_post_ancestors($post -> ID));
		// we are only concerned with sub-level pages
		if($parents && count($parents)>=$min_parents){
			// enclose in a classed div for easier styling later on
			echo '<div id="breadcrumb">';
			// loop over every ancestor to get its title and link
			foreach($parents as $pageid){
				$page = get_post($pageid);
				$title = $page -> post_title;
				echo '<a href="' . get_permalink($page) . '">'. $title . '</a>' . $delimiter;
			}
			echo get_the_title();
			echo '</div>';
		}
	}
}

// Change the feed to just the sermons
// JRS: 11/20/2012
function myfeed_request($qv) {
	if (isset($qv['feed']) && !isset($qv['post_type']))
		$qv['post_type'] = array('sermon_post');
	return $qv;
}
add_filter('request', 'myfeed_request');

// remove wp version param from any enqueued scripts
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

/*
// Show other blocks in the post page
function change_default_hidden($hidden, $screen) {
	// if ( 'post' == $screen->base || 'page' == $screen->base )
		$hidden = array('slugdiv', 'trackbacksdiv', 'postexcerpt', 'commentstatusdiv', 'commentsdiv', 'revisionsdiv');
		// removed 'postcustom','authordiv'
	return $hidden;
}
add_filter('default_hidden_meta_boxes', 'change_default_hidden', 10, 2);
*/

// Add custom post types to searches
// JRS: 12/19/12
function filter_search($query) {
    if ($query->is_search) {
			$query->set('post_type', array('staff','page','sermon_post','events'));
			
    };
    return $query;
};
add_filter('pre_get_posts', 'filter_search');

// Add icon and logo to Atom feeds
// JRS: 11/21/12
add_action('atom_head','atom_feed_add_icon');
add_action('comments_atom_head','atom_feed_add_icon');
function atom_feed_add_icon() { ?>
	<feed>
		<icon><?php echo get_option( 'church_custom_favicon' ); ?></icon>
		<logo><?php echo get_option( 'church_logo' ); ?></logo>
	</feed>
<?php }

// Add icon and logo to RSS feeds
// JRS: 11/21/12
add_action('rss_head','rss_feed_add_icon');
add_action('rss2_head','rss_feed_add_icon');
add_action('commentsrss2_head','rss_feed_add_icon');
function rss_feed_add_icon() { ?>
	<image>
		<url><?php echo get_option( 'church_logo' ); ?></url>
		<title><?php bloginfo_rss('name'); ?></title>
		<link><?php bloginfo_rss('url'); ?></link>
		<description><?php bloginfo('description'); ?></description>
	</image>
<?php } ?>
