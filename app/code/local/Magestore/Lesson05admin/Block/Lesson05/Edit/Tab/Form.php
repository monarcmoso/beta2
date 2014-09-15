<?php

class Magestore_Lesson05admin_Block_Lesson05_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('lesson05_form', array('legend'=>Mage::helper('lesson05')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('lesson05')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('lesson05')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('lesson05')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('lesson05')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('lesson05')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('lesson05')->__('Content'),
          'title'     => Mage::helper('lesson05')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getLesson05Data() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getLesson05Data());
          Mage::getSingleton('adminhtml/session')->setLesson05Data(null);
      } elseif ( Mage::registry('lesson05_data') ) {
          $form->setValues(Mage::registry('lesson05_data')->getData());
      }
      return parent::_prepareForm();
  }
}