<?php
class A4on_Mybrand_Model_Myproduct extends Mage_Core_Model_Abstract
{
     public function _construct()
     {
         parent::_construct();
         $this->_init('myproduct/myproduct');
     }
}
?>