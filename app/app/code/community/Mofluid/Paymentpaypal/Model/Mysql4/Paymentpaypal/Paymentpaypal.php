<?php
class Mofluid_Paymentpaypal_Model_Mysql4_Paymentpaypal extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('mofluid_paymentpaypal/payment', 'payment_method_id'); 
    }
}
