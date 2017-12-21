<?php
class MageProfis_StaticProduct_Block_Json_List_Category
extends Mage_Core_Block_Abstract
{
    /**
     * 
     * @param string $data
     * @return MageProfis_StaticProduct_Block_Json_List
     */
    protected function _saveCache($data) {
        return $this;
    }

    /**
     * 
     * @return boolean
     */
    protected function _loadCache() {
        return false;
    }

    /**
     * 
     * @return string
     */
    protected function _toHtml()
    {
        //Mage::getSingleton('core/session')->setLastProductId($this->getProduct()->getId());
        //Mage::getSingleton('catalog/session')->setLastViewedProductId($this->getProduct()->getId());
        $result = array(
            'category_id' => $this->getCategory()->getId(),
            'items'      => array(),
            'attributes' => array(),
            'info'       => '<input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'" />',
            //'form'       => Mage::helper('checkout/cart')->getAddUrl($this->getProduct()),
        );
        foreach ($this->getSortedChildren() as $name) {
            $block = $this->getLayout()->getBlock($name);
            /* @var $block Mage_Core_Block_Abstract */
            if (!$block) {
                Mage::throwException(Mage::helper('core')->__('Invalid block: %s', $name));
            }
            $block->setCategory($this->getCategory());
            $content = trim($block->toHtml());
            if (!$content && strlen($content) == 0)
            {
                continue;
            }
            $type = $block->getItemType();
            if (!$type) {
                $type = 'html';
            }
            $data = array();
            if ($block->getItemType() == 'attribute')
            {
                $data = array(
                    'class'     => $block->getBlockAlias(),
                    'attribute' => $block->getAttributeName(),
                    'content'   => $content,
                    'type'      => $type
                );
            } elseif($block->getItemType() == 'replace') {
                $data = array(
                    'class'   => $block->getBlockAlias(),
                    'content' => $content,
                    'type'      => $type
                );
            } elseif($block->getItemType() == 'hide') {
                // hide :)
            } else {
                $data = array(
                    'class'   => $block->getBlockAlias(),
                    'content' => $content,
                    'type'      => $type
                );
            }
            $result['items'][] = $data;
        }
        return Mage::helper('core')->jsonEncode($result);
    }

    /**
     * get Product Model
     * 
     * @return Mage_Catalog_Model_Product
     */
    public function getCategory()
    {
        $category = Mage::registry('category');
        if($category instanceof Mage_Catalog_Model_Category)
        {
            return $category;
        }
        $category = Mage::helper('mpstaticproduct')->getCategory($this->getCategoryId());
        try {
            Mage::unregister('category');
            Mage::unregister('current_category');
        } catch (Exception $ex) {

        }
        Mage::register('category', $category);
        Mage::register('current_category', $category);
        return $category;
    }

    /**
     * get Product Id
     * 
     * @return int
     */
    public function getCategoryId()
    {
        return (int) Mage::registry('category_id');
    }
}