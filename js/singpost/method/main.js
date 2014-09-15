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
//ends
});

$(window).load(function(){
$('#home').on('click', function ( e ) {
    $.fn.custombox( this, {
        effect:         'fade',
        position:       'center',
        customClass:    'customslide',
        speed:          200,
        overlayClose:	false
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