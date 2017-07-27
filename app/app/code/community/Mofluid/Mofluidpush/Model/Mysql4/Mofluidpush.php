<?php
class Mofluid_Mofluidpush_Model_Mysql4_Mofluidpush extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluidpush/mofluidpush', 'push_id');
        $this->_init('mofluidpush/mofluidpush_settings', 'mofluidadmin_id');
    }
}
