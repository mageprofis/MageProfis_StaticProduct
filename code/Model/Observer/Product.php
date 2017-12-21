<?php

class MageProfis_StaticProduct_Model_Observer_Product extends MageProfis_StaticProduct_Model_Observer_Abstract
{
    public $_object_type = 'product';
    
    public function onProductViewPreDispatch($event)
    {
        if (Mage::helper('mpstaticproduct')->isProductCacheActive() && $this->getCacheMode() == 'magento')
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
    
    public function onProductSaveAfter($event)
    {
        $product = $event->getProduct();
        Mage::helper('mpstaticproduct')->deleteCacheForProductId($product->getId());
    }

    /**
     * @mageEvent controller_action_postdispatch_catalog_product_view
     * @mageEvent 'controller_action_postdispatch_'.$this->getFullActionName()
     */
    public function onProductViewPostDispatch($event)
    {
        if ($this->isCacheAble())
        {
            /* @var $controller Mage_Catalog_ProductController */
            $controller = $event->getControllerAction();

            $productUrl = trim(parse_url($this->getProduct()->getProductUrl(), PHP_URL_PATH), '/');
            if (!empty($productUrl))
            {
                $this->saveCachedFile($productUrl);
            }
        }
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    protected function getProduct()
    {
        return Mage::registry('current_product');
    }
    
    public function getObjectId()
    {
        return $this->getProduct()->getId();
    }

    /**
     * @return bool
     */
    protected function isCacheAble()
    {
        if(!Mage::helper('mpstaticproduct')->isProductCacheActive())
        {
            return false;
        }
        
        if (stristr(Mage::helper('core/url')->getCurrentUrl(), 'catalog/product/view/'))
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

        if (!$this->getProduct() || !$this->getProduct()->getId())
        {
            return false;
        }

        return true;
    }

    public function getCacheKeyArray()
    {
        $cache_keys = parent::getCacheKeyArray();

        $cache_keys[] = 'PRODUCT';

        //Product
        if ($this->getProduct() && $this->getProduct()->getId())
        {
            $cache_keys[] = $this->getProduct()->getId();
        } else if (Mage::app()->getRequest()->getParam('id'))
        {
            $cache_keys[] = Mage::app()->getRequest()->getParam('id');
        }

        return $cache_keys;
    }

}
