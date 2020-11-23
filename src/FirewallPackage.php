<?php declare(strict_types=1);

namespace Bone\Firewall;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Http\GlobalMiddlewareRegistrationInterface;

class FirewallPackage implements RegistrationInterface, GlobalMiddlewareRegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {

    }

    /**
     * @param Container $c
     * @return array
     */
    public function getMiddleware(Container $c): array
    {
        return [
            new RouteFirewall($c),
        ];
    }

    /**
     * @param Container $c
     * @return array
     */
    public function getGlobalMiddleware(Container $c): array
    {
        return [
            RouteFirewall::class
        ];
    }
}
