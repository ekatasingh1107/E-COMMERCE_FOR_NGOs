<?php

	class Mofluid_Buildios_Adminhtml_BuildiosController extends Mage_Adminhtml_Controller_Action
	{


	    /**
	     * View form action
	     */
	    public function indexAction()
	    {
		$this->_registryObject();
		$this->loadLayout();
		$this->_setActiveMenu('mofluid/form');
		$this->_addBreadcrumb(Mage::helper('mofluid_buildios')->__('Form'), Mage::helper('mofluid_buildios')->__('Form'));
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
                $this->set_php_ini_values();
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
		    $this->getLayout()->createBlock('mofluid_buildios/adminhtml_form_edit_tab_product')
			->toHtml()
		);
	    }
	    
	    /**
	     * saveAccountCredentials
	     * Save Account Credentials to Magento Database
	     *
	     * @return void
	     */
	    protected function saveAccountCredentials($config, $files, $print_success_log) {
		$account_config = array();
	       
		//Saving Certificate and Provisioning Profile
		    //Path for Certificates and provisioning profile
		    $mofluid_saved_certificates_path = Mage::getBaseDir().'/media/mofluid/certificates';
		    $error = 0;
		    try {
			//Save iOS Certificate
			if($files["mofluid_certificates"]["error"] == 0) {
			    $file_uploader = new Varien_File_Uploader( array(
					'name' => $files["mofluid_certificates"]["name"],
					    'type' => $files["mofluid_certificates"]["type"],
					    'tmp_name' => $files["mofluid_certificates"]["tmp_name"],
					    'error' => $files["mofluid_certificates"]["error"],
					    'size' => $files["mofluid_certificates"]["size"]
				    ));
				    $file_uploader->setAllowedExtensions(array('p12')); //Allowed extension for file
				    $file_uploader->setAllowCreateFolders(false); //for creating the directory if not exists
				    $file_uploader->setAllowRenameFiles(true); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
				    $file_uploader->setFilesDispersion(false);
				    $file_uploader->save($mofluid_saved_certificates_path, $files["mofluid_certificates"]["name"]);
				    $renamedFileName = $file_uploader->getUploadedFileName(); 	
				    $fileurl = $mofluid_saved_certificates_path . "/" . $renamedFileName;
				    @chmod($fileurl, 0777);
				    $mofluid_certificates_path = Mage::getBaseUrl('media').'mofluid/certificates/'.$renamedFileName;
				    $account_config['certificate_path'] = $mofluid_certificates_path;
			}
			else {
			    if($config["mofluid_certificates"]["value"]) {
				$account_config['certificate_path'] = $config["mofluid_certificates"]["value"];
			    }
			    else {
				$error = 1;
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__('Please upload your iOS certificate.'));
				Mage::getSingleton('adminhtml/session')->setFormData(true);
			    $this->_redirect('*/*/');
			    } 
			}
			if($error == 0) {
			    //Save iOS Provisioning Profile
			    if($files["mofluid_provisioning_profile"]["error"] == 0) {
				$file_uploader = new Varien_File_Uploader( array(
					    'name' => $files["mofluid_provisioning_profile"]["name"],
						'type' => $files["mofluid_provisioning_profile"]["type"],
						'tmp_name' => $files["mofluid_provisioning_profile"]["tmp_name"],
						'error' => $files["mofluid_provisioning_profile"]["error"],
						'size' => $files["mofluid_provisioning_profile"]["size"]
					));
					$file_uploader->setAllowedExtensions(array('mobileprovision')); //Allowed extension for file
					$file_uploader->setAllowCreateFolders(true); //for creating the directory if not exists
					$file_uploader->setAllowRenameFiles(true); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
					$file_uploader->setFilesDispersion(false);
					$file_uploader->save($mofluid_saved_certificates_path, $files["mofluid_provisioning_profile"]["name"]);
					$renamedFileName = $file_uploader->getUploadedFileName(); 	
					$fileurl = $mofluid_saved_certificates_path . "/" . $renamedFileName;
					@chmod($fileurl, 0777);
					$mofluid_provisioning_profile_path = Mage::getBaseUrl('media').'mofluid/certificates/'.$renamedFileName;
					$account_config['provisioning_profile'] = $mofluid_provisioning_profile_path;
			    }
			    else {
				if($config["mofluid_provisioning_profile"]["value"]) {
				    $account_config['provisioning_profile']  = $config["mofluid_provisioning_profile"]["value"];
				}
				else {
				    $error = 1;
				    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__('Please upload your provisioning profile.'));
				    Mage::getSingleton('adminhtml/session')->setFormData(true);
				$this->_redirect('*/*/');
				}
			    }
			}
			if($error == 0) {
			    //Set Mofluid, Phonegap and Other Credentials
			    $account_config['mofluid_id'] = $config['mofluid_username'];
			$account_config['mofluid_password'] = $config['mofluid_password'];
			//$account_config['phonegap_build_id'] = $config['phonegap_build_username'];
			//$account_config['phonegap_build_password'] = $config['phonegap_build_password'];
			$account_config['certificate_passpharse'] = $config['mofluid_passpharse'];
			$account_config['certificate_type'] = $config['certificate_type'];
			
			//Save Details Using Mofluid Build iOS Account Model
			    $mofluid_buildios_account_model = Mage::getModel('mofluid_buildios/accounts');
				if($mofluid_buildios_account_model != null) {
				    $mofluid_buildios_account_model->setData($account_config)->setId(1);
					$mofluid_buildios_account_model->save();
				}
				if($print_success_log) {
				    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildios')->__('Account Credentials has been saved successfully.'));
				Mage::getSingleton('adminhtml/session')->setFormData(true);
			    $this->_redirect('*/*/');
			}
		    }
		    }
		    catch(Exception $err) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__($err->getMessage()));
			Mage::getSingleton('adminhtml/session')->setFormData(true);
		    $this->_redirect('*/*/');
		}
		}
	    protected function saveBuildConfig($config, $files, $print_success_log) {
		try {
				$build_config = array();
				$mofluidcurrency = array();
				$mofluidcurrency["code"] = $config["buildios_currency"];
				$mofluidcurrency["symbol"] = base64_encode(Mage::app()->getLocale()->currency($config["buildios_currency"])->getSymbol());
			if($mofluidcurrency["symbol"] == null || $mofluidcurrency["symbol"] == '') {
                          $mofluidcurrency["symbol"] = $mofluidcurrency["code"];
                        }
                        $build_config["mofluid_app_name"] = $config["buildios_appname"];
			$build_config["mofluid_app_bundleid"] = $config["buildios_bundleid"];
			$build_config["mofluid_app_version"] = $config["buildios_version"];
			$build_config["mofluid_default_store"] = $config["buildios_storeid"];
			$build_config["mofluid_default_currency"] = json_encode($mofluidcurrency);

			$build_config["mofluid_default_theme"] = $config["buildios_themes"];
			//Save Details Using Mofluid iOS Build Config Model
			$mofluid_buildios_config_model = Mage::getModel('mofluid_buildios/buildconfig');
			if($mofluid_buildios_config_model != null) {
			    
				$mofluid_buildios_config_model->setData($build_config)->setId(1);
				$mofluid_buildios_config_model->save();
				if($print_success_log) {
				    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildios')->__('Build Configuration has been saved successfully.'));
				    Mage::getSingleton('adminhtml/session')->setFormData(true);
				    $this->_redirect('*/*/');
        	    }
			}
			else {
			   Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__('There is a Problem while saving Build Configuration'));
	           Mage::getSingleton('adminhtml/session')->setFormData(true);
               $this->_redirect('*/*/');
			}
			
        }
        catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__($ex->getMessage()));
	        Mage::getSingleton('adminhtml/session')->setFormData(true);
            $this->_redirect('*/*/');
        }
    }
    protected function saveApplicationAssets($config, $files, $print_success_log) {
        //Saving Icons and Splash Screens
		$mofluid_image_path = Mage::getBaseDir().'/media/mofluid/images/ios';
		$mofluid_image_data = array();
			try {
			    foreach($files as $key=>$value) {
			        if($key == "mofluid_certificates" || $key == "mofluid_provisioning_profile") {
                                    continue;
                                }
                                if($value["name"] != ""){
			            $finalkey = str_replace("icon_","",$key);
			            $finalkey = str_replace("splash_","",$finalkey);
			            $finalkey = str_replace("artwork_","",$finalkey);
			            $finalkey = str_replace("other_","",$finalkey);
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
						    @chmod($fileurl, 0777);
						    $exact_path = Mage::getBaseUrl('media').'mofluid/images/ios/'.$renamedFileName;
					        //Update Database
					        $mofluid_buildios_assets_model = Mage::getModel('mofluid_buildios/assets');
					        //print_r($mofluid_elegant_image_model); die;
			                if($mofluid_buildios_assets_model != null) {
			                    $image_data = array("mofluid_assets_value" => $exact_path);
			                    $mofluid_buildios_assets_model->setData($image_data)->setId($finalkey);
			                    $mofluid_buildios_assets_model->save();
			                } 
			            }
			        }
			    }
			    if($print_success_log) {
			        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildios')->__('Application\'s Assets has been saved successfully.'));
			        Mage::getSingleton('adminhtml/session')->setFormData(true);
			        $this->_redirect('*/*/');
			    }
			}
			catch(Exception $ex1) {
			    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__($ex1->getMessage()));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
			}			
    }
    public function saveMofluidConfig($request, $files, $print_success_log) 
    {
        $this->saveAccountCredentials($request->getParam('mofluid_build_ios_account'), $files, $print_success_log);
        $this->saveBuildConfig($request->getParam('mofluid_build_ios_config'), $files, $print_success_log);
        $this->saveApplicationAssets($request->getParam('mofluid_build_ios_assets'), $files, $print_success_log);
    }
    
    public function generateAction()
    {
       $this->set_php_ini_values();
       $this->saveMofluidConfig($this->getRequest(), $_FILES, false);
       $this->sendiOSBuildRequest($this->getActiveMofluidAppTheme());
    }
    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function saveAction()
    {
        $this->set_php_ini_values();
        $this->saveMofluidConfig($this->getRequest(), $_FILES, true);
    }
   protected function getActiveMofluidAppTheme() {
        $active_theme_code = "elegant";
        try {
            $mofluid_build_config_model = Mage::getModel('mofluid_buildios/buildconfig');
            if($mofluid_build_config_model != null) {
                $mofluid_build_config_android = $mofluid_build_config_model->getCollection()->addFieldToFilter('mofluid_platform_id',1);
                $mofluid_build_config_data = $mofluid_build_config_android->getData();
                $mofluid_build_config = $mofluid_build_config_data[0];
                if($mofluid_build_config["mofluid_default_theme"] != null) {
                    $active_theme_code = $mofluid_build_config["mofluid_default_theme"];
                }
            }
        }
        catch(Exception $ex) {
            $active_theme_code = "elegant";
        }
        return $active_theme_code;
    } 
    protected function sendiOSBuildRequest($selected_mofluid_theme_code) {
        $mofluid_media_site_url = Mage::getBaseUrl('media');   //http:/domain/media/
		$mofluid_media_site_path = Mage::getBaseDir('media'); 
		
        if($selected_mofluid_theme_code == null || $selected_mofluid_theme_code == ""){
		    $selected_mofluid_theme_code = 'elegant';
		}
		$mofluid_theme_default_model = Mage::getModel('mofluid_thememofluidelegant/thememofluidelegant');
        $mofluid_theme_default_collection = $mofluid_theme_default_model->getCollection()->addFieldToFilter('mofluid_theme_code',$selected_mofluid_theme_code);
        $mofluid_theme_default = $mofluid_theme_default_collection->getData(); 
        
        //Get Theme ID of selected mofluid theme
        $selected_mofluid_theme_id = $mofluid_theme_default[0]["mofluid_theme_id"];
		$mofluid_theme_data_colors = Mage::getModel('mofluid_thememofluidelegant/colors')->getCollection()->addFieldToFilter('mofluid_theme_id',$selected_mofluid_theme_id)->getData();
		$mofluid_theme_data_images = Mage::getModel('mofluid_thememofluidelegant/images')->getCollection()->addFieldToFilter('mofluid_theme_id',$selected_mofluid_theme_id)->getData();
		$mofluid_theme_data_messages = Mage::getModel('mofluid_thememofluidelegant/messages')->getCollection()->addFieldToFilter('mofluid_theme_id',$selected_mofluid_theme_id)->getData();
		
		foreach($mofluid_theme_data_colors as $color_key=>$color_val) {
            $final_color_key =  preg_replace('/[^a-zA-Z0-9_]/', '', strtolower(str_replace(" ","_",trim($color_val["mofluid_color_label"]))));
            $mofluid_theme_color[$color_val["mofluid_color_type"]][$final_color_key] = ltrim($color_val["mofluid_color_value"],"#"); 
        }
                   
        foreach($mofluid_theme_data_images as $images_key=>$images_val) {
            $final_image_key =  preg_replace('/[^a-zA-Z0-9_]/', '', strtolower(str_replace(" ","_",trim($images_val["mofluid_image_label"]))));
            $mofluid_theme_image[$images_val["mofluid_image_type"]][$final_image_key] = $images_val["mofluid_image_value"]; 
        }
		           
		foreach($mofluid_theme_data_messages as $message_key=>$message_val) {
            $final_message_key =  preg_replace('/[^a-zA-Z0-9_]/', '', strtolower(str_replace(" ","_",trim($message_val["mofluid_message_label"]))));
            $mofluid_theme_message[$message_val["mofluid_message_type"]][$final_message_key]= $message_val["mofluid_message_value"]; 
        }
		
		$mofluid_theme_data["config"] = $mofluid_theme_default[0];
		$mofluid_theme_data["colors"] = $mofluid_theme_color;
		$mofluid_theme_data["images"] = $mofluid_theme_image;
		$mofluid_theme_data["messages"] = $mofluid_theme_message;
		
		
		$resource = Mage::getSingleton('core/resource');
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;
        $module_name_arr = array();
        foreach($modulesArray as $key=>$val)  {
            if($val->active) {
                try {
                    $module_name_arr[] = $key; 
                }
                catch(Exception $ex) {
                    
                }
            }  
        }
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read'); 
        $selectresource = $connection->select()->from(Mage::getSingleton('core/resource')->getTableName('adminmofluid/mofluidresource'), array('*'));
        $MofluidResourcedata =$connection->fetchAll($selectresource);
        $mofluid_available_resource = array();
        $mofluid_final_resource_data = array();
        $mofluid_final_resource = array();
        $found = 0;
        foreach($module_name_arr as $mkey=>$mval) {
            foreach($MofluidResourcedata as $mrkey=>$mrval) {
                if($mrval['module'] == $mval && $mrval['sendbuildmode'] != 0) {
                    $mofluid_available_resource[] = $mrval['resource'];
                    $found = 1;
                }
            }
        }
        
        if($found == 1) {
            foreach($mofluid_available_resource as $index=>$table_name) {
                $mofluid_res_select = $connection->select()->from($table_name, array('*')); 
                $mofluid_final_resource_data[$table_name] = $connection->fetchAll($mofluid_res_select);
            }
        }
        try {
            $mofluid_table_prefix = Mage::getConfig()->getTablePrefix();
        }
        catch(Exception $prefixexc) {
            $mofluid_table_prefix = '';
        }
        $platform_data = array();
        $mofluid_buildios_config = Mage::getModel('mofluid_buildios/buildconfig')->getCollection()->addFieldToFilter('mofluid_app_platform','ios')->getData();
		$mofluid_buildios_account = Mage::getModel('mofluid_buildios/accounts')->getCollection()->addFieldToFilter('mofluid_platform_id',1)->getData();
		$mofluid_buildios_assets = Mage::getModel('mofluid_buildios/assets')->getCollection()->addFieldToFilter('mofluid_platform','ios')->getData();
		
		$platform_data["config"] = $mofluid_buildios_config[0]; 
		$platform_data["account"] = $mofluid_buildios_account[0]; 
		$platform_data["assets"] = $mofluid_buildios_assets; 
		
		$mofluid_final_resource["site_url"] = Mage::getBaseUrl();
        $mofluid_final_resource["table_prefix"] = $mofluid_table_prefix;
        $mofluid_final_resource["mofluid_build_auth_key"] = "QE1vZmx1aWQxLjE2LjBAX0BGcmVlQF9AQnlFYml6b25AX0BTaGFzaGlA";
        $mofluid_final_resource["platform_data"] = $platform_data;
        $mofluid_final_resource["mofluid_theme_data"] = $mofluid_theme_data;
        $mofluid_final_resource["resource"] = $mofluid_available_resource;
        $mofluid_final_resource["data"] = $mofluid_final_resource_data;
        $current_timestamp = Mage::getModel('core/date')->timestamp(time());
        try {
            $request_file_name = $mofluid_media_site_path.'/mofluid/build_request_logs/ios/mofluid_buildrequest_'.$current_timestamp.'.json';
            $request_file_handle = @fopen($request_file_name, "w");
            @fwrite($request_file_handle, json_encode($mofluid_final_resource));
            @fclose($request_file_handle);
            @chmod($request_file_name, 0777);
             
        }
        catch(Exception $ex) {
            echo 'Error : '.$ex->getMessage(); die;
        }
        $request_file_path = $mofluid_media_site_url.'mofluid/build_request_logs/ios/mofluid_buildrequest_'.$current_timestamp.'.json';
        $data= array();
		$data["mofluidid"] = $mofluid_final_resource["platform_data"]["account"]["mofluid_id"];
		$data['source'] = $request_file_path;
		$data['build_app_type'] = $mofluid_final_resource["platform_data"]["account"]["certificate_type"];
		$downloadlink = $mofluid_final_resource["platform_data"]["account"]["build_url"].'/iOS/Controller.php?data='.base64_encode(json_encode($data));
		$downloadlink = str_replace(" ","%20",$downloadlink);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $downloadlink);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_PROXY, '192.168.1.11');
		//curl_setopt($ch, CURLOPT_PROXYPORT, '8080');
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$exec = curl_exec($ch);
		$resp = explode("\n\r\n", $exec);
		$res_msg = explode("\n", $resp[1]);
		$json = implode("",$res_msg);
		curl_close($ch);
		$obj =$json;

      	if($obj == "") {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildios')->__("Server Not Responding properly...Please Try Again after some time."));
	        Mage::getSingleton('adminhtml/session')->setFormData(true);
            $this->_redirect('*/*/');
        }
        else {
  	        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildios')->__($obj));
	        Mage::getSingleton('adminhtml/session')->setFormData(true);
            $this->_redirect('*/*/');
        }
  	   
    } 
    /**
     * registry form object
     */
    protected function _registryObject()
    {
         //Mage::register('mofluid_paymentcod', Mage::getModel('mofluid_paymentcod/form'));
    }
    /*
     * Set PHP.INI Values 
     */
     public function set_php_ini_values() {
        //-------- Set PHP.INI Values
        ini_set('post_max_size','256M');
        ini_set('upload_max_filesize','256M');
        ini_set('max_file_uploads','50');
     }
}
