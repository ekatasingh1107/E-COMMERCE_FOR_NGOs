<?php

class Mofluid_Paymentcod_Model_Mysql4_Paymentcod_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentcod/payment');
    }
}
