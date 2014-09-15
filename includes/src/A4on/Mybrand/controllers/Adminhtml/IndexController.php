<?php
class A4on_Mybrand_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
  protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('mybrand/set_time')
                ->_addBreadcrumb('mybrand Manager','mybrand Manager');
       return $this;
     }
      public function indexAction()
      {
         $this->_initAction();
         $this->renderLayout();
      }
      public function editAction()
      {
           $testId = $this->getRequest()->getParam('id');
           $testModel = Mage::getModel('mybrand/mybrand')->load($testId);
           if ($testModel->getId() || $testId == 0)
           {
             Mage::register('mybrand_data', $testModel);
             $this->loadLayout();
             $this->_setActiveMenu('mybrand/set_time');
             $this->_addBreadcrumb('mybrand Manager', 'mybrand Manager');
             $this->_addBreadcrumb('Mybrand Description', 'Mybrand Description');
             $this->getLayout()->getBlock('head')
                  ->setCanLoadExtJs(true);
             $this->_addContent($this->getLayout()
                  ->createBlock('mybrand/adminhtml_mybrand_edit'))
                  ->_addLeft($this->getLayout()
                  ->createBlock('mybrand/adminhtml_mybrand_edit_tabs')
              );
             $this->renderLayout();
           }
           else
           {
                 Mage::getSingleton('adminhtml/session')
                       ->addError('mybrand does not exist');
                 $this->_redirect('*/*/');
            }
       }
       public function newAction()
       {
          $this->_forward('edit');
       }
       public function saveAction()
       {
         if ($this->getRequest()->getPost())
         {
           try {
                 $postData = $this->getRequest()->getPost();
                 $testModel = Mage::getModel('mybrand/mybrand');
               if( $this->getRequest()->getParam('id') <= 0 )
                  $testModel->setCreatedTime(
                     Mage::getSingleton('core/date')
                            ->gmtDate()
                    );
                  $testModel
                    ->addData($postData)
                    ->setUpdateTime(
                             Mage::getSingleton('core/date')
                             ->gmtDate())
                    ->setId($this->getRequest()->getParam('id'))
                    ->save();
                 Mage::getSingleton('adminhtml/session')
                               ->addSuccess('successfully saved');
                 Mage::getSingleton('adminhtml/session')
                                ->settestData(false);
                 $this->_redirect('*/*/');
                return;
          } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')
                                  ->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')
                 ->settestData($this->getRequest()
                                    ->getPost()
                );
                $this->_redirect('*/*/edit',
                            array('id' => $this->getRequest()
                                                ->getParam('id')));
                return;
                }
              }
              $this->_redirect('*/*/');
            }
          public function deleteAction()
          {
              if($this->getRequest()->getParam('id') > 0)
              {
                try
                {
                    $testModel = Mage::getModel('mybrand/mybrand');
                    $testModel->setId($this->getRequest()
                                        ->getParam('id'))
                              ->delete();
                    Mage::getSingleton('adminhtml/session')
                               ->addSuccess('successfully deleted');
                    $this->_redirect('*/*/');
                 }
                 catch (Exception $e)
                  {
                           Mage::getSingleton('adminhtml/session')
                                ->addError($e->getMessage());
                           $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                  }
             }
            $this->_redirect('*/*/');
       }
}
?>