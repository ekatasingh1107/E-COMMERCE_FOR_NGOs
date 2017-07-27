<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DELETE FROM {$this->getTable('mofluid_paymentcod/payment')}  WHERE payment_method_id=1;

INSERT INTO {$this->getTable('mofluid_paymentcod/payment')} 
(
  `payment_method_id`,
  `payment_method_title`,
  `payment_method_code`,
  `payment_method_order_code`, 
  `payment_method_status`,
  `payment_method_mode`
)
VALUES (
 1,
 'Cash On Delivery',
 'cod',
 'cashondelivery',
 0,
 0
);

");
$installer->endSetup();
?>
