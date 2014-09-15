<?php
/**
 * This class overrides the _loginPostRedirect method of default AccountController so as to tweak the redirection URL.
 * @category   MagentoPycho
 * @package    MagentoPycho_Customer
 * @author     developer@magepsycho.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once 'Mage/Customer/controllers/AccountController.php';
class MagentoPycho_Customer_AccountController extends Mage_Customer_AccountController
{
    public function loginPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
					}
					//email the user when they logged in 3 times
					//$this->logSurvey();
					Mage::helper('login')->logSurvey();
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }
		$this->_loginPostRedirect();
    }

	// public function logSurvey()
	// {
		// //email users if they logged-in 3 times 
		// $customer_id = Mage::getSingleton('customer/session')->getId();
		// $store_id = Mage::app()->getStore()->getStoreId();
// 		
		// $model = Mage::getModel("singpost_login/profile");
        // $count = $model->getUserLogCount($customer_id,$store_id);
// 		
		// $data = $model->getLogsProfileInfo($customer_id);
		// //return $data['email'];
		// //detect if the count is 2, because the new logs is not yet being saved!
		// if($count['count'] == 2)
		// {
			// $templateId = 4;
			// $emailTemplate = Mage::getModel('core/email_template')->load($templateId);
// 			
			// //$sender = array('name'  => 'user','email' => 'user@ekmedia.asia');                                
			// //$vars = array('customer'=>$customer);
// 			
			// $processedTemplate = $emailTemplate->getProcessedTemplate($vars);
// 	
			// $mail = Mage::getModel('core/email');
			// $mail->setToEmail($data['email']);
			// $mail->setBody($processedTemplate);
			// $mail->setSubject('The Sample Store');
			// $mail->setFromName("Sample Store");
			// $mail->setType('html');// You can use 'html' or 'text'
// 			
			// try {
			    // $mail->send();
			    // //Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
			    // //$this->_redirect('');
			// }
			// catch (Exception $e) {
			    // //Mage::getSingleton('core/session')->addError('Unable to send.');
			    // //$this->_redirect('');
			// }	
		// }
	// } 
	    
   /**
     * Overriding defaults redirect URL 
     * Define target URL and redirect customer after logging in
     */
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
        //$this->_redirectUrl($session->getBeforeAuthUrl(true));        
    }
}
