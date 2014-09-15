<?php
class Singpost_Profile_Model_Dashboard extends Mage_Core_Model_Abstract
{
	public function getDashboard()
    {
        $sql = "SELECT content FROM cms_page WHERE identifier = 'dashboard'";
        $data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchRow($sql);
        return $data;
    }
	
	public function getUserProfile($customer_id)
	{
		$sql = "SELECT a.id,a.name,b.id as fieldset_id, c.id as field_id, c.name, c.result_label,e.id as result_value_id,e.field_id, e.value  
				FROM webforms a 
				LEFT JOIN  webforms_fieldsets b 
				ON a.id = b.webform_id 
				LEFT JOIN webforms_fields c
				ON b.id = c.fieldset_id AND a.id = c.webform_id
				LEFT JOIN webforms_results_values e ON e.field_id = c.id
				LEFT JOIN webforms_results d ON d.id = e.result_id
				WHERE a.code = 'profile_settings' AND d.customer_id = $customer_id";
		$data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
		return $data;
	}
	
	public function getNewsletterSubscription()
	{
		try{
			$query = "SELECT a.id,a.name,b.id as fieldset_id, c.id as field_id,c.code, c.name as field_name,c.value
			FROM webforms a 
			LEFT JOIN  webforms_fieldsets b 
			ON a.id = b.webform_id 
			LEFT JOIN webforms_fields c
			ON b.id = c.fieldset_id AND a.id = c.webform_id
			WHERE a.code = 'registration' AND c.id IN (90,91)";
			$newsletter = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($query);
			return $newsletter;
		}catch (Exception $exception){
			return false;
		}//}catch (Exception $exception){	
	}
	
	public function getUsersNewsLetter($customer_id)
	{
		try{
			$sql = "SELECT a.id,a.name,b.id as fieldset_id, c.id as field_id,c.code,c.name,c.value,e.id as result_value_id,e.field_id, e.value ,d.customer_id,d.id as result_id,e.id as result_value_id
			FROM webforms a 
			LEFT JOIN  webforms_fieldsets b 
			ON a.id = b.webform_id 
			LEFT JOIN webforms_fields c
			ON b.id = c.fieldset_id AND a.id = c.webform_id
			LEFT JOIN webforms_results_values e ON e.field_id = c.id
			LEFT JOIN webforms_results d ON d.id = e.result_id
			WHERE a.code = 'registration' AND d.customer_id = '{$customer_id}'";
			$data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
			return $data;
			// if(count($data) > 1){
				// return $data;
			// }else{
				// return false;
			// }
		}catch (Exception $exception){
			Mage::logException($e);
		}//}catch (Exception $exception){	
	}
	
	public function getNewsletter()
	{
		try{
			$query = "SELECT a.id,a.name,b.id as fieldset_id, c.id as field_id,c.code, c.name as field_name,c.value
			FROM webforms a 
			LEFT JOIN  webforms_fieldsets b 
			ON a.id = b.webform_id 
			LEFT JOIN webforms_fields c
			ON b.id = c.fieldset_id AND a.id = c.webform_id
			WHERE a.code = 'registration'";
			$newsletter = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($query);
			return $newsletter;
		}catch (Exception $exception){
			return false;
		}//}catch (Exception $exception){	
	}
	
	public function countUserFbLogin($customer_id)
	{
		$select = $this->_read()->select()
		->from('belvg_facebook_customer', array('*')) 
		->where("customer_id = '{$customer_id}'");
		$result = $this->_read()->fetchAll($select);
		return count($result);
	}
	
	public function getPdpaCount($customer_id)
	{
		$select = $this->_read()->select()
		->from('webforms_results', array('*')) 
		->where("customer_id = '{$customer_id}' AND webform_id = 16");
		$result = $this->_read()->fetchAll($select);
		return count($result);
	}
	
	public function _read(){
		return  Mage::getSingleton('core/resource') ->getConnection('core_read');
	}
}
	