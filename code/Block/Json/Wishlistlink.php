<?php

class MageProfis_StaticProduct_Block_Json_Wishlistlink
extends MageProfis_StaticProduct_Block_Json_Abstract
{
    
    protected function _toHtml() {
        if (!Mage::getConfig()->getModuleConfig('Mage_Wishlist')->is('active', 'true'))
        {
            return '';
        }
        $wishlistHelper = $this->helper('wishlist');
        /* @var $wishlistHelper Mage_Wishlist_Helper_Data */
        if ($wishlistHelper->isAllow())
        {
            return $wishlistHelper->getAddUrlWithParams($this->getProduct());
        }
        return '';
    }
}