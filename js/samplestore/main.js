
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
$('#modal').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'blur',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});

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
$('#home').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'fade',
        position:       'center',
        customClass:    'customslide',
        speed:          200
    });
    e.preventDefault();
});

//sticky ads
	var s = $("#sticker");
	var pos = s.position();					   
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		//s.html("Distance from top:" + pos.top + "<br />Scroll position: " + windowpos);
		if (windowpos >= pos.top) {
			s.addClass("stick");
		} else {
			s.removeClass("stick");	
		}
	});

//animate on hover

//animationHover('h1.logo','tada')
animationHover('nav ul li.dropdown1','fadeIn')
animationHover('nav ul li.dropdown2','fadeIn')
animationHover('nav ul.secondary-menu li.logged-in','fadeIn')

function animationHover(element, animation){
  element = $(element);
  element.hover(
    function() {
      element.addClass('animated ' + animation);
    },
    function(){
      //wait for animation to finish before removing classes
      window.setTimeout( function(){
        element.removeClass('animated ' + animation);
      }, 2000);
    }
  );
};
});

function animationClick(element, animation){
  element = $(element);
  element.click(
    function() {
      element.addClass('animated ' + animation);
      //wait for animation to finish before removing classes
      window.setTimeout( function(){
          element.removeClass('animated ' + animation);
      }, 2000);
    }
  );
};

});

