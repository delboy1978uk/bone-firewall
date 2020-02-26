# firewall
Firewall package for controlling Bone Framework vendor package routes.
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
#### blocking routes
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
#### adding middleware
Sometimes you might want to add middleware to a route coming from a vendor package, you can do this also by adding the 
following config key. Each key can hold either an actual instance of the middleware, a string representing the 
middleware which would be found in the container, or an array of either if you wish to add multiple middlewares.
```php
return [
    'blockedRoutes' => [
        // etc
    ],
    'routeMiddleware' => [
        '/api/some/endpoint' => SomeMiddleware::class,
        '/api/another/endpoint' => new AwesomeMiddleware(),
        '/api/yet/another/endpoint' => [
            new AwesomeMiddleware(),
            SomeMidlleware::class,
        ],
    ],
];
```