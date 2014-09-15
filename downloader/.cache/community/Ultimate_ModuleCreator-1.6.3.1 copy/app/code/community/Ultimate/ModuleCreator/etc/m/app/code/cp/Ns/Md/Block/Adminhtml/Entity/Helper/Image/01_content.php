<?php
{{License}}
/**
 * {{EntityLabel}} image field renderer helper
 *
 * @category	{{Namespace}}
 * @package		{{Namespace}}_{{Module}}
 * {{qwertyuiop}}
 */
class {{Namespace}}_{{Module}}_Block_Adminhtml_{{Entity}}_Helper_Image extends Varien_Data_Form_Element_Image{
	/**
	 * get the url of the image
	 * @access protected
	 * @return string
	 * {{qwertyuiop}}
	 */
	protected function _getUrl(){
		$url = false;
		if ($this->getValue()) {
			$url = Mage::helper('{{module}}/{{entity}}_image')->getImageBaseUrl().$this->getValue();
		}
		return $url;
	}
}
 