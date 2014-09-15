<?php
/**
 * SilverTouch Technologies Limited.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.silvertouch.com/MagentoExtensions/LICENSE.txt
 *
 * @category   Sttl
 * @package    Sttl_Silverbrand
 * @copyright  Copyright (c) 2011 SilverTouch Technologies Limited. (http://www.silvertouch.com/MagentoExtensions)
 * @license    http://www.silvertouch.com/MagentoExtensions/LICENSE.txt
 */
class Sttl_Silverbrand_Model_Mode_Display_Enabledisable
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('adminhtml')->__('Enable')),
            array('value'=>0, 'label'=>Mage::helper('adminhtml')->__('Disable')),
        );
    }
}
?>


