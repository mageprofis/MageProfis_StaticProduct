<?php
/* @var $this Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer->startSetup();

$installer->getConnection()->changeColumn($installer->getTable('mpstaticproduct/cache'), 'product_id', 'object_id', "INT( 11 ) NULL DEFAULT NULL");

$installer->getConnection()
->addColumn($installer->getTable('mpstaticproduct/cache'), 'object_type', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    //'nullable'  => false,
    'default'   => null,
    'length'    => 255,
    'after'     => 'path', // column name to insert new column after
    'comment'   => 'Object Type'
));   

$installer->endSetup();