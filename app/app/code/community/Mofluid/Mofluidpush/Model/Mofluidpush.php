<?php
class Mofluid_Mofluidpush_Model_Mofluidpush extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluidpush/mofluidpush');
        $this->_init('mofluidpush/mofluidpush_settings');// this is location of the resource file.
    }
}
