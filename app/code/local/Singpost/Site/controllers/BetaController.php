<?php
class Singpost_Site_BetaController extends Mage_Core_Controller_Front_Action
{
	public function guidelinesAction()
	{
		$this->loadLayout();
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('pages');
		// $block->setDashboard($dashboard);    
		// $block->setPopup($notif_logs);    
		$this->renderLayout();		
	}
}