<?php

namespace TemplateMonster\LoginPopup\Block\Form;

use \Magento\Customer\Block\Form\Register as BaseRegister;

/**
 * Customer register pop-up block
 */
class Register extends BaseRegister
{
    /**
     * Prevent parents block from setting page title
     *
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        return $this;
    }
}