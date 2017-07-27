<?php

class Mofluid_Paymentauthorize_Block_Adminhtml_Form_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
    
        $model = Mage::getModel('mofluid_paymentauthorize/paymentauthorize');
        $mofluid_pay_authorize = $model->load(2);
        $mof_authorize_id = $mofluid_pay_authorize->getData('payment_method_account_id'); //
        $mof_authorize_key = $mofluid_pay_authorize->getData('payment_method_account_key');
        $mof_authorize_status = $mofluid_pay_authorize->getData('payment_method_status');
        $mof_authorize_mode = $mofluid_pay_authorize->getData('payment_method_mode');
    
        $helper = Mage::helper('mofluid_paymentauthorize');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general_');
        $form->setFieldNameSuffix('general');

        $fieldset = $form->addFieldset('display', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide'
        ));
       
      $fieldset->addField('mofluid_payment_authorize_status', 'select', array(
          'label'     => $helper->__('Status'),
          'name'      => 'mofluid_payment_authorize_status',
          'required'  => true,
          'class'     => 'validate-select',
          'value'     => $mof_authorize_status,
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => $helper->__('Enabled'),
              ),

              array(
                  'value'     => 0,
                  'label'     => $helper->__('Disabled'),
              ),
          ),
      ));

        $fieldset->addField('mofluid_payment_authorize_id', 'text', array(
            'name'         => 'mofluid_payment_authorize_id',
            'label'        => $helper->__('Authorize.Net ID'),
            'required'       => true,
            'value'         => $mof_authorize_id,
            'class'        => 'validate-alphanum',

        ));

        $fieldset->addField('mofluid_payment_authorize_key', 'text', array(
            'name'         => 'mofluid_payment_authorize_key',
            'label'        => $helper->__('Authorize.Net Key'),
            'required'       => true,
            'value'          => $mof_authorize_key,
            'class'        => 'validate-alphanum',
        ));
        
        $fieldset->addField('mofluid_payment_authorize_mode', 'select', array(
          'label'     => $helper->__('Mode'),
          'name'      => 'mofluid_payment_authorize_mode',
          'required'  => true,
          'class'     => 'validate-select',
          'value'     => $mof_authorize_mode,
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => $helper->__('Test'),
              ),

              array(
                  'value'     => 1,
                  'label'     => $helper->__('Live'),
              ),
          ),
      ));
    
     if (Mage::registry('mofluid_paymentauthorize')) {
            $form->setValues(Mage::registry('mofluid_paymentauthorize')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }

}
