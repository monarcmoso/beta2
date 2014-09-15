
//scroll up
$(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        }); 
 
        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 1500);
            return false;
        });
		

//Show Hide
function showHide(shID) {
   if (document.getElementById(shID)) {
      if (document.getElementById(shID+'-show').style.display != 'none') {
         document.getElementById(shID+'-show').style.display = 'none';
         document.getElementById(shID).style.display = 'block';
      }
      else {
         document.getElementById(shID+'-show').style.display = 'inline';
         document.getElementById(shID).style.display = 'none';
      }
   }

}

$(function() {
//accordian menu sidebar

  $('#cssmenu > ul > li:has(ul)').addClass("has-sub");

  $('#cssmenu > ul > li > a').click(function() {
    var checkElement = $(this).next();
    
    $('#cssmenu li').removeClass('active');
    $(this).closest('li').addClass('active');	
    
    
    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
      $(this).closest('li').removeClass('active');
      checkElement.slideUp('normal');
    }
    
    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
      $('#cssmenu ul ul:visible').slideUp('normal');
      checkElement.slideDown('normal');
    }
    
    if (checkElement.is('ul')) {
      return false;
    } else {
      return true;	
    }		
  });


jQuery(document).ready(function($){

//faq toggle answers
 $('#faq h2').click(function() {
                
    $(this).next('.answer').slideToggle(500);
    $(this).toggleClass('close2');
                
  });

//tabs
$('#tab-wrapper').easyTabs({defaultContent:1});
$('#tab-wrapper2').easyTabs({defaultContent:1});

//custom upload file button
$(":file").jfilestyle({input: false});

//modalbox
$('#modal1').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal2').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal3').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal4').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal5').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal6').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal7').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#modal8').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit1').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit2').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit3').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit4').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit5').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit6').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$('#edit7').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
//modalbox
$('#cart-coupon').click(function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
//sticky ad
function fixDiv() {
  var $cache = $('#sticker'); 
  if ($(window).scrollTop() > 410) 
    $cache.css({'position': 'fixed', 'top': '0px','left': '0px', 'background-color':'#fff', 'z-index': '9000'}); 
  else
    $cache.css({'position': 'relative', 'top': 'auto'});
}
$(window).scroll(fixDiv);
fixDiv();

//dropdown menu effect
 $( '.dropdown' ).hover(
	function(){
    	$(this).children('.sub-menu').slideDown(120);
    },
    function(){
    	$(this).children('.sub-menu').slideUp(120);
    }
 );

 $( '.logged-in' ).hover(
	function(){
    	$(this).children('.sub-menu').slideDown(120);
    },
    function(){
    	$(this).children('.sub-menu').slideUp(120);
    }
 );
 
 
 //index more/less button
 $('#category-toggle').on('click', function(e){
	e.stopPropagation();e.preventDefault();
	  if(!$("#category-container").hasClass("expanded")){
		$("#category-container").addClass("expanded");
		$(this).addClass("expanded");
	  } else {
		$("#category-container").removeClass("expanded");
		$(this).removeClass("expanded");
	  }
 });

});


//ends
});

$(window).load(function(){
$('#home').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'fade',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});
$("#home").trigger('click');

$(function() {
    $('.banner').unslider({
		fluid: false,
		dots: true,
		speed: 500,
		slideTransitionEffect: 'fade'
		});
	});

});