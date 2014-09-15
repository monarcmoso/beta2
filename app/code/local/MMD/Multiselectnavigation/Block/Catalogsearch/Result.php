<?php
class MMD_Multiselectnavigation_Block_Catalogsearch_Result extends Mage_CatalogSearch_Block_Result
{
    /**
     * Retrieve Search result list HTML output, wrapped with <div>
     *
     * @return string
     */
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        $html = Mage::helper('multiselectnavigation')->wrapProducts($html);
        return $html;
    }
}