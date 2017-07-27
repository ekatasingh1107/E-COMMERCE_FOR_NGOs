<?php

class Mofluid_Paymentbanktransfer_Block_Adminhtml_Form_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
    
        $model = Mage::getModel('mofluid_paymentbanktransfer/paymentbanktransfer');
        $mofluid_pay_banktransfer = $model->load(5);
        $mof_banktransfer_status = $mofluid_pay_banktransfer->getData('payment_method_status');
        
        $helper = Mage::helper('mofluid_paymentbanktransfer');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general_');
        $form->setFieldNameSuffix('general');

        $fieldset = $form->addFieldset('display', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide'
        ));
       
      $fieldset->addField('mofluid_payment_banktransfer_status', 'select', array(
          'label'     => $helper->__('Status'),
          'name'      => 'mofluid_payment_banktransfer_status',
          'required'  => true,
          'value'     => $mof_banktransfer_status,
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

       
     if (Mage::registry('mofluid_paymentbanktransfer')) {
            $form->setValues(Mage::registry('mofluid_paymentbanktransfer')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }

}