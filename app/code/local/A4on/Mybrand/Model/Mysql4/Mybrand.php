<?php
class A4on_Mybrand_Model_Mysql4_Mybrand extends Mage_Core_Model_Mysql4_Abstract
{
     public function _construct()
     {
         $this->_init('mybrand/mybrand', 'brand_id');
     }
}
?>