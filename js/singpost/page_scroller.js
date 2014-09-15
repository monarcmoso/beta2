var js = $.noConflict();
js(document).ready(function(){
	js(document).on('click','.scrollup',function(){
	  js("html, body").animate({ scrollTop: 0 }, "slow");
	  return false;
	});
	
	js(window).scroll(function(){
		if(js(this).scrollTop() > 100) {
		    js('.scrollup').fadeIn();
		} else {
			js('.scrollup').fadeOut();
		}
	}); 
}); 