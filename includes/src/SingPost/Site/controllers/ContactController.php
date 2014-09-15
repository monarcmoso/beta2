<?php
class Singpost_Site_ContactController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('pages');  
		$this->renderLayout();		
	}
}