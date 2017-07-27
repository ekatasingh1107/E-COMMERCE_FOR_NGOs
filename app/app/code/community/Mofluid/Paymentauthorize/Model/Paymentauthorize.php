<?php

class Mofluid_Paymentauthorize_Model_Paymentauthorize extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mofluid_paymentauthorize/paymentauthorize');
    }
}
