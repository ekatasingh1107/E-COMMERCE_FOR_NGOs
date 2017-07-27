<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_core')};
DROP TABLE IF EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')};
DROP TABLE IF EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')};
DROP TABLE IF EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')};

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_core')} (
  `mofluid_theme_id` int(11) unsigned NOT NULL, 
  `mofluid_store_id` int(11) NOT NULL default 1, 
  `mofluid_theme_code` varchar(63) default '',
  `mofluid_theme_title` varchar(63) default '',
  `mofluid_theme_status` int(11) NOT NULL default 1,
  `mofluid_theme_custom_footer` varchar(2048) NOT NULL default '',
  `mofluid_display_catsimg` int(11) NOT NULL default 0,
  `mofluid_theme_display_custom_attribute` int(11) NOT NULL default 0,
  `mofluid_theme_banner_image_type` varchar(63) NOT NULL default '0',
  PRIMARY KEY (`mofluid_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} (
  `mofluid_theme_id` int(11) unsigned NOT NULL, 
  `mofluid_store_id` int(11) NOT NULL default 1, 
  `mofluid_message_id` int(16) unsigned NOT NULL,
  `mofluid_message_type` varchar(63) default '',
  `mofluid_message_label` varchar(63) default '',
  `mofluid_message_value` varchar(255) default '',
  `mofluid_message_helptext` varchar(511) default '',
  `mofluid_message_helplink` varchar(255) default '',
  `mofluid_message_isrequired` int(11) NOT NULL default 0,
  PRIMARY KEY (`mofluid_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} (
  `mofluid_theme_id` int(11) unsigned NOT NULL, 
  `mofluid_store_id` int(11) NOT NULL default 1, 
  `mofluid_color_id` int(16) unsigned NOT NULL,
  `mofluid_color_type` varchar(63) default '',
  `mofluid_color_label` varchar(63) default '',
  `mofluid_color_value`varchar(63) default '',
  `mofluid_color_helptext` varchar(511) default '',
  `mofluid_color_helplink` varchar(255) default '',
  `mofluid_color_isrequired` int(11) NOT NULL default 0,
  PRIMARY KEY (`mofluid_color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} (
  `mofluid_theme_id` int(11) unsigned NOT NULL, 
  `mofluid_store_id` int(11) NOT NULL default 0, 
  `mofluid_image_id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `mofluid_image_type`  varchar(63) default '',
  `mofluid_image_label`  varchar(63) default '',
  `mofluid_image_value`  varchar(511) default '',
  `mofluid_image_helptext`  varchar(511) default '',
  `mofluid_image_helplink`  varchar(255) default '',
  `mofluid_image_isrequired` int(11) NOT NULL default 0,
  `mofluid_image_sort_order` int(11) NOT NULL default 0,
  `mofluid_image_isdefault` int(11) NOT NULL default 0,
  `mofluid_image_action`varchar(511)  NOT NULL default '',
  `mofluid_image_data` varchar(511)  NOT NULL default '',
    PRIMARY KEY (`mofluid_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_core')} (`mofluid_theme_id`, `mofluid_theme_code`, `mofluid_theme_title`, `mofluid_theme_status`, `mofluid_theme_display_custom_attribute`) VALUES ( 1, 'elegant', 'Elegant', 1, 0);

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100001, 'button', 'Home', 'Home', 'This text will displayed at home button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100002, 'button', 'My Account', 'My Account', 'This text will displayed at my account button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100003, 'button', 'Edit Profile', 'Edit Profile', 'This text will displayed at Edit Profile button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100004, 'button', 'My Orders', 'My Orders', 'This text will displayed at My Orders button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100005, 'button', 'Sign In', 'Sign In', 'This text will displayed at Sign In button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100006, 'button', 'Sign Out', 'Sign Out', 'This text will displayed at Sign Out button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100007, 'button', 'Login', 'Login', 'This text will displayed at Login button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100008, 'button', 'Proceed', 'Proceed', 'This text will displayed at Proceed button.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100009, 'button', 'Update', 'Update', 'This text will displayed at Update button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100010, 'button', 'Change Password', 'Change Password', 'This text will displayed at Change Password button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100011, 'button', 'Forgot Password', 'Forgot Password', 'This text will displayed at Forgot Password button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100012, 'button', 'Create An Account', 'Create An Account', 'This text will displayed at Create An Account button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100013, 'button', 'Details', 'Details', 'This text will displayed at product detail page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100014, 'button', 'Add to Cart', 'Add to Cart', 'This text will displayed at Add to Cart Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100015, 'button', 'Apply Coupon', 'Apply Coupon', 'This text will displayed at Apply Coupon Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100016, 'button', 'Cancel Coupon', 'Cancel Coupon', 'This text will displayed at Cancel Coupon Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100017, 'button', 'Checkout', 'Checkout', 'This text will displayed at Checkout Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100018, 'button', 'Continue Shopping', 'Continue Shopping', 'This text will displayed at Continue Shopping Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100019, 'button', 'Confirm Proceed', 'Confirm Proceed', 'This text will displayed at Confirm Proceed Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100020, 'button', 'Submit', 'Submit', 'This text will displayed at Submit Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100021, 'button', 'Retrive Your Password', 'Retrive Your Password', 'This text will displayed at Retrive Your Password Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100022, 'button', 'Click Here', 'Click Here', 'This text will displayed at Click Here Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100023, 'button', 'Continue', 'Continue', 'This text will displayed at Continue Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100024, 'button', 'Sign Up Now', 'Sign Up Now', 'This text will displayed at Sign Up Now Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100025, 'button', 'Edit Information', 'Edit Information', 'This text will displayed at Edit Information Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100026, 'button', 'Change Account Password', 'Change Account Password', 'This text will displayed at Change Account Password Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100027, 'button', 'Get Price', 'Get Price', 'This text will displayed on Get Price Button of Product Description Page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 100028, 'button', 'Close', 'Close', 'This text will displayed to close popup', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200001, 'alert', 'Username Validation for Blank', 'Username can\'t be left blank.', 'This message will be displayed when username left blank during login or signup', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200002, 'alert', 'Username Validation for Wrong attempt', 'Please provide a valid email address as Username.', 'This message will be displayed when wrong login attempt', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200003, 'alert', 'Customer already exist', 'Customer already exist.', 'This message will be displayed when signup with existing email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200004, 'alert', 'Password Validation for minimum length', 'Password should be atleast 6 characters long.', 'This message will be displayed when password has not atleast 6 characters.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200005, 'alert', 'Invalid Username & password message', 'The username or password you entered is incorrect', 'This message will be displayed when invalid username and password is used', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200006, 'alert', 'Login Success Message', 'Welcome {{username}} you have logged in successfully.', 'This message will be displayed when succesfull login. {{username}} will replace with actual name of the User', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200007, 'alert', 'Server Not Responding', 'Server is busy please try after some time.', 'This message will be displayed when no response from the server during maintainence or site down', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200008, 'alert', 'Sign out Message', 'You have successfully log Out.', 'This message will be displayed when user log out', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200009, 'alert', 'Out of stock message', 'This product is out of stock.', 'This message will be displayed when user try to add out of stock product on cart', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200010, 'alert', 'Country Blank', 'Please enter the country', 'This message will be displayed when country field leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200011, 'alert', 'Firstname Blank', 'Please enter the firstname', 'This message will be displayed when firstname field leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200012, 'alert', 'Firstname Invalid', 'Please enter the valid firstname', 'This message will be displayed when firstname field leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200013, 'alert', 'Lastname Blank', 'Please enter the lastname', 'This message will be displayed when lastname leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200014, 'alert', 'Lastname Invalid', 'Please enter the valid lastname', 'This message will be displayed when lastname leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200015, 'alert', 'Contact no blank', 'Please enter the phone number', 'This message will be displayed when contact number leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200016, 'alert', 'Empty Address', 'Please enter the address', 'This message will be displayed when address leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200017, 'alert', 'Empty City Message', 'Please enter the city', 'This message will be displayed when city leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200018, 'alert', 'Empty Country Message', 'Please enter the country', 'This message will be displayed when country leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200019, 'alert', 'Empty State Message', 'Please enter the state', 'This message will be displayed when state leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200020, 'alert', 'Empty Zipcode Message', 'Please enter the zipcode', 'This message will be displayed when zipcode leaves blank.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200021, 'alert', 'Invalid Address', 'Please enter the valid address', 'This message will be displayed when address is invalid', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200022, 'alert', 'Invalid City Message', 'Please enter the valid city', 'This message will be displayed when city is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200023, 'alert', 'Invalid State Message', 'Please enter the valid state', 'This message will be displayed when state is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200024, 'alert', 'Invalid Country Message', 'Please enter the valid country', 'This message will be displayed when country is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200025, 'alert', 'Invalid Zipcode Message', 'Please enter the valid zipcode', 'This message will be displayed when zipcode is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200026, 'alert', 'Invalid Contact Message', 'Please enter the valid contact number', 'This message will be displayed when contact number is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200027, 'alert', 'Email Not Registered', 'This email id is not registered with us.', 'This message will be displayed when user try to get forget password link using non existing email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200028, 'alert', 'Empty Email', 'Email Address can\'t be left blank.', 'This message will be displayed when user left email field blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200029, 'alert', 'Invalid Email', 'Please provide a valid email address.', 'This message will be displayed when user provide invalid email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200030, 'alert', 'Email Already Registered', 'This email id is already registered with us.', 'This message will be displayed when user provide invalid email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200031, 'alert', 'Forget Password Action Message', 'You will receive an email with a link to reset your password.', 'This message will be displayed when user successfully get response to reset the password', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200032, 'alert', 'Confirm Password Different', 'Password doesn\'t match.', 'This message will be displayed when confirm password does not match', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200033, 'alert', 'Change Password Success', 'Your password has been changed successfully', 'This message will be displayed when password has been changed successfully.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200034, 'alert', 'Register Successfully Message', 'You have registered successfully.', 'This message will be displayed when user succesfully registered with us.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200035, 'alert', 'Profile address update', 'Default Billing and Shipping Address has been Updated.', 'This message will be displayed when user succesfully updates his address.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200036, 'alert', 'Payment Mode', 'Please select mode of payment.', 'This message will be displayed when user checkout without selecting any payment mode.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200037, 'alert', 'Configurable Options Validation', 'Please Select option from', 'This message will be displayed when user add a product to cart without selecting the required option listed in detail page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200038, 'alert', 'Shipping Mode', 'Please select shipping method.', 'This message will be displayed when user checkout without selecting any shipping mode.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200039, 'alert', 'Item Remove Message', 'Please remove this item', 'This message will be displayed when out of stock product is going for checkout', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200040, 'alert', 'When same product added to cart', 'This product is already added in your cart. Please visit cart to change the quantity of the product.', 'This message will be displayed when same product added to cart', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200041, 'alert', 'When a product removed from cart', '{{product}} has been removed', 'This message will be displyaed when a product is removed from cart. {{product}} will replace with actual product name.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200042, 'alert', 'Quantity of Product increased more than stock', 'This Product is having only {{quantity}} quantities in stock', 'This message will be displayed when quantity of same product increased more than stock. {{quantity}} will replace with actual no of quantity available for the product', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200043, 'alert', 'Product Out of Stock', 'Sorry Product {{product}} is Out Of Stock. Please remove the product from cart.', 'This message will be displayed when out of stock product is going for checkout process from the cart. {{product}} will replace with actual product name.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200044, 'alert', 'Limited Quantity is in stock for Product', 'Sorry for Product {{product}} we have only {{quantity}} quanity in Stock please select less quantity.', 'This message will be displayed when limited quantity of the product is avialable for checkout process from the cart. {{product}} will replace with actual product name and {{quantity}} will replace with actual available quantity.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200045, 'alert', 'Order Success Message', 'The order has been placed successfully', 'This message will be displayed when order is placed succeessfully.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200046, 'alert', 'Failure Message for address save', 'Sorry, Address are not Saved.Please Retry', 'This message will be displyaed when failure occurred during address save.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200047, 'alert', 'Message when empty Search', 'Please provide any keyword for search.', 'This message will be displayed when empty search is performed.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200048, 'alert', 'Coupon Applied', 'Coupon code {{coupon}} was applied.', 'This message will be displayed when coupon is applied successfully. {{coupon}} will replace with actual coupon code', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200049, 'alert', 'Coupon Failed', 'Coupon code {{coupon}} is not valid.', 'This message will be displayed when invalid coupon is applied. {{coupon}} will replace with actual coupon code', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200050, 'alert', 'Coupon Canceled', '{{coupon}} Coupon Canceled', 'This message will be displayed when coupon code is canceled. {{coupon}} will replace with actual coupon code', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200051, 'alert', 'Required Validation', 'Please fill all the required entries', 'This message will be displayed when user skip any required field detail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200052, 'alert', 'Check Email', 'Please check your Email address.', 'This text will appear when forget password or any link is sent to the registered mail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 200053, 'alert', 'Try Again', 'There was a temporary error please try again later.', 'This text will appear when the app can not recieve the data from the api due to error or site down.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300001, 'text', 'Display Application Name', '', 'If blank, Name of your application will used', '', 0);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300002, 'text', 'Support Text', 'Need help? 24 X 7 support', 'Used when default footer is selected', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300003, 'text', 'Policy Text', 'Policies', 'Used when default footer is selected', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300004, 'text', 'Shop By Departments', '', 'If blank, text Shop by department will used', '', 0);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300005, 'text', 'Personal Information', 'Personal Information', 'This text will display as a heading on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300006, 'text', 'Billing Address', 'Billing Address', 'This text will display as a heading on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300007, 'text', 'No Default Billing Address Found', 'No Default Billing Address Found', 'This text will display on my profile page when no billing address is set to default', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300008, 'text', 'Shipping Address', 'Shipping Address', 'This text will display as a heading on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300009, 'text', 'No Default Shipping Address', 'No Default Shipping Address', 'This text will display on my profile page when no shipping address is set to default', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300010, 'text', 'Full Name', 'Full Name', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300011, 'text', 'First Name', 'First Name', 'This text will display on checkout form', '',  1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300012, 'text', 'Last Name', 'Last Name', 'This text will display on checkout form', '',  1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300013, 'text', 'Change Account Password', 'Change Account Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300014, 'text', 'Old Password', 'Old Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300015, 'text', 'New Password', 'New Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300016, 'text', 'Confirm Password', 'Confirm Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300017, 'text', 'Address', 'Address', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300018, 'text', 'Member Since', 'Member Since', 'This text will display on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300019, 'text', 'Edit Information', 'Edit Information', 'This text will display on profile edit page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300020, 'text', 'Email Address', 'Email Address', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300021, 'text', 'Contact No', 'Contact No', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300022, 'text', 'City', 'City', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300023, 'text', 'State', 'State', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300024, 'text', 'Country', 'Country', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300025, 'text', 'Zipcode', 'Zipcode', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300026, 'text', 'You have no Orders', 'You have no Orders', 'This text will display on myorder page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300027, 'text', 'Total Orders', 'You have total {{totalorder}} Orders', 'This text will display on myorder page. {{totalorder}} will replace with total number of orders. ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300028, 'text', 'Order', 'Order', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300029, 'text', 'Orders', 'Orders', 'This text will display on order list page', '', 1);    
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300030, 'text', 'Order Id', 'Order Id', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300031, 'text', 'Status', 'Status', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300032, 'text', 'Products', 'Products', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300033, 'text', 'Payment Method', 'Payment Method', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300034, 'text', 'Shipping Method', 'Shipping Method', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300035, 'text', 'Total Amount', 'Total Amount', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300036, 'text', 'SKU', 'SKU', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300037, 'text', 'Name', 'Name', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300038, 'text', 'Qty', 'Qty', 'This text will display on order list page', '', 1); 
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300039, 'text', 'Price', 'Price', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300040, 'text', 'Total', 'Total', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300041, 'text', 'Item', 'Item', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300042, 'text', 'Shipping Amount', 'Shipping Amount', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300043, 'text', 'Grand Total', 'Grand Total', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300044, 'text', 'Search by Name', 'Search by Name', '', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300045, 'text', 'Search Result Text', 'Showing search result for {{serachstring}}', 'This text will display on search page. {{searchstring}} will replace with actual search string', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300046, 'text', 'No Search result found', 'No such product found', 'This text will display on search page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300047, 'text', 'Position (Sort Type)', 'Position', 'This text will display on product listing page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300048, 'text', 'Name (Sort Type)', 'Name', 'This text will display on product listing page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300049, 'text', 'Price (Sort Type)', 'Price', 'This text will display on product listing page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300050, 'text', 'Product Description', 'Product Description', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300051, 'text', 'Description', 'Description', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300052, 'text', 'Availability', 'Availability', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300053, 'text', 'Product SKU', 'Product SKU', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300054, 'text', 'Shipping Charge', 'Shipping Charge', 'This text will display on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300055, 'text', 'In Stock', 'In Stock', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300056, 'text', 'Out of Stock', 'Out of Stock', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300057, 'text', 'Product Options', 'Product Options', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300058, 'text', 'Cart Empty Text', 'The Cart is empty now', 'This text will display on cart page when cart is empty.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300059, 'text', 'Discount Codes', 'Discount Codes', 'This text will display on cart page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300060, 'text', 'Remove', 'Remove', 'This text will display on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300061, 'text', 'Amount Payable', 'Amount Payable', 'This text will display on cart page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300062, 'text', 'Sender', 'Sender', 'This text will display on checkout page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300063, 'text', 'Receiver', 'Receiver', 'This text will display on checkout page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300064, 'text', 'Order Success', 'Thank You for placing your order with us. We\'ll do our best to deliver it to below address.', 'This text will display on Invoice page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300065, 'text', 'Coupon Code Text', 'Enter your coupon code if you have one.', 'This text will display on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300066, 'text', 'Checkout Form Heading', 'Please fill the form to complete your order.', 'This text will display on checkout page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300067, 'text', 'Same Billing and Shipping Address Message', 'Shipping detail same as Billing details.', 'This text will display on checkout page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300068, 'text', 'Different Shipping Address Message', 'Shipping to different address.', 'This text will display on checkout page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300069, 'text', 'Save to address book.', 'Save to address book.', 'This text will display on checkout page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300070, 'text', 'Authorize.Net Redirect Message', 'Please wait, your order is being processed and you will be redirected to the Authorize.Net website.', 'This text will display when user checkout using Authorize.Net Payment method', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300071, 'text', 'Authorize.Net Auto Redirect Message', 'If you are not automatically redirected to authorize.net within 5 seconds...', 'This text will display when user checkout using Authorize.Net Payment method and page is being to auto redirect', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300072, 'text', 'Click Here', 'Click Here', 'General Text required whereever a link is clickable', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300073, 'text', 'Paypal Redirect Message', 'Please wait, your order is being processed and you will be redirected to the paypal website', 'This text will display when user checkout using Paypal Payment method', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300074, 'text', 'Paypal Auto Redirect Message', 'If you are not automatically redirected to paypal within 5 seconds...', 'This text will display when user checkout using Paypal Payment method and page is being to auto redirect', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300075, 'text', 'Forget password Message', 'Please provide your registered email to retrieve your password', 'This text will display on forget password page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300076, 'text', 'Please enter your username', 'Please enter your username', 'This text will display on signup/signin page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300077, 'text', 'Please enter your password', 'Please enter your password', 'This text will display on signup/signin page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300078, 'text', 'Password', 'Password', 'This text will display on signup/signin page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300079, 'text', 'Payment Information', 'Payment Information', 'This text will display on order page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300080, 'text', 'Image', 'Image', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300081, 'text', 'Unit Price', 'Unit Price', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300082, 'text', 'Shipping & Handling', 'Shipping & Handling', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300083, 'text', 'Discount', 'Discount', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300084, 'text', 'Terms and Conditions', 'Terms and Conditions', 'This text will display on order preview page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300085, 'text', 'Terms and Conditions Message', 'I agree to the above terms and conditions.', 'This text will display on order preview page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300086, 'text', 'No Shipping Methods Message', 'Sorry, no quotes are available for this order at this time.', 'This text will display on shipping method page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300087, 'text', 'No Product Found Text', 'Products will be added soon.', 'This text will appear when no product found.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300088, 'text', 'Discount (coupon)', 'Discount (coupon).', 'This text will appear on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300089, 'text', 'Default', 'Default', 'This text will appear instead of default', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300090, 'text', 'Select', 'Select', 'This text will appear with default state of drop down.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300091, 'text', 'All Products', 'All Products', 'This text will appear inside every category to display all products of that category.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300092, 'text', 'Tax', 'Tax', 'This text will appear on cart, my order and order review page to display tax amount.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_messages')} VALUES ( 1, 0, 300093, 'text', 'No Payment Required', 'No Payment Method Required', 'This text will appear on payment page, when payment is not required. ex: when order amount is zero.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100001, 'foreground', 'Product Name', '000000', 'This color will use with product name text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100002, 'foreground', 'Product Description', '000000', 'This color will use with product description text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100003, 'foreground', 'Price', 'EB5946', 'This color will use with Price.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100004, 'foreground', 'Category Heading', '000000', 'This color will use with Category Heading text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100005, 'foreground', 'Sub Category Heading', '000000', 'This color will use with Sub Category Heading text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100006, 'foreground', 'Default Text', '000000', 'This color will use as default text color for all other items.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100007, 'foreground', 'Primary button', '000000', 'This color will use with Primary button text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100008, 'foreground', 'Secondary button', '000000', 'This color will use with Secondary button text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100009, 'foreground', 'Table Primary Row text', '000000', 'This color will use with Table Primary Row text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 100010, 'foreground', 'Table Secondary Row text', '000000', 'This color will use with Table Secondary Row text.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200001, 'background', 'Featured Product List', 'D1D1D1', 'This color will used as background color with listing of featured product.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200002, 'background', 'Slider Menu', 'FFFFFF', 'This color will used as background color of the slider menu panel.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200003, 'background', 'Category Heading', 'FFFFFF', 'This color will used as background color with Category Heading.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200004, 'background', 'Sub Category Heading', 'FFFFFF', 'This color will used as background color with Sub Category Heading.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200005, 'background', 'Default Background', 'FFFFFF', 'This color will used as default background color.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200006, 'background', 'Primary button', 'EB5946', 'This color will used as background color with Primary button.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200007, 'background', 'Secondary button', 'ADADAD', 'This color will used as background color with Secondary button.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200008, 'background', 'Table Primary Row text', 'B5F8FF', 'This color will used as background color with Table Primary Row.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200009, 'background', 'Table Secondary Row text', 'FFA8CB', 'This color will used as background color with Table Secondary Row.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200010, 'background', 'Application Header', 'FFFFFF', 'This color will used as background color of main header of the mobile app.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_colors')} VALUES ( 1, 0, 200011, 'background', 'Product Image Container', 'FFFFFF', 'This color will used as background color of a container having product images.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 300001, 'logo', 'Logo ', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/logo_banner/logo.png', 'Upload your application logo displayed in the app (<b>Recommended Size : 150X50px</b>).', '', 1, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 300002, 'banner', 'Banner ', 'http://125.63.92.59/Mofluid/Production1.14.1/Default_Assets/Android/logo_banner/banner.png', 'Upload your application banner displayed in the app (<b>Recommended Size : 420x260px</b>).', '', 1, 0, 1 ,'','');

INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400003, 'themeicons', 'Menu (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400004, 'themeicons', 'Back (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400005, 'themeicons', 'Cart (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400006, 'themeicons', 'Search (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400007, 'themeicons', 'Delete (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400008, 'themeicons', 'Cross (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidelegant/mofluid_themes_images')} VALUES ( 1, 0, 400009, 'themeicons', 'Cart Empty (150x150)', '', 'leave blank for default theme icons.', '', 0, 0 , 0,'','');
");
$installer->endSetup();
?>
