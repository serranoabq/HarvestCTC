<?php
	// HELPER: Feed
	
	// iTunes enhancements

function harvest_podcast_description(){
	global $wp_query;
	$query = $wp_query->query;
	$site_desc = bloginfo( 'description' );
	$pod_desc = harvest_option('podcast_desc');
	$term_desc = '';
	if( is_user_logged_in() ){
	if( isset( $query['ctc_sermon_topic'] )){
		$term = get_term_by( 'slug', $query['ctc_sermon_topic'], 'ctc_sermon_topic' );
		$term_desc = $term->description;
	}
	}
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

add_filter('rss2_head', 'harvest_itunes_head');
function harvest_itunes_head() {
		$desc = harvest_podcast_description();
if( harvest_option( 'podcast_author' ) ) 
			echo '
	<itunes:author>'. harvest_option( 'podcast_author' ) . '</itunes:author>';
	if( $desc ) 
			echo '
	<itunes:summary>'. $desc . '</itunes:summary>';
	if( harvest_option('podcast_image') )
			echo '
	<itunes:image href="'. harvest_option('podcast_image' ) . '"/>';
}
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
			$min_loc_slug = $locs[0]->slug;
			
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
	
	