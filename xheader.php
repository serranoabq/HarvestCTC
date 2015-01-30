<!DOCTYPE html>

<!--[if lte IE 8]> <html class="ie ie6 <?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 7]>     <html class="ie ie7 <?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 8]>     <html class="ie ie8 <?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 9]>     <html class="ie ie9 <?php language_attributes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	
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
	
<style>
.container{margin:0 auto; max-width:940px}
.container:before,.container:after,.row:before,.row:after{display:table;content:' ';}
.row{ margin:0 -2px;}
.col{float:left;padding:10px;}
.col-1-3, .col-2-6{width:33.3333333%;}
.col-2-3, .col-4-6{width:66.6666666%;}
.col-1-2, .col-3-6, .col-2-4{width:50%;}
.col-1-4{width:25%;}
.col-3-4{width:75%;}
#footer_info{background:#ccc;padding:10px 0;}
#logo{}
#header_nav_mobile{}
#header_nav {}
.navigation {}
.menu-link {}
#hamburger {}

</style>
	<div class="container">
		<div class="row">
			<div class="col col-1-3">
				<div id="logo">
					<h1>
						<a href="<?php bloginfo('home'); ?>" title="<?php bloginfo('title'); ?>">
<?php if ( harvest_option( 'logo' ) <> "" ) { ?>
							<img src="<?php echo harvest_option( 'logo' ); ?>" alt="logo" />
<?php } else { ?>
							<?php bloginfo('title'); ?>
<?php } ?>
						</a>
					</h1>    
				</div> <!-- #logo -->
			</div> <!-- .col .col-1-3 -->
			<div class="col col-2-3">
				<div id="header_nav_mobile">
					<a href="#header_nav" class="menu-link" title="Menu"><span id="hamburger" class="fa-navicon fa-2x" aria-hidden="true"></span></a>
				</div><!-- #header_nav_mobile -->
				<div id="header_nav" class="navigation">
					<?php wp_nav_menu (array ( 'theme_location' => 'main-nav-menu') ); ?>
				</div> <!-- #header_nav -->
			</div> <!-- .col .col-2-3 -->
		</div> <!-- .row -->
	</div> <!-- .container -->

