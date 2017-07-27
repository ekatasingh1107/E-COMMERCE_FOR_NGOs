<?php
$installer = $this;  //Getting Installer Class Object In A Variable
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_core')} (
  `mofluid_theme_id` int(11) unsigned NOT NULL, 
  `mofluid_store_id` int(11) NOT NULL default 1, 
  `mofluid_theme_code` varchar(63) default '',
  `mofluid_theme_title` varchar(63) default '',
  `mofluid_theme_status` int(11) NOT NULL default 1,
  `mofluid_theme_custom_footer` varchar(2048) NOT NULL default '',
  `mofluid_display_catsimg` int(11) NOT NULL default 0,
  `mofluid_theme_display_custom_attribute` int(11) NOT NULL default 0,
  `mofluid_theme_banner_image_type` varchar(63) NOT NULL default '1',
  `google_ios_clientid` varchar(255) default '',
  `google_login` varchar(10) default '',
  `cms_pages` int(11) NOT NULL default 0,
  `about_us` int(11) NOT NULL default 0,
  `term_condition` int(11) NOT NULL default 0,
  `privacy_policy` int(11) NOT NULL default 0,
  `return_privacy_policy` int(11) NOT NULL default 0,
  `tax_flag` int(11) NOT NULL default 0,
   PRIMARY KEY (`mofluid_theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} (
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


CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} (
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

CREATE TABLE IF NOT EXISTS {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} (
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

INSERT INTO {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_core')} (`mofluid_theme_id`, `mofluid_theme_code`, `mofluid_theme_title`, `mofluid_theme_status`,`google_ios_clientid`,`google_login`,`cms_pages`,`about_us`,`term_condition`,`privacy_policy`,`return_privacy_policy`,`tax_flag`) VALUES ( 2, 'modern', 'Modern', 0,'','0','0','','','','','0');

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100001, 'button', 'Home', 'Home', 'This text will displayed at home button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100002, 'button', 'My Account', 'My Account', 'This text will displayed at my account button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100003, 'button', 'Edit Profile', 'Edit Profile', 'This text will displayed at Edit Profile button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100004, 'button', 'My Orders', 'My Orders', 'This text will displayed at My Orders button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100005, 'button', 'Sign In', 'Sign In', 'This text will displayed at Sign In button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100006, 'button', 'Sign Out', 'Sign Out', 'This text will displayed at Sign Out button visible in slider menu', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100007, 'button', 'Login', 'Login', 'This text will displayed at Login button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100008, 'button', 'Proceed', 'Proceed', 'This text will displayed at Proceed button.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100009, 'button', 'Update', 'Update', 'This text will displayed at Update button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100010, 'button', 'Change Password', 'Change Password', 'This text will displayed at Change Password button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100011, 'button', 'Forgot Password', 'Forgot Password', 'This text will displayed at Forgot Password button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100012, 'button', 'Create An Account', 'Create An Account', 'This text will displayed at Create An Account button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100013, 'button', 'Details', 'Details', 'This text will displayed at product detail page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100014, 'button', 'Add to Cart', 'Add to Cart', 'This text will displayed at Add to Cart Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100015, 'button', 'Apply Coupon', 'Apply Coupon', 'This text will displayed at Apply Coupon Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100016, 'button', 'Cancel Coupon', 'Cancel Coupon', 'This text will displayed at Cancel Coupon Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100017, 'button', 'Checkout', 'Checkout', 'This text will displayed at Checkout Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100018, 'button', 'Continue Shopping', 'Continue Shopping', 'This text will displayed at Continue Shopping Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100019, 'button', 'Confirm Proceed', 'Confirm Proceed', 'This text will displayed at Confirm Proceed Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100020, 'button', 'Submit', 'Submit', 'This text will displayed at Submit Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100021, 'button', 'Retrive Your Password', 'Retrive Your Password', 'This text will displayed at Retrive Your Password Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100022, 'button', 'Click Here', 'Click Here', 'This text will displayed at Click Here Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100023, 'button', 'Continue', 'Continue', 'This text will displayed at Continue Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100024, 'button', 'Sign Up Now', 'Sign Up Now', 'This text will displayed at Sign Up Now Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100025, 'button', 'Edit Information', 'Edit Information', 'This text will displayed at Edit Information Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100026, 'button', 'Change Account Password', 'Change Account Password', 'This text will displayed at Change Account Password Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100027, 'button', 'Get Price', 'Get Price', 'This text will displayed on Get Price Button of Product Description Page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100028, 'button', 'Close', 'Close', 'This text will displayed to close popup', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2100029, 'button', 'Update Cart', 'Update Cart', 'This text will displayed to update cart', '', 1);


INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200001, 'alert', 'Username Validation for Blank', 'Username can\'t be left blank.', 'This message will be displayed when username left blank during login or signup', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200002, 'alert', 'Username Validation for Wrong attempt', 'Please provide a valid email address as Username.', 'This message will be displayed when wrong login attempt', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200003, 'alert', 'Customer already exist', 'Customer already exist.', 'This message will be displayed when signup with existing email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200004, 'alert', 'Password Validation for minimum length', 'Password should be atleast 6 characters long.', 'This message will be displayed when password has not atleast 6 characters.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200005, 'alert', 'Invalid Username & password message', 'The username or password you entered is incorrect', 'This message will be displayed when invalid username and password is used', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200006, 'alert', 'Login Success Message', 'Welcome {{username}} you have logged in successfully.', 'This message will be displayed when succesfull login. {{username}} will replace with actual name of the User', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200007, 'alert', 'Server Not Responding', 'Server is busy please try after some time.', 'This message will be displayed when no response from the server during maintainence or site down', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200008, 'alert', 'Sign out Message', 'You have successfully log Out.', 'This message will be displayed when user log out', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200009, 'alert', 'Out of stock message', 'This product is out of stock.', 'This message will be displayed when user try to add out of stock product on cart', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200010, 'alert', 'Country Blank', 'Please enter the country', 'This message will be displayed when country field leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200011, 'alert', 'Firstname Blank', 'Please enter the firstname', 'This message will be displayed when firstname field leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200012, 'alert', 'Firstname Invalid', 'Please enter the valid firstname', 'This message will be displayed when firstname field leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200013, 'alert', 'Lastname Blank', 'Please enter the lastname', 'This message will be displayed when lastname leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200014, 'alert', 'Lastname Invalid', 'Please enter the valid lastname', 'This message will be displayed when lastname leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200015, 'alert', 'Contact no blank', 'Please enter the phone number', 'This message will be displayed when contact number leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200016, 'alert', 'Empty Address', 'Please enter the address', 'This message will be displayed when address leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200017, 'alert', 'Empty City Message', 'Please enter the city', 'This message will be displayed when city leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200018, 'alert', 'Empty Country Message', 'Please enter the country', 'This message will be displayed when country leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200019, 'alert', 'Empty State Message', 'Please enter the state', 'This message will be displayed when state leaves blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200020, 'alert', 'Empty Zipcode Message', 'Please enter the zipcode', 'This message will be displayed when zipcode leaves blank.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200021, 'alert', 'Invalid Address', 'Please enter the valid address', 'This message will be displayed when address is invalid', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200022, 'alert', 'Invalid City Message', 'Please enter the valid city', 'This message will be displayed when city is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200023, 'alert', 'Invalid State Message', 'Please enter the valid state', 'This message will be displayed when state is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200024, 'alert', 'Invalid Country Message', 'Please enter the valid country', 'This message will be displayed when country is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200025, 'alert', 'Invalid Zipcode Message', 'Please enter the valid zipcode', 'This message will be displayed when zipcode is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200026, 'alert', 'Invalid Contact Message', 'Please enter the valid contact number', 'This message will be displayed when contact number is invalid.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200027, 'alert', 'Email Not Registered', 'This email id is not registered with us.', 'This message will be displayed when user try to get forget password link using non existing email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200028, 'alert', 'Empty Email', 'Email Address can\'t be left blank.', 'This message will be displayed when user left email field blank', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200029, 'alert', 'Invalid Email', 'Please provide a valid email address.', 'This message will be displayed when user provide invalid email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200030, 'alert', 'Email Already Registered', 'This email id is already registered with us.', 'This message will be displayed when user provide invalid email', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200031, 'alert', 'Forget Password Action Message', 'You will receive an email with a link to reset your password.', 'This message will be displayed when user successfully get response to reset the password', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200032, 'alert', 'Confirm Password Different', 'Password doesn\'t match.', 'This message will be displayed when confirm password does not match', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200033, 'alert', 'Change Password Success', 'Your password has been changed successfully', 'This message will be displayed when password has been changed successfully.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200034, 'alert', 'Register Successfully Message', 'You have registered successfully.', 'This message will be displayed when user succesfully registered with us.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200035, 'alert', 'Profile address update', 'Default Billing and Shipping Address has been Updated.', 'This message will be displayed when user succesfully updates his address.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200036, 'alert', 'Payment Mode', 'Please select mode of payment.', 'This message will be displayed when user checkout without selecting any payment mode.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200037, 'alert', 'Configurable Options Validation', 'Please Select option from', 'This message will be displayed when user add a product to cart without selecting the required option listed in detail page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200038, 'alert', 'Shipping Mode', 'Please select shipping method.', 'This message will be displayed when user checkout without selecting any shipping mode.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200039, 'alert', 'Item Remove Message', 'Please remove this item', 'This message will be displayed when out of stock product is going for checkout', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200040, 'alert', 'When same product added to cart', 'This product is already added in your cart. Please visit cart to change the quantity of the product.', 'This message will be displayed when same product added to cart', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200041, 'alert', 'When a product removed from cart', '{{product}} has been removed', 'This message will be displyaed when a product is removed from cart. {{product}} will replace with actual product name.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200042, 'alert', 'Quantity of Product increased more than stock', 'This Product is having only {{quantity}} quantities in stock', 'This message will be displayed when quantity of same product increased more than stock. {{quantity}} will replace with actual no of quantity available for the product', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200043, 'alert', 'Product Out of Stock', 'Sorry Product {{product}} is Out Of Stock. Please remove the product from cart.', 'This message will be displayed when out of stock product is going for checkout process from the cart. {{product}} will replace with actual product name.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200044, 'alert', 'Limited Quantity is in stock for Product', 'Sorry for Product {{product}} we have only {{quantity}} quanity in Stock please select less quantity.', 'This message will be displayed when limited quantity of the product is avialable for checkout process from the cart. {{product}} will replace with actual product name and {{quantity}} will replace with actual available quantity.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200045, 'alert', 'Order Success Message', 'The order has been placed successfully', 'This message will be displayed when order is placed succeessfully.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200046, 'alert', 'Failure Message for address save', 'Sorry, Address are not Saved.Please Retry', 'This message will be displyaed when failure occurred during address save.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200047, 'alert', 'Message when empty Search', 'Please provide any keyword for search.', 'This message will be displayed when empty search is performed.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200048, 'alert', 'Coupon Applied', 'Coupon code {{coupon}} was applied.', 'This message will be displayed when coupon is applied successfully. {{coupon}} will replace with actual coupon code', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200049, 'alert', 'Coupon Failed', 'Coupon code {{coupon}} is not valid.', 'This message will be displayed when invalid coupon is applied. {{coupon}} will replace with actual coupon code', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200050, 'alert', 'Coupon Canceled', '{{coupon}} Coupon Canceled', 'This message will be displayed when coupon code is canceled. {{coupon}} will replace with actual coupon code', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200051, 'alert', 'Required Validation', 'Please fill all the required entries', 'This message will be displayed when user skip any required field detail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200052, 'alert', 'Check Email', 'Please check your Email address.', 'This text will appear when forget password or any link is sent to the registered mail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200053, 'alert', 'Try Again', 'There was a temporary error please try again later.', 'This text will appear when the app can not recieve the data from the api due to error or site down.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200054, 'alert', 'Emai id validation for Blank', 'Email id can not be left blank.', 'This message will appear when username left blank during login or signup', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200055, 'alert', 'Username Validation for Wrong attempt', 'Please provide a valid email id as Username.', 'This message will appear when wrong login attempt is made', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200056, 'alert', 'Invalid email id or password message', 'Details you have entered is incorrect', 'This message will appear when invalid Full name, email id or password is used', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200057, 'alert', 'Full Name message', 'Please enter full name including space between first and second name', 'This message will appear when user enter invalid full name', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200058, 'alert', 'Products Out of Stock message', 'Some products in your cart has been out of stock or we do not have required quantity at this time.', 'This text will appear when placing the order and we do not have required quantity available.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200059, 'alert', 'Group Pro Validation', 'Please specify the quantity of product(s).', 'This text will appear when grouped product quantity not selected.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200060, 'alert', 'Billing Address Update', 'Billing Address has been Updated.', 'This text will appear when billing address will update', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2200061, 'alert', 'Shipping Address Update', 'Shipping Address has been Updated.', 'This text will appear when shipping address will update.', '', 1);


INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300001, 'text', 'Display Application Name', '', 'If blank, Name of your application will used', '', 0);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300002, 'text', 'Support Text', 'Need help? 24 X 7 support', 'Used when default footer is selected', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300003, 'text', 'Policy Text', 'Policies', 'Used when default footer is selected', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300004, 'text', 'Shop By Departments', '', 'If blank, text Shop by department will used', '', 0);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300005, 'text', 'Personal Information', 'Personal Information', 'This text will display as a heading on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300006, 'text', 'Billing Address', 'Billing Address', 'This text will display as a heading on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300007, 'text', 'No Default Billing Address Found', 'No Default Billing Address Found', 'This text will display on my profile page when no billing address is set to default', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300008, 'text', 'Shipping Address', 'Shipping Address', ' This text will appear text for Shipping address', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300009, 'text', 'No Default Shipping Address', 'No Default Shipping Address', 'This text will display on my profile page when no shipping address is set to default', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300010, 'text', 'Full Name', 'Full Name', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300011, 'text', 'First Name', 'First Name', 'This text will display on checkout form', '',  1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300012, 'text', 'Last Name', 'Last Name', 'This text will display on checkout form', '',  1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300013, 'text', 'Change Account Password', 'Change Account Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300014, 'text', 'Old Password', 'Old Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300015, 'text', 'New Password', 'New Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300016, 'text', 'Confirm Password', 'Confirm Password', 'This text will display on change password screen', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300017, 'text', 'Address', 'Address', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300018, 'text', 'Member Since', 'Member Since', 'This text will display on my profile page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300019, 'text', 'Edit Information', 'Edit Information', 'This text will display on profile edit page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300020, 'text', 'Email Address', 'Email Address', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300021, 'text', 'Contact Number', 'Contact Number', 'This text will appear text for contact number field', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300022, 'text', 'City', 'City', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300023, 'text', 'State', 'State', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300024, 'text', 'Country', 'Country', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300025, 'text', 'Zipcode', 'Zipcode', 'This text will display on checkout form', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300026, 'text', 'You have no Orders', 'You have no Orders', 'This text will display on myorder page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300027, 'text', 'Total Orders', 'You have total {{totalorder}} Orders', 'This text will display on myorder page. {{totalorder}} will replace with total number of orders. ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300028, 'text', 'Order', 'Order', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300029, 'text', 'Orders', 'Orders', 'This text will display on order list page', '', 1);    
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300030, 'text', 'Order Id', 'Order Id', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300031, 'text', 'Status', 'Status', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300032, 'text', 'Products', 'Products', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300033, 'text', 'Payment Method', 'Payment Method', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300034, 'text', 'Shipping Method', 'Shipping Method', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300035, 'text', 'Total Amount', 'Total Amount', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300036, 'text', 'SKU', 'SKU', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300037, 'text', 'Name', 'Name', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300038, 'text', 'Qty', 'Qty', 'This text will display on order list page', '', 1); 
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300039, 'text', 'Price', 'Price', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300040, 'text', 'Total', 'Total', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300041, 'text', 'Item', 'Item', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300042, 'text', 'Shipping Amount', 'Shipping Amount', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300043, 'text', 'Grand Total', 'Grand Total', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300044, 'text', 'Search', 'Search by Name', 'This text will appear as place holder for search field', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300045, 'text', 'Search Result Text', 'Showing search result for {{serachstring}}', 'This text will display on search page. {{searchstring}} will replace with actual search string', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300046, 'text', 'No Search result found', 'No such product found', 'This text will display on search page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300047, 'text', 'Position (Sort Type)', 'Position', 'This text will display on product listing page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300048, 'text', 'Name (Sort Type)', 'Name', 'This text will display on product listing page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300049, 'text', 'Price (Sort Type)', 'Price', 'This text will display on product listing page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300050, 'text', 'Product Description', 'Product Description', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300051, 'text', 'Description', 'Description', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300052, 'text', 'Availability', 'Availability', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300053, 'text', 'Product SKU', 'Product SKU', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300054, 'text', 'Shipping Charge', 'Shipping Charge', 'This text will display on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300055, 'text', 'In Stock', 'In Stock', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300056, 'text', 'Out of Stock', 'Out of Stock', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300057, 'text', 'Product Options', 'Product Options', 'This text will display on product detail page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300058, 'text', 'Cart Empty Text', 'The Cart is empty now', 'This text will display on cart page when cart is empty.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300059, 'text', 'Discount Codes', 'Discount Codes', ' This text will appear when discount coupon is applied', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300060, 'text', 'Remove', 'Remove', 'This text will display on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300061, 'text', 'Amount Payable', 'Amount Payable', ' This text will appear title text for payment method page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300062, 'text', 'Sender', 'Sender', 'This text will display on checkout page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300063, 'text', 'Receiver', 'Receiver', 'This text will display on checkout page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300064, 'text', 'Order Success', 'Thank You for placing your order with us. We\'ll do our best to deliver it to below address.', 'This text will display on Invoice page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300065, 'text', 'Coupon Code Text', 'Enter your coupon code if you have one.', 'This text will display on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300066, 'text', 'Checkout Form Heading', 'Please fill the form to complete your order.', 'This text will display on checkout page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300067, 'text', 'Billing & Shipping Address', 'Billing & Shipping Address', '  This text will appear when shipping and billing address are same', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300068, 'text', 'Shipping to different address', 'Shipping to different address', ' This text will appear to set different address for billing & shipping address', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300069, 'text', 'Save to address book.', 'Save to address book.', ' This text will appear to save address to address book', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300070, 'text', 'Authorize.Net Redirect Message', 'Please wait, your order is being processed and you will be redirected to the Authorize.Net website.', 'This text will display when user checkout using Authorize.Net Payment method', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300071, 'text', 'Authorize.Net Auto Redirect Message', 'If you are not automatically redirected to authorize.net within 5 seconds...', 'This text will display when user checkout using Authorize.Net Payment method and page is being to auto redirect', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300072, 'text', 'Click Here', 'Click Here', 'General Text required whereever a link is clickable', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300073, 'text', 'Paypal Redirect Message', 'Please wait, your order is being processed and you will be redirected to the paypal website', 'This text will display when user checkout using Paypal Payment method', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300074, 'text', 'Paypal Auto Redirect Message', 'If you are not automatically redirected to paypal within 5 seconds...', 'This text will display when user checkout using Paypal Payment method and page is being to auto redirect', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300075, 'text', 'Forget password Message', 'Please provide your registered email to retrieve your password', 'This text will display on forget password page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300076, 'text', 'Please enter your username', 'Please enter your username', 'This text will display on signup/signin page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300077, 'text', 'Please enter your password', 'Please enter your password', 'This text will display on signup/signin page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300078, 'text', 'Password', 'Password', 'This text will display on signup/signin page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300079, 'text', 'Payment Information', 'Payment Information', 'This text will display on order page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300080, 'text', 'Image', 'Image', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300081, 'text', 'Unit Price', 'Unit Price', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300082, 'text', 'Shipping & Handling', 'Shipping & Handling', ' This text will appear for shipping amount', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300083, 'text', 'Discount', 'Discount', 'This text will display on order list page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300084, 'text', 'Terms and Conditions', 'Terms and Conditions', 'This text will display on order preview page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300085, 'text', 'Terms and Conditions Message', 'I agree to the above terms and conditions.', 'This text will display on order preview page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300086, 'text', 'No Shipping Methods Message', 'Sorry, no quotes are available for this order at this time.', 'This text will display on shipping method page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300087, 'text', 'No Product Found Text', 'Products will be added soon.', 'This text will appear when no product found.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300088, 'text', 'Discount (coupon)', 'Discount (coupon).', 'This text will appear on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300089, 'text', 'Default', 'Default', 'This text will appear instead of default', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300090, 'text', 'Select', 'Select', 'This text will appear with default state of drop down.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300091, 'text', 'All Products', 'All Products', 'This text will appear inside every category to display all products of that category.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300092, 'text', 'Tax', 'Tax', 'This text will appear on cart, my order and order review page to display tax amount.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300093, 'text', 'No Payment Required', 'No Payment Method Required', 'This text will appear on payment page, when payment is not required. ex: when order amount is zero.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300094, 'text', 'Retrive Your Password', 'Retrive Your Password', 'This text will displayed at Retrive Your Password Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300095, 'text', 'Check Email', 'Please check your Email address.', 'This text will appear when forget password or any link is sent to the registered mail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300096, 'text', 'Sign Up ', 'Sign Up ', 'This text will appear at Sign Up button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300097, 'text', 'My Cart ', 'My Cart ', 'This text will appear title text for Cart page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300098, 'text', 'Checkout ', 'Checkout', 'This text will appear at Checkout Button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300099, 'text', 'Continue Shopping ', 'Continue Shopping', 'This text will appear at Continue Shopping Button over whole app', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300100, 'text', 'Add address', 'Add address', ' This text will appear title text for Billing address page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300101, 'text', 'Submit', 'Submit', '  This text will appear to submit address ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300102, 'text', 'Order Summary', 'Order Summary', '   This text will appear for order summary', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300103, 'text', 'Apply Coupon', 'Apply Coupon', '    This text will appear for discount code button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300104, 'text', 'Cancel Coupon', 'Cancel Coupon', '    This text will appear for cancel discount code button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300105, 'text', 'Proceed', 'Proceed', '     This text will appear to proceed user to payment page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300106, 'text', 'Payment Mode', 'Please select mode of payment.', ' This message will appear when user checkout without selecting any payment mode.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300107, 'text', 'Pay', 'Pay', 'This text will appear to user to confirm payment', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300108, 'text', 'Thank You Message for COD mode', 'Thank You for placing order with us. We will do our best to deliver it to below address.', ' This text will appear as thank you message for COD orders', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300109, 'text', 'Order Amount', 'Order Amount', '  This text will appear text for Order Amount', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300110, 'text', 'My Account', 'My Account', '   This text will appear title text for My Account page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300111, 'text', 'Edit', 'Edit', '    This text will appear text for Edit button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300112, 'text', 'Edit Address', 'Edit Address', '     This text will appear title text for Edit address page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300113, 'text', 'Save', 'Save', ' This text will appear on save button of edit address page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300114, 'text', 'Back', 'Back', ' This text will appear on back button of edit address page', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300115, 'text', 'View Details', 'View Order', '  This text will appear to view order details ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300116, 'text', 'Load More', 'Load More', '  This text will appear to view all order placed by user  ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300117, 'text', 'Order Details', 'Order Details', '  This text will appear as title text for Order detail page ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300118, 'text', 'Order Date', 'Order Date', '  This text will appear to display date of placing Order ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300119, 'text', 'Items in your Order', 'Items in your Order', '   This text will appear as title text for items in cart ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300120, 'text', 'Grand Total ( Items)', 'Grand Total ( # Count Items)', 'This text will appear to display grand total with number of items in cart ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300121, 'text', 'View All Orders', 'View All Orders', 'This text will appear to display all order palced by user', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300122, 'text', 'Shop by Category', 'Shop by Category', 'This text will appear for Category heading', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300123, 'text', 'Featured Products', 'Featured Products', 'This text will appear as title for Featured products', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300124, 'text', 'Name (Sort Type A- Z )', 'Name ( A - Z )', 'This text will appear to add text for Sorting type', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300125, 'text', 'Name (Sort Type Z- A )', 'Name ( Z - A )', 'This text will appear to add text for Sorting type', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300126, 'text', 'Price (Sort Type High - Low)', 'Price ( High - Low )', 'This text will appear to add text for Sorting type', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300127, 'text', 'Price (Sort Type Low - High)', 'Price ( Low - High )', 'This text will appear to add text for Sorting type', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300128, 'text', 'Close', 'Close', ' This text will displayed to close popup', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300129, 'text', 'Try Again', 'There was a temporary error please try again later.', ' This text will appear when the app can not recieve the data from the api due to error or site down.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300130, 'text', 'Internet Connection problem', 'Unable to connect to internet', ' This text will appear when app stop responsing due to internet connection error', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300131, 'text', 'Show Password', 'Show Password', ' This text will appear below on password field', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300132, 'text', 'Invalid Quantity', 'Please provide numeric value for the product quantity.', ' This text will appear when invalid quantity value is entered on cart page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300133, 'text', 'New User? Sign Up', 'New User? Sign Up', ' This text will appear on signup page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300134, 'text', 'Already have an account?', 'Already have an account?', ' This text will appear on signup page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300135, 'text', 'Incorrect Old Password', 'Incorrect Old Password', ' This text will appear on change password page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300136, 'text', 'Reorder', 'ReOrder', ' This text will appear on cart page for Reorder button.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300137, 'text', 'Payment Canceled', 'Sorry your payment has been canceled.', ' This text will appear when payment has been canceled.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300138, 'text', 'Message for invalid payment amount', 'Invalid amount for payment processing.', ' This text will appear on payment page when invalid amount.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300139, 'text', 'including_tax', 'Inc. Tax', ' This text will appear on product detail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300140, 'text', 'excluding_tax', 'Ex. Tax', ' This text will appear on product detail.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300141, 'text', 'checkout header text', 'CHECKOUT AS A GUEST OR REGISTER OR LOGIN', 'This text will appear on checkout page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300142, 'text', 'login checkout text', 'Checkout As Guest', ' This text will appear on checkout page for radio button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300143, 'text', 'register login', 'Register Or Login', ' This text will appear on checkout page for radio button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300144, 'text', 'google login text', 'Login With Google', ' This text will appear on checkout page for radio button', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300145, 'text', 'New Products', 'New Products', ' This text will appear on home page.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300146, 'text', 'Push Notification', 'Push Notification', ' This text will appear on home page push notification.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300147, 'text', 'aboutus', 'About Us', ' This text will appear in sidebar.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300148, 'text', 'termcondition', 'Term & Condition', 'This text will appear in sidebar.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300149, 'text', 'privacypolicy', 'Privacy Policy', ' This text will appear in sidebar.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300150, 'text', 'returnpolicy', 'Return Policy', ' This text will appear in sidebar.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300151, 'text', 'Starting At', 'Starting At', ' This text will appear in grouped Products.', '', 1);

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_messages')} VALUES ( 2, 0, 2300152, 'text', 'No Grouped Product', 'No options of this product are available', ' This text will appear in grouped Products Details page.', '', 1);


INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100001, 'foreground', 'Product Name', '#373737', 'This color will appear for product name', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100002, 'foreground', 'Product Description', '#747474', 'This color will appear for product name', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100003, 'foreground', 'Price', '#e70808', 'This color will appear for product price', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100004, 'foreground', 'Special Price', '#747474', 'This color will appear for product special price', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100005, 'foreground', 'Category Heading', '#343434', 'This color will appear with Category Heading text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100006, 'foreground', 'Sub Category Heading', '#343434', 'This color will appear with Sub Category Heading text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100007, 'foreground', 'Default Text', '#373737', 'This color will appear as default text color for all other items.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100008, 'foreground', 'Primary button', '#ffffff', 'This color will appear for all primary buttons such as Add to cart , Checkout , Login , Sign Up, Save & Continue , Proceed , Pay , Save , Retrieve Password , Apply Coupon', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100009, 'foreground', 'Secondary button', '#ffffff', 'This color will use with Secondary button text.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2100010, 'foreground', 'Icons Color', '#ffffff', 'This color will use for footer Icon color.', '', 1);


INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200001, 'background', 'Featured Product List', '#ffffff', 'This color will appear  as background color with listing of featured product.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200002, 'background', 'Slider Menu', '#ffffff', 'This color will appear as background color of the slider menu panel.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200003, 'background', 'Category Heading', '#f8f8f8', 'This color will appear as background color with Category and Featured product heading.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200004, 'background', 'Sub Category Heading', '#ffffff', 'This color will appear as background color with Sub Category Heading.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200005, 'background', 'Default Background', '#ffffff', 'This color will appear as default background color of app', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200006, 'background', 'Primary button', '#ff8a00', 'This color will appear as background coor for  all primary buttons such as Add to cart , Checkout , Login , Sign Up, Save & Continue , Proceed , Pay , Save , Retrieve Password , Apply Coupon', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200007, 'background', 'Secondary button', '#747474', 'This color will appear as background color for all Secondary buttons such as Get Price , Continue Shopping , Cancel , Back , View Details , View All Orders , Load More .', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200008, 'background', 'Application Header', '#ffffff', 'This color will appear as background color of main header of the mobile app', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200009, 'background', 'Product Image Container', '#ffffff', 'This color will used as background color of a container having product images.', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200010, 'background', 'Discount Coupon Container', '#fef7bf', 'This color will used as background color for discount coupon container', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200011, 'background', 'Footer Background Color', '#ff8b01', 'This color will used as background color for ', '', 1);
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_colors')} VALUES ( 2, 0, 2200012, 'background', 'Secondary header background color', '#ff8b01', 'This color will used as background color for search section bar background color ', '', 1);


INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2300001, 'logo', 'Logo ', 'http://54.169.219.183/Mofluid/Production1.17.0/Default_Assets/Android/logo_banner/logo.png', 'Upload your application logo displayed in the app (<b>Recommended Size : 150X50px</b>).', '', 1, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2300002, 'banner', 'Banner ', 'http://54.169.219.183/Mofluid/Production1.17.0/Default_Assets/Android/logo_banner/banner4.png', 'Upload your application banner displayed in the app (<b>Recommended Size : 1024x500px</b>).', '', 1, 0, 1 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2300003, 'banner', 'Banner ', 'http://54.169.219.183/Mofluid/Production1.17.0/Default_Assets/Android/logo_banner/banner5.png', 'Upload your application banner displayed in the app (<b>Recommended Size : 1024x500px</b>).', '', 1, 0, 1 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2300004, 'banner', 'Banner ', 'http://54.169.219.183/Mofluid/Production1.17.0/Default_Assets/Android/logo_banner/banner6.png', 'Upload your application banner displayed in the app (<b>Recommended Size : 1024x500px</b>).', '', 1, 0, 1 ,'','');

INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400003, 'themeicons', 'Menu (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400004, 'themeicons', 'Back (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400005, 'themeicons', 'Cart (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400006, 'themeicons', 'Search (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400007, 'themeicons', 'Delete (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0 ,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400008, 'themeicons', 'Cross (32x32)', '', 'leave blank for default theme icons.', '', 0, 0, 0,'','');
INSERT INTO  {$this->getTable('mofluid_thememofluidmodern/mofluid_themes_images')} VALUES ( 2, 0, 2400009, 'themeicons', 'Cart Empty (150x150)', '', 'leave blank for default theme icons.', '', 0, 0 , 0,'','');
");
$installer->endSetup();
?>
