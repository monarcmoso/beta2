<?php
class Singpost_OneTimePassword_Model_Action extends Mage_Core_Model_Abstract
{
	public function _construct()
    {
        parent::_construct();
       	$this->_init('singpost_onetimepassword/action');
    }
	
	public function _saveMobileNumber($mobile,$otp)
	{
		if($this->_getMobileSpammer($mobile) == true){
			return json_encode(array('response'=>400, 'message'=>'You already reach the maximum limit of sending OTP.'));	
			exit;
		}
		
		//check if the mobile number is existing
		if($this->_getMobile($mobile) == true){
			$customer_id = $this->_customerId();
			//if mobile number is valid, insert to database.
			$data = array('mobile_number'=>$mobile,'otp_key'=>$otp,'customer_id'=>$customer_id);
			try {
			    $this->_write()->beginTransaction();
			    $this->_write()->insert('smsotp',$data);
				$last_id = $this->_write()->lastInsertId();
			    $this->_write()->commit();
				return json_encode(array('response'=>200, 'message'=>'Success','last_id'=>$last_id));
			}
			catch (Exception $exception){
				Mage::logException($exception);
			    return json_encode(array('response'=>400, 'message'=>'Failed'));
			}
		}else{
			return json_encode(array('response'=>400, 'message'=>'The mobile no. is already in use. Please try again with a different mobile no.'));
		}
	}
	
	public function _getMobile($mobile)
	{
		try {
			$select = $this->_read()->select()
			->from('smsotp', array('*'))
			->where("mobile_number='{$mobile}' AND status = 1");
			$rows = $this->_read()->fetchAll($select);
			if(count($rows) > 0){
				return false;
			}else{
				return true;
			}
		}catch (Exception $exception){
			Mage::logException($exception);
		    return json_encode(array('response'=>400, 'message'=>'Failed'));
		}
	}
	
	public function _getMobileSpammer($mobile)
	{
		try {
			$select = $this->_read()->select()
			->from('smsotp', array('*'))
			->where("mobile_number='{$mobile}'");
			$rows = $this->_read()->fetchAll($select);
			if(count($rows) >= 3){
				return 1;
			}else{
				return 0;
			}
		}catch (Exception $exception){
			Mage::logException($exception);
		    return json_encode(array('response'=>400, 'message'=>'Failed'));
		}
	}
	
	public function _updateMessageStatus($message_id,$id)
	{
		try {
		    $this->_write()->beginTransaction();
			$where = "id = {$id}";
		    $this->_write()->update('smsotp', array("message_id" => $message_id), $where);
		    $this->_write()->commit();
		    return json_encode(array('response'=>200, 'message'=>'success'));
		}
		catch (Exception $exception){
		    Mage::logException($exception);
		}
	}
	
	public function _verifyOtp($otp,$mobile)
	{
		if($this->_getMobile($mobile) == true){
			try {
				$select = $this->_read()->select()
				->from('smsotp', array('*'))
				->where("otp_key='{$otp}' AND mobile_number = '{$mobile}' AND status = 0");
				$rows = $this->_read()->fetchAll($select);
				if(count($rows) > 0){
					//do the process of updating the status
					$update_otp = $this->updateOtp($otp,$mobile);
					$update_decode = json_decode($update_otp,true);
					if($update_decode['response'] == 200){
						return json_encode(array('response'=>200, 'message'=>'OTP is verified.'));
					}
					else{
						return json_encode(array('response'=>400,'message'=>$update_decode['message']));
					}
				}else{
					return json_encode(array('response'=>400, 'message'=>'Invalid OTP key'));
				}
			}catch (Exception $exception){
				Mage::logException($exception);
			    return json_encode(array('response'=>400, 'message'=>'Failed'));
			}
		}else{
			return json_encode(array('response'=>400, 'message'=>'Mobile number has already verified!'));
		}
	}
	
	public function updateOtp($otp,$mobile)
	{
		$expiry = $this->_getOtpExpiry($otp,$mobile);
		if($expiry == false){
			return json_encode(array('response'=>400, 'message'=>'Your OTP key is already expired.'));
			exit;
		}
		try {
		    $this->_write()->beginTransaction();
			$where = "otp_key = '{$otp}' AND mobile_number = '{$mobile}'";
		    $this->_write()->update('smsotp', array("status" => 1), $where);
		    $this->_write()->commit();
		    return json_encode(array('response'=>200, 'message'=>'success'));
		}
		catch (Exception $exception){
		    Mage::logException($exception);
		}
	}
	
	public function _getOtpExpiry($otp,$mobile)
	{
		try {
			$select = $this->_read()->select()
			->from('smsotp', array('date_added'))
			->where("mobile_number='{$mobile}' AND otp_key = '{$otp}'");
			$row = $this->_read()->fetchRow($select);
			$from_time = strtotime($row['date_added']);
			date_default_timezone_set('Asia/Singapore');
			$current = date('Y-m-d H:i:s');
			$start_date = new DateTime($row['date_added']);
			$since_start = $start_date->diff(new DateTime($current));
			// echo $since_start->days.' days total<br>';
			// echo $since_start->y.' years<br>';
			// echo $since_start->m.' months<br>';
			// echo $since_start->d.' days<br>';
			// echo $since_start->h.' hours<br>';
			// echo $since_start->i.' minutes<br>';
			// echo $since_start->s.' seconds<br>';
			if(($since_start->y == 0) && 
				($since_start->m == 0) && 
				($since_start->d == 0) && 
				($since_start->h == 0) &&
				($since_start->i <= 5)){
				return true;
			}else{
				return false;
			}
			
		}catch (Exception $exception){
			Mage::logException($exception);
		    return json_encode(array('response'=>400, 'message'=>'Failed'));
		}
	}
	
	public function _getUserIfverified()
	{
		$mobile = '';
		//get data of the user by id
		$customer = Mage::getModel("customer/customer");  
		$customer->load($this->_customerId());
		$address = array();
		foreach ($customer->getAddresses() as $data){
			$address = $data->toArray();
		}
		
		$mobile = "+65".$address['telephone'];
		if($mobile == ''){
			return "0";
			
		}
		else{
			//do the query of verification
			try{  
				$select = $this->_read()->select()
				->from('smsotp', array('*'))
				->where("mobile_number='{$mobile}' AND status = 1");
				$rows = $this->_read()->fetchAll($select);
				if(count($rows) > 0){
                                   
					return "1";
				}
				else{
					return "0";
				}
			}catch(exception $e){
				Mage::logException($e);
				return "0";
			}
		}
		
		
	}
	
	public function _customerId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
	
	public function _write()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_write');
	}

	public function _read()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}

	public function _storeId()
	{
		return Mage::app()->getStore()->getStoreId();
	}
	
	public function _websiteId()
	{
		return Mage::app()->getWebsite()->getId();
	}
}