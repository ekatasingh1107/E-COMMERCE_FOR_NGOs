<?php

class Mofluid_Paymentpaypal_Model_Mysql4_Paymentpaypal extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the web_id refers to the key field in your database table.
        $this->_init('mofluid_paymentpaypal/payment', 'payment_method_id');
    }
}
