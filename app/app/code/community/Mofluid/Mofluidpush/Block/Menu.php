<?php
class Mofluid_Mofluidpush_Block_Menu extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
		$url =$this->helper('core/url')->getCurrentUrl();
		$arr = explode('/',$url);
		$pos = count($arr)-1;
		$option2 = ''.$arr[$pos];
		$name = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->name;
          $type = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->type;
          $version = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->version;
          $title = $name.' '.$type.' '.$version;
           if(trim($title) == '' || $title == null) {
               $title = 'Mofluid';
           }
		$menu = '<div class="side-col" id="page:left"><h3>'.$title.'</h3>
		         <ul id="system_config_tabs" class="tabs config-tabs">
		         <li><dl><dt class=label>Android Push Notification</dt>
		         <dd><a href="push_android_gcm" class="tab-item-link" id="push_android_gcm" ><span>Setup Google Cloud Account</span></a></dd>
		         <dd><a href="push_android_send" class="tab-item-link" id="push_android_send"><span>Send Push Notification </span></a></dd>
                 </dl></li>
                 <li><dl><dt class=label>iPhone Push Notification</dt>
		         <dd><a href="push_ios_apn" class="tab-item-link" id="push_ios_apn" ><span>Setup Apple Push Notification</span></a></dd>
		         <dd><a href="push_ios_send" class="tab-item-link" id="push_ios_send"><span>Send Push Notification</span></a></dd>
                 </dl></li></ul></div>
                      ';
		$menu .= '<script>
		            var d = document.getElementById("'.$option2.'");
                    d.className = d.className + " active";
              </script>';
        return $menu;
    }
}    
