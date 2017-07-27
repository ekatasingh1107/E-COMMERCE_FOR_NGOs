<?php

class Mofluid_Paymentcod_Model_Mysql4_Paymentcod extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the web_id refers to the key field in your database table.
        $this->_init('mofluid_paymentcod/payment', 'payment_method_id');
    }
}
