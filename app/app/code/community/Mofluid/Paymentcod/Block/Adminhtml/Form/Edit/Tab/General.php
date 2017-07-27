<?php

class Mofluid_Paymentcod_Block_Adminhtml_Form_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
    
        $model = Mage::getModel('mofluid_paymentcod/paymentcod');
        $mofluid_pay_cod = $model->load(1);
        $mof_cod_status = $mofluid_pay_cod->getData('payment_method_status');
        
        $helper = Mage::helper('mofluid_paymentcod');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general_');
        $form->setFieldNameSuffix('general');

        $fieldset = $form->addFieldset('display', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide'
        ));
       
      $fieldset->addField('mofluid_payment_cod_status', 'select', array(
          'label'     => $helper->__('Status'),
          'name'      => 'mofluid_payment_cod_status',
          'required'  => true,
          'value'     => $mof_cod_status,
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

       
     if (Mage::registry('mofluid_paymentcod')) {
            $form->setValues(Mage::registry('mofluid_paymentcod')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }

}