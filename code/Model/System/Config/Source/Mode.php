<?php

class MageProfis_StaticProduct_Model_System_Config_Source_Mode
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'magento', 'label' => Mage::helper('adminhtml')->__('Magento')),
            array('value' => 'nginx', 'label' => Mage::helper('adminhtml')->__('Nginx')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'magento' => Mage::helper('adminhtml')->__('Magento'),
            'nginx' => Mage::helper('adminhtml')->__('Nginx'),
        );
    }

}
