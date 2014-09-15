<?php
class Singpost_OneTimePassword_Model_Mysql4_Action extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('singpost_onetimepassword/action', 'id');
    }
}
?>