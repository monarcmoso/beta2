<?php
/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookFree
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_FacebookFree_Model_FacebookFree extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('facebookfree/facebookfree');
    }
	
	public function setSummary($customer_id)
	{
		$connection  = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
		$sql = "INSERT INTO aw_points_summary ('customer_id', 'points', 'balance_update_notification') VALUES ($customer_id, 4, 1)";
		$connection->query($sql);
		$lastInsertId = $connection->lastInsertId();
		return $lastInsertId;
	}
	
	public function countUserFbLogin()
	{
		$select = $this->_read()->select()
		->from('belvg_facebook_customer', array('*')) 
		->where("customer_id = '{$this->_customerId()}'");
		$result = $this->_read()->fetchAll($select);
		return count($result);
	}
	
	public function _customerId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
	
	public function _write()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_write');
	}

	public function _read()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}

	public function _storeId()
	{
		return Mage::app()->getStore()->getStoreId();
	}
	
	public function _websiteId()
	{
		return Mage::app()->getWebsite()->getId();
	}
}