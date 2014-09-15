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
class Medma_Avatar_Model_Observer extends Varien_Object{
    
    public function saveCustomerAvatar($observer){
        $fileName = null;
        $customer = $observer->getEvent()->getCustomer();
        if(isset($_FILES['avatar-file'])){
            $avatarFile = $_FILES['avatar-file'];
            $avatar = Mage::getModel('avatar/avatar');
            $avatar->setAvatarFileData($avatarFile);
            try{
                $fileName = $avatar->saveAvatarFile();
                $customer->setData(Medma_Avatar_Model_Config::AVATAR_ATTR_CODE, $fileName);
            }catch(Exception $e){
                Mage::logException($e);
            }
        }    
        
        return $this;
    }   
    
}