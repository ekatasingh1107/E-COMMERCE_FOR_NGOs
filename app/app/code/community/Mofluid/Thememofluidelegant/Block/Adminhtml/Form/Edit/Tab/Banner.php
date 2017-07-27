<?php
class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Tab_Banner extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form in tab
     */
    protected function _prepareForm() 
    {
	    $helper = Mage::helper('mofluid_thememofluidelegant');
	    $form = new Varien_Data_Form();
	    $form->setHtmlIdPrefix('');
	    $form->setFieldNameSuffix('mofluidtheme_logobanner');
	    $mofluid_elegant_config_model_settings = Mage::getModel('mofluid_thememofluidelegant/thememofluidelegant')->getCollection()->addFieldToFilter('mofluid_theme_id','1')->addFieldToFilter('mofluid_theme_code','elegant')->getData();
	    $mofluid_theme_banner_image_type = $mofluid_elegant_config_model_settings[0]['mofluid_theme_banner_image_type'];
	    $mofluid_theme_elegant_model = Mage::getModel('mofluid_thememofluidelegant/images');
	    $mofluid_theme_elegant_banner = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_image_type','banner');
	    $mofluid_theme_elegant_banner_data = $mofluid_theme_elegant_banner->getData(); 
	    //creating banner fieldset tab
	    $themebanner_fieldset = $form->addFieldset('themebanner_configure', array(
		   'legend'       => $helper->__('Configure Banner'),
		   'class'        => 'fieldset-wide'
	    ));
	    $themebanner_add_fieldset = $form->addFieldset('themebanner_add', array(
		   'legend'       => $helper->__('Add New Banner Image'),
		   'class'        => 'fieldset-wide'
	    ));
	    $themebanner_add_fieldset->addType('custombutton', 'Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Renderer_Custombutton');
	    $themebanner_fieldset->addType('image', 'Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Renderer_Image');
	    //$themebanner_fieldset->addType('image', Mage::getConfig()->getBlockClassName('Mofluid_Thememofluidelegant/Adminhtml_Form_Edit_Renderer_Image'));
	    $themebanner_fieldset->addField('mofluid_theme_banner_image_type', 'select', array(
		   'label'     => $helper->__('Banner Style'),
		   'name'      => 'mofluid_theme_banner_image_type',
		   'required'  => true,
		   'value'     => $mofluid_theme_banner_image_type,
		   'after_element_html' => '<br>Select your banner style either Single or Slider.',
		   'values'    => array(
			  array(
				 'value'     => 0,
				 'label'     => $helper->__('Single'),
			  ),
			 array(
				'value'     => 1,
				'label'     => $helper->__('Slider'),
			 ),
		  ),
	   ));
	   $mofluid_all_stores = Mage::app()->getStores();
	   $mofluid_all_stores_result = array(); 
	   $mofluid_all_stores_result[0] = array('label'=>'All Store', 'value'=>'0');
	   foreach ($mofluid_all_stores as $_eachStoreId => $val) 
	   {	
		   $_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
		   $_storeName = Mage::app()->getStore($_eachStoreId)->getName();
		   $_storeId = Mage::app()->getStore($_eachStoreId)->getId();
		   $mofluid_all_stores_result[] = array(
			  'label' => Mage::app()->getStore($_eachStoreId)->getName(),
			  'value' => Mage::app()->getStore($_eachStoreId)->getId()
		   );
	   }
	   $themebanner_add_fieldset->addField('mofluid_themeelegent_banner_image', 'image', array(
		'label' => $helper->__('Choose Banner'),
		'name' => 'mofluid_themeelegent_banner_image',
		'value' => '',
		'after_element_html' => '<br>Upload Image to display as banner image in app (<b>Recommended Size : 1024x500px</b>).'
	 ));
	 $themebanner_add_fieldset->addField('mofluid_theme_banner_isdefault', 'select', array(
		 'label'     => $helper->__('Default'),
		 'required'  => false,
		 'name'      => 'mofluid_theme_banner_isdefault',
		 'after_element_html' => '<br>When selecting banner style as single, default banner will display for that store.',
		 'values'    => array(
			 array(
				  'value'     => '0',
				 'label'     => 'No',      
			 ),
			 array(
				  'value'     => '1',
				 'label'     => 'Yes',      
			 ),
		 )
	  ));
	   $themebanner_add_fieldset->addField('mofluid_theme_banner_store', 'select', array(
		  'label'     => $helper->__('Store'),
		  'name'      => 'mofluid_theme_banner_store',
		  'required'  => true,
		  'values'    => $mofluid_all_stores_result,
		   'after_element_html' => '<br>Select store to display uploaded banner image.',
	   ));
	   $themebanner_add_fieldset->addField('mofluid_theme_banner_action', 'select', array(
		 'label'     => $helper->__('Action for Banner'),
		 'required'  => false,
		 'onchange' => 'checkSelectedItem(this.value)',
		 'name'      => 'mofluid_theme_banner_action',
		 'values'    => array(
			 array(
				  'value'     => '0',
				 'label'     => 'No Action',      
			 ),
			 array(
				  'value'     => '1',
				 'label'     => 'Open Category',      
			 ),
			 array(
				 'value'     => '2',
				 'label'     => 'Open Product',         
			 ),
		 ),
		  'after_element_html' => '<br>Enable Action to link banner with a category or product'
	  ));
	 $total_products = array();           
	 $total_product_collection = Mage::getResourceModel('catalog/product_collection')->joinField('is_in_stock','cataloginventory/stock_item','is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')
											 ->addAttributeToSelect('*')
											 ->addStoreFilter(1)
											 ->addAttributeToFilter('visibility', 4)
											 ->addFieldToFilter('status', 1)
											 ->addAttributeToFilter('type_id', array('in' => array(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE, Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)))
											 ->addAttributeToFilter('is_in_stock', array('in' => array($is_in_stock_option, 1)));
	 foreach ($total_product_collection as $current_product) {
		 $total_products[] = array(
			 'label' => $current_product->getName(). ' (Id : '.$current_product->getId().')',
			 'value' => $current_product->getId(),
		 );
	 }
	 $themebanner_add_fieldset->addField('mofluid_theme_banner_action_product', 'select', array(
		 'label'     => $helper->__('Product'),
		 'required'  => false,
		 'name'      => 'mofluid_theme_banner_action_product',
		 'values'    => $total_products
	 ));
	 $categories = Mage::getModel('catalog/category')
						 ->getCollection()
						 ->addAttributeToSelect('*')
						 ->addIsActiveFilter();
	 foreach ($categories as $current_category) {
		 $total_categories[] = array(
			 'label' => $current_category->getName(). ' (Id : '.$current_category->getId().')',
			 'value' => $current_category->getId(),
		 );
	 }
	 $themebanner_add_fieldset_obj = $themebanner_add_fieldset->addField('mofluid_theme_banner_action_category', 'select', array(
		'label'     => $helper->__('Category'),
		'required'  => false,
		'name'      => 'mofluid_theme_banner_action_category',
		'values'    => $total_categories
	 ));  
	 $themebanner_add_fieldset_obj->setAfterElementHtml("
		 <script type=\"text/javascript\">
			document.getElementById('mofluid_theme_banner_action_category').parentNode.parentNode.style.display= 'none';
			document.getElementById('mofluid_theme_banner_action_product').parentNode.parentNode.style.display= 'none';
			function checkSelectedItem(selectElement){ 
			    if(selectElement == '1') {
				   document.getElementById('mofluid_theme_banner_action_category').parentNode.parentNode.style.display= 'table-row';
				   document.getElementById('mofluid_theme_banner_action_product').parentNode.parentNode.style.display= 'none';
			    }
			    else if(selectElement == '2'){
				   document.getElementById('mofluid_theme_banner_action_category').parentNode.parentNode.style.display= 'none';
				   document.getElementById('mofluid_theme_banner_action_product').parentNode.parentNode.style.display= 'table-row';
			    }
			    else {
			       document.getElementById('mofluid_theme_banner_action_category').parentNode.parentNode.style.display= 'none';
				  document.getElementById('mofluid_theme_banner_action_product').parentNode.parentNode.style.display= 'none';
			    }
			}
		 </script>");     
	 $themebanner_add_fieldset->addField('mofluid_themeelegent_banner_sort_order', 'text', array(
		'label' => $helper->__('Sort Order'),
		'name' => 'mofluid_themeelegent_banner_sort_order',
		'class' => 'validate-number validate-greater-than-zero',
		'style' =>'width:100px !important',
		'value' => '',
		'onclick' => ''
	 ));
	 $themebanner_add_fieldset->addField('mofluid_themeelegent_banner_isbanneradd', 'hidden', array(
		'label' => $helper->__(''),
		'name' => 'mofluid_themeelegent_banner_isbanneradd',
		'style' =>'width:100px !important',
		'value' => '0',
		'onclick' => ''
	 ));
	 $themebanner_add_fieldset->addField('mofluid_themeelegent_banner_add_new', 'custombutton', array(
		'label' => $helper->__(''),
		'title' => $helper->__('Add Banner Image'),
		'name' => 'mofluid_themeelegent_banner_add_new',
		'onclick' => 'addbutton.add(this)',
		'value' => 'Add Banner Image'
	 ));
	 if (Mage::registry('mofluid_thememofluidelegant')) {
		 $form->setValues(Mage::registry('mofluid_thememofluidelegant')->getData());
	 }
	 $this->setForm($form);
	 return parent::_prepareForm();
   }
}
