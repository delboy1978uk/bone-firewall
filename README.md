# firewall
Firewall package for Bone Mvc Framework
## installation
Use Composer
```
composer require delboy1978uk/bone-firewall
```
## usage
Simply add to the `config/packages.php`
```php
<?php

// use statements here
use Bone\Firewall\Firewall\FirewallPackage;

return [
    'packages' => [
        // packages here...,
        FirewallPackage::class,
    ],
    // ...
];
```