<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mofluidpush/mofluidpush')};
DROP TABLE IF EXISTS {$this->getTable('mofluidpush/mofluidpush_settings')};

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluidpush/mofluidpush')} (
  `mofluidadmin_id` int(11) unsigned NOT NULL,
  `push_id` int(11) unsigned NOT NULL auto_increment,
  `device_id` varchar(511) NOT NULL default '',
  `push_token_id` varchar(511) NOT NULL default '',
  `platform` varchar(127) NOT NULL default '',
  `description` varchar(1023) NOT NULL default '',
  `app_name` varchar(127) NOT NULL default '',
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`push_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluidpush/mofluidpush_settings')} (
  `mofluidadmin_id` int(11) unsigned NOT NULL auto_increment,
  `gcm_mode` varchar(127) NOT NULL default '',
  `gcm_id` varchar(511) NOT NULL default '',
  `gcm_key` varchar(1023) NOT NULL default '',
  `apn_mode` varchar(127) NOT NULL default '',
  `apn_certificate` varchar(511) NOT NULL default '',
  `apn_passphrase` varchar(127) NOT NULL default '',
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`mofluidadmin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, sendbuildmode) VALUES ('Mofluid_Mofluidpush','mofluidpush_setup','{$this->getTable('mofluidpush/mofluidpush')}','1.14.0',0);

INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, sendbuildmode) VALUES ('Mofluid_Mofluidpush','mofluidpush_setup','{$this->getTable('mofluidpush/mofluidpush_settings')}','1.14.0',1);

INSERT INTO {$this->getTable('mofluidpush/mofluidpush_settings')} (mofluidadmin_id) VALUES(1);
");
$installer->endSetup();
?>
