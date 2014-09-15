<?php
class MMD_Multiselectnavigation_Block_Catalog_Layer_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('multiselectnavigation/filter_category.phtml');
        $this->_filterModelName = 'multiselectnavigation/catalog_layer_filter_category'; 
    }
    
    public function getVar(){
        echo $this->_filter->getRequestVar();;
        return $this->_filter->getRequestVar();
    }
    
    public function getClearUrl()
    {
        return '';
    }
     public function getHtmlId($item)
    {
        return $this->getVar() . '-' . $item->getValueString();        
    }
    
    public function isSelected($item)
    {
        $ids = (array)$this->_filter->getActiveState();
        return in_array($item->getValueString(), $ids);        
    }
    
    public function getItemsArray()
    {   
        $items = array(); 
        $iconsOnly = (3 == $this->getColumnsNum());
        $baseUrl = Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK); 
     
        $hideLinks = Mage::getStoreConfig('multiselectnavigation/general/remove_links');
         
        foreach ($this->getItems() as $_item){
            
            //$htmlParams = 'id="' . $this->getHtmlId($_item) . '" ';
            
            $href = "#";
            if (!$hideLinks)
                $href = $this->htmlEscape($_item->getUrl());
                
            $htmlParams .= 'href="' . $href . '" ';
                
            if ($iconsOnly){
                $htmlParams .= ' title="'.$this->htmlEscape($_item->getLabel()).'" class="mmd-multinav-icon ' 
                            . ($this->isSelected($_item) ? 'mmd-multinav-icon-selected' : '') . '" ';
            }
            else{
                $htmlParams .= 'class="mmd-multinav-attribute ' 
                            . ($this->isSelected($_item) ? 'mmd-multinav-attribute-selected' : '') . '" ';
            }
            
            $icon = '';
            if ($_item->getIcon()){
                $icon = '<img border="0" alt="'.$this->htmlEscape($_item->getLabel()).'" src="'.$baseUrl.'media/icons/'.$_item->getIcon().'" />';
            } 
        
            $qty = '';
            if (!$this->getHideQty()) 
                $qty =  '(' .  $_item->getCount() .')';
        
            $label = $_item->getLabel();
            if ($iconsOnly){
                $label = '';
            }
            $label = $icon . $label;
            
            $items[] = '<a '.$htmlParams.'>'.$label.'</a>';
        }
        return $items;
    }    
}