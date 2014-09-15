<?php
 
class Mycode_Function_Helper_Data extends Mage_Core_Helper_Abstract
{
 
	public function test(){
 
	return 'works';
 
	}
        // sorting  array by date
        public function sortFunction( $a, $b ) {
            return strtotime($a["date"]) - strtotime($b["date"]);
        }
}