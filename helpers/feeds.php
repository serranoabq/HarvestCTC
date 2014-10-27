<?php
	// HELPER: Feed
	
	function harvest_prep_feeds(){
		add_filter( 'request', 'harvest_feed_request' );
		add_filter( 'the_excerpt_rss', 'harvest_rss_post_thumbnail' );
		add_filter( 'the_content_feed', 'harvest_rss_post_thumbnail' );
		add_action( 'atom_head', 'harvest_atom_feed_add_icon' );
		add_action( 'comments_atom_head', 'harvest_atom_feed_add_icon' );
		add_action( 'rss_head', 'harvest_rss_feed_add_icon' );
		add_action( 'rss2_head', 'harvest_rss_feed_add_icon' );
		add_action( 'commentsrss2_head', 'harvest_rss_feed_add_icon' );
	}

	/*************************************************************
	/ RSS Feeds
	/************************************************************/
	// Change the feed to just the sermons_posts
	function harvest_feed_request( $qv ) {
		if (isset( $qv['feed'] ) && !isset($qv[ 'post_type' ]))
			$qv[ 'post_type' ] = array( 'sermon_post' );
		return $qv;
	}

	// Add an image to go with the item on the feed
	function harvest_rss_post_thumbnail( $content ) {
		global $post;
		if(function_exists( 'harvest_getSermonImage' ) )
			$content = '<p>' . harvest_getSermonImage( $post->ID,'post-thumbnail' ) . '</p>' . $content;
		
		return $content;
	}

	// Add logos and icons to feeds
	function harvest_atom_feed_add_icon() { ?>
		<feed>
			<icon><?php echo harvest_option( 'favicon' ); ?></icon>
			<logo><?php echo harvest_option( 'logo' ); ?></logo>
		</feed>
	<?php }

	function harvest_rss_feed_add_icon() { ?>
		<image>
			<url><?php echo harvest_option( 'logo' ); ?></url>
			<title><?php bloginfo_rss( 'name' ); ?></title>
			<link><?php bloginfo_rss( 'url' ); ?></link>
			<description><?php bloginfo( 'description' ); ?></description>
		</image>
<?php 
	} 
	
?>