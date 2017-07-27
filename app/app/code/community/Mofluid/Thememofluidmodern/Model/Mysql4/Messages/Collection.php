<?php

class Mofluid_Thememofluidmodern_Model_Mysql4_Messages_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_thememofluidelegant/mofluid_themes_message');
    }
}
