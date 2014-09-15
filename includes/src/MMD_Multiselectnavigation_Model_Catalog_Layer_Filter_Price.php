<?php

class MMD_Multiselectnavigation_Model_Catalog_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Price
{
    protected $baseSelect = null;
    
    public function __construct()
    {
        parent::__construct();
    }   
    
    protected function _getItemsData()
    {
        $data = array();   
        $style = Mage::getStoreConfig('multiselectnavigation/general/price_style'); 
        if ('default' == $style){
            return parent::_getItemsData();
        }
        elseif('input' == $style){
            list($from, $to) = $this->getFilterValueFromRequest();
            $data[] = array(
                'label' => '',
                'value' => $from . ',' . $to,
                'count' => 1,
            );
        }    
        elseif('slider' == $style){
            $data[] = array(
                'label' => '',
                'value' => 0 . ',' . $this->getMaxPriceInt()+1,
                'count' => 1,
            );
        }    
        
        return $data;
    }
    
    private function getFilterValueFromRequest()
    {
        $request = Mage::app()->getRequest();
        $filter = $request->getParam($this->_requestVar);
     
        if (!$filter) {
            return array(0, 0);
        }

        $filter = explode(',', $filter);
       
        if (count($filter) != 2) {
            return array(0, 0);
        }

        list($from, $to) = $filter;
        $from = sprintf("%.02f", $from);
        $to   = sprintf("%.02f", $to);
         
        return array($from, $to);
    }

    /**
     * Apply price range filter to collection
     *
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        list($from, $to) = $this->getFilterValueFromRequest();
        if ('default' == Mage::getStoreConfig('multiselectnavigation/general/price_style')){
            $index = $from;
            $rate  = $to;
            
            $from = ($index-1)*$rate;
            $to   = $index*$rate;
            
            $this->setActiveState(sprintf('%d,%d', $index, $rate));
        }
        else{
            $this->setActiveState($from . ','. $to);
        }
        
        $this->baseSelect = clone $this->getLayer()->getProductCollection()->getSelect();
        if ($from >= 0.01 || $to >= 0.01) {
            $this->applyFromToFilter($from, $to);
            
        }

        return $this;
    }
    
    // copied from Mage_CatalogIndex_Model_Mysql4_Price,
    // bacause of AWFULL!!! design: the method accept $range, $index INSTEAD OF $from, $to args
    // hope you, my reader, understand that is it incorrect in terms of tiers (library
    // function shouldn't know about logic of price ranges ...
    protected function applyFromToFilter($from, $to)
    {
        $tableName    = 'price_table';
        $collection   = $this->getLayer()->getProductCollection();
        $attribute    = $this->getAttributeModel();
        $websiteId    = Mage::app()->getStore()->getWebsiteId();
        $custGroupId  = Mage::getSingleton('customer/session')->getCustomerGroupId();
        
        /**
         * Distinct required for removing duplicates in case when we have grouped products
         * which contain multiple rows for one product id
         */
        $collection->getSelect()->distinct(true);
        $tableName = $tableName.'_'.$attribute->getAttributeCode();
        $collection->getSelect()->joinLeft(
            array($tableName => Mage::getSingleton('core/resource')->getTableName('catalogindex/price')), // modified line
            $tableName .'.entity_id=e.entity_id',
            array()
        );

        $response = new Varien_Object();
        $response->setAdditionalCalculations(array());

        $collection->getSelect()
            ->where($tableName . '.website_id = ?', $websiteId)   // modified line
            //->where($tableName . '.attribute_id = ?', $attribute->getId())
			;
            
            

        if ($attribute->getAttributeCode() == 'price') {
            //$collection->getSelect()->where($tableName . '.customer_group_id = ?', $custGroupId); // modified line
            $args = array(
                'select'         => $collection->getSelect(),
                'table'          => $tableName,
                'store_id'       => Mage::app()->getStore()->getId(), // modified line
                'response_object'=> $response,
            );
            Mage::dispatchEvent('catalogindex_prepare_price_select', $args);
        }
        // TODO - explore!
        $rate = 1;

        // make query a little bit faster
        if ($from > 0.01)
            $collection->getSelect()->where("(({$tableName}.price".implode('', $response->getAdditionalCalculations()).")*$rate) >= ?", $from);
        if ($to > 0.01)    
            $collection->getSelect()->where("(({$tableName}.price".implode('', $response->getAdditionalCalculations()).")*$rate) <= ?", $to);
        
		/*echo  $collection->getSelect(); die;*/
        return $this;
    }  
    
    
    protected function _getBaseCollectionSql()
    {
        return $this->baseSelect;
    }

    public function getMaxPriceInt()
    {
        // for some really UNKNOWN reason magento team 
        // rounds max price to lower value :)
        return 1 + parent::getMaxPriceInt();
    }
}
