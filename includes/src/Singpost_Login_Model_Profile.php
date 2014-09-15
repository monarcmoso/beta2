<?php
class Singpost_Login_Model_Profile extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
       	$this->_init('singpost_login/profile');
    }
    
    public function getProfile($customer_id)
    {
        $sql = "SELECT COUNT(*)  AS count FROM webforms a INNER JOIN webforms_results b ON a.id = b.webform_id WHERE a.code = 'profile_settings' AND b.customer_id = $customer_id";
        $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
        return $data;
    }
    
    public function getNotification()
    {
        $sql = "SELECT content FROM cms_page WHERE identifier = 'account_notification'";
        $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
        return $data;
    }
    
    public function getUserLogCount($customer_id,$store_id)
    {
        $sql = "SELECT * FROM log_customer WHERE customer_id = $customer_id AND store_id = $store_id";
        $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
        return count($data);
    }

	public function closeForever($customer_id,$store_id)
	{
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql = "INSERT INTO `customer_account_notification_logs` (customer_id, store_id,action) VALUES ('$customer_id', '$store_id','1')";
		$connection->query($sql);
		$query_id= $connection->lastInsertId();
		return $query_id;
	}
	
	public function closeonce($customer_id,$store_id)
	{
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql = "INSERT INTO `customer_account_notification_logs` (customer_id, store_id) VALUES ('$customer_id', '$store_id')";
		$connection->query($sql);
		$query_id= $connection->lastInsertId();
		return $query_id;
	}
	
	public function getNotificationLogs($customer_id)
	{
		//check database if the user close it forever
		$sql = "SELECT * FROM customer_account_notification_logs WHERE action = 1 AND  customer_id = '{$customer_id}'";
        $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
		$total_rows = count($data);
		return $total_rows;
       	// if($total_rows == 0)
		// {
			// $query = "SELECT id FROM `customer_account_notification_logs` WHERE action = 0 AND customer_id = 129 AND DATE(`date_added`) = CURDATE() ORDER BY date_added";
			// $rows = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($query);
			// $count = count($rows);
			// if($count >= 3)
			// {
				// return $count;
			// }
			// else {
				// $a = 0;
				// return $a;
			// }
		// }
		// else {
			// $a = 3;
			// return $a;
		// }
	}
}