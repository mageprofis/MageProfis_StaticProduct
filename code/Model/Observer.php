<?php

class MageProfis_StaticProduct_Model_Observer extends Mage_Core_Model_Abstract
{
    public function cleanAll()
    {
        //delete files
        $path = Mage::getBaseDir('var') . DS . 'static' . DS;
        $pathNew = Mage::getBaseDir('var') . DS . 'static_delete_me' . DS;
        shell_exec('mv ' . $path . ' ' . $pathNew . ' && rm -rf ' . $pathNew . ' 2>&1');

        //delete data base
        $resource = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_write');
        $table = $resource->getTableName('mpstaticproduct/cache');
        $connection->truncateTable($table);
        
        return;
    }

}
