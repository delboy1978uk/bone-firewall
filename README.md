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
And add a list of `blockedRoutes` to your config. As an example here, package `delboy1978uk/bone-user` has an endpoint 
for a visitor to register a user account. If you don't want endpoints like these exposed you can add the path that is 
configured in the package's `addRoutes(Container $c, Router $router)` method that adds the routes, you can use the 
wildcard strings too as shown here. 
```php
<?php

return [
    'blockedRoutes' => [
        '/user/register',
        '/user/lost-password/{email}',
    ],
];
```