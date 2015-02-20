<?php
	// HELPER: Display
	
	// Breadcrumb function
	function harvest_breadcrumb( $min_parents = 1, $cpt_or_taxs = array() ) {
		global $post;
		// min_parents is the number of ancestors that a page must have to show the breadcrumbs
		// 0 = always show breadcrumbs
		
		// breadcrumb delimiter >>
		$delimiter = '<span class="delimiter"> &raquo; </span>';
		
		if ( is_page() ) {
			// For pages, we just need their ancestors
			$parents = array_reverse(get_post_ancestors($post -> ID));
		} elseif( !empty( $cpt_or_taxs ) ) {
			foreach( $cpt_or_taxs as $cpt ) {
				$tax = '';
				if( is_singular( $cpt ) ){
					$pages= harvest_get_pages_with_template( 'single-' . $cpt . '.php' );
					break;
				} elseif( is_tax( $cpt ) ) {
					$pages= harvest_get_pages_with_template( 'taxonomy-' . $cpt . '.php' );
					$tax = $cpt;
					break;
				}
			}
			
			if( $pages ){
				$page = $pages[0];
				$parents = array_reverse( get_post_ancestors( $page ) );
				$parents[] = $page->ID;
			}
		}
		
		// we are only concerned with sub-level pages
		if( $parents && count( $parents ) >= $min_parents ){
			// enclose in a classed div for easier styling later on
			echo '<div id="breadcrumb">';
			// loop over every ancestor to get its title and link
			foreach( $parents as $pageid ){
				$page = get_post( $pageid );
				$title = $page -> post_title;
				echo '<a href="' . get_permalink( $page ) . '">'. $title . '</a>' . $delimiter;
			}
			if( !empty( $tax ) && is_tax( $tax ) ) {
				$term = get_queried_object();
				$title = $term->name;
			} else {
				$title = get_the_title();
			}
			echo $title;
			echo '</div>';
		}
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
	function harvest_pagination($pages = '', $range = 2){
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

	add_action( 'admin_init', 'harvest_add_color_metabox' );
	add_action( 'admin_enqueue_scripts', 'harvest_colorpicker_script' );
	function harvest_add_color_metabox() {
		$meta_box = array(

			// Meta Box
			'id' 		=> 'post_accent_color', // unique ID
			'title' 	=> __( 'Accent Color ', 'harvest' ),
			'post_type'	=> 'page',
			'context'	=> 'side', 
			'priority'	=> 'low', 

			// Fields
			'fields' => array(
				'_post_accent_color' => array(
					'name'				=> __( 'Accent Color', 'harvest' ),
					'desc'				=> __( 'Choose an accent color to use on this page.', 'harvest' ), 
					'type'				=> 'colorpicker', 
					'default'			=> harvest_option( 'accent', '#006f7c' ), 
					'no_empty'			=> true, 
					'class'				=> 'color-picker ctmb-small', // class(es) to add to input (try ctmb-medium, ctmb-small, ctmb-tiny)
					'field_class'		=> '', // class(es) to add to field container
					'custom_field'		=> 'harvest_colorpicker', 
				),
			),
		);
		
		// Add Meta Box
		new CT_Meta_Box( $meta_box );
		
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
	