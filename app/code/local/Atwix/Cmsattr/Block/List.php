<?php
class Atwix_Cmsattr_Block_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_itemCollection = null;
 
    public function getItems()
    {
        $color = $this->getColor();
        if (!$color)
            return false;
        if (is_null($this->_itemCollection)) {
            $this->_itemCollection = Mage::getModel('atwix_cmsattr/products')->getItemsCollection($color);
        }
 
        return $this->_itemCollection;
    }
}