<?php

class MageProfis_StaticProduct_Helper_Data
extends Mage_Core_Helper_Abstract
{

    protected $_product = null;
    protected $_category = null;
    protected $_categorie = null;
    protected $_categorieIds = null;

    /**
     * 
     * @param int|null $product_id
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct($product_id = null)
    {
        if (!$product_id)
        {
            $product_id = $this->getProductId();
        }
        if (!$this->_product && $product_id)
        {
            $attr = Mage::getSingleton('catalog/config')->getProductAttributes();
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect($attr)
                    ->addStoreFilter($this->getStoreId())
                    ->addAttributeToFilter('entity_id', (int) $product_id)
                    ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->addAttributeToFilter('visibility', array('in' => array(
                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH,
                    )))
                    ->setPageSize(1)
                    ->setCurPage(1)
            ;
            /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $lastId = Mage::getSingleton('catalog/session')->getLastVisitedCategoryId();
            $collection->addPriceData()
                    ->addUrlRewrite($lastId);
            $product = $collection->getFirstItem();
            /* @var $product Mage_Catalog_Model_Product */
            if (in_array($this->getWebsiteId(), $product->getWebsiteIds()))
            {
                $this->_product = $product;
                return $product;
            }
        }
        if (!$this->_product)
        {
            return new Varien_Object();
        }
        return $this->_product;
    }
    
    public function getCategory($id)
    {
        if (!$this->_category)
        {
            $this->_category = Mage::getModel('catalog/category')->load($id);
        }
        return $this->_category;
    }

    /**
     * get Categorie Ids for this Product
     * 
     * @return array
     */
    public function getCategories()
    {
        if (is_null($this->_categorieIds))
        {
            $id = $this->getProduct()->getId();
            $result = array();
            if ($id)
            {
                $sql = $this->_getConnection()
                        ->select()
                        ->from($this->getTableName('catalog_category_product_index'), array('category_id'))
                        ->where('product_id = ?', $id)
                ;
                $result = $this->_getConnection()->fetchCol($sql);
                array_walk($result, 'intval');
            }
            $this->_categorieIds = $result;
        }
        return $this->_categorieIds;
    }

    public function getCurrentCategory()
    {
        $lastId = (int) Mage::getSingleton('catalog/session')->getLastVisitedCategoryId();
        if ($lastId > 0 && !$this->_categorie && in_array($lastId, $this->getCategories()))
        {
            $category = Mage::getModel('catalog/category')->getCollection()
                    ->addAttributeToSelect(array('name'))
                    ->addAttributeToFilter('entity_id', $lastId)
                    ->addUrlRewriteToResult()
                    ->setCurPage(1)
                    ->setPageSize(1)
                    ->getFirstItem();
            $this->_categorie = $category;
            Mage::register('category', $category, true);
            Mage::register('current_category', $category, true);
            $this->_categorie = $category;
        }
        if ($this->_categorie instanceof Mage_Catalog_Model_Category)
        {
            return $this->_categorie;
        }
        return new Varien_Object();
    }
    
    public function deleteCacheForProductId($id)
    {
        $collection = Mage::getModel('mpstaticproduct/cache')->getCollection();
        $collection->addFieldToFilter('object_id', $id);
        $collection->addFieldToFilter('object_type', 'product');
        foreach ($collection as $_cache)
        {
            $_cache->delete();
        }
        
        return;
    }
    
    public function deleteCacheForCategoryId($id)
    {
        $collection = Mage::getModel('mpstaticproduct/cache')->getCollection();
        $collection->addFieldToFilter('object_id', $id);
        $collection->addFieldToFilter('object_type', 'category');
        foreach ($collection as $_cache)
        {
            $_cache->delete();
        }
        
        return;
    }
    
    public function isProductCacheActive()
    {
        return Mage::getStoreConfig('staticcache/general/product_active');
    }

    public function isCategoryCacheActive()
    {
        return Mage::getStoreConfig('staticcache/general/category_active');
    }

    /**
     * 
     * @return int
     */
    public function getProductId()
    {
        return (int) Mage::registry('product_id');
    }

    /**
     * 
     * @return int
     */
    public function getStoreId()
    {
        return (int) Mage::app()->getStore()->getStoreId();
    }

    /**
     * 
     * @return int
     */
    public function getWebsiteId()
    {
        return (int) Mage::app()->getWebsite()->getId();
    }

    /**
     * 
     * @return Mage_Core_Model_Resource
     */
    protected function _resource()
    {
        return Mage::getSingleton('core/resource');
    }

    /**
     * 
     * @param string $name
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getConnection($name = 'core_read')
    {
        return $this->_resource()->getConnection($name);
    }

    /**
     * @param string $modelEntity
     * @return string
     */
    protected function getTableName($modelEntity)
    {
        return $this->_resource()->getTableName($modelEntity);
    }
}
