<?php
class MMD_Multiselectnavigation_Model_System_Config_Source_Price extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'default',
                'label' => Mage::helper('multiselectnavigation')->__('Default')
        );
        $options[] = array(
                'value'=> 'slider',
                'label' => Mage::helper('multiselectnavigation')->__('Slider')
        );
        $options[] = array(
                'value'=> 'input',
                'label' => Mage::helper('multiselectnavigation')->__('Input')
        );
        
        return $options;
    }
}