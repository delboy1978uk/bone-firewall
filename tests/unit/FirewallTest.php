<?php

namespace Barnacle\Tests;

use Barnacle\Container;
use Bone\Firewall\FirewallPackage;
use Bone\Firewall\RouteFirewall;
use Bone\Router\Router;
use BoneTest\FakeRequestHandler;
use Codeception\TestCase\Test;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FirewallTest extends Test
{
    /** @var Container */
    protected $container;

    protected function _before()
    {
        $this->container = $c = new Container();
        $c[Router::class] = $this->getMockBuilder(Router::class)->getMock();
//            ->expects($this->once())->method('getRoutes');
    }

    protected function _after()
    {
        unset($this->container);
    }

    public function testFirewall()
    {
        $package = new FirewallPackage();
        $package->addToContainer($this->container);
        $this->assertTrue($this->container->has(RouteFirewall::class));
        /** @var RouteFirewall $firewall */
        $firewall = $this->container->get(RouteFirewall::class);
        $this->assertInstanceOf(RouteFirewall::class, $firewall);
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $handler = new FakeRequestHandler();
        $response = $firewall->process($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}


