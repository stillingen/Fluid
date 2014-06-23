jQuery(function( $ ){
	$(window).scroll(function() {
		var yPos = ( $(window).scrollTop() );
		if(yPos > 200) { // show sticky menu after screen has scrolled down 200px from the top
			$(".nav-secondary").fadeIn();
		} else {
			$(".nav-secondary").fadeOut();
		}
	});
});