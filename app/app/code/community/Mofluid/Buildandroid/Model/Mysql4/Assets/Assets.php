<?php
class Mofluid_Buildandroid_Model_Mysql4_Assets extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluid_buildandroid/assets', 'mofluid_assets_id'); 
    }
}
