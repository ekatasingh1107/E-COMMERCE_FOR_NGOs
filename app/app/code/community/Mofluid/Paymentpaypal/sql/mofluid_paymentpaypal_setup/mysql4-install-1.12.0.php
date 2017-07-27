<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DELETE FROM {$this->getTable('mofluid_paymentpaypal/payment')}  WHERE payment_method_id=3;

INSERT INTO {$this->getTable('mofluid_paymentpaypal/payment')} 
(
  `payment_method_id`,
  `payment_method_title`,
  `payment_method_code`,
  `payment_method_status`,
  `payment_method_mode`
)
VALUES (
 3, 
 'Paypal Standard',
 'paypal',
 0,
 0
);
");
$installer->endSetup();
?>
