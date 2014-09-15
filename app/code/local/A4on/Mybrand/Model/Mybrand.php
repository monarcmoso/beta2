<?php
class A4on_Mybrand_Model_Mybrand extends Mage_Core_Model_Abstract
{
     public function _construct()
     {
         parent::_construct();
         $this->_init('mybrand/mybrand');
     }
}
?>