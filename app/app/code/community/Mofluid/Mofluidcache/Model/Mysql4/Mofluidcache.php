<?php

class Mofluid_Mofluidcache_Model_Mysql4_Mofluidcache extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the web_id refers to the key field in your database table.
        $this->_init('mofluid_mofluidcache/mofluidcache', 'mofluid_cs_id');
    }
}
