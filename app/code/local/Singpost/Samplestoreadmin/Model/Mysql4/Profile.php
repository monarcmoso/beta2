<?php
class Singpost_Login_Model_Mysql4_Profile extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('singpost_login/profile', 'customer_id');
    }
}
?>