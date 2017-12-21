<?php

class MageProfis_StaticProduct_Block_Product_Js
extends Mage_Core_Block_Abstract
{
    /**
     * 
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('mpstaticproduct/ajax/product');
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if ($this->getData('product'))
        {
            return $this->getData('product');
        }
        $this->setData('product', Mage::registry('current_product'));
        return Mage::registry('current_product');
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->getProduct()->getId();
    }

    /**
     * @return string
     */
    protected function _toHtml() {
        return '<script type="text/javascript">'
                . 'new Product.StaticCache({product_id: '.$this->getProductId().','
                . 'url: "'.$this->getAjaxUrl().'"})'
                . '</script>';
    }
}