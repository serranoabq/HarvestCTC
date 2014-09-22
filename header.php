<!DOCTYPE html>

<!--[if lte IE 7]> <html class="ie ie6"> <![endif]-->
<!--[if IE 7]>     <html class="ie ie7"> <![endif]-->
<!--[if IE 8]>     <html class="ie ie8"> <![endif]-->
<!--[if IE 9]>     <html class="ie ie9"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="es"> <!--<![endif]-->
<head>
	<title>
		<?php bloginfo( 'name' ); ?>
		<?php if ( is_home() ) { ?> | <?php bloginfo( 'description' ); } ?>
		<?php if ( is_page() || is_single() ) { ?> | <?php wp_title(''); ?><?php if('' != wp_title( '' ,false ) ) { ?> | <?php } ?><?php bloginfo( 'name' ); ?><?php } ?>
		
		<?php if ( is_tax('ctc_sermon_series') ) { ?> | Sermon Series: <?php single_tag_title( '', true ); } ?>
		<?php if ( is_tax('ctc_sermon_book') ) { ?> | Sermon Books: <?php single_tag_title( '', true ); } ?>
		<?php if ( is_tax('ctc_sermon_speaker') ) { ?> | Sermon Speakers: <?php single_tag_title( '', true ); } ?>
		<?php if ( is_tax('ctc_sermon_topic') ) { ?> | Sermon Topics: <?php single_tag_title( '', true ); } ?>

		<?php if ( is_search() ) { ?> | Search Results <?php } ?>
		<?php if ( is_author() ) { ?> | Author Archive <?php } ?>
		<?php if ( is_category() ) { ?> | Archive: <?php single_cat_title(); } ?>
		<?php if ( is_month() ) { ?> |  Archive  | <?php the_time('F'); ?><?php } ?>
	</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<?php if ( harvest_option( 'google_verification' ) <> "" ) { ?>
	<meta name="google-site-verification" content=<?php echo harvest_option( 'google_verification' ); ?> />
<?php } ?>
	
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,800" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Oswald:300,700' rel='stylesheet' type='text/css'>
	
<?php if(harvest_option('favicon') <> "") { ?>
	<link rel="shortcut icon" href="<?php echo harvest_option('favicon')  ?> "/>
<?php }?>
     
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_bloginfo_rss('rss2_url'); ?>" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class( harvest_page_slug() ); ?>>

	<div id="top">
		<div id="top_bar">
			<div id="topheadline">
				<span id="headline">
					<?php if( harvest_option( 'headline' ) ) echo stripslashes (harvest_option( 'headline' )); else bloginfo( 'description' );?>
					<?php if ( harvest_option( 'use_address' ) ) { ?>
					- <a href="http://maps.google.com/?q=<?php echo harvest_option( 'address' ); ?>" title="<?php _e( 'GET DIRECTIONS' , 'harvest' ); ?>" target="_blank"><?php _e( 'GET DIRECTIONS' , 'harvest' ); ?></a>
					<?php } ?>
				</span>
			</div>
			
			<div class="social-icons">
				<?php wp_nav_menu (array ( 'theme_location' => 'social-menu', 'depth' => 0) ); ?>
			</div> <!-- .social -->
		</div> <!-- #top_bar -->
	</div> <!-- #top -->

	<div id="wrap">
		<div class="box">
			<div id="header">
				<div id="logo">
					<h1>
						<a href="<?php bloginfo('home'); ?>" title="<?php bloginfo('title'); ?>"><?php if ( harvest_option( 'logo' ) <> "" ) { ?><img src="<?php echo harvest_option( 'logo' ); ?>" alt="logo" /><?php } else { ?><?php bloginfo('title'); ?><?php } ?></a>
					</h1>    
				</div> <!-- #logo -->

				<div><a href="#header_nav" class="menu-link">Menu...<span id="caret" class="icon-caret-down icon-2x right" aria-hidden="true"></span></a></div>
				<nav id="header_nav" class="navigation">
					<?php wp_nav_menu (array ( 'theme_location' => 'main-nav-menu') ); ?>
				</nav> <!-- #header_right.navigation -->

			</div> <!-- #header -->

<?php
	if (function_exists('harvest_breadcrumb')) { harvest_breadcrumb(); }
?>
			