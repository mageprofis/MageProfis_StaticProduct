<?php

class MageProfis_StaticProduct_Model_Observer_Abstract extends Mage_Core_Model_Abstract
{
    public $_object_type = 'abstract';


    /**
     * @return bool
     */
    protected function isCacheAble()
    {
        return false;
    }

    protected function saveCachedFile($urlPath)
    {
        $store = Mage::app()->getStore()->getCode() . '_' . Mage::app()->getStore()->getStoreId();

        if ($this->getCacheMode() == 'magento')
        {
            $path = DS . 'static' . DS . $store . DS . $this->getCacheKey();
        } else
        {
            $path = DS . 'static' . DS . $store . DS . str_replace('/', DS, $urlPath);
        }

        $fullPath = Mage::getBaseDir('var') . $path;
        $fullDir = dirname($fullPath);
        if (!is_dir($fullDir))
        {
            mkdir($fullDir, 0755, true);
        }
        $content = Mage::app()->getResponse()->getBody();
        file_put_contents($fullPath, trim($content));
        $item = Mage::getModel('mpstaticproduct/cache')->load($path, 'path');
        /* @var $item MageProfis_StaticProduct_Model_Cache */
        $item->setPath($path)
                ->setObjectId($this->getObjectId())
                ->setObjectType($this->_object_type)
                ->setUpdateCreatedAt(true)
                ->save();
        ;
    }
    
    public function getObjectId()
    {
        return -1;
    }

    public function getCacheMode()
    {
        $t = Mage::getStoreConfig('staticcache/general/mode');

        //prevent chaos...
        if (!in_array($t, array('magento', 'nginx')))
            $t = 'magento';

        return $t;
    }

    public function getCacheKey()
    {
        $str = implode(';;', $this->getCacheKeyArray());
        return hash('sha256', $str);
    }

    public function getCacheKeyArray()
    {
        $cache_keys = array();

        //Default
        $cache_keys[] = Mage::app()->getStore()->getCode() . '_' . Mage::app()->getStore()->getStoreId();
        $cache_keys[] = Mage::getDesign()->getPackageName();
        $cache_keys[] = Mage::getDesign()->getTheme('template');

        //Customer
        $customer_session = Mage::getSingleton('customer/session');
        if (!$customer_session->isLoggedIn())
        {
            $cache_keys[] = 'NOT_LOGGED_IN';
        } else
        {
            $customer = $customer_session->getCustomer();
            $cache_keys[] = 'CUSTOMER_GROUP_' . $customer->getGroupId();
            $cache_keys[] = 'CUSTOMER_WEBSITE_' . $customer->getWebsiteId();
            $cache_keys[] = 'CUSTOMER_STORE_' . $customer->getStoreId();
        }
        
        return $cache_keys;
    }

}
