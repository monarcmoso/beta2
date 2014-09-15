var js = $.noConflict();
js(document).ready(function(){
	
	js(document).on('click','.btn',function(){
	    js.fn.custombox(this, {
	        effect:         'blur',
	        position:       'center',
	        customClass:    'customslide',
	        speed:          200,
	        height:			800
	    });
	});
	
	js(document).on('click','.paging_reviews',function(){
		var attr = js("#attrVal").val();
		var rel = js(this).attr("rel");
		//show popup
		js("#popup").show();
		//slide to the div
		js('html, body').animate({
			scrollTop: js('#tabReview').offset().top
		}, 'slow');
		//load page
		var url = "/voice/paging/_pageAjax/page/"+rel+"/attr/"+attr;
		 js("#tabReview").load(url, function() {
			js("#popup").hide();
		});
	});
	
	js(document).on('click','.review_close',function(){
		js.fn.custombox('close');
	});
	
	//js(document).on('click','#reviewClick',function(){
	
	//tabs
	js('#tab-wrapper').easyTabs();
	js('#tab-wrapper2').easyTabs();
	
	js("#reviewClick").on("click", function () {
		var attr = js("#attrVal").val();
		//show popup
		js("#popup").show();
		//slide to the div
		js('html, body').animate({
			scrollTop: js('#tabReview').offset().top
		}, 'slow');
		//load page
		var url = "/voice/paging/_pageAjax/page/0/attr/"+attr;
		 js("#tabReview").load(url, function() {
			js("#popup").hide();
		});
	});
	
}); // end of document ready