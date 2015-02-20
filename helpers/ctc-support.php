<?php 
// Add Church Theme Content support

// For Debugging ONLY
//add_action( 'admin_init', 'harvest_update_recurring_event_dates' );

function harvest_add_ctc(){
	 
	add_theme_support( 'church-theme-content' );
	
	// Add a few new metaboxes for events
	add_filter( 'ctmb_meta_box-ctc_event_date', 'harvest_metabox_filter_event_date' );
	
	// Update the event columns recurrence note
	add_filter( 'ctc_event_columns_recurrence_note', 'harvest_column_recurrence_note', 10, 2 );
	
	// Handle the event recurrence
	remove_action( 'ctc_update_recurring_event_dates', 'ctc_update_recurring_event_dates' );
	add_action( 'ctc_update_recurring_event_dates', 'harvest_update_recurring_event_dates' );

	// Change slugs in the custom CTC types
	add_filter( 'ctc_post_type_person_args', 'harvest_ctc_slugs', 10, 1);
	add_filter( 'ctc_post_type_sermon_args', 'harvest_ctc_slugs', 10, 1);
	add_filter( 'ctc_post_type_event_args', 'harvest_ctc_slugs', 10, 1);
	add_filter( 'ctc_post_type_location_args', 'harvest_ctc_slugs', 10, 1);
	add_filter( 'ctc_taxonomy_sermon_series_args', 'harvest_ctc_slugs', 10, 1);
	
	// Hijack the topic taxonomy for other purposes
	add_filter( 'ctc_taxonomy_sermon_topic_args', 'harvest_ctc_topic_args', 10, 1);
	
	// Add a new taxonomy for events
	add_filter( 'ctc_post_type_event_args', 'harvest_add_event_tag', 10, 1);
	
	// Events
	add_theme_support( 'ctc-events', array(
			'taxonomies' => array(),
			'fields' => array(
					'_ctc_event_start_date',
					'_ctc_event_end_date',
					'_ctc_event_start_time',
					'_ctc_event_end_time',
					'_ctc_event_recurrence',
					'_ctc_event_recurrence_end_date',
					'_ctc_event_recurrence_period', // Not default in CTC
					'_ctc_event_recurrence_monthly_type', // Not default in CTC
					'_ctc_event_recurrence_monthly_week', // Not default in CTC 
					'_ctc_event_venue',
					'_ctc_event_address',
					'_ctc_event_show_directions_link',
			),
			'field_overrides' => array()
	) );
	
	// Sermons
	add_theme_support( 'ctc-sermons', array(
			'taxonomies' => array(
					'ctc_sermon_topic',
					'ctc_sermon_series',
					'ctc_sermon_speaker',
			),
			'fields' => array(
					'_ctc_sermon_video',
					'_ctc_sermon_audio',
			),
			'field_overrides' => array()
	) );
	 
	// People
	add_theme_support( 'ctc-people', array(
			'taxonomies' => array(
					'ctc_person_group',
			),
			'fields' => array(
					'_ctc_person_position',
					'_ctc_person_phone',
					'_ctc_person_email',
					'_ctc_person_urls',
			),
			'field_overrides' => array()
	) );

	// Locations
	add_theme_support( 'ctc-locations', array(
			'taxonomies' => array(),
			'fields' => array(
					'_ctc_location_address',
					'_ctc_location_phone',
					'_ctc_location_times',
			),
			'field_overrides' => array()
	) );

}

// Change Sermon Topic to Location
function harvest_ctc_topic_args( $args ){
	if( ! harvest_option( 'ctc-sermon-topic' ) ) return $args;
	if( harvest_option( 'ctc-sermon-topic' ) != 'location' ) return $args;
	
	add_filter( 'manage_ctc_sermon_posts_columns' , 'harvest_ctc_topic_col', 10, 1 );
	
	$name = __( 'Location', 'harvest' );
	
	$search = array( 'Topics', 'Topic', 'topics', 'topic' );
	$replace = array( $name, $name, strtolower( $name ), strtolower( $name ) );
	$args = json_decode( str_replace( $search, $replace, json_encode( $args ) ) );
	return $args;
}

// Change the topic column to location
function harvest_ctc_topic_col( $columns ){
	if( $columns['ctc_sermon_topics'] ) 
		$columns['ctc_sermon_topics'] = 'Location';
	return $columns;
}

// Change slugs of CTC 
function harvest_ctc_slugs( $args ){
	$cpt = $args['rewrite']['slug'];
	$name = $args['labels']['name'];
	$cpt = sanitize_title( harvest_option( 'ctc-' . $cpt, $cpt  ), $cpt);
	$name = harvest_option( 'ctc-' . $cpt, $name );
	
	$args['labels']['name'] = $name;
	$args['rewrite']['slug'] = $cpt;
	return $args;
}

// Custom event taxonomy
function harvest_add_event_tag( $args ){
	$args[ 'taxonomies' ] = array( 'ctc_event_tag' );
	return $args;
}

// Custom event taxonomy
function harvest_register_taxonomy_event_tag() {
	// Arguments
	$args = array(
		'labels' => array(
			'name' 							=> _x( 'Event Tag', 'taxonomy general name', 'harvest' ),
			'singular_name'					=> _x( 'Event Tag', 'taxonomy singular name', 'harvest' ),
			'search_items' 					=> _x( 'Search Tags', 'people', 'harvest' ),
			'popular_items' 				=> _x( 'Popular Tags', 'people', 'harvest' ),
			'all_items' 					=> _x( 'All Tags', 'people', 'harvest' ),
			'parent_item' 					=> null,
			'parent_item_colon' 			=> null,
			'edit_item' 					=> _x( 'Edit Tag', 'people', 'harvest' ),
			'update_item' 					=> _x( 'Update Tag', 'people', 'harvest' ),
			'add_new_item' 					=> _x( 'Add Tag', 'people', 'harvest' ),
			'new_item_name' 				=> _x( 'New Tag', 'people', 'harvest' ),
			'separate_items_with_commas' 	=> _x( 'Separate tags with commas', 'people', 'harvest' ),
			'add_or_remove_items' 			=> _x( 'Add or remove tags', 'people', 'harvest' ),
			'choose_from_most_used' 		=> _x( 'Choose from the most used tags', 'people', 'harvest' ),
			'menu_name' 					=> _x( 'Tags', 'event menu name', 'harvest' )
		),
		'hierarchical'	=> true, // category-style instead of tag-style
		'public' 		=> true,
		'rewrite' 		=> array(
			'slug' 			=> 'event-tag',
			'with_front' 	=> false,
			'hierarchical' 	=> true
		)
	);
	$args = apply_filters( 'ctc_taxonomy_event_tag_args', $args ); // allow filtering

	// Registration
	register_taxonomy( 
		'ctc_event_tag',
		'ctc_event',
		$args
	);
}
add_action( 'init', 'harvest_register_taxonomy_event_tag' );

// New event metaboxes
function harvest_metabox_filter_event_date( $meta_box ) {
	// With the exception of daily recurrence, the other settings are included in the
	// CTC plugin, but are not exposed by default. These are exposed by the custom 
	// recurrence plugin ($30/year/site). Yeah, I didn't want to pay, so I rolled up
	// my own
	
	// Add daily recurrence 
	$options = $meta_box['fields']['_ctc_event_recurrence']['options'];
	$meta_box['fields']['_ctc_event_recurrence']['options'] = ctc_array_merge_after_key(
		$options, 
		array( 'daily' => _x( 'Daily', 'event meta box', 'harvest' ) ),
		'none'	
	);
	
	// Add recurrence period
	$recurrence_period = array(
		'name'	=> __( 'Recurrence Period', 'harvest' ),
		'after_name'	=> '',
		'after_input'	=> '',
		'desc'	=> _x( 'Recur every N days/weeks/months/years', 'event meta box', 'harvest' ),
		'type'	=> 'select', 
		'options'	=> array_combine( range(1,12), range(1,12) ) ,
		'default'	=> '1', 
		'no_empty'	=> true, // if user empties value, force default to be saved instead
		'allow_html'	=> false, // allow HTML to be used in the value (text, textarea)
		'visibility' 		=> array( 
			'_ctc_event_recurrence' => array( 'none', '!=' ),
		)
	);
	$meta_box['fields'] = ctc_array_merge_after_key(
		$meta_box['fields'], 
		array( '_ctc_event_recurrence_period' => $recurrence_period ),
		'_ctc_event_recurrence'	
	);
	
	// Add recurrence monthly type
	$recurrence_monthly_type = array(
		'name'	=> __( 'Monthly Recurrence Type', 'harvest' ),
		'desc'	=> '',
		'type'	=> 'radio', 
		'options'	=> array( 
			'day' => _x( 'On the same day of the month', 'harvest' ),
			'week' => _x( 'On a specific week of the month', 'harvest' ),
		),
		'default'	=> 'day', 
		'no_empty'	=> true, // if user empties value, force default to be saved instead
		'allow_html'	=> false, // allow HTML to be used in the value (text, textarea)
		'visibility' 		=> array( 
			'_ctc_event_recurrence' => 'monthly',
		)
	);
	$meta_box['fields'] = ctc_array_merge_after_key(
		$meta_box['fields'], 
		array( '_ctc_event_recurrence_monthly_type' => $recurrence_monthly_type ),
		'_ctc_event_recurrence_period'	
	);
	
	// Add recurrence monthly week
	$recurrence_monthly_week = array(
		'name'	=> __( 'Monthly Recurrence Week', 'harvest' ),
		'desc'	=> _x( 'Day of the week is the same as Start Date', 'event meta box', 'harvest' ),
		'type'	=> 'select', // text, textarea, checkbox, radio, select, number, upload, upload_textarea, url
		'options'	=> array( 
			'1' 		=> 'First Week',
			'2' 		=> 'Second Week',
			'3'		 	=> 'Third Week',
			'4' 		=> 'Fourth Week',
			'last' 	=> 'Last Week',
		) ,
		'default'	=> '', 
		'no_empty'	=> true, 
		'custom_field'	=> '', // function for custom display of field input
		'visibility' 		=> array( 
			'_ctc_event_recurrence_monthly_type' => 'week',
		)
	);
	$meta_box['fields'] = ctc_array_merge_after_key(
		$meta_box['fields'], 
		array( '_ctc_event_recurrence_monthly_week' => $recurrence_monthly_week ),
		'_ctc_event_recurrence_monthly_type'	
	);
	
	return $meta_box;
}

// Update the recurrence note on the Events listing
function harvest_column_recurrence_note( $recurrence_note, $args ){
	extract( $args );
	return harvest_get_recurrence_note( $post );
}

// This helper is used to get an expression for recurrence
function harvest_get_recurrence_note( $post_obj ) {
	if( !isset( $post_obj ) )
		global $post;
	else
		$post = $post_obj;
	
	$start_date = trim( get_post_meta( $post->ID , '_ctc_event_start_date' , true ) );
	$recurrence = get_post_meta( $post->ID , '_ctc_event_recurrence' , true );
	$recurrence_period = get_post_meta( $post->ID , '_ctc_event_recurrence_period' , true );
	$recurrence_monthly_type = get_post_meta( $post->ID , '_ctc_event_recurrence_monthly_type' , true );
	$recurrence_monthly_week = get_post_meta( $post->ID , '_ctc_event_recurrence_monthly_week' , true );
	$recurrence_note = '';
	
	// Frequency
	switch ( $recurrence ) {

		case 'daily' :
			$recurrence_note = sprintf( 
				_n( 'Every day','Every %d days', (int)$recurrence_period, 'harvest' ), 
				(int)$recurrence_period 
			);
			break;
			
		case 'weekly' :
			$recurrence_note = sprintf( 
				_n( 'Every week', 'Every %d weeks', (int)$recurrence_period, 'harvest' ), 
				(int)$recurrence_period 
			);
			break;

		case 'monthly' :
			$recurrence_note = sprintf( 
				_n( 'Every month','Every %d months', (int)$recurrence_period, 'harvest' ), 
				(int)$recurrence_period 
			);
			break;

		case 'yearly' :
			$recurrence_note = sprintf( 
				_n( 'Every year','Every %d years', (int)$recurrence_period, 'harvest' ), 
				(int)$recurrence_period 
			);
			break;

	}
	
	if( $recurrence_monthly_type && $recurrence_monthly_week ) {
		if( $recurrence_monthly_type == 'day' ) {
			$recurrence_note .= sprintf( _x(' on the %s', 'date expression', 'harvest'), date_i18n( 'jS' , strtotime( $start_date ) ) );
		} else {
			$ends = array( '1' => '1st', '2' => '2nd', '3' => '3rd', '4' => '4th' );
			if( $recurrence_monthly_week != 'last' )
				$recurrence_monthly_week = $ends[ $recurrence_monthly_week ];
			$recurrence_note .= sprintf( _x(' on the %s %s', 'date expression', 'harvest'), $recurrence_monthly_week, date_i18n( 'l' , strtotime( $start_date ) ) );
		}
	}
	
	return $recurrence_note;
}

// Update recurring event dates. This overrides the function provided by CTC plugin to allow daily recurrence, and custom recurrence periods
function harvest_update_recurring_event_dates() {

	// Get all events with end date in past and have valid recurring value
	$events_query = new WP_Query( array(
		'post_type'	=> 'ctc_event',
		'nopaging'	=> true,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_ctc_event_end_date',
				'value' => date_i18n( 'Y-m-d' ), // today localized
		 		'compare' => '<', // earlier than today
				'type' => 'DATE',
		   ),
			array(
				'key' => '_ctc_event_recurrence',
				'value' => array( 'daily', 'weekly', 'monthly', 'yearly' ),
		 		'compare' => 'IN',
		   )
		)
	) );

	// Loop events
	if ( ! empty( $events_query->posts ) ) {

		// Instantiate recurrence class
		$ctc_recurrence = new Harvest_Recurrence();

		// Loop events to modify dates
		foreach ( $events_query->posts as $post ) {

			// Get start and end date
			$start_date = get_post_meta( $post->ID, '_ctc_event_start_date', true );
			$end_date = get_post_meta( $post->ID, '_ctc_event_end_date', true );

		 	// Get recurrence
		 	$recurrence = get_post_meta( $post->ID, '_ctc_event_recurrence', true );
		 	$recurrence_period = get_post_meta( $post->ID, '_ctc_event_recurrence_period', true );
		 	$recurrence_monthly_type = get_post_meta( $post->ID, '_ctc_event_recurrence_monthly_type', true );
		 	$recurrence_monthly_week = get_post_meta( $post->ID, '_ctc_event_recurrence_monthly_week', true );
			$recurrence_end_date = get_post_meta( $post->ID, '_ctc_event_recurrence_end_date', true );

			// Difference between start and end date in seconds
			$time_difference = strtotime( $end_date ) - strtotime( $start_date );

			// Get soonest occurrence that is today or later
			$args = array(
				'start_date'			=> $start_date, // first day of event, YYYY-mm-dd (ie. 2015-07-20 for July 15, 2015)
				'frequency'				=> $recurrence, // daily, weekly, monthly, yearly
				'interval'				=> $recurrence_period,
				'monthly_type'		=> $recurrence_monthly_type,
				'monthly_week'		=> $recurrence_monthly_week,
			);
			$args = apply_filters( 'ctc_event_recurrence_args', $args, $post ); // Custom Recurring Events add-on uses this
			$new_start_date = $ctc_recurrence->calc_next_future_date( $args );

			// If no new start date gotten, set it to current start date
			// This could be because recurrence ended, arguments are invalid, etc.
			if ( ! $new_start_date ) {
				$new_start_date = $start_date;
			}

			// Add difference between original start/end date to new start date to get new end date
			$new_end_date = date( 'Y-m-d', ( strtotime( $new_start_date ) + $time_difference ) );

			// Has recurrence ended?
			// Recurrence end date exists and is earlier than new start date
			if ( $recurrence_end_date && strtotime( $recurrence_end_date ) < strtotime( $new_start_date ) ) {

				// Unset recurrence option to keep dates from being moved forward
				update_post_meta( $post->ID, '_ctc_event_recurrence', 'none' );

			}

			// No recurrence or recurrence end date is still future
			else {

				// Update start and end dates
				update_post_meta( $post->ID, '_ctc_event_start_date', $new_start_date );
				update_post_meta( $post->ID, '_ctc_event_end_date', $new_end_date );

				// Update the hidden datetime fields for ordering
				ctc_update_event_date_time( $post->ID );

			}

		}

	}

}

if( class_exists( 'CT_Recurrence' ) ) {
	// This extension is used by the harvest_update_recurring_event_dates
	class Harvest_Recurrence extends CT_Recurrence {
		public function __construct() {
			// Version
			$this->version = '0.9.1';
		}

		// This is a rewrite of the original class function to add 'daily' 
		// as an acceptable frequency argument. Would've been nice to have a filter.
		public function prepare_args( $args ) {

			// Is it a non-empty array?
			if ( empty( $args ) || ! is_array( $args ) ) { // could be empty array; set bool
				$args = false;
			}

			// Acceptable arguments
			$acceptable_args = array(
				'start_date',
				'until_date',
				'frequency',
				'interval',
				'monthly_type',
				'monthly_week',
				'limit',
			);

			// Loop arguments
			// Sanitize and set all keys
			$new_args = array();
			foreach( $acceptable_args as $arg ) {
				// If no key, set it
				if ( ! empty( $args[$arg] ) ) {
					$new_args[$arg] = $args[$arg];
				} else {
					$new_args[$arg] = '';
				}

				// Trim value
				$args[$arg] = trim( $new_args[$arg] );
			}
			$args = $new_args;

			// Start Date
			if ( $args ) {
				if ( empty( $args['start_date'] ) || ! $this->validate_date( $args['start_date'] ) ) {
					$args = false;
				}
			}

			// Until Date (optional)
			if ( $args ) {
				// Value is provided
				if ( ! empty( $args['until_date'] ) ) {
					// Date is invalid
					if ( ! $this->validate_date( $args['until_date'] ) ) {
						$args = false;
					}
				}
			}

			// Frequency
			if ( $args ) {
				// Value is invalid - Grand change here: 'daily'
				if ( empty( $args['frequency'] ) || ! in_array( $args['frequency'], array( 'daily', 'weekly', 'monthly', 'yearly' ) ) ) {
					$args = false;
				}
			}

			// Interval
			// Every X weeks / months / years
			if ( $args ) {
				// Default is 1 if nothing given
				if ( empty( $args['interval'] ) ) {
					$args['interval'] = 1;
				}
				// Invalid if not numeric or is negative
				if ( ! is_numeric( $args['interval'] ) || $args['interval'] < 1 ) {
					$args = false;
				}
			}

			// Monthly Type (required when frequency is monthly)
			if ( $args ) {
				// Value is required
				if ( 'monthly' == $args['frequency'] ) {
					// Default to day if none
					if ( empty( $args['monthly_type'] ) ) {
						$args['monthly_type'] = 'day';
					}
					// Value is invalid
					if ( ! in_array( $args['monthly_type'], array( 'day', 'week' ) ) ) {
						$args = false; // value is invalid
					}
				}
				// Not required in this case
				else {
					$args['monthly_type'] = '';
				}
			}

			// Monthly Week (required when frequency is monthly and monthly_type is week)
			if ( $args ) {
				// Value is required
				if ( 'monthly' == $args['frequency'] && 'week' == $args['monthly_type'] ) {
					// Is value valid?
					if ( empty( $args['monthly_week'] ) || ! in_array( $args['monthly_week'], array( '1', '2', '3', '4', 'last' ) ) ) {
						$args = false; // value is invalid
					}
				}
				// Not required in this case
				else {
					$args['monthly_week'] = '';
				}
			}

			// Limit (optional)
			if ( $args ) {
				// Set default if no until date to prevent infinite loop
				if ( empty( $args['limit'] ) && empty( $args['until_date'] ) ) {
					$args['limit'] = 100;
				}
				// Limit is not numeric or is negative
				if ( ! empty( $args['limit'] ) && ( ! is_numeric( $args['limit'] ) || $args['limit'] < 1 ) ) {
					$args['limit'] = false;
				}
			}
			
			return $args;
		}

		// This is the same as the original function, except that it
		// incorporates a daily recurrence
		public function calc_next_date( $args ) {
			$date = false;
			
			// Validate and set default arguments
			$args = $this->prepare_args( $args );

			// Get next recurring date
			// This may or may not be future
			if ( $args ) { // valid args
				// Get month, day and year
				list( $start_date_y, $start_date_m, $start_date_d ) = explode( '-', $args['start_date'] );

				// Calculate next recurrence
				switch ( $args['frequency'] ) {

					// Daily - New
					case 'daily' :
						// Add day(s)--Update 0.9.1
						$DateTime = new DateTime( $args['start_date'] );
						$DateTime->modify( '+' . $args['interval'] . ' days' );
						list( $y, $m, $d ) = explode( '-', $DateTime->format( 'Y-m-d' ) );
						break;

					// Weekly
					case 'weekly' :
						// Add week(s)
						$DateTime = new DateTime( $args['start_date'] );
						$DateTime->modify( '+' . $args['interval'] . ' weeks' );
						list( $y, $m, $d ) = explode( '-', $DateTime->format( 'Y-m-d' ) );
						break;

					// Monthly
					case 'monthly' :
						// On same day of the month
						$DateTime = new DateTime( $args['start_date'] );
						$DateTime->modify( '+' . $args['interval'] . ' months' );
						list( $y, $m, $d ) = explode( '-', $DateTime->format( 'Y-m-d' ) );

							// Notes removed: see CT_Recurrence
							if ( $d < $start_date_d ) {
								// Move back to last day of last month
								$m--;
								if ( 0 == $m ) {
									$m = 12;
									$y--;
								}
								// Get days in the prior month
								$d = date( 't', mktime( 0, 0, 0, $m, $d, $y) );
							}

						// On a specific week of month's day
						// 1st - 4th or Last day of week in the month
						if ( 'week' == $args['monthly_type'] && ! empty( $args['monthly_week'] ) ) {
							// What is start_date's day of the week
							// 0 - 6 represents Sunday through Saturday
							$start_date_day_of_week = date( 'w', strtotime( $args['start_date'] ) );

							// Loop the days of this month
							$week_of_month = 1;
							$times_day_of_week_found = 0;
							$days_in_month = date( 't', mktime( 0, 0, 0, $m, 1, $y ) );

							for ( $i = 1; $i <= $days_in_month; $i++ ) {

								// Get this day's day of week (0 - 6)
								$day_of_week = date( 'w', mktime( 0, 0, 0, $m, $i, $y ) );

								// This day's day of week matches start date's day of week
								if ( $day_of_week == $start_date_day_of_week ) {
									$last_day_of_week_found = $i;
									$times_day_of_week_found++;

									// Is this the 1st - 4th day of week we're looking for?
									if ( $args['monthly_week'] == $times_day_of_week_found ) {
										$d = $i;
										break;
									}
								}
							}

							// Are we looking for 'last' day of week in a month?
							if ( 'last' == $args['monthly_week'] && ! empty( $last_day_of_week_found ) ) {
								$d = $last_day_of_week_found;
							}
						}

						break;

					// Yearly
					case 'yearly' :
						// Move forward X year(s)
						$DateTime = new DateTime( $args['start_date'] );
						$DateTime->modify( '+' . $args['interval'] . ' years' );
						list( $y, $m, $d ) = explode( '-', $DateTime->format( 'Y-m-d' ) );

							// Note removed
							if ( $d < $start_date_d ) {
								// Move back to last day of last month
								$m--;
								if ( 0 == $m ) {
									$m = 12;
									$y--;
								}

								// Get days in the prior month
								$d = date( 't', mktime( 0, 0, 0, $m, $d, $y) );
							}
						break;
				}

				// Form the date string
				$date = date( 'Y-m-d', mktime( 0, 0, 0, $m, $d, $y ) ); // pad day, month with 0
			}
			return $date;
		}
	}
}