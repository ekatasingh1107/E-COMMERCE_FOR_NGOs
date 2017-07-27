<?php

class Mofluid_Buildandroid_Block_Adminhtml_Form_Edit_Tab_Account extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
        $helper = Mage::helper('mofluid_buildandroid');
        $form = new Varien_Data_Form();
        //$form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluid_build_android_account');

        $mofluid_build_model = Mage::getModel('mofluid_buildandroid/accounts');
        $mofluid_build_account_model = $mofluid_build_model->getCollection()->addFieldToFilter('mofluid_platform_id',2);
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
          'value'     =>  $mofluid_build_account["mofluid_id"],
          'class' => 'validate-email',
          'after_element_html' => '<br><b>Note : Generated Android build will be delivered at this email id.</b>'
        ));
        $mofluid_password =  $mofluid_build_account["mofluid_password"];
        if(!$mofluid_password) {
            $mofluid_password = "mofluidpassword";
        }
        /* Start phone gap Entry */        
        $mofluid_configuration_fields->addField('mofluid_password', 'hidden', array(
          'name'      => 'mofluid_password',
          'label'     => 'Mofluid Password',
          'required'  => true,
          'class' => 'validate-password',
          'value'     => $mofluid_build_account["mofluid_password"],
        ));
    /*    
        $phonegap_build_fields = $form->addFieldset('phonegap_build', array(
            'legend'       => $helper->__('Phonegap Build Credentials'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
       $phonegap_build_fields->addField('phonegap_build_username', 'text', array(
          'name'      => 'phonegap_build_username',
          'label'     => 'Username of Phonegap Build account',
          'required'  => true,
           'class' => 'validate-email',
           'readonly' => true,
          'value'     =>  $mofluid_build_account["phonegap_build_id"],
          'after_element_html' => '<br />This is non editable field and is used to generate Android and iOS build',
          
        ));
        $phonegap_build_fields->addField('phonegap_build_password', 'password', array(
          'name'      => 'phonegap_build_password',
          'label'     => 'Password of Phonegap Build account',
          'required'  => true,
            'class' => 'validate-password',
            'readonly' => true,
          'value'     =>  $mofluid_build_account["phonegap_build_password"],
          'after_element_html' => '<br />This is non editable field.',
        ));
        */
        /* End phone gap Entry */
        
       
        
        $mofluid_certificates_fields = $form->addFieldset('mofluid_android_certificates', array(
            'legend'       => $helper->__('Application Signing Key'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
        if($mofluid_build_account['release_key_type'] == null || $mofluid_build_account['release_key_type'] == "") {
        	 $mofluid_build_account['release_key_type'] = 0;
        }
         $mofluid_signing_key_type_field = $mofluid_certificates_fields->addField('signing_key_type', 'select', array(
          'name'      => 'signing_key_type',
          'label'     =>  $helper->__('Signing Key Type'),
          'value'     =>  $mofluid_build_account['release_key_type'],
          'required'  => true,
          'values'    => array(
          					array("label"=>"Apply for New Signing Key", "value"=>"0"),
          					array("label"=>"I already have a Signing key", "value"=>"1")
          				 ),
          'onchange' => 'toggleSigningKeyFields(this.value)',
          'after_element_html' => '<br />If you are using first time please select "Apply for New Signing Key" and If you already have the private key generated before select "I already have a Signing key" <a target ="_blank" href="http://developer.android.com/tools/publishing/app-signing.html">Click here</a> for more detail about signing.
        '));
         $certificates_helptext = '';
        if($mofluid_build_account["certificate_path"]) {
            $certificates_helptext = '<br /><b>'.basename($mofluid_build_account["certificate_path"]).'</b>';
        }
        $certificates_helptext .= '<br/>Upload the Android Signing Key (.keystore file). If you don\'t have such file select "Apply for New Signing Key" from Signing Key Type Field. If you want to make versioning of the app it is required to upload the keystore file which you have already generated for earlier version.';
        
        $mofluid_certificates_fields->addField('mofluid_certificates', 'image', array(
          'name'      => 'mofluid_certificates',
          'label'     =>  $helper->__('Signing Key'),
          'value'     =>  $mofluid_build_account["certificate_path"],
          'after_element_html' => $certificates_helptext,
          ));
          
        $mofluid_certificates_fields->addField('release_privatekey_password', 'password', array(
          'name'      => 'release_privatekey_password',
          'label'     =>  $helper->__('Signing Key Password'),
          'value'     =>  $mofluid_build_account["release_privatekey_password"],
           'class' => 'validate-password',
          'required'  => true,
          'after_element_html' => '<br />Set Password for your New Signing key Or If you already have the key, provide the same password which you have set at the time of creation.',
          
        ));
        $mofluid_certificates_fields->addField('release_keystore_password', 'password', array(
          'name'      => 'release_keystore_password', 
          'label'     =>  $helper->__('Signing Key Store Password'),
          'value'     =>  $mofluid_build_account["release_keystore_password"],
          'required'  => true,
           'class' => 'validate-password',
          'after_element_html' => '<br />Set Password for your New Signing key Store Information Or If you already have the key, provide the same password which you have set at the time of creation.',
          
        ));
        
        $mofluid_certificates_fields->addField('release_key_validity', 'text', array(
          'name'      => 'release_key_validity', 
          'label'     =>  $helper->__('Signing Key Validity(in days)'),
          'value'     =>  $mofluid_build_account["release_key_validity"],
            'class' => 'validate-digits validate-greater-than-zero',
          'after_element_html' => '<br />Set Number of days less than 100000 for your signing key validity after that your key will expire for reuse.',
          
        ));
        $key_details = json_decode($mofluid_build_account["release_key_data"]);
        $mofluid_certificates_fields->addField('release_key_data_commonname', 'text', array(
          'name'      => 'release_key_data_commonname', 
          'label'     =>  $helper->__('Common Name'),
          'value'     =>  $key_details->CN,
          'class' => 'validate-alpha',
          'after_element_html' => '<br />Set Common Name for the Key. generally Name of the Application Owner',
          
        ));
        $mofluid_certificates_fields->addField('release_key_data_orgname', 'text', array(
          'name'      => 'release_key_data_orgname', 
          'label'     =>  $helper->__('Organization Name'),
          'value'     =>  $key_details->O,
           'class' => 'validate-alpha',
          'after_element_html' => '<br/>Set Organization Name for the Key. generally Name of the Owner\'s Organization',
          
        ));
         $mofluid_certificates_fields->addField('release_key_data_orgunit', 'text', array(
          'name'      => 'release_key_data_orgunit', 
          'label'     =>  $helper->__('Organization Unit'),
          'value'     =>  $key_details->OU,
           'class' => 'validate-alpha',
          'after_element_html' => '<br/>Set Organization Unit for the Key. generally Unit name of the Owner\'s Organization like Developemet, QA, Production, Marketing, Sale etc',
          
        ));
        $mofluid_certificates_fields->addField('release_key_data_locality', 'text', array(
          'name'      => 'release_key_data_locality', 
          'label'     =>  $helper->__('City or Locality'),
          'value'     =>  $key_details->L,
           'class' => 'validate-alpha',
          'after_element_html' => '<br/>Set City or locality for the Key. generally name of the city or locality where owner\'s organization is situated.',
          
        ));
        $mofluid_certificates_fields->addField('release_key_data_province', 'text', array(
          'name'      => 'release_key_data_province', 
          'label'     =>  $helper->__('State or Province Name'),
          'value'     =>  $key_details->ST,
           'class' => 'validate-alpha',
          'after_element_html' => '<br />Set State or Province Name for the Key. generally name of the state or province where owner\'s organization is situated.',
          
        ));
        $mofluid_country_field =  $mofluid_certificates_fields->addField('release_key_data_country', 'text', array(
          'name'      => 'release_key_data_country',  
          'label'     =>  $helper->__('Country Code (Max 2 Characters)'),
          'value'     =>  $key_details->C,
           'class' => 'validate-alpha validate-length minimum-length-2 maximum-length-2'
        ));
        $mofluid_country_field->setAfterElementHtml("
           <br />Set Country Code in 2 characters where owner\'s organization is situated like US for United States, IN for India, UK for United Kingdom etc.
		 <script type=\"text/javascript\">
		     document.getElementById('signing_key_type').onchange();
		     //toggleSigningKeyFields('0');
		     function toggleSigningKeyFields(selectElement){ 
			    if(selectElement == '0') {
			        //Apply for new key
				   document.getElementById('mofluid_certificates').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_validity').parentNode.parentNode.style.display= 'table-row';
				  document.getElementById('release_key_data_commonname').parentNode.parentNode.style.display= 'table-row';
				   document.getElementById('release_key_data_orgname').parentNode.parentNode.style.display= 'table-row';
				    document.getElementById('release_key_data_orgunit').parentNode.parentNode.style.display= 'table-row';
				   document.getElementById('release_key_data_locality').parentNode.parentNode.style.display= 'table-row';
				   document.getElementById('release_key_data_province').parentNode.parentNode.style.display= 'table-row';
				   document.getElementById('release_key_data_country').parentNode.parentNode.style.display= 'table-row';
			    }
			    else {
			        //already have key
				   document.getElementById('mofluid_certificates').parentNode.parentNode.style.display= 'table-row';
				   document.getElementById('release_key_validity').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_data_commonname').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_data_orgname').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_data_orgunit').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_data_locality').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_data_province').parentNode.parentNode.style.display= 'none';
				   document.getElementById('release_key_data_country').parentNode.parentNode.style.display= 'none';
			    }
			}
		 </script>");     
        if (Mage::registry('mofluid_buildandroid')) {
            $form->setValues(Mage::registry('mofluid_buildandroid')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();      
    }

}
