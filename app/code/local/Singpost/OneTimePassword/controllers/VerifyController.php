<?php
class Singpost_OneTimePassword_VerifyController extends Mage_Core_Controller_Front_Action
{
	public $var_mobile = '';
	public $var_otp = '';
	public function indexAction()
	{
		$a = $this->_model()->_getUserIfverified();
		print_r($a);
		exit;
		$mobile = "+6583077224";
		$otp = "pemayz";
		$verify = $this->_model()->_verifyOtp($otp,$mobile);
		//$verify = $this->_model()->updateOtp($otp,$mobile);
		echo $verify;
	}
	
	public function otpAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//get variable.
			$this->var_mobile = $this->getRequest()->getPost('mobile_num');
			$this->var_otp = $this->getRequest()->getPost('otp_key');
			$mobile = (!empty($this->var_mobile)) ? $this->var_mobile : '';
			$otp = (!empty($this->var_otp)) ? $this->var_otp : '';
			
			if($mobile == ''){
				Mage::log('Verify OTP: Mobile Number is Empty');
				echo json_encode(array('response'=>'400','message'=>'Mobile Number is Empty'));
				exit;
			} 
			
			$mobile =& preg_replace("/[^0-9]/", '', $mobile);
			//2..count if valid 8 digit mobile number after the preg_replace
			if(strlen($mobile) != 8){
				Mage::log('Verify OTP: Invalid Mobile Number');
				echo json_encode(array('response'=>'400','message'=>'Invalid Mobile Number'));
				exit;
			}
			
			if($mobile == ''){
				Mage::log('Verify OTP:OTP is empty');
				echo json_encode(array('response'=>'400','message'=>'OTP Key is Empty'));
				exit;
			} 

			if(strlen($mobile) != 8){
				Mage::log('Verify OTP:OTP is empty');
				echo json_encode(array('response'=>'400','message'=>'Invalid OTP Key'));
				exit;
			} 
			
			//insert +65
			$mobile = "+65".$mobile;
			try{
				$verify = $this->_model()->_verifyOtp($otp,$mobile);
				echo $verify;
			} catch(Exception $e) {
				Mage::log('Verify OTP: Cannot verify OTP');
	    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
			}
		}
		else {
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		}
	}
	
	public function _model()
	{
		return Mage::getModel("onetimepassword/action");
	}
}