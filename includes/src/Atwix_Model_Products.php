<?php
class Atwix_Cmsattr_Model_Products extends Mage_Catalog_Model_Product
{
    public function getItemsCollection($valueId)
    {
        $collection = $this->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('color', array('eq' => $valueId));
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        return $collection;
    }
}