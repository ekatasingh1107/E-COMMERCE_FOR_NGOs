<?php
class Mofluid_Mofluidpush_IndexController extends Mage_Adminhtml_Controller_Action //Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		
        $this->loadLayout();
        $this->_setActiveMenu('mofluid_menu/mofluid_pushsettings'); 
        $this->_addLeft($this->getLayout()
        ->createBlock('mofluidpush/menu')
        ->setText('<h1>Left Block</h1>'));
        $block = $this->getLayout()
        ->createBlock('mofluidpush/view')
        ->setText('<h1>Main Block</h1>');           
        $this->_addContent($block);
        $this->renderLayout();
        
    }

          
}

