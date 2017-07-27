<?php

class Mofluid_Buildandroid_Model_Mysql4_Assets_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_buildandroid/assets');
    }
}
