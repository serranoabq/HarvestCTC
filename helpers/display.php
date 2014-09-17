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

	// Excerpt settings
	add_filter('excerpt_length', 'harvest_excerpt_length');
	function harvest_excerpt_length($length) {
		return 100;
	}

	// Add trailing ellipsis when trimming long text
	function string_limit_words($string, $word_limit){
		$words = explode(' ', $string, ($word_limit+ 1));
		if(count($words) > $word_limit) {
			array_pop($words);
			//add a ... at last article when more than limitword count
			echo implode(' ', $words)."..."; } 
		else {
			//otherwise
			echo implode(' ', $words); 
		}
	}
	
	// Retrieve pages by their template
	function harvest_get_pages_with_template($template){
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $template,
			'hierarchical' => 0
		));
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

?>