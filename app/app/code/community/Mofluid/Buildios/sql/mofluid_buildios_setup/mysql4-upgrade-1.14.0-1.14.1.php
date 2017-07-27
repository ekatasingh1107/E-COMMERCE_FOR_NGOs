<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mofluid_buildios/accounts')};
DROP TABLE IF EXISTS {$this->getTable('mofluid_buildios/buildconfig')};
DROP TABLE IF EXISTS {$this->getTable('mofluid_buildios/assets')};

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_buildios/accounts')} (
  `mofluid_admin_id` int(11) unsigned NOT NULL, 
  `mofluid_platform_id` int(11) unsigned NOT NULL,
  `mofluid_id` varchar(127) NOT NULL default '',
  `mofluid_password` varchar(127) NOT NULL default '',
  `platform` varchar(63) NOT NULL default '',
  `phonegap_build_id` varchar(127) NOT NULL default '',
  `phonegap_build_password` varchar(127) NOT NULL default '',
  `certificate_type` varchar(63) NOT NULL default '',
  `certificate_path` varchar(511) NOT NULL default '',
  `certificate_passpharse` varchar(63) NOT NULL default 1,
  `provisioning_profile` varchar(511) NOT NULL default '',
  `release_key_type` varchar(63) NOT NULL default '',
  `release_privatekey_password` varchar(127) NOT NULL default '',
  `release_keystore_password` varchar(127) NOT NULL default '',
  `release_key_validity` varchar(63) NOT NULL default '10000',
  `release_key_data` varchar(2048) NOT NULL default '',
  `build_url` varchar(2048) NOT NULL default 'http://125.63.92.59/Mofluid/Production1.14.1/Platforms',
   PRIMARY KEY (`mofluid_platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_buildios/buildconfig')} (
  `mofluid_admin_id` int(11) unsigned NOT NULL,
  `mofluid_platform_id` int(11) unsigned NOT NULL,
  `mofluid_app_name` varchar(127) default '',
  `mofluid_app_bundleid` varchar(127) default '',
  `mofluid_app_platform` varchar(63) NOT NULL default '',
  `mofluid_app_version` varchar(63) default '',
  `mofluid_default_store` varchar(63) default '',
  `mofluid_default_currency` varchar(63) default '',
  `mofluid_default_theme` varchar(63) default '',
  PRIMARY KEY (`mofluid_platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_buildios/assets')} (
  `mofluid_admin_id` int(11) unsigned NOT NULL, 
  `mofluid_assets_id` int(11) unsigned NOT NULL, 
  `mofluid_platform` varchar(63) default '',
  `mofluid_assets_type` varchar(63) default '',
  `mofluid_assets_name` varchar(127) NOT NULL default 1,
  `mofluid_assets_value` varchar(2048) NOT NULL default '',
  `mofluid_assets_isrequired` int(11) NOT NULL default 0,
  `mofluid_assets_heptext` varchar(127) default '',
  PRIMARY KEY (`mofluid_assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluid_Buildios','mofluid_buildios_setup','{$this->getTable('mofluid_buildios/accounts')}','1.14.1','iOS',1);
INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluid_Buildios','mofluid_buildios_setup','{$this->getTable('mofluid_buildios/buildconfig')}','1.14.1','iOS',1);
INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluid_Buildios','mofluid_buildios_setup','{$this->getTable('mofluid_buildios/assets')}','1.14.1','iOS',1);

INSERT INTO   {$this->getTable('mofluid_buildios/accounts')} VALUES ( 1, 1, '', 'freemofluid', 'ios', '', '', 1, '', '', '', 1, '', '', '', '', 'http://125.63.92.59/Mofluid/Production1.14.1/Platforms');
INSERT INTO   {$this->getTable('mofluid_buildios/accounts')} VALUES ( 1, 2, '', 'freemofluid', 'android', '', '', 1, '', '', '', 1, '', '', '10000', '', 'http://125.63.92.59/Mofluid/Production1.14.1/Platforms');

INSERT INTO  {$this->getTable('mofluid_buildios/buildconfig')} VALUES ( 1, 1, 'Mofluid', 'com.mofluid.appname', 'ios', '1.0.0', '', '', ''); 
INSERT INTO  {$this->getTable('mofluid_buildios/buildconfig')} VALUES ( 1, 2, 'Mofluid', 'com.mofluid.appname', 'android', '1.0.0', '', '', ''); 


INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100001, 'ios', 'icon', 'icon small (29x29px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_small.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100002, 'ios', 'icon', 'icon 40 (40x40px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_40.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100003, 'ios', 'icon', 'icon 50 (50x50px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_50.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100004, 'ios', 'icon', 'icon 57 (57x57px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100005, 'ios', 'icon', 'icon 60 (60x60px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_60.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100006, 'ios', 'icon', 'icon 72 (72x72px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_72.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100007, 'ios', 'icon', 'icon 76 (76x76px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_76.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100008, 'ios', 'icon', 'icon small @2x (58x58px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_small_2x.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100009, 'ios', 'icon', 'icon 40 @2x (80x80px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_40_2x.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100010, 'ios', 'icon', 'icon 50 @2x (100x100px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_50_2x.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100011, 'ios', 'icon', 'icon 57 @2x (114x114px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_2x.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100012, 'ios', 'icon', 'icon 60 @2x (120x120px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_60_2x.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100013, 'ios', 'icon', 'icon 72 @2x (144x144px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_72_2x.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 100014, 'ios', 'icon', 'icon 76 @2x (152x152px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/icon_76_2x.png', 1, '');

INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 200001, 'ios', 'artwork', 'iTunesArtwork (512x512px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/itunesartwork.png', 0, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 200002, 'ios', 'artwork', 'iTunesArtwork @2x 76 @2x (1024x1024px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/icons/itunesartwork_2x.png', 0, '');

INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300001, 'ios', 'splash', 'screen_iphone_portrait (320x480px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_iphone.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300002, 'ios', 'splash', 'screen_iphone_portrait @2x (640x960px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_2x_iphone.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300003, 'ios', 'splash', 'screen_ipad_portrait (768x1024px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_Portrait_ipad.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300004, 'ios', 'splash', 'screen_ipad_portrait @2x (1536x2048px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_Portrait_2x_ipad.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300005, 'ios', 'splash', 'screen_ipad_landscape(1024x768px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_Landscape_ipad.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300006, 'ios', 'splash', 'screen_ipad_landscape @2x (2048x1536px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_Landscape_2x_ipad.png', 1, '');
INSERT INTO  {$this->getTable('mofluid_buildios/assets')} VALUES ( 1, 300007, 'ios', 'splash', 'screen_iphone_default (640x1136px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/iOS/splash/Default_568h_2x_iphone.png', 1, '');

INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 500001, 'android', 'icon', 'Icon drawable (96x96px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/icons/drawable.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 500002, 'android', 'icon', 'Icon hdpi (72x72px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/icons/drawable_hdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 500003, 'android', 'icon', 'Icon mdpi (48x48px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/icons/drawable_mdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 500004, 'android', 'icon', 'Icon ldpi (36x36px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/icons/drawable_ldpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 500005, 'android', 'icon', 'Icon xhdpi (96x96px)', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/icons/drawable_xhdpi.png', 1, '');

INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600001, 'android', 'splash', 'drawable_port_hdpi [Size : 480x800 & Orientation : Portrait]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_port_hdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600002, 'android', 'splash', 'drawable_port_ldpi [Size : 200x320 & Orientation : Portrait]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_port_ldpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600003, 'android', 'splash', 'drawable_port_mdpi[Size : 320x480 & Orientation : Portrait]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_port_mdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600004, 'android', 'splash', 'drawable_port_xhdpi [Size : 720x1280 & Orientation : Portrait]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_port_xhdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600005, 'android', 'splash', 'drawable_land_hdpi [Size : 800x480 & Orientation : Landscape]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_land_hdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600006, 'android', 'splash', 'drawable_land_ldpi [Size : 320x200 & Orientation : Landscape]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_land_ldpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600007, 'android', 'splash', 'drawable_land_mdpi [Size : 480x320 & Orientation : Landscape]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_land_mdpi.png', 1, '');
INSERT INTO {$this->getTable('mofluid_buildios/assets')} VALUES (1, 600008, 'android', 'splash', 'drawable_land_xhdpi [Size : 1280x720 & Orientation : Landscape]', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/splash/drawable_land_xhdpi.png', 1, '');
");
$installer->endSetup();
?>
