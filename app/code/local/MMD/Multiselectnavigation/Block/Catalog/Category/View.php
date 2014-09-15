<?php
class MMD_Multiselectnavigation_Block_Catalog_Category_View extends Mage_Catalog_Block_Category_View
{
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        $html = Mage::helper('multiselectnavigation')->wrapProducts($html);
        return $html;
    }   
}