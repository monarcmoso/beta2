<?php
class Singpost_Profile_Helper_Analytics extends Mage_Core_Helper_Abstract
{
	public function checkedVerifyUser()
	{
		if($this->_getConfirmed() == true && $this->_getShippingAddress() == true){
			return true;
		}else{
			return false;
		}
	}
	
	public function updateAnalytics()
	{
		try{
			$analytics_logs = Mage::getModel("profile/analytics");
			$analytics_logs->addAnalyticsLogs('confirmed',$id);
		}catch(exception $e){
			Mage::logException($e);
		}
	}
	
	protected function _getShippingAddress()
	{
		$customer = $this->_getCustomerInfoById();
		$address = array();
		foreach ($customer->getAddresses() as $data){
			$address = $data->toArray();
		}
		if($address['street'] == ''){
			return false;
		}else{
			return true;
		}
	}
	
	protected function _getConfirmed()
	{
		 $customer = $this->_getCustomerInfoById();
         if ($customer->isConfirmationRequired()) {
            if ($customer->getConfirmation()) {
     			return false;       	
            }else{
            	return true;
            }
        } 
	}
	
	public function _getCustomerInfoById()
	{
		$customer = Mage::getModel("customer/customer");  
		$customer->load($this->_getUserId());
		return $customer;
	}
	
	private function _getUserId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
}