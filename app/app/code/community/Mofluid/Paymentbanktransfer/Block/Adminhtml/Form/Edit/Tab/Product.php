<?php

class Mofluid_Paymentbanktransfer_Block_Adminhtml_Form_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('paymentbanktransfer_products');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setDefaultFilter(array('in_category'=> 1));
        $this->setUseAjax(true);
    }

    /**
     * adding filter by column
     *
     * @param Varien_Object $column - colum data
     * @return Mofluid_Paymentbanktransfer_Block_Adminhtml_Form_Edit_Tab_Product
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_category') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = array(0);
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=> $productIds));
            }
            elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=> $productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare grid collection object
     *
     * @return Mofluid_Paymentbanktransfer_Block_Adminhtml_Form_Edit_Tab_Product
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
        // example of how to join your table with values
/*            ->joinField('position',
                'mofluid_paymentbanktransfer/form_product',
                'position',
                'product_id=entity_id',
                'category_id=' . 0,
                'left')
*/
;
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns
     *
     * @return Mofluid_Paymentbanktransfer_Block_Adminhtml_Form_Edit_Tab_Product
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_category', array(
            'header_css_class' => 'a-center',
            'type'             => 'checkbox',
            'name'             => 'in_category',
            'values'           => $this->_getSelectedProducts(),
            'align'            => 'center',
            'index'            => 'entity_id'
        ));
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('mofluid_paymentbanktransfer')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('mofluid_paymentbanktransfer')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('mofluid_paymentbanktransfer')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
        $this->addColumn('price', array(
            'header'        => Mage::helper('mofluid_paymentbanktransfer')->__('Price'),
            'type'          => 'currency',
            'width'         => '1',
            'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'         => 'price'
        ));
        $this->addColumn('position', array(
            'header'    => Mage::helper('mofluid_paymentbanktransfer')->__('Position'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'position',
            'editable'  => true
        ));

        return parent::_prepareColumns();
    }

    /**
     * get URL for Ajax call
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=> true));
    }

    /**
     * get selected products
     *
     * @return array|mixed
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products) && Mage::registry('mofluid_paymentbanktransfer')) {
            return array_keys($this->getSelectedProducts());
        }

        return $products;
    }

    /**
     * get selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $products = array();
        if (Mage::registry('mofluid_paymentbanktransfer')) {
            foreach (Mage::registry('mofluid_paymentbanktransfer')->getProductsPosition() as $id => $pos) {
                $products[$id] = array('position' => $pos);
            }
        }

        return $products;
    }

}