jQuery.noConflict();
jQuery(document).ready(function($){
	event.prevendefault();
	$("#frmRegistration").validate({
		rules: 
		{
			full_name: {required: true},
			email: {required: true},
			password:{required: true},
			confirmation:{required: true},
			month:{required: true},
			day:{required: true},
			year:{required: true},
			gender:{required: true}
		},	
		errorPlacement: function(error,element) {
			return true;
		},
		invalidHandler: function(form, validator)
		{
			var errors = validator.numberOfInvalids();if (errors) 
			{
				 $(".error_msg").fadeIn();
			}
		}
	});// $("#frmPassword").validate({
	
	//country js
	$('#country').prop('disabled', false);
	$("#others input:radio").click(function(){
		//uncheck the dummy and orig
		$("input[name=country_value]").prop('checked', false);
		//show dropdown and the dummy
		$("#country").show();
	});
	
	$("input[name=country_value]").click(function(){
		//hide the dummy and display the original
		//uncheck the others and hide the dropdown
		var val = $(this).val();
		$("#others input:radio").prop('checked', false);
		$("#country_dropdown").css({ display: "none" });
		//$('#country').val(val);
		$('#country').val(val);
		$("input[name=country_id]").val(val);
		$("#country").hide();
		$('input[name=country_value]').removeClass('error');
	});
	
	//validate date inputs
	$(document).on('click','.button',function(event){
		var month = $("#month").val();
		var day = $("#day").val();
		var year= $("#year").val();
		//var country_value = $('input[name=country_value]:checked').length;
		if(month == '' || day == '' || year == ''){}
		else if((month < 1) || (month > 12) || (day > 31) || (day < 1) || (year < 1900) || (year > 2004)){
			 $(".error_msg2").text("Please specify your birthdate correctly").fadeIn();
		}else{
			$(".error_msg2").hide();
			$(".error_msg2").text("");
		}
		
		//check if the user select the empty in dropdown
		if($('input[name=others]').is(':checked')){
			 $(".error_msg").fadeIn();
		}else if(!country_value > 0){
			//do here the validation of the country by flags
			$('input[name=country_value]').addClass('error');
			$(".error_msg").fadeIn();
			var webform_14;
			webform_14.abort();
		}
	});
});//end of document ready