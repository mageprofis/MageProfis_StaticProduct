<?php

class MageProfis_StaticProduct_Model_Observer
extends Mage_Core_Model_Abstract
{
    
    public function onProductSaveAfter($event)
    {
        $product = $event->getProduct();
        $collection = Mage::getModel('mpstaticproduct/cache')->getCollection();
        $collection->addFieldToFilter('product_id', $product->getId());
        foreach($collection as $_cache)
        {
            $_cache->delete();
        }
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

    /**
     * @return bool
     */
    protected function isCacheAble()
    {
        if (stristr(Mage::helper('core/url')->getCurrentUrl(), 'catalog/product/view/'))
        {
            return false;
        }
        
        if (!Mage::getStoreConfigFlag('web/seo/use_rewrites'))
        {
            return false;
        }

        if (Mage::helper('customer')->isLoggedIn())
        {
            return false;
        }

        if ( (float) Mage::helper('checkout/cart')->getItemsCount() > 0)
        {
            return false;
        }

        if (!$this->getProduct() || !$this->getProduct()->getId())
        {
            return false;
        }

        return true;
    }
    
    protected function saveCachedFile($urlPath)
    {
        $store = Mage::app()->getStore()->getCode().'_'.Mage::app()->getStore()->getStoreId();
        $path = DS.'static'.DS.$store.DS.str_replace('/', DS, $urlPath);
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
                ->setProductId($this->getProduct()->getId())
                ->save();
        ;
    }
}
