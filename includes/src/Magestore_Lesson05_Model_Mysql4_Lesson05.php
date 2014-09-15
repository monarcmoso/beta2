<?php

class Magestore_Lesson05_Model_Mysql4_Lesson05 extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the lesson05_id refers to the key field in your database table.
        $this->_init('lesson05/lesson05', 'lesson05_id');
    }
}