<?php

class Mofluid_Thememofluidmodern_Model_Mysql4_Images_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_thememofluidmodern/mofluid_themes_images');
    }
}
