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


class AW_Points_Model_Actions_AddedByAdmin extends AW_Points_Model_Actions_Abstract
{
    protected $_action = 'added_by_admin';

    public function getComment()
    {
        if (isset($this->_commentParams['comment'])) {
            return $this->_commentParams['comment'];
        }
        return $this->_comment;
    }

    public function getCommentHtml($area = self::ADMIN)
    {
        return Mage::helper('points')->__($this->_transaction->getComment());
    }
}