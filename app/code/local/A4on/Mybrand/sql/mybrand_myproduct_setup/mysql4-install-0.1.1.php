<?php
$installer = $this;

$installer->startSetup();

$installer->run("DROP TABLE IF EXISTS {$this->getTable('mybrands')};
            CREATE TABLE {$this->getTable('mybrands')} (
 `brand_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `brand_name` VARCHAR( 50 ) NOT NULL ,
 `brand_image_file` VARCHAR( 50 ) NOT NULL
);");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('my_products')};
            CREATE TABLE {$this->getTable('my_products')} (
`product_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `cp_id` INT( 11 ) NOT NULL ,
 `brand_id` INT( 11 ) NOT NULL
);");

$installer->endSetup();

?>