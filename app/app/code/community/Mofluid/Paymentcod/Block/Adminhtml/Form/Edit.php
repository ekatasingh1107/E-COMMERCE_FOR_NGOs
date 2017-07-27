<?php

class Mofluid_Paymentcod_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('back');
        $this->_blockGroup = 'mofluid_paymentcod';
        $this->_controller = 'adminhtml_form';
        $this->_headerText = Mage::helper('mofluid_paymentcod')->__('Cash On Delivery');
    }

}