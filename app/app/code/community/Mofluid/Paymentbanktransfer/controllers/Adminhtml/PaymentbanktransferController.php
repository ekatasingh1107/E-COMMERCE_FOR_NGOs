<?php

class Mofluid_Paymentbanktransfer_Adminhtml_PaymentbanktransferController extends Mage_Adminhtml_Controller_Action
{


    /**
     * View form action
     */
    public function indexAction()
    {
        $this->_registryObject();
        $this->loadLayout();
        $this->_setActiveMenu('mofluid/form');
        $this->_addBreadcrumb(Mage::helper('mofluid_paymentbanktransfer')->__('Form'), Mage::helper('mofluid_paymentbanktransfer')->__('Form'));
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
            $this->getLayout()->createBlock('mofluid_paymentbanktransfer/adminhtml_form_edit_tab_product')
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
            $model = Mage::getModel('mofluid_paymentbanktransfer/paymentbanktransfer');	
            if($model != null) {
                $mofluid_pay_insert_data = array();
                $mofluid_pay_insert_data['payment_method_status'] = $mofluid_payment_post_array['mofluid_payment_banktransfer_status'];
                $model->setData($mofluid_pay_insert_data)->setId(5);
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
	   Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_paymentbanktransfer')->__('Settings has been saved successfully saved'));
	   Mage::getSingleton('adminhtml/session')->setFormData(true);
       $this->_redirect('*/*/');
       
    }

    /**
     * registry form object
     */
    protected function _registryObject()
    {
//        Mage::register('mofluid_paymentbanktransfer', Mage::getModel('mofluid_paymentbanktransfer/form'));
    }

}