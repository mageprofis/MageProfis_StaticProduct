<?php

class MageProfis_StaticProduct_Block_Json_Breadcrumbs
extends Mage_Catalog_Block_Breadcrumbs
{
    protected function _construct() {
        parent::_construct();
        $this->setData('module_name', 'Mage_Catalog');
    }
    
    /**
     * Preparing layout
     *
     * @return Mage_Catalog_Block_Breadcrumbs
     */
    protected function _prepareLayout()
    {
        $this->getProduct();
        Mage::helper('mpstaticproduct')->getCurrentCategory();
        parent::_prepareLayout();
    }

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