<?php

class MageProfis_StaticProduct_Block_Category_Js
extends Mage_Core_Block_Abstract
{
    /**
     * 
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('mpstaticproduct/ajax/category');
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getCategory()
    {
        if ($this->getData('category'))
        {
            return $this->getData('category');
        }
        $this->setData('category', Mage::registry('current_category'));
        return Mage::registry('current_category');
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->getCategory()->getId();
    }

    /**
     * @return string
     */
    protected function _toHtml() {
        return '<script type="text/javascript">'
                . 'new Category.StaticCache({category_id: '.$this->getCategoryId().','
                . 'url: "'.$this->getAjaxUrl().'"})'
                . '</script>';
    }
}