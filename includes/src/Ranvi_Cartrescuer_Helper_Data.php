<?php

class Ranvi_Cartrescuer_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isEnable(){
		return Mage::getStoreConfig('cartrescuer/settings/enabled');
	}

	public function getStoreWebsiteCode(){
		return Mage::getStoreConfig('cartrescuer/settings/cartrescuer_websitecode');
	}

	public function getStoreWebsiteDomain(){
		return Mage::getStoreConfig('cartrescuer/settings/cartrescuer_websitedomain');
	}

	public function getLoginUsernameKey(){
		return Mage::getStoreConfig('cartrescuer/settings/cartrescuer_loginusernamekey');
	}
}