<?php

class Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Tab_Bannerlist extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Constructor
     */
    public function __construct()
    {
            
        parent::__construct();
        $this->setId('mofluidbanner_banner');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
        $this->setDefaultFilter(array('mofluid_image_type'=> 'banner'));
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false); //delete filter and search button from grid
        $this->setPagerVisibility(false);
    }

    /**
     * adding filter by column
     *
     * @param Varien_Object $column - colum data
     * @return Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Tab_Bannerlist
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
       /* if ($column->getId() == 'in_category') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = array(0);
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('banner_id', array('in'=> $productIds));
            }
            elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('banner_id', array('nin'=> $productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;*/
    }

    /**
     * Prepare grid collection object
     *
     * @return Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Tab_Bannerlist
     */
    protected function _prepareCollection()
    {
       $mofluid_theme_modern_model = Mage::getModel('mofluid_thememofluidmodern/images');
       $mofluid_theme_modern_banner = $mofluid_theme_modern_model->getCollection()->addFieldToFilter('mofluid_theme_id', '2')->addFieldToFilter('mofluid_image_type','banner');
	  $this->setCollection($mofluid_theme_modern_banner);
	  return parent::_prepareCollection();
	}
   
    /**
     * prepare columns
     *
     * @return Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Tab_Bannerlist
     */
    protected function _prepareColumns()
    {
          $form = new Varien_Data_Form();
          $form->setHtmlIdPrefix('_');
          $form->setFieldNameSuffix('mofluidtheme_logobanner');
          $helper = Mage::helper('mofluid_thememofluidmodern');
        
        //creating banner fieldset tab
        $themebanner_fieldset = $form->addFieldset('themebanner', array(
            'legend'       =>'Banner',
            'class'        => 'fieldset-wide'
        ));
         $themebanner_fieldset->addField('mofluid_theme_banner_image_type', 'select', array(
          'label'     => $helper->__('Banner Style'),
          'name'      => 'mofluid_theme_catsimg',
          'required'  => true,
          'value'     => $modern_theme_settings['mofluid_display_catsimg'],
          'after_element_html' => '<br>Enable if you want to display category thumbnail images on listing.For More Detail : <a href="http://mofluid.com/features/" target="_blank">Click Here</a>',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => 'Single',
              ),

              array(
                  'value'     => 1,
                  'label'     => 'Slider',
              ),
          ),
       ));
       
       
       $this->setForm($form);
        $this->addColumn('banner_id', array(
            'header'    => $helper->__('Banner Id'),
            'sortable'  => false,
            'width'     => '10',
            'align'     =>'right',
            'index'     => 'mofluid_image_id'
        ));
       $this->addColumn('mofluid_theme_banner_image_store', array(
            'header'    => $helper->__('Store'),
             'width'     => '120',
             'align'     =>'center',
            'index'     => 'mofluid_store_id',
            'renderer'  => 'Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Renderer_Store'
        ));
        $this->addColumn('mofluid_theme_banner_image_url', array(
            'header'        => $helper->__('Banner '),
            'index'         => 'mofluid_image_value',
            'renderer'  => 'Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Renderer_Store'
        ));
       $this->addColumn('mofluid_theme_banner_image_isdefault', array(
            'header'    => $helper->__('Default'),
             'width'     => '1',
            'index'     => 'mofluid_image_isdefault',
            'renderer'  => 'Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Renderer_Store'
        ));
       $this->addColumn('sort_order', array(
            'header'    => $helper->__('Sort Order'),
             'width'     => '1',
            'index'     => 'mofluid_image_sort_order'
        ));
        $this->addColumn('mofluid_image_action', array(
            'header'    => $helper->__('Frontend Action'),
             'width'     => '1',
            'index'     => 'mofluid_image_action',
             'renderer'  => 'Mofluid_Thememofluidmodern_Block_Adminhtml_Form_Edit_Renderer_Store'
        ));
       
        $this->addColumn('action',
            array(
                'header'    =>  $helper->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => $helper->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
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
      
    }

    /**
     * get selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        
    }

}
