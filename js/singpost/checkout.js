//jQuery.noConflict();
jQuery(document).ready(function($){
	//this is the event for the fancybox
        
	$( "#updateInfo").click(function() {
           var mobile_num='123456';
            $.ajax({
                    url: '/checkout/onepage/billingInfo',
                    type: "get",
                        data:{mobile_num:mobile_num},
                    dataType: 'json',
                    success: function(data) {
                            
                                $(".c_fullname").html(" Recipient Name : "+data.fullname);
                                $(".c_address").html("Shipping Address : "+data.street);
                                $(".c_postcode").html("Postal Code : "+data.postal_code);
                                $(".c_mobile").html("Mobile No.: "+data.mobile_no);
                    }//end  success: function(data) {
                });//end of ajax*/
        });
	//edit mobile
	$(document).on('click','#updateMobile',function(event){
            
		$("#frmMobile").validate({
                    
			rules: 
			{
				mobile_num: {required: true,maxlength:8, minlength:8,number: true},
			},
			messages: 
			{
				mobile_num:{
					required : "Please insert your new mobile number.",
					maxlength : "Please insert 8 digits only for your Singapore registered mobile no.",
					minlength : "Please insert 8 digits only for your Singapore registered mobile no.",
					number : "Please insert numbers only."
				}
			},submitHandler: function(){changeMobile();}
		});// $("#frmEmail").validate({	
		function changeMobile()
		{   
			var mobile_num = $("#mobile_num").val();
			$("#updateMobile").attr('disabled','disabled');
			$("#popup").show();
			$.ajax({
			    url: '/onetimepassword/send/otp',
			    type: "post",
			    data:{mobile_num:mobile_num},
			    dataType: 'json',
			    success: function(data) {
					$(".clearfix").hide();
					if(data.response == 400){
						$("#error_msg").show().text(data.message);
						$("#updateMobile").removeAttr('disabled');
						$("#popup").hide();
					}
					else
					{	
						$("#otp_msg").text("An SMS containing the One-Time-Password(OTP) key has been sent to your phone number.");
						$("#mobile").hide();
						$("#top_otp").hide();
						$("#error_msg").hide();
						$("#otp").show();
						$("#popup").hide();
					}
			    }//end  success: function(data) {
			});//end of ajax*/
		}
	});
	
	//verify OTP
	$(document).on('click','#verifyOtp',function(event){
         
		$("#frmOtp").validate({
			rules: 
			{
				otp_key: {required: true,maxlength:6, minlength:6},
			},
			messages: 
			{
				otp_key:{
					required : "Please insert the OTP key you received.",
					maxlength : "Please insert 6 characters for the OTP key.",
					minlength : "Please insert 6 characters for the OTP key.",
				}
			},submitHandler: function(){verifyOtp();}
		});// $("#frmEmail").validate({	
		function verifyOtp()
		{
			var otp_key = $("#otp_key").val();
			var mobile_num = $("#mobile_num").val();
			$("#verifyOtp").attr('disabled','disabled');
			$("#popup").show();
			$.ajax({
			    url: '/onetimepassword/verify/otp',
			    type: "post",
			    data:{mobile_num:mobile_num,otp_key:otp_key},
			    dataType: 'json',
			    success: function(data) {
					$(".clearfix").hide();
					if(data.response == 400){
						$("#error_msg").show().text(data.message);
						$("#verifyOtp").removeAttr('disabled');
						$("#popup").hide();
					}
					else
					{	
						$("#popup").hide();
						$("#otp_message").text(data.message);
						$("#error_msg").hide();
						//close the form
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
						$("#change_mobile_form").hide();
                                                $("#shippingMethodConfirm").show();
					}
			    }//end  success: function(data) {
			});//end of ajax*/
		}
	});
	
	$(document).on('click','input[name=country_value]',function(){
		//hide the dummy and display the original
		//uncheck the others and hide the dropdown
		var val = $(this).val();
		$("#others").prop('checked', false);
		$("#country_dropdown").css({display: "none"});
		$('#country').val(val);
		$("input[type=radio]").removeClass('error');
		countryFlag = 1;
	});
	
	$(document).on('click','#updateCountry',function(e){
		$(".accountError").remove();
		if(countryFlag == 2){
			$("input[type=radio]").addClass('error');
			$(".row").eq(1).before($("<p class='accountError'>Please specify your Country.</p>"));
			return false;
		}
		else if(countryFlag == 1){
			changeCountry();
			return false;
		}
		else{
			$("#frmCountry").validate({
				rules: 
				{
					country: {required: true},
				},
				messages: 
				{
					mobile_num:"Please Specify Your Country"
				},submitHandler: function(){changeCountry();}
			});// $("#frmEmail").validate({		
		}
	});
	//edit shipping
	$(document).on('click','#updateShipping',function(e){
		$("#frmShipping").validate({
			rules: 
			{
				shipping_address: {required: true, maxlength:250},
			},
			messages: 
			{
				shipping_address:{
					required:"Please Specify Your Shipping Address.",
					maxlength: "You have exceeded on the maximum of 250 characters."
				}
			},submitHandler: function(){changeAddress();}
		});// $("#frmEmail").validate({
		function changeAddress()
		{
			var address = $("#shipping_address").val();
			$.ajax({
			    url: '/profile/action/changeAddressPost',
			    type: "post",
			    data:{address:address},
			    dataType: 'json',
			    success: function(data) {
					if(data.result != 'error'){
						var length = data.message.length;
						var i;
						for(i = 0; i<length; i++)
						{
							$("#error_msg").show().text(data.message[i]);
							setTimeout(function() {
								$.fn.custombox('close');
							}, 2000);
						}
					}//if(data.result == 'error')
					else
					{	
						$("#error_msg").show().text(data.message);
						//close the form
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
					window.location.href = "/profile/index/settings/";
					//$(".profile-index-settings").load("settingsAjaxLoad");
			    }//end  success: function(data) {
			});//end of ajax*/	
		}
	});
	
});//end of document ready
 