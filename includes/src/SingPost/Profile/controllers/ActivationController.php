<?php
class Singpost_Profile_ActivationController extends Mage_Core_Controller_Front_Action
{
	public function emailAction()
	{
		//1. check if not empty
		$param = $this->getRequest()->getParam('key');
		$key = !empty($param) ? $param : '';
		if($key == ''){
			//refidirect to the homepage
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		}//if($key == ''){
		else {
			//2. check if the activation key is 40
			if(strlen($key) != 40){
				//redirect to the homepage
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
			}//if(strlen($key) != 40){
			else {
				//3. load the model and update the email address in the model
				$model = Mage::getModel("profile/action");
				$query = $model->getEditEmail($key);
				if($query['response'] == 200){
					Mage::getSingleton('core/session')->addSuccess($query['message']);
	    			$this->_redirect('/');	
				}
				else {
					Mage::getSingleton('core/session')->addError($query['message']);
	    			$this->_redirect('/');	
				}
			}//else {
		}//else {
	}
}