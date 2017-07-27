<?php

class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Tab_Themeicons extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
       
        $helper = Mage::helper('mofluid_thememofluidelegant');
         $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluidtheme_themeicons');
        
        $mofluid_theme_elegant_model = Mage::getModel('mofluid_thememofluidelegant/images');
        $mofluid_theme_elegant = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_theme_id','1')->addFieldToFilter('mofluid_image_type','themeicons');
        $mofluid_theme_elegant_data = $mofluid_theme_elegant->getData(); 
        
        //creating forground fieldset tab
        $themeicons_fieldset = $form->addFieldset('themeicons', array(
            'legend'       => $helper->__('Theme Icons/Images'),
            'class'        => 'fieldset-wide'
        ));
         foreach($mofluid_theme_elegant_data as $key=>$value) {
            $field_id = strtolower(str_replace(' ', '_', trim($value["mofluid_image_type"].'_'.$value["mofluid_image_id"])));
            $help_text = $value["mofluid_image_helptext"];
            if($value["mofluid_image_helplink"]) {
                $help_text .=  ' For More Detail : <a href="'.$value["mofluid_image_helplink"].'" target="_blank">Click Here</a>';
            }
            $themeicons_fieldset->addField($field_id, 'image', array(
                'name'  => $field_id,
                'label' => $value["mofluid_image_label"],
                'value' => $value["mofluid_image_value"],
                'after_element_html' => $help_text,
                'required' => $value["mofluid_image_isrequired"] ? true : false
            ));
        }
      
     if (Mage::registry('mofluid_thememofluidelegant')) {
            $form->setValues(Mage::registry('mofluid_thememofluidelegant')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
       