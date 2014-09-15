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
 * @category    Lanot
 * @package     Lanot_EasyBanner
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Category admin edit tabs block
 *
 * @author Lanot
 */
class Lanot_EasyBanner_Block_Adminhtml_Category_Edit_Tabs
	extends Mage_Adminhtml_Block_Widget_Tabs
{
	/**
	 * Initialize tabs and define tabs block settings
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->_getHelper()->__('Category Info'));
	}

    /**
     * @return Lanot_EasyBanner_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easybanner');
    }

    protected function _prepareLayout()
    {
        $this->addTab('main', array(
            'label'   => $this->_getHelper()->__('Category info'),
            'content' => $this->getLayout()->createBlock('lanot_easybanner/adminhtml_category_edit_tab_main')->toHtml(),
        ));

        $params = array(
            '_current'  => true,
            'category_id' => (int) $this->_getHelper()->getCategoryItemInstance()->getId(),
        );
        $this->addTab('banners', array(
            'label'  => $this->_getHelper()->__('Banners'),
            'url'    => $this->getUrl('*/*/ajaxbannersgrid', $params),
            'class'  => 'ajax',
        ));

        return parent::_prepareLayout();
    }
}
