<?php

class MageProfis_StaticProduct_AjaxController
extends Mage_Core_Controller_Front_Action
{
    public function productAction() {
        if ($this->getRequest()->isPost())
        {
            $productid = $this->getRequest()->getParam('product_id', false);
            Mage::register('product_id', $productid);
            $this->loadLayout($this->getFullActionName());
            $this->_initLayoutMessages('checkout/session');
            $this->_initLayoutMessages('catalog/session');
            $this->renderLayout();
        } else {
            $id = (int) Mage::getSingleton('core/session')->getLastProductId();
            $helper =  Mage::helper('mpstaticproduct');
            if ($id && $helper->getProduct($id)->getId())
            {
                $this->_redirectUrl($helper->getProduct($id)->getProductUrl());
            } else {
                $this->norouteAction();
            }
        }
    }
    
    public function categoryAction() {
        if ($this->getRequest()->isPost())
        {
            $categoryid = $this->getRequest()->getParam('category_id', false);
            Mage::register('category_id', $categoryid);
            $this->loadLayout($this->getFullActionName());
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
        } else {
            $this->norouteAction();
        }
    }
}
