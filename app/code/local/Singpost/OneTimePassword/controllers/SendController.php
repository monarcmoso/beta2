<?php
class Singpost_OneTimePassword_SendController extends Mage_Core_Controller_Front_Action
{
	public $mobile_num = '';
	public $otp_key = '';
	
	public function indexAction()
	{
		print_r(array('result'=>$this->_model()->_getMobileSpammer('+6583077224')));
		exit;
		$mobile = "+6583077224";
		$a = $this->_model()->_getMobile($mobile);
		echo json_encode(array('result'=>$a));
		exit;
		//echo $this->_messageSending();
		
		//echo $this->_generateOtpKey();
		//get the token of the SMS
		$token = $this->_requestToken();
		//decode the $token.
		$token_decode = json_decode($token);
		$access_token =  $token_decode->access_token;
		$token_type = $token_decode->token_type;
		
		$status = $this->_requestSmsStatus('53e06bcf0f46950200f20b0b',$access_token);
		$status_result = json_decode($status,true);
		$stats = $status_result['response_data']['outbound_message'];
		echo $stats['status'];
		$save = $this->_model();
		$update = $save->_updateMessageStatus('Delivered','53e09d819a37d40200825d4a',41);
		echo $update;
		exit;
		//var_dump($token);
		
		//generate OTP KEY 
		$otpKey = $this->_generateOtpKey();
		//$message = "Your Sample Store OTP access is ".$otpKey;
		$message = "Here is your SampleStore OTP key".$otpKey;
		try{
			//save the OTP key and mobile number of the users
			$sms = $this->_sendSMS($access_token,$token_type,$message);
		} catch(Exception $e) {
    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
		}
	}
	
	public function otpAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//validate if the user is not login
			if(!Mage::getSingleton('customer/session')->isLoggedIn()){
				$message = array('response'=>400, 'message'=>'User is not Login');
				Mage::log('Sending OTP: '.$message['message']);	
				echo json_encode($message);
				exit;
			}
			$this->mobile_num = $this->getRequest()->getPost('mobile_num');
			$mobile = (!empty($this->mobile_num)) ? $this->mobile_num : '';
			//validate if mobile number is empty
			if($mobile == ''){
				Mage::log('SMSOTP: Mobile Number is Empty');
				echo json_encode(array('result'=>'error','message'=>'Mobile Number is Empty'));
				exit;
			} 
			//1. eliminate invalid characters
			$mobile =& preg_replace("/[^0-9]/", '', $mobile);
			//2..count if valid 8 digit mobile number after the preg_replace
			if(strlen($mobile) != 8){
				Mage::log('SMSOTP: Invalid Mobile Number');
				echo json_encode(array('result'=>'error','message'=>'Invalid Mobile Number'));
				exit;
			}
			try{
				$model = Mage::getModel("profile/action");
				$attr_id = $this->_loadAttrByCode('telephone');
				$query = $model->editSettings($mobile,'telephone',$attr_id);
				if($query['response'] == 200){
					//get the token of the SMS
					$token = $this->_requestToken();
					//decode the $token.
					$token_decode = json_decode($token);
					$access_token =  $token_decode->access_token;
					$token_type = $token_decode->token_type;
		
					//do the process of the sending OTP
					$sms = $this->_messageSending($mobile,$access_token,$token_type);
					$smsResult = json_decode($sms,true);
					//get the status of the message and update the database	
					// $status = $this->_requestSmsStatus($smsResult['message_id'],$access_token);
					// $status_result = json_decode($status,true);
					// $stats = $status_result['response_data']['outbound_message'];
					if($smsResult['response'] == 200){
						//update the model
						$save = $this->_model();
						$update = $save->_updateMessageStatus($smsResult['message_id'],$smsResult['last_id']);
						$updateDecode = json_decode($update,true);
						if($updateDecode['response'] == 200){
							echo json_encode(array('response'=>200,'message'=>'OTP has been Sent'));
						}
						else{
							echo json_encode(array('response'=>400,'message'=>'ERROR : Failure in sending OTP'));
						}
					}
					else{
						echo $sms;
					}
				}
				else{
					Mage::log('Saving Mobile Number : '.$query['message']);
					echo json_encode($query);
				}
				//echo json_encode($sms);
			} catch(Exception $e) {
				Mage::log('SMSOTP: Invalid Mobile Number');
	    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
			}
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 	
	}
	
	private function _messageSending($number,$access_token,$token_type)
	{				
		//generate OTP KEY 
		$otpKey = $this->_generateOtpKey();
		$message = "Your Sample Store OTP key is ".$otpKey;
		$mobile = "+65".$number;
		try{
			//save the OTP key and mobile number of the users
			$result = $this->_model();
			$save = $result->_saveMobileNumber($mobile,$otpKey);
			$result_decoded = json_decode($save);
			if($result_decoded->response == 200){
				$sms = $this->_sendSMS($access_token,$token_type,$message,$mobile);
				$sms_message = json_decode($sms,true);
				if($sms_message['error']['error_code'] == 0){
					$message = $sms_message['response_data']['message_id'];
					return json_encode(array('response'=>200,'last_id'=>$result_decoded->last_id,'message_id'=>$message));	
				}
				else {
					Mage::log('SMSOTP: '.$sms_message['error']['error_code']);
					return json_encode(array('response'=>400,'message'=>$sms_message['error']['error_code']));
					//return json_encode($sms);
				}
				//return $sms;
			}
			else{
				Mage::log('SMSOTP: '.$result_decoded->message);
				//Mage::logException($result_decoded->message);
				return json_encode(array('response'=>400, 'message'=>$result_decoded->message));
			}
		} catch(Exception $e) {
			Mage::logException($e);
    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
		}
	}
	
	private function _sendSMS($access_token, $token_type,$message,$mobile)
	{
		try{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://apiserver.sent.ly/api/outboundmessage");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"from\":\"SampleStore\",\"to\":\"$mobile\",\"text\":\"$message\"}");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Content-Type: application/json", "Authorization: Bearer ".$access_token));
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		} catch(Exception $e) {
			Mage::logException($e);
    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
		}
	}
	
	private function _requestToken()
	{
		//get the base64 key for the oauth of the api
		$token_key = 'D4ZpIuFuvFkXAEXn74a4lAzJA8EaQ8ax:1aCpwI2nMLSMDMq5dadFH5onQc1nD4Xr';
		$base_key = base64_encode($token_key);
		//echo $base_key;
		try{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://apiserver.sent.ly/oauth/token");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic RDRacEl1RnV2RmtYQUVYbjc0YTRsQXpKQThFYVE4YXg6MWFDcHdJMm5NTFNNRE1xNWRhZEZINW9uUWMxbkQ0WHI=", "Content-Type: application/x-www-form-urlencoded;charset=UTF-8", "Content-Length: 29", "Accept-Encoding: gzip"));
			$response = curl_exec($ch);
			//echo curl_error($ch);
			curl_close($ch);
			return $response;
		} catch(Exception $e) {
			Mage::logException($e);
    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
		}
	}

	private function _requestSmsStatus($message_id,$access_token)
	{
		try{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://apiserver.sent.ly/api/outboundmessage/".$message_id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$access_token,"Accept: application/json;charset-UTF-8"));
			$response = curl_exec($ch);
			curl_close($ch);
		return $response;
		} catch(Exception $e) {
			Mage::logException($e);
    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
		}
	}

	protected function _generateOtpKey()
	{
		//$aplha = 'abcdefghjkmnpqrstuvwxyz';
		$numeric = '0123456789';
		$letters = '';
		$numbers = '';
		for ($i = 0; $i < 6; $i++) {
			//$letters .= $aplha[rand(0, strlen($aplha) - 1)];
			$numbers .= $numeric[rand(0, strlen($numeric) - 1)];
		}
		//$shuffle = $letters.$numbers;
		//$otp = str_shuffle($shuffle);
		$otp = str_shuffle($numbers);
		return $otp;
	}
	
	public function statusAction()
	{
		//http://magestaging.samplestore.com/onetimepassword/send/status/message_id/53e9c8ad4ef18f0200f782cf
		$message_id = $this->getRequest()->getParam('message_id');
		$token = $this->_requestToken();
		//decode the $token.
		$token_decode = json_decode($token);
		$access_token =  $token_decode->access_token;
		$token_type = $token_decode->token_type;
		print "<pre>";
		print_r($this->_requestSmsStatus($message_id,$access_token));
		exit;
	}
	
	public function _model()
	{
		return Mage::getModel("onetimepassword/action");
	}
	
	public function _loadAttrByCode($attr)
	{
		$attribute = Mage::getModel('eav/entity_attribute')->loadByCode(2, $attr);
		return $attribute->getAttributeId();
	}
}