<?php
/**
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2014 Vladimir Popov
 */

class VladimirPopov_WebForms_IndexController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction()
	{	
		Mage::register('webforms_preview',true);
		Mage::register('show_form_name',true);
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->_initLayoutMessages('catalog/session');
		$this->renderLayout();
	}
	function is_profanity($q,$json=0) {
                $q=urlencode(preg_replace('/[\W+]/',' ',$q));
                $p=file_get_contents('http://www.wdyl.com/profanity?q='.$q);
                if ($json) { return $p; }
                $p=json_decode($p);
                return ($p->response=='true')?1:0;
        }

          protected function _getSession()
        {
            return Mage::getSingleton('checkout/session');
        }
	public function iframeAction()
	{  
		$webform = Mage::getModel('webforms/webforms')
			->setStoreId(Mage::app()->getStore()->getId())
			->load(Mage::app()->getRequest()->getPost("webform_id"));
              
               
		$result = array("success" => false, "errors" => array());
		if(Mage::app()->getRequest()->getPost('submitWebform_'.$webform->getId())){
			$result["success"] = $webform->savePostResult();
			if($result["success"]){
				$result["success_text"] = $webform->getSuccessText();
		if((float)substr(Mage::getVersion(),0,3)>1.3 || Mage::helper('webforms')->getMageEdition() == 'EE'){

                    // apply custom variables
                    $filter = Mage::helper('cms')->getPageTemplateProcessor();
                    $webformObject = new Varien_Object();
                    $webformObject->setData($webform->getData());
                    $resultObject = Mage::getModel('webforms/results')->load($result['success']);
                    $subject = $resultObject->getEmailSubject('customer');
                    $filter->setVariables(array(
                        'webform_result' => $resultObject->toHtml('customer'),
                        'result' => $resultObject->getTemplateResultVar(),
                        'webform' => $webformObject,
                        'webform_subject' => $subject
                    ));
		 	
                 $images = array();   
                 $sql2 = "select * from webforms_fields where code='review_image' and webform_id=".Mage::app()->getRequest()->getPost("webform_id")."";
                 $image_data2 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql2);
		 foreach($image_data2 as $dat2){
                     $images[] = Mage::app()->getRequest()->getPost("".$dat2['id']);
                 }
               
                 $sql = "select webforms_results_values.id,webforms_results_values.value as image_name,webforms_results_values.key from webforms_results join webforms_results_values on webforms_results.id=webforms_results_values.result_id where webforms_results.id=".$result['success']." and webforms_results_values.key!=''";
                 $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
		
		$images_id = "";
                $images_deleted = "";
                $image_uploaded = array();
		$ctr = 1;
                
		if($data){
                   foreach($data as $dat){
                       $image_uploaded[] = $dat['image_name']; 
                       if(!in_array($dat['image_name'], $images) && $dat['image_name']!=""){
                            $images_id .= " ".$dat['image_name']." has been added.<br/>";
                       }
			$ctr++;
		  }
                  
		  if($images_id!=""){
		 	$message = $this->__($images_id);
                        $this->_getSession()->addSuccess($message);
                        $result["upload_success"] =$images_id; 
		  }
                  
		}
                if($images){
                    foreach($images as $imag_items){
                         if(!in_array($imag_items, $image_uploaded) && $imag_items!=""){
                              $images_deleted .= " ".$imag_items." has been deleted.<br/>";
                         } 
                    }
                    if($images_deleted){
                        $message = $this->__($images_deleted);
                        $this->_getSession()->addError($message);
                    }
                }
                    $result["success_text"] = $filter->filter($webform->getSuccessText());
                    $message = $this->__('Your review has been successfully saved.');
                    $this->_getSession()->addSuccess($message);
                }		
	 $customer_id = Mage::getSingleton('customer/session')->getId();
	    $summaryForCustomer =  Mage::getModel('points/summary')->loadByCustomer(
                    Mage::getSingleton('customer/session')->getCustomer()
                );
           try{
		$webform_id = $webform->getId(); 
                $user_points = $summaryForCustomer->getPoints();
	
	        $points_summary_id = $summaryForCustomer->getId();             
                $new_points = $user_points +1;
           	
                Mage::helper('review')->setPoints($customer_id, $new_points , $webform_id);
               
            
             }catch (Mage_Payment_Model_Info_Exception $e) {
		   $message = "There was a problem on adding points to your account.";	
		  $this->_getSession()->addError($message);
	    }

			       
				if($webform->getRedirectUrl()){
					if(strstr($webform->getRedirectUrl(),'://'))	
						$redirectUrl = $webform->getRedirectUrl();
					else
						$redirectUrl = Mage::app()->getStore()->getUrl($webform->getRedirectUrl());
					$result["redirect_url"] = $redirectUrl;
				}
                                
                                if(Mage::app()->getRequest()->getPost("redirect_url")!=""){
                                       $redirectUrl =  Mage::app()->getStore()->getUrl(Mage::app()->getRequest()->getPost("redirect_url").".html");
                                       $result["redirect_url"] = $redirectUrl;
                                }
			} else {
				$errors = Mage::getSingleton('core/session')->getMessages(true)->getItems();
				foreach($errors as $err){
					$result["errors"][] = $err->getCode();
				}
				$html_errors = "";
				if(count($result["errors"])>1){
					foreach($result["errors"] as $err){
						$html_errors.= '<li>'.$err.'</li>';
					}
					$result["errors"] = '<ul class="webforms-errors-list">'.$html_errors.'</ul>';
				} else {
					$result["errors"] = '<p class="webforms-error-message">'.$result["errors"][0].'</p>';
				}
			}
		}
		
		Mage::dispatchEvent('webforms_controllers_index_iframe_action',array('result'=>$result, 'webform'=>$webform));

		$this->getResponse()->setBody(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
	}
	
}  
?>
