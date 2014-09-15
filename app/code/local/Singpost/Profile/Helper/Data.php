<?php
class Singpost_Profile_Helper_Data extends Mage_Core_Helper_Abstract
{
 	public function sendNewEmailVerification($email,$verification)
	{
			$templateId = 4;
			$emailTemplate = Mage::getModel('core/email_template')->load($templateId);
			$verification_link = Mage::getBaseUrl()."/profile/activation/email/key/$verification";
			
			$body = '<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">';
			$body .= '<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">';
			$body .= '<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%"><tr><td align="center" valign="top" style="padding:20px 0 20px 0">';
			$body .= '<table bgcolor="FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">';
			$body .= '<tr><td valign="top">';
			$body .= '<h1 style="font-size:12px; font-weight:normal; line-height:22px; margin:0 0 11px 0;">Hey,</h1>';
			$body .= '<p>You are getting this email because you requested to change the email address saved in your Sample Store account.</p>';
			$body .= '<p>If this is correct, click on the link below to confirm changing your email, or copy and paste it into your browser : <a href="'.$verification_link.'">'.$verification_link.'</a></p>';
			$body .= '<p>To make sure you get your payment updates, please mark this mail as "Not Spam / Not Junk".</p>';
			$body .= '<p>Thanks, <br/>Sample Store Team</p></td></tr></table>';
			$body .= '</td></tr></table></div></body>';

			$mail = Mage::getModel('core/email');
			$mail->setToEmail($email);
			$mail->setBody($body);
			$mail->setSubject('Change email address in your Sample Store account');
			$mail->setFromName("Sample Store");
			$mail->setType('html');// You can use 'html' or 'text'
			try {
			    $mail->send();
				return true;
			}
			catch (Exception $e) {
				return $e;
			}	
	} 
	
	// public function fbRedirect()
	// {
		// $customer_id = Mage::getSingleton('customer/session')->getId();
		// $model = Mage::getModel("facebookfree/facebookfree");
		// $fbLoginCount = $model->countUserFbLogin();
// 		
		// $profileModel = Mage::getModel("singpost_login/profile");
        // $data = $profileModel->getProfile($customer_id);
		// // if($fbLoginCount > 0){
			// // $customer = Mage::getModel("customer/customer"); 
			// // $customer->load($customer_id);
			// // if($customer->getDob() == '1920-04-30 00:00:00'){
				// // Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index/fbprofile'));
			// // }
			// // else{
				// // Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			// // }
		// // }
		// //else if($data[0]['count'] == 0)
		// if($data[0]['count'] == 0)
		// {
			// if($fbLoginCount > 0){
			// $customer = Mage::getModel("customer/customer"); 
			// $customer->load($customer_id);
				// if($customer->getDob() == '1920-04-30 00:00:00'){
					// Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index/fbprofile'));
				// }
				// else{
					// Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));	
				// }
			// }
			// else{
				// Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));	
			// }
		// }
	// }
}