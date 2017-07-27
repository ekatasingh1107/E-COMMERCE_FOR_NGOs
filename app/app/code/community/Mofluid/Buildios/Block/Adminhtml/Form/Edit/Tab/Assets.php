<?php

class Mofluid_Buildios_Block_Adminhtml_Form_Edit_Tab_Assets extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
       
        $helper = Mage::helper('mofluid_buildios');
        $form = new Varien_Data_Form();
        //$form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluid_build_ios_assets');
        
        $mofluid_buildios_assets_data = Mage::getModel('mofluid_buildios/assets')
        										->getCollection()
       											->addFieldToFilter('mofluid_admin_id',1)
        										->addFieldToFilter('mofluid_platform','ios')
        										->getData(); 
        $buildios_assets_notes_fields = $form->addFieldset('buildios_assets_notes', array(
            'legend'       => $helper->__('Notes'),
            'class'        => 'fieldset-wide'
        ));
     
       $buildios_assets_icons_fields = $form->addFieldset('buildios_assets_icons', array(
            'legend'       => $helper->__('Icons'),
            'class'        => 'fieldset-wide'
        ));
        $buildios_assets_splash_fields = $form->addFieldset('buildios_assets_splash', array(
            'legend'       => $helper->__('Splash Screens'),
            'class'        => 'fieldset-wide'
        ));
        $buildios_assets_other_fields = $form->addFieldset('buildios_assets_other', array(
            'legend'       => $helper->__('Others'),
            'class'        => 'fieldset-wide'
        ));
        $buildios_assets_notes_fields->addField('note_image', 'note', array(
           'label'    => $helper->__('Note : '),
           'text'     => $helper->__('Please make sure you have allowed <b>"max_file_uploads"</b> 25 or greater in your <b>"php.ini"</b><br/>'),
        )); 
        $buildios_assets_notes_fields->addField('note_logo', 'note', array(
           'label'    => $helper->__('Logo/Banner : '),
           'text'     => $helper->__('Upload logo/banner for the mobile app in the <b>“Theme Configuration”</b> section.'),
        )); 
        $buildios_assets_notes_fields->addField('note_assets_recommendation', 'note', array(
           'label'    => $helper->__('Transparency Recommendation : '),
           'text'     => $helper->__('Please don\'t use transparent background for the splash screens.'),
        )); 
        
        
        foreach($mofluid_buildios_assets_data as $key=>$value) {
            $asset_type = strtolower(str_replace(' ', '', $value["mofluid_assets_type"]));
            $field_id = strtolower(str_replace(' ', '_', trim($value["mofluid_assets_type"].'_'.$value["mofluid_assets_id"])));
            $help_text = $value["mofluid_assets_heptext"];
            
            if($asset_type == "icon") {
                $buildios_assets_icons_fields->addField($field_id, 'image', array(
            		'name'  => $field_id,
            		'label' => $value["mofluid_assets_name"],
            		'value' => $value["mofluid_assets_value"],
            		'after_element_html' => $help_text,
            		'required' => $value["mofluid_assets_isrequired"] ? true : false
        		));
            }
            else if($asset_type == "splash") {
                $buildios_assets_splash_fields->addField($field_id, 'image', array(
            		'name'  => $field_id,
            		'label' => $value["mofluid_assets_name"],
            		'value' => $value["mofluid_assets_value"],
            		'after_element_html' => $help_text,
            		'required' => $value["mofluid_assets_isrequired"] ? true : false
        		));
            }
            else  {
                $buildios_assets_other_fields->addField($field_id, 'image', array(
            		'name'  => $field_id,
            		'label' => $value["mofluid_assets_name"],
            		'value' => $value["mofluid_assets_value"],
            		'after_element_html' => $help_text,
            		'required' => $value["mofluid_assets_isrequired"] ? true : false
        		));
            }
         }
      
        if (Mage::registry('mofluid_buildios')) {
            $form->setValues(Mage::registry('mofluid_buildios')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
       
