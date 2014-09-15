<?php 

class A4on_Mybrand_IndexController extends Mage_Core_Controller_Front_Action
{
	function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	function savebrandAction()
	{
		if ((($_FILES["image"]["type"] == "image/gif")
		|| ($_FILES["image"]["type"] == "image/jpeg")
		|| ($_FILES["image"]["type"] == "image/jpg")
		|| ($_FILES["image"]["type"] == "image/pjpeg")
		|| ($_FILES["image"]["type"] == "image/x-png")
		|| ($_FILES["image"]["type"] == "image/png")
		|| ($_FILES["image"]["type"] == "image/PNG")))
		{
			move_uploaded_file($_FILES["image"]["tmp_name"],"./upload/mybrand/brands/" . $_FILES["image"]["name"]);
			
			$data = array('brand_name'=>$_POST[brand],'brand_image_file'=>$_FILES["image"]["name"]);
			
			$model = Mage::getModel('mybrand/mybrand')->load($_POST[id])->addData($data);
			
			$model->setId($_POST[id])->save();
		}
		
		$this->_redirect('myproductadmin/adminhtml_index');
		
	}
	
	function newbrandAction()
	{
		if ((($_FILES["image"]["type"] == "image/gif")
		|| ($_FILES["image"]["type"] == "image/jpeg")
		|| ($_FILES["image"]["type"] == "image/jpg")
		|| ($_FILES["image"]["type"] == "image/pjpeg")
		|| ($_FILES["image"]["type"] == "image/x-png")
		|| ($_FILES["image"]["type"] == "image/png")
		|| ($_FILES["image"]["type"] == "image/PNG")))
		{
			
			move_uploaded_file($_FILES["image"]["tmp_name"],"./upload/mybrand/brands/" . $_FILES["image"]["name"]);
			
			$collection = Mage::getModel('mybrand/mybrand');
			
			$collection->setData('brand_name',$_POST['brand']);
			
			$collection->setData('brand_image_file',$_FILES["image"]["name"]);
			
			$collection->save();
		
		}
		
		$this->_redirect('myproductadmin/adminhtml_index');
		
	}
	
	function deletebrandAction()
	{
		$collection = Mage::getModel('mybrand/mybrand');
		$collection->setId($_GET['id']);
		$collection->delete();
		$this->_redirect('myproductadmin/adminhtml_index');
	}
	
	function newproductAction()
	{
		$product_id = $_POST['product'];
		
		$brand_id = $_POST['brand'];
		
		$collection = Mage::getModel('myproduct/myproduct');
			
		$collection->setData('brand_id',$brand_id);
		
		$collection->setData('cp_id',$product_id);
		
		$collection->save();
		
		
		
		$this->_redirect('myproductadmin/adminhtml_index');
	}
	
	function saveproductAction()
	{
		
		$data = array('brand_id'=>$_POST[brand],'cp_id'=>$_POST[product]);
		
		$model = Mage::getModel('myproduct/myproduct')->load($_POST[id])->addData($data);
		
		$model->setId($_POST[id])->save();
		
		$this->_redirect('myproductadmin/adminhtml_index');
		
	}
	
	function deleteproductAction()
	{
		$collection = Mage::getModel('myproduct/myproduct');
		$collection->setId($_GET['id']);
		$collection->delete();
		$this->_redirect('myproductadmin/adminhtml_index');
	}
	
	function brandslistAction()
	{
		
//            $productNameArray = array();	
//            $host = $_SERVER[HTTP_HOST];
//            $uri = $_SERVER[REQUEST_URI];
//            $uriArray = explode('/',$uri);
//            $url2 = '';//'/'.$uriArray[1];
//
//            echo '<link href="http://'.$host.$url2.'/skin/frontend/base/default/css/mybrand.css" rel="stylesheet" type="text/css">';
//            echo '<script src="http://'.$host.$url2.'/skin/frontend/base/default/js/mybrand.js"></script>';
//            echo '<script src = "http://'.$host.$url2.'/skin/frontend/base/default/js/vticker.js"></script>';
//            echo '<script>';
//            echo '$.noConflict();';
//            echo '</script>';
////            echo "<script>";
////            echo "	jQuery(function() {";
////            echo "	  jQuery('#example').vTicker({";
////            echo "		   speed: 500,";
////            echo "		   pause: 3000,";
////            echo "		   animation: 'fade',";
////            echo "		   mousePause: true,";
////            echo "		   showItems: 1,";
////            echo "		   height:120";
////            echo "	  });";
////            echo "	});";
////            echo "</script>";
//
//            $collection = Mage::getModel('mybrand/mybrand')->getCollection()->setOrder('brand_id','asc');
//            echo '<div>';
//            echo '</div>';
//            echo '<div id="example" style="padding: 10px;width:100px;float:left;">';
//            echo '<div style="width: 1200px;">';
//            foreach($collection as $data)
//            {
//
//                    echo '<div style="float:left;width: 200px;">';
//                    echo '<div style="width: 100px;" class = "mybrand-name"><center>'.$data->getData('brand_name').'</center></div>';
//                    echo '<a href = "http://'.$host.$url2.'/mybrand/?id='.$data->getData('brand_id').'">';
//                    echo '<img src = "http://'.$host.$url2.'/upload/mybrand/brands/'.$data->getData('brand_image_file').'" style = "width:100px;height:50px">';
//                    echo '</a>';
//                    echo '</div>';
//
//            } 
//            echo '</div>';
//            echo '</div><div style="clear:both;"></div>';
        
		$this->loadLayout();
		$this->renderLayout();
	}
}