<?php
class MMD_Multiselectnavigation_Block_Catalog_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    private $_style;
    
    public function __construct()
    {
        parent::__construct();
        $this->_style = Mage::getStoreConfig('multiselectnavigation/general/price_style');
        $this->setTemplate('multiselectnavigation/filter_price_' . $this->_style . '.phtml');
        
        $this->_filterModelName = 'multiselectnavigation/catalog_layer_filter_price';
    }
    
    public function getVar(){
        return $this->_filter->getRequestVar();
    }
    
    public function getClearUrl()
    {
        $url = '';
        if ('slider' != $this->_style){
            $url = Mage::getUrl('*/*/*', array(
                '_current'     => true, 
                '_use_rewrite' => true, 
                '_query'       => array($this->getVar() => null),
            )); 
        }
        return $url;
    }
    
    public function isSelected($item)
    {
        return ($item->getValueString() == $this->_filter->getActiveState());        
    }
    
    public function getSymbol()
    {
        $s = $this->getData('symbol');
        if (!$s){
            $code = Mage::app()->getStore()->getCurrentCurrencyCode();
            $s = trim(Mage::app()->getLocale()->currency($code)->getSymbol());
            
            $this->setData('symbol', $s);
        }
        return $s;
    }
}