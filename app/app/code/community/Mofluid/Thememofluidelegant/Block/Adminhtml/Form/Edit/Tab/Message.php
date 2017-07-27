<?php

class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Tab_Message extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
        $helper = Mage::helper('mofluid_thememofluidelegant');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluidtheme_alert');
     
     
     
      $mofluid_theme_elegant_model = Mage::getModel('mofluid_thememofluidelegant/messages');
        $mofluid_theme_elegant = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_theme_id','1')->addFieldToFilter('mofluid_message_type','alert');
        $mofluid_theme_elegant_data = $mofluid_theme_elegant->getData();        
        
        //creating button text tab
        $appmessage_fieldset = $form->addFieldset('appmessage', array(
            'legend'       => $helper->__('Warning/Alert Messages'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
           
         foreach($mofluid_theme_elegant_data as $key=>$value) {
            $field_id = strtolower(str_replace(' ', '_', trim($value["mofluid_message_type"].'_'.$value["mofluid_message_id"])));
            $help_text = $value["mofluid_message_helptext"];
            if($value["mofluid_message_helplink"]) {
                $help_text .=  ' For More Detail : <a href="'.$value["mofluid_message_helplink"].'" target="_blank">Click Here</a>';
            }
            $appmessage_fieldset->addField($field_id, 'text', array(
                'name'  => $field_id,
                'label' => $value["mofluid_message_label"],
                'value' => $value["mofluid_message_value"],
                'after_element_html' => $help_text,
                'required' => $value["mofluid_message_isrequired"] ? true : false,
            ));
        }
        
        if (Mage::registry('mofluid_thememofluidelegant')) {
            $form->setValues(Mage::registry('mofluid_thememofluidelegant')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();    

    }

}
