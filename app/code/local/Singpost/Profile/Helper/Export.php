<?php
class Singpost_Profile_Helper_Export extends Mage_Core_Helper_Abstract
{
    /**
     * Contains current collection
     * @var string
     */
    protected $_list = null;
	
	protected function _getEmail($type)
	{
		$model = Mage::getModel("profile/export");
		return $model->getCustomerEmail($type);
	}
	
    protected function _getCsvHeaders($products)
    {
        return array('Email Address','NewsLetter');
    }
 
    /**
     * Generates CSV file with product's list according to the collection in the $this->_list
     * @return array
     */
    public function generateMlnList($type)
    {
        $io = new Varien_Io_File();
        $path = Mage::getBaseDir('var') . DS . 'export' . DS;
        $name = md5(microtime());
        $file = $path . DS . $name . '.csv';
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $path));
        $io->streamOpen($file, 'w+');
		$io->streamLock(true);
		$io->streamWriteCsv($this->_getCsvHeaders($items));
		foreach ($this->_getEmail($type) as $data) {
			$io->streamWriteCsv(array($data['email'],$data['field_name']));
		}
		return array(
			'type'  => 'filename',
			'value' => $file,
			'rm'    => true // can delete file after use
		);
    }
}