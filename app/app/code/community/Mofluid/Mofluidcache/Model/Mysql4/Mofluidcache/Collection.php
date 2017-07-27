<?php

class Mofluid_Cache_Model_Mysql4_Cache_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_cache/cache');
    }
}
