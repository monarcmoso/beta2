<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Points
 * @version    1.7.3
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Points_Model_Total_Pdf_Points extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * Retrieve Points amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getSource()->getMoneyForPoints();
    }

    /**
     * Retrieve Points title
     *
     * @return string
     */
    public function getTitle()
    {
        return Mage::helper('points/config')->getPointUnitName($this->getOrder()->getStoreId());
    }
}