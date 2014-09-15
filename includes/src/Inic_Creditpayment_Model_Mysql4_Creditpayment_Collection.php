<?php

class Inic_Creditpayment_Model_Mysql4_Creditpayment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
    	
        parent::_construct();
        $this->_init('creditpayment/creditpayment');
        
    }
}