<?php 
/**
 * HEADER
 */
?>
<!--[if lte IE 8]> <html class="ie ie6 <?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 7]>     <html class="ie ie7 <?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 8]>     <html class="ie ie8 <?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 9]>     <html class="ie ie9 <?php language_attributes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> > <!--<![endif]-->
<head>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="<?php echo get_bloginfo( 'description' ); ?>">
	<meta name="author" content="<?php echo get_bloginfo( 'name' ); ?>">
	
<?php if ( harvest_option( 'favicon' ) <> "" ) { ?>
	<link rel="shortcut icon" href="<?php echo harvest_option( 'favicon' )  ?> "/>
<?php }?>
 
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_bloginfo_rss( 'rss2_url' ); ?>" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
<?php wp_head(); ?>
	
</head>

<body <?php body_class( harvest_page_slug() ); ?>>

	<div class="wrapper">
		<header class="header_wrap">
			<div class="grid-container top">
				
				<div class="grid-33" id="logo">&nbsp;
				
					<a href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'name' ); ?>">
<?php if ( harvest_option( 'logo' ) <> "" ) : ?>
						<img src="<?php echo harvest_option( 'logo' ); ?>" alt="logo" />
<?php else: ?>
						<span class="name"><?php bloginfo( 'name' ); ?></span>
<?php endif; ?>
					</a>
				
				</div> <!-- #logo.grid-33 -->
				
				<div class="grid-66">
					
					<nav id="header_nav_mobile">
						<a href="#header_nav" class="menu-link" title="<?php _e( 'Menu', 'harvest' ); ?>">
							<span id="hamburger" class="fa fa-navicon fa-2x" aria-hidden="true"></span>
						</a>
					</nav><!-- #header_nav_mobile -->
					
					<nav id="header_nav" class="nav-main">
							<?php wp_nav_menu (array ( 'theme_location' => 'header-menu' ) ); ?>
					</nav> <!-- #header_nav -->
					
				</div> <!-- .grid-66 -->
			</div> <!-- .top -->
		</header> <!-- .header-wrap -->
		