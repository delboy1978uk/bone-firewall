<?php

declare(strict_types=1);

namespace Bone\Firewall;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Mvc\Router;

class FirewallPackage implements RegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        /** @var Router $router */
        $router = $c->get(Router::class);
        $firewall = new RouteFirewall($router);
        $c[RouteFirewall::class] = $firewall;
    }

    /**
     * @return string
     */
    public function getEntityPath(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasEntityPath(): bool
    {
        return false;
    }
}
