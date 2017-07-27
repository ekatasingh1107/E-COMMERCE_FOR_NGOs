<?php
/**
 * File path:
 * magento_root/app/code/local/Mofluid/Thememofluidelegant/Block/Adminhtml/Entityname/Edit/Form/Renderer/Fieldset/Customtype.php
 */
class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Renderer_Custombutton 
      extends Varien_Data_Form_Element_Abstract
{
 protected $_element;
 
 public function getElementHtml()
 {
 /*
  * You can do all necessary customisations here
  *
  * You can use parent::getElementHtml() to get original markup
  * if you are basing on some other type and if it is required
  *
  * Use $this->getData('desired_data_key') to extract the desired data
  * E.g. $this->getValue() or $this->getData('value') will return form elements value
  */
  
  //$redirect = '*/*/success';
  //$this->setFormAction(Mage::getUrl('*/*/success'));
  // return '<button id='.$this->getId().' class="" onclick="setLocation(\''.Mage::helper('adminhtml')->getUrl('*/*/success').'\');"><span>'.$this->getValue().'</span></button>';
        $value = $this->getTitle();
        $onclick=$this->getOnclick();
        $class=$this->getClass();
        $id=$this->getId();
        $style=$this->getStyle();
        $type=$this->getType();
        $html='<button id="'.$id.'" class="'.$class.'" style="'.$style.'" onclick="'.$onclick.'" type="'.$type.'" title="'.$value.'"><span>'.$value.' </span></button>';
        $html .= '<p id="' . $this->getHtmlId() . '"'. $this->serialize($this->getHtmlAttributes()) .'>
                <script type="text/javascript">
                //<![CDATA[                        
                    var addbutton =  
                    {                                                      
                         add : function(obj) {  
                             document.getElementById("mofluid_themeelegent_banner_isbanneradd").value = "1";
                             return false;
                         },                            
                    };
                //]]>
                </script>
            </p>';             
    return $html;
 
 
 }
 
}
