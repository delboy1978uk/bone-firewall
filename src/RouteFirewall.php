<?php declare(strict_types=1);

namespace Bone\Firewall;

use Barnacle\Container;
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

    /** @var array $blockedRoutes */
    private $blockedRoutes;

    /** @var MiddlewareInterface[] $middlewares */
    private $middlewares;

    /** @var Container $container */
    private $container;

    /**
     * RouteFirewall constructor.
     * @param Container $container
     */
    public function __construct(Container $c)
    {
        /** @var Router $router */
        $router = $c->get(Router::class);
        $blockedRoutes = $c->has('blockedRoutes') ? $c->get('blockedRoutes') : [];
        $middlewares = $c->has('routeMiddleware') ? $c->get('routeMiddleware') : [];
        $this->container = $c;
        $this->router = $router;
        $this->blockedRoutes = $blockedRoutes;
        $this->middlewares = $middlewares;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routes = $this->router->getRoutes();

        /** @var Route $route */
        foreach ($routes as $route) {
            $path = $route->getPath();

            if (in_array($path, $this->blockedRoutes)) {
                $this->router->removeRoute($route);
                break;
            }

            if (array_key_exists($path, $this->middlewares)) {
                $this->handleMiddleware($path, $route);
            }
        }

        return $handler->handle($request);
    }

    private function handleMiddleware(string $path, Route $route)
    {
        $routeMiddleware = $this->middlewares[$path];

        if (is_array($routeMiddleware)) {
            foreach ($routeMiddleware as $middleware) {
                $this->addMiddleware($route, $middleware);
            }
        } else {
            $this->addMiddleware($route, $routeMiddleware);
        }

    }

    /**
     * @param Route $route
     * @param $middleware
     */
    private function addMiddleware(Route $route, $middleware): void
    {
        if ($middleware instanceof MiddlewareInterface) {
            $route->middleware($middleware);
        } elseif (is_string($middleware) && $this->container->has($middleware)) {
            $middleware = $this->container->get($middleware);
            $route->middleware($middleware);
        }
    }
}