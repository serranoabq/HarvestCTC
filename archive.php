<?php

get_header(); 
$title = __( 'Archives', 'harvestctc' );
if ( 'ctc_event' == get_post_type() ) $title = __( 'Events', 'harvestctc' );
if ( 'ctc_location' == get_post_type() ) $title = __( 'Locations', 'harvestctc' );
if ( 'ctc_sermon' == get_post_type() ) $title = __( 'Messages', 'harvestctc' );
if ( ! have_posts() ) :
	$title = __( 'Nothing found' );
else:

?>
		<!-- TITLE BAR -->
		<div class="title_wrap">
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php echo $title; ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>

		<div class="content_wrap">

			<div class="grid-container content">
<?php  
		while ( have_posts() ) : the_post();
			get_template_part( 'content', get_post_format() );
		endwhile;
	endif;
?>
		
			</div> <!-- .content.grid-container -->
		</div> <!-- .content_wrap -->
		<!-- END CONTENT -->

<?php

get_footer();
	
