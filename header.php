<?php get_template_part('templates/head'); ?>

<body <?php body_class( harvest_page_slug() ); ?>>

	<div id="top">
		<div id="top_bar">
			<div id="topheadline">
				<span id="headline">
					<?php if( harvest_option( 'headline' ) ) echo stripslashes( harvest_option( 'headline' )); else bloginfo( 'description' );?>
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

				<div id="header_nav_link">
					<a href="#header_nav" class="menu-link">Menu...<span id="caret" class="icon-caret-down icon-2x right" aria-hidden="true"></span></a>
				</div><!-- #header_nav_link -->
				<nav id="header_nav" class="navigation">
					<?php wp_nav_menu (array ( 'theme_location' => 'main-nav-menu') ); ?>
				</nav> <!-- #header_nav -->

			</div> <!-- #header -->

<?php
	if (function_exists('harvest_breadcrumb')) { harvest_breadcrumb(); }
?>
			