<?php

class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
       // $this->setTemplate('gallerymedia/edit/tab/image.phtml'); 
        $this->setId('edit_home_tabs');
        $this->setDestElementId('edit_form');
        $name = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->name;
        $type = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->type;
        $version = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->version;
        $title = $name.' '.$type.' '.$version;
        if(trim($title) == '' || $title == null) {
            $title = 'Mofluid';
        }
        $this->setTitle(Mage::helper('mofluid_thememofluidelegant')->__($title));
          
    }

    /**
     * add tabs before output
     *
     * @return Mofluid_Paymentcod_Block_Adminhtml_Form_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
       $this->addTab('configuration', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Configuration'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Configuration'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_configuration')->toHtml(),
        ));

        $this->addTab('logo', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Logo'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Logo'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_logo')->toHtml(),
        ));
        
        $this->addTab('banner', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Configure or Add Banner'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Configure or Add Banner'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_banner')->toHtml(),
        ));
        
        $this->addTab('banner_list', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Banner List'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Banner List'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_bannerlist')->toHtml(),
        ));
        
        $this->addTab('themeicons', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Theme Icons'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Theme Icons'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_themeicons')->toHtml(),
        )); 
        $this->addTab('themecolor_foreground', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Foreground Color Scheme'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Foreground Color Scheme'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_themecolorforeground')->toHtml(),
        ));
        
        $this->addTab('themecolor_background', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Background Color Scheme'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Background Color Scheme'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_themecolorbackground')->toHtml(),
        ));

       $this->addTab('buttontext', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Button Text'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Button Text'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_buttontext')->toHtml(),
        ));

        $this->addTab('apptext', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Application Text'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Application Text'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_apptext')->toHtml(),
        ));  

        $this->addTab('message', array(
            'label'     => Mage::helper('mofluid_thememofluidelegant')->__('Warning/Alert Messages'),
            'title'     => Mage::helper('mofluid_thememofluidelegant')->__('Warning/Alert Messages'),
            'content'   => $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_message')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
