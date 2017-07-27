<?php

class Mofluid_Thememofluidelegant_Block_Adminhtml_Form_Edit_Tab_Buttontext extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * prepare form in tab
     */
    protected function _prepareForm()
    {
    
        $helper = Mage::helper('mofluid_thememofluidelegant');
         $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_');
        $form->setFieldNameSuffix('mofluidtheme_button');
    
        $mofluid_theme_elegant_model = Mage::getModel('mofluid_thememofluidelegant/messages');
        $mofluid_theme_elegant = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_theme_id','1')->addFieldToFilter('mofluid_message_type','button');
        $mofluid_theme_elegant_data = $mofluid_theme_elegant->getData();        
        
        //creating button text tab
        $button_fieldset = $form->addFieldset('button', array(
            'legend'       => $helper->__('Button'),
            'class'        => 'fieldset-wide',
            'expanded'  => false,
        ));
           
         foreach($mofluid_theme_elegant_data as $key=>$value) {
            $field_id = strtolower(str_replace(' ', '_', trim($value["mofluid_message_type"].'_'.$value["mofluid_message_id"])));
            $help_text = $value["mofluid_message_helptext"];
            if($value["mofluid_message_helplink"]) {
                $help_text .=  ' For More Detail : <a href="'.$value["mofluid_message_helplink"].'" target="_blank">Click Here</a>';
            }
            $button_fieldset->addField($field_id, 'text', array(
                'name'  => $field_id,
                'label' => $value["mofluid_message_label"],
                'value' => $value["mofluid_message_value"],
                'after_element_html' => $help_text,
                'required' => $value["mofluid_message_isrequired"] ? true : false,
            ));
        }
        
        //Add Proceed Button fieldset Element
       /* $button_fieldset->addField('mofluid_locale_elegant_home', 'text', array(
            'name'         => 'mofluid_locale_elegant_home',
            'label'        => $helper->__('Home'),
            'required'       => true,
            'value' => 'Home',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));*/
        
      /* 
        //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_home', 'text', array(
            'name'         => 'mofluid_locale_elegant_home',
            'label'        => $helper->__('Home'),
            'required'       => true,
            'value' => 'Home',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_myaccount', 'text', array(
            'name'         => 'mofluid_locale_elegant_myaccount',
            'label'        => $helper->__('My Account'),
            'required'       => true,
            'value' => 'My Account',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_editprofile', 'text', array(
            'name'         => 'mofluid_locale_elegant_editprofile',
            'label'        => $helper->__('Edit Profile'),
            'required'       => true,
            'value' => 'Edit Profile',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_myorders', 'text', array(
            'name'         => 'mofluid_locale_elegant_myorders',
            'label'        => $helper->__('My Orders'),
            'required'       => true,
            'value' => 'My Orders',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_signin', 'text', array(
            'name'         => 'mofluid_locale_elegant_signin',
            'label'        => $helper->__('Sign In'),
            'required'       => true,
            'value' => 'Sign In',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_signout', 'text', array(
            'name'         => 'mofluid_locale_elegant_signout',
            'label'        => $helper->__('Sign Out'),
            'required'       => true,
            'value' => 'Sign Out',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
    
       //Add Proceed Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_proceed', 'text', array(
            'name'         => 'mofluid_locale_elegant_proceed',
            'label'        => $helper->__('Proceed'),
            'required'       => true,
            'value' => 'Proceed',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Update Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_update', 'text', array(
            'name'         => 'mofluid_locale_elegant_update',
            'label'        => $helper->__('Update'),
            'required'       => true,
            'value' => 'Update',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
      
        //Add Change Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_change_password', 'text', array(
            'name'         => 'mofluid_locale_elegant_change_password',
            'label'        => $helper->__('Change Password'),
            'required'       => true,
            'value' => 'Change Password',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
       
         //Add Login Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_login', 'text', array(
            'name'         => 'mofluid_locale_elegant_login',
            'label'        => $helper->__('Login'),
            'required'       => true,
            'value' => 'Login',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
      
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_forgot_password', 'text', array(
            'name'         => 'mofluid_locale_elegant_forgot_password',
            'label'        => $helper->__('Forgot Password'),
            'required'       => true,
            'value' => 'Forgot Password',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_create_account', 'text', array(
            'name'         => 'mofluid_locale_elegant_create_account',
            'label'        => $helper->__('Create An Account'),
            'required'       => true,
            'value' => 'Create An Account',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_product_grid_detail', 'text', array(
            'name'         => 'mofluid_locale_elegant_product_grid_detail',
            'label'        => $helper->__('Details on Product Grid'),
            'required'       => true,
            'value' => 'Details',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
      
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_add_cart', 'text', array(
            'name'         => 'mofluid_locale_elegant_add_cart',
            'label'        => $helper->__('Add to Cart'),
            'required'       => true,
            'value' => 'Add to Cart',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));

        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_apply_coupon', 'text', array(
            'name'         => 'mofluid_locale_elegant_apply_coupon',
            'label'        => $helper->__('Apply Coupon'),
            'required'       => true,
            'value' => 'Apply Coupon',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_cancel_coupon', 'text', array(
            'name'         => 'mofluid_locale_elegant_cancel_coupon',
            'label'        => $helper->__('Cancel Coupon'),
            'required'       => true,
            'value' => 'Cancel Coupon',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
       
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_checkout', 'text', array(
            'name'         => 'mofluid_locale_elegant_checkout',
            'label'        => $helper->__('Checkout'),
            'required'       => true,
            'value' => 'Checkout',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_continue_shopping', 'text', array(
            'name'         => 'mofluid_locale_elegant_continue_shopping',
            'label'        => $helper->__('Continue Shopping'),
            'required'       => true,
            'value' => 'Continue Shopping',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_confirm_proceed', 'text', array(
            'name'         => 'mofluid_locale_elegant_confirm_proceed',
            'label'        => $helper->__('Confirm Proceed'),
            'required'       => true,
            'value' => 'Confirm Proceed',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_submit', 'text', array(
            'name'         => 'mofluid_locale_elegant_confirm_proceed',
            'label'        => $helper->__('Submit'),
            'required'       => true,
            'value' => 'Submit',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
         //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_retrieve_password', 'text', array(
            'name'         => 'mofluid_locale_elegant_retrieve_password',
            'label'        => $helper->__('Retrive Your Password'),
            'required'       => true,
            'value' => 'Retrive Your Password',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
         //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_click_here', 'text', array(
            'name'         => 'mofluid_locale_elegant_click_here',
            'label'        => $helper->__('Click Here'),
            'required'       => true,
            'value' => 'Click Here',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
         //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_continue', 'text', array(
            'name'         => 'mofluid_locale_elegant_continue',
            'label'        => $helper->__('Continue'),
            'required'       => true,
            'value' => 'Continue',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
        //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_signup_now', 'text', array(
            'name'         => 'mofluid_locale_elegant_signup_now',
            'label'        => $helper->__('Sign Up Now'),
            'required'       => true,
            'value' => 'Sign Up Now',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
         //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_edit_information', 'text', array(
            'name'         => 'mofluid_locale_elegant_edit_information',
            'label'        => $helper->__('Edit Information'),
            'required'       => true,
            'value' => 'Edit Information',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
         //Add Forgot Password Button fieldset Element
        $button_fieldset->addField('mofluid_locale_elegant_change_account_password', 'text', array(
            'name'         => 'mofluid_locale_elegant_change_account_password',
            'label'        => $helper->__('Change Account Password'),
            'required'       => true,
            'value' => 'Change Account Password',
            'after_element_html' => 'Used in <strong>Product details screen</strong>.<br />this color is used for various product description text like description, sku, stock etc. </u>For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
            'class' => '',
        ));
        
      */
     if (Mage::registry('mofluid_thememofluidelegant')) {
            $form->setValues(Mage::registry('mofluid_thememofluidelegant')->getData());
        }

        $this->setForm($form);
        return parent::_prepareForm();    

        //return parent::_prepareForm();
    }

}
