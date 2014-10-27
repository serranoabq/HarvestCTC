<?php
	
	/* Each section is a tab */
	$harvest_theme_sections['general']      = __( 'General Settings', 'harvest-ctc' );
	$harvest_theme_sections['logos']      	= __( 'Logos', 'harvest-ctc' );
	$harvest_theme_sections['social']   		= __( 'Social Media', 'harvest-ctc' );
	$harvest_theme_sections['itunes']   		= __( 'iTunes Podcast', 'harvest-ctc' );
	$harvest_theme_sections['analytics']   	= __( 'Analytics', 'harvest-ctc' );
	$harvest_theme_sections['about']   			= __( 'About this Theme', 'harvest-ctc' );
	
	
	/* Individual settings in each section */
	$harvest_theme_options = array();

	/* General Settings
	===========================================*/	
	$harvest_theme_options['layout'] = array(
		'title'   => _x( 'Homepage Layout', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Choose the layout for your homepage.', 'harvest-ctc' ),
		'std'     => 'two-two',
		'type'    => 'radio',
		'section' => 'general',
		'choices' => array(
				'two-two' => '<img src="'.get_stylesheet_directory_uri().'/images/two-two.png" style="vertical-align:middle; margin: 10px"/> (default)',
				'one-three' => '<img src="'.get_stylesheet_directory_uri().'//images/one-three.png" style="vertical-align:middle; margin: 10px"/>'
			)
	);
	
	
	$harvest_theme_options['custom_style'] = array(
		'title'   => __( 'Custom Color Stylesheet', 'harvest-ctc' ),
		'desc'    => __( 'Choose a stylesheet to complement the theme.', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'styles',
		'section' => 'general' 
	);
	
	$harvest_theme_options['headline'] = array(
		'title'   => _x( 'Headline', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Headline text displayed at the top of the page.', 'harvest-ctc' ),
		'std'     => get_bloginfo( 'description', 'harvest-ctc' ),
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	$harvest_theme_options['address'] = array(
		'title'   => __( 'Address', 'harvest-ctc' ),
		'desc'    => __( 'Church address. Used in Footer and Contact Widget', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	$harvest_theme_options['phone'] = array(
		'title'   => _x( 'Phone', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Church phone number. Used in Footer and Contact Widget', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'text',
		'section' => 'general'
	);
	
	$harvest_theme_options['addl_info'] = array(
		'title'   => _x( 'Additional Info', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Enter any additional information you would like to display in the Contact Widget. HTML is allowed.', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'textarea',
		'section' => 'general'
	);
	
	/* Logo Settings
	===========================================*/
	$harvest_theme_options['logo'] = array(
		'title'   => _x( 'Custom Logo', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Logo for website. Displayed on header and RSS feeds.', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'logos'
	);
	
	$harvest_theme_options['favicon'] = array(
		'title'   => _x( 'Custom Favicon', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Upload a 16x16 png or gif icon for your site.' ),
		'std'     => '',
		'type'    => 'upload',
		'section' => 'logos'
	);
	
	$harvest_theme_options['custom_style'] = array(
		'title'   => __( 'Custom Color Stylesheet', 'harvest-ctc' ),
		'desc'    => __( 'Choose a stylesheet to complement the theme.', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'styles',
		'section' => 'logos' 
	);
	
	/* Analytics
	===========================================*/
	$harvest_theme_options['analytics-desc'] = array(
		'title'   => '',
		'desc'    => __( 'Paste your <a href="http://analytics.google.com" target="_blank">Google Analytics</a> to add it to your site\'s pages. If you use a SEO plugin, you can ignore this feature.', 'harvest-ctc' ) ,
		'type'    => 'description',
		'std'     => '',
		'section' => 'analytics'
	);
	$harvest_theme_options['google_analytics'] = array(
		'title'   => _x( 'Google Analytics Code', 'Metabox name', 'harvest-ctc' ),
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
		'desc'    => 'Enter the URL for your social media pages if you have one (i.e., <code>http://www.facebook.com/your_page</code>). A link back to your social page will be added to the header and/or footer if a value is entered.',
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
	
	/* iTunes Podcasting
	===========================================*/
	$harvest_theme_options['it-desc'] = array(
		'title'   => '',
		'desc'    => __( 'Use this section to enhance your sermon feed and make it compatible with iTunes&trade;', 'harvest-ctc' ),
		'type'    => 'description',
		'std'     => '',
		'section' => 'itunes'
	);
	
	$harvest_theme_options['it-use'] = array(
		'title'   => _x( 'Enable iTunes enhancements', 'Metabox name', 'harvest-ctc' ),
		'desc'    => __( 'Must be checked for the enhancements below to take effect', 'harvest-ctc' ),
		'type'    => 'checkbox',
		'std'     => 0,
		'section' => 'itunes'
	);
	
	$harvest_theme_options['it-podurl'] = array(
		'title'   => __( 'iTunes podcast URL', 'harvest-ctc' ),
		'desc'    => __( 'URL to the podcast in the iTunes Store. This will be displayed in the media pages.', 'harvest-ctc' ),
		'type'    => 'url',
		'std'     => '',
		'section' => 'itunes'
	);
	
	$harvest_theme_options['it-poddesc'] = array(
		'title'   => _x( 'Podcast Description', 'Metabox name', 'harvest-ctc' ),
		'std'     => get_bloginfo ( 'description' ),
		'type'    => 'textarea',
		'desc'    => __( 'Text to display in the Description section of the podcast directory', 'harvest-ctc' ),
		'section' => 'itunes'
	);
	
	$harvest_theme_options['it-podimg'] = array(
		'title'   => _x( 'Podcast Image', 'Metabox name', 'harvest-ctc' ),
		'std'     => '',
		'type'    => 'upload',
		'desc'    => __( 'Logo or image to use in the podcast directory. iTunes requires this to be a 1400 x 1400 PNG or JPG ending in .png or .jpg', 'harvest-ctc'),
		'section' => 'itunes'
	);

	$harvest_theme_options['it-podauthor'] = array(
		'title'   => _x( 'Podcast Author', 'Metabox name', 'harvest-ctc' ),
		'std'     => get_bloginfo ( 'name' ),
		'type'    => 'text',
		'desc'    => __( 'Name to use in the podcast directory', 'harvets' ),
		'section' => 'itunes'
	);

?>