<?php

class VES_CheckoutDiscountCode_Model_Observer {

    /**
     * Make sure after save billing step it go to vendor step
     * @param unknown_type $observer
     */
    public function controller_action_postdispatch_checkout_onepage_saveBilling($observer) {
        if (!Mage::getStoreConfig('checkoutdiscountcode/config/enable'))
            return;

        
        $controller = $observer->getData('controller_action');
        $body = Mage::helper('core')->jsonDecode($controller->getResponse()->getBody());
        if ($body['goto_section'] == 'shipping_method')
            $body['goto_section'] = 'discountcode';
        $body['update_section'] = array(
            'name' => 'shipping-method',
            'html' => $this->_getVendorHtml($controller)
        );
        $body['allow_sections'] = array('billing', 'shipping', 'discountcode');
        $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($body));
    }

    /**
     * Make sure after save shipping step it go to vendor step
     * @param unknown_type $observer
     */
    public function controller_action_postdispatch_checkout_onepage_saveShipping($observer) {
        if (!Mage::getStoreConfig('checkoutdiscountcode/config/enable'))
            return;

        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT * FROM salesrule_coupon AS a INNER JOIN salesrule AS b ON a.rule_id = b.rule_id";

        $rows = $db->fetchAll($sql);

        $current_date1 = time();
        $active = 0;
        $expired = FALSE;
        $launch = FALSE;

        foreach ($rows as $row) {
            //check if coupon has activated.
            $active = $row['is_active'];

            if ($row['to_date'] != null)
                $end_datetime = $row['to_date'];

            $start_datetime = $row['from_date'];
            //check if coupon has expired
            if ($end_datetime && $row['to_date'] != null) {
                $end_datetime = date('Y-m-d', strtotime($end_datetime));
                //check expiry date
                if (strtotime($end_datetime) >= $current_date1) :
                    $expired = FALSE;
                else:
                    $expired = TRUE;
                endif;
            }

            //check if coupon has started
            if ($start_datetime && $row['from_date'] != null) {
                $start_datetime = date('Y-m-d', strtotime($start_datetime));

                //check expiry date
                if (strtotime($start_datetime) <= $current_date1) :
                    $launch = FALSE;
                else:
                    $launch = TRUE;
                endif;
            }
            //check all
        }
        if ($launch || $expired || !$active) {
            $page = 'shipping_method';
        } else {
            $page = 'discountcode';
        }
        
        $controller = $observer->getData('controller_action');
        $body = Mage::helper('core')->jsonDecode($controller->getResponse()->getBody());
        if ($body['goto_section'] == 'shipping_method')
            $body['goto_section'] = 'discountcode';
        $body['update_section'] = array(
            'name' => 'shipping-method',
            'html' => $this->_getVendorHtml($controller)
        );
        $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($body));
    }

    /**
     * Get shipping method step html
     *
     * @return string
     */
    protected function _getVendorHtml($controller) {
        $layout = $controller->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_discountcode');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

}