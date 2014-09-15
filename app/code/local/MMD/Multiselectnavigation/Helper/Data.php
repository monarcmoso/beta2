<?php
class MMD_Multiselectnavigation_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isSearch()
    {
        $mod = Mage::app()->getRequest()->getModuleName();
        if ('catalogsearch' == $mod)
            return true;
            
        if ('multiselectnavigation' == $mod && 'search' == Mage::app()->getRequest()->getActionName())
            return true;
        
        return false;
    }
    
    public function getContinueShoppingUrl()
    {
        $url = '';
        
        $query = Mage::app()->getRequest()->getQuery();
        if ($this->isSearch()){
            $url = Mage::getModel('core/url')->getUrl('catalogsearch/result/index', array('_query'=>$query));
        }
        else {
            $category = Mage::registry('current_category');
            $rootId = Mage::app()->getStore()->getRootCategoryId();
            if ($category && $category->getId() != $rootId){
                $url = $category->getUrl();
            }
            else {
                $url = Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
            }
            //add query
            $url .= '?'; 
            $query['cat'] = null; 
            foreach ($query as $k=>$v){  
                if ($v){
                    $url .= ($k . '=' .urlencode($v) . '&');    
                }   
            }
            $url = substr($url, 0, -1);    
        }
        
        return $url;
    }
    
    public function wrapProducts($html)
    {
        //$html = str_replace('onchange="setLocation', 'onchange="adj_nav_toolbar_make_request', $html);
          
        $loaderHtml =  '<div class="mmd-multinav-progress" style="display:none"><img src="'. Mage::getDesign()->getSkinUrl('images/mmd-multinav-progress.gif') .'" /></div>';  
        $html .= $loaderHtml;
        
        if (Mage::app()->getRequest()->isXmlHttpRequest()){
            $html = str_replace('?___SID=U&amp;', '?', $html);
            $html = str_replace('?___SID=U', '', $html);
            $html = str_replace('&amp;___SID=U', '', $html);
            
            $k = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
            $v = Mage::helper('core')->urlEncode($this->getContinueShoppingUrl());
            $html = preg_replace("#$k/[^/]+#","$k/$v", $html);
            
        }
        else {
            $html = '<div id="mmd-multinav-container">'
                  . $html
                  . '</div>';
                 // . '<script>adj_nav_toolbar_init()</script>';
        }    
        return $html;        
    }
}
