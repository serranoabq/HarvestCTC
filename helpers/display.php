<?php
	// HELPER: Display

	// Register livestream
	wp_embed_register_handler( 'livestream', '#(http|https)://livestream\.com/accounts/([\d]+)/events/([\d]+)/videos/([\d]+)/player?
	width=([\d]+)&height=([\d]+)&autoPlay=(true|false)&mute=(true|false)#i', 'wp_embed_handler_livestream' );

function wp_embed_handler_livestream( $matches, $attr, $url, $rawattr ) {

	$embed = sprintf(
			'<div class="video-container"><iframe src="%1$s://livestream\.com/accounts/%2$s/events/%3$s/videos/%4$s/player?
	width=%5$s&height=%6$s&autoPlay=%7$s&mute=%8$s" frameborder="0" scrolling="no" marginwidth="0" marginheight="0"></iframe></div>',
			esc_attr($matches[1]),
			esc_attr($matches[2]),
			esc_attr($matches[3]),
			esc_attr($matches[4]),
			esc_attr($matches[5]),
			esc_attr($matches[6]),
			esc_attr($matches[7]),
			esc_attr($matches[8])
			);

	return apply_filters( 'embed_livestream', $embed, $matches, $attr, $url, $rawattr );
}

	// Modify the loop for Sermon archives
	add_action( '__before_loop', 'harvest_ctc_sermon_pre_loop');
	function harvest_ctc_sermon_pre_loop() {
		global $wp_query;
		$query_term = $wp_query->query;

		if( 'ctc_sermon' != $query_term['post_type'] ) return;
		
		$args = array(
			'order'   => 'DESC',
			'orderby' => 'date',
			'posts_per_page' => 9,
		);
		
		$topic_option = ctcex_get_option( 'ctc-sermon-topic' );
		if( !empty( $topic_option ) && ! isset( $qv['ctc_sermon_topic' ] ) ) {
			// Set the first location as the default
			$locs = get_terms( 'ctc_sermon_topic', array( 'order_by' => 'id', 'order' => 'DESC') );
			$min_loc_slug = $locs[0]->slug;
			if( !empty( $min_loc_slug ) ){
				$tax_query = array(
					array(
						'taxonomy' => 'ctc_sermon_topic',
						'field'    => 'slug',
						'terms'    => $min_loc_slug
					)
				);
				$args[ 'tax_query' ] = $tax_query;
			}
		}
		
		$query_term = array_merge( $args, $query_term ); 
		$wp_query = new WP_Query( $query_term );	

	}	

	
	// Modify the loop for Event archives
	add_action( '__before_loop', 'harvest_ctc_event_pre_loop');
	function harvest_ctc_event_pre_loop() {
		global $wp_query;
		$query_term = $wp_query->query;
		$ct1 = array_key_exists( 'ctc_event_category', $query_term ) && ! empty( $query_term['ctc_event_category'] );
		$ct3 = array_key_exists( 'post_type', $query_term )  && 'ctc_event' == $query_term['post_type'];
		if( !( $ct1 || $ct3 ) ) return;
		
		$args = array(
			'order' => 'ASC',
			'orderby' => 'meta_value',
			'meta_key' => '_ctc_event_start_date_start_time',
			'meta_type' => 'DATETIME',
			'posts_per_page' => 9,
			'meta_query' => array(
				array(
					'key' => '_ctc_event_end_date_end_time',
					'value' => date_i18n( 'Y-m-d H:i:s' ), // today localized
					'compare' => '>=', // later than today
					'type' => 'DATE',
				),
			)
		);
		$query_term = array_merge( $args, $query_term ); 
		$wp_query = new WP_Query( $query_term );	

	}	

	
	// Retrieve pages by their template
	function harvest_get_pages_with_template( $template ){
		$pages = get_pages( array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $template,
			'hierarchical' => 0
		) );
		return $pages;
	}
	
	// Custom pagination display
	function harvest_pagination_old($pages = '', $range = 2){
		$showitems = ($range * 2)+1; 
		global $paged;
		if(empty($paged)) $paged = 1;

		if($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages) $pages = 1;
		}  
	echo "<!-- $pages -->";
		
		if(1 != $pages){
			echo "<div class=\"pagination\">";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
				echo "<a href='".get_pagenum_link(1)."'>&lt; First</a>";
			if($paged > 1 && $showitems < $pages) 
				echo "<a href='".get_pagenum_link($paged - 1)."'>&lt;&lt;</a>";

			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
					echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
				}	
			}

			if ($paged < $pages && $showitems < $pages) 
				echo "<a href=\"".get_pagenum_link($paged + 1)."\">&gt;&gt;</a>";
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
				echo "<a href='".get_pagenum_link($pages)."'>Last &gt;</a>";
			echo "<span class=\"numbers\">Page ".$paged." of ".$pages."</span></div>\n";
		}
	}

	// Custom pagination display
	function harvest_pagination_new(){
		global $paged, $wp_query;
		
		// Check pages
		if( empty($paged) ) $paged = 1;
		$pages = $wp_query->max_num_pages;
		if( !$pages ) $pages = 1;
		
		// Prep the arrows
		$prev_arrow = '<div class="grid-20 mobile-grid-20 secondary-accent-background"><i class="fa fa-chevron-left"></i></div>';
		$next_arrow = '<div class="grid-20 mobile-grid-20 secondary-accent-background"><i class="fa fa-chevron-right"></i></div>';
		
		// Previous
		$page_links = array();
		if( 1 == $paged ) 
			$page_links[0] = '<span class="prev page-numbers disabled">' . $prev_arrow . '</span>';
		else 
			$page_links[0] = '<a href="'. get_pagenum_link($paged-1) . '" class="prev page-numbers">' . $prev_arrow . '</a>';
		
		// Page count
		$page_links[1] = '<span class="page-numbers"><div class="grid-40 mobile-grid-40 prefix-10 suffix-10 mobile-prefix-10 mobile-suffix-10 secondary-accent-background">Page ' . $paged . ' of ' . $pages . '</div></span>';
		
		// Next
		if( $paged == $pages )
			$page_links[2] = '<span class="next page-numbers disabled">' . $next_arrow . '</span>';
		else
			$page_links[2] = '<a href="'. get_pagenum_link($paged+1) . '" class="next page-numbers">' . $next_arrow . '</a>';
			
		// Pagination is only added when needed	
		if( $pages > 1 )
			echo join('', $page_links) ;
		
	}
	
	// Remove version numbers from css & js calls
	add_filter( 'style_loader_src', 'harvest_nover', 9999 );
	add_filter( 'script_loader_src', 'harvest_nover', 9999 );
	function harvest_nover( $src ) {
			if ( strpos( $src, 'ver=' ) )
					$src = remove_query_arg( 'ver', $src );
			return $src;
	}

	// Create a different slug for pages
	function harvest_page_slug(){
		global $post;
		$page_slug = $post -> post_type == 'page' ? 'page-' : '';
		$page_slug .= $post -> post_name; 
		return $page_slug;
	}
	
	/*************************************************************
	/ Editor-related settings
	/************************************************************/
	// Allow special tags in editor
	add_filter('tiny_mce_before_init', 'harvest_change_mce_options');
	function harvest_change_mce_options($initArray) {
		$ext = 'pre[*],iframe[*],script[*],style';
		if ( isset( $initArray['extended_valid_elements'] ) ) {
			$initArray['extended_valid_elements'] .= ',' . $ext;
		} else {
			$initArray['extended_valid_elements'] = $ext;
		}
		return $initArray;
	}

	if( class_exists( 'CT_Meta_Box' ) ){
		add_action( 'admin_init', 'harvest_add_color_metabox' , 11);
		add_action( 'admin_enqueue_scripts', 'harvest_colorpicker_script', 11 );
		function harvest_add_color_metabox() {
			$meta_box = array(

				// Meta Box
				'id'        => '_post_accent_color', // unique ID
				'title'     => __( 'Accent Color ', 'harvest' ),
				'post_type' => 'page',
				'context'   => 'side', 
				'priority'  => 'low', 

				// Fields
				'fields' => array(
					'_post_accent_color' => array(
						'name'          => __( 'Accent Color', 'harvest' ),
						'desc'          => __( 'Choose an accent color to use on this page.', 'harvest' ), 
						'type'          => 'colorpicker', 
						'default'       => harvest_option( 'accent', '#006f7c' ), 
						'no_empty'      => true, 
						'class'         => 'color-picker ctmb-small', // class(es) to add to input (try ctmb-medium, ctmb-small, ctmb-tiny)
						'field_class'   => '', // class(es) to add to field container
						'custom_field'  => 'harvest_colorpicker', 
					),
				),
			);
			
			// Add Meta Box
			new CT_Meta_Box( $meta_box );
			
		}
	}
	function harvest_colorpicker_script( ) {
    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
	}
	
	function harvest_colorpicker( $data ) {
		
		$input = '<span class="color-demo" style="display: inline-block; width: 50px; background: ' . $data['esc_value'] .'">&nbsp;</span> ';
		$input .= '<input type=text" ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '" value="' . $data['esc_value'] . '" data-default-color="'. $data['field']['default'] .'" size="7" /> ';
		$input .= '<input type="button" class="color-reset button-secondary" value="Reset"/>';
		$input .= '<div class="iris"></div>';
		
		?>
		<div id="ctmb-field-<?php echo esc_attr( $data['key'] ); ?>" class="<?php echo esc_attr( $data['field_class'] ); ?>"<?php echo $data['field_attributes']; ?>>

			<div class="ctmb-name">

				<?php if ( ! empty( $data['field']['name'] ) ) : ?>

					<?php echo esc_html( $data['field']['name'] ); ?>

					<?php if ( ! empty( $data['field']['after_name'] ) ) : ?>
						<span><?php echo esc_html( $data['field']['after_name'] ); ?></span>
					<?php endif; ?>

				<?php endif; ?>

			</div>

			<div class="ctmb-value">

				<?php echo $input; ?>

				<?php if ( ! empty( $data['field']['desc'] ) ) : ?>
				<p class="description">
					<?php echo $data['field']['desc']; ?>
				</p>
				<?php endif; ?>

			</div>

		</div>
		<script>
			jQuery(document).ready( function($){
				$('.color-picker').iris({
					target: $('.iris'),
					palettes: true,
					change: function( ev, ui ) {
						$('.color-demo').css( 'background', ui.color.toString() )
					}
				});
				$('.color-reset').click( function (){
					$('.color-picker').iris('color', $('.color-picker').attr( 'data-default-color') );
					//$('.color-demo').css( 'background', )
				})
			});
		</script>
<?php		
	}
	
	// Title bar on all pages
	function harvest_title_bar( $title, $color = '' ){
		$color_style =  "style='background: $color;'"; 
?>		
		<!-- TITLE BAR -->
		<div class="title_wrap accent-background" <?php echo $color ? $color_style: ''; ?> >
			<div class="grid-container title-bar">
				<div class="grid-100 title">
					<h2><?php echo $title; ?></h2>
				</div> <!-- .title.grid-100 -->
			</div> <!-- .title-bar.grid-100 -->
		</div>

<?php		
	}

	function harvest_get_tax_dropdown( $tax ){
		if( ! in_array( $tax, array( 'ctc_sermon_topic', 'ctc_event_category' ) ) )
			return;
		
		$tags = get_terms( $tax, array( 'hide_empty' => 1 ) );
		foreach ($tags as $option) {
			$a_tags[] = sprintf( '<option value="%s">%s</option>', get_term_link( intval( $option->term_id ), $tax ), $option->name );
		}
		if( $a_tags ) { 
			$title = explode( '/', harvest_option( str_replace( '_', '-', $tax ) , __( 'Topic', 'harvest' ) ) );
			$title = array_pop( $title );
			if( 'ctc_event_category' == $tax ) $title = 'Category';
			
			array_unshift( $a_tags, sprintf( '<option value="">' . _x( 'Choose a %s', 'Dropdown category instructions', 'harvest' ) . '</option>', $title ) );
			$s_tags = implode('', $a_tags);
	?>	
					<!-- Category dropdown -->
					<div class="grid-100 <?php echo $tax .'-select'; ?> ctc-category-dropdown" style="text-align: right; padding-bottom: 20px"><select onChange="window.location = jQuery(this).find('option:selected').val();">
					<?php echo $s_tags; ?>
					</select></div>
	<?php 
		}
	}
