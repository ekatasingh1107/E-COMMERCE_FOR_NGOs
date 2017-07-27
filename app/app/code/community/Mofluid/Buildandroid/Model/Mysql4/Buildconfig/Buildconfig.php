<?php
class Mofluid_Buildandroid_Model_Mysql4_Buildconfig extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluid_buildandroid/buildconfig', 'mofluid_platform_id'); 
    }
}
