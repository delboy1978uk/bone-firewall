<?php

declare(strict_types=1);

namespace Bone\Firewall\Firewall;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use League\Route\Router;

class FirewallPackage implements RegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        /** @var Router $router */
        $router = $c->get(Router::class);
//        $router;
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
