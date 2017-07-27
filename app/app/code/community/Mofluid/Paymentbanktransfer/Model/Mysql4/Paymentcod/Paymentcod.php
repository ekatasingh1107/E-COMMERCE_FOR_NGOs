<?php
class Mofluid_Paymentcod_Model_Mysql4_Paymentcod extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluid_paymentcod/payment', 'payment_method_id'); 
    }
}
