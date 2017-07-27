<?php

class Mofluid_Mofluidcache_Block_Adminhtml_Form_Edit_Tab_Configuration extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
        $helper = Mage::helper('mofluid_mofluidcache');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('');
		$mofluid_mofluidcache_settings_model = Mage::getModel('mofluid_mofluidcache/mofluidcache')->load(25);
        $mof_cs_id = $mofluid_mofluidcache_settings_model->getData('mofluid_cs_id'); //
        $mof_cs_account_id = $mofluid_mofluidcache_settings_model->getData('mofluid_cs_accountid');
        $mof_cs_status = $mofluid_mofluidcache_settings_model->getData('mofluid_cs_status');
        $mof_cs_extras = $mofluid_mofluidcache_settings_model->getData('mofluid_cs_extras');
    
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general_');
        $form->setFieldNameSuffix('general');

        $fieldset = $form->addFieldset('display', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide'
        ));
       
      $fieldset->addField('mofluid_mofluidcache_status', 'select', array(
          'label'     => $helper->__('Enable'),
          'name'      => 'mofluid_mofluidcache_status',
          'required'  => true,
          'class'     => 'validate-select',
          'value'     => $mof_cs_status,
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => $helper->__('Yes'),
              ),

              array(
                  'value'     => 0,
                  'label'     => $helper->__('No'),
              ),
          ),
      ));
	/*	
		$fieldset->addField('time', 'time', array(
          'label'     => Mage::helper('form')->__('Time'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
          'onclick' => "",
          'onchange' => "",
          'value'  => '12,04,15',
          'disabled' => false,
          'readonly' => false,
          'after_element_html' => '<small>Comments</small>',
          'tabindex' => 1
        ));
		*/
		
        $fieldset->addField('mofluid_mofluidcache_account_id', 'text', array(
            'name'         => 'mofluid_mofluidcache_account_id',
            'label'        => $helper->__('Cache Expire Time(In Minute)'),
            'required'       => true,
            'value'         => $mof_cs_account_id,
          	'class'     => 'validate-not-negative-number'

        ));

         if (Mage::registry('mofluid_mofluidcache')) {
            $form->setValues(Mage::registry('mofluid_mofluidcache')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();      
        
    }

}
