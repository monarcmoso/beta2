<?php
class Singpost_Profile_NotificationsController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		//validate user  if login
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		// else {
	        // $model = Mage::getModel("singpost_login/profile");
	        // $data = $model->getProfile($this->_customerId());
	        // if($data[0]['count'] == 0)
			// {
				// Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			// }
		// }
		$collection = Mage::getModel('sales/order')
	                ->getCollection()
	                ->addAttributeToFilter('customer_email',array('like'=>$this->_customerEmail()));
					
		$logs = mage::getModel("profile/action");
		$user_logs = $logs->getUserEditLogs();
		
		$data = array();
		foreach($collection as $key => $order){
		    //do somethin
            $date = new DateTime($order->getCreatedAt());
			$result = $date->format('d-m-Y');
			//explode the date
			$dateExplode = explode("-", $result);
			$monthName = date("F", mktime(0, 0, 0, $dateExplode[1], 10));
			$date = $dateExplode[0]." ".$monthName." ".$dateExplode[2];
			//filter all by dates
			$timeDiff = $this->_checkDate($date);
			if($timeDiff <= 3){
				$data[] = array('date_string'=>$date,'state'=>$order->getState(),'type'=>'sales','date'=>$order->getCreatedAt(),'order_id'=> $order->getId());	
			}			
		}
		
		$userLogs = array();
		foreach($user_logs as $logs)
		{
			$logsDate = new DateTime($logs['date_added']);
			$logsResult = $logsDate->format('d-m-Y');
			$logsDateExplode = explode("-", $logsResult);
			$logsMonthName = date("F", mktime(0, 0, 0, $dateExplode[1], 10));
			$dateLogs = $logsDateExplode[0]." ".$logsMonthName." ".$logsDateExplode[2];
			//filter all by dates
			$_filterDate = $this->_checkDate($dateLogs);
			if($_filterDate <= 3){
				$userLogs[] = array('date_string'=>$dateLogs,'state'=>$logs['attribute_code'],'type'=>'edit_logs','date'=>$logs['date_added'],'order_id'=>'');
			}
		}
		$notif = array_merge_recursive($data,$userLogs);			
		// load the page of the notification.
		$this->loadLayout();
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('profile');
		$block->setNotification($notif);
		$this->renderLayout();
		
	}
	
	public function _checkDate($date){
		$dateAdded = date("d-m-Y", strtotime($date));
		$dateToday = date('d-m-Y');
		return abs($dateToday - $dateAdded);
	}
	public function _customerId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
	
	public function _customerEmail()
	{
		return Mage::getSingleton('customer/session')->getCustomer()->getEmail();
	}
}?>