<?php

class Mofluid_Paymentpaypal_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('back');
        $this->_blockGroup = 'mofluid_paymentpaypal';
        $this->_controller = 'adminhtml_form';
        $this->_headerText = Mage::helper('mofluid_paymentpaypal')->__('Paypal');
    }

}