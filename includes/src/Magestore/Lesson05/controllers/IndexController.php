<?php
class Magestore_Lesson05_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/lesson05?id=15 
    	 *  or
    	 * http://site.com/lesson05/id/15 	
    	 */
//                  
//        if($lesson05_id != null && $lesson05_id != '')	{
//                $lesson05 = Mage::getModel('lesson05/lesson05')->load($lesson05_id)->getData();
//        } else {
//                $lesson05 = null;
//        }	
//		
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($lesson05 == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$lesson05Table = $resource->getTableName('lesson05');
			
			$select = $read->select()
			   ->from($lesson05Table,array('lesson05_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$lesson05 = $read->fetchRow($select);
		}
		Mage::register('lesson05', $lesson05);
		*/
                
			
		$this->loadLayout();     
		$this->renderLayout();
    }
    public function brandsAction() 
    {          
         
             $this->loadLayout();
             $this->getLayout()->createBlock('core/template')->setTemplate('lesson05/lesson05.phtml')->toHtml();
            
             //$this->renderLayout();
              
    }
    public function seedersAction(){


        
                $this->loadLayout();
		$this->renderLayout();
       
    }
}