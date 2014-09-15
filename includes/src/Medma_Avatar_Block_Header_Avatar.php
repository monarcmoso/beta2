<?php
/**
 * Medma Avatar Module.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Team
 * that is bundled with this package of Medma Infomatix Pvt. Ltd.
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Medma Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */




/*
 * display avatar in magento header
 */

class Medma_Avatar_Block_Header_Avatar extends Mage_Core_Block_Template {
    
    protected  $_avatar = null;
    
    const DEFAULT_WIDTH = 75;
    
    const DEFAULT_HEIGHT = 75;
    

    public function __construct() {
        parent::__construct();
        $customer = $this->getCustomerFromSession();
        if($customer){
            $customerObj = Mage::getModel('customer/customer')->load($customer->getId());
            if($avatar = $customerObj->getMedmaAvatar()){
                $this->_avatar = $avatar; 
            }else{
                $this->_avatar = null;     
            }
        }
        
    }
    
    protected function getCustomerFromSession(){
        return Mage::getSingleton('customer/session')->getCustomer();
    }
    
    protected function getWidth(){
        $configWidth = (int)Mage::getStoreConfig(
                          'customer/avatar_group/avatar_field_width',
                          Mage::app()->getStore()
                        );
        
        if($configWidth > 0){
            $width = $configWidth;
        }else{
            $width = self::DEFAULT_WIDTH;
        }
        
        return $width;
    }
    
    protected function getHeight(){
        $configHeight = (int)Mage::getStoreConfig(
                           'customer/avatar_group/avatar_field_height',
                           Mage::app()->getStore()
                        );
        
        if($configHeight > 0){
            $height = $configHeight;
        }else{
            $height = self::DEFAULT_HEIGHT;
        }
        
        return $height;
    }
    
    public function getAvatar(){
        return $this->_avatar;
    }
    public function getAvatarPath(){
        /*if($file = $this->getAvatar()){
            $path = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) 
                    . 'customer' 
                    . $file;
            return $path; 
        }*/
		// return value is http://magestore.samplestore.com/avatar/customer/viewAvatar/
        return $this->getUrl('avatar/customer/viewAvatar');
    }
    
    public function getAvatarHtml(){
        
		$parsed = parse_url($this->getAvatar());
		if (empty($parsed['scheme'])) {
			$html = "<img src='".$this->getAvatarPath()."' width ='".$this->getWidth()."' height='".$this->getHeight() ."'/>";
		}
		else
		{
			$html = "<img src='".$this->getAvatar()."' width ='".$this->getWidth()."' height='".$this->getHeight() ."'/>";
		}
        return $html;
    }
    
    public function getUploadUrl(){
        return Mage::getUrl('*/customer/upload');
    }
}
