<?php

namespace TemplateMonster\LoginPopup\Block\Form;

use \Magento\Customer\Block\Form\Login as BaseLogin;

/**
 * Customer login pop-up block
 */
class Login extends BaseLogin
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