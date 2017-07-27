<?php

class Mofluid_Paymentpaypal_Block_Adminhtml_Form_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
    
        $model = Mage::getModel('mofluid_paymentpaypal/paymentpaypal');
        $mofluid_pay_paypal = $model->load(3);
        $mof_paypal_emailid = $mofluid_pay_paypal->getData('payment_account_email'); //
        $mof_paypal_status = $mofluid_pay_paypal->getData('payment_method_status');
        $mof_paypal_mode = $mofluid_pay_paypal->getData('payment_method_mode');
        $mof_paypal_apikey = $mofluid_pay_paypal->getData('payment_method_account_key');
        $helper = Mage::helper('mofluid_paymentpaypal');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general_');
        $form->setFieldNameSuffix('general');

        $fieldset = $form->addFieldset('display', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide'
        ));
       
      $fieldset->addField('mofluid_payment_paypal_status', 'select', array(
          'label'     => $helper->__('Status'),
          'name'      => 'mofluid_payment_paypal_status',
          'required'  => true,
          'value'     => $mof_paypal_status,
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

        $fieldset->addField('mofluid_payment_paypal_businessmail', 'text', array(
            'name'         => 'mofluid_payment_paypal_businessmail',
            'label'        => $helper->__('Business mail'),
            'required'       => true,
            'value'         => $mof_paypal_emailid,
            'class'        => 'validate-email',
            'after_element_html' => '<br><a href="https://www.paypal.com/in/webapps/mpp/merchant" target="_blank">Click here</a> to setup your paypal merchant account.' 
            
        ));
       $fieldset->addField('mofluid_payment_paypal_appkey', 'text', array(
            'name'         => 'mofluid_payment_paypal_appkey',
            'label'        => $helper->__('REST API Client ID'),
            'required'       => true,
            'value'         => $mof_paypal_apikey,
            'after_element_html' => '<br><a href="https://developer.paypal.com/docs/integration/admin/manage-apps/" target="_blank">Click here</a> to know about REST API Client ID' 
        ));

        $fieldset->addField('mofluid_payment_paypal_mode', 'select', array(
          'label'     => $helper->__('Mode'),
          'name'      => 'mofluid_payment_paypal_mode',
          'required'  => true,
          'value'     => $mof_paypal_mode,
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
     if (Mage::registry('mofluid_paymentpaypal')) {
            $form->setValues(Mage::registry('mofluid_paymentpaypal')->getData());
        }

        
        $this->setForm($form);
        return parent::_prepareForm();

    }

}