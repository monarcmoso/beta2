<?php

/*
 * @category   Mage
 * @package    Inic_Creditpayment
 */

$installer = $this;

$installer->startSetup();

$installer->addAttribute('customer', 'credit_limit', array(
    'label'         => 'Credit limit',
    'visible'       => 1,
    'type'            => 'decimal',
    'required'      => 0,
    'position'      => 1,
    'sort_order'    => 180
));


$installer->run("
  -- DROP TABLE IF EXISTS {$this->getTable('customers_credit_applied')};
  
  CREATE TABLE `{$this->getTable('customers_credit_applied')}` (
  `id` INT(10) unsigned NOT NULL auto_increment,
  `customer_id` INT(10) NOT NULL,
  `order_id` INT(10) NOT NULL,
  `applied_amount` DECIMAL(12,2) NOT NULL,
  `created_time` datetime NULL,
   PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

");


$customerattrubute = Mage::getModel('customer/attribute')->loadByCode('customer', 'credit_limit');
$forms=array('adminhtml_customer');
$customerattrubute->setData('used_in_forms', $forms);
$customerattrubute->save();

$installer->endSetup();
