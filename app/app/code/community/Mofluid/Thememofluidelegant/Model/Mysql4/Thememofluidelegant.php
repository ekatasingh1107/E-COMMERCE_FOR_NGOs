<?php

class Mofluid_Thememofluidelegant_Model_Mysql4_Thememofluidelegant extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the web_id refers to the key field in your database table.
        $this->_init('mofluid_thememofluidelegant/mofluid_themes_core', 'mofluid_theme_id');
    }
}
