<?php

class Mofluid_Paymentauthorize_Model_Mysql4_Paymentauthorize extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the web_id refers to the key field in your database table.
        $this->_init('mofluid_paymentauthorize/payment', 'payment_method_id');
    }
}
