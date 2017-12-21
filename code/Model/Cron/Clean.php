<?php

class MageProfis_StaticProduct_Model_Cron_Clean extends Mage_Core_Model_Abstract
{
    public function run()
    {
        $to_date   = date('Y-m-d H:i:s', time() - (60*60*24*4)); //last 4 days
                                                                 //see also a find cronjob!!!
        
        $collection = Mage::getModel('mpstaticproduct/cache')->getCollection();
        $collection->addFieldToFilter('created_at', array('to' => $to_date));

        foreach ($collection as $_cache)
        {
            $_cache->delete();
        }
    }

}
