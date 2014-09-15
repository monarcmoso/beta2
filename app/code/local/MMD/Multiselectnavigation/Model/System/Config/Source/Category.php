<?php
class MMD_Multiselectnavigation_Model_System_Config_Source_Category extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'breadcrumbs',
                'label' => Mage::helper('multiselectnavigation')->__('Yes')
        );
        $options[] = array(
                'value'=> 'none',
                'label' => Mage::helper('multiselectnavigation')->__('No')
        );
        
        return $options;
    }
}