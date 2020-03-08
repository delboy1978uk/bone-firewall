<?php declare(strict_types=1);

namespace Bone\Firewall;

use Barnacle\Container;
use Barnacle\RegistrationInterface;

class FirewallPackage implements RegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        $firewall = new RouteFirewall($c);
        $c[RouteFirewall::class] = $firewall;
    }
}
