<?php
	
	/* Each section is a tab */
	$harvest_theme_sections['general']      = __( 'General Settings' );
	$harvest_theme_sections['logos']      	= __( 'Logos' );
	$harvest_theme_sections['social']   		= __( 'Social Media' );
	$harvest_theme_sections['analytics']   	= __( 'Analytics' );
	$harvest_theme_sections['about']   			= __( 'About this Theme' );
	
	
	/* Individual settings in each section */
	$harvest_theme_options = array();

	/* General Settings
	===========================================*/	
	$harvest_theme_options['layout'] = array(
		'title'   => __( 'Homepage Layout' ),
		'desc'    => __( 'Choose the layout for your homepage.' ),
		'std'     => 'two-two',
		'type'    => 'radio',
		'section' => 'general',
		'choices' => array(
				'two-two' => '<img src="'.get_stylesheet_directory_uri().'/images/two-two.png" style="vertical-align:middle; margin: 10px"/> (default)',
				'one-three' => '<img src="'.get_stylesheet_directory_uri().'//images/one-three.png" style="vertical-align:middle; margin: 10px"/>'
			)
	);
	
	$harvest_theme_options['headline'] = array(
		'title'   => __( 'Headline' ),
		'desc'    => __( 'Headline text displayed at the top of the page.' ),
		'std'     => get_bloginfo ( 'description' ),
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	$harvest_theme_options['address'] = array(
		'title'   => __( 'Address' ),
		'desc'    => __( 'Church address. Used in Header (optional), Footer and Contact Widget' ),
		'std'     => '',
		'type'    => 'textarea',
		'section' => 'general'
	);
	$harvest_theme_options['use_address'] = array(
		'title'   => '',
		'desc'    => __( 'Show link to Google Maps in headline' ),
		'std'     => 0,
		'type'    => 'checkbox',
		'section' => 'general'
	);
	
	$harvest_theme_options['phone'] = array(
		'title'   => __( 'Phone' ),
		'desc'    => __( 'Church phone number. Used in Footer and Contact Widget' ),
		'std'     => '',
		'type'    => 'text',
		'section' => 'general'
	);
	
	$harvest_theme_options['addl_info'] = array(
		'title'   => __( 'Additional Info' ),
		'desc'    => __( 'Enter any additional information you would like to display in the Contact Widget. HTML is allowed.' ),
		'std'     => '',
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	/* Logo Settings
	===========================================*/
	$harvest_theme_options['logo'] = array(
		'title'   => __( 'Custom Logo' ),
		'desc'    => __( 'Logo for website. Displayed on header and RSS feeds.' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'logos'
	);
	
	$harvest_theme_options['favicon'] = array(
		'title'   => __( 'Custom Favicon' ),
		'desc'    => __( 'Upload a 16x16 png or gif icon for your site.' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'logos'
	);
	
	$harvest_theme_options['custom_style'] = array(
		'title'   => __( 'Custom Color Stylesheet' ),
		'desc'    => __( 'Choose a stylesheet to complement the theme.' ),
		'std'     => '',
		'type'    => 'styles',
		'section' => 'logos' 
	);
	
	/* Analytics
	===========================================*/
	$harvest_theme_options['analytics-desc'] = array(
		'title'   => '',
		'desc'    => 'Paste your <a href="http://analytics.google.com" target="_blank">Google Analytics</a> to add it to your site\'s pages.',
		'type'    => 'description',
		'std'     => '',
		'section' => 'analytics'
	);
	$harvest_theme_options['google_analytics'] = array(
		'title'   => __( 'Google Analytics Code' ),
		'desc'    => '',
		'std'     => '',
		'type'    => 'textarea',
		'class'		=> 'analytics_box',
		'section' => 'analytics'
	);
	
	/* Social Media
	===========================================*/
	$harvest_theme_options['social-desc'] = array(
		'title'   => '',
		'desc'    => 'Enter the URL for your social media page, if you have one (i.e., <code>http://www.facebook.com/your_page</code>). A link back to your social page will be added to the header and/or footer if a value is entered.',
		'type'    => 'description',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['facebook'] = array(
		'title'   => __( 'Facebook' ),
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['twitter'] = array(
		'title'   => __( 'Twitter' ),
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['google'] = array(
		'title'   => __( 'Google+' ),
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['instagram'] = array(
		'title'   => __( 'Instagram' ),
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	
	$harvest_theme_options['youtube'] = array(
		'section' => 'social',
		'title'   => __( 'You Tube' ),
		'desc'    => '',
		'type'    => 'url',
		'std'     => ''
	);
	
	$harvest_theme_options['vimeo'] = array(
		'title'   => __( 'Vimeo' ),
		'desc'    => '',
		'type'    => 'url',
		'std'     => '',
		'section' => 'social'
	);
	

?>