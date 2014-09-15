<?php
class Singpost_Login_Model_Facelift extends Mage_Core_Model_Abstract
{	
	public function hash_password($salt,$password){
		$hashpass = sha1($password.$salt);
		return $hashpass;
	}
	
	public function facelift($email = null, $password = null)
	{
		$select = $this->_read()->select()
		->from('users', array('*')) 
		->where("email='{$email}' AND level <> 5");
		$user = $this->_read()->fetchAll($select);
		if(!empty($user)){
			$salt = $user[0]['salt'];
			$real_password = $user[0]['password'];
			$user_password = $this->hash_password($salt,$password);
			if($user_password == $real_password){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	public function _updateStatus($email)
	{	
		try {
			$entity = array('email'=>$email);
			$this->_write()->beginTransaction();
		    $this->_write()->insert('customer_migration', $entity);
		    $this->_write()->commit();	
		}
		catch (Exception $exception){
		    return false;
		}
		
		$this->_write()->beginTransaction();
		$where = "email='{$email}'";
	    $this->_write()->update("users", array("level" => 5), $where);
	    $this->_write()->commit();
	}
	
	public function _checkMigrated($email)
	{
		$select = $this->_read()->select()
		->from('customer_migration', array('*')) 
		->where("email='{$email}'");
		$user = $this->_read()->fetchAll($select);
		return count($user);
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