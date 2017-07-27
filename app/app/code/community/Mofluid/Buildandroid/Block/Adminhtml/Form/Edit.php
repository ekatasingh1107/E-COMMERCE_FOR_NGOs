<?php

class Mofluid_Buildandroid_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('back');
        $this->_removeButton('save');
        $this->_addButton('mofluid_buildandroid_save', array(
            'label'     => Mage::helper('mofluid_buildandroid')->__('Save Details'),
            'onclick'   => "editForm.submit();",
            'class'   => 'save'
        ));
        $this->_addButton('mofluid_buildandroid_generate', array(
            'label'     => Mage::helper('mofluid_buildandroid')->__('Save and Generate App'),
            'onclick'   => "editForm.submit('".$this->getUrl('/*/generate')."')", 
            'class'   => 'save'
        ));
        
        $this->_blockGroup = 'mofluid_buildandroid';
        $this->_controller = 'adminhtml_form';
        $this->_headerText = Mage::helper('mofluid_buildandroid')->__('Mofluid Build - Android');
        
    }

}
