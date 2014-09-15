<?php

class MMD_Multiselectnavigation_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category
{
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        // very small optimization
        $catId = (int) $request->getParam($this->getRequestVar());
        if ($catId){
            parent::apply($request, $filterBlock);
        }
        return $this;
    }

    protected function _getItemsData()
    {   
         $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
         $data = $this->getLayer()->getAggregator()->getCacheData($key);
  
        if ($data === null) {
            $category   = $this->getCategory();
       
            /** @var $categoty Mage_Catalog_Model_Categeory */
            $categories = $category->getChildrenCategories();
    
            $data = array();
            $level = 0;
            $parent = null;
            if ($category->getLevel() > 1){ // current category is not root
                $parent = $category->getParentCategory();
                
                ++$level;
                if ($parent->getLevel()>1){
                    $data[] = array(
                        'label'       => $parent->getName(),
                        'value'       => $parent->getUrl(),
                        'level'       => $level,
                        'category_id' => $parent->getId(),
                    );
                }
                //always include current category
                ++$level;
                $data[] = array(
                    'label'       => $category->getName(),
                    'value'       => '',
                    'level'       => $level,
                    'is_current'  => true,
                    'category_id' => $category->getId(),
                );
            } 
        
            $this->getLayer()->getProductCollection()
                ->addCountToCategories($categories);
            
             
            ++$level;
            foreach ($categories as $cat) {
                if ($cat->getIsActive() && $cat->getProductCount()) {
                    $data[] = array(
                        'label'       => $cat->getName(),
                        'value'       => $cat->getUrl(), 
                        'count'       => $cat->getProductCount(),
                        'level'       => $level,
                        'category_id' => $cat->getId(),
                    );
                }
                
            }
            
            // Hope it's more efficient than calling 
            // Mage::getModel('core/url') again with '_current' key
            $queryStr = $this->_getCurrentQueryString();
            for ($i=0, $n=sizeof($data); $i<$n; ++$i) {
             
                $pos = strpos($url, '?');
                if ($pos)
                    $url = substr($url, 0, $pos);
                $data[$i]['value'] = $url . $queryStr;
            }
            
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }    
    
    private function _getCurrentQueryString()
    {
        $params = $_GET; // ohh .. not Mage style :)
        $pageKey = Mage::getBlockSingleton('page/html_pager')->getPageVarName();
        if (isset($params[$pageKey])){
            $params[$pageKey] = null;
            unset($params[$pageKey]);
        }
        
        $queryStr = '?';
        foreach ($params as $k => $v){
            $queryStr .= $k . '=' . urlencode($v) . '&';    
        }
        
        return substr($queryStr, 0, -1);
    }
    
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
              
        foreach ($data as $itemData) {
            $obj = new Varien_Object();
            $obj->setData($itemData);
            $obj->setUrl($itemData['value']);
            
            $items[] = $obj;
        }
        $this->_items = $items;
        return $this;
    }
    
}
