				${{siblings}} = $this->getRequest()->getPost('{{siblings}}', -1);
				if (${{siblings}} != -1) {
					${{entity}}->set{{Siblings}}Data(Mage::helper('adminhtml/js')->decodeGridSerializedInput(${{siblings}}));
				}
