<?php

class Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Renderer_Store
extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * renderer
     *
     * @param Varien_Data_Form_Element_Abstract $element
     */
    public function render(Varien_Object $row)
    {
        $mofluid_custom_column_value = '';
        $column_id = $this->getColumn()->getId();
        if($column_id == 'mofluid_theme_banner_image_store') {
        	   $column_value =  $row->getData($this->getColumn()->getIndex());
             if($column_value == null || $column_value == '' || $column_value == 0 || $column_value == 'Admin') {
                 $mofluid_store_name = "All Stores";
             }
        	   else {
        		  $mofluid_store_name = Mage::app()->getStore($column_value)->getName();
        	   }
        	   $mofluid_custom_column_value = $mofluid_store_name;
        }
        else if($column_id == 'mofluid_theme_banner_image_isdefault') {
        	   $column_value =  $row->getData($this->getColumn()->getIndex());
             if($column_value == '1' || $column_value == 1 ) {
                 $mofluid_banner_isdefault = "Yes";
             }
        	   else {
        		 $mofluid_banner_isdefault = 'No';
        	   }
        	   $mofluid_custom_column_value = $mofluid_banner_isdefault;
        }
        else if($column_id == 'mofluid_theme_banner_image_url') {
            $column_value =  $row->getData($this->getColumn()->getIndex());
             if($column_value == '') {
                 $mofluid_banner_url_image = "No Image Found";
             }
        	   else {
        		 $mofluid_banner_url_image = '<div id="mofluid_banner"><div id="mofluid_banner_thumbmail" style="float: left;padding: 0 10px;">
        		 <img src="'.$column_value.'" width=50px height=50px /></div><div id="mofluid_banner_description" style="float:left">'.basename($column_value).' <a href='.$column_value.' target="_blank">Preview </a></div></div>';
        	   }
        	   $mofluid_custom_column_value = $mofluid_banner_url_image;
        }
         else if($column_id == 'mofluid_image_action') {
            $column_value =  $row->getData($this->getColumn()->getIndex());
             if($column_value == '') {
                 $mofluid_banner_action = "No Action";
                 return $mofluid_banner_action;
             }
        	   else {
        	      try {
        	      	$mofluid_banner_action_data = json_decode(base64_decode($column_value));
        	      	if($mofluid_banner_action_data->base == "product") {
        	      		$_product = Mage::getModel('catalog/product')->load($mofluid_banner_action_data->id);
        	      		$target_action_name = $_product->getName().' ('.$_product->getSku().')';
        	      	}
        	      	else {
        	      	     $_category = Mage::getModel('catalog/category')->load($mofluid_banner_action_data->id);
        	      		$target_action_name = $_category->getName();
        	      	}
        	          $mofluid_banner_action = ucfirst($mofluid_banner_action_data->action).'  '.ucfirst($mofluid_banner_action_data->base).' "'.trim($target_action_name).'"';
        	      }
        	      catch(Exception $ex) {
        	      	$mofluid_banner_action = "No Action";
        	      	return $mofluid_banner_action;
        	      }
        		 $mofluid_banner_url_image = $mofluid_banner_action;
        	   }
        	   $mofluid_custom_column_value = $mofluid_banner_url_image;
        }
        return $mofluid_custom_column_value;
    }
}