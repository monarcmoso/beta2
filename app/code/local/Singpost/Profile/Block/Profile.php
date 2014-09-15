<?php
class Singpost_Profile_Block_Profile extends Mage_Core_Block_Template
{
	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}
	
	public function methodBlock()
	{
		echo "aaa";
		return 'informations about my block !!' ;
	}
}