<?php

class MMD_Multiselectnavigation_Model_Catalog_Layer_Filter_Categorysearch extends Mage_Catalog_Model_Layer_Filter_Category
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
        $key = $this->getLayer()->getStateKey().'_SEARCH_SUBCATEGORIES';
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $category   = $this->getCategory();
            
            /** @var $categoty Mage_Catalog_Model_Categeory */
            $categories = $category->getChildrenCategories();

            $data = array();
            $level = 0;
//            $parentId = 0;
            if ($category->getLevel() > 1){ // current category is not root
                $parent = $category->getParentCategory();
                
                ++$level;
                if ($parent->getLevel()>1){
                    $data[] = array(
                        'label' => $parent->getName(),
                        'value' => $parent->getId(),
                        'count' => 0,
                        'level' => $level,
                    );
//                    $parentId = $parent->getId();
//                    $categories->addItem($parent);
                    
                }
                //always include current category
                ++$level;
                $data[] = array(
                    'label' => $category->getName(),
                    'value' => '',
                    'level' => $level,
                    'is_current' => true,
                );
            }
            
            $this->getLayer()->getProductCollection()
                ->addCountToCategories($categories);
                
//            if ($parentId){
//                $data[0]['count'] = $parent->getProductCount();
//                $categories->removeItemByKey($parentId);
//            }    
            
            ++$level;
            foreach ($categories as $cat) {
                if ($cat->getIsActive() && $cat->getProductCount()) {
                    $data[] = array(
                        'label' => $cat->getName(),
                        'value' => $cat->getId(), 
                        'count' => $cat->getProductCount(),
                        'level' => $level,
                    );
                }
            }
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }

    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
        foreach ($data as $itemData) {
            $obj = Mage::getModel('catalog/layer_filter_item');
            $obj->setData($itemData);
            $obj->setFilter($this);
            
            $items[] = $obj;
        }
        $this->_items = $items;
        return $this;
    }    
    
}