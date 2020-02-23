<?php declare(strict_types=1);

namespace Bone\Firewall;

use Bone\Mvc\Router;
use League\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteFirewall implements MiddlewareInterface
{
    /** @var Router $router */
    private $router;

    /**
     * RouteFirewall constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routes = $this->router->getRoutes();

        /** @var Route $route */
        foreach ($routes as $route) {
            die(var_dump($route));
        }
    }
}