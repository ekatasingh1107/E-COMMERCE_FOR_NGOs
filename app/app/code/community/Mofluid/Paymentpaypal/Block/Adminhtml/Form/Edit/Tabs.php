<?php

class Mofluid_Paymentpaypal_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_home_tabs');
        $this->setDestElementId('edit_form');
        $name = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->name;
        $type = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->type;
        $version = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->version;
        $title = $name.' '.$type.' '.$version;
        if(trim($title) == '' || $title == null) {
            $title = 'Mofluid';
        }
        $this->setTitle(Mage::helper('mofluid_paymentpaypal')->__($title));
    }

    /**
     * add tabs before output
     *
     * @return Mofluid_Paymentpaypal_Block_Adminhtml_Form_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('mofluid_paymentpaypal')->__('Configuration'),
            'title'     => Mage::helper('mofluid_paymentpaypal')->__('Configuration'),
            'content'   => $this->getLayout()->createBlock('mofluid_paymentpaypal/adminhtml_form_edit_tab_general')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}