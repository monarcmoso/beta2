<?php
class Singpost_Profile_Model_Analytics extends Mage_Core_Model_Abstract
{
	public function addAnalyticsLogs($event,$customer_id)
	{
		$data = array('customer_id'=>$customer_id,'event'=>$event);
		try {
		    $this->_write()->beginTransaction();
		    $this->_write()->insert('analytics_logs',$data);
		    $this->_write()->commit();
			return true;
		}
		catch (Exception $exception){
		    return false;
		}
	}
	
	public function getReferalCode($code)
	{
		$select = $this->_read()->select()
		->from('analytics_referral', array('*')) 
		->where("code='{$code}'");
		$result = $this->_read()->fetchAll($select);
		return count($result);
	}	
	
	public function _getAnalyticsLogs($event,$customer_id)
	{
		$select = $this->_read()->select()
		->from('analytics_logs', array('*')) 
		->where("event='{$event}' AND customer_id={$customer_id}");
		$result = $this->_read()->fetchAll($select);
		return count($result);
		//analytics_logs
	}
	
	public function _write()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_write');
	}
	
	public function _read()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}	
}
?>