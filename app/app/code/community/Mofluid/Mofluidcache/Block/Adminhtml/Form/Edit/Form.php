<?php

class Mofluid_Mofluidcache_Block_Adminhtml_Form_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Preparing form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'     => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $form->setUseContainer(true);
        $form->setAfterElementHtml('<script>


// here goes your custom Javascript
function saveAndContinueEdit(urlTemplate) {
        var template = new Template(urlTemplate, productTemplateSyntax);
        var url = template.evaluate({tab_id:product_info_tabsJsTabs.activeTab.id});
        productForm.submit(url);
        }
</script>');
        $this->setForm($form);
        return parent::_prepareForm();
    }
}