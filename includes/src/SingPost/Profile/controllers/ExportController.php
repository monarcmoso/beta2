<?php
class Singpost_Profile_ExportController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$type = $this->getRequest()->getParam('type');
		$filename = "$type.csv";
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