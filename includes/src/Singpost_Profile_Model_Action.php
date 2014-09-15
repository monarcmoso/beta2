<?php
class Singpost_Profile_Model_Action extends Mage_Core_Model_Abstract
{
	const SPECIAL_OFFERS = "I would like to receive up to date new product information and special offers from The Sample Store and the SingPost Group of Companies.";
	const NEW_PRODUCT_INFO = "I would like to receive up to date, new product information and special offers from external third parties.";
	
	public function editProfileModel($value, $attribute_code,$oldval)
	{
		//load attribute, get attribute id and backend_type (varchar,int,etc..)
		$attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode(1, $attribute_code);
		$table = "customer_entity_".$attributeModel->getBackendType();
		$attr_id = $attributeModel->getAttributeId();
		//load customer id and store id
		$customer_id = Mage::getSingleton('customer/session')->getId();
		//update the information
		try {
		    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		    $connection->beginTransaction();
			$where = "entity_id = '{$customer_id}' AND attribute_id = '{$attr_id}'";
		    $connection->update($table, array("value" => $value), $where);
		    $connection->commit();
		    //insert logs
		    $this->_editLogs($attribute_code,$oldval,$value);
			return true;
		}
		catch (Exception $exception){
		    return false;
		}
	}
	
	public function editEmail($data)
	{
		//validate if the user is login by facebook
		try {
		    $this->_write()->beginTransaction();
		    $this->_write()->insert('customer_email_edit',$data);
		    $this->_write()->commit();
			return true;
		}
		catch (Exception $exception){
		    return false;
		}
	}
	
	public function getEditEmail($activation)
	{
		//get the data in the customer_email_edit
		$select = $this->_read()->select()
		->from('customer_email_edit', array('*')) 
		->where("activation_key='{$activation}' AND status = 0");
		$result = $this->_read()->fetchAll($select);
		if(count($result) > 0){
			$email = $result[0]['email'];
			$customer_id = $result[0]['customer_id'];
			$dateActivated = date("Y-m-d H:i:s");
			try {
				//update the customer_entity table
			    $this->_write()->beginTransaction();
				$where = "entity_id = '{$customer_id}'";
			    $this->_write()->update("customer_entity", array("email" => $email), $where);
			    $this->_write()->commit();	
				//check the user if account is not activated in the eav
				$confirmation = $this->_read()->fetchAll("SELECT a.attribute_id, a.attribute_code, b.value FROM eav_attribute a
					LEFT JOIN customer_entity_varchar b ON a.attribute_id = b.attribute_id
					WHERE a.attribute_code = 'confirmation' AND entity_id = {$customer_id}");
				if(count($confirmation) > 0){
					$confirmation = $confirmation[0]['attribute_id'];
					$condition = array($this->_write()->quoteInto('attribute_id=?', $confirmation));
					$this->_write()->delete('customer_entity_varchar', $condition);
				}		
				//update the table of customer_email_edit			
				try{
					$whereActivated = "customer_id = '{$customer_id}' AND activation_key='{$activation}'";
					$this->_write()->beginTransaction();
					$this->_write()->update("customer_email_edit", array('status'=>1, 'date_confirmed'=>$dateActivated), $whereActivated);
					$this->_write()->commit();
					return array('response'=>'200','message'=>'Your Email Address is now activated.');
				}
				catch (Exception $exception){
					 return array('response'=>'203','message'=>'Activation failed. Please Contact the Web Administrator');
				}
			}//try {
			catch (Exception $exception){
			   return array('response'=>'Internal Error 500','message'=>'Activation failed. Please Contact the Web Administrator');
			}//catch (Exception $exception){
		}//if(count($result) > 0)
		else {
			return array('response'=>'Bad request 400','message'=>'Activation is invalid');
		}
	}
	
	public function editSettings($value,$attribute_code,$attr_id)
	{
		//get attribute id by attribute code
		//$attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode(2, 'telephone');
		// $attributeModel = $this->_loadAttrByCode($attribute_code);
		// return $attributeModel;
		// exit;
		// $attr_id = $attributeModel->getAttributeId();
		//select if there is any existing mobile number.
		$select = $this->_read()->select()
		->from('customer_address_entity_varchar', array('*'))
		->where("attribute_id='{$attr_id}' AND entity_id = '{$this->_addressEntityId()}'");
		$rows = $this->_read()->fetchAll($select);
		if($attribute_code == 'telephone')$message = "Mobile Number has been saved"; else $message = "Changes has been saved";
		// return $rows[0]['value'];
		// exit;
		if(count($rows) > 0){
			//do the update process and get the user id
			try{
				$this->_write()->beginTransaction();
				$fields = array();
				$fields['value'] = $value;
				$where = "attribute_id='{$attr_id}' AND entity_id = '{$this->_addressEntityId()}'";
				$this->_write()->update('customer_address_entity_varchar', $fields, $where);
				$this->_write()->commit();
				//insert logs in here
				$this->_editLogs($attribute_code,$rows[0]['value'],$value);
				return array('response'=>'200','message'=>$message);
			}
			catch (Exception $exception){
				Mage::logException($exception);
				return array('response'=>'Internal Error 500','message'=>'Updating failed. Please Contact the Web Administrator');
			}	
		}
		else {
			//insert to the database
			if($this->_checkAddressEntity() == false){
				try{
					$entity = array('entity_type_id'=>2,'parent_id'=>$this->_customerId());
					$this->_write()->beginTransaction();
				    $this->_write()->insert('customer_address_entity', $entity);
				    $this->_write()->commit();
				}catch(exception $e){
					Mage::logException($e);
				}
			}

			try{
				//get attribute id
				$fields = array('entity_type_id'=>2,'attribute_id'=>$attr_id,'entity_id'=>$this->_addressEntityId(),'value'=>$value);
				$this->_write()->beginTransaction();
			    $this->_write()->insert('customer_address_entity_varchar', $fields);
			    $this->_write()->commit();
				//insert logs in here
				$this->_editLogs($attribute_code,'Newly Inserted Value',$value);
				return array('response'=>'200','message'=>$message);
			}
			catch (Exception $exception){
				Mage::logException($exception);
				return array('response'=>'Internal Error 500','message'=>'Updating failed 2. Please Contact the Web Administrator');
			}	
		}
	}

	public function _checkAddressEntity(){
		$select = $this->_read()->select()
		->from('customer_address_entity', array('*'))
		->where("parent_id='{$this->_customerId()}'");
		$rows = $this->_read()->fetchAll($select);
		if(count($rows) > 0){
			return true;
		}else{
			return false;
		}

	}
	
	public function editNewsLetter($special_offers,$new_product_information,$result_id)
	{
		//get current user with newsletter
		$dashboardModel = Mage::getModel("profile/dashboard");
		$userNewsLetter = $dashboardModel->getUsersNewsLetter($this->_customerId());
		//get the field id
		$newsletter = $dashboardModel->getNewsletterSubscription();	
		if(count($userNewsLetter) > 1){
			//delete all here
			$result_value_id = array();
			foreach($userNewsLetter as $userLetter){
				if($userLetter['code'] == 'terms_and_condition'){}
				else{
					$result_value_id[] = $userLetter['result_value_id'];
					$code[] = $userLetter['code'];
					$value[] = $userLetter['value'];	
				}
			}
			//do the delete
			$ids = join(',',$result_value_id);
			$this->_write()->delete('webforms_results_values', "id IN ($ids)");
		}
		
		foreach ($newsletter as $value) {
			// /$field_id[] = $value['field_id'];
			$field[] = array('code'=>$value['code'], 'field_id'=>$value['field_id']);	
		}

		$special_offers = ($special_offers != '') ? self::SPECIAL_OFFERS : '';
		$new_product_information = ($new_product_information != '') ? self::NEW_PRODUCT_INFO : '';		
		
		$field_special_offers = ($field[0]['code'] == 'special_offers') ? $field[0]['field_id'] : $field[1]['field_id'];
		$field_new_product_information = ($field[1]['code'] == 'new_product_information') ? $field[1]['field_id'] : $field[0]['field_id'];

		//do the insert in here
		try{
			$sql = "INSERT INTO `webforms_results_values` (`result_id`, `field_id`, `value`)
					VALUES ($result_id, $field_special_offers, '{$special_offers}'), ($result_id, $field_new_product_information, '{$new_product_information}')";
			$this->_write()->query($sql);
				// $update = "UPDATE webforms_results_values 
						// SET value = CASE 
						// WHEN field_id ='{$prod_info_id}' THEN '{$prod}' 
						// WHEN field_id ='{$special_offers}' THEN '{$new_product_information}' 
						// ELSE value END 
						// WHERE id = 1010 OR id = 1011";
				// $query->query($update);
			$this->_editLogs('Newsletter','Newsletter and Product Information',"$special_offers & $new_product_information");	
			return array('response'=>'200','message'=>'Newsletter subscription preference updated successfully.');
		}
		catch (Exception $exception){
			Mage::logException($e);	
			return array('response'=>'Internal Error 500','message'=>'Updating failed. Please Contact the Web Administrator');
		}	
	}
	
	public function _editLogs($attr_code,$past,$value)
	{
		try{
			$fields = array(
				'customer_id'=>$this->_customerId(),
				'store_id'=>$this->_storeId(),
				'website_id'=>$this->_websiteId(),
				'attribute_code'=>$attr_code,
				'old_values'=>$past,
				'value'=>$value);
			$this->_write()->beginTransaction();
		    $this->_write()->insert('customer_edit_logs', $fields);
		    $this->_write()->commit();
		}catch (Exception $e) {
            Mage::logException($e);
        }
	}
	
	public function _checkFbUser()
	{
		//select the users in belvg_facebook_customer by user_id
		$select = $this->_read()->select()
		->from('belvg_facebook_customer', array('*'))
		->where('customer_id=?',$this->_customerId());
		$fb = $this->_read()->fetchAll($select);	
		if(count($fb) > 0){
			return true;
		}
		else {
			return false;
		}
	}
	
	public function getUserEditLogs()
	{
		$select = $this->_read()->select()
		->from('customer_edit_logs', array('*'))
		->where('customer_id=?',$this->_customerId());
		$result = $this->_read()->fetchAll($select);
		return $result;
	}
	
	public function _customerId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
	
	public function _write()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_write');
	}

	public function _read()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}
	
	public function _addressEntityId()
	{
		$sql = $this->_read()->fetchRow("SELECT entity_id FROM customer_address_entity WHERE parent_id = '{$this->_customerId()}'");
		return $sql['entity_id'];
	}

	public function _storeId()
	{
		return Mage::app()->getStore()->getStoreId();
	}
	
	public function _websiteId()
	{
		return Mage::app()->getWebsite()->getId();
	}
	
	public function _loadAttrByCode($attr)
	{
		return Mage::getModel('eav/entity_attribute')->loadByCode('customer_address', 'telephone');
	}
}