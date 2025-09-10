<?php

declare(strict_types=1);

namespace App\Routing;

use App\Core\Container;
use App\Http\Request;
use App\Http\Response;
use App\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    /** @var array<string, array<int, array{pattern:string, handler:callable|array}>> */
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $pattern = '#^' . preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $path) . '$#';
        $this->routes[$method][] = ['pattern' => $pattern, 'handler' => $handler];
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    $controller = $this->container->get($class);
                    return $controller->$action($request, $params);
                }
                return $handler($request, $params);
            }
        }

        return new Response('Not Found', 404);
    }
}
