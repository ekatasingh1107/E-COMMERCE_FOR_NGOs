<?php

class Mofluid_Paymentcod_Adminhtml_PaymentcodController extends Mage_Adminhtml_Controller_Action
{


    /**
     * View form action
     */
    public function indexAction()
    {
        $this->_registryObject();
        $this->loadLayout();
        $this->_setActiveMenu('mofluid/form');
        $this->_addBreadcrumb(Mage::helper('mofluid_paymentcod')->__('Form'), Mage::helper('mofluid_paymentcod')->__('Form'));
        $this->renderLayout();
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function gridAction()
    {
        $this->_registryObject();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mofluid_paymentcod/adminhtml_form_edit_tab_product')
                ->toHtml()
        );
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function saveAction()
    {
        try 
        {
            $mofluid_payment_post_array = $this->getRequest()->getParam('general'); 
            $model = Mage::getModel('mofluid_paymentcod/paymentcod');	
            if($model != null) {
                $mofluid_pay_insert_data = array();
                $mofluid_pay_insert_data['payment_method_status'] = $mofluid_payment_post_array['mofluid_payment_cod_status'];
                $mofluid_pay_insert_data['payment_method_mode'] = $mofluid_payment_post_array['mofluid_payment_cod_mode'];
                $mofluid_pay_insert_data['payment_method_account_id'] = $mofluid_payment_post_array['mofluid_payment_cod_id'];
                $mofluid_pay_insert_data['payment_method_account_key'] = $mofluid_payment_post_array['mofluid_payment_cod_key'];
                $model->setData($mofluid_pay_insert_data)->setId(1);
        		$model->setCreatedTime(now())->setUpdateTime(now());
        		$model->save();
		    }
		    else {
		        echo "No Model Found"; die;
		    }
		}    
		catch(Exception $ex) {
		    echo $ex->getMessage();
	   }
	   Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_paymentcod')->__('Settings has been saved successfully saved'));
	   Mage::getSingleton('adminhtml/session')->setFormData(true);
       $this->_redirect('*/*/');
       
    }

    /**
     * registry form object
     */
    protected function _registryObject()
    {
//        Mage::register('mofluid_paymentcod', Mage::getModel('mofluid_paymentcod/form'));
    }

}