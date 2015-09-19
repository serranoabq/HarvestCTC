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
	
	/* Make the sermon-name-views responsive */
	var gridh = parseInt($('.ctc-sermon-grid .ctc-sermon').css('padding-bottom')) * 0.8;
	var hh = $( '.ctc-sermon-grid .ctc-grid-full h1' ).height();
	var h = 1.5;
	while( gridh  < hh ){
		h = h - 0.1;
		$( '.ctc-sermon-grid .ctc-grid-full h1' ).css('font-size', h + 'em')
		hh = $( '.ctc-sermon-grid .ctc-grid-full h1' ).height();			
	}
	
	try {
		var colorThief = new ColorThief();
		var rgb = colorThief.getColor( $('.single img[class$="-img"][class^="ctc-"]')[0] );
		ctr = rgbVa ( rgb );
		
		$( '.accent-background' ).css( 'transition', 'background-color 3s' );
		
		$( '.accent-background' ).css( 'background-color', 'rgb(' + rgb.join(',') + ')' );
		$('.single img[class$="-img"][class^="ctc-"]').css( 'box-shadow', '0 0 5px rgb(' + rgb.join(',') + ')' );
		$( '.accent-background h2' ).css( 'color', ctr > 200 ? '#333' : 'white' );
	}catch (e){
		//console.log(e);
	}
	
	function rgbVa( rgb ){
		return ((rgb[0]*299)+(rgb[1]*587)+(rgb[2]*114))/1000;
	}
});

