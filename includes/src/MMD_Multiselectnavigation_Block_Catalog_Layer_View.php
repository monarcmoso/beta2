<?php
class MMD_Multiselectnavigation_Block_Catalog_Layer_View extends Mage_Catalog_Block_Layer_View
{
    protected $_filterBlocks = null;
    
    //@todo refactor  ( too many low level operation with URL)
//    public function getStateInfo()
//    {
//         $url = Mage::helper('multiselectnavigation')->getContinueShoppingUrl();
//         if (Mage::helper('multiselectnavigation')->isSearch()){
//            $ajaxUrl = Mage::getUrl('multiselectnavigation/ajax/search');
//         }
//         elseif ($cat = $this->getLayer()->getCurrentCategory()){
//            $ajaxUrl = Mage::getUrl('multiselectnavigation/ajax/category', array('id'=>$cat->getId()));
//         }
//
//         $pos = strpos($url, '?');
//         if ($pos)
//            $url = substr($url, 0, $pos);
//         $pos = strpos($ajaxUrl, '?');
//         if ($pos)
//            $ajaxUrl = substr($ajaxUrl, 0, $pos);
//         
//         
//         $queryStr = '';
//         $query   = Mage::app()->getRequest()->getQuery();
//         $query[Mage::getBlockSingleton('page/html_pager')->getPageVarName()] = null;
//         foreach ($query as $k => $v){
//            if ($v) // skip empty vals, null and 0
//                $queryStr .= $k . '=' . urlencode($v) . '&';  
//         }
//         $queryStr = trim($queryStr, '&');
//         
//         $clearAll = $url;
//         if (Mage::helper('multiselectnavigation')->isSearch())
//            $clearAll .= '?q=' . urlencode($query['q']);
//         $this->setClearAllUrl($clearAll);
//         
//
//         return array($url, $queryStr, $ajaxUrl);
//    }
//    
//    protected function _prepareLayout()
//    {
//        //max height
//        //$h = intVal(Mage::getStoreConfig('multiselectnavigation/general/max_h'));
//        //if ($h > 0)
//        //    $this->setHeightStyle('style="height:'.$h.'px; overflow:auto; border:1px solid black"');
//        
//        //preload setting    
//        $this->setIsRemoveLinks(Mage::getStoreConfig('multiselectnavigation/general/remove_links'));
//            
//        //blocks    
//        $this->createCategoriesBlock();
//
//        $filterableAttributes = $this->_getFilterableAttributes();
//        
//        // we rewrite this piece of code
//        // to make sure price filter is applied last
//        $blocks = array();
//        foreach ($filterableAttributes as $attribute) {
//            $blockType = 'multiselectnavigation/catalog_layer_filter_attribute';
//            
//            if ($attribute->getFrontendInput() == 'price') {
//                $blockType = 'multiselectnavigation/catalog_layer_filter_price';
//            }
//            $name = $attribute->getAttributeCode().'_filter';
//            $blocks[$name] = $this->getLayout()->createBlock($blockType)
//                ->setLayer($this->getLayer())
//                ->setAttributeModel($attribute);
//                    
//            $this->setChild($name, $blocks[$name]);
//        }
//        
//        foreach ($blocks as $name=>$block){
//            if ($name != 'price_filter')
//                $block->init();
//        }
//        
//        if (!empty($blocks['price_filter']))
//            $blocks['price_filter']->init();
//
//        $this->getLayer()->apply();
//        
//        return Mage_Core_Block_Template::_prepareLayout();
//    }  
    
//    protected function createCategoriesBlock(){
//        if ('none' != Mage::getStoreConfig('multiselectnavigation/general/cat_style')){
//            $categoryBlock = $this->getLayout()->createBlock('multiselectnavigation/catalog_layer_filter_category')
//                ->setLayer($this->getLayer())
//                ->init();
//            $this->setChild('category_filter', $categoryBlock);
//        }
//    }
//    
//    public function getFilters()
//    {
//        if (is_null($this->_filterBlocks)){
//            $this->_filterBlocks = parent::getFilters();
//            $val = Mage::getConfig()->getNode('modules/Multiselectnavigation_Icon/active');
//    	    if ((string)$val == 'true'){
//    	       Mage::helper('adjicon')->addIconsToFilters($this->_filterBlocks);
//    	    }
//        }	    
//	    return $this->_filterBlocks;
//    }
//    
//    protected function _toHtml(){
//        $html = parent::_toHtml();
//        if (!Mage::app()->getRequest()->isXmlHttpRequest()){
//            $html = '<div id="mmd-multinav-navigation">' . $html . '</div>';
//        }
//        return $html; 
//    }

}