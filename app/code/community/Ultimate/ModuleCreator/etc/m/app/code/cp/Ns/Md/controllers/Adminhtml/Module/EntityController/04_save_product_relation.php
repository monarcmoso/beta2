				$products = $this->getRequest()->getPost('products', -1);
				if ($products != -1) {
					${{entity}}->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
				}
