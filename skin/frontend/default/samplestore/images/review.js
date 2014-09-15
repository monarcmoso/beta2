var js = $.noConflict();
js(document).ready(function(){
	
	js(document).on('click','.btn',function(){
	    js.fn.custombox(this, {
	        effect:         'blur',
	        position:       'center',
	        customClass:    'customslide',
	        speed:          200
	    });
	});
	js(document).on('click','.review_close',function(){
		js.fn.custombox('close');
	});
	
	//tabs
	js('#tab-wrapper').easyTabs({defaultContent:1});
	js('#tab-wrapper2').easyTabs({defaultContent:1});

	
}); // end of document ready