<?php
class Magestore_Lesson05admin_Block_Adminhtml_Lesson05 extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'lesson05admin';
    $this->_blockGroup = 'lesson05admin';
    $this->_headerText = Mage::helper('lesson05admin')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('lesson05admin')->__('Add Item');
    parent::__construct();
  }
}
