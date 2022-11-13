<?php

namespace App\Core;

use App\Core\Attributes\Route;
use App\Core\Enums\HttpMethod;
use App\Exceptions\RouteMethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;
use Illuminate\Container\Container;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function get(string $route, callable|array $action)
    {
        return $this->register(HttpMethod::GET, $route, $action);
    }

    public function post(string $route, callable|array $action)
    {
        return $this->register(HttpMethod::POST, $route, $action);
    }

    public function register(HttpMethod $reqMethod, string $router, callable|array $action): self
    {
        $this->routes[$router][strtolower($reqMethod->value)] = $action;

        return $this;
    }

    public function registerRoutesFromControllerAttributes(\Generator $controllers): self
    {
        foreach ($controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class, \ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    $this->register($route->method, $route->path, [$controller, $method->getName()]);
                }
            }
        }
        return $this;
    }

    public function resolve(string $route, string $reqMethod)
    {
        $action = $this->routes[$route][strtolower($reqMethod)] ?? false;

        if (! $action) {
            if (isset($this->routes[$route])) {
                throw RouteMethodNotAllowedException::create($this->routeMethods($route), $reqMethod);
            }
            throw new RouteNotFoundException();
        }

        if (is_array($action)) {
            $action[0] = $this->container->get($action[0]);
        }

        return call_user_func($action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function routeMethods(string $route): array
    {
        return isset($this->routes[$route]) ? array_keys($this->routes[$route]) : [];
    }
}
