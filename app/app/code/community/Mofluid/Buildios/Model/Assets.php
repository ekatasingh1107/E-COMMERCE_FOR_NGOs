<?php

class Mofluid_Buildios_Model_Assets extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_buildios/assets');
    }
}
