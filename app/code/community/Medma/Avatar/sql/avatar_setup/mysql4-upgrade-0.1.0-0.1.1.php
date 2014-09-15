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
 * Try reinstalling medma_avatar attribute
 */
?>

<?php

$installer = $this;

$installer->startSetup();

$setup = Mage::getModel('customer/entity_setup', 'core_setup');
$customerEntityType = $setup->getEntityTypeId('customer');

$attributeCode = Medma_Avatar_Model_Config::AVATAR_ATTR_CODE;

#Remove if already installed
$setup->removeAttribute($customerEntityType, $attributeCode); 

#add new attribute to customer entity
$setup->addAttribute(
        $customerEntityType, 
        $attributeCode, 
        array(
                'type'              => 'varchar',
                'input'             => 'image',
                'label'             => 'Upload Avatar',
                'global'            => 1,
                'visible'           => 1,
                'required'          => 0,
                'user_defined'      => 1,
                'visible_on_front'  => 1,
         )
);

if (version_compare(Mage::getVersion(), '1.6.0', '<='))
{
      $customer = Mage::getModel('customer/customer');
      $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
      $setup->addAttributeToSet($customerEntityType, $attrSetId, 'General', $attributeCode);
}
if (version_compare(Mage::getVersion(), '1.4.2', '>='))
{
    Mage::getSingleton('eav/config')
    ->getAttribute($customerEntityType, $attributeCode)
    ->setData(
               'used_in_forms', 
                array(
                       'adminhtml_customer',
                       'customer_account_create',
                       'customer_account_edit',
                       'checkout_register'
                )
    )
    ->save();
}

$installer->endSetup(); 
