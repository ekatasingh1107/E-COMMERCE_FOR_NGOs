<?php

class Mofluid_Paymentpaypal_Model_Paymentpaypal extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentpaypal/paymentpaypal');
    }
}
