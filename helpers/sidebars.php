<?php
	// HELPER: Sidebars (aka widget areas)
	
	add_action( 'widgets_init', 'harvest_widgets_init' );
	function harvest_widgets_init(){
		if ( ! function_exists('register_sidebars') )
			return;

		// Note the sidebar names when using in a child theme
		
		// The drawback is that when themes are changed, the sidebars are deactivated because the
		// standard WP names of sidebar-1, sidebar-2... are not used
		// Main sidebar--used everywhere
		register_sidebar( array( 
			'name'          => 'Main Sidebar', 
			'id'            => 'sidebar-1',
			'description'   => 'Primary sidebar', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
		// Home page has four areas: boxes, and left, middle, and right above the footer
		register_sidebar( array( 
			'name'          => 'Home Page Boxes', 
			'id'            => 'home-boxes',
			'description'   => 'Widget area designed for 3 Home Page Box widgets. ', 
			'before_widget' => '<div id="%1$s" class="grid-33 front-box-widget %2$s">',
			'after_widget'  => '</div>',
		));

		register_sidebar( array( 
			'name'          => 'Home Page Bottom Left', 
			'id'            => 'home-left',
			'description'   => 'Widget area above the footer in the home page-left. If home page layout is 66-33, this area takes up 66% of the width. If the layout is 33-33-33, this area takes up 33% of the width.', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));

		register_sidebar( array( 
			'name'          => 'Home Page Bottom Middle', 
			'id'            => 'home-middle',
			'description'   => 'Widget area above the footer in the home page-middle. Only shown if the layout option is 33-33-33.', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));		

		register_sidebar( array( 
			'name'          => 'Home Page Bottom Right', 
			'id'            => 'home-right',
			'description'   => 'Widget area above the footer in the home page-right side', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
		/* Location widget areas */
		register_sidebar( array( 
			'name'          => 'Location Sidebar Right', 
			'id'            => 'location-sidebar-right',
			'description'   => 'Half-width widget area on single location pages, right side', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
		register_sidebar( array( 
			'name'          => 'Location Sidebar Left', 
			'id'            => 'location-sidebar-left',
			'description'   => 'Half-width widget area on single location pages, left side', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
		/* Event--Use this for a text widget to add content at the top of the event archive */
		register_sidebar( array( 
			'name'          => 'Event Top Sidebar', 
			'id'            => 'event-sidebar',
			'description'   => 'Widget area at top of event archive', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
		/* Sermon--Use this for a text widget to add content at the top of the sermon archive */
		register_sidebar( array( 
			'name'          => 'Sermon Top Sidebar', 
			'id'            => 'sermon-sidebar',
			'description'   => 'Widget area at top of sermon archive', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
		/* Footer widget area */
		register_sidebar( array( 
			'name'          => 'Footer', 
			'id'            => 'footer',
			'description'   => '', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		));
		
	}

