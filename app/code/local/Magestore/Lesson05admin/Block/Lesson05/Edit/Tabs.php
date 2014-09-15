<?php

class Magestore_Lesson05admin_Block_Lesson05_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('lesson05admin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('lesson05')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('lesson05')->__('Item Information'),
          'title'     => Mage::helper('lesson05')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('lesson05/adminhtml_lesson05_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
