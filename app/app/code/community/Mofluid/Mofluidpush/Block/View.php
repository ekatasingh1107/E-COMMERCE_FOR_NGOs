<?php
/*****************************************************************************
 -----------------------------------------------------------------------------
 *  File            :     Mofluid/Mofluidpush/Block/View.php
 *  Application     :     Mofluid-Magento Plugin
 *  Description     :     View of Mofluid Admin Panel Menu for Magento Admin
 *  Organization    :     Mofluid
 *  Org URL         :     http://www.mofluid.com
 -----------------------------------------------------------------------------
 Copyright 2014: Ebizon Netinfo Pvt. Ltd. All rights reserved
 -----------------------------------------------------------------------------
*****************************************************************************/
class Mofluid_Mofluidpush_Block_View extends Mage_Core_Block_Template {
    protected function _toHtml() {
        //Set PHP.INI Values
	    ini_set('post_max_size','50M');
	    ini_set('upload_max_filesize','50M');
	    ini_set('max_file_uploads','50');
		
	    //Find URL and respective menu option
	    $url =$this->helper('core/url')->getCurrentUrl();
	    $arr = explode('/',$url);
	    $pos = count($arr)-1;
	    $option = ''.$arr[$pos];
		
	    //Include JS and other library
        $form = '<script type="text/javascript" src='.$this->getJsUrl().'mofluid/js/jquery-1.10.2.min.js></script><script type="text/javascript" src='.$this->getJsUrl().'mofluid/js/prototype.js></script>
                 <script type="text/javascript" src='.$this->getJsUrl().'mofluid/js/scriptaculous.js?load=builder,effects></script>
                 <script type="text/javascript" src='.$this->getJsUrl().'mofluid/js/modalbox.js></script>
                 <script type="text/javascript" src='.$this->getJsUrl().'mofluid/js/jscolor.js></script>
                 <link rel="stylesheet" href='.$this->getJsUrl().'mofluid/css/modalbox.css type="text/css" media="screen" />';
		
	    //----- Determine wether a POST Request Occurrs or not
	    if(isset($_POST['form_name'])){
	        //-------- Find the menu optiom and navigate accordingly
	        switch($_POST['form_name']) {	
	            case 'form_push_android_gcm':
	                try {
	                    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $connection->beginTransaction();
                        $_fieldsPushAndroid = array();
                        $_fieldsPushAndroid['gcm_id'] = $_POST['gcm_id'];
                        $_fieldsPushAndroid['gcm_key'] = $_POST['gcm_key'];
                        $_fieldsPushAndroid['gcm_mode'] = $_POST['gcm_mode'];
                        $_where = $connection->quoteInto('mofluidadmin_id =?', 1);
                        $connection->update(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush_settings'), $_fieldsPushAndroid, $_where);
                        $connection->commit();
                        $form .= '<script>Modalbox.show("<p><font color=green size=2>Your Google Cloud Messaging Account has been setup successfully for push notification.</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
		            }
	                catch(Exception $ex) {
	                    $form .= '<script>Modalbox.show("<p><font color=red size=2>'.$ex->getMessage().'</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
	                }
	                break;
	            case 'form_push_android_send':
	                try {
	                    $connection = Mage::getSingleton('core/resource')->getConnection('core_read'); 
                        $select = $connection->select()
                            ->from(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush_settings'), array('*')) 
                            ->where('mofluidadmin_id=?',1);
                        $AndroidPush =$connection->fetchRow($select);
	                    $GOOGLE_GCM_URL = 'https://android.googleapis.com/gcm/send';  //GCM URL
                        $apiKey = $AndroidPush['gcm_key'];                                 // GCM API Key
                        $proxy = '';                                                       // Proxy Server Address If any
                        $data = $_POST['push_android_message'];                            // Message Which should be send as Push Notification
                        $allRegIds = '';                                                   // Array of all registration Ids
                        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
                        $select = $connection->select()
                            ->from(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush'), array('push_token_id'))
                            ->where('platform=?','android')
                            ->where('mofluidadmin_id=?',1);
                        $allRegIds =$connection->fetchAll($select);
                        $push_headers = array (
                            'Authorization: key=' . $apiKey,
                            'Content-Type: application/json'
                        );
                        $message = array(
                            'message' => $data,
                            'title' => "Push Notification"
                        );
                        $push_logs = '################################################'.PHP_EOL.' : Push Notification Logs : '.PHP_EOL.'################################################'.PHP_EOL;
                        $push_logs .= PHP_EOL.'Title : '.$message["title"].PHP_EOL.'Message : '.$message["message"].PHP_EOL.PHP_EOL;
             
                        foreach($allRegIds as $regIds) {
                            $ids = array();
                            $ids[] = $regIds["push_token_id"];
                            $fields = array (
                                'registration_ids' => $ids, 
                                'data'             => $message
                            );
                            $post_data = json_encode($fields, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $GOOGLE_GCM_URL);
                            if (!is_null($proxy)) {
                                curl_setopt($ch, CURLOPT_PROXY, $proxy);
                            }
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $push_headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                            $result = curl_exec($ch);
                            if ($result === false) {
                                throw new \Exception(curl_error($ch));
                            }
                            curl_close($ch);
                            $output = json_decode($result,true);
                            $push_logs .= 'Multicast Id'.$output["multicast_id"].' || Success '.$output["success"].' || Failure '.$output["failure"].' || Message Id '.$output["results"][0]["message_id"].' || Device Token Id '.$regIds["push_token_id"].PHP_EOL;
                        }
                        //Save Push Notification Logs
                        $fname_push_logs_file = 'mofluid/push_notification_logs/android_logs'.Mage::getModel('core/date')->timestamp(time()).'.txt';
                        $fname_push_logs = Mage::getBaseDir().'/js/'.$fname_push_logs_file;
                        $fname_push_logs_link = $this->getJsUrl().$fname_push_logs_file;
                        $fhandle_push_logs = @fopen($fname_push_logs,"w");
                        @fwrite($fhandle_push_logs,$push_logs);
                        @fclose($fhandle_push_logs);
                        $form .= '<script>Modalbox.show("<p><font color=green size=2>Push Notification Sent Successfully. <br>See <a target=_blank href = '.$fname_push_logs_link.' >Log file</a> for more detail </font></p>",{title: "Mofluid : Push Notification Status ", width: 400});</script>';
                    }
                    catch(Exception $ex) {
                        $form .= '<script>Modalbox.show("<p><font color=green size=2>Push Notification Error. <br> '.$ex->getMessage().',{title: "Mofluid : Push Notification Status ", width: 400});</script>';
                    }
                    break;
	         case 'form_push_ios_apn':
	         try {
	              $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                  $connection->beginTransaction();
                  
                  $_fieldsPushiPhone = array();
                  $_fieldsPushiPhone['apn_passphrase'] = $_POST['apn_passphrase'];
                  $_fieldsPushiPhone['apn_mode'] = $_POST['apn_mode'];
                  
                  $path =Mage::getBaseDir().'/js/mofluid/certificates';
                  
                  if($_FILES['apn_pem_file']['name']!="") {
			           try {
			                $uploader = new Varien_File_Uploader('apn_pem_file'); //load class
                            $uploader->setAllowedExtensions(array('pem')); //Allowed extension for file
                            $uploader->setAllowCreateFolders(true); //for creating the directory if not exists
                            $uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
                            $uploader->setFilesDispersion(false);
                            $uploader->save($path,$_FILES['apn_pem_file']['name']); //save the file on the specified path
                            $_fieldsPushiPhone['apn_certificate'] =  $path.'/'.$_FILES['apn_pem_file']['name'];		             
                       }
                       catch(Exception $ex) {
                            $form .= '<script>Modalbox.show("<p><font color=red size=2>'.$ex->getMessage().'</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
                            break;
                       }
                  }
                  $_where = $connection->quoteInto('mofluidadmin_id =?', 1);
                  $connection->update(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush_settings'), $_fieldsPushiPhone, $_where);
                  $connection->commit();
                  
                  $form .= '<script>Modalbox.show("<p><font color=green size=2>Your Apple Push Notification Settings has been setup successfully.</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
		     }
	         catch(Exception $ex) {
	              $form .= '<script>Modalbox.show("<p><font color=red size=2>'.$ex->getMessage().'</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
	         }
	         break;
	         case 'form_push_ios_send':
	             try {
	                 $connection = Mage::getSingleton('core/resource')->getConnection('core_read'); 
                     $select = $connection->select()
                               ->from(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush_settings'), array('*')) 
                               ->where('mofluidadmin_id=?',1);
                     $Push =$connection->fetchRow($select);
                     $connection = Mage::getSingleton('core/resource')->getConnection('core_read'); 
                     $select = $connection->select()
                               ->from(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush'), array('*')) 
                               ->where('platform=?','ios')
                               ->where('mofluidadmin_id=?',1);
                    $PushIds =$connection->fetchAll($select);
                    // Put your private key's passphrase here:
                    $passphrase = $Push['apn_passphrase'];
                    
                    // Your Apple Push Notification Certificate (.pem file)
                    $apn_certificate =$Push['apn_certificate']; // '/Users/mac007/Desktop/Shashi_Desktop/MyPushTest/SimplePush/ck.pem';//

                    // Put your alert message here:
                    $message = $_POST["push_ios_message"];
                }
                catch(Exception $ex) {
                    $form .= '<script>Modalbox.show("<p><font color=red size=2>'.$ex->getMessage().'</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
                    break;
                }
                if($apn_certificate == "" ||  $apn_certificate == null || $passphrase == "" || $passphrase == null) {
                    $form .= '<script>Modalbox.show("<p><font color=red size=2>Please fill the required setup notification details before sending push notification</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
                }
                else {
                    try {
                        $ctx = stream_context_create();
                        stream_context_set_option($ctx, 'ssl', 'local_cert', $apn_certificate);
                        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                        //Open a connection to the APNS server
                        if($Push['apn_mode'] == 0 || $Push['apn_mode'] == "0") {
                            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                        }
                        else {
                            $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                        }
                        if (!$fp) {
                            $form .= '<script>Modalbox.show("<p><font color=red size=2>Failed to connect APN Server. Incorrect passphrase or pem file: '.$apn_certificate.'</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
	                        break;
                        }
                        //Create the payload body 
                        $body['aps'] = array(
                            'badge' => 1,  
                            'alert' => $message,
	                        'sound' => 'default.mp3'
	                    );
                        //Push Notification Log Contents 
                        $push_logs = '################################################'.PHP_EOL.' : Push Notification Logs : '.PHP_EOL.'################################################'.PHP_EOL;
                        $push_logs .= PHP_EOL.'Message : '.$message.PHP_EOL.PHP_EOL;
                        //Encode the payload as JSON
                        $payload = json_encode($body);
                        foreach($PushIds as $PushId) {
                            $deviceToken =  $PushId["push_token_id"];
                            $pdeviceid = $PushId["device_id"];
                            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                            // Send it to the server
                            $result = fwrite($fp, $msg, strlen($msg));
                            if (!$result)
	                            $push_logs .= 'Message not delivered to Device ID : '.$pdeviceid.' || Push Token ID: ' . $deviceToken.' '.PHP_EOL;
                            else
	                            $push_logs .= 'Message successfully delivered to Device ID : '.$pdeviceid.' || Push Token ID:' . $deviceToken.' '.PHP_EOL;
                        }
                        //Close the connection to the server
                        fclose($fp);
                        //Save Push Notification Logs
                        $fname_push_logs_file = 'mofluid/push_notification_logs/ios_logs'.Mage::getModel('core/date')->timestamp(time()).'.txt';
                        $fname_push_logs = Mage::getBaseDir().'/js/'.$fname_push_logs_file;
                        $fname_push_logs_link = $this->getJsUrl().$fname_push_logs_file;
                        $fhandle_push_logs = @fopen($fname_push_logs,"w");
                        @fwrite($fhandle_push_logs,$push_logs);
                        @fclose($fhandle_push_logs);
                        $form .= '<script>Modalbox.show("<p><font color=green size=2>Push Notification Sent Successfully. <br>See <a target=_blank href = '.$fname_push_logs_link.' >Log file</a> for more detail </font></p>",{title: "Mofluid : Push Notification Status ", width: 400});</script>';
                    }
                    catch(Exception $ex) {
                        $form .= '<script>Modalbox.show("<p><font color=red size=2>'.$ex->getMessage().'</font></p>",{title: "Mofluid : Push Notification", width: 400});</script>';
                    }
                }
                break;
	         case 'appdetails':
		         //--------- When Application Details form submit with POST request 
		         $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                         $connection->beginTransaction();
                         $dbpath = $this->getJsUrl().'mofluid/images/android/'; //desitnation directory
                         $path =Mage::getBaseDir().'/js/mofluid/images/android';
                         $__fields = array();
                         $__fieldsAndroid = array();
                         if($_FILES['apn_pem_file']['name']!="") {
			                  $uploader = new Varien_File_Uploader('apn_pem_file'); //load class
                              $uploader->setAllowedExtensions(array('pem')); //Allowed extension for file
                              $uploader->setAllowCreateFolders(true); //for creating the directory if not exists
                              $uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
                              $uploader->setFilesDispersion(false);
                              $uploader->save($path,$_FILES['apn_pem_file']['name']); //save the file on the specified path
                              $__fieldsAndroid['release_key'] = $path.'/'.$_FILES['']['name'];		             
                         }
                         $__fields['mofluid_id'] = $_POST['mofluid_id'];
                         $__fields['mofluid_key'] = $_POST['mofluid_key'];
                         $__fieldsAndroid['app_name'] = $_POST['name'];
                         $__fieldsAndroid['bundle_id'] = $_POST['bundle_id'];
                         $__fieldsAndroid['version'] = $_POST['version'];
                         $__fieldsAndroid['default_store'] = $_POST['store'];
                         $__fieldsAndroid['key_status'] = $_POST['key_type'];
                         if($_POST['key_type']=="1") {
		              $__fieldsAndroid['keystore_pswd'] = $_POST['keystore_pswd'];
                              $__fieldsAndroid['privatekey_pswd'] = $_POST['privatekey_pswd'];
			 }
			 else {
			      $__fieldsAndroid['keystore_pswd'] = $_POST['key_store_pwd'];
                              $__fieldsAndroid['privatekey_pswd'] = $_POST['key_pwd'];
                              $__fieldsAndroid['key_validity'] = $_POST['key_validity'];
                              $__fieldsAndroid['key_data'] = 'CN='.$_POST['key_common_name'].',OU='.$_POST['key_org_unit'].',O='.$_POST['key_org'].',L='.$_POST['key_city'].',ST='.$_POST['key_state'].',C='.$_POST['key_country'].'';
			 }
                         $__fieldsAndroid['valid_app'] = 1;
                         $__where = $connection->quoteInto('mofluidadmin_id =?', 1);
                         $connection->update(Mage::getSingleton('core/resource')->getTableName('mofluidadmin/mofluidadmin'), $__fields, $__where);
                         $connection->update(Mage::getSingleton('core/resource')->getTableName('mofluidadmin/mofluidadminandroid'), $__fieldsAndroid, $__where);
                         $connection->commit();
                         $form .= '<script>Modalbox.show("<p><font color=green size=2>Application details saved for android.</font></p>",{title: "Mofluid : Application Details", width: 400});</script>';
		    break;
		           
		    default:
		           echo 'Others';
		
		
	       }
		}
		    $connection = Mage::getSingleton('core/resource')->getConnection('core_read'); 
            $select1 = $connection->select()
                      ->from(Mage::getSingleton('core/resource')->getTableName('mofluidpush/mofluidpush_settings'), array('*')) 
                      ->where('mofluidadmin_id=?',1);
            $pushSettings =$connection->fetchRow($select1);
		switch($option)
		{
			 case 'push_android_gcm':
			 
			   $form .=  '<form name="form_push_android_gcm" action="'.$url.'"  method="post"  enctype="multipart/form-data"> 
			               <div class="content-header"><table cellspacing=0 ><tbody><tr><td><h3>Setup Google Cloud Messaging Account</h3></td><td class="form-buttons"><button type="button" class="scalable save" onclick="save_push_android_gcm();"><span>Save Configuration</span></button><!--button type="button" class="scalable delete" onclick="reset_push_android();"><span>Reset and Clear all Data</span></button--></td></tr></tbody></table></div>
		 		           <div class="fieldset box fieldset-wide"><table cellspacing=10 style="width:50%"><tbody>
		 		           <tr><td colspan="2"><h4 style="color:#EA7601">Google Cloud Messaging Account</h4></td></tr>
		 		           <tr><td>GCM ID* : </td><td><input type="text" style="width:99%" name="gcm_id" id="gcm_id" class="required-entry input-text" value="'.$pushSettings["gcm_id"].'"></td></tr>
		 		           <tr><td>GCM API Server Key* : </td><td><input type="text" style="width:99%" name="gcm_key" id="gcm_key" class="required-entry input-text" value="'.$pushSettings["gcm_key"].'"></td></tr>
		 		           <tr><td>Push Notification Mode : </td><td><select name="gcm_mode" style="width:99%" id="gcm_mode"><option value="1">Production</option><option value="0">Development</option></select></td></tr>
		                   <tr><td><input name="form_name" type="hidden" value="form_push_android_gcm"></td><td><input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'"/></td></tr>
		                   </tbody></table>
		                   <div id="about_gcm" style="padding-top:20px;">
		                   <p style="padding-top:20px;">To Create GCM Project and get API Server key Open <a href="https://cloud.google.com/console/project" target="_blank">Google Developers Console.</a></p>
		                   <p style="padding-top:10px;">For More detail about Google Cloud Messaging Account <a href="https://support.google.com/googleplay/android-developer/answer/2663268?hl=en" target="_blank">Click here</a></p>
		                   </div></div></form>
		                   <script>
		                   document.getElementById("gcm_mode").value = "'.$pushSettings["gcm_mode"].'";
		                   function save_push_android_gcm() {
		                        var gcm_id = document.getElementById("gcm_id").value;
		                        var gcm_key = document.getElementById("gcm_key").value;
		                        var gcm_mode = document.getElementById("gcm_mode").value;
		                        if(gcm_id == "" || gcm_id==null) {
		                             Modalbox.show("<p><font color=red size=2>Please provide you Google Cloud Messaging Account ID.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else if(gcm_key == "" || gcm_key == null) {
		                             Modalbox.show("<p><font color=red size=2>Please provide you Google Cloud Messaging API Server Key.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else if(gcm_mode == "" || gcm_mode == null ) {
		                             Modalbox.show("<p><font color=red size=2>Please select the push notification environment or mode.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else if(gcm_key.length<16){
		                             Modalbox.show("<p><font color=red size=2>Your Google Cloud Messaging Account API key is invalid.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else {
		                             document.form_push_android_gcm.submit();
		                             return true;
		                        }
		                   
		                   }
		                   </script>
		                   
		                   ';
			 break;   
			 case 'push_android_send': 
			 
			 $form .=  '<form name="form_push_android_send" action="'.$url.'"  method="post"  enctype="multipart/form-data"> 
			               <div class="content-header"><table cellspacing=0><tbody><tr><td><h3>Send Push Notification</h3></td><td class="form-buttons"><button type="button" class="scalable save" onclick="send_push_android();"><span>Send Push Notification</span></button></td></tr></tbody></table></div>
		 		           <div class="fieldset box fieldset-wide"><table cellspacing=10><tbody>
		 		           <tr><td colspan="2"><h4 style="color:#EA7601">Send Push Notification</h4></td></tr>
		 		           <tr><td>Message for Push Notification * : </td><td><textarea rows="10" cols="100" style="resize:none;" name="push_android_message" id="push_android_message" class="required-entry textarea" value=""></textarea></td></tr>
		                   <tr><td><input name="form_name" type="hidden" value="form_push_android_send"></td><td><input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'"/></td></tr>
		                   </tbody></table></div></form>

<script>
function send_push_android() {
    var gcm_msg = document.getElementById("push_android_message").value;
	
	if(gcm_msg.trim() == "") {
	    Modalbox.show("<p><font color=red size=2>Pushnotification message can\'t be left blank..</font></p>",{title: "Mofluid : Push Notification", width: 400});
		return false;
	}
	else {
        document.form_push_android_send.submit();
    }
}
</script>
';
			 
			 break;
			 case 'push_android_reset': 
			 
			 
			 break;
			 case 'push_ios_apn': 
			 $form .=  '<form name="form_push_ios_apn" action="'.$url.'"  method="post"  enctype="multipart/form-data"> 
			               <div class="content-header"><table cellspacing=0><tbody><tr><td><h3>Setup Apple Push Notification</h3></td><td class="form-buttons"><button type="button" class="scalable save" onclick="save_push_ios_apn();"><span>Save Configuration</span></button><!--button type="button" class="scalable delete" onclick="reset_push_ios();"><span>Reset and Clear all Data</span></button--></td></tr></tbody></table></div>
		 		           <div class="fieldset box fieldset-wide"><table cellspacing=10><tbody>
		 		           <tr><td colspan="2"><h4 style="color:#EA7601">Apple Push Notification</h4></td></tr>
		 		           <tr><td>Upload Certificate with Private Key (.pem file) : </td><td><input type="file" name="apn_pem_file" id="apn_pem_file" value=""><input type="hidden" value ="'.basename($pushSettings["apn_certificate"]).'" name="apn_certificate" id="apn_certificate">'.basename($pushSettings["apn_certificate"]).'</td></tr>
		 		           <tr><td>Passphrase : </td><td><input type="text" name="apn_passphrase" id="apn_passphrase" class="required-entry input-text" value="'.$pushSettings["apn_passphrase"].'"></td></tr>
		                   <tr><td>Push Notification Mode : </td><td><select name="apn_mode" id="apn_mode"><option value="1">Production</option><option value="0">Development</option></select></td></tr>
		 		           <tr><td><input name="form_name" type="hidden" value="form_push_ios_apn"></td><td><input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'"/></td></tr>
		                   </tbody></table></div></form>
		                   <script>
		                   document.getElementById("apn_mode").value = "'.$pushSettings["apn_mode"].'";
		                   function save_push_ios_apn() {
		                        var apn_certificate = document.getElementById("apn_certificate").value;
		                        var apn_passphrase = document.getElementById("apn_passphrase").value;
		                        var apn_mode = document.getElementById("apn_mode").value;
		                        if((apn_certificate == "" || apn_certificate==null) && document.form_push_ios_apn.apn_pem_file.value=="") {
		                             Modalbox.show("<p><font color=red size=2>Please upload your Apple Push Notification Certificate with Private Key (.pem file).</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else if(apn_passphrase == "" || apn_passphrase == null) {
		                             Modalbox.show("<p><font color=red size=2>Please provide APN Passpharse.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else if(apn_mode == "" || apn_mode == null ) {
		                             Modalbox.show("<p><font color=red size=2>Please select the push notification environment or mode.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else if(apn_passphrase.length<6){
		                             Modalbox.show("<p><font color=red size=2>Your APN passphrase is invalid.</font></p>",{title: "Mofluid : Push Notification", width: 400});
		                             return false;
		                        }
		                        else {
		                             document.form_push_ios_apn.submit();
		                             return true;
		                        }
		                   
		                   }
		                   </script>
		                   ';
			 
			 break;
			 case 'push_ios_send': 
			 
			  $form .=  '<form name="form_push_ios_send" action="'.$url.'"  method="post"  enctype="multipart/form-data"> 
			               <div class="content-header"><table cellspacing=0><tbody><tr><td><h3>Send Push Notification</h3></td><td class="form-buttons"><button type="button" class="scalable save" onclick="send_push_ios();"><span>Send Push Notification</span></button></td></tr></tbody></table></div>
		 		           <div class="fieldset box fieldset-wide"><table cellspacing=10><tbody>
		 		           <tr><td colspan="2"><h4 style="color:#EA7601">Send Push Notification</h4></td></tr>
		 		           <tr><td>Message for Push Notification * : </td><td><textarea rows="10" cols="100" style="resize:none;" name="push_ios_message" id="push_ios_message" class="required-entry textarea" maxlength="150"  value=""></textarea></td></tr>
		                   <tr><td><input name="form_name" type="hidden" value="form_push_ios_send"></td><td><input name="form_key" type="hidden" value="'.Mage::getSingleton('core/session')->getFormKey().'"/></td></tr>
		                   </tbody></table></div></form>
		                   <script>
		                        function send_push_ios () {
		                        var gcm_msg = document.getElementById("push_ios_message").value;
	
	if(gcm_msg.trim() == "") {
	    Modalbox.show("<p><font color=red size=2>Pushnotification message can\'t be left blank..</font></p>",{title: "Mofluid : Push Notification", width: 400});
		return false;
	}
	else {
        document.form_push_ios_send.submit();
    }
		                             
		                        }
		                   </script>
		                   ';
			 
			 break;
			 case 'push_ios_reset': 
			 
			 break;
			 
			 default:
              $form = '<h3 style="color:#EA7601">Push Notification </h3><p>Push notification allows an app to notify you of new messages or events without the need to actually open the application, similar to how a text message will make a sound and pop up on your screen. This is a great way for apps to interact with us in the background, whether it be a game notifying us of some event occurring in our game world or simply the iPad\'s mail application beeping as a new message appears in our inbox.</p><div id="push_inform_msg" style="text-align: center;font-weight: bold;font-size: 11pt;padding-top: 5%;color:#EA7601"> Configure your Push Settings and send notification to the devices. Select Subcategory from the left menu and go for Push Notification.</div> ';			
         }
        return $form;
    }
}    
