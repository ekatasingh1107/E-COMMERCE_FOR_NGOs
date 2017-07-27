<?php

class Mofluid_Mofluidcache_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('back');
        $this->_removeButton('save');
        $this->_addButton('mofluid_mofluidcache_save', array(
            'label'     => Mage::helper('mofluid_mofluidcache')->__('Save Details'),
            'onclick'   => "editForm.submit();",
            'class'   => 'save'
        ));
                
        $this->_blockGroup = 'mofluid_mofluidcache';
        $this->_controller = 'adminhtml_form';
        $this->_headerText = Mage::helper('mofluid_mofluidcache')->__('Mofluid 1.18.0');
        
    }

}
