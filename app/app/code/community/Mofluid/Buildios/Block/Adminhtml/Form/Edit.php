<?php

class Mofluid_Buildios_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_removeButton('back');
        $this->_removeButton('save');
        $this->_addButton('mofluid_buildios_save', array(
            'label'     => Mage::helper('mofluid_buildios')->__('Save Details'),
            'onclick'   => "editForm.submit();",
            'class'   => 'save'
        ));
        $this->_addButton('mofluid_buildios_generate', array(
            'label'     => Mage::helper('mofluid_buildios')->__('Save and Generate App'),
            'onclick'   => "editForm.submit('".$this->getUrl('/*/generate')."')", 
            'class'   => 'save'
        ));
        
        $this->_blockGroup = 'mofluid_buildios';
        $this->_controller = 'adminhtml_form';
        $this->_headerText = Mage::helper('mofluid_buildios')->__('Mofluid Build - iOS');
        
    }

}
