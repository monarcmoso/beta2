<?php
class Singpost_Profile_Model_Invitation extends Mage_Core_Model_Abstract
{
	public function getInvite($email)
    {
    	//validate if the email has a history or record of registration
    	$registered = "SELECT invitation_id FROM aw_points_invitation WHERE email = '{$email}' AND registered = '1'";
		if(count(Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($registered)) === 0)
		{
		    $sql = "SELECT invitation_id,customer_id FROM aw_points_invitation WHERE email = '{$email}' AND status IN ('2','3','4') ORDER BY date ASC LIMIT 1";
	        $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
	        $customer_id = $data[0]['customer_id'];
	        	
	        //validate if the count is true;
	        if(count($data) > 0)
			{
				//update to status = 3
				$query = Mage::getSingleton('core/resource')->getConnection('core_read');
				$update = "UPDATE aw_points_invitation SET registered = 1 WHERE customer_id = '{$customer_id}'";
				$query->query($update);
				
				//count the number of the registered email by the user.
				//identify if the count is divisible by 5.
				$referal_count = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll("SELECT * FROM aw_points_invitation WHERE customer_id = '{$customer_id}' AND registered = 1");
				if($referal_count %5 == 0) 
				{
					//get referer information by id
					$referer = Mage::getModel("customer/customer"); 
					$referer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
					$referer->load($customer_id);
					$name = $referer->getId();
					$name = $referer->getFullName();
					$email =  $referer->getEmail();

					//get summary of the aw_points_summary, get the id
					$summary = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchRow("SELECT id,points FROM aw_points_summary WHERE customer_id = $customer_id");
					$summary_id = $summary['id'];
					$points = $summary['points'];
					//update points
					$points += 1;
					$query->query("UPDATE aw_points_summary SET points = $points WHERE customer_id = '{$customer_id}'");
					
					//insert to points transaction
					$rewardsSql = "INSERT INTO aw_points_transaction (store_id, summary_id, balance_change, balance_change_spent, action, comment, notice, change_date, customer_name, customer_email, is_locked)
									VALUES (1, $summary_id, 1, 0, 'invitee_registered', 'Reward for registration of invited users', '', NOW(), '$name', '$email', 0)";
					$query->query($rewardsSql);
					return $query->lastInsertId();
				}//end if($referal_count %5 == 0) 
			}//if(count($data) > 0)	
		}//if(count(Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($registered)) === 0)
    }//end method
}
	