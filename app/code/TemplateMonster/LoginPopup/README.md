Description
===========
Magento2 login pop-up implementation.

Install
=======

1. Add repo to composer.json file:
```json
{
    "repositories": [
            {
                "type": "composer",
                "url": "https://repo.magento.com/"
            },
            {
                "type": "vcs",
                "url": "http://products.git.devoffice.com/magento/magento2-login-popup.git"
            }
        ]
}
```

2. Add the module to composer:
```bash
composer require templatemonster/login-popup:dev-master
```

3. Enable the module:
```bash
bin/magento module:enable --clear-static-content TemplateMonster_LoginPopup
bin/magento setup:upgrade
```

Uninstall
=========

1. Disable the module:
```bash
bin/magento module:disable --clear-static-content TemplateMonster_LoginPopup
```

2. Remove the module from composer:
```bash
composer remove templatemonster/login-popup
```