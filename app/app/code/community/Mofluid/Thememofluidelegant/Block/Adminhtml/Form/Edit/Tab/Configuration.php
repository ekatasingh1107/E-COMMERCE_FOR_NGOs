<?php

class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Tab_Configuration extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
        $helper = Mage::helper('mofluid_thememofluidelegant');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluidtheme_config');

        $mofluid_theme_elegant_model = Mage::getModel('mofluid_thememofluidelegant/thememofluidelegant');
        $mofluid_theme_elegant = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_theme_id','1');
        $mofluid_theme_elegant_data = $mofluid_theme_elegant->getData(); 
        
        $elegant_theme_settings = $mofluid_theme_elegant_data[0]; 
        $configuration_fieldset = $form->addFieldset('configuration', array(
            'legend'       => $helper->__('Configuration'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
        
        $configuration_fieldset->addField('mofluid_theme_id', 'hidden', array(
          'name'      => 'mofluid_theme_id',
          'value'     => $elegant_theme_settings['mofluid_theme_id'],
        ));
       
       
       $configuration_fieldset->addField('google_ios_clientid', 'text', array(
          'name'      => 'google_ios_clientid',
          'label'     => 'Google Client ID',
          'required'  => true,
          'value'     => $elegant_theme_settings["google_ios_clientid"],
          'after_element_html' => '<br />Google Client key id for google login',
          
        ));
        $configuration_fieldset->addField('google_login', 'select', array(
          'name'      => 'google_login',
          'label'     =>  $helper->__('Login With Google'),
          'value'     =>  $elegant_theme_settings['google_login'],
          'required'  => true,
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
          'value'     => $elegant_theme_settings['tax_flag'],
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
          'value'     => $elegant_theme_settings['mofluid_display_catsimg'],
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
          'value'     => $elegant_theme_settings['mofluid_theme_display_custom_attribute'],
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
       $configuration_fieldset->addField('mofluid_theme_custom_footer', 'editor', array(
	       'label' => $helper->__('Custom Footer'),
	       'title' => $helper->__('Custom Footer'),
	       'name' => 'mofluid_theme_custom_footer',
	       'wysiwyg' => true,// enable WYSIWYG editor
	       'after_element_html' => '<br>Leave blank for default footer.',
           'value' => base64_decode($elegant_theme_settings['mofluid_theme_custom_footer']) 
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
          'value'     =>  $elegant_theme_settings['cms_pages'],
          'required'  => true,
          'values'    => array(
          					array("label"=>"Disable", "value"=>"0"),
          					array("label"=>"Enable", "value"=>"1")
          				 ),
          'after_element_html' => '',
          
        ));
		
		
		$configuration_cmspages->addField('about_us', 'text', array(
		'label' => $helper->__('About Us Page Id'),
		'name' => 'about_us',
		'class' => '',
		'style' =>'width:100px !important',
		'value' => $elegant_theme_settings["about_us"],
		'onclick' => ''
	 ));
	 $configuration_cmspages->addField('term_condition', 'text', array(
		'label' => $helper->__('Term Condition Page Id'),
		'name' => 'term_condition',
		'class' => '',
		'style' =>'width:100px !important',
		'value' => $elegant_theme_settings["term_condition"],
		'onclick' => ''
	 ));
	 $configuration_cmspages->addField('privacy_policy', 'text', array(
		'label' => $helper->__('Privacy Policy Page Id'),
		'name' => 'privacy_policy',
		'class' => '',
		'style' =>'width:100px !important',
		'value' => $elegant_theme_settings["privacy_policy"],
		'onclick' => ''
	 ));
	 $configuration_cmspages->addField('return_privacy_policy', 'text', array(
		'label' => $helper->__('Return Privacy Policy Page Id'),
		'name' => 'return_privacy_policy',
		'class' => '',
		'style' =>'width:100px !important',
		'value' => $elegant_theme_settings["return_privacy_policy"],
		'onclick' => ''
	 ));
	 
	 	/*  End CMS Pages Blocks */
      
       if (Mage::registry('mofluid_thememofluidelegant')) {
            $form->setValues(Mage::registry('mofluid_thememofluidelegant')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();      
    }

}
