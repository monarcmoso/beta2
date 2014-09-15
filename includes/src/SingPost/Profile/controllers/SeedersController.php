<?php
class Singpost_Profile_SeedersController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		else {
			$customer_id = Mage::getSingleton('customer/session')->getId();
	        $model = Mage::getModel("singpost_login/profile");
	        $data = $model->getProfile($customer_id);
	        if($data[0]['count'] == 0)
			{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			}
		}
		
		$this->loadLayout();
		$this->renderLayout();
    }
}