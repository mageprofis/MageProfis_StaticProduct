<?php

class MageProfis_StaticProduct_Block_Json_Price
extends Mage_Catalog_Block_Product_Abstract
{
    protected function _toHtml() {
        return $this->getPriceHtml($this->getProduct());
    }
}