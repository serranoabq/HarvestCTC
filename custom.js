/* Custom scripts for themes */
jQuery(document).ready(function($) {
  /* the Responsive menu script */
 	//$('body').addClass('js');
	var menu = $('#header_nav > div'),
			menulink = $('.menu-link');
			caret=$('#caret');
			
	menulink.click(function(e) {
			e.preventDefault();
			caret.toggleClass('icon-caret-up');
			menu.toggleClass('active');
	});

});