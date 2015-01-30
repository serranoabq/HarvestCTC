jQuery(document).ready( function($) {
	$(".wrap h3").each( function() {
		var section = $(this).html();
		var slug = sections[section];
		$(this).next().wrap("<div class=\'tab-panel\' id=\'" + slug + "\'></div>")
		//$("#" + slug ).prepend("<h3>" + section + "</h3>");
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
		
	// Uploader stuff
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	$(".remove_button").click(function(e){
		targetfield = $(this).prev().prev(".upload-url");
		console.log(targetfield);
		targetfield.val("");
		$(targetfield).nextAll("div:first").find(".upload-preview").css("display","none").attr("src","");
		
	});
	$(".upload_button").click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		targetfield = $(this).prev(".upload-url");
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				targetfield.val(attachment.url);
				$(targetfield).nextAll("div:first").find(".upload-preview").attr("src",attachment.url).css("display","inline");
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}

		wp.media.editor.open(button);
		return false;
	});
	
} );

function tabs_to_accordion(){
	jQuery( '.options-tabs' ).each( function(){ 
		cww = jQuery( this ).width();
		if ( cww < 640 ) 
			jQuery(  this ).addClass( 'accordion' );
		else
			jQuery( this ).removeClass( 'accordion' );
	} );
}
