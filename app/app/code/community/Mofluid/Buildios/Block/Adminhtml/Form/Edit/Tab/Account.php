<?php

class Mofluid_Buildios_Block_Adminhtml_Form_Edit_Tab_Account extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
        $helper = Mage::helper('mofluid_buildios');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluid_build_ios_account');

        $mofluid_build_model = Mage::getModel('mofluid_buildios/accounts');
        $mofluid_build_account_model = $mofluid_build_model->getCollection()->addFieldToFilter('mofluid_platform_id',1);
        $mofluid_build_account_data = $mofluid_build_account_model->getData(); 
        
        $mofluid_build_account = $mofluid_build_account_data[0]; 
        $mofluid_configuration_fields = $form->addFieldset('mofluid_configuration', array(
            'legend'       => $helper->__('Mofluid Credentials'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
        $userId = Mage::getSingleton('admin/session')->getUser()->getUserId();

        $mofluid_configuration_fields->addField('mofluid_loginadmin_id', 'hidden', array(
          'name'      => 'mofluid_loginadmin_id',
          'label'     => 'Admin ID',
          'value'     =>  $userId
        ));
        
       $mofluid_configuration_fields->addField('mofluid_username', 'text', array(
          'name'      => 'mofluid_username',
          'label'     => 'Mofluid Username',
          'required'  => true,
           'class' => 'validate-email',
          'value'     =>  $mofluid_build_account["mofluid_id"],
          'after_element_html' => '<br><b>Note : Generated iOS build will be delivered at this email id.</b>'
        ));
        $default_password = $mofluid_build_account["mofluid_password"];
        if(!$default_password) {
            $default_password = "mofluidpassword";
        }
        $mofluid_configuration_fields->addField('mofluid_password', 'hidden', array(
          'name'      => 'mofluid_password',
          'label'     => 'Mofluid Password',
          'required'  => true,
            'class' => 'validate-password',
          'value'     => $default_password
          ));
      /*  
        $phonegap_build_fields = $form->addFieldset('phonegap_build', array(
            'legend'       => $helper->__('Phonegap Build Credentials'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
       $phonegap_build_fields->addField('phonegap_build_username', 'text', array(
          'name'      => 'phonegap_build_username',
          'label'     => 'Phonegap Build Username',
          'required'  => true,
           'class' => 'validate-email',
          'value'     =>  $mofluid_build_account["phonegap_build_id"],
          'after_element_html' => '<br />Username of Phonegap Build account. If you don\'t have an account Signup at <a target="_blank" href="http://build.phonegap.com">Phonegap Build</a> and provide its account username.',
          
        ));
        $phonegap_build_fields->addField('phonegap_build_password', 'password', array(
          'name'      => 'phonegap_build_password',
          'label'     => 'Phonegap Build Password',
          'required'  => true,
            'class' => 'validate-password',
          'value'     =>  $mofluid_build_account["phonegap_build_password"],
          'after_element_html' => '<br />Password of Phonegap Build account. If you don\'t have an account Signup at <a target="_blank" href="http://build.phonegap.com">Phonegap Build</a> and provide its account password.',
        ));
        
        
       */
        
      
        $mofluid_certificates_fields = $form->addFieldset('mofluid_ios_certificates', array(
            'legend'       => $helper->__('iOS Certificates'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
        $certificates_helptext = '';
        if($mofluid_build_account["certificate_path"]) {
            $certificates_helptext = '<br /><b>'.basename($mofluid_build_account["certificate_path"]).'</b>';
        }
        $certificates_helptext .= '<br/> Upload your iOS Developer Account Certificate with its private key (.p12 file). Export your certificate and private key pair from your MacOS X keychain which will create .p12 file. for more detail <a target="_blank" href="http://docs.mofluid.com/how-to-export-ios-certificates">Click here</a>';
        
        $mofluid_certificates_fields->addField('mofluid_certificates', 'image', array(
          'name'      => 'mofluid_certificates',
          'label'     =>  $helper->__('iOS Certificates'),
          'value'     =>  $mofluid_build_account["certificate_path"],
          'required'  => true,
          'after_element_html' => $certificates_helptext,
          ));
        
        $mofluid_certificates_fields->addField('mofluid_passpharse', 'text', array(
          'name'      => 'mofluid_passpharse',
          'label'     =>  $helper->__('Passpharse'),
          'value'     =>  $mofluid_build_account["certificate_passpharse"],
          'required'  => true,
          'after_element_html' => '<br />Provide Passpharse to unlock your certificate. which you have given while exporting the certificate.',
          
        ));
         $mofluid_certificates_fields->addField('certificate_type', 'select', array(
          'name'      => 'certificate_type',
          'label'     =>  $helper->__('Certificate Type'),
          'value'     =>  $mofluid_build_account['certificate_type'],
          'required'  => true,
          'values'    => array(
          					array("label"=>"Development", "value"=>"0"),
          					array("label"=>"Production", "value"=>"1")
          				 ),
          'after_element_html' => '<br />Select your iOS Developer Certificate Type. if you have uploaded distribution/production certificate, production build will be generated otherwise developement build will be generated',
          
        ));
        $provisioning_helptext = '';
        if($mofluid_build_account["provisioning_profile"]) {
            $provisioning_helptext = '<br /><b>'.basename($mofluid_build_account["provisioning_profile"]).'</b>';
        }
        $provisioning_helptext .= '<br/> Upload your provisioning profile make sure the provisioning profile will use correct bundle id and has all the device UDID attached in case of developement build. for more detail <a target="_blank" href="http://docs.mofluid.com/how-to%E2%80%8B-create%E2%80%8B-provisioning-profile">Click here</a>';
        $mofluid_certificates_fields->addField('mofluid_provisioning_profile', 'image', array(
          'name'      => 'mofluid_provisioning_profile',
          'label'     =>  $helper->__('Provisioning Profile'),
          'value'     =>  $mofluid_build_account["provisioning_profile"],
          'required'  => true,
          'after_element_html' => $provisioning_helptext,
          
        ));
        if (Mage::registry('mofluid_buildios')) {
            $form->setValues(Mage::registry('mofluid_buildios')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();      
    }

}
