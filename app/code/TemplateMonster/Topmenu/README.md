## Magento 2 Topmenu Module

Временное решение для реализации выпадающего меню в Magento 2  

### Установка через Composer 

В файл composer.json в корне Magento добавляете: 

В раздел "repositories": 
{
    "type": "vcs",
    "url": "http://products.git.devoffice.com/magento/TemplateMonster_Topmenu.git"
}

В раздел "require":
"templatemonster/topmenu-module": "1.0.*"

"1.0.*" - указывайте верную версию 