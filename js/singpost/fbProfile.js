jQuery(document).ready(function($){
    $("#frmFBprofiler").validate({
    	errorLabelContainer : "#error_msg2",
		rules: 
		{
			mm:{required: true,maxlength:2,minlength:2,number: true,max: 12,min: 01},
			dd:{required: true,maxlength:2,minlength:2,number: true,max: 31,min: 01,},
			yy:{required: true,maxlength:4,minlength:4,max: 2004,min: 1900,number: true}
		},	
		messages:{
			mm:{
				required:"Please specify your birth month",
				maxlength:"Maximum of 2 characters for birth month",
				minlength:"Maximum of 2 characters for birth month",
				number:"Please enter numbers only",
				max:"Please define your birth month from 01  - 12 only",
				min:"Please define your birth month from 01  - 12 only"
			},
			dd:{
				required:"Please specify your birth day",
				maxlength:"Maximum of 2 characters for birth day",
				minlength:"Maximum of 2 characters for birth day",
				number:"Please enter numbers only",
				max:"Please define your birth month from 01  - 31 only",
				min:"Please define your birth month from 01  - 31 only"
			},
			yy:{
				required:"Please specify your birth year",
				maxlength:"Maximum of 4 characters for birth year",
				minlength:"Maximum of 4 characters for birth year",
				number:"Please enter numbers only",
				min:"Please define your birth year from 1900  - 2004 only",
				max:"Please define your birth year from 1900  - 2004 only"
			}
		},submitHandler: function(){updateFbProfile();},
	});// $("#frmPassword").validate({
	
	$(document).on('click','.submit-btn',function(event){
		var others = ($('input[name=others]:checked').is(':checked')) ? 1 : 0;
		var country_value = ($('input[name=country_value]').is(':checked') ) ? 1 : 0;
		if(others > 0){
			$("#country").rules("add", {
				required: true,
				messages: {
					required: "Please specify your country"
				}
			});
			$("input[name=country_value]").rules("remove", "required");
		}
		else{
			$("input[name=country_value]").rules("add", {
				required: true,
				messages: {
					required: "Please specify your country"
				}
			});
		}
	});
    
	jQuery.validator.addMethod("tcrequired", jQuery.validator.methods.required,"Terms and Condition is required");
	jQuery.validator.addClassRules('terms_and_condition', {
        tcrequired: true
    });
	function updateFbProfile()
	{
		var month = $("#mm").val();
		var day = $("#dd").val();
		var year = $("#yy").val();		
		// var tnc = $("#profile_0").val();
		// var singpost_special = $("#profile_1").val();
		// var third_party = $("#profile_2").val();
		var webform_id = $("#webform_id").val();
		
		var singpost_special = ($('#profile_1').is(':checked')) ? $("#profile_1").val() : '';
		var third_party = ($('#profile_2').is(':checked')) ? $("#profile_2").val() : '';
		var tnc = ($('#profile_0').is(':checked')) ? $("#profile_0").val() : '';
		
		//date of birth yy-mm-dd
		var dob = year+'-'+month+'-'+day;
		var country = $("#country").val();
		$("#popup").show();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: '/profile/action/updateFacebookUsersPost',
			data:{country:country,dob:dob,tnc:tnc,singpost_special:singpost_special,third_party:third_party,webform_id:webform_id},
			success: function(data)
           {
           		$("#popup").hide();
           		if(data.response == 'error'){
           			$("#updateCountry").show();
           			$(".error_msg2").text(data.result).show();
           		}else{
					window.location.replace("/profile/index/");
           			$("#updateCountry").hide();	
           		}
           }
         });
	}
	
	var countryFlag = 2;
	$(document).on('click','#others',function(){
		//uncheck the dummy and orig
		$("input[name=country_value]").prop('checked', false);
		//show dropdown and the dummy
		$("#country_dropdown").show();
		$("input[type=radio]").removeClass('error');
		countryFlag = 0;
	});
	$(document).on('click','input[name=country_value]',function(){
		//hide the dummy and display the original
		//uncheck the others and hide the dropdown
		var val = $(this).val();
		$("#others").prop('checked', false);
		$("#country_dropdown").css({ display: "none" });
		$('#country').val(val);
		$("input[type=radio]").removeClass('error');
		countryFlag = 1;
	});
});