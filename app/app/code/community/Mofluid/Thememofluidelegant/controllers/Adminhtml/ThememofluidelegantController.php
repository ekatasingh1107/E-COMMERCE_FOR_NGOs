<?php

class Mofluid_Thememofluidelegant_Adminhtml_ThememofluidelegantController extends Mage_Adminhtml_Controller_Action
{


    /**
     * View form action
     */
    public function indexAction()
    {
        $this->_registryObject();
        $this->loadLayout();
        $this->_setActiveMenu('mofluid/form');
        $this->_addBreadcrumb(Mage::helper('mofluid_thememofluidelegant')->__('Form'), Mage::helper('mofluid_thememofluidelegant')->__('Form'));
        $this->getLayout()->getBlock('head')
             ->setCanLoadExtJs(true)
             ->setCanLoadTinyMce(true)
             ->addItem('js','tiny_mce/tiny_mce.js')
             ->addItem('js','mage/adminhtml/wysiwyg/tiny_mce/setup.js')
             ->addJs('mage/adminhtml/browser.js')
             ->addJs('prototype/window.js')
             ->addJs('lib/flex.js')
             ->addJs('mage/adminhtml/flexuploader.js')
             ->addItem('js_css','prototype/windows/themes/default.css')
             ->addItem('js_css','prototype/windows/themes/magento.css');
       $this->renderLayout();
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function gridAction()
    {
        $this->_registryObject();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mofluid_thememofluidelegant/adminhtml_form_edit_tab_product')
                ->toHtml()
        );
    }
   public function deleteAction()
   {
       try {
       	$delete_banner_id = $this->getRequest()->getParam('id'); 
       	$model = Mage::getModel('mofluid_thememofluidelegant/images');
       	$model->setId($delete_banner_id)->delete();
       	Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_thememofluidelegant')->__('Banner with Id '.$delete_banner_id .' has been deleted.'));
		Mage::getSingleton('adminhtml/session')->setFormData(true);
	  }
	  catch(Exception $exc) {
	   	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_thememofluidelegant')->__($exc->getMessage()));
		Mage::getSingleton('adminhtml/session')->setFormData(true);
	  }
       $this->_redirect('*/*/');
   }
    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function saveAction()
   {
        try {
            $mofluidtheme_logobanner = $this->getRequest()->getParam('mofluidtheme_logobanner');
		  $mofluidtheme_config = $this->getRequest()->getParam('mofluidtheme_config');
		  $mofluidtheme_foreground = $this->getRequest()->getParam('mofluidtheme_foreground');
		  $mofluidtheme_background = $this->getRequest()->getParam('mofluidtheme_background');
		  $mofluidtheme_button = $this->getRequest()->getParam('mofluidtheme_button');
		  $mofluidtheme_text = $this->getRequest()->getParam('mofluidtheme_text');
		  $mofluidtheme_alert = $this->getRequest()->getParam('mofluidtheme_alert');
		  $isBannerRequest = $mofluidtheme_logobanner["mofluid_themeelegent_banner_isbanneradd"];
	   }	
	   catch(Exception $ex) {
	  
	   }
	   if($isBannerRequest) {
	       try {
	       	 $new_mofluid_banner = $_FILES['mofluid_themeelegent_banner_image'];
			 $new_mofluid_banner_action_type = $mofluidtheme_logobanner['mofluid_theme_banner_action'];
			 $new_mofluid_banner_isdefault = $mofluidtheme_logobanner['mofluid_theme_banner_isdefault'];
			 $new_mofluid_banner_product_action = $mofluidtheme_logobanner['mofluid_theme_banner_action_product'];
			 $new_mofluid_banner_category_action = $mofluidtheme_logobanner['mofluid_theme_banner_action_category'];
			 $new_mofluid_banner_sort_order = $mofluidtheme_logobanner['mofluid_themeelegent_banner_sort_order'];
			 $new_mofluid_banner_store = $mofluidtheme_logobanner['mofluid_theme_banner_store']; 
			 if(!$new_mofluid_banner_sort_order) {
			    $new_mofluid_banner_sort_order = 0;	
			 }
			  if(!$new_mofluid_banner_store) {
			    $new_mofluid_banner_store = 0;	
			 }
			  if($new_mofluid_banner_action_type == "2") {
				    $mofluid_new_banner_action_data_base  = "product";
				    $mofluid_new_banner_action_data_base_id = $new_mofluid_banner_product_action;
			  	    $mofluid_new_banner_action_data = json_encode(array("action"=> "open", "base" => $mofluid_new_banner_action_data_base,"id" => $mofluid_new_banner_action_data_base_id));
			  }
			  else if ($new_mofluid_banner_action_type == "1"){
				    $mofluid_new_banner_action_data_base  = "category";
				    $mofluid_new_banner_action_data_base_id = $new_mofluid_banner_category_action;
			         $mofluid_new_banner_action_data = json_encode(array("action"=> "open", "base" => $mofluid_new_banner_action_data_base,"id" => $mofluid_new_banner_action_data_base_id));
			  }
			  else {
			  	    $mofluid_new_banner_action_data = "";
			  }
			  if($new_mofluid_banner_isdefault == 1) {
			      $mofluid_elegant_image_banner_alreadydefault = Mage::getModel('mofluid_thememofluidelegant/images')
			      																	->getCollection()
			      																	->addFieldToFilter('mofluid_theme_id',1)
			      																	->addFieldToFilter('mofluid_store_id', $new_mofluid_banner_store)
			      																	->addFieldToFilter('mofluid_image_isdefault', $new_mofluid_banner_isdefault)
			      																	->getData();  
			      foreach($mofluid_elegant_image_banner_alreadydefault as $current_banner) {
			          $current_banner_id = $current_banner['mofluid_image_id'];
			          $mofluid_elegant_current_banner_model = Mage::getModel('mofluid_thememofluidelegant/images');
					if($mofluid_elegant_current_banner_model != null) {
						  $current_banner_image_data = array("mofluid_image_isdefault" => 0);
						  $mofluid_elegant_current_banner_model->setData($current_banner_image_data)->setId($current_banner_id);
						  $mofluid_elegant_current_banner_model->save();
			          } 
			      }
			  }
			  $mofluid_elegant_image_banner_highest = Mage::getModel('mofluid_thememofluidelegant/images')->getCollection()->setOrder('mofluid_image_id', 'DESC')->getFirstItem()->getData();
			  $mofluid_elegant_image_banner_next_id = intval($mofluid_elegant_image_banner_highest['mofluid_image_id'])+1;
			   if($new_mofluid_banner["name"] != ""){
				    if($new_mofluid_banner["error"] == 0) {
					    //File Upload to Media
					    $file_uploader = new Varien_File_Uploader( array(
						    'name' => $new_mofluid_banner['name'],
						    'type' => $new_mofluid_banner['type'],
						    'tmp_name' => $new_mofluid_banner['tmp_name'],
						    'error' => $new_mofluid_banner['error'],
						    'size' => $new_mofluid_banner['size']
					    ));
					    $mofluid_image_path = Mage::getBaseDir().'/media/mofluid/images';
					    $file_uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); //Allowed extension for file
					    $file_uploader->setAllowCreateFolders(true); //for creating the directory if not exists
					    $file_uploader->setAllowRenameFiles(true); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
					    $file_uploader->setFilesDispersion(false);
					    $file_uploader->save($mofluid_image_path, $new_mofluid_banner['name']);
					    $renamedFileName = $file_uploader->getUploadedFileName(); 	
					    $fileurl = $mofluid_image_path . "/" . $renamedFileName;
					    $exact_path = Mage::getBaseUrl('media').'mofluid/images/'.$renamedFileName;
					    //Update Database
					    $mofluid_elegant_image_model_add = Mage::getModel('mofluid_thememofluidelegant/images');
					    if($mofluid_elegant_image_model_add != null) {
						   $image_data = array(
							  "mofluid_theme_id" => 1,
							  "mofluid_store_id" => $new_mofluid_banner_store,
						  	  "mofluid_image_type" => "banner",
							  "mofluid_image_label" => basename($exact_path),
							  "mofluid_image_value" => $exact_path,
							  "mofluid_image_helptext" => 'None',
							  "mofluid_image_helplink" => 'None',
                                     "mofluid_image_isrequired" => 0,
							  "mofluid_image_sort_order" => $new_mofluid_banner_sort_order,
							  "mofluid_image_isdefault" => $new_mofluid_banner_isdefault,
							  "mofluid_image_action" => base64_encode($mofluid_new_banner_action_data),
							  "mofluid_image_action_data" => ''
							);
						    $mofluid_elegant_image_model_add->setData($image_data)->save();
						}
				    }
			    }
	          	Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_thememofluidelegant')->__('New Banner has been added.'));
				Mage::getSingleton('adminhtml/session')->setFormData(true);
	          }
	          catch(Exception $exc) {
	          	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_thememofluidelegant')->__($exc->getMessage()));
				Mage::getSingleton('adminhtml/session')->setFormData(true);
	          }
         }
	    else {
	        try {
	             //echo "<pre>"; print_r($mofluidtheme_logobanner); die;
			   $mofluid_theme_id = $mofluidtheme_config['mofluid_theme_id'];
			    //Saving Elegant Theme Configuration
			    $mofluid_elegant_config_model = Mage::getModel('mofluid_thememofluidelegant/thememofluidelegant');
			    $mofluid_elegant_config_data = array();
			    $mofluid_elegant_config_data = $this->getElegantConfigData($mofluidtheme_config);
			    $mofluid_elegant_config_data["mofluid_theme_banner_image_type"] =  $mofluidtheme_logobanner["mofluid_theme_banner_image_type"];
			    if($mofluid_elegant_config_model != null) {
			        $mofluid_elegant_config_model->setData($mofluid_elegant_config_data)->setId($mofluid_theme_id);
			        $mofluid_elegant_config_model->save();
			    }
				//Saving Elegant Theme Colors
		   	    $mofluid_colors = $this->getElegantColor($mofluidtheme_foreground, $mofluidtheme_background);
			    foreach($mofluid_colors as $color_key=>$color_value) {
			        $mofluid_elegant_color_model = Mage::getModel('mofluid_thememofluidelegant/colors');
			        if($mofluid_elegant_color_model != null) {
				       $color_data = array("mofluid_color_value" => $color_value);
			            $mofluid_elegant_color_model->setData($color_data)->setId($color_key);
			            $mofluid_elegant_color_model->save();
			        }
			    }
			    //Saving Elegant Theme Text
			    $mofluid_texts = $this->getElegantLocale($mofluidtheme_button, $mofluidtheme_text, $mofluidtheme_alert);
			    foreach($mofluid_texts as $text_key=>$text_value) {
			        $mofluid_elegant_message_model = Mage::getModel('mofluid_thememofluidelegant/messages');
			        if($mofluid_elegant_message_model != null) {
			            $message_data = array("mofluid_message_value" => $text_value);
			            $mofluid_elegant_message_model->setData($message_data)->setId($text_key);
			            $mofluid_elegant_message_model->save();
			        }
			    }
				//Saving Logo and Banner
			    $mofluid_image_path = Mage::getBaseDir().'/media/mofluid/images';
			    $mofluid_image_data = array();
			    try {
			        foreach($_FILES as $key=>$value) {
			            if($value["name"] != ""){
			                $finalkey = str_replace("logo_","",$key);
			                $finalkey = str_replace("banner_","",$finalkey);
			            	 $finalkey = str_replace("themeicons_","",$finalkey);
			            	 if($value["error"] == 0) {
			                	//File Upload to Media
			                	$file_uploader = new Varien_File_Uploader( array(
							    'name' => $value['name'],
							    'type' => $value['type'],
							    'tmp_name' => $value['tmp_name'],
							    'error' => $value['error'],
							    'size' => $value['size']
			                	));
			                	$file_uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); //Allowed extension for file
						     $file_uploader->setAllowCreateFolders(true); //for creating the directory if not exists
						     $file_uploader->setAllowRenameFiles(true); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
						     $file_uploader->setFilesDispersion(false);
						     $file_uploader->save($mofluid_image_path, $value['name']);
						     $renamedFileName = $file_uploader->getUploadedFileName(); 	
						     $fileurl = $mofluid_image_path . "/" . $renamedFileName;
						     $exact_path = Mage::getBaseUrl('media').'mofluid/images/'.$renamedFileName;
					          //Update Database
					          $mofluid_elegant_image_model = Mage::getModel('mofluid_thememofluidelegant/images');
					          if($mofluid_elegant_image_model != null) {
			                    	$image_data = array("mofluid_image_value" => $exact_path);
			                    	$mofluid_elegant_image_model->setData($image_data)->setId($finalkey);
			                    	$mofluid_elegant_image_model->save();
			                	} 
			            	}
			        	}
			    	}
			}
			catch(Exception $imgerr) {
			    echo $imgerr->getMessage();
			}			
		}
         	catch(Exception $theme_excep) {
             echo $theme_excep->getMessage();
         }
         Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_thememofluidelegant')->__('Mofluid Theme Settings has been saved successfully'));
	    Mage::getSingleton('adminhtml/session')->setFormData(true);
     }
	$this->_redirect('*/*/');
  }
  protected function getElegantConfigData($mofluidtheme_config) {
         $theme_config = array(
          "google_ios_clientid" => $mofluidtheme_config["google_ios_clientid"],
         "google_login" => $mofluidtheme_config["google_login"],
          "cms_pages" => $mofluidtheme_config["cms_pages"],
         "about_us" => $mofluidtheme_config["about_us"],
         "term_condition" => $mofluidtheme_config["term_condition"],
         "privacy_policy" => $mofluidtheme_config["privacy_policy"],
         "return_privacy_policy" => $mofluidtheme_config["return_privacy_policy"],
         "mofluid_theme_custom_footer" => base64_encode($mofluidtheme_config["mofluid_theme_custom_footer"]),
         "mofluid_display_catsimg" => $mofluidtheme_config["mofluid_theme_catsimg"],
         "tax_flag" => $mofluidtheme_config["tax_flag"],
         "mofluid_theme_display_custom_attribute" =>  $mofluidtheme_config["mofluid_theme_display_custom_attribute"]
          );
         return  $theme_config;
     }
     protected function getElegantColor($foreground , $background) {
         $mofluid_color = array();
         $mofluidtheme_color = array_merge($foreground, $background);
		 foreach($mofluidtheme_color as $key=>$value) {
		    $final_key = str_replace("foreground_","", $key);
		    $final_key = str_replace("background_","", $final_key);
		    $mofluid_color[$final_key] = $value;
		 }
		 return $mofluid_color;
     }
     protected function getElegantLocale($mofluidtheme_button, $mofluidtheme_text, $mofluidtheme_alert) {
         $mofluidtheme_locale = array();
         $mofluidtheme_locale_result = array();
         $mofluidtheme_locale = array_merge(array_merge($mofluidtheme_button, $mofluidtheme_text),$mofluidtheme_alert);
         foreach($mofluidtheme_locale as $key=>$value) {
		    $final_key = str_replace("alert_","", $key);
		    $final_key = str_replace("text_","", $final_key);
		    $final_key = str_replace("button_","", $final_key);
		    $mofluidtheme_locale_result[$final_key] = $value;
		 }
		 return $mofluidtheme_locale_result;
     
     }
    /**
     * registry form object
     */
    protected function _registryObject()
    {
//        Mage::register('mofluid_thememofluidelegant', Mage::getModel('mofluid_thememofluidelegant/form'));
    }

}