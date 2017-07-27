<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mofluid_paymentauthorize/payment')};

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_paymentauthorize/payment')} (
  `payment_method_id` int(11) unsigned NOT NULL, 
  `payment_method_title` varchar(63) default '',
  `payment_method_code` varchar(63) default '',
  `payment_method_order_code` varchar(63) default '',
  `payment_method_status` int(11) NOT NULL default 0,
  `payment_method_mode` int(11) NOT NULL default 0,
  `payment_method_account_id` varchar(63) NOT NULL default '',
  `payment_method_account_key` varchar(63) NOT NULL default '',
  `payment_account_email` varchar(63) default '',
  PRIMARY KEY (`payment_method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('mofluid_paymentauthorize/payment')} (
  `payment_method_id`,
  `payment_method_title`,
  `payment_method_code`,
  `payment_method_order_code`,
  `payment_method_status`,
  `payment_method_mode`
)
VALUES (
 2, 
 'Authorize.Net',
 'authorize',
 'authorizenet',
 0,
 0
);
INSERT INTO {$this->getTable('adminmofluid/mofluidresource')} (module, resource_name, resource, version, scope, sendbuildmode) VALUES ('Mofluid_Payment','mofluid_payment_setup','{$this->getTable('mofluid_paymentauthorize/payment')}','1.14.1','Payment',1);

");
$installer->endSetup();
?>
