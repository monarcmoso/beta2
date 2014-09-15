<?php
class Magestore_Lesson05_Block_Lesson05 extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
//     public function getLesson05()     
//     {  echo "aaa";
//        if (!$this->hasData('lesson05')) {
//            $this->setData('lesson05', Mage::registry('lesson05'));
//        }
//        return $this->getData('lesson05');
//        
//    }
      public function methodBlock()
   { 
     return 'informations about my block !!' ;
   }
}