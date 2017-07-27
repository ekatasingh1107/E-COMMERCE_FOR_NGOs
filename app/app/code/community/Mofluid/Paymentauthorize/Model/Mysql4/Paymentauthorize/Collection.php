<?php

class Mofluid_Paymentauthorize_Model_Mysql4_Paymentauthorize_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentauthorize/payment');
    }
}
