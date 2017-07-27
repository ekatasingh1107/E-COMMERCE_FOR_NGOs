<?php

class Mofluid_Paymentauthorize_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('edit_home_tabs');
        $this->setDestElementId('edit_form');
        $name = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->name;
        $type = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->type;
        $version = Mage::getConfig()->getModuleConfig("Mofluid_Adminmofluid")->version;
        $title = $name.' '.$type.' '.$version;
        if(trim($title) == '' || $title == null) {
            $title = 'Mofluid';
        }
        $this->setTitle(Mage::helper('mofluid_paymentauthorize')->__($title));
    }

    /**
     * add tabs before output
     *
     * @return Mofluid_Paymentauthorize_Block_Adminhtml_Form_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('mofluid_paymentauthorize')->__('Configuration'),
            'title'     => Mage::helper('mofluid_paymentauthorize')->__('Configuration'),
            'content'   => $this->getLayout()->createBlock('mofluid_paymentauthorize/adminhtml_form_edit_tab_general')->toHtml(),
        ));

        /*$product_content = $this->getLayout()->createBlock('mofluid_paymentauthorize/adminhtml_form_edit_tab_product', 'paymentauthorize_products.grid')->toHtml();
        $serialize_block = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer');
        $serialize_block->initSerializerBlock('paymentauthorize_products.grid', 'getSelectedProducts', 'products', 'selected_products');
        $serialize_block->addColumnInputName('position');
        $product_content .= $serialize_block->toHtml();
        $this->addTab('associated_products', array(
            'label'     => Mage::helper('mofluid_paymentauthorize')->__('Products'),
            'title'     => Mage::helper('mofluid_paymentauthorize')->__('Products'),
            'content'   => $product_content
        ));*/

        return parent::_beforeToHtml();
    }

}