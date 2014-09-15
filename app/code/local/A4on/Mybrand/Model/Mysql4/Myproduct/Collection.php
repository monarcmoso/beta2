<?php
class A4on_Mybrand_Model_Mysql4_Myproduct_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     public function _construct()
     {
         parent::_construct();
         $this->_init('myproduct/myproduct');
     }
}
?>