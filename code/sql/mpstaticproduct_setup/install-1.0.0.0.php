<?php
/* @var $this Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('mpstaticproduct/cache'))
    ->addColumn('cache_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Account Id')
    ->addColumn('path', Varien_Db_Ddl_Table::TYPE_TEXT, 65536, array(
    ), 'File Path')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    ), 'product_id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Store Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Created At')
    ->addForeignKey($installer->getFkName('mpstaticproduct/cache', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('mpstaticproduct cache table');
$installer->getConnection()->createTable($table);

$installer->endSetup();