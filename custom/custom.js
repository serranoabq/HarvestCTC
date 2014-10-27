/* 
CUSTOM JAVASCRIPT
---------------------

Instructions:

Add your custom script here instead. To execute code at runtime, add it 
to the document.ready function below. Other functions can go below the 
specified line.

*/

jQuery(document).ready(function($) {
  
	/* the Responsive menu script */
 	var menu = $('#header_nav > div'),
			menulink = $('.menu-link');
			caret=$('#caret');
			
	menulink.click(function(e) {
			e.preventDefault();
			caret.toggleClass('icon-caret-up');
			menu.toggleClass('active');
	});

	
	// Add custom javascript to execute at DOMready below this line 
	
});

// Add custom Javascript functions below this line 
