<?php
class VES_CheckoutDiscountCode_IndexController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Validate ajax request and redirect on failure
	 *
	 * @return bool
	 */
	protected function _expireAjax()
	{
		if (!$this->getOnepage()->getQuote()->hasItems()
				|| $this->getOnepage()->getQuote()->getHasError()
				|| $this->getOnepage()->getQuote()->getIsMultiShipping()) {
			$this->_ajaxRedirectResponse();
			return true;
		}
		$action = $this->getRequest()->getActionName();
		if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
				&& !in_array($action, array('index', 'progress'))) {
			$this->_ajaxRedirectResponse();
			return true;
		}
	
		return false;
	}
	
	
	protected function _ajaxRedirectResponse()
	{
		$this->getResponse()
		->setHeader('HTTP/1.1', '403 Session Expired')
		->setHeader('Login-Required', 'true')
		->sendResponse();
		return $this;
	}
	
	/**
	 * Get shipping method step html
	 *
	 * @return string
	 */
	protected function _getShippingMethodsHtml()
	{
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onepage_shippingmethod');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		return $output;
	}
	
	/**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }
	
	/**
	 * Get one page checkout model
	 *
	 * @return Mage_Checkout_Model_Type_Onepage
	 */
	public function getOnepage()
	{
		return Mage::getSingleton('checkout/type_onepage');
	}
	
	public function submitAction()
    {
    	if ($this->_expireAjax()) {
    		return;
    	}
    	if ($this->getRequest()->isPost()) {
    		 $couponCode = trim($this->getRequest()->getPost('coupon_code'));
          
    		$result = array();

    		$this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
			$this->_getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
				->collectTotals()
				->save();
			if ($couponCode == $this->_getQuote()->getCouponCode()) {
				$result = array('error' => 0);
				$this->_getSession()->setStepData('discountcode', 'complete');
			}
			else {
				$result = array('error' => 1, 'message' => $this->__('Coupon code %s is not valid.', Mage::helper('core')->htmlEscape($couponCode)));
				//do the process of checking the coupon code for the points.
				// $points = json_decode($this->_pointsCoupon($couponCode),true);
				// if($points['error'] == true){
					// //return the error message.
					// $result = array('error' => 1);
				// }else{
					// //return success message
					// $result = array('error' => 0);
					// $this->_getSession()->setStepData('discountcode', 'complete');
				// }
			}
    		
    		if(!$result['error']) {
    			$this->getOnepage()->getQuote()->getShippingAddress()->setCollectShippingRates(true);
    			 $result['goto_section'] = 'shipping_method';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );
    		}else {
                    
            	$result['goto_section'] = 'shipping_method';
            }
    		
    		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    	}
    }
	public function indexAction(){
    
    	$this->loadLayout()->renderLayout();
    }
	
    public function _pointsCoupon($couponCode)
    {
        //$couponCode = $this->getRequest()->getParam('coupon_code');
        $customerSession = Mage::getSingleton('customer/session');

        if (!$couponCode) {
			return json_encode(array('error'=>true,'success'=>false,'message'=>'Please, enter a coupon code'));
        }

        $coupon = Mage::getModel('points/coupon')->loadByCouponCode($couponCode);

        if (!$coupon->getId()) {
        	return json_encode(array('error'=>true,'success'=>false,'message'=>'Invalid coupon code'));
        }
        $customer = $customerSession->getCustomer();

        /* 1.Webs */
        if (!$coupon->validateWebsite()) {
        	return json_encode(array('error'=>true,'success'=>false,'message'=>'You cannot use this coupon on this website'));
        }

        /*   2.Customer group    */
        if (!$coupon->validateCustomerGroup($customer)) {
        	return json_encode(array('error'=>true,'success'=>false,'message'=>'You must be included to another group of customers to activate this coupon'));
        }

        /*  3.Active/Inactive  */
        if (!$coupon->getData('status')) {
        	return json_encode(array('error'=>true,'success'=>false,'message'=>'Coupon is inactive'));
        }


        /*  4.Period of validity  */
        if (!$coupon->isStarted()) {
			return json_encode(array('error'=>true,'success'=>false,'message'=>'Coupon is not active yet'));
        }

        if ($coupon->isExpired()) {
			return json_encode(array('error'=>true,'success'=>false,'message'=>'Coupon activation period has expired'));
        }


        /* 5.Number of activations */
        if ($coupon->getData('activation_cnt') >= $coupon->getData('uses_per_coupon')) {
			return json_encode(array('error'=>true,'success'=>false,'message'=>'The limit of activations of this coupon is reached'));
        }

        $customerCouponTransaction = Mage::getModel('points/coupon_transaction')
            ->LoadByCouponIdCustomerId($coupon->getId(), $customer->getId())
            ->getData('transaction_id');
        if ($customerCouponTransaction) {
            // $customerSession->addError(Mage::helper('points')->__('You cannot activate this coupon twice'));
            // $this->_redirectReferer();
            // return $this;
			return json_encode(array('error'=>true,'success'=>false,'message'=>'You cannot activate this coupon twice'));
        }


        $transactionId = Mage::getModel('points/api')->addTransaction(
            $coupon->getData('points_amount'), 'coupon_activation', $customer
        );

        if ($transactionId) {
            Mage::getModel('points/coupon_transaction')
                ->setData('coupon_id', $coupon->getId())
                ->setData('transaction_id', $transactionId)
                ->setData('customer_id', $customer->getId())
                ->save();

            $coupon->activate();
			return json_encode(array('error'=>false,'success'=>true,'message'=>'You cannot activate this coupon twice'));
            //Mage::getSingleton('customer/session')->addSuccess(Mage::helper('points')->__('Coupon was activated'));
        } else {
        	return json_encode(array('error'=>true,'success'=>false,'message'=>'Coupon was NOT activated'));
            //Mage::getSingleton('customer/session')->addError(Mage::helper('points')->__('Coupon was NOT activated'));
            //$customerSession->setData('awpoints_coupon', null);
        }

        // $this->_redirectReferer();
        // return $this;
    }
}