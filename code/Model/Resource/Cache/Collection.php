<?php

class MageProfis_StaticProduct_Model_Resource_Cache_Collection
extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('mpstaticproduct/cache');
    }
}