jQuery(document).ready( function($) {
	$( '#hamburger' ).click( function() {
		if( $( this ).hasClass( 'menu-active') ) {
			$( '.wrapper' ).removeClass( 'menu-active' );
			$( this ).removeClass( 'menu-active' );
		} else {
			$( '.wrapper' ).addClass( 'menu-active' );
			$( this ).addClass( 'menu-active' );
		}
		
	});
	
	/* Make the heading title responsive on load */
	var th = $( '.title_wrap' ).height();
	var hh = $( '.title h2' ).height();
	var h = 1.3;
	while(th<hh){
		h = h - 0.1;
		$( '.title h2' ).css('font-size', h + 'em')
		th = $( '.title_wrap' ).height();
		hh = $( '.title h2' ).height();			
	}
});