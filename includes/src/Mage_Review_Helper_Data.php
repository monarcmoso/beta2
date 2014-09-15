<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Review
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Default review helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Review_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_REVIEW_GUETS_ALLOW = 'catalog/review/allow_guest';

    public function getDetail($origDetail){
        return nl2br(Mage::helper('core/string')->truncate($origDetail, 50));
    }

    /**
     * getDetailHtml return short detail info in HTML
     * @param string $origDetail Full detail info
     * @return string
     */
    public function getDetailHtml($origDetail){
        return nl2br(Mage::helper('core/string')->truncate($this->escapeHtml($origDetail), 50));
    }

    public function getIsGuestAllowToWrite()
    {
        return Mage::getStoreConfigFlag(self::XML_REVIEW_GUETS_ALLOW);
    }

    /**
     * Get review statuses with their codes
     *
     * @return array
     */
    public function getReviewStatuses()
    {
        return array(
            Mage_Review_Model_Review::STATUS_APPROVED     => $this->__('Approved'),
            Mage_Review_Model_Review::STATUS_PENDING      => $this->__('Pending'),
            Mage_Review_Model_Review::STATUS_NOT_APPROVED => $this->__('Not Approved'),
        );
    }

    /**
     * Get review statuses as option array
     *
     * @return array
     */
    public function getReviewStatusesOptionArray()
    {
        $result = array();
        foreach ($this->getReviewStatuses() as $k => $v) {
            $result[] = array('value' => $k, 'label' => $v);
        }

        return $result;
    }
    public function setPoints($customer_id, $newpoints, $webform_id)
    {
            $sql = "select id from aw_points_summary where customer_id=$customer_id";
            $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchRow($sql);
            //return $data['customer_id'];
            $points_summary_id = $data['id'];
            //echo $lastInsertId;
 $db = Mage::getSingleton('core/resource')->getConnection('core_read');
	    $sql = "SELECT * FROM aw_points_transaction where summary_id=$points_summary_id and webform_id=$webform_id";
	   $rows = $db->fetchAll($sql);
            //echo $lastInsertId;
	if(empty($rows)){
            $customerData = Mage::getModel('customer/customer')->load($customer_id)->getData();
            
            $comment = 'Added 1 Point for Review';
            $connection  = Mage::getSingleton('core/resource')->getConnection('core_write');
            $sql2 = "update aw_points_summary  set points=$newpoints where customer_id=$customer_id";
            $connection->query($sql2);
           
            //insert to points transaction
		
            $email = $customerData['email']; 
            $name = $customerData['full_name'];
            $deductsSql = "INSERT INTO aw_points_transaction (store_id, summary_id, balance_change, balance_change_spent, action, comment, notice, change_date, customer_name, customer_email, is_locked, webform_id)  VALUES (1, $points_summary_id, 1, 0, 'review_approved', '$comment', '', NOW(), '$name', '$email', 0, $webform_id)";
            
            $connection->query($deductsSql);
          }
          
            return true;

    }
}
