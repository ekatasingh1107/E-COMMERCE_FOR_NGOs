<?php

class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Renderer_Image extends Varien_Data_Form_Element_Image
{

    /**
     * Retrieve Element HTML fragment
     *
     * @return string
     */
    public function getElementHtml()
    {
        return array_merge(parent::getHtmlAttributes(), array('multiple'));
    }
    public function render(Varien_Object $row){
        if($row->getData($this->getColumn()->getIndex())==""){ 
        		return "";
        }
        else{ 
            $html = '<img '; $html .= 'id="' . $this->getColumn()->getId() . '" '; 
            $html .= 'width="80" '; $html .= 'height="80" '; $html .= 'src="' . Mage::getBaseUrl("media") .'finder/stre_locator/' . $row->getData($this->getColumn()->getIndex()) . '"'; 
            $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss() . '"/>'; 
       } 
       return $html; 
  }
       
}