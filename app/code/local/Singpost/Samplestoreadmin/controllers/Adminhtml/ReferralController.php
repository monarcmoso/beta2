<?php
class Singpost_Samplestoreadmin_Adminhtml_ReferralController extends Mage_Adminhtml_Controller_Action
{
	public $title = '';
	public $desc = '';
	
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }
    
	public function yesAction()
    {
        $this->loadLayout()->renderLayout();
    }
    // public function postAction()
    // {
        // $post = $this->getRequest()->getPost();
        // try {
            // if (empty($post)) {
                // Mage::throwException($this->__('Invalid form data.'));
            // }
//             
            // /* here's your form processing */
//             
            // $message = $this->__('Your form has been submitted successfully.');
            // Mage::getSingleton('adminhtml/session')->addSuccess($message);
        // } catch (Exception $e) {
            // Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        // }
        // $this->_redirect('*/*');
    // }
	
	public function saveReferralAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			$this->title = $this->getRequest()->getParam('referral_title');
			$this->desc = $this->getRequest()->getParam('referral_desc');
			$referral_title = (!empty($this->title)) ? $this->title : '';
			$referral_desc = (!empty($this->desc)) ? $this->desc : '';
			if($referral_title == ''){
				return json_encode(array('response'=>400,'message'=>'Referal title is empty'));
				exit;
			}
			if($referral_desc == ''){
				return json_encode(array('response'=>400,'message'=>'Referal description is empty'));
				exit;
			}
			//do the process of saving in here
			try {
				$model = Mage::getModel("samplestoreadmin/action");
				$referral = $model->_saveReferal($referral_title,$referral_desc);
				$referralDecode = json_decode($referral);
				if($referralDecode->response == 200){
					Mage::getSingleton('adminhtml/session')->addSuccess('You have successfully save referral code.');	
					echo json_encode(true);
				}else{
					Mage::getSingleton('adminhtml/session')->addError('Cannot save the referral. Please contact the administrator.');
					echo json_encode(false);
				}
				
			} catch (Exception $e) {
				Mage::logException($e);
            	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        	}
		}
		else{
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		}
	}

	public function testinglangAction()
	{
		$this->loadLayout()->renderLayout();
		$type = $this->getRequest()->getParam('type');
		$filename = "$type.csv";
		//get the admin session
		// Mage::getSingleton('core/session', array('name'=>'adminhtml'));
		// if(!Mage::getSingleton('admin/session')->isLoggedIn()){
			// Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));
			// exit;
		// }	
		// try {
	        // $content = Mage::helper('profile/export')->generateMlnList($type);
	       	// $this->_prepareDownloadResponse($filename, $content);
			// echo json_encode(true);
		// }
		// catch (Exception $exception){
		    // echo json_encode(false);
		// }
// 		
	}
}