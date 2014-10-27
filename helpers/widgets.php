<?php
	// HELPER: Widgets
	
	add_action( 'widgets_init', 'harvest_widgets_init' );
	function harvest_widgets_init(){
		if ( !function_exists('register_sidebars') )
			return;

		// Note the sidebar names when using in a child theme
		
		// The drawback is that when themes are changed, the sidebars are deactivated because the
		// standard WP names of sidebar-1, sidebar-2... are notused
		// Main sidebar--used everywhere
		register_sidebar( array( 
			'name'          => 'Main Sidebar', 
			'id'            => 'sidebar-1',
			'description'   => 'Sidebar common to all pages/posts', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'.
		));
		

		// Home page has three areas: left, middle, and right
		register_sidebar( array( 
			'name'          => 'Home Page Left', 
			'id'            => 'sidebar-home-left',
			'description'   => 'Left widget area below the main slide', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'.
		));

		register_sidebar( array( 
			'name'          => 'Home Page Middle', 
			'id'            => 'sidebar-home-mid',
			'description'   => 'Middle widget area below the main slide', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'.
		));		

		register_sidebar( array( 
			'name'          => 'Home Page Right', 
			'id'            => 'sidebar-home-right',
			'description'   => 'Right widget area below the main slide', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'.
		));
		
		
	}

	
?>