<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
  /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookFree
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_FacebookFree_CustomerController extends Mage_Core_Controller_Front_Action {

    public function LoginAction()
    {
        $me = null;
        $cookie = $this->get_facebook_cookie(Mage::getStoreConfig('facebookfree/settings/appid'), Mage::getStoreConfig('facebookfree/settings/secret'));
        $me = json_decode($this->getFbData('https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
        if (!is_null($me)) 
        {
        	//check if the the user is existing in the belvg_facebook_customer table
			$me = (array)$me;
            $session = Mage::getSingleton('customer/session');
            $db_read = Mage::getSingleton('core/resource')->getConnection('facebookfree_read');
            $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
            $sql = 'SELECT `customer_id`
					FROM `' . $tablePrefix . 'belvg_facebook_customer`
					WHERE `fb_id` = ' . $me['id'] . '
					LIMIT 1';
            $data = $db_read->fetchRow($sql);
            if ($data)
			{
            	// if the user exist this will be the function
                $session->loginById($data['customer_id']);
				//function for the profile picture capture
				$this->_loginProfilePicture($data['customer_id'],$me);
            } 
            else 
            {
            	//if the user is not existing in the belvg_facebook_customer this will work!
          		//select entity id customer table, check the user if exist in the database
                $sql = 'SELECT `entity_id`
						FROM `' . $tablePrefix . 'customer_entity`
						WHERE email = "' . $me['email'] . '"
						AND store_id = "'.Mage::app()->getStore()->getStoreId().'"
						AND website_id = "'.Mage::getModel('core/store')->load(Mage::app()->getStore()->getStoreId())->getWebsiteId().'"
						LIMIT 1';
                $r = $db_read->fetchRow($sql);
                if ($r) 
                {
                	// if the user exits insert data into the belvg_facebook_customer
                    $db_write = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
                    $sql = 'INSERT INTO `' . $tablePrefix . 'belvg_facebook_customer` (customer_id, fb_id)
                                                    VALUES (' . $r['entity_id'] . ', ' . $me['id'] . ')';
                    $db_write->query($sql);
                    $session->loginById($r['entity_id']);
					
					//function for the profile picture capture
					$this->_loginProfilePicture($r['entity_id'],$me);
                }//end if ($r) 
                else 
                {
                	//register the customer to the website
                    $this->_registerCustomer($me, $session);
                }// end else of if ($r)
            }
			//send email if the user login count is 3
			Mage::helper('login')->logSurvey();
			
            //$this->_loginPostRedirect($session);
            //user redirect to custom template for the settings.
           Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index'));
        }//if (!is_null($me)) 
    }

    public function LogoutAction()
    {
        $session = Mage::getSingleton('customer/session');
        $session->logout()
                ->setBeforeAuthUrl(Mage::getUrl());

        $this->_redirect('customer/account/logoutSuccess');
    }

    private function _registerCustomer($data, &$session)
    {
    	$avatar = NULL;
    	$pic_url = 'https://graph.facebook.com/'.$data['id'].'/picture?type=large';
		$pic = file_get_contents($pic_url);	
		if($pic !== FALSE) 
		{
			$pic_format = '.jpg';
			$pic_filename = uniqid('fb_').$pic_format;
			//$pic_path = Mage::getBaseDir('media') .DS.'customer' . Belvg_Facebookfree_Model_Config::FOLDER_FACEBOOK_PROFILE_PIC . $pic_filename;
			$pic_path = Mage::getBaseDir('media') .DS.'customer' .'/profile_pic_fb/'. $pic_filename;
			//echo $pic_path;
			$upload = file_put_contents($pic_path, $pic);
			//check if upload is true and then validate the information
			//for upgrade should use try catch and logs, as of now use the if condition
			if($upload)
			{
				//$avatar = Belvg_Facebookfree_Model_Config::FOLDER_FACEBOOK_PROFILE_PIC . $pic_filename;
				$avatar = '/profile_pic_fb/'.$pic_filename;
				
			}
			else {
				$avatar = NULL;
			}
		}//end if($pic !== FALSE) 
        $customer = Mage::getModel('customer/customer')->setId(null);
		$gender = ($data['gender'] == 'male') ? 123 : 124;
        $customer->setData('full_name', $data['first_name'].' '.$data['last_name']);
        $customer->setData('email', $data['email']);
        $customer->setData('password', md5(time() . $data['id'] . $data['locale']));
        $customer->setData('is_active', 1);
		$customer->setData('gender', $gender);
		$customer->setData('dob', '30-04-1920');
        $customer->setData('confirmation', null);
		$customer->setData('medma_avatar', $avatar);
        $customer->setConfirmation(null);
        $customer->getGroupId();
        $customer->save();

        Mage::getModel('customer/customer')->load($customer->getId())->setConfirmation(null)->save();
        $customer->setConfirmation(null);
        $session->setCustomerAsLoggedIn($customer);
        $customer_id = $session->getCustomerId();


		//insert summary in the aw_points_summary database
		$connection  = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
		$sql = "INSERT INTO aw_points_summary (customer_id, points, balance_update_notification) VALUES ($customer_id, 4, 1)";
		$connection->query($sql);
		$summary_id = $connection->lastInsertId();
		//echo $lastInsertId;
		
		//insert to points transaction
		$name = $data['first_name']." ".$data['last_name'];
		$email = $data['email'];
		$rewardsSql = "INSERT INTO aw_points_transaction (store_id, summary_id, balance_change, balance_change_spent, action, comment, notice, change_date, customer_name, customer_email, is_locked)
						VALUES (1, $summary_id, 4, 0, 'customer_register', 'Reward for registration', '', NOW(), '$name', '$email', 0)";
		$connection->query($rewardsSql);
		$points_transaction_id = $connection->lastInsertId();
		
		
		$fb_id = $data['id']; 
       	
       	$db_write = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
        $sql = "INSERT INTO belvg_facebook_customer (customer_id,fb_id) VALUES($customer_id,$fb_id)";
        $db_write->query($sql);
    }
	
	public function getProfilePictureAction()
	{
		//customer id
		$customer_id = Mage::getSingleton('customer/session')->getId();
		$cookie = $this->get_facebook_cookie(Mage::getStoreConfig('facebookfree/settings/appid'), Mage::getStoreConfig('facebookfree/settings/secret'));
        $fb = json_decode($this->getFbData('https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
		if (!is_null($fb)){
			$db_read = Mage::getSingleton('core/resource')->getConnection('facebookfree_read');
   			$check_pic = "SELECT a.value, b.attribute_id FROM customer_entity_varchar a LEFT JOIN eav_attribute b ON a.attribute_id = b.attribute_id WHERE b.attribute_code = 'medma_avatar' AND entity_id = $customer_id";
   			$result = $db_read->fetchRow($check_pic);
	    	$attr_id = $result['attribute_id'];
	    	if(!result){
	    		$this->_loginProfilePicture($customer_id,$fb);
	    	}
			else {
				$avatar = NULL;
		    	$pic_url = 'https://graph.facebook.com/'.$fb->id.'/picture?type=large';
				$pic = file_get_contents($pic_url);	
				if($pic !== FALSE) 
				{
					$pic_format = '.jpg';
					$pic_filename = uniqid('fb_').$pic_format;
					//$pic_path = Mage::getBaseDir('media') .DS.'customer' . Belvg_Facebookfree_Model_Config::FOLDER_FACEBOOK_PROFILE_PIC . $pic_filename;
					$pic_path = Mage::getBaseDir('media') .DS.'customer' .'/profile_pic_fb/'. $pic_filename;
					//echo $pic_path;
					$upload = file_put_contents($pic_path, $pic);
					//check if upload is true and then validate the information
					//for upgrade should use try catch and logs, as of now use the if condition
					if($upload)
					{
						//path of the avatar
						$avatar = '/profile_pic_fb/'.$pic_filename;
					}//end of if($upload)
					else {
						//set to null the avatar so that it will not be saved to database
						$avatar = NULL;
					}//end of else if($upload)
				}//end if($pic !== FALSE)	
			}
			try{
				
				$model = Mage::getModel("profile/action");
				$model->_editLogs('medma_avatar', $result['value'],$avatar);
				
				$connection  = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');	
				$connection->beginTransaction();
				$fields = array();
				$fields['value'] = $avatar;
				$where = "attribute_id = '{$attr_id}' AND entity_id = '{$customer_id}'";
				$connection->update('customer_entity_varchar', $fields, $where);
				$connection->commit();
				
				//redirect 
				//Mage::getSingleton('core/session')->addSuccess('Profile Picture has been updated.');
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index/account'));
			}catch (Exception $e) {
	            Mage::logException($e);
				Mage::getSingleton('core/session')->addError('Failure getting profile picture in facebook. Please try again.');
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index/account'));
	        }  
		}else{
			//redirect and error message
			//Mage::getSingleton('core/session')->addError('Failure getting profile picture in facebook. Please try again.');
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index/account'));
		} 
	}
	
	private function _loginProfilePicture($customer_id,$data)
	{
		//Check profile pic if null or no profile pic at all.
		$db_read = Mage::getSingleton('core/resource')->getConnection('facebookfree_read');
   		$check_pic = "SELECT a.value, b.attribute_id FROM customer_entity_varchar a LEFT JOIN eav_attribute b ON a.attribute_id = b.attribute_id WHERE b.attribute_code = 'medma_avatar' AND entity_id = $customer_id";
   		$check_result = $db_read->fetchRow($check_pic);
		if(!$check_result)
		{
	    	$avatar = NULL;
	    	$pic_url = 'https://graph.facebook.com/'.$data->id.'/picture?type=large';
			$pic = file_get_contents($pic_url);	
			if($pic !== FALSE) 
			{
				$pic_format = '.jpg';
				$pic_filename = uniqid('fb_').$pic_format;
				$pic_path = Mage::getBaseDir('media') .DS.'customer' .'/profile_pic_fb/'. $pic_filename;
				//echo $pic_path;
				$upload = file_put_contents($pic_path, $pic);
				//check if upload is true and then validate the information
				//for upgrade should use try catch and logs, as of now use the if condition
				if($upload)
				{
					//path of the avatar
					$avatar = '/profile_pic_fb/'.$pic_filename;
				}//end of if($upload)
				else {
					//set to null the avatar so that it will not be saved to database
					$avatar = NULL;
				}//end of else if($upload)
			}//end if($pic !== FALSE)
			
			//get attribute_id of the medma_avatar
			$attr_sql = "SELECT attribute_id FROM eav_attribute WHERE attribute_code = 'medma_avatar'";
			$attr = $db_read->fetchRow($attr_sql);
			$attr_id = $attr['attribute_id'];
			//insert data
		    $connection  = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
			$sql = "INSERT INTO customer_entity_varchar (entity_type_id,attribute_id,entity_id,value) VALUES ('1',$attr_id,$customer_id,'$avatar')";
			$connection->query($sql);
			$summary_id = $connection->lastInsertId();
			return $summary_id;
		}//end else of if(count($check_pic) > 0)
		else
		{
			return false;
		}
	}
	
    private function _loginPostRedirect(&$session)
    {

        if ($referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME)) {
            $referer = Mage::helper('core')->urlDecode($referer);
            if ((strpos($referer, Mage::app()->getStore()->getBaseUrl()) === 0)
                    || (strpos($referer, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true)) === 0)) {
                $session->setBeforeAuthUrl($referer);
            } else {
                $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
            }
        } else {
            $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    private function get_facebook_cookie($app_id, $app_secret)
    {
        if ($_COOKIE['fbsr_' . $app_id] != '') {
            return $this->get_new_facebook_cookie($app_id, $app_secret);
        } else {
            return $this->get_old_facebook_cookie($app_id, $app_secret);
        }
    }

    private function get_old_facebook_cookie($app_id, $app_secret)
    {
        $args = array();
        parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
        ksort($args);
        $payload = '';
        foreach ($args as $key => $value) {
            if ($key != 'sig') {
                $payload .= $key . '=' . $value;
            }
        }
        if (md5($payload . $app_secret) != $args['sig']) {
            return array();
        }
        return $args;
    }

    private function get_new_facebook_cookie($app_id, $app_secret)
    {
        $signed_request = $this->parse_signed_request($_COOKIE['fbsr_' . $app_id], $app_secret);
        // $signed_request should now have most of the old elements
        $signed_request['uid'] = $signed_request['user_id']; // for compatibility 
        if (!is_null($signed_request)) {
            // the cookie is valid/signed correctly
            // lets change "code" into an "access_token"
			$access_token_response = $this->getFbData("https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=&client_secret=$app_secret&code=$signed_request[code]");
			parse_str($access_token_response);
			$signed_request['access_token'] = $access_token;
			$signed_request['expires'] = time() + $expires;
        }

        return $signed_request;
    }

    private function parse_signed_request($signed_request, $secret)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    private function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
	
	private function getFbData($url)
	{
		$data = null;

		if (ini_get('allow_url_fopen') && function_exists('file_get_contents')) {
			$data = file_get_contents($url);
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
		}
		return $data;
	}

}