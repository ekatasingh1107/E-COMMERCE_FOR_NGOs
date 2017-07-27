<?php

class Mofluid_Paymentauthorize_Adminhtml_PaymentauthorizeController extends Mage_Adminhtml_Controller_Action
{


    /**
     * View form action
     */
    public function indexAction()
    {
        $this->_registryObject();
        $this->loadLayout();
        $this->_setActiveMenu('mofluid/form');
        $this->_addBreadcrumb(Mage::helper('mofluid_paymentauthorize')->__('Form'), Mage::helper('mofluid_paymentauthorize')->__('Form'));
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
            $this->getLayout()->createBlock('mofluid_paymentauthorize/adminhtml_form_edit_tab_product')
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
            $model = Mage::getModel('mofluid_paymentauthorize/paymentauthorize');	
            if($model != null) {
                $mofluid_pay_insert_data = array();
                $mofluid_pay_insert_data['payment_method_status'] = $mofluid_payment_post_array['mofluid_payment_authorize_status'];
                $mofluid_pay_insert_data['payment_method_mode'] = $mofluid_payment_post_array['mofluid_payment_authorize_mode'];
               $mofluid_pay_insert_data['payment_method_account_id'] = $mofluid_payment_post_array['mofluid_payment_authorize_id'];
                $mofluid_pay_insert_data['payment_method_account_key'] = $mofluid_payment_post_array['mofluid_payment_authorize_key'];
 
$model->setData($mofluid_pay_insert_data)->setId(2);
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
	   Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_paymentauthorize')->__('Settings has been saved successfully saved'));
	   Mage::getSingleton('adminhtml/session')->setFormData(true);
       $this->_redirect('*/*/');
       /*if ($data = $this->getRequest()->getPost()) {		  			
	  			
			$model = Mage::getModel('web/web');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('web')->__('Dealer was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {*/
					//$this->_redirect('*/*/edit', array('id' => $model->getId()));
					//return;
				//}
				//$this->_redirect('*/*/');
				//return;
            //} catch (Exception $e) {
            //   Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            //    Mage::getSingleton('adminhtml/session')->setFormData($data);
              //  $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                //return;
            //}
        //}
        //Mage::getSingleton('adminhtml/session')->addError(Mage::helper('web')->__('Unable to find Dealer to save'));
        //$this->_redirect('*/*/');
    }

    /**
     * registry form object
     */
    protected function _registryObject()
    {
//        Mage::register('mofluid_paymentauthorize', Mage::getModel('mofluid_paymentauthorize/form'));
    }

}
