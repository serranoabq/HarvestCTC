jQuery(document).ready( function() {
	$(".wrap h3").each( function() {
		//var section = jQuery.inArray( $(this).html(), sections );
		var section = $(this).html();
		var slug = sections[section];
		$(this).next().wrap("<div class=\'tab-panel\' id=\'" + slug + "\'></div>")
		$("#" + slug ).prepend("<h3>" + section + "</h3>");
		$(this).remove();
	});
	$(".options-tabs").responsiveTabs();
	
	tabs_to_accordion();
	jQuery( window ).resize( function(){ tabs_to_accordion(); } );
	
	$("input[type=text],  input[type=url], input[type=email], textarea").each( function() {
		if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "" )
			$(this).css("color", "#999");
	});
	
	$("input[type=text], input[type=url], input[type=email], textarea").focus(function() {
		if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
			$(this).val("");
			$(this).css("color", "#000");
		}
	}).blur(function() {
		if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
			$(this).val($(this).attr("placeholder"));
			$(this).css("color", "#999");
		}
	});
	
	if ($.browser.mozilla) $("form").attr("autocomplete", "off");
			
} );

function tabs_to_accordion(){
	jQuery( '.option-tabs' ).each( function(){ 
		cww = jQuery( this ).width();
		if ( cww < 512 ) 
			jQuery(  this ).addClass( 'accordion' );
		else
			jQuery( this ).removeClass( 'accordion' );
	} );
}
