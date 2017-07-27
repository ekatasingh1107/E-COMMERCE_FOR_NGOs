<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('adminmofluid/mofluidresource')};

CREATE TABLE IF NOT EXISTS {$this->getTable('adminmofluid/mofluidresource')} (
  `resourceid` int(11) unsigned NOT NULL AUTO_INCREMENT, 
  `module` varchar(63) default '',
  `resource_name` varchar(63) default '',
  `resource` varchar(63) NOT NULL default '',
  `version` varchar(16) NOT NULL default '0',
  `scope` varchar(32) NOT NULL default 'Module',
  `sendbuildmode` int(16) NOT NULL default 0,
  PRIMARY KEY (`resourceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluid_Adminmofluid','adminmofluid_setup','{$this->getTable('adminmofluid/mofluidresource')}','1.11.2','Mofluid Resorce',0);
INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluid_MofluidApi','','','1.11.2','Mofluid API',0);
");
$installer->endSetup();
?>
