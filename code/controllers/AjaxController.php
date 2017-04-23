<?php

class MageProfis_StaticProduct_AjaxController
extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
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
}
