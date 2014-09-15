<?php
class MMD_Multiselectnavigation_Block_Catalog_Layer_Filter_Categorysearch extends MMD_Multiselectnavigation_Block_Catalog_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('multiselectnavigation/filter_category_search.phtml');
        $this->_filterModelName = 'multiselectnavigation/catalog_layer_filter_categorysearch'; 
    }
}
