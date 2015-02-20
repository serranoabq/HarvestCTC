<?php
	// HELPER: Widgets
	
	include_once( get_template_directory() . '/helpers/widget_weekly-cal.php' );
	include_once( get_template_directory() . '/helpers/widget_home-box.php' );
	include_once( get_template_directory() . '/helpers/widget_recent-sermon.php' );
	include_once( get_template_directory() . '/helpers/widget_locations.php' );
	
	add_action( 'widgets_init', 'harvest_registerWidgets' );
	function harvest_registerWidgets() {
		register_widget( 'harvest_HomeBox' );
		register_widget( 'harvest_WeeklyCalendar' );
		register_widget( 'harvest_RecentSermon' );
		register_widget( 'harvest_Locations' );
	}
	