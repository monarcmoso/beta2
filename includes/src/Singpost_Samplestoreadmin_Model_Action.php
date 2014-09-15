<?php
class Singpost_Samplestoreadmin_Model_Action extends Mage_Core_Model_Abstract
{
	public function _saveReferal($code,$description)
	{
		//validate if the user is login by facebook
		$data = array('code'=>$code,'desccription'=>$description);
		try {
		    $this->_write()->beginTransaction();
		    $this->_write()->insert('analytics_referral',$data);
		    $this->_write()->commit();
			return json_encode(array('response'=>200,'message'=>'save'));
		}
		catch (Exception $exception){
		    return json_encode(array('response'=>400,'message'=>'failed'));
		}
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