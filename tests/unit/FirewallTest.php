<?php

namespace Bone\Test;

use Barnacle\Container;
use Bone\Firewall\FirewallPackage;
use Bone\Firewall\RouteFirewall;
use Bone\Router\Router;
use Bone\Test\FakeController;
use Bone\Test\FakeMiddleware;
use Bone\Test\FakeRequestHandler;
use Codeception\Test\Unit;
use League\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FirewallTest extends Unit
{
    /** @var Container */
    protected $container;

    protected function _before()
    {
        $this->container = $c = new Container();
        $router = new Router();
        $router->map('GET', '/computer/says/no', [FakeController::class, 'handle']);
        $router->map('GET', '/computer/says/yes', [FakeController::class, 'handle']);
        $router->map('GET', '/scotland/says/yes', [FakeController::class, 'handle']);
        $router->map('GET', '/everyone/says/yes', [FakeController::class, 'handle']);
        $c[Router::class] = $router;
    }

    protected function _after()
    {
        unset($this->container);
    }

    public function testPackage()
    {
        $package = new FirewallPackage();
        $middleware = $package->getMiddleware($this->container);
        $this->assertCount(1, $middleware);
    }

    public function testFirewall()
    {
        /** @var RouteFirewall $firewall */
        $firewall = new RouteFirewall($this->container);
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $handler = new FakeRequestHandler();
        $response = $firewall->process($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testBlockedRoutes()
    {
        $this->container['blockedRoutes'] = [
            '/computer/says/no',
        ];

        //** @var RouteFirewall $firewall */
        $firewall = new RouteFirewall($this->container);

        $handler = new FakeRequestHandler();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $firewall->process($request, $handler);
        /** @var Router $router */
        $router = $this->container->get(Router::class);
        $routes = $router->getRoutes();
        $this->assertCount(3, $routes);
    }

    public function testMiddleware()
    {
        $this->container['previouslySetMiddleware'] = new FakeMiddleware();
        $this->container['routeMiddleware'] = [
            '/computer/says/yes' => [
                FakeMiddleware::class,
                new FakeMiddleware(),
                'previouslySetMiddleware',
            ],
            '/scotland/says/yes' => FakeMiddleware::class
        ];

        //** @var RouteFirewall $firewall */
        $firewall = new RouteFirewall($this->container);

        $handler = new FakeRequestHandler();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $firewall->process($request, $handler);
        $mirror = new \ReflectionClass(RouteFirewall::class);
        $property = $mirror->getProperty('middlewares');
        $property->setAccessible(true);
        $middlewares = $property->getValue($firewall);
        $this->assertCount(2, $middlewares);
        $this->assertArrayHasKey('/computer/says/yes', $middlewares);
        $this->assertArrayHasKey('/scotland/says/yes', $middlewares);
    }
}


