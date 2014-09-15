<?php
class MMD_Multiselectnavigation_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function categoryAction()
    {
        // init category
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        if (!$categoryId) {
            $this->_forward('noRoute'); 
            return;
        }

        $category = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($categoryId);
        Mage::register('current_category', $category);         
        
        
        $this->loadLayout();
        
        $response = array();
        $response['layer']    = $this->getLayout()->getBlock('layer')->toHtml();
        $response['products'] = $this->getLayout()->getBlock('root')->toHtml();  
        
        $response = Zend_Json::encode($response);
        $this->getResponse()->setBody($response);
    }
    
    public function searchAction()
    {
        $this->loadLayout();
        
        $response = array();
        $response['layer']    = $this->getLayout()->getBlock('layer')->toHtml();
        $response['products'] = $this->getLayout()->getBlock('root')->setIsSearchMode()->toHtml();  
        
        $response = Zend_Json::encode($response);
        $this->getResponse()->setBody($response);
        
    }
    
}