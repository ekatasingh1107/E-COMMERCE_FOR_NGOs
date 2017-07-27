<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('mofluid_mofluidcache/mofluidcache')};
CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_mofluidcache/mofluidcache')} (
  `mofluid_cs_id` int(11) unsigned NOT NULL, 
  `mofluid_cs_status` int(11) NOT NULL default 0,
  `mofluid_cs_accountid` varchar(63) NOT NULL default '',
  `mofluid_cs_extras` varchar(63) NOT NULL default '',
  PRIMARY KEY (`mofluid_cs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



 INSERT INTO {$this->getTable('mofluid_mofluidcache/mofluidcache')} (
  `mofluid_cs_id`,
  `mofluid_cs_status`,
  `mofluid_cs_accountid`,
  `mofluid_cs_extras`
 )
VALUES (
 25, 
 0,
 '',
 ''
);
INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluidextra_Mofluidga','mofluidextra_mofluidga_setup','{$this->getTable('mofluid_mofluidcache/mofluidcache')}','1.0.0','Cache',1);

");
$installer->endSetup();
?>
