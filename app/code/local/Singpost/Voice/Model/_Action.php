<?php
class Singpost_Voice_Model_Action extends Mage_Core_Model_Abstract
{	
	public function reviewData($attributeValue,$_id)
	{
		try {	
			$_individualUsersQuery = "SELECT 
				a.name,a.code, 
				b.id as field_id,b.name as field_set,b.code as field_code, b.result_label as field_label,
				c.value as result_value,c.result_id as image_result_id, c.field_id as image_field_id, c.key,
				d.customer_id,d.created_time as date 
				FROM 
				webforms a
				LEFT JOIN webforms_fields b ON a.id = b.webform_id
				LEFT JOIN webforms_results_values c ON b.id = c.field_id
				LEFT JOIN webforms_results d ON d.id = c.result_id
				WHERE a.code = '{$attributeValue}' AND d.customer_id = {$_id}";
			return $this->_read()->fetchAll($_individualUsersQuery);
		}catch (Exception $exception){
			Mage::logException($exception);
		    return json_encode(array('response'=>400, 'message'=>'Failed'));
		}
	}
	
	public function _reviews($attributeValue,$offset = 0, $limit = 4)
	{
		$sql = "SELECT a.name,a.code,d.customer_id,d.created_time as date 
				FROM 
				webforms a
				LEFT JOIN webforms_results d ON d.webform_id = a.id
				WHERE a.code = '{$attributeValue}' ORDER BY d.customer_id DESC LIMIT $offset,$limit";
		$arr = $this->_read()->fetchAll($sql);
		return $arr;
	}
	
	public function _getAllReviews($attributeValue)
	{
		$sql = "SELECT 
			a.name,a.code, 
			b.id as field_id,b.name as field_set,b.code as field_code, b.result_label as field_label,
			c.value as result_value,
			d.customer_id,d.created_time as date 
			FROM 
			webforms a
			LEFT JOIN webforms_fields b ON a.id = b.webform_id
			LEFT JOIN webforms_results_values c ON b.id = c.field_id
			LEFT JOIN webforms_results d ON d.id = c.result_id
			WHERE a.code = '{$attributeValue}' ORDER BY d.created_time DESC";
		$arr = $this->_read()->fetchAll($sql);
		return $arr;
	}
	
	public function _getAllQuestions($attributeValue){
		$sql = "SELECT a.name,a.code, b.name,b.code as field_code,b.result_label
		FROM 
		webforms a
		LEFT JOIN webforms_fields b ON b.webform_id = a.id
		WHERE a.code = '{$attributeValue}' AND b.code NOT IN ('will_you_buy_it','review_comment','review_image')";
		$arr = $this->_read()->fetchAll($sql);
		return $arr;
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