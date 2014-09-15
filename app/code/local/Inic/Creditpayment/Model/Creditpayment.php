<?php

class Inic_Creditpayment_Model_Creditpayment extends Mage_Core_Model_Abstract
{
	public function _construct()
    {
    	
        parent::_construct();
        $this->_init('creditpayment/creditpayment');
        
    }
}
