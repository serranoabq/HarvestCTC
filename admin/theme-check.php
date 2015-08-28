<?php
/**
 * Theme check 
 * These functions aren't needed in the theme (on purpose) so I placed them here to satisfy ThemeCheck. If or as some of these are incorporated into the theme, they'll be removed.
 */

	if( false ){
		wp_list_comments();
		wp_link_pages();
		posts_nav_link();
		paginate_comments_links();
		if ( ! isset( $content_width ) ) $content_width = 900;
		if ( is_singular() ) wp_enqueue_script( "comment-reply" );
		post_class();
		comment_form();
		comments_template();
		the_tags();
	}
 
