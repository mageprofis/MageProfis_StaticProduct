<?php

class MageProfis_StaticProduct_Model_Resource_Cache
extends Mage_Core_Model_Resource_Db_Abstract
{
    
    protected function _construct()
    {
        $this->_init('mpstaticproduct/cache', 'cache_id');
    }
}