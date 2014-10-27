<?php
/**
 * CUSTOM POST TYPE: Staff
 * 
 * Custom post type for showing staff member profiles
 * 
 */
?>
<?php

// Create Staff post type
add_action( 'init', 'harvest_createMinistryPostType' );
function harvest_createMinistryPostType() {
	$args = array(
		'labels' => array(
			'name'								=> __( 'Ministries', 'harvest-ctc' ),
			'singular_name'				=> __( 'Ministry', 'harvest-ctc' ),
			'add_new' 						=> __( 'Add New Ministry', 'harvest-ctc' ),
			'add_new_item' 				=> __( 'Add Ministry', 'harvest-ctc' ),
			'edit_item' 					=> __( 'Edit Ministry', 'harvest-ctc' ),
			'new_item' 						=> __( 'New Ministry', 'harvest-ctc' ),
			'all_items' 					=> __( 'All Ministries', 'harvest-ctc' ),
			'view_item' 					=> __( 'View Ministry', 'harvest-ctc' ),
			'search_items' 				=> __( 'Search Ministries', 'harvest-ctc' ),
			'not_found' 					=> __( 'No ministries found', 'harvest-ctc' ),
			'not_found_in_trash' 	=> __( 'No ministries found in Trash', 'harvest-ctc' )
		),
		'public' 				=> true,
		'has_archive' 	=> false,
		'rewrite'				=> array(
			'slug' 				=> 'ministries',
			'with_front' 	=> false,
			'feeds'				=> false
		),
		'supports' 			=> array( 'title', 'editor', 'publicize', 'thumbnail' ), 
		'menu_icon'			=> 'dashicons-groups',
		'menu_position'	=> 5 // below Posts
	);
	$args = apply_filters( 'ctc_post_type_person_args', $args ); // allow filtering

	register_post_type( 'ministry',
		array(
			'labels' => array(
				'name' 								=> __( 'Ministries' ),
				'singular_name' 			=> __( 'Ministry' ),
				'add_new' 						=> __( 'Add New Ministry' ),
				'add_new_item' 				=> __( 'Add New Ministry' ),
				'edit' 								=> __( 'Edit Ministry' ),
				'edit_item' 					=> __( 'Edit Ministry' ),
				'new_item' 						=> __( 'Add New Ministry' ),
				'view' 								=> __( 'View Ministry' ),
				'view_item' 					=> __( 'View Ministry' ),
				'search_items' 				=> __( 'Search Ministries' ),
				'not_found' 					=> __( 'No Ministries found' ),
				'not_found_in_trash' 	=> __( 'No Ministries found in Trash' ),
				'menu_name'						=> __( 'Ministries' )
			),
			'public' 								=> true,
			'menu_icon' 						=> get_bloginfo('stylesheet_directory') . '/images/ministry-icon.png',  // Icon Path
			'supports' 							=> array('title', 'editor', 'thumbnail' ),
			'rewrite' 							=> true,
			'query_var' 						=> true,
			'exclude_from_search' 	=> false,
			'show_ui' 							=> true,
			'show_in_menu'					=> true,
			'show_in_nav_menus'			=> true,
			'capability_type' 			=> 'post'
		)
	);
}

// Create Metaboxes 
add_action("admin_init", "harvest_add_ministry_metaboxes");
function harvest_add_ministry_metaboxes(){
	add_meta_box("ministry_poc", "Point-of-Contact for Ministry", "harvest_ministry_poc_metabox", "ministry", "normal", "high");
	add_meta_box("ministry_poc_email", "POC Email", "harvest_ministry_poc_email_metabox", "ministry", "normal", "high");
}

//// Staff Desigation
function harvest_ministry_poc_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$ministry_poc = $custom["ministry_poc"][0];
?>
	<label>Point-of-Contact for Miinistry:</label>
	<input name="ministry_poc" value="<?php echo $ministry_poc; ?>" style="width: 50%;" />
<?php
}
// Save Link
function harvest_save_ministry_poc($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "ministry_poc", $_POST["ministry_poc"]);
}
add_action('save_post', 'harvest_save_ministry_poc');
//// End Staff Designation

//// Staff Email
function harvest_ministry_poc_email_metabox(){
	global $post;
	$custom = get_post_custom($post->ID);
	$ministry_poc_email = $custom["ministry_poc_email"][0];
?>
	<label>POC Email:</label>
	<input name="ministry_poc_email" value="<?php echo $ministry_poc_email; ?>" style="width: 50%;" />
<?php
}
// Save Link
function harvest_save_ministry_poc_email($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	} 
	update_post_meta($post->ID, "ministry_poc_email", $_POST["ministry_poc_email"]);
}
add_action('save_post', 'harvest_save_ministry_poc_email');
//// End Staff Designation

add_shortcode('ministry', 'harvest_ministry_shortcode');  
function harvest_ministry_shortcode($attr) {

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('ministry', '', $attr);
	if ( $output != '' ) return $output;

	extract(shortcode_atts(array(
		'name'      => null,
		'align'			=> 'left',
		'before'    => '',
		'after'     => '',
		'noautop'   => null
		), $attr));

	$args=array(
		'name' => $name,
		'post_type' => 'ministry',
		'post_status' => 'publish',
		'posts_per_page' => 1
	);
	global $post;
	$ministries = get_posts( $args );
	$post = $ministries[0];
	if ($ministries) {
		setup_postdata($post);?>
		<div class="entry ministry">
			<h3><?php the_title(); ?></h3>
			<p>
			<?php the_post_thumbnail('ministry-full', array('class'=>'ministry-img '.$align)); ?>
			<?php $poc=get_post_meta($post->ID, 'ministry_poc', true); ?>
			<?php $email=get_post_meta($post->ID, 'ministry_poc_email', true); ?>
			<?php the_content(); ?>
			</p>
<?php if($poc): ?>
			<div class="staff_info">
				<i class="icon-user"></i> <span><?php echo $poc; ?></span><br/>
<?php if($email): ?>
				<i class="icon-envelope"></i> <span><a href="mailto:<?php echo $email; ?>" class="mail_link"><?php echo $email; ?></a></span>
<?php endif; ?>
			</div>
<?php endif; ?>
		</div>
<?php				
		
	}
}	
	// global $wpdb;
	// if($name == '') return;
	
	// $query = "SELECT $wpdb->posts.* FROM $wpdb->posts WHERE post_name = '$name'";   

	// $result = $wpdb->get_var($query);

	// if(!empty($result)){
		// if(!isset($noautop)) 
			// $result = wpautop( $result, $br = 1 ); 
		// return $before . $result . $after;
	// } else
		// return;
// }

?>