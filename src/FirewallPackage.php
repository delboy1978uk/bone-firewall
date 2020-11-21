<?php declare(strict_types=1);

namespace Bone\Firewall;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Http\Middleware\Stack;
use Bone\Http\MiddlewareAwareInterface;

class FirewallPackage implements RegistrationInterface, MiddlewareAwareInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        $firewall = new RouteFirewall($c);
        $c[RouteFirewall::class] = $firewall;
    }

    /**
     * @param Stack $stack
     * @param Container $container
     */
    public function addMiddleware(Stack $stack, Container $c): void
    {
        $firewall = $c->get(RouteFirewall::class);
        $stack->addMiddleWare($firewall);
    }
}
