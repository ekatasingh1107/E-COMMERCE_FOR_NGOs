<?php
class Mofluid_Cache_Model_Mysql4_Cache extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluid_cache/cache', 'mofluid_cs_id'); 
    }
}
