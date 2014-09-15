<?php
class Singpost_Profile_CouponController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
    	$customer_id = Mage::getSingleton('customer/session')->getId();
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		else {
	        $model = Mage::getModel("singpost_login/profile");
	        $data = $model->getProfile($customer_id);
	        if($data[0]['count'] == 0)
			{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			}
		}
		
		// print "<pre>";
		// var_dump($sales);
		// exit;
		// foreach ($sales as $order) {
		    // $email = $order->getCustomerEmail();
			// echo "------------------------- <br/>";
		    // echo 'ID : '.$order->getId().'<br/>';
			// echo 'Coupon : '.$order->getCouponCode().'<br/>';
			// echo 'Status : '.$order->getStatus().'<br/>';
			// echo 'Gift : '.$order->getDiscountDescription().'<br/>';
			// echo 'Date : '.$order->getCreatedAt().'<br/>';
		// }
		
		$this->loadLayout();
		$this->renderLayout();
	}
}