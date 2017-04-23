<?php

class MageProfis_StaticProduct_Block_Json_Abstract
extends Mage_Core_Block_Abstract
{
    /**
     * get Product Model
     * 
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        $product = Mage::registry('product');
        if($product instanceof Mage_Catalog_Model_Product)
        {
            return $product;
        }
        $product = Mage::helper('mpstaticproduct')->getProduct($this->getProductId());
        Mage::register('product', $product, true);
        Mage::register('current_product', $product, true);
        return $product;
    }

    /**
     * get Product Id
     * 
     * @return int
     */
    public function getProductId()
    {
        return (int) Mage::registry('product_id');
    }
}