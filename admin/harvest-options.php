<?php
	
	$harvest_theme_sections['general']  = __( 'General Settings', 'harvest' );
	$harvest_theme_sections['social']   = __( 'Social Media', 'harvest' );
	$harvest_theme_sections['ctc']   		= __( 'Church Content', 'harvest' );
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
	
	$harvest_theme_options['favicon'] = array(
		'title'   => _x( 'Favicon', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Upload a 16x16 png or gif icon for your site.' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'general'
	);
	
	$harvest_theme_options['slider'] = array(
		'title'   => _x( 'Slider', 'Metabox name', 'harvest' ),
		'desc'    => __( 'Enter the shrotcode a homepage slider (e.g. <code>[masterslider id="1"]</code>)', 'harvest' ),
		'std'     => '',
		'type'    => 'textarea',
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
	===========================================*/
	$harvest_theme_options['ctc-desc'] = array(
		'title'   => '',
		'desc'    => 'Enter the display names to use for the different church theme content types. For instance <code>People</code> could be <code>Staff</code>, <code>Sermons</code> could be <code>Messages</code> or <code>Locations</code> could be <code>Places</code>',
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
		
	$harvest_theme_options['itunes-social'] = array(
		'title'   => 'iTunes',
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	