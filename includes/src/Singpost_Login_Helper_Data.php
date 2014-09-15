<?php
class Singpost_Login_Helper_Data extends Mage_Core_Helper_Abstract
{
 	public function logSurvey()
	{
		//email users if they logged-in 3 times 
		$customer_id = Mage::getSingleton('customer/session')->getId();
		$store_id = Mage::app()->getStore()->getStoreId();
		
		$model = Mage::getModel("singpost_login/profile");
        $count = $model->getUserLogCount($customer_id,$store_id);
		$customer = Mage::getSingleton('customer/session')->getCustomer();
    	$customerData = Mage::getModel('customer/customer')->load($customer->getId());

		//detect if the count is 2, because the new logs is not yet being saved!
		if($count == 2)
		{
			$templateId = 3;
			$emailTemplate = Mage::getModel('core/email_template')->load($templateId);
			
			//$sender = array('name'  => 'user','email' => 'user@ekmedia.asia');                                
			$vars = array('name'=>'name');
			
			$processedTemplate = $emailTemplate->getProcessedTemplate($vars);
			
			$mail = Mage::getModel('core/email');
			$mail->setToEmail($customerData->getEmail());
			$mail->setBody($processedTemplate);
			$mail->setSubject("How's your experience with Sample Store?");
			$mail->setFromName("Sample Store");
			$mail->setType('html');// You can use 'html' or 'text'
			
			try {
			    $mail->send();
			    //Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
			    //$this->_redirect('');
			    //return true;
			}
			catch (Exception $e) {
			    //Mage::getSingleton('core/session')->addError('Unable to send.');
			    //$this->_redirect('');
			    //return false;
			}	
		}
	}
	
 	public function getDashboardUrl()
    {
        return $this->_getUrl('profile/index');
    }
	
	public function getAccountUrl()
    {
        return $this->_getUrl('profile/index');
    }
}