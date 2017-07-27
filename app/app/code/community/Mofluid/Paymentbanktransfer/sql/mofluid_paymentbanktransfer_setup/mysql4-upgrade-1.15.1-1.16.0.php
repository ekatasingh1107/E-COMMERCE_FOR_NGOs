<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DELETE FROM {$this->getTable('mofluid_paymentbanktransfer/payment')}  WHERE payment_method_id=5;

INSERT INTO {$this->getTable('mofluid_paymentbanktransfer/payment')} 
(
  `payment_method_id`,
  `payment_method_title`,
  `payment_method_code`,
 `payment_method_order_code`,
  `payment_method_status`,
  `payment_method_mode`
)
VALUES (
 5,
 'Bank Transfer',
 'banktransfer',
 'banktransfer',
 0,
 0
);
");
$installer->endSetup();
?>
