<?php
/**
 * @author        Vladimir Popov
 * @copyright    Copyright (c) 2014 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Results
    extends VladimirPopov_WebForms_Block_Webforms
    implements Mage_Widget_Block_Interface
{
    protected $_resultsCollection;
    protected $_reviewsCollection;
    protected function _construct()
    {
        parent::_construct();
        $this->setData('results', 1);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($toolbar = Mage::app()->getLayout()->createBlock('page/html_pager')) {
            $toolbar->setCollection($this->getResultsCollection());
            $this->setChild('toolbar', $toolbar);
        }
        $data = $this->getFormData();
        if ($rating = Mage::app()->getLayout()->createBlock('webforms/rating')) {
            $rating->setData('webform_id', $data["webform_id"]);
            $rating->setTemplate('webforms/results/rating.phtml');
            $this->setChild('rating', $rating);
        }

        return $this;
    }

    public function getResultsCollection($webform_id = null)
    {   
        if (null === $this->_resultsCollection) {
            $data = $this->getFormData();
           
            if($webform_id) $data['webform_id'] = $webform_id;
            $this->_resultsCollection = Mage::getModel('webforms/results')->getCollection()
                ->addFilter('store_id', Mage::app()->getStore()->getId())
                ->addFilter('webform_id', $data['webform_id'])
          
                ->addFilter('approved', 1);
            
            $this->_resultsCollection->getSelect()->order('created_time desc');
        }
        return $this->_resultsCollection;
    }
    public function getAllResultsCollection($customer_id = null)
    {   $this->_reviewsCollection = null;
        if (null === $this->_reviewsCollection) {
            $data = $this->getFormData();
            if($webform_id) $data['webform_id'] = $webform_id;
            $this->_reviewsCollection = Mage::getModel('webforms/results')->getCollection()
                ->addFilter('store_id', Mage::app()->getStore()->getId())
               // ->addFilter('webform_id', $data['webform_id'])
                ->addFilter('customer_id', $customer_id)
                ->addFilter('approved', 1);
            
            $this->_reviewsCollection->getSelect()->order('created_time desc');
        }
          Mage::getModel('core/session')->setData('customer_id','');
                 Mage::getModel('core/session')->setData('customer_id',$customer_id);
        return $this->_reviewsCollection;
    }
}
