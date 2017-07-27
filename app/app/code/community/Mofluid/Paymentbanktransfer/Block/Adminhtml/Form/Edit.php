<?php

class Mofluid_Paymentbanktransfer_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('back');
        $this->_blockGroup = 'mofluid_paymentbanktransfer';
        $this->_controller = 'adminhtml_form';
        $this->_headerText = Mage::helper('mofluid_paymentbanktransfer')->__('Bank Transfer');
    }

}