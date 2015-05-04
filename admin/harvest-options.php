<?php
	
	$harvest_theme_sections['general']  = __( 'General Settings', 'harvest' );
	$harvest_theme_sections['social']   = __( 'Social Media', 'harvest' );
	//$harvest_theme_sections['ctc']   		= __( 'Church Content', 'harvest' );
	$harvest_theme_sections['podcast']   		= __( 'Podcasting', 'harvest' );
	$harvest_theme_sections['about']   	= __( 'About this Theme', 'harvest' );
	
	/* Individual settings in each section */
	$harvest_theme_options = array();
	
	// This will be the name used for the options
	$harvest_theme_options['themename']  = 'Harvest';

	/* General Settings
	===========================================*/	
	
	$harvest_theme_options['logo'] = array(
		'title'   => _x( 'Website Logo', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Upload a logo.', 'harvest' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'general'
	);
	
	$harvest_theme_options['feed_logo'] = array(
		'title'   => _x( 'Website RSS Feed Logo ', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Upload a logo to use in RSS feed. If not spcified, the logo above will be used.', 'harvest' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'general'
	);
	
	$harvest_theme_options['logo_name'] = array(
		'title'   => _x( 'Display site name', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Optionally display the site name next to the logo.', 'harvest' ),
		'std'     => '0',
		'type'    => 'checkbox',
		'section' => 'general'
	);
	
	$harvest_theme_options['logo_name_css'] = array(
		'title'   => _x( 'Site Name CSS', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Enter any specific styling for the logo name. Check the <code>.logo_name</code> style in the theme stylesheet for reference.', 'harvest' ),
		'std'     => '',
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	$harvest_theme_options['favicon'] = array(
		'title'   => _x( 'Favicon', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Upload a 16x16 png or gif icon for your site.', 'harvest'),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'general'
	);
	
	$harvest_theme_options['city'] = array(
		'title'   => _x( 'Default city', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Enter the city for your church.' , 'harvest'),
		'std'     => 'Albuquerque',
		'type'    => 'text',
		'section' => 'general'
	);
	
	$harvest_theme_options['slider'] = array(
		'title'   => _x( 'Slider', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Enter the shortcode a homepage slider (e.g. <code>[masterslider id="1"]</code>)', 'harvest' ),
		'std'     => '',
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	$harvest_theme_options['accent'] = array(
		'title'   => _x( 'Primary Accent Color', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Choose the default accent color for the theme. This color appears in the title bars of post and pages. ', 'harvest' ),
		'std'     => '#006f7c',
		'type'    => 'color',
		'section' => 'general'
	);
	
	$harvest_theme_options['secondary_accent'] = array(
		'title'   => _x( 'Secondary Accent Color', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Choose the secondary accent color for the theme. This color is used on the title bar of widgets in the body of the homepage, and location pages.', 'harvest' ),
		'std'     => '#b4b2b1',
		'type'    => 'color',
		'section' => 'general'
	);
	
	$harvest_theme_options['layout'] = array(
		'title'   => _x( 'Homepage Bottom Widget Area Layout', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Choose the layout for your homepage widget area.', 'harvest' ),
		'std'     => '66',
		'type'    => 'radio',
		'section' => 'general',
		'choices' => array(
				'66' => '<div class="box-demo"><div class="box-66"><div>&nbsp;</div></div><div class="box-33"><div>&nbsp;</div></div></div> (default)',
				'33' => '<div class="box-demo"><div class="box-33"><div>&nbsp;</div></div><div class="box-33"><div>&nbsp;</div></div><div class="box-33"><div>&nbsp;</div></div></div>'
			)
	);
	
	/* CTC Options
	===========================================*
	$harvest_theme_options['ctc-desc'] = array(
		'title'   => '',
		'desc'    => 'Enter the display names to use for the different church theme content types. For instance <code>People</code> could be <code>Staff</code>, <code>Sermons</code> could be <code>Messages</code> or <code>Locations</code> could be <code>Places</code>. Make sure to resave the Permalinks to update the permalinks. If separate singular and plural names are desired, write them as <code>Plural/Singluar</code> (i.e., <code>Campuses/Campus</code>).',
		'type'    => 'description',
		'std'     => '',
		'section' => 'ctc'
	);
	
	$harvest_theme_options['ctc-sermons'] = array(
		'title'   => 'Sermons',
		'desc'    => '',
		'type'    => 'text',
		'std'     => __( 'Sermons', 'harvest' ),
		'section' => 'ctc'
	);
	
	$harvest_theme_options['ctc-sermon-series'] = array(
		'title'   => 'Sermon Series',
		'desc'    => '',
		'type'    => 'text',
		'std'     => __( 'Sermon Series', 'harvest' ),
		'section' => 'ctc'
	);
	
	$harvest_theme_options['ctc-sermon-topic'] = array(
		'title'   => 'Sermon Topics' ,
		'desc'    => __( 'This theme uses the <code>Sermon Topics</code> field to assign a sermon to a location. While the topic is never used in the theme output, changing this to <code>Location</code> will make it clearer on the admin page.', 'harvest' ),
		'std'     => 'topic',
		'type'    => 'radio',
		'section' => 'ctc',
		'choices' => array(
				'topic' => __( 'Sermon Topic', 'harvest' ),
				'location' => __( 'Location', 'harvest' )
			)
	);
	
	$harvest_theme_options['ctc-locations'] = array(
		'title'   => 'Locations',
		'desc'    => '',
		'type'    => 'text',
		'std'     => __( 'Locations', 'harvest' ),
		'section' => 'ctc'
	);
	
	$harvest_theme_options['ctc-people'] = array(
		'title'   => 'People',
		'desc'    => '',
		'type'    => 'text',
		'std'     => __( 'People', 'harvest' ),
		'section' => 'ctc'
	);
	
	$harvest_theme_options['ctc-events'] = array(
		'title'   => 'Events',
		'desc'    => '',
		'type'    => 'text',
		'std'     => __( 'Events', 'harvest' ),
		'section' => 'ctc'
	);
	*/
	
	/* Social Media
	===========================================*/
	$harvest_theme_options['social-desc'] = array(
		'title'   => '',
		'desc'    => 'Enter the URL for your social media pages if you have one (i.e., <code>http://www.facebook.com/your_page</code>).',
		'type'    => 'description',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['facebook'] = array(
		'title'   => 'Facebook',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['twitter'] = array(
		'title'   => 'Twitter',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['google'] = array(
		'title'   => 'Google+',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['instagram'] = array(
		'title'   => 'Instagram',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['youtube'] = array(
		'title'   => 'You Tube',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social',
	);
	
	$harvest_theme_options['vimeo'] = array(
		'title'   => 'Vimeo',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
		
	/* Podcasting Podcasting
	===========================================*/
	$harvest_theme_options['podcast-desc'] = array(
		'title'   => '',
		'desc'    => 'Use this section to enhance your sermon feeds and make them compatible with iTunes&trade;',
		'type'    => 'description',
		'std'     => '',
		'section' => 'podcast'
	);
	
	$harvest_theme_options['podcast_desc'] = array(
		'title'   => __( 'Podcast Description', 'harvest'),
		'std'     => get_bloginfo ( 'description' ),
		'type'    => 'textarea',
		'desc'    => 'Text to display in the Description section of the podcast directory',
		'section' => 'podcast'
	);
	
	$harvest_theme_options['podcast_image'] = array(
		'title'   => __( 'Podcast Image', 'harvest' ),
		'std'     => '',
		'type'    => 'upload',
		'desc'    => __( 'Logo or image to use in the podcast directory. iTunes requires this to be a 1400 x 1400 PNG or JPG ending in .png or .jpg', 'harvest'),
		'section' => 'podcast'
	);

	$harvest_theme_options['podcast_author'] = array(
		'title'   => __( 'Podcast Author', 'harvest' ),
		'std'     => get_bloginfo ( 'name' ),
		'type'    => 'text',
		'desc'    => __( 'Name to use in the podcast directory. This is applied to ALL feeds.', 'harvest'),
		'section' => 'podcast'
	);
	
	