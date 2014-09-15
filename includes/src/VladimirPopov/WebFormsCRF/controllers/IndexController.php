<?php
require_once 'Mage/Customer/controllers/AccountController.php';

class VladimirPopov_WebFormsCRF_IndexController extends Mage_Customer_AccountController{

	public function createAction(){
		//validate captchas
		if($this->is_valid_submission($this->getRequest()->getPost('isSubmitClicked'),$this->getRequest()->getPost('sf_text')) === false)
		{
			//if the return is false redirect to the homepage
			//Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
			$redirect_url = Mage::getBaseUrl();
			$response = array('success'=>false,'errors'=>'Invalid Registration',"success_text"=>"",'redirect_url'=>$redirect_url);
			//$this->getResponse()->setBody(htmlspecialchars(json_encode($response), ENT_NOQUOTES));
			echo $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			exit;
		}
		
		//do process
		$session = $this->_getSession();
		$webform = Mage::getModel('webforms/webforms')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load(Mage::app()->getRequest()->getPost('webform_id'));
		$webform->setData('disable_captcha',!Mage::helper('webformscrf')->showCaptchaRegistration());

		$result = array(
			'success' => false, 
			'errors' => array(), 
			'success_text' => '',
			'redirect_url' => '',
			'referral'=>''
		);
		
		$result['redirect_url'] = Mage::getUrl('customer/account/index', array('_secure'=>true));  

		if($webform->getRedirectUrl())
				$result['redirect_url'] = Mage::getUrl($webform->getRedirectUrl(), array('_secure'=>true));

		$errors = $webform->validatePostResult();
		$collection = Mage::getResourceModel('customer/customer_collection')
			->addAttributeToSelect('email')
			->addAttributeToFilter('email',$this->getRequest()->getPost('email'));

        if(Mage::getStoreConfig('customer/account_share/scope')){
            $collection->addAttributeToSelect('website_id')->addAttributeToFilter('website_id',Mage::app()->getWebsite()->getId());
        }
			
		if($collection->getFirstItem()->getData('entity_id')){
			$errors[]= $this->__('Customer email already exists');
		}
		
		if(!count($errors)){
			// register customer
			$this->createPostAction();
			$register_errors = $session->getMessages()->getItems();
			foreach($register_errors as $err){
				if($err->getType() == Mage_Core_Model_Message::ERROR){
					$message = $err->getCode();

					// fix for inactive accounts
					if($message != Mage::helper('core')->__('This account is not activated.'))
						$errors[]= $message;
					
					// clear error
                    if(is_object($message) && get_class($message) == 'Mage_Core_Model_Message_Error' && method_exists($message, 'clear')) $message->clear();
				}
			}
		}
		if(!count($errors)){
			$result['success'] = true;				
		}else{
			//clear session message to not duplicate the data
			$session->getMessages(true);
		}
		
		$result['errors'] = implode('\n',$errors);
		
		if($result['success'] == true)
		{
			$customer_id = Mage::getSingleton('customer/session')->getId();
			//add points to the user
			$points = Mage::getModel('webforms/webforms');
			$points->setPoints($this->getRequest()->getPost('email'));	
			 $result['referral'] = $customer_id;
			//add referal points
			// $invitation = Mage::getModel("profile/invitation");
			// $invite = $invitation->getInvite($this->getRequest()->getPost('email'));
			// if($invite > 0)
			// {
				// $result['referral'] = 'true';
			// }
			// else {
				// $result['referral'] = 'false';
			// }
			
			//analytics
			$analytics_logs = Mage::getModel("profile/analytics");
			$analytics_logs->addAnalyticsLogs('registration',$customer_id);
		}
		$this->getResponse()->setBody(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
	}
	
	public function editAction(){
		
		$session = $this->_getSession();
		$webform = Mage::getModel('webforms/webforms')->load(Mage::app()->getRequest()->getPost('webform_id'));
		$webform->setData('disable_captcha',true);
		
		$result = array(
			'success' => false, 
			'errors' => array(), 
			'success_text' => '',
			'redirect_url' => '',
		);
		
		$errors = $webform->validatePostResult();
		
		if(!count($errors)){
			// register customer
			$this->editPostAction();
			$register_errors = $session->getMessages()->getItems();
			foreach($register_errors as $err){
				if($err->getType() == Mage_Core_Model_Message::ERROR){
					$errors[] = $err->getCode()->clear();
				}
			}
		}
		
		if(!count($errors)){
			$result['success'] = true;
			$result['redirect_url'] = Mage::getUrl('customer/account/edit', array('_secure'=>true));
		}
		
		$result['errors'] = implode('\n',$errors);

		$this->getResponse()->setBody(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
	}
	
	
	protected function _redirectError($defaultUrl){
		return $this;
	}
	
	protected function _redirectSuccess($defaultUrl){
		return $this;
	}
	
	 protected function _redirect($path, $arguments=array()){
		return $this;
	 }
	 
	 private function is_valid_submission($isclicked,$sftext)
	 {
	 	//validate only the hidden text field and isClicked
	 	$return_val = false;
		//if ($isclicked=='1' && $sftext == '' && $sfyear=date('Y'))
		if ($isclicked=='1' && $sftext == '') 
		{
			 $return_val = true; 
		}	
		return $return_val;
	 }
	 
	protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
    {
        $session = $this->_getSession();
        if ($customer->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store*/
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId()
            );
            $customerHelper = $this->_getHelper('customer');
            //session
            //$session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
            //$customerHelper->getEmailConfirmationUrl($customer->getEmail())));
			
			//ito yun dinagdag ko para auto login
			$session->setCustomerAsLoggedIn($customer);
            $session->renewSession();
            $url = $this->_welcomeCustomer($customer);
        } else {
            $session->setCustomerAsLoggedIn($customer);
            $session->renewSession();
            $url = $this->_welcomeCustomer($customer);
        }
        $this->_redirectSuccess($url);
        return $this;
    }
}
?>
