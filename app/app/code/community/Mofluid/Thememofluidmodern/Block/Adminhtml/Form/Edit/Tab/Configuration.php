<?php

class Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Tab_Configuration extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
        $helper = Mage::helper('mofluid_thememofluidmodern');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluidtheme_config');
        //Cms Pages 
        $cmspage = Mage::getModel('cms/page')->getCollection();
        $cmspage_array[]=array("label"=>"Select Page", "value"=>"0");
        foreach($cmspage as $tdata)
        {
        	$cmspage_array[]=array("label"=>$tdata->getTitle()." (Id:".$tdata->getId().")", "value"=>$tdata->getId());
        }
		
        $mofluid_theme_modern_model = Mage::getModel('mofluid_thememofluidmodern/thememofluidmodern');
        $mofluid_theme_modern = $mofluid_theme_modern_model->getCollection()->addFieldToFilter('mofluid_theme_id','2');
        $mofluid_theme_modern_data = $mofluid_theme_modern->getData(); 
        
        $modern_theme_settings = $mofluid_theme_modern_data[0]; 
        $configuration_fieldset = $form->addFieldset('configuration', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
        
        $configuration_fieldset->addField('mofluid_theme_id', 'hidden', array(
          'name'      => 'mofluid_theme_id',
          'value'     => $modern_theme_settings['mofluid_theme_id'],
        ));
        $configuration_fieldset->addField('google_ios_clientid', 'text', array(
          'name'      => 'google_ios_clientid',
          'label'     => 'Google Client ID',
          'required'  => false,
          'value'     =>  $modern_theme_settings["google_ios_clientid"],
          'after_element_html' => '<br />Please enter here Google client id generated for iOS app. For more Details <a href="https://developers.google.com/+/mobile/ios/samples/quickstart-ios" target="_blank">Click here</a>.<br>
          For android app please follow this link <a href="https://developers.google.com/+/mobile/android/samples/quickstart-android" target="_blank">Click here</a>.',
          
        ));
        $configuration_fieldset->addField('google_login', 'select', array(
          'name'      => 'google_login',
          'label'     =>  $helper->__('Login With Google'),
          'value'     =>  $modern_theme_settings['google_login'],
          'required'  => false,
          'values'    => array(
          					array("label"=>"Disable", "value"=>"0"),
          					array("label"=>"Enable", "value"=>"1")
          				 ),
          'after_element_html' => '',
          
        ));
         $configuration_fieldset->addField('tax_flag', 'select', array(
          'label'     => $helper->__('Show text of tax details with the product price in the product details page'),
          'name'      => 'tax_flag',
          'required'  => true,
          'value'     => $modern_theme_settings['tax_flag'],
          'after_element_html' => '<br>Enable if you want to display tax text on product details page.',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => $helper->__('No'),
              ),

              array(
                  'value'     => 1,
                  'label'     => $helper->__('Yes'),
              ),
          ),
       ));
        $configuration_fieldset->addField('mofluid_theme_catsimg', 'select', array(
          'label'     => $helper->__('Display Category Images'),
          'name'      => 'mofluid_theme_catsimg',
          'required'  => true,
          'value'     => $modern_theme_settings['mofluid_display_catsimg'],
          'after_element_html' => '<br>Enable if you want to display category thumbnail images on listing.For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => $helper->__('Disable'),
              ),

              array(
                  'value'     => 1,
                  'label'     => $helper->__('Enable'),
              ),
          ),
       ));
        $configuration_fieldset->addField('mofluid_theme_display_custom_attribute', 'select', array(
          'label'     => $helper->__('Product Custom Attribute'),
          'name'      => 'mofluid_theme_display_custom_attribute',
          'required'  => true,
          'value'     => $modern_theme_settings['mofluid_theme_display_custom_attribute'],
          'after_element_html' => '<br>Select "Show" if you want to display all the custom attribute associated with product at product description page. ',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => $helper->__('Hide'),
              ),

              array(
                  'value'     => 1,
                  'label'     => $helper->__('Show'),
              ),
          ),
       ));
       
        $configuration_fieldset->addField('version_code', 'text', array(
          'name'      => 'version_code',
          'label'     => 'Version Code',
          'required'  => false,
          'value'     =>  $modern_theme_settings["version_code"],
          'after_element_html' => 'It is a unique code and is needed to publish application.',
          
        ));
       
/*       $configuration_fieldset->addField('mofluid_theme_custom_footer', 'editor', array(
	       'label' => $helper->__('Custom Footer'),
	       'title' => $helper->__('Custom Footer'),
	       'name' => 'mofluid_theme_custom_footer',
	       'wysiwyg' => true,// enable WYSIWYG editor
	       'after_element_html' => '<br>Leave blank for default footer.',
           'value' => base64_decode($modern_theme_settings['mofluid_theme_custom_footer']) 
       ));
		/*  CMS Pages Blocks */
		 $configuration_cmspages = $form->addFieldset('cmspages', array(
            'legend'       => $helper->__('CMS Pages'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
		
		 $configuration_cmspages->addField('cms_pages', 'select', array(
          'name'      => 'cms_pages',
          'label'     =>  $helper->__('CMS Pages'),
          'value'     =>  $modern_theme_settings['cms_pages'],
          'required'  => true,
          'values'    => array(
          					array("label"=>"Disable", "value"=>"0"),
          					array("label"=>"Enable", "value"=>"1")
          				 ),
          'after_element_html' => '',
          
        ));
		
		$configuration_cmspages->addField('about_us', 'select', array(
          'name'      => 'about_us',
          'label' => $helper->__('About Us Page Id'),
          'value'     =>  $modern_theme_settings['about_us'],
          'required'  => false,
          'values'    => $cmspage_array,
          'after_element_html' => '',
          
        ));
		
		
	
	 $configuration_cmspages->addField('term_condition', 'select', array(
		'label' => $helper->__('Term Condition Page Id'),
		'name' => 'term_condition',
		'class' => '',
		'value' => $modern_theme_settings["term_condition"],
		'required'  => false,
        'values'    => $cmspage_array,
		'after_element_html' => '',
	 ));
	 $configuration_cmspages->addField('privacy_policy', 'select', array(
		'label' => $helper->__('Privacy Policy Page Id'),
		'name' => 'privacy_policy',
		'class' => '',
		'value' => $modern_theme_settings["privacy_policy"],
		'required'  => false,
        'values'    => $cmspage_array,
		'after_element_html' => '',
	 ));
	 $configuration_cmspages->addField('return_privacy_policy', 'select', array(
		'label' => $helper->__('Return Privacy Policy Page Id'),
		'name' => 'return_privacy_policy',
		'class' => '',
		'value' => $modern_theme_settings["return_privacy_policy"],
		'required'  => false,
        'values'    => $cmspage_array,
		'after_element_html' => '',
	 ));
	 
	 	/*  End CMS Pages Blocks */
		
		
       
      
       if (Mage::registry('mofluid_thememofluidmodern')) {
            $form->setValues(Mage::registry('mofluid_thememofluidmodern')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();      
    }

}
