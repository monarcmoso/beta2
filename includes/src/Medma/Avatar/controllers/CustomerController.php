<?php

/**
 * Medma Avatar Module.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Team
 * that is bundled with this package of Medma Infomatix Pvt. Ltd.
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Medma Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */
/*
 * module => Medma_Avatar
 * upload the avatar 
 */

class Medma_Avatar_CustomerController extends Mage_Core_Controller_Front_Action {

    public function preDispatch() {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }

        #register domain event starts
        $domainName = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);

        Mage::dispatchEvent('medma_domain_authentication', array(
            'domain_name' => $domainName,
            //'email' => $generalEmail,
                )
        );
        #register domain event ends
    }

    protected function getCustomerSession() {
        return Mage::getSingleton('customer/session');
    }

    /*
     * show the upload form to the customer
     */

    public function formAction() {
        $this->loadLayout();

        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('avatar')->__('My Avatar'));
        }

        $this->renderLayout();
    }

    public function uploadAction() {

        $session = Mage::getSingleton('core/session');
        $customer = $this->getCustomerSession()->getCustomer();
        if ($this->getRequest()->isPost() 
                && isset($_FILES['avatar-file']['name']) 
                && ($_FILES['avatar-file']['name'] != '')) {
			$file_size = number_format($_FILES['avatar-file']['size'] / 1048576, 2) . ' MB';
			$limit = 20.00;
			if($file_size > $limit){
				Mage::getSingleton('core/session')->addError('Error: You have reached the maximum file upload of 20mb.');
			}
			else{
	            try {
	                $customer->setDataChanges(true)->save();
	            } catch (Exception $e) {
	                Mage::logException($e);
	            }
			}
        }
        $this->_redirectReferer();
    }

    public function viewAvatarAction() {

        $file = null;
        $plain = false;
        $customerSession = Mage::getSingleton('customer/session');
        if ($customerId = $this->getRequest()->getParam('id')) {
            // download file
            $customer = Mage::getModel('customer/customer')->load($customerId);
        } elseif ($customerSession->getId()) {
            $customer = $customerSession->getCustomer();
        } else {
            return $this->norouteAction();
        }

        $path = Mage::getBaseDir('media') . DS . 'customer';
        $file = $customer->getData(Medma_Avatar_Model_Config::AVATAR_ATTR_CODE);

        $ioFile = new Varien_Io_File();
        $ioFile->open(array('path' => $path));
        $fileName = $ioFile->getCleanPath($path . $file);
        $path = $ioFile->getCleanPath($path);

        if ((!$ioFile->fileExists($fileName) || strpos($fileName, $path) !== 0) && !Mage::helper('core/file_storage')->processStorageFile(str_replace('/', DS, $fileName))
        ) {
            return $this->norouteAction();
        }

        if ($plain) {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            switch (strtolower($extension)) {
                case 'gif':
                    $contentType = 'image/gif';
                    break;
                case 'jpg':
                    $contentType = 'image/jpeg';
                    break;
                case 'png':
                    $contentType = 'image/png';
                    break;
                default:
                    $contentType = 'application/octet-stream';
                    break;
            }

            $ioFile->streamOpen($fileName, 'r');
            $contentLength = $ioFile->streamStat('size');
            $contentModify = $ioFile->streamStat('mtime');

            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->setHeader('Pragma', 'public', true)
                    ->setHeader('Content-type', $contentType, true)
                    ->setHeader('Content-Length', $contentLength)
                    ->setHeader('Last-Modified', date('r', $contentModify))
                    ->clearBody();
            $this->getResponse()->sendHeaders();

            while (false !== ($buffer = $ioFile->streamRead())) {
                echo $buffer;
            }
        } else {
            $name = pathinfo($fileName, PATHINFO_BASENAME);
            $this->_prepareDownloadResponse($name, array(
                'type' => 'filename',
                'value' => $fileName
            ));
        }

        exit();
    }

}

?>
