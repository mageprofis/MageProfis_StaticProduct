<?php

class MageProfis_StaticProduct_Model_Observer_Category extends MageProfis_StaticProduct_Model_Observer_Abstract
{
    public $_object_type = 'category';
    
    public function onSaveAfter($event)
    {
        $category = $event->getCategory();
        Mage::helper('mpstaticproduct')->deleteCacheForCategoryId($category->getId());
    }
    
    public function onViewPreDispatch($event)
    {        
        if (Mage::helper('mpstaticproduct')->isCategoryCacheActive() && $this->getCacheMode() == 'magento' && !$this->isIgnored())
        {
            $store = Mage::app()->getStore()->getCode() . '_' . Mage::app()->getStore()->getStoreId();
            $path = DS . 'static' . DS . $store . DS . $this->getCacheKey();
            $fullPath = Mage::getBaseDir('var') . $path;

            if (file_exists($fullPath))
            {
                $c = file_get_contents($fullPath);
                echo $c;
                exit;
            }
        }
    }

    /**
     * @mageEvent controller_action_postdispatch_catalog_product_view
     * @mageEvent 'controller_action_postdispatch_'.$this->getFullActionName()
     */
    public function onViewPostDispatch($event)
    {
        if ($this->isCacheAble())
        {
            /* @var $controller Mage_Catalog_ProductController */
            $controller = $event->getControllerAction();

            $productUrl = trim(parse_url($this->getCategory()->getUrl(), PHP_URL_PATH), '/');
            if (!empty($productUrl))
            {
                $this->saveCachedFile($productUrl);
            }
        }
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    protected function getCategory()
    {
        return Mage::registry('current_category');
    }
    
    public function getObjectId()
    {
        if ($this->getCategory() && $this->getCategory()->getId())
        {
            return $this->getCategory()->getId();
        } else if (Mage::app()->getRequest()->getParam('id'))
        {
            return Mage::app()->getRequest()->getParam('id');
        }

        return -99;        
    }
    
    public function isIgnored()
    {
        $ids = Mage::getStoreConfig('staticcache/category/ignore_ids');
        $ids = explode(",", $ids);
        $ids = array_filter($ids);
        
        return in_array($this->getObjectId(), $ids);
    }

    /**
     * @return bool
     */
    protected function isCacheAble()
    {
        if(!Mage::helper('mpstaticproduct')->isCategoryCacheActive())
        {
            return false;
        }
        
        if($this->isIgnored())
        {
            return false;
        }
        
        if (stristr(Mage::helper('core/url')->getCurrentUrl(), 'catalog/category/view/'))
        {
            return false;
        }

        if (!Mage::getStoreConfigFlag('web/seo/use_rewrites'))
        {
            return false;
        }

        if ($this->getCacheMode() != 'magento' && Mage::helper('customer')->isLoggedIn())
        {
            return false;
        }

        if ($this->getCacheMode() != 'magento' && (float) Mage::helper('checkout/cart')->getItemsCount() > 0)
        {
            return false;
        }

        if (!$this->getCategory() || !$this->getCategory()->getId())
        {
            return false;
        }

        return true;
    }

    public function getCacheKeyArray()
    {
        $cache_keys = parent::getCacheKeyArray();

        $cache_keys[] = 'CATEGORY';
        
        //Product
        if ($this->getCategory() && $this->getCategory()->getId())
        {
            $cache_keys[] = $this->getCategory()->getId();
        } else if (Mage::app()->getRequest()->getParam('id'))
        {
            $cache_keys[] = Mage::app()->getRequest()->getParam('id');
        }
        
        $params = Mage::app()->getRequest()->getParams();
        foreach ($params as $key => $value)
        {
            if (in_array($key, array('id')))
                continue;

            $cache_keys[] = 'PARAM_' . $key . '_VAL_' . $value;
        }

        return $cache_keys;
    }

}
