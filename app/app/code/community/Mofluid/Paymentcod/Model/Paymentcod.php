<?php

class Mofluid_Paymentcod_Model_Paymentcod extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentcod/paymentcod');
    }
}
