<?php

class Mofluid_Buildandroid_Adminhtml_BuildandroidController extends Mage_Adminhtml_Controller_Action
{


    /**
     * View form action
     */
    public function indexAction()
    {
        $this->_registryObject();
        $this->loadLayout();
        $this->_setActiveMenu('mofluid/form');
        $this->_addBreadcrumb(Mage::helper('mofluid_buildandroid')->__('Form'), Mage::helper('mofluid_buildandroid')->__('Form'));
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
            $this->getLayout()->createBlock('mofluid_buildandroid/adminhtml_form_edit_tab_product')
                ->toHtml()
        );
    }
    
    /**
     * saveAccountCredentials
     * Save Account Credentials to Magento Database for Android
     * @return void
     */
    protected function saveAccountCredentials($config, $files, $print_success_log) {
        $found_error = 0;
        $mofluid_android_account_detail = array();
        $mofluid_buildandroid_account_read = Mage::getModel('mofluid_buildandroid/accounts')->getCollection()->addFieldToFilter('mofluid_platform_id',2)->getData();
        $mofluid_cert_already = $mofluid_buildandroid_account_read[0]["certificate_path"];
        //User has the Signing Key Already
        if($config["signing_key_type"] == 1 || $config["signing_key_type"] == "1") {
            //Saving Android Signing Key
	        $mofluid_saved_certificates_path = Mage::getBaseDir().'/media/mofluid/certificates';
	        try {
	           if($files["mofluid_certificates"]["error"] == 0) {
	               $file_uploader = new Varien_File_Uploader( array(
			           'name' => $files["mofluid_certificates"]["name"],
    				   'type' => $files["mofluid_certificates"]["type"],
	    			   'tmp_name' => $files["mofluid_certificates"]["tmp_name"],
		    		   'error' => $files["mofluid_certificates"]["error"],
			    	   'size' => $files["mofluid_certificates"]["size"]
			       ));
			       $file_uploader->setAllowedExtensions(array('keystore','keystores')); //Allowed extension for file
			       $file_uploader->setAllowCreateFolders(false); //for creating the directory if not exists
			       $file_uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
			       $file_uploader->setFilesDispersion(false);
			       $file_uploader->save($mofluid_saved_certificates_path, $files["mofluid_certificates"]["name"]);
			       $renamedFileName = $file_uploader->getUploadedFileName(); 	
			       $fileurl = $mofluid_saved_certificates_path . "/" . $renamedFileName;
			       @chmod($fileurl, 0777);
			       $mofluid_certificates_path = Mage::getBaseUrl('media').'mofluid/certificates/'.$renamedFileName;
			       $mofluid_android_account_detail['certificate_path'] = $mofluid_certificates_path;
                   $mofluid_android_account_detail["mofluid_id"] = $config["mofluid_username"];
                   $mofluid_android_account_detail["mofluid_password"] = $config["mofluid_password"];
                   $mofluid_android_account_detail["release_key_type"] = $config["signing_key_type"]; 
                   $mofluid_android_account_detail["release_privatekey_password"] = $config["release_privatekey_password"]; 
                   $mofluid_android_account_detail["release_keystore_password"] = $config["release_keystore_password"]; 
                   
                }
                else {
                     if($mofluid_cert_already == null || $mofluid_cert_already == "") {
                         $found_error = 1;
                         Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Signing Key is a required field. When selecting Signing Key Type as I already have a Signing key'));
	                     Mage::getSingleton('adminhtml/session')->setFormData(true);
                         $this->_redirect('*/*/');
                     }
                     else {
                         $mofluid_android_account_detail["mofluid_id"] = $config["mofluid_username"];
						 $mofluid_android_account_detail["mofluid_password"] = $config["mofluid_password"];
						 $mofluid_android_account_detail["release_key_type"] = $config["signing_key_type"]; 
						 $mofluid_android_account_detail["release_privatekey_password"] = $config["release_privatekey_password"]; 
						 $mofluid_android_account_detail["release_keystore_password"] = $config["release_keystore_password"]; 
                     }
                }
                   
            }
            catch (Exception $ex) {
                $found_error = 1;
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__($err->getMessage()));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
                
        } 
        //User Apply for a New Signing Key 
        else {
            $found_error = 1;
            if($config["release_key_validity"] == null || $config["release_key_validity"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Signing Key Validity is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else if($config["release_key_data_commonname"] == null || $config["release_key_data_commonname"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Common Name is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else if($config["release_key_data_orgname"] == null || $config["release_key_data_orgname"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Organisation Name is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else if($config["release_key_data_orgunit"] == null || $config["release_key_data_orgunit"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Organisation Unit is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else if($config["release_key_data_locality"] == null || $config["release_key_data_locality"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('City or Locality is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else if($config["release_key_data_province"] == null || $config["release_key_data_province"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('State or Province Name is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else if($config["release_key_data_country"] == null || $config["release_key_data_country"] == "") {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Counter Code is a required field. When selecting Signing Key Type as Apply for New Signing Key'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
            }
            else {
                $found_error = 0;
                $mofluid_android_account_detail["mofluid_id"] = $config["mofluid_username"];
                $mofluid_android_account_detail["mofluid_password"] = $config["mofluid_password"];
               	$mofluid_android_account_detail["release_key_type"] = $config["signing_key_type"]; 
                $mofluid_android_account_detail["release_privatekey_password"] = $config["release_privatekey_password"]; 
                $mofluid_android_account_detail["release_keystore_password"] = $config["release_keystore_password"]; 
                $mofluid_android_account_detail["release_key_validity"] = $config["release_key_validity"]; 
                $mofluid_android_account_detail["release_key_data"] = json_encode(array(
                    "CN" => $config["release_key_data_commonname"],
                    "O" => $config["release_key_data_orgname"],
                    "OU" => $config["release_key_data_orgunit"],
                    "L" => $config["release_key_data_locality"],
                    "ST" => $config["release_key_data_province"],
                    "C" => $config["release_key_data_country"]
                ));
            }
        }
        if($found_error == 0) {
            $mofluid_buildandroid_account_model = Mage::getModel('mofluid_buildandroid/accounts');
		    if($mofluid_buildandroid_account_model != null) {
		        $mofluid_buildandroid_account_model->setData($mofluid_android_account_detail)->setId(2);
			    $mofluid_buildandroid_account_model->save();
			    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildandroid')->__('Account details has been saved for android.'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
		    }
		    else {
		        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Model for Build Android Not Found.'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
		    }
		}
	}
    protected function saveBuildConfig($config, $files, $print_success_log) {
        try {
			$build_config = array();
                        $mofluidcurrency = array();
                        $mofluidcurrency["code"] = $config["buildandroid_currency"];
                        $mofluidcurrency["symbol"] = base64_encode(Mage::app()->getLocale()->currency($config["buildandroid_currency"])->getSymbol());
			if($mofluidcurrency["symbol"] == null || $mofluidcurrency["symbol"] == '') {
                          $mofluidcurrency["symbol"] = $mofluidcurrency["code"];
                        }
                        $build_config["mofluid_app_name"] = $config["buildandroid_appname"];
			$build_config["mofluid_app_bundleid"] = $config["buildandroid_bundleid"];
			$build_config["mofluid_app_version"] = $config["buildandroid_version"];
			$build_config["mofluid_default_store"] = $config["buildandroid_storeid"];
			$build_config["mofluid_default_currency"] = json_encode($mofluidcurrency);
			$build_config["mofluid_default_theme"] = $config["buildandroid_themes"];
		
			//Save Details Using Mofluid iOS Build Config Model
			$mofluid_buildandroid_config_model = Mage::getModel('mofluid_buildandroid/buildconfig');
			if($mofluid_buildandroid_config_model != null) {
				$mofluid_buildandroid_config_model->setData($build_config)->setId(2);
				$mofluid_buildandroid_config_model->save();
				if($print_success_log) {
				   Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildandroid')->__('Build Configuration has been saved successfully.'));
				   Mage::getSingleton('adminhtml/session')->setFormData(true);
				   $this->_redirect('*/*/');
        	    }
			}
			else {
			    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Model for Build Config of Android Not Found.'));
	            Mage::getSingleton('adminhtml/session')->setFormData(true);
                $this->_redirect('*/*/');
			}
			
        }
        catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__($ex->getMessage()));
	        Mage::getSingleton('adminhtml/session')->setFormData(true);
            $this->_redirect('*/*/');
        }
    }
    protected function getActiveMofluidAppTheme() {
        $active_theme_code = "elegant";
        try {
            $mofluid_build_config_model = Mage::getModel('mofluid_buildandroid/buildconfig');
            if($mofluid_build_config_model != null) {
                $mofluid_build_config_android = $mofluid_build_config_model->getCollection()->addFieldToFilter('mofluid_platform_id',2);
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
    protected function saveApplicationAssets($config, $files, $print_success_log) {
        //Saving Icons and Splash Screens
		$mofluid_image_path = Mage::getBaseDir().'/media/mofluid/images/android';
		$mofluid_image_data = array();
			try {
			    foreach($files as $key=>$value) {
			        if($key == "mofluid_certificates") {
			            continue;
			        }
			        if($value["name"] != ""){
			            $finalkey = str_replace("icon_","",$key);
			            $finalkey = str_replace("splash_","",$finalkey);
			            $finalkey = str_replace("artwork_","",$finalkey);
			            $finalkey = str_replace("other_","",$finalkey);
			            if($value["error"] == 0) {
			                //File Upload to Media
			                try {
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
								$exact_path = Mage::getBaseUrl('media').'mofluid/images/android/'.$renamedFileName;
								//Update Database
								$mofluid_buildandroid_assets_model = Mage::getModel('mofluid_buildandroid/assets');
								//print_r($mofluid_elegant_image_model); die;
								if($mofluid_buildandroid_assets_model != null) {
									$image_data = array("mofluid_assets_value" => $exact_path);
									$mofluid_buildandroid_assets_model->setData($image_data)->setId($finalkey);
									$mofluid_buildandroid_assets_model->save();
								}
			                }
			                catch(Exception $ex1) {
								Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__('Pass1'.$ex1->getMessage()));
								Mage::getSingleton('adminhtml/session')->setFormData(true);
								$this->_redirect('*/*/');
			                }	
			            }
			        }
			    }
			    if($print_success_log) {
			        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildandroid')->__('Application\'s Assets has been saved successfully.'));
			        Mage::getSingleton('adminhtml/session')->setFormData(true);
			        $this->_redirect('*/*/');
			    }
			}
			catch(Exception $ex1) {
			}			
    }
    public function saveMofluidConfig($request, $files, $print_success_log) 
    {
  
        $this->saveAccountCredentials($request->getParam('mofluid_build_android_account'), $files, $print_success_log);
        $this->saveBuildConfig($request->getParam('mofluid_build_android_config'), $files, $print_success_log);
        $this->saveApplicationAssets($request->getParam('mofluid_build_android_assets'), $files, $print_success_log);
    }
    
    public function generateAction()
    {
       $this->saveMofluidConfig($this->getRequest(), $_FILES, false);
       $this->sendAndroidBuildRequest($this->getActiveMofluidAppTheme());
    }
    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function saveAction()
    {
        $this->saveMofluidConfig($this->getRequest(), $_FILES, true);
    }
    
    protected function sendAndroidBuildRequest($selected_mofluid_theme_code) {
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
        $mofluid_buildandroid_config = Mage::getModel('mofluid_buildandroid/buildconfig')->getCollection()->addFieldToFilter('mofluid_app_platform','android')->getData();
		$mofluid_buildandroid_account = Mage::getModel('mofluid_buildandroid/accounts')->getCollection()->addFieldToFilter('mofluid_platform_id',2)->getData();
		$mofluid_buildandroid_assets = Mage::getModel('mofluid_buildandroid/assets')->getCollection()->addFieldToFilter('mofluid_platform','android')->getData();
		
		$platform_data["config"] = $mofluid_buildandroid_config[0]; 
		$platform_data["account"] = $mofluid_buildandroid_account[0]; 
		$platform_data["assets"] = $mofluid_buildandroid_assets; 
		
		$mofluid_final_resource["site_url"] = Mage::getBaseUrl();
        $mofluid_final_resource["table_prefix"] = $mofluid_table_prefix;
        $mofluid_final_resource["mofluid_build_auth_key"] = "QE1vZmx1aWQxLjE2LjBAX0BGcmVlQF9AQnlFYml6b25AX0BTaGFzaGlA";
        $mofluid_final_resource["platform_data"] = $platform_data;
        $mofluid_final_resource["mofluid_theme_data"] = $mofluid_theme_data;
        $mofluid_final_resource["resource"] = $mofluid_available_resource;
        $mofluid_final_resource["data"] = $mofluid_final_resource_data;
        $current_timestamp = Mage::getModel('core/date')->timestamp(time());
        try {
            $request_file_name = $mofluid_media_site_path.'/mofluid/build_request_logs/android/mofluid_buildrequest_'.$current_timestamp.'.json';
            $request_file_handle = @fopen($request_file_name, "w");
            @fwrite($request_file_handle, json_encode($mofluid_final_resource));
            @fclose($request_file_handle);
            @chmod($request_file_name, 0777);
             
        }
        catch(Exception $ex) {
            echo 'Error : '.$ex->getMessage(); die;
        }
        $request_file_path = $mofluid_media_site_url.'mofluid/build_request_logs/android/mofluid_buildrequest_'.$current_timestamp.'.json';
        $data= array();
		$data["mofluidid"] = $mofluid_final_resource["platform_data"]["account"]["mofluid_id"];
		$data['source'] = $request_file_path;
		$data['build_app_type'] = $mofluid_final_resource["platform_data"]["account"]["certificate_type"];
		$downloadlink = $mofluid_final_resource["platform_data"]["account"]["build_url"].'/Android/Controller.php?data='.base64_encode(json_encode($data));
		$downloadlink = str_replace(" ","%20",$downloadlink);
		//print_r($downloadlink); exit;
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
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mofluid_buildandroid')->__("Server Not Responding properly...Please Try Again after some time."));
	        Mage::getSingleton('adminhtml/session')->setFormData(true);
            $this->_redirect('*/*/');
        }
        else {
  	        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_buildandroid')->__($obj));
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

}
