<?php

	class Mofluid_Mofluidcache_Adminhtml_MofluidcacheController extends Mage_Adminhtml_Controller_Action
	{


	    /**
	     * View form action
	     */
	    public function indexAction()
	    {
		$this->_registryObject();
		$this->loadLayout();
		$this->_setActiveMenu('mofluid/form');
		$this->_addBreadcrumb(Mage::helper('mofluid_mofluidcache')->__('Form'), Mage::helper('mofluid_mofluidcache')->__('Form'));
		$this->getLayout()->getBlock('head')
		     ->setCanLoadExtJs(true)
		     ->setCanLoadTinyMce(true)
		     ->addItem('js','tiny_mce/tiny_mce.js')
		     ->addItem('js','mage/adminhtml/wysiwyg/tiny_mce/setup.js')
		     ->addJs('mage/adminhtml/browser.js')
		     ->addJs('prototype/window.js')
		     ->addJs('lib/flex.js')
		     ->addJs('mage/adminhtml/flexuploader.js')
		     ->addItem('js_css','prototype/windows/themes/default.css')
		     ->addItem('js_css','prototype/windows/themes/magento.css');
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
		    $this->getLayout()->createBlock('mofluid_mofluidcache/adminhtml_form_edit_tab_product')
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
            $mofluid_mofluidcache_settings_post_array = $this->getRequest()->getParam('general'); 
            $model = Mage::getModel('mofluid_mofluidcache/mofluidcache');	
            if($model != null) {
                $mofluid_mofluidcache_settings_data = array(); 
                $mofluid_mofluidcache_settings_data['mofluid_cs_status'] = $mofluid_mofluidcache_settings_post_array['mofluid_mofluidcache_status'];
                $mofluid_mofluidcache_settings_data['mofluid_cs_accountid'] = $mofluid_mofluidcache_settings_post_array['mofluid_mofluidcache_account_id'];
                $mofluid_mofluidcache_settings_data['mofluid_cs_extras'] = '';
 
                $model->setData($mofluid_mofluidcache_settings_data)->setId(25);
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
	   Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mofluid_mofluidcache')->__('Settings has been saved successfully'));
	   Mage::getSingleton('adminhtml/session')->setFormData(true);
       $this->_redirect('*/*/');
    }
   
    /**
     * registry form object
     */
    protected function _registryObject()
    {
         //Mage::register('mofluid_paymentcod', Mage::getModel('mofluid_paymentcod/form'));
    }
   
}
