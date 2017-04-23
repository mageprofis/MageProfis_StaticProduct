<?php

class MageProfis_StaticProduct_Block_Json_Comparelink
extends MageProfis_StaticProduct_Block_Json_Abstract
{
    protected function _toHtml() {
        $helper = Mage::helper('catalog/product_compare');
        /* @var $helper Mage_Catalog_Helper_Product_Compare */
        return $helper->getAddUrl($this->getProduct());
    }
}