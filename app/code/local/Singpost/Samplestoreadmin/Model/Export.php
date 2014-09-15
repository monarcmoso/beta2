<?php
class Singpost_Samplestoreadmin_Model_Export extends Mage_Core_Model_Abstract
{
	public function getCustomerEmail($type)
	{
		$sql = "SELECT 
				a.email,
				b.name,b.code,
				c.id as field_set, c.name as field_name, 
				d.value as result_value
				FROM webforms b
				LEFT JOIN webforms_fields c
				ON b.id = c.webform_id
				LEFT JOIN webforms_results_values d
				ON c.id = d.field_id
				LEFT JOIN webforms_results e
				ON e.id = d.result_id
				LEFT JOIN customer_entity a
				ON a.entity_id = e.customer_id
				WHERE b.code = 'registration'
				AND a.email NOT IN ('')
				AND d.value NOT IN ('')
				AND c.code = '$type'";
		//return $this->_read->fetchAll($registered);
		$data = $this->_read()->fetchAll($sql);
		return $data;
	}
	
	public function _write()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_write');
	}
	
	public function _read()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}	
}
?>