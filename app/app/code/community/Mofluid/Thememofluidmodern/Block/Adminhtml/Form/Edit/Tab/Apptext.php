<?php

class Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Tab_Apptext extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
    
        
        $helper = Mage::helper('mofluid_thememofluidmodern');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluidtheme_text');
     
        $mofluid_theme_modern_model = Mage::getModel('mofluid_thememofluidmodern/messages');
        $mofluid_theme_modern = $mofluid_theme_modern_model->getCollection()->addFieldToFilter('mofluid_theme_id','2')->addFieldToFilter('mofluid_message_type','text');
        $mofluid_theme_modern_data = $mofluid_theme_modern->getData(); 
        
        //creating app text fieldset tab
        $apptext_fieldset = $form->addFieldset('apptext', array(
            'legend'       => $helper->__('Application Text'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
       
        foreach($mofluid_theme_modern_data as $key=>$value) {
            $field_id = strtolower(str_replace(' ', '_', trim($value["mofluid_message_type"].'_'.$value["mofluid_message_id"])));
            $help_text = $value["mofluid_message_helptext"];
            if($value["mofluid_message_helplink"]) {
                $help_text .=  ' For More Detail : <a href="'.$value["mofluid_message_helplink"].'" target="_blank">Click Here</a>';
            }
            $apptext_fieldset->addField($field_id, 'text', array(
                'name'  => $field_id,
                'label' => $value["mofluid_message_label"],
                'value' => $value["mofluid_message_value"],
                'after_element_html' => $help_text,
                'required' => $value["mofluid_message_isrequired"] ? true : false,
            ));
        }
      
      
        if (Mage::registry('mofluid_thememofluidmodern')) {
            $form->setValues(Mage::registry('mofluid_thememofluidmodern')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();   
    }

}
