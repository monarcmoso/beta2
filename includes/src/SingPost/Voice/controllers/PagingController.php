<?php
class Singpost_Voice_PagingController extends Mage_Core_Controller_Front_Action
{
	public function _pageAjaxAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			$limit = $this->getRequest()->getParam('page');
			if($limit == 8){
				$offset = 0;
			}
			else{
				$offset = $limit - 7;
			}
			//$attributeValue = 'pure_beauty_pomegranate_antioxidant_eye_gel';
			$attributeValue = $this->getRequest()->getParam('attr');
			$arr = Mage::getModel("voice/action")->_getAllReviews($attributeValue);
			$filtered = array();
			$filtered_user = array();
			$buyIt = array();
			foreach($arr as $temp){
				$filtered_user[] = array('field_id'=>$temp['field_id'],'customer_id'=>$temp['customer_id'],'result_value'=>$temp['result_value'],'date'=>$temp['date']);
				if(is_numeric($temp['result_value'])){
					$filtered[] = array('field_id'=>$temp['field_id'],'customer_id'=>$temp['customer_id'],'result_value'=>$temp['result_value']);
				}
				//$buy = strtolower(str_replace(' ', '_', $temp['field_set']));
				$buy = $temp['field_code'];
				if($buy == 'will_you_buy_it'){
					$buyIt[] = strtolower($temp['result_value']);
				}
			}
	
			$_result = array();
			foreach ($filtered as $data) {
			  $id = $data['customer_id'];
			  if (isset($_result[$id])) {
			     $_result[$id][] = $data;
			  } else {
			     $_result[$id] = array($data);
			  }
			}
			
			$customers = array();
			$items = array();
			foreach($_result as $key => $res){
				foreach($res as  $r){
					$customers[$key][] = $r['result_value'];
				}
				for($i = 0; $i< count($res); $i++){
					$items[$i][] = $res[$i]['result_value'];
				}
			}		
			
			$_reviewArray = array();
			$_reviewUsers = Mage::getModel("voice/action")->_reviews($attributeValue,0, 8);
			foreach($_reviewUsers as $_Userreview){
				$_reviewCustomerId = $_Userreview['customer_id'];
				$_reviewArray[$_reviewCustomerId] = $customers[$_reviewCustomerId];
			}
			//get questions here
			//$eff_questions = "";
			$this->loadLayout(); 
			//return the page
			$eff_questions = Mage::getModel("voice/action")->_getAllQuestions($attributeValue);
			
			echo $this->getLayout()->createBlock('lesson05/lesson05')->setData('attr', $attributeValue)->setData('efficacy',$eff_questions)->setData('data', $_reviewArray)->setTemplate('lesson05/reviews.phtml')->toHtml();
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}//end method
}