<?php

class Magestore_Lesson05admin_Block_Lesson05_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'lesson05admin';
        $this->_controller = 'lesson05admin';
        
        $this->_updateButton('save', 'label', Mage::helper('lesson05')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('lesson05')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('lesson05_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'lesson05_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'lesson05_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('lesson05_data') && Mage::registry('lesson05_data')->getId() ) {
            return Mage::helper('lesson05')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('lesson05_data')->getTitle()));
        } else {
            return Mage::helper('lesson05')->__('Add Item');
        }
    }
}
