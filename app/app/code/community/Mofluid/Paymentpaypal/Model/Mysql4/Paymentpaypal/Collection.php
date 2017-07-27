<?php

class Mofluid_Paymentpaypal_Model_Mysql4_Paymentpaypal_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentpaypal/payment');
    }
}
