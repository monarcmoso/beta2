<?php

class Inic_Creditpayment_Model_Customer extends Mage_Customer_Model_Customer
{
    
    /** added By IndiaNIC **/
    public function getCreditLimit()
    {
        $creditori = $this->getData('credit_limit');
        $credit_limit = round($creditori,2);
        $this->setData('credit_limit',$credit_limit);
        return $credit_limit;
    }
     /** added By IndiaNIC **/
}
