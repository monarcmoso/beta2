<?php
require_once("Mage/Customer/controllers/AccountController.php");
class Singpost_Login_AccountController extends Mage_Customer_AccountController 
{
	public $param = '';
	public function confirmAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_getSession()->logout()->regenerateSessionId();
        }
        try {
            $id      = $this->getRequest()->getParam('id', false);
            $key     = $this->getRequest()->getParam('key', false);
            $backUrl = $this->getRequest()->getParam('back_url', false);
            if (empty($id) || empty($key)) {
                throw new Exception($this->__('Bad request.'));
            }

            // load customer by id (try/catch in case if it throws exceptions)
            try {
                $customer = $this->_getModel('customer/customer')->load($id);
                if ((!$customer) || (!$customer->getId())) {
                    throw new Exception('Failed to load customer by id.');
                }
            }
            catch (Exception $e) {
                throw new Exception($this->__('Wrong customer account specified.'));
            }

            // check if it is inactive
            if ($customer->getConfirmation()) {
                if ($customer->getConfirmation() !== $key) {
                    throw new Exception($this->__('Wrong confirmation key.'));
                }

                // activate customer
                try {
                    $customer->setConfirmation(null);
                    $confirm = $customer->save();
					//analytics
					$analytics_logs = Mage::getModel("profile/analytics");
					$analytics_logs->addAnalyticsLogs('activated',$id);
                }
                catch (Exception $e) {
                    throw new Exception($this->__('Failed to confirm customer account.'));
                }

                $session->renewSession();
                // log in and send greeting email, then die happy
                $session->setCustomerAsLoggedIn($customer);
                $successUrl = $this->_welcomeCustomer($customer, true);
               //$this->_redirectSuccess($backUrl ? $backUrl : $successUrl);
                //return;
            }

            // die happy
            if($confirm){
				$layout = $this->getLayout();
				echo $layout->createBlock('profile/profile')->setTemplate('singpost/confirmation.phtml')->toHtml();
            }
			else{
				$this->_redirectSuccess($this->_getUrl('*/*/index', array('_secure' => true)));
            	return;
			}
        }
        catch (Exception $e) {
            // die unhappy
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError($this->_getUrl('*/*/index', array('_secure' => true)));
            return;
        }
	}

    public function loginPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();
		
		//checking is it from checkout
		$session->setCustomerRedirectFlag(0);
		$context = $this->getRequest()->getPost('context');
		$customerAlwaysRedirectToUrl = Mage::getStoreConfig('customer/customerredirect/always');
		if((!$customerAlwaysRedirectToUrl)){
			$session->setCustomerRedirectFlag(1);
		}
		
        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
			
			$isActive = Mage::getModel('customer/customer');
			$isActive->loadByEmail($login['username']);
			if($isActive->getisActive() == 0){
				//return error message invalid
				$session->addError($this->__('Invalid login or password.'));
				$this->_redirect('*/*/');
				return;
			}
			
            if (!empty($login['username']) && !empty($login['password'])) {
            	//check here if the user is already exist in the samplestore
                try {
                    $login = $session->login($login['username'], $login['password']);
					if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
					Mage::helper('login')->logSurvey();
					
                } catch (Mage_Core_Exception $e) {
					$migrated = Mage::getModel("singpost_login/facelift")->_checkMigrated($login['username']);
	            	if(!$migrated > 0)
	            	{
		            	$facelift = Mage::getModel("singpost_login/facelift")->facelift($login['username'], $login['password']);
	                	$customer = Mage::getModel("customer/customer"); 
	                	$customer->loadByEmail($login['username']);
						$customerid = $customer->getId();
						//1. do the change password
						if($facelift === true){
							try{
								$customer->load($customerid);
					       		$customer->setPassword($login['password']);
					        	$customer->save();
					        	//update the table
					        	$session->loginById($customerid);
								 Mage::getModel("singpost_login/facelift")->_updateStatus($login['username']);
								$this->_redirect('/profile/index');
								return;
							}
							catch(exeption $e){
								//$session->addError($this->__('Invalid login or password.'));
							}
						}
	            	}
					switch ($e->getCode()) {
					case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
					    $value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
					$message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
					    break;
					case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
					    $message = $e->getMessage();
					    break;
					default:
					    $message = $e->getMessage();
					}
					$session->addError($message);
					$session->setUsername($login['username']);
					//}
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }

    /**
     * Customer register form page
     */
    public function createAction()
    {   
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }
    	
		$this->param = $this->getRequest()->getParam('src');
		$referal_key = (!empty($this->param)) ? $this->param  : '';
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
		
		//load the model that count of the links.
		$model = Mage::getModel("profile/analytics");
		$result = $model->getReferalCode($this->param );
		if($result > 0){
			//pass the key to the front end
			Mage::register('referral_key', $referal_key);
		}
        $this->renderLayout();
    }
	
	protected function _loginPostRedirect()
    {
        $session                         = $this->_getSession();        
        $custom_login_redirect_flag      = Mage::getStoreConfig('mpcustomer/customloginredirect/active');
        $custom_login_redirect_url       = trim(Mage::getStoreConfig('mpcustomer/customloginredirect/url'));
        
        if(1 == $custom_login_redirect_flag){
            if($session->isLoggedIn()){
                $filtered_url = Mage::getUrl( ltrim( str_replace(Mage::getBaseUrl(), '', $custom_login_redirect_url), '/') );
                $session->setBeforeAuthUrl($filtered_url);
            }else{
                $session->setBeforeAuthUrl(Mage::helper('customer')->getLoginUrl());
            }
        }else{
            if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl() ) {

                // Set default URL to redirect customer to
                $session->setBeforeAuthUrl(Mage::helper('customer')->getAccountUrl());
    
                // Redirect customer to the last page visited after logging in
                if ($session->isLoggedIn())
                {
                    if (!Mage::getStoreConfigFlag('customer/startup/redirect_dashboard')) {
                        if ($referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME)) {
                            $referer = Mage::helper('core')->urlDecode($referer);
                            if ($this->_isUrlInternal($referer)) {
                                $session->setBeforeAuthUrl($referer);
                            }
                        }
                    }
                } else {
                    $session->setBeforeAuthUrl(Mage::helper('customer')->getLoginUrl());
                }
            }
        }

		if(Mage::getSingleton('customer/session')->isLoggedIn()){
			$customer_id = Mage::getSingleton('customer/session')->getId();
			$model = Mage::getModel("singpost_login/profile");
			$data = $model->getProfile($customer_id);
			//check if the user already filled-up the form
			if($data[0]['count'] == 0)
			{
				$this->_redirectUrl('/profile/settings');
			}
			else
			{
				$this->_redirectUrl($session->getBeforeAuthUrl(true));
			}
		}
		else {
			$this->_redirectUrl($session->getBeforeAuthUrl(true));
		}
    }
}
