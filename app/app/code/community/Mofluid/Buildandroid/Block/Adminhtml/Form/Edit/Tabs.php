<?php

class Mofluid_Buildandroid_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setTitle(Mage::helper('mofluid_buildandroid')->__($title));
    }

    /**
     * add tabs before output
     *
     * @return Mofluid_Paymentcod_Block_Adminhtml_Form_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
       $this->addTab('account', array(
            'label'     => Mage::helper('mofluid_buildandroid')->__('Account Credentials'),
            'title'     => Mage::helper('mofluid_buildandroid')->__('Account Credentials'),
            'content'   => $this->getLayout()->createBlock('mofluid_buildandroid/adminhtml_form_edit_tab_account')->toHtml(),
        ));
        $this->addTab('configuration', array(
            'label'     => Mage::helper('mofluid_buildandroid')->__('Build Configuration'),
            'title'     => Mage::helper('mofluid_buildandroid')->__('Build Configuration'),
            'content'   => $this->getLayout()->createBlock('mofluid_buildandroid/adminhtml_form_edit_tab_configuration')->toHtml(),
        ));
        $this->addTab('assets', array(
            'label'     => Mage::helper('mofluid_buildandroid')->__('Application\'s Assets'),
            'title'     => Mage::helper('mofluid_buildandroid')->__('Application\'s Assets'),
            'content'   => $this->getLayout()->createBlock('mofluid_buildandroid/adminhtml_form_edit_tab_assets')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
