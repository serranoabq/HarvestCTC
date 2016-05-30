<?php
	// HELPER: Feed
	
	// iTunes enhancements

function harvest_podcast_description(){
	global $wp_query;
	$query = $wp_query->query;
	$site_desc = bloginfo( 'description' );
	$pod_desc = harvest_option('podcast_desc');
	$term_desc = '';
	//if( is_user_logged_in() ){
		if( isset( $query['ctc_sermon_topic'] )){
			$term = get_term_by( 'slug', $query['ctc_sermon_topic'], 'ctc_sermon_topic' );
			$term_desc = $term->description;
		}
	//}
	if( !empty($term_desc) ) return $term_desc;
	if( !empty($pod_desc) ) return $pod_desc;
	if( !empty($site_desc) ) return $site_desc;
	return '';
}
	
// Add namespace
add_filter( 'rss2_ns', 'harvest_itunes_namespace' );
function harvest_itunes_namespace() {
	echo 'xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"';
}

add_filter( 'bloginfo_rss', 'harvest_rssbloginfo', 10, 2 );
function harvest_rssbloginfo( $rss_container, $show ){
	if( 'description' == $show ) return harvest_podcast_description();
	return $rss_container;
}

add_filter('rss2_head', 'harvest_itunes_head');
function harvest_itunes_head() {
		$desc = harvest_podcast_description();
if( harvest_option( 'podcast_author' ) ) 
			echo '
	<itunes:author>'. harvest_option( 'podcast_author' ) . '</itunes:author>';
			echo '
	<itunes:owner>
		<itunes:name>'. get_bloginfo( 'name' ) . '</itunes:name>
		<itunes:email>'. get_bloginfo( 'admin_email' ) . '</itunes:email>
	</itunes:owner>';

	if( $desc ) 
			echo '
	<itunes:summary>'. $desc . '</itunes:summary>';
	if( harvest_option('podcast_image') )
			echo '
	<itunes:image href="'. harvest_option('podcast_image' ) . '"/>';
}

function harvest_rss_query( $query ){
    if ( is_feed() ){
			
			$meta_query = array(
				array(
					'key'     => '_ctc_sermon_audio',
					'value'   => NULL,
					'compare' => '!=',
				),
			);
			$query->set( 'meta_query', $meta_query );
			$query->set( 'meta_key', '_ctc_sermon_audio' );
			  
    }
}
add_action( 'pre_get_posts', 'harvest_rss_query' );

/*************************************************************
/ RSS Feeds
/************************************************************/
// Change the feed to just the sermons
add_filter( 'request', 'harvest_feed_request' );
function harvest_feed_request( $qv ) {
	if( !isset( $qv['feed'] ) ) return $qv;
	if( !isset($qv[ 'post_type' ] ) )
		$qv[ 'post_type' ] = 'ctc_sermon';
	
	$topic_option = ctcex_get_option( 'ctc-sermon-topic' );
	if( !empty( $topic_option ) && ! isset( $qv['ctc_sermon_topic' ] ) ) {
		// Set the first location as the default
		$locs = get_terms('ctc_sermon_topic', array( 'order_by' => 'id', 'order' => 'DESC') );
		$loc0 = array_shift( $locs );
		$min_loc_slug = $loc0->slug;
		
		if( !empty( $min_loc_slug ) )
			$qv[ 'ctc_sermon_topic' ] = $min_loc_slug;
	}
	
	return $qv;
}

// Add an image to go with the item on the feed
add_filter( 'the_excerpt_rss', 'harvest_rss_post_thumbnail' );
add_filter( 'the_content_feed', 'harvest_rss_post_thumbnail' );
function harvest_rss_post_thumbnail( $content ) {
	global $post;
	$content = '<p><img src="' . harvest_getImage( $post->ID ) . '"/></p>' . $content;
	
	return $content;
}

// Add logos and icons to feeds
add_action( 'atom_head', 'harvest_atom_feed_add_icon' );
add_action( 'comments_atom_head', 'harvest_atom_feed_add_icon' );
function harvest_atom_feed_add_icon() { 
?>
	<feed>
		<icon><?php echo harvest_option( 'favicon' ); ?></icon>
		<logo><?php echo harvest_option( 'feed_logo' ) ? harvest_option( 'feed_logo' ) : harvest_option( 'logo' ); ?></logo>
	</feed>
<?php }

add_action( 'rss_head', 'harvest_rss_feed_add_icon' );
add_action( 'rss2_head', 'harvest_rss_feed_add_icon' );
add_action( 'commentsrss2_head', 'harvest_rss_feed_add_icon' );
function harvest_rss_feed_add_icon($text) { 
?>
	<image>
		<url><?php echo harvest_option( 'feed_logo' ) ? harvest_option( 'feed_logo' ) : harvest_option( 'logo' ); ?></url>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link><?php bloginfo_rss( 'url' ); ?></link>
		<description><?php echo harvest_podcast_description(); ?></description>
	</image>
<?php 
} 

// Fix feed title
add_filter('get_wp_title_rss', 'harvest_rss_title', 10, 2);
function harvest_rss_title( $title, $dep ){
	global $wp_query;
	$query = $wp_query->query;
	$title = get_bloginfo( 'name' );
	
	// If a topic (aka Location) is set fix the title appropriately
	if( isset( $query[ 'ctc_sermon_topic' ] ) ){
		// Since we've filtered the feed such that the first location is the 'default', we
		// only add the location to the title if it's not the default one
		$locs = get_terms('ctc_sermon_topic', array( 'order_by' => 'id', 'order' => 'DESC') );
		$def_loc = array_shift( $locs );
		$def_loc = $def_loc->slug;
		$term = get_term_by( 'slug', $query[ 'ctc_sermon_topic' ], 'ctc_sermon_topic' );
		if( $term && $term->slug != $def_loc ){
			$title .= ' ' . $term->name;
			// This corrects duplication of a name if the campus names have the name of the church 
			// ( e.g., "Crossroads" is the church and the campus is "Crossroads Springfield", which would result in "Crossroads Crossroads Springfield")
			$title = implode( ' ', array_unique( explode( ' ', $title ) ) );
		}
	}
	
	return $title;
}


