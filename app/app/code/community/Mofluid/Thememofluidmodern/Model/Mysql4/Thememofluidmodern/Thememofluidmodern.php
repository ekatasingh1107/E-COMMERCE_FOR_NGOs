<?php
class Mofluid_Thememofluidmodern_Model_Mysql4_Thememofluidmodern extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluid_thememofluidmodern/mofluid_themes_core', 'mofluid_theme_id'); 
    }
}
