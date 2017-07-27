<?php
class Mofluid_Mofluidcache_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setTitle(Mage::helper('mofluid_mofluidcache')->__($title));
    }

    /**
     * add tabs before output
     *
     * @return Mofluid_Paymentcod_Block_Adminhtml_Form_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
         $this->addTab('configuration', array(
            'label'     => Mage::helper('mofluid_mofluidcache')->__('Mofluidcache Settings'),
            'title'     => Mage::helper('mofluid_mofluidcache')->__('Mofluidcache Settings'),
            'content'   => $this->getLayout()->createBlock('mofluid_mofluidcache/adminhtml_form_edit_tab_configuration')->toHtml(),
        ));
        
         return parent::_beforeToHtml();
    }

}
