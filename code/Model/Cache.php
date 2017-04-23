<?php

/**
 * @method int getPath()
 * @method MageProfis_StaticProduct_Model_Cache setPath(int $id)
 * @method string getProductId() Name
 * @method MageProfis_StaticProduct_Model_Cache setProductId(string $name)
 * @method int getStoreId() get Store Id
 * @method MageProfis_StaticProduct_Model_Cache setStoreId(int $store_id) set Store Id
 * 
 * @method MageProfis_StaticProduct_Model_Resource_Cache _getResource()
 * @method MageProfis_StaticProduct_Model_Resource_Cache getResource()
 */
class MageProfis_StaticProduct_Model_Cache
extends Mage_Core_Model_Abstract
{
    
    protected function _construct()
    {
        $this->_init('mpstaticproduct/cache');
    }

    /**
     * 
     * @return MageProfis_StaticProduct_Model_Cache
     */
    protected function _beforeDelete()
    {
        parent::_afterDelete();
        if ($this->getPath())
        {
            $path = Mage::getBaseDir('var').$this->getPath();
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        return $this;
    }

    /**
     * Processing object before save data
     *
     * @return MageProfis_StaticProduct_Model_Cache
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if (!$this->getId())
        {
            $this->setData('created_at', date('Y-m-d H:i:s'));
        }
        if (!$this->getStoreId())
        {
            $id = Mage::app()->getStore()->getStoreId();
            $this->setData('store_id', $id);
        }
        return $this;
    }
}