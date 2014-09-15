<?php

class Inic_Creditpayment_Model_Mysql4_Creditpayment extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
    	
        // Note that the contactvalue_id refers to the key field in your database table.
        $this->_init('creditpayment/creditpayment', 'id');
        
        
    }
}