<?php
class Singpost_Samplestoreadmin_Adminhtml_ExportController extends Mage_Core_Controller_Front_Action
//Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$type = $this->getRequest()->getParam('type');
		$filename = "$type.csv";
		//get the admin session
		Mage::getSingleton('core/session', array('name'=>'adminhtml'));
		if(!Mage::getSingleton('admin/session')->isLoggedIn()){
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));
			exit;
		}	
		try {
	        $content = Mage::helper('profile/export')->generateMlnList($type);
	       	$this->_prepareDownloadResponse($filename, $content);
			echo json_encode(true);
		}
		catch (Exception $exception){
		    echo json_encode(false);
		}
	}
}