<?php

class Mofluid_Paymentbanktransfer_Model_Mysql4_Paymentbanktransfer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentbanktransfer/payment');
    }
}
